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
class Clearandfizzy_Reducedcheckout_Helper_Order extends Mage_Core_Helper_Abstract {
	
	/**
	 * We use this during the user signup phase.
	 * @var string
	 */
	public $_session_key = 'reducedcheckout_register_key';	
	
	public function getSessionKey() {
		return $this->_session_key;
	} // end 
	
	public function getOrder() {
		//get the order id from the session
		$session = Mage::getSingleton('checkout/session');
		$lastOrderId = $session->getLastOrderId();
		
		// load the order
		$order = Mage::getSingleton('sales/order');
		$order->load($lastOrderId);
		
		return $order;
	} // end
	
	/**
	 *
	 * @return Ambigous <mixed, unknown, multitype:>
	 */
	public function getEmail() {
		$order = $this->getOrder();
	
		// get the orders email address
		$email = $order->getCustomerEmail();
		return $email;
	} // end
	
}