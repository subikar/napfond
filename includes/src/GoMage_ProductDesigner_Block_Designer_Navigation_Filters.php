<?php
/**
 * GoMage Product Designer Extension
 *
 * @category     Extension
 * @copyright    Copyright (c) 2013 GoMage (http://www.gomage.com)
 * @author       GoMage
 * @license      http://www.gomage.com/license-agreement/  Single domain license
 * @terms of use http://www.gomage.com/terms-of-use/
 * @version      Release: 1.0.0
 * @since        Available since Release 1.0.0
 */

/**
 * Designer navigation filters block
 *
 * @category   GoMage
 * @package    GoMage_ProductDesigner
 * @subpackage Block
 * @author     Roman Bublik <rb@gomage.com>
 */
class GoMage_ProductDesigner_Block_Designer_Navigation_Filters extends Mage_Core_Block_Template
{
    /**
     * Return available filter options
     *
     * @param string $filter Filter Name
     * @return Mage_Catalog_Model_Mysql_Category_Collection|null|Varien_Data_Collection
     */
    public function getAvailableFilterOptions($filter)
    {
        $filters = Mage::getSingleton('gomage_designer/navigation')->getFilterOptions($filter);
        return $filters;
    }

    /**
     * Return available filters
     *
     * @return array
     */
    public function getAvailableFilters()
    {
        $storeId = Mage::app()->getStore()->getId();
        $filters = array();
        $items = Mage::getSingleton('gomage_designer/navigation')->getAvailableFilters();
        foreach ($items as $_code => $item) {
            if (is_object($item)) {
                $filters[$item->getAttributeCode()] = $item->getStoreLabel($storeId);
            } elseif(is_string($item)) {
                $filters[$_code] = $item;
            }
        }

        return $filters;
    }

    /**
     * Is filter option selected
     *
     * @param string $filter Filter Name
     * @param int    $value  Option value
     * @return bool
     */
    public function isFilterOptionSelected($filter, $value)
    {
        return $this->getRequest()->getParam($filter) == $value ? true : false;
    }

    public function filterIsVisible($filer, $options)
    {
        return $filer == 'category' ? count($options) > 0 : count($options) > 1;
    }
}
