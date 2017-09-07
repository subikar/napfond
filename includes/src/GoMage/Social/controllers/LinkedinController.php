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

require_once (Mage::getBaseDir('lib') . DS . 'GoMage' . DS . 'Linkedin' . DS . 'linkedinoauth.php');

class GoMage_Social_LinkedinController extends GoMage_Social_Controller_Social {
	
	public function getSocialType() {
		return GoMage_Social_Model_Type::LINKEDIN;
	}
	
	public function loginAction() {
		
		if ($this->getSession()->isLoggedIn()) {
			return $this->_redirectUrl();
		}
		
		$connection = new LinkedInOAuth(Mage::getStoreConfig('gomage_social/linkedin/id'), Mage::getStoreConfig('gomage_social/linkedin/secret'));
		
		$callback_params = array('_secure' => true);
		if ($this->getRequest()->getParam('gs_url', '')) {
			$callback_params['gs_url'] = $this->getRequest()->getParam('gs_url');
		}

		$callback_url = Mage::getUrl('gomage_social/linkedin/callback', $callback_params);		
		$request_token = $connection->getRequestToken($callback_url);
	
		switch ($connection->http_code) {
			case 200:
				Mage::getSingleton('core/session')->setData('oauth_token', $request_token['oauth_token']);
				Mage::getSingleton('core/session')->setData('oauth_token_secret', $request_token['oauth_token_secret']);
				  
    			$url = $connection->getAuthorizeURL($request_token['oauth_token']);
				return $this->_redirectUrl($url);
			break;
			default:
				$this->getSession()->addError($this->__('Could not connect to LinkedIn. Refresh the page or try again later.'));				     			 
		}
		
		return $this->_redirectUrl();
		
	}
	
	public function callbackAction(){
		
		$oauth_token = $this->getRequest()->getParam('oauth_token');
		$oauth_verifier = $this->getRequest()->getParam('oauth_verifier');

		if (!$oauth_token || !$oauth_verifier){
			return $this->_redirectUrl();
		}
		
		if ($oauth_token != Mage::getSingleton('core/session')->getData('oauth_token')){
			return $this->_redirectUrl();
		}
		
		$connection = new LinkedInOAuth(Mage::getStoreConfig('gomage_social/linkedin/id'), Mage::getStoreConfig('gomage_social/linkedin/secret'), Mage::getSingleton('core/session')->getData('oauth_token'), Mage::getSingleton('core/session')->getData('oauth_token_secret'));		
		$access_token = $connection->getAccessToken($oauth_verifier); 
		
		Mage::getSingleton('core/session')->unsetData('oauth_token');
		Mage::getSingleton('core/session')->unsetData('oauth_token_secret');
		
		$profile = null;
				
		switch ($connection->http_code) {
			case 200:  				
    			$profile = $connection->get('v1/people/~:(id,first-name,last-name,email-address)', array('format' => 'json'));       				
			break;
			default:
				$this->getSession()->addError($this->__('Could not connect to LinkedIn. Refresh the page or try again later.'));
				return $this->_redirectUrl();				     			 
		}
			
		if ($profile) {
			
			$social_collection = Mage::getModel('gomage_social/entity')
									->getCollection()
									->addFieldToFilter('social_id', $profile->id)
									->addFieldToFilter('type_id', GoMage_Social_Model_Type::LINKEDIN);
						
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
	        									
				$customer = Mage::getModel('customer/customer');
				if (Mage::getSingleton('customer/config_share')->isWebsiteScope()) {
					$customer->setWebsiteId(Mage::app()->getWebsite()->getId());				
				}
								
				$profile->email = $profile->emailAddress;
				$profile->first_name = $profile->firstName; 
				$profile->last_name = $profile->lastName;
								
				$customer->loadByEmail($profile->email);
				
				if (!$customer->getId()){
					$customer = $this->createCustomer($profile);
				}					
				if ($customer && $customer->getId()){						
					$this->createSocial($profile->id, $customer->getId());							
					$this->getSession()->loginById($customer->getId());
				}
								
        	}
		}

		return $this->_redirectUrl();
	}
}