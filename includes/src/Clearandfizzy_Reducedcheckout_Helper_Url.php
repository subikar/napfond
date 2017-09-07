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
* @copyright   Copyright (c) 2014 Clearandfizzy Ltd. (http://www.clearandfizzy.com)
* @license     http://creativecommons.org/licenses/by-nd/3.0/ Creative Commons (CC BY-ND 3.0)
* @author		Gareth Price <gareth@clearandfizzy.com>
*
*/
class Clearandfizzy_Reducedcheckout_Helper_Url extends Mage_Core_Helper_Abstract {

	protected $is_secure;

	/**
	 * Returns a true/false if the current page is https or not
	 * @return boolean
	 */
	public function getIsSecure() {
		if ($this->is_secure == null) {
			$this->is_secure = Mage::app()->getStore()->isCurrentlySecure();
		} // end if

		return $this->is_secure;
	} // end


	/**
	 *
	 */
	public function getProgressUrl() {
		return Mage::getUrl('reducedcheckout/onepage/progress', array("_secure" => $this->getIsSecure()));
	} // end

	public function getReviewUrl() {
		return Mage::getUrl('reducedcheckout/onepage/review', array("_secure" => $this->getIsSecure()));
	} // end

	public function getSaveMethodUrl() {
		return Mage::getUrl('reducedcheckout/onepage/saveMethod', array("_secure" => $this->getIsSecure()));
	}

	public function getFailureUrl() {
		return Mage::getUrl('checkout/cart', array("_secure" => $this->getIsSecure()));
	}

	public function getAddressUrl() {
		return Mage::getUrl('reducedcheckout/onepage/getAddress', array("_secure" => $this->getIsSecure()));
	} // end

	public function getSaveBillingUrl() {
		return Mage::getUrl('reducedcheckout/onepage/saveBilling', array("_secure" => $this->getIsSecure()));
	} // end

	public function getSaveShippingUrl() {
		return Mage::getUrl('reducedcheckout/onepage/saveShipping', array("_secure" => $this->getIsSecure()));
	}

	public function getSaveShippingMethod() {
		return Mage::getUrl('reducedcheckout/onepage/saveShippingMethod', array("_secure" => $this->getIsSecure()));
	}

	public function getSavePaymentUrl() {
		return Mage::getUrl('reducedcheckout/onepage/savePayment', array("_secure" => $this->getIsSecure()));
	}

} // end class