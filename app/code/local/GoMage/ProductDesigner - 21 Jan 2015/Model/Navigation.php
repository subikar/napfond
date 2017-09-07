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
 * Designer navigation model
 *
 * @category   GoMage
 * @package    GoMage_ProductDesigner
 * @subpackage Model
 * @author     Roman Bublik <rb@gomage.com>
 */
class GoMage_ProductDesigner_Model_Navigation extends Mage_Core_Model_Abstract
{
    protected $_collection = null;
    protected $_additionalFilters = array('category' => 'Category');
    protected $_availableFilters;
    protected $_category;

    /**
     * Prepare product collection
     *
     * @return Mage_Catalog_Model_Resource_Product_Collection
     */
    protected function _prepareProductCollection()
    {
        $category = $this->_getRootCategory();
        $collection = Mage::getModel('catalog/product')->getCollection()
            ->addAttributeToFilter('type_id', array('in' => Mage::helper('gomage_designer')->getAllowedProductTypes()))
            ->addAttributeToFilter('enable_product_designer', 1)
            ->addStoreFilter(Mage::app()->getStore())
            ->addCategoryFilter($category)
            ->addCategoryIds();
        $this->_addFiltersAttributes($collection);
        $this->_addDesignAreaFilter($collection);

        Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($collection);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($collection);
        $collection->getSelect()->group('e.entity_id');

        return $collection;
    }

    /**
     * Return root category
     *
     * @return Mage_Catalog_Model_Category
     */
    protected function _getRootCategory()
    {
        if (is_null($this->_category)) {
            $categoryId = Mage::getStoreConfig('gomage_designer/navigation/category')
                ?: Mage::app()->getStore()->getRootCategoryId();
            $this->_category = Mage::getModel('catalog/category')->load($categoryId);
        }

        return $this->_category;
    }

    /**
     * Return associated product collection
     *
     * @param array $ids Parent Product Ids
     * @return Mage_Catalog_Model_Resource_Product_Collection
     */
    protected function _getAssociatedProductCollection($ids = array())
    {
        $collection = Mage::getResourceModel('gomage_designer/catalog_product_type_configurable_collection');
        $this->_addFiltersAttributes($collection);
        $collection->addCategoryIds();
        if (!empty($ids)) {
            $collection->setProductFilter($ids);
        }
        $this->_addDesignAreaFilter($collection, 'link_table', 'parent_id');
        $collection->getSelect()->group('e.entity_id');

        return $collection;
    }

    /**
     * Add Design area filter
     * @param Mage_Catalog_Model_Resource_Product_Collection $collection Product collection
     * @param string $tableAlias Table Alias
     * @param string $joinField Join Field
     * @return Mage_Catalog_Model_Resource_Product_Collection
     */
    protected function _addDesignAreaFilter($collection, $tableAlias = 'e', $joinField = 'entity_id')
    {
        $storeId = Mage::app()->getStore()->getId();
        $mediaGalleryAttribute = Mage::getSingleton('eav/config')
            ->getAttribute(Mage_Catalog_Model_Product::ENTITY, 'media_gallery');
        if ($mediaGalleryAttribute->getId()) {
            $collection->getSelect()->joinInner(
                array('media_gallery' => $collection->getTable('catalog/product_attribute_media_gallery')),
                "{$tableAlias}.{$joinField} = media_gallery.entity_id AND media_gallery.attribute_id = {$mediaGalleryAttribute->getId()}",
                array()
            )->joinLeft(
                array('media_gallery_value' => $collection->getTable('catalog/product_attribute_media_gallery_value')),
                "media_gallery.value_id = media_gallery_value.value_id AND media_gallery_value.store_id = {$storeId}",
                array()
            )->joinLeft(
                array('media_gallery_value_default' => $collection->getTable('catalog/product_attribute_media_gallery_value')),
                "media_gallery.value_id = media_gallery_value_default.value_id AND media_gallery_value_default.store_id = 0",
                array('media_gallery_value_default.design_area')
            )
            ->where('IFNULL(media_gallery_value.design_area, media_gallery_value_default.design_area) IS NOT NULL');
        }

        return $collection;
    }

    /**
     * Add filter attribute to product collection
     *
     * @param Mage_Catalog_Model_Resource_Product_Collection $collection Product Collection
     * return void
     */
    protected function _addFiltersAttributes($collection)
    {
        foreach ($this->getAvailableFilters() as $_code => $filter) {
            if (is_object($filter)) {
                $collection->addAttributeToSelect($filter->getAttributeCode(), true);
            } elseif (is_string($filter)) {
                $collection->addAttributeToSelect($_code);
            }

        }
    }

