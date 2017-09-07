<?php
class Gravita_Trending_Model_Trending extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('trendingproduct/trendingproduct');
    }
}