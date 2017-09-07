<?php
class Magestore_Faq_Block_Search extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		$breadcrumbs = $this->getLayout()->getBlock('breadcrumbs');
		$breadcrumbs->addCrumb('home', array('label'=>Mage::helper('cms')->__('Home'), 'title'=>Mage::helper('cms')->__('Home Page'), 'link'=>Mage::getBaseUrl()));
		$breadcrumbs->addCrumb('faq', array('label'=>'FAQ', 'title'=>'FAQ', 'link'=>Mage::getUrl("faq")));
		$breadcrumbs->addCrumb('search', array('label'=>Mage::helper('faq')->__('Search'), 'title'=>Mage::helper('faq')->__('Search'), 'link'=>Mage::getUrl("faq/search/result")));
		return parent::_prepareLayout();
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