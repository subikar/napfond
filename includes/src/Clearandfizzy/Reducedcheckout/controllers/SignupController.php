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
class Clearandfizzy_Reducedcheckout_SignupController extends Mage_Core_Controller_Front_Action
{
	
	/**
	 * Grab the form submission, inject needed data into the form post before forwarding onto the magento create account controller.
	 * 
	 */
	public function indexAction() {
		$helper = Mage::helper('clearandfizzy_reducedcheckout/order');

		//get the current order
		$order = $helper->getOrder();
		
		// grab the details we need from the order
		$firstname = $order->getCustomerFirstname();
		$lastname = $order->getCustomerLastname();
		$email = $order->getCustomerEmail();
		$password = $this->getRequest()->getParam('password');
		$confirmation = $this->getRequest()->getParam('password');
		
		// put them in the form submission 
		$this->getRequest()->setPost('firstname', $firstname);
		$this->getRequest()->setPost('lastname', $lastname);
		$this->getRequest()->setPost('email', $email);
		$this->getRequest()->setPost('password', $password);
		$this->getRequest()->setPost('confirmation', $password);
		$this->getRequest()->setPost('success_url', Mage::getUrl('customer/account/*'));
		
		// there are two parts to this process..
		// Once the customer account is created we observe the "customer_register_success" event
		// and add the addresses / order etc in there.
		
		// set this before any observers fire
		Mage::register($helper->getSessionKey(), true);
		
		// forward onto the account creation action
		$this->_forward('createpost','account','customer');		
		
	} // end 
	
} // end class