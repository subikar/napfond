<?php

class Magestore_Faq_Model_Faqstore extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('faq/faqstore');
    }
	
	public function loadByStore($faqid,$store_id)
	{
		$collection = $this->getCollection()
							->addFieldToFilter('faq_id',$faqid)
							->addFieldToFilter('store_id',$store_id)
							->addFieldToFilter('status',1)
							;
		return $collection->getFirstItem();
	}
	
	public function loadByFaqIdStore($faqid,$store_id)
	{
		$collection = $this->getCollection()
							->addFieldToFilter('faq_id',$faqid)
							->addFieldToFilter('store_id',$store_id)
							;
		return $collection->getFirstItem();
	}
	
	public function getJoinedCollection($status=1)
	{
		$store_id = $this->getStoreId();
		$category_table = Mage::getResourceModel('faq/faq')
							->getTable('categorystore');
							
							
		$collection  = $this->getCollection();
		$collection->getSelect()
		
						->join(array("category_table"=>$category_table),'category_table.category_id=main_table.category_id',
									array('category_id'=>'category_id',
										'cat_is_applied'=>'is_applied',
										'cat_store_id'=>'store_id',
										'cat_status'=>'status',
									)
								);
		$collection						
						->addFieldToFilter('main_table.status',$status)
						->addFieldToFilter('category_table.status',$status)
						->addFieldToFilter('main_table.status',1)
						->addFieldToFilter('category_table.status',1)
						->addFieldToFilter('main_table.store_id',$store_id)
						->addFieldToFilter('category_table.store_id',$store_id)
						->setOrder('main_table.ordering','ASC')
						->setOrder('main_table.title','ASC')
						->setOrder('main_table.update_time','DESC')
						;
		
		//$collection->printlogquery(true);die();
		
		return $collection;		
	}
	
	public function getMostFrequently($limit)
	{
		$collection  = $this->getJoinedCollection()					
							->addFieldToFilter('main_table.most_frequently',1)
			                ->setCurPage(1)
							->setPageSize($limit)
							;
		return $this->_prepareLoadData($collection);		
	}
	
	public function getQuestions($category_id,$limit = null)
	{
		$collection  = $this->getJoinedCollection()					
						->addFieldToFilter('main_table.category_id',$category_id)		
						->setCurPage(1)
						;
		if($limit)
		{
			$collection->setPageSize($limit);
		}								
		return $this->_prepareLoadData($collection);
	}							
	
	public function getSearchResult($keyword)
	{
		$collection  = $this->getJoinedCollection()
							;
						
		$collection->getSelect()->where("main_table.title like '%".$keyword."%' or main_table.description like '%".$keyword."%'");				
		 //$collection->printlogquery(true);die();
		return $collection;
	}

	protected function _prepareLoadData($collection)
	{
		
		return $collection;				
	}
	
	public function updateUrlKey()
	{	
		
		$id = $this->getFaqId();
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
		$urlrewrite->setData("store_id",$store_id);
		$urlrewrite->setData("request_path",$this->getData('url_key'));
		$urlrewrite->setData("target_path",'faq/index/view/id/'. $id );
		$urlrewrite->setData("product_id",$product_id);
		
		
		
		try{
			$urlrewrite->save();				
		} catch (Exception $e){
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage());	
		}
		
	}
	
	public function deleteUrlKey()
	{
		$id = $this->getFaqId();
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
	
}