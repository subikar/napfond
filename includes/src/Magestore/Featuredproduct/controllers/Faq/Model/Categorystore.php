<?php

class Magestore_Faq_Model_Categorystore extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('faq/categorystore');
    }
	
	public function loadByStore($catid,$store_id)
	{
		$collection = $this->getCollection()
							->addFieldToFilter('category_id',$catid)
							->addFieldToFilter('store_id',$store_id)
							->addFieldToFilter('is_applied',array('neq'=>0))
							;
		return $collection->getFirstItem();
	}
	
	public function loadByCatIdStore($catid,$store_id)
	{
		$collection = $this->getCollection()
							->addFieldToFilter('category_id',$catid)
							->addFieldToFilter('store_id',$store_id)
							;
		return $collection->getFirstItem();
	}


	public function updateUrlKey()
	{	
		$id = $this->getCategoryId();
		$url_key = $this->getData('url_key');	
		$url_key .= ".html";	
		$store_id = $this->getStoreId();
		if(!$store_id)
		{
			$store_id = 0;
		}
		
		
		$urlrewrite = Mage::getModel("faq/urlrewrite")->load("faq_category/".$store_id."/".$id,"id_path");
		
		
		
		
		$product_id = Mage::getResourceModel("faq/faq")->getFirstProductId();
		$urlrewrite->setData("id_path","faq_category/".$store_id."/".$id);
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
		$id = $this->getCategoryId();
		$url_key = $this->getData('url_key');	
		$store_id = $this->getStoreId();
		$urlrewrite = Mage::getModel("faq/urlrewrite")->load("faq_category/".$store_id."/".$id,"id_path");
			
		try{
			if($urlrewrite->getId())
			{
				$urlrewrite->delete();				
			}	
		} catch (Exception $e){
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage());	
		}
	}	
}