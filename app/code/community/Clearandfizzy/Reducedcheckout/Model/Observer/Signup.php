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
class Clearandfizzy_Reducedcheckout_Model_Observer_Signup extends Mage_Core_Model_Observer {
	
	protected $_helper;
	
	public function __construct() {
		$this->_helper = Mage::helper('clearandfizzy_reducedcheckout/order');
	} // end 
		
	/**
	 * Observes the user register event.
	 * If a guest customer decides to register then we should assign the order to that new customer account
	 * 
	 * @param Varien_Event_Observer $observer
	 */
	
	public function checkCustomerCreated(Varien_Event_Observer $observer) {
		$helper 	= $this->_helper;
		
		// if this session key does not exist then we have nothing to do
		if (Mage::registry($helper->getSessionKey()) == false) {
			return;
		} // end if
		
		$customer 	= $observer->getCustomer();
		$order 		= $helper->getOrder();
		
		$address 	= Mage::getModel('customer/address');
		$billing	= $order->getBillingAddress();
		$shipping	= $order->getShippingAddress();		

		// assign this order to the current customer
		$order = $this->_assignCustomerToOrder($order, $customer);
		
		// assign bill and shipping to the customer
		$billing = $this->_prepareAddress($billing, $customer);
		$address->setData($billing->getData());
		$customer->addAddress($address);
		$customer->save();
		
		// un-register this key
		Mage::unregister($helper->getSessionKey());
		
		return true;		
	} // end 
	
	/**
	 * Assign a customer to an order
	 * @param unknown $order
	 * @param unknown $customer
	 * @return unknown
	 */
	protected function _assignCustomerToOrder($order, $customer) {
	
		// assign the customer to the order
		$order->setCustomerId($customer->getId());
		$order->save();
	
		return $order;
	}
	
	/**
	 * Remove some values from the address
	 * @param unknown $address
	 * @param unknown $customer
	 * @return unknown
	 */
	protected function _prepareAddress($address, $customer) {
		$address->unsetData('entity_id');
		$address->setCustomerId($customer->getId());
		$address->setIsDefaultBilling(true);
	
		return $address;
	} // end	
	
} // end