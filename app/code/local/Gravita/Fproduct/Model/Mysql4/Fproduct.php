<?php
class Gravita_Featuredproduct_Model_Mysql4_Featuredproduct extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {    
        // Note that the featuredproduct_id refers to the key field in your database table.
        $this->_init('featuredproduct/featuredproduct');
    }
}
