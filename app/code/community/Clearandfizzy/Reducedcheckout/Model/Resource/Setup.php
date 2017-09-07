<?php
/**
 * Clearandfizzy
 *
 * NOTICE OF LICENSE
 *
 *
 * THE WORK (AS DEFINED BELOW) IS PROVIDED UNDER THE TERMS OF THIS CREATIVE
 * COMMONS PUBLIC LICENSE ("CCPL" OR "LICENSE"). THE WORK IS PROTECTED BY
 * COPYRIGHT AND/OR OTHER APPLICABLE LAW. ANY USE OF THE WORK OTHER THAN AS
 * AUTHORIZED UNDER THIS LICENSE OR COPYRIGHT LAW IS PROHIBITED.

 * BY EXERCISING ANY RIGHTS TO THE WORK PROVIDED HERE, YOU ACCEPT AND AGREE
 * TO BE BOUND BY THE TERMS OF THIS LICENSE. TO THE EXTENT THIS LICENSE MAY
 * BE CONSIDERED TO BE A CONTRACT, THE LICENSOR GRANTS YOU THE RIGHTS
 * CONTAINED HERE IN CONSIDERATION OF YOUR ACCEPTANCE OF SUCH TERMS AND
 * CONDITIONS.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * versions in the future. If you wish to customize this extension for your
 * needs please refer to http://www.clearandfizzy.com for more information.
 *
 * @category    Community
 * @package     Clearandfizzy_Reducedcheckout
 * @copyright   Copyright (c) 2014 Clearandfizzy ltd. (http://www.clearandfizzy.com)
 * @license     http://creativecommons.org/licenses/by-nd/3.0/ Creative Commons (CC BY-ND 3.0) 
 * @author		Gareth Price <gareth@clearandfizzy.com>
 * 
 */
class Clearandfizzy_Reducedcheckout_Model_Resource_Setup extends Mage_Core_Model_Resource_Setup
{

	/**
	 * This is called during the setep process, 
	 * @see etc/config.xml
	 * @see sql/reducecheckout_setup/*.php
	 * @param unknown $string
	 */
	public function addBlock($string) {
		
		$cms_block = Mage::getModel('cms/block');
		
		$cms_block->setData('title', 'ReducedCheckout - Order Success Page - Customer Registration Form');
		$cms_block->setData('identifier', 'reducedcheckout_success_form_register');
		$cms_block->setData('content', $string );

		// all stores
		$stores = array();
		$stores[] = 0;
		foreach ( Mage::app()->getStores() as $store) {
			$stores[] = $store->getId();
		} // end 
		
		$cms_block->setData('stores', $stores);
		$cms_block->setData('is_active', '1');
		$cms_block->save();
	 	
	} // end 
	
}
