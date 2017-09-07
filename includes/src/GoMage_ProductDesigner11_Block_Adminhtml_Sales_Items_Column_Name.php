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
 * Sales name column renderer
 *
 * @category   GoMage
 * @package    GoMage_ProductDesigner
 * @subpackage Block
 * @author     Roman Bublik <rb@gomage.com>
 */
class GoMage_ProductDesigner_Block_Adminhtml_Sales_Items_Column_Name
    extends Mage_Adminhtml_Block_Sales_Items_Column_Name
{
    public function getOrderDesignOption()
    {
        $options = $this->getItem()->getProductOptions();
        if (!isset($options['info_buyRequest'])) {
            return false;
        }
        $options = $options['info_buyRequest'];
        if (isset($options['design'])) {
            $designId = (int) $options['design'];
            $design = Mage::getModel('gomage_designer/design')->load($designId);
            if ($design && $design->getId()) {
                $option = array(
                    'price' => $design->getPrice(),
                    'design_id' => $design->getId(),
                    'url' => Mage::helper('adminhtml')->getUrl('*/designer_design/view',
                        array('design_id' => $design->getId()))
                );

                return new Varien_Object($option);
            }
        }

        return false;
    }

    /**
     * Add line breaks and truncate value
     *
     * @param string $value
     * @return array
     */
    public function getFormattedOption($value)
    {
        $_remainder = '';
        $value = Mage::helper('core/string')->truncate($value, 55, '', $_remainder);
        $result = array(
            'value' => nl2br($value),
            'remainder' => nl2br($_remainder)
        );

        return $result;
    }
}
