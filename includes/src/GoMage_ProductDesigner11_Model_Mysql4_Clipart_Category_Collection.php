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
 * Clipart category collection
 *
 * @category   GoMage
 * @package    GoMage_ProductDesigner
 * @subpackage Model
 * @author     Roman Bublik <rb@gomage.com>
 */
class GoMage_ProductDesigner_Model_Mysql4_Clipart_Category_Collection
    extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init('gomage_designer/clipart_category');
    }

    public function addIdFilter($categoryIds)
    {
        if (is_array($categoryIds)) {
            if (empty($categoryIds)) {
                $condition = '';
            } else {
                $condition = array('in' => $categoryIds);
            }
        } elseif (is_numeric($categoryIds)) {
            $condition = $categoryIds;
        } elseif (is_string($categoryIds)) {
            $ids = explode(',', $categoryIds);
            if (empty($ids)) {
                $condition = $categoryIds;
            } else {
                $condition = array('in' => $ids);
            }
        }
        $this->addFieldToFilter('category_id', $condition);
        return $this;
    }

    public function addVisibleFilter()
    {
        $this->addFieldToFilter('is_active', 1);
        return $this;
    }

    public function addClipartCountFilter()
    {
        $concatExpr = $this->_getConcatSql(array('main_table.path', $this->_conn->quote('/%')));
        $countSelect = $this->getConnection()->select()
            ->from(array('clipart_category' => $this->getMainTable()), null)
            ->joinLeft(
                array('cliparts' => $this->getTable('gomage_designer/clipart')),
                'clipart_category.category_id=cliparts.category_id',
                array('COUNT(DISTINCT cliparts.clipart_id)'))
            ->where('clipart_category.category_id = main_table.category_id')
            ->orWhere('clipart_category.path LIKE ?', $concatExpr);
        $countExpr = $this->getConnection()
            ->quoteInto(new Zend_Db_Expr('('. $countSelect .') > ?'), 0);
        $this->getSelect()->where($countExpr);

        return $this;
    }

    /**
     * Generate fragment of SQL, that combine together (concatenate) the results from data array
     * All arguments in data must be quoted
     *
     * @param array $data
     * @param string $separator concatenate with separator
     * @return Zend_Db_Expr
     */
    protected function _getConcatSql(array $data, $separator = null)
    {
        $format = empty($separator) ? 'CONCAT(%s)' : "CONCAT_WS('{$separator}', %s)";
        return new Zend_Db_Expr(sprintf($format, implode(', ', $data)));
    }
}