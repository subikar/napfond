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
 * Designer cliparts filter block
 *
 * @category   GoMage
 * @package    GoMage_ProductDesigner
 * @subpackage Block
 * @author     Roman Bublik <rb@gomage.com>
 */
class GoMage_ProductDesigner_Block_Designer_Design_Filters extends Mage_Core_Block_Template
{
    /**
     * Return clipart categories collection
     *
     * @return GoMage_ProductDesigner_Model_Mysql4_Clipart_Category_Collection
     */
    public function getCategoriesCollection()
    {
        $category = Mage::getSingleton('gomage_designer/clipart_category');
        $defaultCategoryId = $category->getDefaultCategoryId();

        $categoriesCollection = $category->getCollection()
            ->addVisibleFilter()
            ->addFieldToFilter('parent_id', $defaultCategoryId)
            ->addClipartCountFilter();
        return $categoriesCollection;
    }

    /**
     * Return clipart subcategories collection
     *
     * @return GoMage_ProductDesigner_Model_Mysql4_Clipart_Category_Collection
     */
    public function getSubCategoriesCollection()
    {
        $categoryId = $this->_getSelectedCategoryId();
        $subCategoriesCollection = new Varien_Data_Collection();
        if($categoryId) {
            $category = Mage::getSingleton('gomage_designer/clipart_category')->load($categoryId);
            if ($category->getId()) {
                $subCategoriesCollection = $category->getCollection()
                    ->addVisibleFilter()
                    ->addFieldToFilter('path', array('like' => $category->getPath(). '/%'))
                    ->addClipartCountFilter();
            }

        }

        return $subCategoriesCollection;
    }

    /**
     * Return selected category id
     *
     * @return int
     */
    protected function _getSelectedCategoryId()
    {
        return $this->getRequest()->getParam('mainCategory');
    }

    /**
     * Return selected subcategory Id
     *
     * @return int
     */
    protected function _getSelectedSubcategoryId()
    {
        return $this->getRequest()->getParam('subCategory');
    }

    /**
     * Return entered search tags
     *
     * @return string
     */
    protected function _getSearchTags()
    {
        return strip_tags($this->getRequest()->getParam('tags'));
    }

    public function navigationEnabled()
    {
        return (bool) Mage::getStoreConfig('gomage_designer/design/navigation');
    }

    public function searchEnabled()
    {
        return (bool) Mage::getStoreConfig('gomage_designer/design/search');
    }

    protected function _toHtml()
    {
        if (!$this->navigationEnabled() && !$this->searchEnabled()) {
            return '';
        }
        return parent::_toHtml();
    }
}
