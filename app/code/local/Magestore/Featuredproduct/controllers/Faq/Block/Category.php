<?php
class Magestore_Faq_Block_Category extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {		
		return parent::_prepareLayout();
    }
    
     public function getFaq()     
     { 
        if (!$this->hasData('faq')) {
            $this->setData('faq', Mage::registry('faq'));
        }
        return $this->getData('faq');
        
    }
	
	public function getCategories()
	{
		$store_id = Mage::helper("faq")->getStoreId();
		$collection = Mage::getModel("faq/category")
					->setStoreId($store_id)
					->getCollection();
		
		return $collection;
	}
}