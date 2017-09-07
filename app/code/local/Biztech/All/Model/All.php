<?php

class Biztech_All_Model_All extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('all/all');
    }
}