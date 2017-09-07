<?php
class Magestore_Faq_Block_Overview extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		$breadcrumbs = $this->getLayout()->getBlock('breadcrumbs');
		$breadcrumbs->addCrumb('home', array('label'=>Mage::helper('cms')->__('Home'), 'title'=>Mage::helper('cms')->__('Home Page'), 'link'=>Mage::getBaseUrl()));
		$breadcrumbs->addCrumb('faq', array('label'=>'FAQ', 'title'=>'FAQ', 'link'=>Mage::getUrl("faq")));
		
		return parent::_prepareLayout();
    }
    
    public function getMostFrequently()
	{
		$most_frequently = Mage::getModel("faq/faq")
								->setStoreId($this->getStoreId())
								->getMostFrequently();
		
		return $most_frequently;
	}	
	
	public function getAllCategory()
	{
		
		$categories = Mage::getModel("faq/category")
						->setStoreId($this->getStoreId())
						->getCollection()
					//	->addFieldToFilter("status",1)
					//	->setOrder("ordering","ASC")
					//	->setOrder("name", "ASC")
						;
						
		return $categories;				
	}
	
	public function getStoreId()
	{
		if(!$this->hasData('store_id'))
		{
			$store_id = Mage::app()->getStore()->getId();
			$this->setData('store_id',$store_id);
		}
		return $this->getData('store_id');
	}
}