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
 * Product designarea block
 *
 * @category   GoMage
 * @package    GoMage_ProductDesigner
 * @subpackage Block
 * @author     Roman Bublik <rb@gomage.com>
 */
class GoMage_ProductDesigner_Block_Adminhtml_Product_Edit extends Mage_Core_Block_Template
{
    protected $_image;

    /**
     * Return product
     *
     * @return Mage_Catalog_Model_Product
     */
    public function getProduct()
    {
        return Mage::registry('product');
    }

    /**
     * Return image settings
     *
     * @return array
     */
    public function getSettings()
    {
        $product = $this->getProduct();
        $image = $this->getImage();

        if (!$product->getId()) {
            return array();
        }
        $settings = Mage::helper('core')->jsonDecode($image->getDesignArea());

        if (is_null($settings) || empty($settings)) {
            $imageWidth = $image->getWidth();
            $imageHeight = $image->getHeight();
            $settings = array(
                't' => round($imageHeight / 2),
                'l' => round($imageWidth / 2),
                'h' => 200,
                'w' => 200,
                's' => 1,
                'ip' => 0,
            );
        }

        return $settings;
    }

    /**
     * Return image
     *
     * @return Varien_Object
     */
    public function getImage()
    {
        if (is_null($this->_image)) {
            $product = $this->getProduct();
            if ($product->getId()) {
                $image = Mage::registry('current_image');
                if ($image && $image->getId()) {
                    $imageUrl = Mage::helper('gomage_designer')->getDesignImageUrl($product, $image);
                    $image->setUrl($imageUrl);
                    list($imageWidth, $imageHeight) = Mage::helper('gomage_designer')->getImageDimensions($image->getUrl());
                    $image->setWidth($imageWidth);
                    $image->setHeight($imageHeight);
                    $this->_image = $image;
                }
            }
        }

        return $this->_image;
    }

    /**
     * Return image Id
     *
     * @return int
     */
    public function getImageId()
    {
        return $this->getRequest()->getParam('img');
    }

    /**
     * Return Image width
     *
     * @return int
     */
    public function getImageWidth()
    {
        return $this->getImage()->getWidth();
    }

    /**
     * Return Image height
     *
     * @return int
     */
    public function getImageHeight()
    {
        return $this->getImage()->getHeight();
    }

    /**
     * Return save url
     *
     * @return bool|string
     */
    public function getSaveUrl()
    {
        $product = $this->getProduct();

        if ($product && $product->getId()){
            return Mage::helper('adminhtml')->getUrl('*/designer_product/save', array(
                'product_id' => $product->getId()
            ));
        }

        return false;
    }
}