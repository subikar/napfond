<?php
class Sparx_Tweets_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function isEnabled(){
		return Mage::getStoreConfig('tweets/general/enable');
	}
	public function isShowIn(){
		return Mage::getStoreConfig('tweets/general/position');		
	}
	public function isEnabledWidgets(){
		return Mage::getStoreConfig('tweets/general/widgets');		
	}
	public function getWidgetScript(){
		return Mage::getStoreConfig('tweets/general/widgets_script');		
	}	
	public function getUsername(){
		return Mage::getStoreConfig('tweets/general/username');
	}
	public function getAccessToken(){
		return Mage::getStoreConfig('tweets/general/access_token');
	}
	public function getAccessTokenSecret(){
		return Mage::getStoreConfig('tweets/general/access_token_secret');
	}
	public function getConsumerKey(){
		return Mage::getStoreConfig('tweets/general/consumer_key');
	}
	public function getConsumerSecrete(){
		return Mage::getStoreConfig('tweets/general/consumer_secret');
	} 
	public function getLimit(){
		return Mage::getStoreConfig('tweets/general/response_limit');
	}
	public function isDisplayPostedTime(){
		return Mage::getStoreConfig('tweets/general/display_posted_time');
	}
	
}
	 
