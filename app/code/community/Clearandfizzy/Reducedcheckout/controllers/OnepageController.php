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
require_once "Mage/Checkout/controllers/OnepageController.php";
class Clearandfizzy_Reducedcheckout_OnepageController extends Mage_Checkout_OnepageController
{
	public $layout;
	protected $_helper;


	/**
	 * (non-PHPdoc)
	 * @see Mage_Core_Controller_Varien_Action::_construct()
	 */
	public function _construct() {
		parent::_construct();
		$this->_helper = Mage::helper('clearandfizzy_reducedcheckout');
	} // end

	/**
	 * (non-PHPdoc)
	 * @see Mage_Checkout_OnepageController::saveMethodAction()
	 */
	public function saveMethodAction()
	{
		if ($this->_expireAjax()) {
			return;
		} // end if

		// set the checkout method
		if ($this->getRequest()->isPost()) {
			$method = $this->getCheckoutMethod();
			$result = $this->getOnepage()->saveCheckoutMethod($method);
		} // end if

	} // end if


	/**
	 * Checks the System > Configuration Setting for this extension and sets the 
	 * CheckoutMethod as appropriate
	 * 
	 * @return Ambigous <mixed, unknown>
	 */
	private function getCheckoutMethod() {

		switch ( $this->_helper->isLoginStepGuestOnly() ) {

			case true:
				$method = "guest";
			break;

			default:
				$method = $this->getRequest()->getPost('method');
			break;

		} // end

		return $method;

	} /// end


	/**
	 * (non-PHPdoc)
	 * @see Mage_Checkout_OnepageController::saveShippingMethodAction()
	 * $gotonext = false forces the method not to go to the next section and return to the calling method
	 */
	public function saveShippingMethodAction($gotonext = true ) {

		if ($this->_expireAjax()) {
			return;
		} // end if

		// this is the default way
		$shipping = $this->getRequest()->getPost('shipping_method', '');

		// override the default value if we need to
		if ( $this->_helper->skipShippingMethod() == true) {
			$shipping = $this->_helper->getShippingMethod();
		} // end if

		// set the shipping method
		$result = $this->getOnepage()->saveShippingMethod($shipping);

		// calculations for the checkout totals
		$this->getOnepage()->getQuote()->collectTotals();
		$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
		$this->getOnepage()->getQuote()->collectTotals()->save();

		// save shipping method event
		Mage::dispatchEvent('checkout_controller_onepage_save_shipping_method',
				array('request'=>$this->getRequest(),
						'quote'=>$this->getOnepage()->getQuote()));


		$this->getOnepage()->getQuote()->setTotalsCollectedFlag(false);

		// attempt to load the next section
		if ( $gotonext == true ) {
			$result = $this->getNextSection($result, $current = 'shippingmethod');
			$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
		} // end if


	} // end

	/**
	 * (non-PHPdoc)
	 * @see Mage_Checkout_OnepageController::savePaymentAction()
	 */
	public function savePaymentAction( $gotonext = true ) {

		if ($this->_expireAjax()) {
			return;
		} // end if

		// this is the default way
		$data = $this->getRequest()->getPost('payment', false);

		// override the default value if we need to
		if ( $this->_helper->skipPaymentMethod() == true) {
			$payment = $this->_helper->getPaymentMethod();
			$data = array('method' => $payment);
		} // end if

 		$result = $this->getOnepage()->savePayment($data);

		// get section and redirect data
		$redirectUrl = $this->getOnepage()->getQuote()->getPayment()->getCheckoutRedirectUrl();

		// attempt to load the next section
		if ( $gotonext == true ) {
			$result = $this->getNextSection($result, $current = 'payment');
			$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
		} // end if

	} // end

	/**
	 * (non-PHPdoc)
	 * @see Mage_Checkout_OnepageController::saveShippingAction()
	 */
	public function saveShippingAction($gotonext = true) {

		if ($this->_expireAjax()) {
			return;
		}

		if ($this->getRequest()->isPost()) {

			$data = $this->getRequest()->getPost('shipping', array());
			$customerAddressId = $this->getRequest()->getPost('shipping_address_id', false);

			// save the billing address info
			$result = $this->getOnepage()->saveShipping($data, $customerAddressId);
		} // end 

		// attempt to load the next section
		if ( $gotonext == true ) {
			$result = $this->getNextSection($result, $current = 'billing');
			$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
		} // end if

	} // end