    /**
     * Return filter options
     *
     * @param string $filter Filter
     * @return array
     */
    public function getFilterOptions($filter)
    {
        $collection = $this->_prepareProductCollection();
        $ids = $collection->getAllIds();
        $this->applyFilters($collection, $filter);
        $options = array();

        if ($filter == 'category') {
            $categoryIds = array();
            $availableCategories = $this->_getRootCategory()->getAllChildren();
            if (!$availableCategories) {
                return $options;
            }
            $availableCategories = explode(',', $availableCategories);

            foreach ($collection as $_item) {
                $categoryIds = array_merge($categoryIds, $_item->getCategoryIds());
            }
            $associatedProducts = $this->_getAssociatedProductCollection($ids);
            $this->applyFilters($associatedProducts, $filter);
            foreach ($associatedProducts as $_item) {
                $categoryIds = array_merge($categoryIds, $_item->getCategoryIds());
            }
            $categoryIds = array_unique($categoryIds);
            $categoryIds = array_intersect($availableCategories, $categoryIds);

            if (!empty($categoryIds)) {
                $categoryCollection = Mage::getModel('catalog/category')->getCollection();
                $categoryCollection->addFieldToFilter('entity_id', array('in' => $categoryIds))
                    ->addAttributeToSelect('name');
                foreach ($categoryCollection as $_category) {
                    $options[$_category->getId()] = $_category->getName();
                }
            }
        } else {
            foreach ($collection as $_item) {
                if (($value = $_item->getData($filter)) && ($label = $_item->getAttributeText($filter))) {
                    $options[$value] = $label;
                }
            }

            $associatedProducts = $this->_getAssociatedProductCollection($ids);
            $this->applyFilters($associatedProducts, $filter);
            foreach ($associatedProducts as $_item) {
                if (($value = $_item->getData($filter)) && ($label = $_item->getAttributeText($filter))) {
                    $options[$value] = $label;
                }
            }
        }

        return $options;
    }

    /**
     * Return product collection
     *
     * @return Mage_Catalog_Model_Resource_Product_Collection
     */
    public function getProductCollection()
    {
        if (is_null($this->_collection)) {
            $collection = $this->_prepareProductCollection();
            $ids = $collection->getAllIds();

            $associatedProducts = $this->_getAssociatedProductCollection($ids);
            $this->applyFilters($associatedProducts);

            $parentIds = array();
            foreach ($associatedProducts as $_item) {
                $parentIds[] = $_item->getParentId();
            }

            $parentIds = array_unique($parentIds);
            $collection->addAttributeToSelect(Mage::getSingleton('catalog/config')->getProductAttributes())
                ->addMinimalPrice()
                ->addFinalPrice();

            $this->applyFilters($collection);
            if (!empty($parentIds)) {
                $collection->getSelect()->orWhere("e.entity_id IN (?)", $parentIds);
            }

            $this->_collection = $collection;
        }

        return $this->_collection;
    }

    /**
     * Return available filters
     *
     * @return array
     */
    public function getAvailableFilters()
    {
        if (is_null($this->_availableFilters)) {
            $filters = array();
            foreach (array(Mage::getStoreConfig('gomage_designer/navigation/color_attribute'),
                 Mage::getStoreConfig('gomage_designer/navigation/size_attribute')) as $filter) {
                if ($attribute = $this->_getFilterAttribute($filter)) {
                    $filters[$attribute->getAttributeCode()] = $attribute;
                }
            }

            $this->_availableFilters = array_merge($this->getAdditionalFilters(), $filters);
        }

        return $this->_availableFilters;
    }

    /**
     * Return additional filters
     *
     * @return array
     */
    public function getAdditionalFilters()
    {
        return $this->_additionalFilters;
    }

    /**
     * Return filter attribute
     *
     * @param string $code Attribute Code
     * @return bool|Mage_Eav_Model_Attribute
     */
    protected  function _getFilterAttribute($code)
    {
        $attribute = Mage::getSingleton('eav/config')
            ->getAttribute(Mage_Catalog_Model_Product::ENTITY, $code);
        if ($attribute && $attribute->getId()) {
            return $attribute;
        }

        return false;
    }

    /**
     * Apply filters to collection
     *
     * @param Mage_Catalog_Model_Resource_Product_Collection $collection Product Collection
     * @param string $excludeFilter Exclude filter attribute code
     * @return $this
     */
    public function applyFilters($collection, $excludeFilter = null)
    {
        if (is_string($excludeFilter)) {
            $excludeFilter = (array) $excludeFilter;
        }
        $request = Mage::app()->getRequest();
        $filters = $this->getAvailableFilters();
        foreach ($filters as $_code => $_filter) {
            if ($value = $request->getParam($_code)) {
                if ($excludeFilter === null || !in_array($_code, $excludeFilter)) {
                    if (is_object($_filter)) {
                        $this->applyFilter($collection, $_filter->getAttributeCode(), $value);
                    } elseif (is_string($_filter)) {
                        $this->applyFilter($collection, $_code, $value);
                    }
                }
            }
        }

        return $this;
    }

    /**
     * Apply filter to collection
     *
     * @param Mage_Catalog_Model_Resource_Product_Collection $collection Collection
     * @param string                                         $filter     Filter
     * @param int|string                                     $value      Value
     * @return $this
     */
    public function applyFilter($collection, $filter, $value)
    {
        if ($filter == 'category') {
            $category = Mage::getModel('catalog/category')->load((int) $value);
            if ($category && $category->getId()) {
                $collection->addCategoryFilter($category);
            }
        } else {
            $collection->addFieldToFilter($filter, $value);
        }

        return $this;
    }
}
