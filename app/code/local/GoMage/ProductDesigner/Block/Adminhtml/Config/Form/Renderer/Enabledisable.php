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
 * Enable/Disable field renderer block
 *
 * @category   GoMage
 * @package    GoMage_ProductDesigner
 * @subpackage Block
 * @author     Roman Bublik <rb@gomage.com>
 */
class GoMage_ProductDesigner_Block_Adminhtml_Config_Form_Renderer_Enabledisable
    extends Mage_Adminhtml_Block_System_Config_Form_Field
{

    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
    	$websites = Mage::helper('gomage_designer')->getAvailableWebsites();
        if (!empty($websites)) {
    			$scope_website_code = $this->getRequest()->getParam('website');
    			$scope_website = Mage::getModel('core/website')->load($this->getRequest()->getParam('website'), 'code');
    			if ($scope_website && in_array($scope_website->getWebsiteId(), $websites)) {
    				$html = $element->getElementHtml();
    			} elseif (!$scope_website_code) {
    				$html = $element->getElementHtml();
    			} else {
    				$html = '<strong class="required">'.$this->__('Please buy additional domains').'</strong>';
    			}
    	} else {
    		$html = '<strong class="required">'.$this->__('Please enter a valid key').'</strong>';
    	}

    	return $html;
    }

}