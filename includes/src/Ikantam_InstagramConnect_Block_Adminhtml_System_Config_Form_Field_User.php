<?php
/**
 * iKantam LLC
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the iKantam EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://magento.ikantam.com/store/license-agreement
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade
 * InstagramConnect Module to newer versions in the future.
 *
 * @category   Ikantam
 * @package    Ikantam_InstagramConnect
 * @author     iKantam Team <support@ikantam.com>
 * @copyright  Copyright (c) 2012 iKantam LLC (http://www.ikantam.com)
 * @license    http://magento.ikantam.com/store/license-agreement  iKantam EULA
 */
class Ikantam_InstagramConnect_Block_Adminhtml_System_Config_Form_Field_User
    extends Mage_Adminhtml_Block_System_Config_Form_Field
{

    protected $_template = "ikantam/instagram_connect/config/form/field/user.phtml";

    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        return $this->_toHtml();
    }

    public function getUserData()
    {
        $info = Mage::getModel('instagramconnect/instagramauth')->getUserData();
        return $info;
    }

}
