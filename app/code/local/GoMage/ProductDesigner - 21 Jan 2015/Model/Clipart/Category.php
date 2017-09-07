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
 * Clipart category model
 *
 * @category   GoMage
 * @package    GoMage_ProductDesigner
 * @subpackage Model
 * @author     Roman Bublik <rb@gomage.com>
 */
class GoMage_ProductDesigner_Model_Clipart_Category extends Mage_Core_Model_Abstract {

    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY                = 'clipart_category';

    /**
     * Model cache tag
     */
    const CACHE_TAG             = 'designer_cliparts_category';

    /**
     * Model cache tag for clear cache in after save and after delete
     */
    protected $_cacheTag        = self::CACHE_TAG;

    /**
     * Root category Id
     */
    const TREE_ROOT_ID          = 1;

    protected $_defaultCategory;

    protected $parentCategory;

    protected function _construct()
    {
        $this->_init('gomage_designer/clipart_category');
    }

    public function getDefaultCategory()
    {
        if(!$this->_defaultCategory) {
            $category = new self;
            $defaultCategory = $category->load(self::TREE_ROOT_ID);
            $this->_defaultCategory = $defaultCategory;
        }
        return $this->_defaultCategory;
    }

    public function getDefaultCategoryId()
    {
        if($this->getDefaultCategory()) {
            return $this->getDefaultCategory()->getId();
        }
    }

    public function getParentCategory()
    {
        if(is_null($this->parentCategory)) {
            if ($this->getId() == $this->getDefaultCategoryId()) {
                return false;
            } elseif ($this->getParentId() == $this->getDefaultCategoryId()) {
                $parentCategory = $this->getDefaultCategory();
            } else {
                if($this->getParentId()) {
                    $category = new self;
                    $parentCategory = $category->load($this->getParentId());
                }
            }
            $this->parentCategory = $parentCategory;
        }
        return $this->parentCategory;
    }

    public function getCategoryById($id)
    {
        $category = new self;
        $category = $category->load($id);
        return $category;
    }

    protected function _beforeSave()
    {
        if ($this->hasData('category_id') && $this->getData('category_id') == null) {
            $this->unsetData('category_id');
        }

        return parent::_beforeSave();
    }

    protected function _afterSave()
    {
        $parentCategory = $this->getParentCategory();
        if($parentCategory && $parentCategory->getPath()) {
            $this->setPath($parentCategory->getPath() . '/'. $this->getId());
            $this->setLevel(count(explode('/', $parentCategory->getPath())));
            $this->getResource()->save($this);
        }

        return parent::_afterSave();
    }

    public function validate()
    {
        $data = $this->getData();
        $errorMessage = 'Not valid category data';
        $error = false;

        if(!Zend_Validate::is(trim($data['parent_id']) , 'NotEmpty')) {
            $error = true;
            $errorMessage = 'No parent ID';
        }
        if(!Zend_Validate::is(trim($data['name']) , 'NotEmpty')) {
            $error = true;
            $errorMessage = 'No Name';
        }
        if(!Zend_Validate::is(trim($data['is_active']) , 'NotEmpty')) {
            $error = true;
            $errorMessage = 'No is_active';
        }

        if($error) {
            Mage::throwException($errorMessage);
        }
        return true;
    }

    public function getClipartsCollection()
    {
        $collection = Mage::getResourceModel('gomage_designer/clipart_collection');
        $collection->addFieldToFilter('category_id', $this->getId());

        return $collection;
    }

    public function getCollection()
    {
        $collection = Mage::getResourceModel('gomage_designer/clipart_category_collection');

        return $collection;
    }

    public function getChildrenCollection()
    {
        if($this->getId()) {
            $this->getChildrenCollectionById($this->getId());
        }
    }

    public function getChildrenCollectionById($parentId)
    {
        return $this->getCollection()->addFieldToFilter('parent_id',$parentId);
    }

    public function getChildrenCount()
    {
        $categoryCollection = $this->getChildrenCollection();
        if($categoryCollection) {
            return $categoryCollection->count();
        }
    }

    /**
     * Move category
     *
     * @param   int $parentId new parent category id
     * @param   int $afterCategoryId category id after which we have put current category
     * @return  Mage_Catalog_Model_Category
     */
    public function move($parentId, $afterCategoryId)
    {
        /**
         * Validate new parent category id. (category model is used for backward
         * compatibility in event params)
         */
        $parent = Mage::getModel('gomage_designer/clipart_category')
            ->setStoreId($this->getStoreId())
            ->load($parentId);

        if (!$parent->getId()) {
            Mage::throwException(
                Mage::helper('catalog')->__('Category move operation is not possible: the new parent category was not found.')
            );
        }

        if (!$this->getId()) {
            Mage::throwException(
                Mage::helper('catalog')->__('Category move operation is not possible: the current category was not found.')
            );
        } elseif ($parent->getId() == $this->getId()) {
            Mage::throwException(
                Mage::helper('catalog')->__('Category move operation is not possible: parent category is equal to child category.')
            );
        }

        /**
         * Setting affected category ids for third party engine index refresh
         */
        $this->setMovedCategoryId($this->getId());

        $eventParams = array(
            $this->_eventObject => $this,
            'parent'        => $parent,
            'category_id'   => $this->getId(),
            'prev_parent_id'=> $this->getParentId(),
            'parent_id'     => $parentId
        );
        $moveComplete = false;

        $this->_getResource()->beginTransaction();
        try {
            /**
             * catalog_category_tree_move_before and catalog_category_tree_move_after
             * events declared for backward compatibility
             */
            Mage::dispatchEvent('catalog_category_tree_move_before', $eventParams);
            Mage::dispatchEvent($this->_eventPrefix.'_move_before', $eventParams);

            $this->getResource()->changeParent($this, $parent, $afterCategoryId);

            Mage::dispatchEvent($this->_eventPrefix.'_move_after', $eventParams);
            Mage::dispatchEvent('catalog_category_tree_move_after', $eventParams);
            $this->_getResource()->commit();

            // Set data for indexer
            $this->setAffectedCategoryIds(array($this->getId(), $this->getParentId(), $parentId));

            $moveComplete = true;
        } catch (Exception $e) {
            $this->_getResource()->rollBack();
            throw $e;
        }
        if ($moveComplete) {
            Mage::getSingleton('index/indexer')->processEntityAction(
                $this, self::ENTITY, Mage_Index_Model_Event::TYPE_SAVE
            );
            Mage::app()->cleanCache(array(self::CACHE_TAG));
        }

        return $this;
    }

    /**
     * Get array categories ids which are part of category path
     * Result array contain id of current category because it is part of the path
     *
     * @return array
     */
    public function getPathIds()
    {
        $ids = $this->getData('path_ids');
        if (is_null($ids)) {
            $ids = explode('/', $this->getPath());
            $this->setData('path_ids', $ids);
        }
        return $ids;
    }
}