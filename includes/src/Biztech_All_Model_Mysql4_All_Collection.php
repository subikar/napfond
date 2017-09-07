<?php

class Biztech_All_Model_Mysql4_All_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('all/all');
    }
}