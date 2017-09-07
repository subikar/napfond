<?php
class Magestore_Faq_Block_View extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		$category_id = Mage::registry("category_id");
		$faq_id = Mage::registry("faq_id");
		
		$category = Mage::getModel("faq/category")
						->setStoreId($this->getStoreId())
						->load($category_id);
		//echo("category_id=".$category_id);				
				
		
		$base_url = Mage::getBaseUrl();
		$category_view_url = $base_url . $category->getUrlKey();
	
		$breadcrumbs = $this->getLayout()->getBlock('breadcrumbs');
		$breadcrumbs->addCrumb('home', array('label'=>Mage::helper('cms')->__('Home'), 'title'=>Mage::helper('cms')->__('Home Page'), 'link'=>Mage::getBaseUrl()));
		$breadcrumbs->addCrumb('faq', array('label'=>'FAQ', 'title'=>'FAQ', 'link'=>Mage::getUrl("faq")));
		$breadcrumbs->addCrumb('category', array('label'=>$category->getName(), 'title'=>$category->getName(), 'link'=>$category_view_url));
		
		$headBlock = $this->getLayout()->getBlock('head');
		
		$page_title = $category->getName();
		
		if($faq_id)
		{
			
			$faq  = Mage::getModel('faq/faq')
					->setStoreId($this->getRequest()->getParam('store'))
					->load($faq_id);
		
			$breadcrumbs->addCrumb('view_question', array('label'=>$faq->getTitle(), 'title'=>$faq->getTitle(), 'link'=>null));
			
			$page_title .= " - ".$faq->getTitle();
		}
		
		$this->page_title = $page_title;
		$headBlock->setTitle($page_title);
		
		
		
		
		return parent::_prepareLayout();
    }
    
	public function getPageTitle()
	{
		return $this->page_title;
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
	
	public function getFaq()
	{
		$faq_id = Mage::registry("faq_id");

		$faq  = Mage::getModel('faq/faq')
					->setStoreId($this->getStoreId())
					->load($faq_id);
		if($faq->getStatus())
		{
			return $faq;
		}
		return null;	
	}
}