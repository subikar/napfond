<?php
class Gravita_Trending_Model_Mysql4_Trending extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {    
        // Note that the featuredproduct_id refers to the key field in your database table.
        $this->_init('trendingproduct/trendingproduct');
    }
}
