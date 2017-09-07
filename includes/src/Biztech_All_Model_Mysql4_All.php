<?php

class Biztech_All_Model_Mysql4_All extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the all_id refers to the key field in your database table.
        $this->_init('all/all', 'all_id');
    }
}