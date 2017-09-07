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
class Clearandfizzy_Reducedcheckout_Model_Observer extends Mage_Core_Model_Observer {

	/**
	 * Use Layout Handles to apply logic.
	 *
	 * @see etc/config.xml <events>
	 * @see app/design/frontend/base/default/layout/clearandfizzy/reducedcheckout/reducedcheckout.xml
	 * @param Varien_Event_Observer $observer
	 */
	public function checkReducedCheckout(Varien_Event_Observer $observer) {
	
		// exit now if reduced checkout is not enabled or this is not checkout_onepage_index
		if ($this->isReducedCheckoutEnabled() == false) {
			return;
		}
	
		$handles = $observer->getEvent()->getLayout()->getUpdate()->getHandles();
		$this->_update = $observer->getEvent()->getLayout()->getUpdate();
	
		// find the handle we're looking for
		if ( array_search('checkout_onepage_index', $handles) == true ) {
			$this->_update->addHandle('clearandfizzy_checkout_reduced');
	
			$this->_loginStepHandle();
			$this->_paymentStepHandle();
			$this->_shippingStepHandle();
			$this->_telephoneFaxHandle();
			return;
		} // end
	
		// find the handle we're looking for
		if ( array_search('customer_address_form', $handles) == true ) {
			$this->_telephoneFaxHandle();
			return;
		} // end
		
		return;
	
	} // end	

	/**
	 * Adds the handle "clearandfizzy_checkout_reduced_forceguestonly"
	 * @see app/design/frontend/base/default/layout/clearandfizzy/reducedcheckout/reducedcheckout.xml
	 */
	protected function _loginStepHandle() {
		// should we remove the login step..
		if ( Mage::getSingleton('customer/session')->isLoggedIn() == false &&
			 Mage::helper('clearandfizzy_reducedcheckout/data')->isLoginStepGuestOnly() == true) {
				$this->getUpdate()->addHandle('clearandfizzy_checkout_reduced_forceguestonly');
		} // end
	} // end
	
	/**
	 * Adds the Handle "clearandfizzy_checkout_reduced_skip_shippingmethod"
	 * @see app/design/frontend/base/default/layout/clearandfizzy/reducedcheckout/reducedcheckout.xml
	 */
	protected function _paymentStepHandle() {
		// should we remove the payment method step..
		if (Mage::helper('clearandfizzy_reducedcheckout/data')->skipShippingMethod() == true) {
			$this->getUpdate()->addHandle('clearandfizzy_checkout_reduced_skip_shippingmethod');
		} // end
	} // end
	
	/**
	 * Adds the Handle "clearandfizzy_checkout_reduced_skip_paymentmethod"
	 * @see app/design/frontend/base/default/layout/clearandfizzy/reducedcheckout/reducedcheckout.xml
	 */
	protected function _shippingStepHandle() {
		// should we remove the shipping method step..
		if (Mage::helper('clearandfizzy_reducedcheckout/data')->skipPaymentMethod() == true) {
			$this->getUpdate()->addHandle('clearandfizzy_checkout_reduced_skip_paymentmethod');
		} // end
	
	} // end
		
	/**
	 * Adds the Handle "clearandfizzy_checkout_reduced_hide_telephonefax"
	 * @see app/design/frontend/base/default/layout/clearandfizzy/reducedcheckout/reducedcheckout.xml
	 */
	protected function _telephoneFaxHandle(){
		// hide the telphone input fields
		if (Mage::helper('clearandfizzy_reducedcheckout/data')->hideTelephoneAndFax() == true) {
			$this->getUpdate()->addHandle('clearandfizzy_checkout_reduced_hide_telephonefax');
		} // end
	} // end
	
	/**
	 * Returns true if the the user is not logged in and email doesn't already exist.
	 * @return boolean
	 */
	protected function _isValidGuest() {
		if (Mage::getSingleton('customer/session')->isLoggedIn() || $this->_customerExists() ) {
			return false;
		} // end
		
		return true;
	} // end 

	/**
	 * Returns true if the last order's email address already exists
	 * @return boolean
	 */
	protected function _customerExists() {
		$helper = Mage::helper('clearandfizzy_reducedcheckout/order');
		$email 	= $helper->getEmail();
		
		$customer = Mage::getSingleton('customer/customer')
								->setStore(Mage::app()->getStore())
								->loadByEmail($email);

		if ($customer->getId()) {
			return true;
		} // end 
		
		return false;
		
	} // end 

	/**
	 * Returns true if Reduced Checkout is Enabled in the Admin Configuration
	 *
	 * @return boolean
	 */
	protected function isReducedCheckoutEnabled() {
		$enabled = Mage::getStoreConfig('clearandfizzy_reducedcheckout_settings/reducedcheckout/isenabled');
	
		// return early if we're not enabled
		if ($enabled != true) {
			return false;
		} // end
	
		return true;
	} // end
	
	protected function getUpdate() {
		return $this->_update;
	} // end	
	
} // end

