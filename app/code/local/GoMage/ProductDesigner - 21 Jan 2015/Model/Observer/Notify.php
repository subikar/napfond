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
class GoMage_ProductDesigner_Model_Observer_Notify
{
	public function notify($event)
    {
		if (Mage::getSingleton('admin/session')->isLoggedIn()
            && Mage::getStoreConfig('gomage_notification/notification/enable')) {
			Mage::helper('gomage_designer')->notify();
		}
	}

}