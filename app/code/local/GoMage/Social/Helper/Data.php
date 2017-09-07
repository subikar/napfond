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

class GoMage_Social_Helper_Data extends Mage_Core_Helper_Abstract {
	
	public function isFBActive() {
		return Mage::getStoreConfig('gomage_social/facebook/enable') && Mage::getStoreConfig('gomage_social/facebook/id') && Mage::getStoreConfig('gomage_social/facebook/secret');
	}
	
	public function isGActive() {
		return Mage::getStoreConfig('gomage_social/google/enable') && Mage::getStoreConfig('gomage_social/google/id') && Mage::getStoreConfig('gomage_social/google/secret') && Mage::getStoreConfig('gomage_social/google/api');
	}
	
	public function isLIActive() {
		return Mage::getStoreConfig('gomage_social/linkedin/enable') && Mage::getStoreConfig('gomage_social/linkedin/id') && Mage::getStoreConfig('gomage_social/linkedin/secret');
	}
	
	public function isActive() {
		return Mage::getStoreConfig('gomage_social/general/enable') && ($this->isFBActive() || $this->isGActive() || $this->isLIActive());
	}
	
	public function getServices($place = '') {
		
		$result = array();
		
		if (! $place) {
			return $result;
		}
		
		$selected_services = Mage::getStoreConfig('gomage_social/general/' . $place);
		
		$selected_services = explode(',', $selected_services);
		
		if ($this->isFBActive() && in_array(GoMage_Social_Model_Type::FACEBOOK, $selected_services)) {
			$result[GoMage_Social_Model_Type::FACEBOOK] = Mage::getStoreConfig('gomage_social/facebook/order');
		}
		if ($this->isGActive() && in_array(GoMage_Social_Model_Type::GOOGLE, $selected_services)) {
			$result[GoMage_Social_Model_Type::GOOGLE] = Mage::getStoreConfig('gomage_social/google/order');
		}
		if ($this->isLIActive() && in_array(GoMage_Social_Model_Type::LINKEDIN, $selected_services)) {
			$result[GoMage_Social_Model_Type::LINKEDIN] = Mage::getStoreConfig('gomage_social/linkedin/order');
		}
		
		natcasesort($result);
		
		return $result;
	}
	
	public function getAllStoreDomains() {
		
		$domains = array();
		
		foreach (Mage::app()->getWebsites() as $website) {
			
			$url = $website->getConfig('web/unsecure/base_url');
			
			if ($domain = trim(preg_replace('/^.*?\\/\\/(.*)?\\//', '$1', $url))) {
				$domains[] = $domain;
			}
			
			$url = $website->getConfig('web/secure/base_url');
			
			if ($domain = trim(preg_replace('/^.*?\\/\\/(.*)?\\//', '$1', $url))) {
				$domains[] = $domain;
			}
		}
		return array_unique($domains);
	
	}
	
	public function setInformation() {
		
		$value = "";
		
		try {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, sprintf('https://www.gomage.com/index.php/gomage_downloadable/key/getinformation'));
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, 'sku=social-connector&domains=' . urlencode(implode(',', $this->getAllStoreDomains())) . '&ver=' . urlencode('1.0'));
			curl_setopt($ch, CURLOPT_TIMEOUT, 30);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			
			$value = curl_exec($ch);
		}
		catch (Exception $e) {
		}
		
		if ($value && ($value != Mage::getStoreConfig('gomage_social/information/text'))) {
			Mage::getModel('core/config')->saveConfig('gomage_social/information/text', $value);
			Mage::getConfig()->reinit();
			Mage::app()->reinitStores();
		}
	
	}
	
	public function notify(){
    	
    	$frequency = intval(Mage::app()->loadCache('gomage_notifications_frequency')); 
    	if (!$frequency){
    		$frequency = 24;
    	}
    	$last_update = intval(Mage::app()->loadCache('gomage_notifications_last_update')); 
    	
    	if (($frequency * 60 * 60 + $last_update) > time()) {
            return false;
        }                      

        $timestamp = $last_update;
        if (!$timestamp){
        	$timestamp = time();
        }
        
        try{
	        $ch = curl_init();
	        curl_setopt($ch, CURLOPT_URL, sprintf('https://www.gomage.com/index.php/gomage_notification/index/data'));
	        curl_setopt($ch, CURLOPT_POST, true);
	        curl_setopt($ch, CURLOPT_POSTFIELDS, 'sku=social-connector&timestamp='.$timestamp.'&ver='.urlencode('1.0'));
	        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	        
	        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	        
	        $content = curl_exec($ch);
	        
	        $result	= Zend_Json::decode($content);
	                                
	        if ($result && isset($result['frequency']) && ($result['frequency'] != $frequency)){
	        	Mage::app()->saveCache($result['frequency'], 'gomage_notifications_frequency'); 
	        }
	        
	    	if ($result && isset($result['data'])){
	        	if (!empty($result['data'])){	
	        		Mage::getModel('adminnotification/inbox')->parse($result['data']); 
	        	} 
	        }
        } catch (Exception $e){}    
        
        Mage::app()->saveCache(time(), 'gomage_notifications_last_update');
        
    }
	

}
	 