	/**
	 * (non-PHPdoc)
	 * @see Mage_Checkout_OnepageController::saveBillingAction()
	 */
	public function saveBillingAction()
	{

		if ($this->_expireAjax()) {
			return;
		}

		if ($this->getRequest()->isPost()) {

			if ( $this->_helper->isLoginStepGuestOnly() == true) {
				// set the checkout method
				$this->saveMethodAction();
			} // end if


			$data = $this->getRequest()->getPost('billing', array());
			$customerAddressId = $this->getRequest()->getPost('billing_address_id', false);

			if (isset($data['email'])) {
				$data['email'] = trim($data['email']);
			} // end if

			// save the billing address info
			$result = $this->getOnepage()->saveBilling($data, $customerAddressId);

			// render the onepage review
			if (!isset($result['error'])) {

				/* check quote for virtual */
				if ($this->getOnepage()->getQuote()->isVirtual()) {

					// find out which section we should go to next
					$result = $this->getNextSection($result, $current = 'billing');

				} elseif (isset($data['use_for_shipping']) && $data['use_for_shipping'] == 1) {

					// find out which section we should go to next
					$result = $this->getNextSection($result, $current = 'billing');
					$result['duplicateBillingInfo'] = 'true';

				} else {

					// go to the shipping section
					$result['goto_section'] = 'shipping';

				} // end if
			} // end

			$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
		}
	} // end


	/**
	 * (non-PHPdoc)
	 * @see Mage_Checkout_OnepageController::_getReviewHtml()
	 */
	protected function _getReviewHtml() {
	 	$layout = $this->getLayout();
		$update = $layout->getUpdate();
		$update->merge('checkout_onepage_review');

		$layout->generateXml();
		$layout->generateBlocks();

		$output = $layout->getBlock('root')->toHtml();
		return $output;

	} // end

	/**
	 * (non-PHPdoc)
	 * @see Mage_Checkout_OnepageController::progressAction()
	 */
	public function progressAction()
	{
		$version_array = Mage::getVersionInfo();
		
		//	Quick fix Magento 1.8 and pre 1.8 have different methods to generate the right hand progress bar.
		if ( $version_array['major'] == 1 && $version_array['minor'] < 8 ) {
			return $this->preV8ProgressAction();
		} // end 
		
		return parent::progressAction();
	} // end 
	
	
	/**
	 * Quick fix Magento 1.8 and pre 1.8 have different methods to generate the right hand progress bar.
	 * This method runs if magento 1.7 or older is being used.
	 * @return string
	 */
	protected function preV8ProgressAction() {
		$layout = $this->getLayout();
		$update = $layout->getUpdate();
		$update->load('checkout_onepage_progress');
		$layout->generateXml();
		$layout->generateBlocks();
		$output = $layout->getOutput();
		
		$this->renderLayout();
		
	} // end 
	
	/**
	 * Returns html for the next step to display depending on logic set in the System > Configuration
	 * 
	 * @param array $result
	 * @param string $current Current step code
	 * @return multitype:string html <string, unknown>
	 */
	private function getNextSection($result, $current) {

		// set the shipping method
		if ( $this->_helper->skipShippingMethod() == true) {
			$this->saveShippingMethodAction($gotonext = false);
		} // end

		// set the payment method
		if ( $this->_helper->skipPaymentMethod() == true) {
			$this->savePaymentAction( $gotonext = false );
		} // end if

		switch ($current) {

			case "billing":
				if ($this->_helper->skipShippingMethod() == true && $this->_helper->skipPaymentMethod() == true) {

					$result['goto_section'] = 'review';
					$result['allow_sections'] = array('review');
					$result['update_section'] = array(
							'name' => 'review',
							'html' => $this->_getReviewHtml()
					);

				} elseif ( $this->_helper->skipShippingMethod() == true && $this->_helper->skipPaymentMethod() == false ) {

	                $result['goto_section'] = 'payment';
	                $result['allow_sections'] = array('payment');
	                $result['update_section'] = array(
	                    'name' => 'payment-method',
	                    'html' => $this->_getPaymentMethodsHtml()
	                );

				} elseif ( $this->_helper->skipShippingMethod() == false ) {

					$result['goto_section'] = 'shipping_method';
					$result['allow_sections'] = array('shipping');
                    $result['update_section'] = array(
                        'name' => 'shipping-method',
                        'html' => $this->_getShippingMethodsHtml()
                    );

				}// end
			break;

			case "shippingmethod":

				if ( $this->_helper->skipPaymentMethod() == true ) {

					$result['goto_section'] = 'review';
					$result['allow_sections'] = array('review');
					$result['update_section'] = array(
							'name' => 'review',
							'html' => $this->_getReviewHtml()
					);

				} elseif (  $this->_helper->skipPaymentMethod() == false ) {

					$result['goto_section'] = 'payment';
					$result['allow_sections'] = array('payment');
					$result['update_section'] = array(
							'name' => 'payment-method',
							'html' => $this->_getPaymentMethodsHtml()
					);
				} // end

			break;

			case "payment":
				$result['goto_section'] = 'review';
				$result['update_section'] = array(
						'name' => 'review',
						'html' => $this->_getReviewHtml()
				);
			break;

		} // end sw

		return $result;

	} // end


} // end class