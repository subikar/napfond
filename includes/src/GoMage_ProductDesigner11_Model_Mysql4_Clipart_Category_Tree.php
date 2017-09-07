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
 * Clipart category tree
 *
 * @category   GoMage
 * @package    GoMage_ProductDesigner
 * @subpackage Model
 * @author     Roman Bublik <rb@gomage.com>
 */
class GoMage_ProductDesigner_Model_Mysql4_Clipart_Category_Tree
    extends Mage_Catalog_Model_Resource_Eav_Mysql4_Category_Tree
{
    public function __construct()
    {
        parent::__construct();

        $resource = Mage::getSingleton('core/resource');

        $connection = $resource->getConnection('gomage_productdesigner_write');
        $table = $resource->getTableName('gomage_designer/clipart_category');
        $fields = array(
            Varien_Data_Tree_Dbp::ID_FIELD       => 'category_id',
            Varien_Data_Tree_Dbp::PATH_FIELD     => 'path',
            Varien_Data_Tree_Dbp::ORDER_FIELD    => 'position',
            Varien_Data_Tree_Dbp::LEVEL_FIELD    => 'level',
        );

        if (!$connection) {
            throw new Exception('Wrong "$connection" parametr');
        }

        $this->_conn    = $connection;
        $this->_table   = $table;

        if (!isset($fields[Varien_Data_Tree_Dbp::ID_FIELD]) ||
            !isset($fields[Varien_Data_Tree_Dbp::PATH_FIELD]) ||
            !isset($fields[Varien_Data_Tree_Dbp::LEVEL_FIELD]) ||
            !isset($fields[Varien_Data_Tree_Dbp::ORDER_FIELD])) {

            throw new Exception('"$fields" tree configuratin array');
        }

        $this->_idField     = $fields[Varien_Data_Tree_Dbp::ID_FIELD];
        $this->_pathField   = $fields[Varien_Data_Tree_Dbp::PATH_FIELD];
        $this->_orderField  = $fields[Varien_Data_Tree_Dbp::ORDER_FIELD];
        $this->_levelField  = $fields[Varien_Data_Tree_Dbp::LEVEL_FIELD];

        $this->_select  = $this->_conn->select();
        $this->_select->from($this->_table);
    }

    public function loadBreadcrumbsArray($path, $addCollectionData = true, $withRootNode = false)
    {
        $pathIds = explode('/', $path);
        if (!$withRootNode) {
            array_shift($pathIds);
        }
        $result = array();
        if (!empty($pathIds)) {
            if ($addCollectionData) {
                $select = $this->_createCollectionDataSelect(false);
            } else {
                $select = clone $this->_select;
            }
            $select
                ->where('main_table.category_id IN(?)', $pathIds)
                ->order($this->_getLengthSql('main_table.path') . ' ' . Varien_Db_Select::SQL_ASC);
            $result = $this->_conn->fetchAll($select);
        }
        return $result;
    }

    protected function _getDefaultCollection($sorted = false)
    {
        $this->_joinUrlRewriteIntoCollection = true;
        $collection = Mage::getModel('gomage_designer/clipart_category')->getCollection();
        /** @var $collection GoMage_ProductDesigner_Model_Mysql4_Clipart_Category_Collection */

        if ($sorted) {
            if (is_string($sorted)) {
                // $sorted is supposed to be attribute name
                $collection->addAttributeToSort($sorted);
            } else {
                $collection->addAttributeToSort('name');
            }
        }

        return $collection;
    }

    protected function _createCollectionDataSelect($sorted = true, $optionalAttributes = array())
    {
        $select = $this->_getDefaultCollection($sorted ? $this->_orderField : false)
            ->getSelect();

        $this->addJoin($select);

        return $select;
    }

    public function addJoin($select) {
        // count children products qty plus self products qty
        $categoriesTable         = Mage::getSingleton('core/resource')->getTableName('gomage_designer/clipart_category');
        $categoriesProductsTable = Mage::getSingleton('core/resource')->getTableName('gomage_designer/clipart');

        $subConcat = $this->_getConcatSql(array('main_table.path', $this->_conn->quote('/%')));
        $subSelect = $this->_conn->select()
            ->from(array('see' => $categoriesTable), null)
            ->joinLeft(
                array('scp' => $categoriesProductsTable),
                'see.category_id=scp.category_id',
                array('COUNT(DISTINCT scp.clipart_id)'))
            ->where('see.category_id = main_table.category_id')
            ->orWhere('see.path LIKE ?', $subConcat);

        $subSelect2 = $this->_conn->select()
            ->from(array('cp' => $categoriesProductsTable), 'COUNT(cp.clipart_id)')
            ->where('cp.category_id = main_table.category_id');

        $select->columns(array(
            'product_count' => new Zend_Db_Expr('('.$subSelect.')'),
            'self_product_count' => new Zend_Db_Expr('('.$subSelect2.')')
        ));
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

    /**
     * Generate fragment of SQL that returns length of character string
     * The string argument must be quoted
     *
     * @param string $string
     * @return Zend_Db_Expr
     */
    protected function _getLengthSql($string)
    {
        return new Zend_Db_Expr(sprintf('LENGTH(%s)', $string));
    }
}