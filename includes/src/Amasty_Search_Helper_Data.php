<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Amasty_Search
 */ 
class Amasty_Search_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * Get catagories of current store
     *
     * @return Varien_Data_Tree_Node_Collection
     */
    public function getStoreCategories()
    {
        $helper = Mage::helper('catalog/category');
        return $helper->getStoreCategories();
    }
    
    public function isCategoryActive($category)
    {
        $ids = Mage::app()->getRequest()->getParam('categories');
        if (is_array($ids) && in_array($category->getId(), $ids))
            return true;
            
        return false;
    }
    
    
    /**
     * Draws category and it's sub categories as checkboxes
     *
     * @param Mage_Catalog_Model_Category $category
     * @param int $level
     * @param boolean $last
     * @return string
     */
    public function drawCategory($category, $level=0, $last=false)
    {
        $html = '';
        if (!$category->getIsActive()) {
            return $html;
        }
        if (Mage::helper('catalog/category_flat')->isEnabled()) {
            $children = $category->getChildrenNodes();
            $childrenCount = count($children);
        } else {
            $children = $category->getChildren();
            $childrenCount = $children->count();
        }
        $hasChildren = $children && $childrenCount;
        
        $html .= '<option value="' . $category->getId(). '" ';
        //$html .= 'style="padding-left:'.(10*$level).'px"';
        
        if ($this->isCategoryActive($category)) {
            $html .= ' selected';
        }

        if ($hasChildren) {
            $cnt = 0;
            foreach ($children as $child) {
                if ($child->getIsActive()) {
                    $cnt++;
                }
            }
        }
        $html .= '>';
        $html .= str_pad($this->htmlEscape($category->getName()), $level, ' ', STR_PAD_LEFT) 
              . '</option>'."\n";

        if ($hasChildren){

            $j = 0;
            $htmlChildren = '';
            foreach ($children as $child) {
                if ($child->getIsActive()) {
                    $htmlChildren.= $this->drawCategory($child, $level+1, ++$j >= $cnt);
                }
            }

            if (!empty($htmlChildren)) {
                $padding = ($level+1)*10;
                //$html.= '<div style="padding-left:'.$padding.'px">';
                $html .= $htmlChildren;
                //$html.= '</div>';
            }

        }
        
        return $html;
    }  
    
    public function drawCategories()
    {
        $tree = '';
        foreach ($this->getStoreCategories() as $cat) { 
            $tree .= $this->drawCategory($cat); 
        }
        
        //create block
        $block = Mage::getModel('core/layout')->createBlock('core/template')
            ->setArea('frontend')
            ->setTemplate('amsearch/categories.phtml');
        $block->assign('_type', 'html')
            ->assign('_section', 'body')        
            ->setTree($tree);
             
        return $block->toHtml();             
    }  
    
    /**
     * Retrieves attribute input type
     *
     * @param   $attribute
     * @return  string
     */
    public function getAttributeInputType($attribute, $formBlock)
    {
        //dropdowns
        $codes = Mage::getStoreConfig('amsearch/general/ranges');
        if ($codes){
            $codes = explode(',', $codes);
            if (in_array($attribute->getAttributeCode(), $codes))
                return 'number';
        }
        //inputs
        $class = $attribute->getFrontendClass();
        if ($class == 'validate-number' || $class == 'validate-digits'){    
            return 'number';    
        }
            
        return $formBlock->getAttributeInputType($attribute);
    }

}