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

require_once (Mage::getBaseDir('lib') . DS . 'GoMage' . DS . 'Google' . DS . 'Google_Client.php');
require_once (Mage::getBaseDir('lib') . DS . 'GoMage' . DS . 'Google' . DS . 'contrib' . DS . 'Google_Oauth2Service.php');
require_once (Mage::getBaseDir('lib') . DS . 'GoMage' . DS . 'Google' . DS . 'contrib' . DS . 'Google_PlusService.php');

class GoMage_Social_GoogleController extends GoMage_Social_Controller_Social {
	
	public function getSocialType(){
		return GoMage_Social_Model_Type::GOOGLE;
	}
	
	private function getGoogleClient(){
		
		$client = new Google_Client();
		$client->setApplicationName($this->__('Login with Google'));
		$client->setClientId(Mage::getStoreConfig('gomage_social/google/id'));
		$client->setClientSecret(Mage::getStoreConfig('gomage_social/google/secret'));
		
		if ($this->getRequest()->getParam('google_plus', 0) == 1) {
			$client->setRedirectUri('postmessage');
		}else {
			$callback_params = array('_secure' => true);		
			$callback_url = Mage::getUrl('gomage_social/google/callback', $callback_params);
			$client->setRedirectUri($callback_url);
		}	
		
		$client->setDeveloperKey(Mage::getStoreConfig('gomage_social/google/api'));
		
		return $client;
	}
	
	public function loginAction() {
		
		if ($this->getSession()->isLoggedIn()) {
			return $this->_redirectUrl();
		}

		$client = $this->getGoogleClient();
				
		$google_oauthV2 = new Google_Oauth2Service($client);		
		$auth_url = $client->createAuthUrl();
		
		if ($this->getRequest()->getParam('gs_url', '')) {
			Mage::getSingleton('core/session')->setData('gs_url', $this->getRequest()->getParam('gs_url'));
		}

		return $this->_redirectUrl($auth_url);
		
	}
	
	
	public function callbackAction(){
		
		$code = $this->getRequest()->getParam('code');
		
		if ($code){

			$client = $this->getGoogleClient();		
					
			$google_oauthV2 = new Google_Oauth2Service($client);
			
			if ($this->getRequest()->getParam('google_plus', 0) == 1) {
				$plus = new Google_PlusService($client);
			}
			
			$client->authenticate($code);
			
			if ($client->getAccessToken()){								
				
					$profile = $google_oauthV2->userinfo->get();
					
					if ($profile && is_array($profile) && isset($profile['id'])) {
					
						$social_collection = Mage::getModel('gomage_social/entity')
												->getCollection()
												->addFieldToFilter('social_id', $profile['id'])
												->addFieldToFilter('type_id', GoMage_Social_Model_Type::GOOGLE);
									
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
							$customer->loadByEmail($profile['email']);
							
							if (!$customer->getId()){
								$profile['first_name'] = $profile['given_name'];
								$profile['last_name'] = $profile['family_name'];
								$customer = $this->createCustomer($profile);
							}					
							if ($customer && $customer->getId()){						
								$this->createSocial($profile['id'], $customer->getId());							
								$this->getSession()->loginById($customer->getId());
							}							
							
			        	}				        	
				
					}
												
			}else{
				$this->getSession()->addError($this->__('Could not connect to Google. Refresh the page or try again later.'));
			}
					
		}else{
			$this->getSession()->addError($this->__('Could not connect to Google. Refresh the page or try again later.'));
		}
		
		if ($this->getRequest()->getParam('google_plus', 0) == 1) {

			$result = array();
			$result['redirect'] = $this->_getRedirectUrl();			
			$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
			
		}else{		
			
			return $this->_redirectUrl();
			
		}
		
	}
	
	
}