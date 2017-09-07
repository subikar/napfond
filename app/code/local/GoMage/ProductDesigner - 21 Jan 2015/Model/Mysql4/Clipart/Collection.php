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
 * Clipart collection
 *
 * @category   GoMage
 * @package    GoMage_ProductDesigner
 * @subpackage Model
 * @author     Roman Bublik <rb@gomage.com>
 */
class GoMage_ProductDesigner_Model_Mysql4_Clipart_Collection
    extends Mage_Core_Model_Mysql4_Collection_Abstract
{

    protected function _construct()
    {
        $this->_init('gomage_designer/clipart');
    }

    public function addVisibleCategoriesFilter()
    {
        $select = $this->getSelect();
        $joinTableAlias = 'categories';
        $joinTable = $this->getTable(GoMage_ProductDesigner_Model_Clipart_Category::ENTITY);

        $select->joinInner(
            array($joinTableAlias => $joinTable),
            $joinTableAlias.'.category_id = main_table.category_id AND '.$joinTableAlias.'.is_active=1'
        );

        return $this;
    }

    public function addCategoryFilter($categoryId)
    {
        $categoryId = (int) $categoryId;
        if($categoryId <= 0) return;

        $select = $this->getSelect();
        $joinTableAlias = 'categories';
        $joinTable = $this->getTable(GoMage_ProductDesigner_Model_Clipart_Category::ENTITY);

        $select->joinInner(
            array($joinTableAlias => $joinTable),
            $joinTableAlias.'.category_id = main_table.category_id
            AND '.$joinTableAlias.'.path REGEXP "/'.$categoryId.'[^0-9]|/'.$categoryId.'$"
            AND '.$joinTableAlias.'.is_active=1'
        );

        return $this;
    }

    public function addTagsFilter($tags)
    {
        $tableAlias = 'main_table';
        $select = $this->getSelect();
        $tags = (string) $tags;
        $tags = $this->_prepareTags($tags);
        $select->where($tableAlias.'.tags REGEXP '.$tags.'');
    }

    protected function _prepareTags($tags)
    {
        $readConnection = $this->getResource()->getReadConnection();

        $tagsArray = explode(',', str_replace(' ', '', $tags));
        $tags = $readConnection->quote(implode('|', $tagsArray));
        return $tags;
    }
}