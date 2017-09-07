<?php
/**
 * GoMage Social Connector Extension
 *
 * @category     Extension
 * @copyright    Copyright (c) 2013 GoMage (http://www.gomage.com)
 * @author       GoMage
 * @license      http://www.gomage.com/license-agreement/  Single domain license
 * @terms of use http://www.gomage.com/terms-of-use
 * @version      Release: 1.0
 * @since        Class available since Release 1.0
 */ 

require_once (Mage::getBaseDir('lib') . DS . 'GoMage' . DS . 'Facebook' . DS . 'facebook.php');

class GoMage_Social_FacebookController extends GoMage_Social_Controller_Social {
	
	public function getSocialType(){
		return GoMage_Social_Model_Type::FACEBOOK;
	}
	
	public function loginAction() {
		
		if ($this->getSession()->isLoggedIn()){
			return $this->_redirectUrl();
		}
		
		$facebook = new Facebook(array(
			'appId' => Mage::getStoreConfig('gomage_social/facebook/id'), 
			'secret' => Mage::getStoreConfig('gomage_social/facebook/secret')
		));
		
		$social_id = $facebook->getUser();
		
		if ($social_id) {
			
			$social_collection = Mage::getModel('gomage_social/entity')
									->getCollection()
									->addFieldToFilter('social_id', $social_id)
									->addFieldToFilter('type_id', GoMage_Social_Model_Type::FACEBOOK);
						
			if(Mage::getSingleton('customer/config_share')->isWebsiteScope()) {
            	$social_collection->addFieldToFilter('website_id', Mage::app()->getWebsite()->getId());
        	} 
        	$social = $social_collection->getFirstItem();
        	
        	$customer = null;
			
        	if ($social && $social->getId()){
        		$customer = Mage::getModel('customer/customer');
	        	if (Mage::getSingleton('customer/config_share')->isWebsiteScope()) {
					$customer->setWebsiteId(Mage::app()->getWebsite()->getId());
				}
        		$customer->load($social->getData('customer_id'));
        	}

        	if ($customer && $customer->getId()){
        		 $this->getSession()->loginById($customer->getId()); 
        	} else {
	        	try {
					$profile = $facebook->api('/me');
				}
				catch (FacebookApiException $e) {
					$this->getSession()->addError($e->__toString());
					$profile = null;
				}
				
				if (! is_null($profile)){
					$customer = Mage::getModel('customer/customer');
					if (Mage::getSingleton('customer/config_share')->isWebsiteScope()) {
						$customer->setWebsiteId(Mage::app()->getWebsite()->getId());
					}
					$customer->loadByEmail($profile['email']);
					
					if (!$customer->getId()){
						$customer = $this->createCustomer($profile);
					}					
					if ($customer && $customer->getId()){						
						$this->createSocial($profile['id'], $customer->getId());							
						$this->getSession()->loginById($customer->getId());
					}
				}
				
        	}

		}
		
		return $this->_redirectUrl();

	}
	
}