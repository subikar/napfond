<?php

class Magestore_Faq_Model_Mysql4_Faq extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the faq_id refers to the key field in your database table.
        $this->_init('faq/faq', 'faq_id');
    }
	
	public function getFirstProductId()
	{
		$prefix = Mage::helper('faq')->getTablePrefix();			
		
		$select = $this->_getReadAdapter()->select()
					->from(array('eao'=> $prefix .'catalog_product_entity'),'entity_id');
					
		$product_id = $this->_getReadAdapter()->fetchOne($select);
		
		
		return $product_id;	
	}
}