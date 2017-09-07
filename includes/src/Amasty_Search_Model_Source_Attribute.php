<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Amasty_Search
 */

class Amasty_Search_Model_Source_Attribute extends Varien_Object
{
    public function toOptionArray()
    {
        
        $product = Mage::getModel('catalog/product');
        $attributes = Mage::getResourceModel('catalog/product_attribute_collection')
            ->setEntityTypeFilter($product->getResource()->getTypeId())
            ->addFieldToFilter('frontend_input', array('in'=>array('select', 'multiselect')))
            ->addHasOptionsFilter()
            ->addDisplayInAdvancedSearchFilter()
            ->setOrder('frontend_label', 'asc')
            ->load();
            
        $options = array();
        $options[] = array(
                'value'=> 0,
                'label' => Mage::helper('amsearch')->__('--None--'),
        );
        foreach ($attributes as $attribute) {
            $options[] = array(
                    'value'=> $attribute->getAttributeCode(),
                    'label' => $attribute->getFrontendLabel(),
            );
        }        
        
        return $options;
    }
}