<?php
class Magestore_Faq_SearchController extends Mage_Core_Controller_Front_Action
{
    public function resultAction()
    {  		
		$store_id = Mage::app()->getStore()->getId();
		$keywords = $this->getRequest()->getParam("keyword","");
		$questions = Mage::getModel("faq/faq")
							->setStoreId($store_id)
							->getSearchResult($keywords);
		Mage::register("questions",$questions);
		Mage::register("keywords",$keywords);
		
		$this->loadLayout();     
		$this->renderLayout();
    }
}