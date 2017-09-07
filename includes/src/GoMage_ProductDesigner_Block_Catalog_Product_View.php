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
 * Catalog product view block
 *
 * @category   GoMage
 * @package    GoMage_ProductDesigner
 * @subpackage Block
 * @author     Roman Bublik <rb@gomage.com>
 */
class GoMage_ProductDesigner_Block_Catalog_Product_View extends Mage_Catalog_Block_Product_View
{
    /**
     * Return true if product has options or has product design
     *
     * @return bool
     */
    public function hasOptions()
    {
        $hasOption = $this->getProduct()->getTypeInstance(true)->hasOptions($this->getProduct());

        if ($hasOption || $this->hasDesign()) {
            return true;
        }

        return false;
    }

    public function hasDesign()
    {
        $design = Mage::helper('gomage_designer')->getProductDesign($this->getProduct());
        return in_array($this->getProduct()->getTypeId(), Mage::helper('gomage_designer')->getAllowedProductTypes())
            && $design && $design->getId();
    }

    public function getDesignUrl()
    {
        $product = $this->getProduct();
        $params = array('_query' => array('id' => $product->getId()));
        return $this->getUrl('designer', $params);
    }

    public function addToCartDisabled()
    {
        if (!Mage::helper('gomage_designer')->isEnabled()) {
            return false;
        }

        if ($this->hasDesign() || !$this->getProduct()->getEnableProductDesigner()) {
            return false;
        }

        return $this->getProduct()->getEnableProductDesigner() && Mage::getStoreConfig('gomage_designer/general/add_to_cart_button');
    }

    public function designButtonEnabled()
    {
        if (!Mage::helper('gomage_designer')->isEnabled()) {
            return false;
        }

        return (!$this->hasDesign() && $this->getProduct()->getEnableProductDesigner());
    }
}
