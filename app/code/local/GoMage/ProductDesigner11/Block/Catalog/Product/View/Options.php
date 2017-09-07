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
 * Product options block
 *
 * @category   GoMage
 * @package    GoMage_ProductDesigner
 * @subpackage Block
 * @author     Roman Bublik <rb@gomage.com>
 */
class GoMage_ProductDesigner_Block_Catalog_Product_View_Options
    extends Mage_Catalog_Block_Product_View_Options
{
    protected $_designOption;

    /**
     * Return product design option
     *
     * @return bool|Mage_Catalog_Model_Product_Option
     */
    public function getDesignOption()
    {
        if (is_null($this->_designOption)) {
            $design = Mage::helper('gomage_designer')->getProductDesign($this->getProduct());
            if ($design && $design->getId()) {
                $option = new Mage_Catalog_Model_Product_Option(array(
                    'id' => $design->getId(),
                    'value' => $design->getId(),
                    'price' => $design->getPrice(),
                    'price_type' => 'fixed',
                ));

                if (!$this->hasOptions()) {
                    $option->setData('decorated_is_last', true);
                }
                $option->setProduct($this->getProduct());
                $priceConfig = $this->_getPriceConfiguration($option);
                $option->setPriceConfig(
                    Mage::helper('core')->jsonEncode($priceConfig)
                );

                $this->_designOption = $option;
            } else {
                $this->_designOption = false;
            }
        }

        return $this->_designOption;
    }

    /**
     * Return product design option html
     *
     * @return string
     */
    public function getDesignOptionHtml()
    {
        if ($option = $this->getDesignOption()) {
            $block = $this->getChild('product_option_design');
            $block->setOption($option);
            return $block->toHtml();
        }

        return '';
    }

    /**
     * Product has design option
     *
     * @return bool
     */
    public function hasDesignOption()
    {
        $option = $this->getDesignOption();
        return $option ? true : false;
    }

    /**
     * Get price configuration
     *
     * @param Mage_Catalog_Model_Product_Option_Value|Mage_Catalog_Model_Product_Option $option
     * @return array
     */
    protected function _getPriceConfiguration($option)
    {
        $data = array();
        $data['price']      = Mage::helper('core')->currency($option->getPrice(true), false, false);
        $data['oldPrice']   = Mage::helper('core')->currency($option->getPrice(false), false, false);
        $data['priceValue'] = $option->getPrice(false);
        $data['type']       = $option->getPriceType();
        $data['excludeTax'] = $price = Mage::helper('tax')->getPrice($option->getProduct(), $data['price'], false);
        $data['includeTax'] = $price = Mage::helper('tax')->getPrice($option->getProduct(), $data['price'], true);
        return $data;
    }
}
