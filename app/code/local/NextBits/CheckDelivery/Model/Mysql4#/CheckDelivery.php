<?php

class NextBits_CheckDelivery_Model_Mysql4_CheckDelivery extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the checkdelivery_id refers to the key field in your database table.
        $this->_init('checkdelivery/checkdelivery', 'checkdelivery_id');
    }
}