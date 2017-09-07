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
 * Product attribute options tab block
 *
 *
 * @category   GoMage
 * @package    GoMage_ProductDesigner
 * @subpackage Block
 * @author     Roman Bublik <rb@gomage.com>
 */
class GoMage_ProductDesigner_Block_Adminhtml_Catalog_Product_Attribute_Edit_Tab_Options
    extends Mage_Adminhtml_Block_Catalog_Product_Attribute_Edit_Tab_Options
{
    protected $_navigationBlock;

    public function __construct()
    {
        parent::__construct();

        if ($navigationBlock = $this->_getNavigationOptionsBlock()) {
            $this->setTemplate($navigationBlock->getTemplate());
            return;
        }

        if (!Mage::helper('gomage_designer')->isEnabled()) {
            return;
        }
        $colorAttributeCode = Mage::getStoreConfig('gomage_designer/navigation/color_attribute');
        if ($colorAttributeCode && ($this->getAttributeObject()->getAttributeCode() == $colorAttributeCode)) {
            $this->setTemplate('gomage/productdesigner/catalog/product/attribute/options.phtml');
        }

    }

    public function getOptionValues()
    {
        if ($navigationBlock = $this->_getNavigationOptionsBlock()) {
            return $navigationBlock->getOptionValues();
        }
        $values = parent::getOptionValues();
        $colorAttributeCode = Mage::getStoreConfig('gomage_designer/navigation/color_attribute');
        if (!$colorAttributeCode || !($this->getAttributeObject()->getAttributeCode() == $colorAttributeCode)) {
            return $values;
        }
        if ($values) {
            $images = $this->getAttributeObject()->getOptionImages();
            foreach ($values as $value) {
                if (isset($images[$value['id']])) {
                    $value->setImageInfo(array($images[$value['id']]));
                }
            }
        }

        return $values;
    }

    public function getPopupTextValues()
    {
        if ($navigationBlock = $this->_getNavigationOptionsBlock()) {
            return $navigationBlock->getPopupTextValues();
        }
    }

    protected function _getNavigationOptionsBlock()
    {
        if (is_null($this->_navigationBlock)) {
            if (!Mage::helper('gomage_designer')->advancedNavigationEnabled()) {
                $this->_navigationBlock = false;
            } elseif (class_exists('GoMage_Navigation_Block_Adminhtml_Catalog_Product_Attribute_Edit_Tab_Options')) {
                $this->_navigationBlock = new GoMage_Navigation_Block_Adminhtml_Catalog_Product_Attribute_Edit_Tab_Options();
            } else {
                $this->_navigationBlock = false;
            }
        }

        return $this->_navigationBlock;
    }
}
