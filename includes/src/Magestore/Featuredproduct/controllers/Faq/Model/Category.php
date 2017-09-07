<?php

class Magestore_Faq_Model_Category extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('faq/category');
    }
	
	public function load($id, $field=null)
	{
		$store_id = $this->getStoreId();

		if(!$store_id)
		{
			return parent::load($id, $field=null);
		}
		
		$cat = Mage::getModel('faq/categorystore')->loadByCatIdStore($id,$store_id);						
		$this->setData($cat->getData());				
		$this->setId($id);
		
		return $this;
	}
	
	public function save()
	{
		if(!$this->getStoreId())
			return parent::save();
		
		$cate_store = Mage::getModel('faq/categorystore')
						->loadByCatIdStore($this->getCategoryId(),$this->getStoreId());
		$id = $cate_store->getId();
		$cate_store->setData($this->getData())
					->setId($id)
					->save();
		return $this;
	}	

	public function getCollection()
	{
		$store_id = $this->getStoreId();
		
		if(!$store_id)
			return parent::getCollection();
		
		$collection = Mage::getResourceModel('faq/categorystore_collection')
						->addFieldToFilter('store_id',$store_id)
						->addFieldToFilter("status",1)
						->setOrder("ordering","ASC")
						->setOrder("name","ASC")						
						;
		
		return $collection;
	}
	
	public function updateUrlKey()
	{	
		$id = $this->getId();
		$store_id = $this->getStoreId();
		if(!$store_id)
		{
			$store_id = 0;
		}
		$url_key = $this->getData('url_key');	
		$url_key .= ".html";	
		$urlrewrite = Mage::getModel("faq/urlrewrite")->load("faq_category/".$store_id ."/".$id,"id_path");
		
		
		$product_id = Mage::getResourceModel("faq/faq")->getFirstProductId();
		$urlrewrite->setData("faq_category/".$store_id ."/".$id,"id_path");
		$urlrewrite->setData("request_path",$this->getData('url_key'));
	    $urlrewrite->setData("store_id",$store_id);
		$urlrewrite->setData("target_path",'faq/index/view/category_id/'. $id );
		$urlrewrite->setData("product_id",$product_id);
			
		try{
		
			$urlrewrite->save();				
		} catch (Exception $e){
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage());	
		}
		
	}
	
	public function deleteUrlKey()
	{
		$id = $this->getId();
		$store_id = $this->getStoreId();
		if(!$store_id)
		{
			$store_id = 0;
		}
		$url_key = $this->getData('url_key');	
		$urlrewrite = Mage::getModel("faq/urlrewrite")->load("faq_category/".$store_id."/".$id,"id_path");
			
		try{
		
			$urlrewrite->delete();				
		} catch (Exception $e){
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage());	
		}
	}
	
	public function deleteAllUrlKey()
	{
		try
		{
			$id = $this->getId();
			$stores = Mage::app()->getStores();
			foreach($stores as $store)
			{
				$urlrewrite = Mage::getModel("faq/urlrewrite")->load("faq_category/".$store->getStoreId()."/".$id,"id_path");	
				$urlrewrite->delete();
			}
		}	 
		catch (Exception $e){
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage());	
		}
			
	}
	
	
	
}