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
 * Source model for default tab in designer
 *
 * @category   GoMage
 * @package    GoMage_ProductDesigner
 * @subpackage Model
 * @author     Roman Bublik <rb@gomage.com>
 */
class GoMage_ProductDesigner_Model_Config_Source_Tabs
{
    public function toOptionArray()
    {
        return array(
            array('value' => 'navigation', 'label' => Mage::helper('gomage_designer')->__('Choose Product')),
            array('value' => 'design', 'label' => Mage::helper('gomage_designer')->__('Add Cliparts')),
            array('value' => 'text', 'label' => Mage::helper('gomage_designer')->__('Add Text')),
            array('value' => 'upload_image', 'label' => Mage::helper('gomage_designer')->__('Upload Images'))
        );
    }
}
