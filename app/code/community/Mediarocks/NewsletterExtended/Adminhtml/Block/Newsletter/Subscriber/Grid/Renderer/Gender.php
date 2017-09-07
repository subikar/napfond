<?php
/**
 * Media Rocks GbR
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA that is bundled with 
 * this package in the file MEDIAROCKS-LICENSE-COMMUNITY.txt.
 * It is also available through the world-wide-web at this URL:
 * http://solutions.mediarocks.de/MEDIAROCKS-LICENSE-COMMUNITY.txt
 *
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This package is designed for Magento COMMUNITY edition. 
 * Media Rocks does not guarantee correct work of this extension
 * on any other Magento edition except Magento COMMUNITY edition.
 * Media Rocks does not provide extension support in case of
 * incorrect edition usage.
 * =================================================================
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade the extension
 * to newer versions in the future. If you wish to customize the extension
 * for your needs please send an email to support@mediarocks.de
 *
 */

/**
 * Adminhtml newsletter subscribers grid gender item renderer
 *
 * @category   Mediarocks
 * @package    Mediarocks_NewsletterExtended
 * @author     Media Rocks Developer
 */

class Mediarocks_NewsletterExtended_Adminhtml_Block_Newsletter_Subscriber_Grid_Renderer_Gender extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
	public function render(Varien_Object $row)
	{
        // not logged in => use subscriber data
        if ($row->getType() != 2) {
            $value = $row->getSubscriberGender();
        }
        // logged-in with override
        elseif (Mage::getStoreConfig('newsletterextended/fields/customer_override')) {
            
            // fallback enabled => fallback to customer data if no data found in subscriber
            if (Mage::getStoreConfig('newsletterextended/fields/customer_fallback')) {
                $value = $row->getSubscriberGender() ? $row->getSubscriberGender() : $row->getCustomerGender();
            }
            // fallback disabled => only use subscriber data event if it's empty
            else {
                $value = $row->getSubscriberGender();
            }
        }
        // logged-in without override => only use customer data even if it's empty
        else {
            $value = $row->getCustomerGender();
        }
		
        // fix strange values (dunno where they come from)
		$options = $this->getColumn()->getOptions();
		if ($value == 123)
			$value = 1;
		else if ($value == 124)
			$value = 2;
			
		return isset($options[$value]) ? $options[$value] : '---';
	}
}