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
class Clearandfizzy_Reducedcheckout_Model_System_Config_Source_Shipping_Enabledmethods {

	public function toOptionArray()
	{

		$active_carriers = Mage::getSingleton('shipping/config')->getActiveCarriers();

		$carrier_methods = array();
		$carrier_methods['noskip'] = Mage::helper('clearandfizzy_reducedcheckout')->__("Do not skip [Default Magento]");

		foreach ( $active_carriers as $code => $carrier ) {
			$label = Mage::getStoreConfig('carriers/'.$code.'/title');
			$enabled = Mage::getStoreConfig('carriers/'.$code. '/active');

			$methods = $carrier->getAllowedMethods();

			foreach ($methods as $method_code => $method_label ) {

				if ( $label != null && $enabled == 1 ) {
					$carrier_methods[$code.'_'.$method_code] = $label . " [".$method_label."]";
				} // end
			}

		} // end

		return $carrier_methods;

	} // end fun


} // end class
