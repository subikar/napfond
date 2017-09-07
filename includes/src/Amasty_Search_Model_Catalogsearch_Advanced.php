<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Amasty_Search
 */

class Amasty_Search_Model_Catalogsearch_Advanced extends Mage_CatalogSearch_Model_Advanced
{
    protected $_customSearchCriterias = array();
    /**
     * Add advanced search filters to product collection
     *
     * @param   array $values
     * @return  Amasty_Search_Model_CatalogSearch_Advanced
     */
    public function addFilters($values)
    {
        $attributes = $this->getAttributes();
        
        // NEW CODE
        // convert from-to to options ids
        $customCodes = Mage::getStoreConfig('amsearch/general/ranges');
        if ($customCodes)
            $customCodes = explode(',', $customCodes);
        else 
            $customCodes = array();
        
        $originalValues = $values;
        
        foreach ($attributes as $a){
            $code = $a->getAttributeCode();
            if (!in_array($code, $customCodes)){
                continue;
            }
            
            if (empty($values[$code]['from']) && empty($values[$code]['to'])){
                unset($values[$code]);
                continue;
            }
            $from = !empty($values[$code]['from']) ? floatVal($values[$code]['from']) : '';
            $to   = !empty($values[$code]['to'])   ? floatVal($values[$code]['to']) : '';

            
            $values[$code] = array();
            
            //get all suitable options
            $options = $a->getSource()->getAllOptions(false);
            foreach ($options as $opt){
                $notLess    = ($opt['label'] >= $from || '' === $from);
                $notGreater = ($opt['label'] <= $to || '' === $to);
                if ($notLess && $notGreater){
                    $values[$code][] = $opt['value'];
                }   
            }
        }
        // add categories filter
        if (!empty($values['categories']) && is_array($values['categories'])){
            $db = Mage::getSingleton('core/resource');
            $s = $db->getConnection('amsearch_read')->select()
                ->from($db->getTableName('catalog/category_product'), array('product_id')) 
                ->where('category_id IN(?)', $values['categories']);
                
            $this->getProductCollection()->addFieldToFilter('entity_id', array('in'=>new Zend_Db_Expr($s)));
            
            //save in search criterisas
            $name = Mage::helper('amsearch')->__('Categories');
            $vals = '';
            foreach ($values['categories'] as $id)
                $vals .= Mage::getModel('catalog/category')->load($id)->getName() . ', ';
            
            $this->_customSearchCriterias[] = array('name'=>$name, 'value'=>substr($vals, 0, -2));
        }
        
        // END NEW CODE
        
        
        $allConditions = array();
        $filteredAttributes = array();
        $indexFilters = Mage::getModel('catalogindex/indexer')->buildEntityFilter(
            $attributes,
            $values,
            $filteredAttributes,
            $this->getProductCollection()
        );

        foreach ($indexFilters as $filter) {
            $this->getProductCollection()->addFieldToFilter('entity_id', array('in'=>new Zend_Db_Expr($filter)));
        }

        $priceFilters = Mage::getModel('catalogindex/indexer')->buildEntityPriceFilter(
            $attributes,
            $values,
            $filteredAttributes,
            $this->getProductCollection()
        );

        foreach ($priceFilters as $code=>$filter) {
            $this->getProductCollection()->getSelect()->joinInner(
                array("_price_filter_{$code}"=>$filter),
                "`_price_filter_{$code}`.`entity_id` = `e`.`entity_id`",
                array()
            );
        }

        foreach ($attributes as $attribute) {
            $code      = $attribute->getAttributeCode();
            $condition = false;

            if (isset($values[$code])) {
                $value = $values[$code];

                if (is_array($value)) {
                    
                    if (isset($value['from']) && isset($value['to'])) {
                        // new code
                        $class = $attribute->getFrontendClass();
                        if ($class == 'validate-number' || $class == 'validate-digits'){
                            if (!empty($value['from']))
                                $value['from'] = new Zend_Db_Expr(floatval($value['from']));
                            if (!empty($value['to']))
                                $value['to'] = new Zend_Db_Expr(floatval($value['to']));
                        }

                        if (!empty($value['from']) || !empty($value['to']))  {                     
                            $condition = $value;
                        }
                    }
                    elseif ($attribute->getBackend()->getType() == 'varchar') {
                        $condition = array('in_set'=>$value);
                    }
                    elseif (!isset($value['from']) && !isset($value['to'])) {
                        $condition = array('in'=>$value);
                    }
                } else {
                    if (strlen($value)>0) {
                        if (in_array($attribute->getBackend()->getType(), array('varchar', 'text'))) {
                            $condition = array('like'=>'%'.$value.'%');
                        } elseif ($attribute->getFrontendInput() == 'boolean') {
                            $condition = array('in' => array('0','1'));
                        } else {
                            $condition = $value;
                        }
                    }
                }
            }

            if (false !== $condition) {
                
                // start new code
                if (in_array($code, $customCodes)){
                    $attribute->setFrontendInput('text');
                    $value = $originalValues[$code]; 
                }
                // end new code
                
                $this->_addSearchCriteria($attribute, $value);

                if (in_array($code, $filteredAttributes))
                    continue;

                $table = $attribute->getBackend()->getTable();
                $attributeId = $attribute->getId();
                if ($attribute->getBackendType() == 'static'){
                    $attributeId = $attribute->getAttributeCode();
                    $condition = array('like'=>"%{$condition}%");
                }

                $allConditions[$table][$attributeId] = $condition;
            }
        }
        if ($allConditions) {
            $this->getProductCollection()->addFieldsToFilter($allConditions);
        } else if (!count($filteredAttributes) && !$this->_customSearchCriterias) {
            Mage::throwException(Mage::helper('catalogsearch')->__('You have to specify at least one search term'));
        }
        
        //echo (string)$this->getProductCollection()->getSelect();
        
        return $this;
    }
    
    
    public function getSearchCriterias()
    {
        $res = parent::getSearchCriterias();
        return array_merge($res, $this->_customSearchCriterias);
    }

    
    public function getProductCollection()
    {
        if (is_null($this->_productCollection)) {
            $this->_productCollection = Mage::getResourceModel('amsearch/catalogsearch_collection')
                ->addAttributeToSelect(Mage::getSingleton('catalog/config')->getProductAttributes())
                ->addMinimalPrice()
                ->addTaxPercents()
                ->addStoreFilter();
                Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($this->_productCollection);
                Mage::getSingleton('catalog/product_visibility')->addVisibleInSearchFilterToCollection($this->_productCollection);
        }

        return $this->_productCollection;
    } 
}
