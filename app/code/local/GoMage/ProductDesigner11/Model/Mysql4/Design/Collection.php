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
 * Design collection
 *
 * @category   GoMage
 * @package    GoMage_ProductDesigner
 * @subpackage Model
 * @author     Roman Bublik <rb@gomage.com>
 */
class GoMage_ProductDesigner_Model_Mysql4_Design_Collection
    extends Mage_Core_Model_Mysql4_Collection_Abstract
{

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('gomage_designer/design');
        $this->addFilterToMap('design_id', 'main_table.design_id');
    }

    /**
     * Filter collection by customer
     *
     * @param int $customerId Customer Id
     * @return GoMage_ProductDesigner_Model_Mysql4_Design_Collection
     */
    public function getCustomerDesignCollection($customerId)
    {
        $this->addFieldToFilter('customer_id', $customerId);
        $this->getSelect()->joinInner(
            array('image' => $this->getTable('gomage_designer/design_image')),
            "main_table.design_id = image.design_id",
            array('image' => 'image.image')
        )->group("main_table.design_id");

        return $this;
    }

    /**
     * Add products to collection
     *
     * @return GoMage_ProductDesigner_Model_Mysql4_Design_Collection
     */
    public function addProductsToCollection()
    {
        foreach ($this as $_item) {
            $productIds[$_item->getProductId()] = $_item->getProductId();
        }

        $productCollection = $this->_getProductCollection($productIds);

        foreach ($this as $_item) {
            $product = $productCollection->getItemById($_item->getProductId());
            $_item->setProduct($product);
        }

        return $this;
    }

    /**
     * Return product collection
     *
     * @param int|array $productIds Product Ids
     * @return Mage_Catalog_Model_Resource_Product_Collection
     */
    protected function _getProductCollection($productIds)
    {
        if (!is_array($productIds)) {
            $productIds = (array) $productIds;
        }
        $collection = Mage::getModel('catalog/product')->getCollection();
        $collection
            ->addAttributeToSelect(Mage::getSingleton('catalog/config')->getProductAttributes())
            ->addIdFilter($productIds)
            ->addUrlRewrite();
        return $collection;
    }

    /**
     * Return count sql select
     *
     * @return Varien_Db_Select
     */
    public function getSelectCountSql()
    {
        $select = clone $this->getSelect();
        $select->reset(Zend_Db_Select::LIMIT_COUNT);
        $select->reset(Zend_Db_Select::LIMIT_OFFSET);

        $countSelect = clone $select;
        $countSelect->reset();
        $countSelect->from(array('count_table' => $select), 'COUNT(*)');
        return $countSelect;
    }
}