<?php

class Magestore_Faq_Helper_Data extends Mage_Core_Helper_Abstract
{
	public static function getCategoryOptions1()
	{
		$options = array();
		$collection = Mage::getModel('faq/category')->getCollection();	
		foreach($collection as $category)
		{
			$options[$category->getCategoryId()] = $category->getName();
		}
		return $options;
	}	
	
	public static function getCategoryOptions2($store_id = null)
	{
		$options = array();
		$collection = Mage::getModel('faq/category')
								->setStoreId($store_id)
								->getCollection();	
								
		foreach($collection as $category)
		{
			$option = array();
			$option['label'] = $category->getName();
			$option['value'] = $category->getCategoryId();
			$options[] = $option;
		}
		
		return $options;
	}

	public function getOptionApplied()
	{
		return array(				
				array('value'=>1,'label'=>$this->__('Yes')),
				array('value'=>0,'label'=>$this->__('No')),
				
			);
	}
	
	public function getTablePrefix()
	{
		$tableName = Mage::getResourceModel('faq/faq')->getTable('faq');
		$prefix = substr($tableName,0,strlen($tableName)-3);		
		return $prefix;
	}
	
	public function normalizeUrlKey($urlKey)
	{
		for($i=0;$i<5;$i++)
		{
			$urlKey = str_replace("  "," ",$urlKey);
		}
		$urlKey = trim($urlKey);
		$urlKey = str_replace(" ","-",$urlKey);
		$urlKey = str_replace(",","",$urlKey);
		$urlKey = str_replace("?","",$urlKey);
		$urlKey = str_replace(":","-",$urlKey);
		$urlKey = str_replace("!","-",$urlKey);
		
		$urlKey = str_replace("'","-",$urlKey);
		$urlKey = strtolower($urlKey);
		
		return $urlKey;		
	}
	
	public function getFaqUrl()
	{
		$url = $this->_getUrl("faq", array());

		return $url;			
	}
	
	public function getStoreId()
	{		
		$store_id = Mage::app()->getStore()->getId();		
		
		return $store_id;
	}
	
	
	public function cloneFaqStoreData($faqStore)
	{
		
		$stores = Mage::app()->getStores();
		if(count($stores) == 1)
		{
			foreach($stores as $store)
			{
				$id = $faqStore->getFaqId();
				$store_id = $store->getStoreId();
				$faqStore_new = Mage::getModel("faq/faqstore")->loadByFaqIdStore($id,$store_id);
				$faqStore_new->setTitle($faqStore->getTitle());
				$faqStore_new->setCategoryId($faqStore->getCategoryId());
				$faqStore_new->setDescription($faqStore->getDescription());
				$faqStore_new->setStatus($faqStore->getStatus());
				$faqStore_new->setOrdering($faqStore->getOrdering());
				$faqStore_new->setUrlKey($faqStore->getUrlKey());
				$faqStore_new->setData("most_frequently",$faqStore->getData("most_frequently"));
				
				//print_r($faqStore_new->getData());die();
				
				$faqStore_new->save();
				
				if($faqStore_new->getStatus() == 1)
				{
					$faqStore_new->updateUrlKey();		
				}
				else
				{
					$faqStore_new->deleteUrlKey();		
				}
			}
		}
		else
		{
			$faqStore->save();
			if($faqStore->getStoreId())
			{
				if($faqStore->getStatus() == 1)
				{
					$faqStore->updateUrlKey();		
				}
				else
				{
					$faqStore->deleteUrlKey();		
				}
			}
		}		
	}
	
	public function cloneCategoryStoreData($categoryStore)
	{
		
		$stores = Mage::app()->getStores();
		if(count($stores) == 1)
		{
			foreach($stores as $store)
			{
				$id = $categoryStore->getCategoryId();
				$store_id = $store->getStoreId();
				$categoryStore_new = Mage::getModel("faq/categorystore")->loadByCatIdStore($id,$store_id);
				$categoryStore_new->setName($categoryStore->getName());
				
				$categoryStore_new->setDescription($categoryStore->getDescription());
				$categoryStore_new->setStatus($categoryStore->getStatus());
				$categoryStore_new->setOrdering($categoryStore->getOrdering());
				$categoryStore_new->setUrlKey($categoryStore->getUrlKey());
				
				$categoryStore_new->save();
				
				if($categoryStore_new->getStatus() == 1)
				{
					$categoryStore_new->updateUrlKey();		
				}
				else
				{
					$categoryStore_new->deleteUrlKey();		
				}
			}
		}
		else
		{
			$categoryStore->save();
			if($categoryStore->getStoreId())
			{
				if($categoryStore->getStatus() == 1)
				{
					$categoryStore->updateUrlKey();		
				}
				else
				{
					$categoryStore->deleteUrlKey();		
				}
			}
		}		
	}
	
	
}