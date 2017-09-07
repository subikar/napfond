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
 * Catalog product list block
 * Add design buttons to page
 *
 * @category   GoMage
 * @package    GoMage_ProductDesigner
 * @subpackage Block
 * @author     Roman Bublik <rb@gomage.com>
 */
class GoMage_ProductDesigner_Block_Catalog_Product_List extends Mage_Catalog_Block_Product_List
{
    public function getProductButtons()
    { 	
	
		$category = Mage::registry('current_category'); 
        
	  if($category->getParentId()){		
			/*$cat_id = Mage::getModel('catalog/layer')->getCurrentCategory()->getId(); // set current category id
			$category = Mage::getModel('catalog/category')->load($cat_id);
			$products = $category->getProductCollection()->addCategoryFilter($category)->addAttributeToSelect('*');*/
			    $products = $this->getLoadedProductCollection();
		}
	  else	{
				$products = $this->getLoadedProductCollection();
		    }		
		
        $_disabledAddToCart = Mage::getStoreConfig('gomage_designer/general/add_to_cart_button');
        $buttons = array();

        if (!Mage::helper('gomage_designer')->isEnabled()) {
            return $buttons;
        }
		
		
		
        foreach ($products as $_product) {
            $buttons[] = array(
                'add_to_cart_enabled' => (!$_product->getEnableProductDesigner() || !$_disabledAddToCart) ? true : false,
                'add_to_design_enabled' => $_product->getEnableProductDesigner() ? true : false,
                'design_url' => $this->_getDesignUrl($_product)
            );
        }

        return $buttons;
    }

    public function getProductButtonsJson()
    {
        return Zend_Json::encode($this->getProductButtons());
    }

    protected function _getDesignUrl($product,$catIdForDesigner=NULL)
    {
        if (!$product->getEnableProductDesigner()) {
            return  '';
        }
		if($catIdForDesigner == NULL)
		$cat_id = Mage::getModel('catalog/layer')->getCurrentCategory()->getId();
		else
		$cat_id = $catIdForDesigner;
        return $this->getUrl('designer', array('_query' => array('id' => $product->getId(),'cat_id' => $cat_id)));
    }
}
