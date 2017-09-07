<?php

class Magestore_Faq_Model_Faq extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('faq/faq');
    }
	
	public function load($id, $field=null)
	{
		$store_id = $this->getStoreId();
		
		if(!$store_id)
		{
			return parent::load($id, $field=null);
		}
		
		$faq = Mage::getModel('faq/faqstore')->loadByFaqIdStore($id,$store_id);
		
		if($faq->getIsApplied() == '0') //is applied
		{
			$this->setData($faq->getData());
		} elseif($faq->getIsApplied() == '1' // use default value
					&& !Mage::getSingleton('admin/session')->isLoggedIn() // not on admin page
				){ 
			parent::load($id, $field=null);
		} else {
			$this->setData($faq->getData());
		}
		$this->setId($id);
		return $this;
	}
	
	public function save()
	{
		if(!$this->getStoreId())
			return parent::save();
		
		$cate_store = Mage::getModel('faq/faqstore')
						->loadByFaqIdStore($this->getFaqId(),$this->getStoreId());
		$id = $cate_store->getId();
		$cate_store->setData($this->getData())
					->setId($id)
					->save();
		return $this;
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
		$urlrewrite = Mage::getModel("faq/urlrewrite")->load("faq/".$store_id."/".$id,"id_path");
		$product_id = Mage::getResourceModel("faq/faq")->getFirstProductId();
		$urlrewrite->setData("id_path","faq/".$store_id."/".$id);
		$urlrewrite->setData("request_path",$this->getData('url_key'));
		$urlrewrite->setData("target_path",'faq/index/view/id/'. $id );
		$urlrewrite->setData("product_id",$product_id);
		$urlrewrite->setData("store_id",$store_id);
			
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
		$urlrewrite = Mage::getModel("faq/urlrewrite")->load("faq/".$store_id."/".$id,"id_path");
			
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
				$urlrewrite = Mage::getModel("faq/urlrewrite")->load("faq/".$store->getStoreId()."/".$id,"id_path");	
				$urlrewrite->delete();
			}
		}	 
		catch (Exception $e){
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage());	
		}
			
	}
	
	public function getMostFrequently()
	{
		$store_id = $this->getStoreId();
		
		$limit = Mage::getStoreConfig('faq/general/most_frequently_number');
		
		if(!$store_id)
		{
			$collection  = $this->getCollection()
							->addFieldToFilter('most_frequently',1)
							->setOrder('main_table.ordering','ASC')
							->setOrder('main_table.title','ASC')
							->setOrder('update_time','DESC')
			                ->setCurPage(1)
							->setPageSize($limit);
							//->setLimit($limit);
		} else {
			$collection = Mage::getSingleton('faq/faqstore')
							->setStoreId($store_id)		
							->getMostFrequently($limit);
		}
		
		
		
		return $collection;
	}
	
	public function getQuestions($category_id,$limit=0)
	{		
		$store_id = $this->getStoreId();
		
		if(!$store_id)
		{
			$collection  = Mage::getResourceModel('faq/faq_collection')
							->join('category', 'category.category_id=main_table.category_id',array('category_id'=>'category_id'))
							->addFieldToFilter("main_table.status",1)
							->addFieldToFilter("category.status",1)
							->addFieldToFilter('main_table.category_id',$category_id)
							->setOrder('main_table.ordering','ASC')
							->setOrder('main_table.title','ASC')							
							->setOrder('main_table.update_time','DESC')
			                ->setCurPage(1);
			if($limit)
			{
				$collection->setPageSize($limit);
			}						
		} else {
			$collection = Mage::getSingleton('faq/faqstore')
							->setStoreId($store_id)
							->getQuestions($category_id,$limit);
		}		
		return $collection;
	}
	
	public function getSearchResult($keyword)
	{
		$store_id = $this->getStoreId();
		
		if(!$store_id)
		{
			$collection  = Mage::getResourceModel('faq/faq_collection')
							->join('category', 'category.category_id=main_table.category_id',array('category_id'=>'category_id'))
							->addFieldToFilter("main_table.status",1)
							->addFieldToFilter("category.status",1)	
							->setOrder('main_table.ordering','ASC')
							->setOrder('main_table.title','ASC')							
							->setOrder('main_table.update_time','DESC');
			$collection->getSelect()->where("main_table.title like '%".$keyword."%' or main_table.description like '%".$keyword."%'");				
		} else {
			$collection = Mage::getModel('faq/faqstore')
							->setStoreId($store_id)
							->getSearchResult($keyword);
		}
		return $collection;
	}
}