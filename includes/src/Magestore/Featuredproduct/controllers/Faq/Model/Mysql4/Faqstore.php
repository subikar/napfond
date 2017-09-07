<?php

class Magestore_Faq_Model_Mysql4_Faqstore extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        $this->_init('faq/faqstore', 'faq_store_id');
    }
	
}