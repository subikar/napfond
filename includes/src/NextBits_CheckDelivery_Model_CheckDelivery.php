<?php

class NextBits_CheckDelivery_Model_CheckDelivery extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('checkdelivery/checkdelivery');
    }
}