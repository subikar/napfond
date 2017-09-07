<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Checkout
 * @copyright   Copyright (c) 2014 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Onepage controller for checkout
 *
 * @category    Mage
 * @package     Mage_Checkout
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Mage_Checkout_OnepageController extends Mage_Checkout_Controller_Action
{
    /**
     * List of functions for section update
     *
     * @var array
     */
    protected $_sectionUpdateFunctions = array(
        'payment-method'  => '_getPaymentMethodsHtml',
        'shipping-method' => '_getShippingMethodsHtml',
        'review'          => '_getReviewHtml',
    );

    /**
     * @var Mage_Sales_Model_Order
     */
    protected $_order;

    /**
     * Predispatch: should set layout area
     *
     * @return Mage_Checkout_OnepageController
     */
    public function preDispatch()
    {
        parent::preDispatch();
        $this->_preDispatchValidateCustomer();

        $checkoutSessionQuote = Mage::getSingleton('checkout/session')->getQuote();
        if ($checkoutSessionQuote->getIsMultiShipping()) {
            $checkoutSessionQuote->setIsMultiShipping(false);
            $checkoutSessionQuote->removeAllAddresses();
        }

        if (!$this->_canShowForUnregisteredUsers()) {
            $this->norouteAction();
            $this->setFlag('',self::FLAG_NO_DISPATCH,true);
            return;
        }
		
	
        return $this;
    }

    /**
     * Send Ajax redirect response
     *
     * @return Mage_Checkout_OnepageController
     */
    protected function _ajaxRedirectResponse()
    {
        $this->getResponse()
            ->setHeader('HTTP/1.1', '403 Session Expired')
            ->setHeader('Login-Required', 'true')
            ->sendResponse();
        return $this;
    }

    /**
     * Validate ajax request and redirect on failure
     *
     * @return bool
     */
    protected function _expireAjax()
    {
        if (!$this->getOnepage()->getQuote()->hasItems()
            || $this->getOnepage()->getQuote()->getHasError()
            || $this->getOnepage()->getQuote()->getIsMultiShipping()
        ) {
            $this->_ajaxRedirectResponse();
            return true;
        }
        $action = $this->getRequest()->getActionName();
        if (Mage::getSingleton('checkout/session')->getCartWasUpdated(true)
            && !in_array($action, array('index', 'progress'))
        ) {
            $this->_ajaxRedirectResponse();
            return true;
        }
        return false;
    }

    /**
     * Get shipping method step html
     *
     * @return string
     */
    protected function _getShippingMethodsHtml()
    {
        $layout = $this->getLayout();
        $update = $layout->getUpdate();
        $update->load('checkout_onepage_shippingmethod');
        $layout->generateXml();
        $layout->generateBlocks();
        $output = $layout->getOutput();
        return $output;
    }

    /**
     * Get payment method step html
     *
     * @return string
     */
    protected function _getPaymentMethodsHtml()
    {
        $layout = $this->getLayout();
        $update = $layout->getUpdate();
        $update->load('checkout_onepage_paymentmethod');
        $layout->generateXml();
        $layout->generateBlocks();
        $output = $layout->getOutput();
        return $output;
    }

    /**
     * Return block content from the 'checkout_onepage_additional'
     * This is the additional content for shipping method
     *
     * @return string
     */
    protected function _getAdditionalHtml()
    {
        $layout = $this->getLayout();
        $update = $layout->getUpdate();
        $update->load('checkout_onepage_additional');
        $layout->generateXml();
        $layout->generateBlocks();
        $output = $layout->getOutput();
        Mage::getSingleton('core/translate_inline')->processResponseBody($output);
        return $output;
    }

    /**
     * Get order review step html
     *
     * @return string
     */
    protected function _getReviewHtml()
    {
		
		
        return $this->getLayout()->getBlock('root')->toHtml();
    }

    /**
     * Get one page checkout model
     *
     * @return Mage_Checkout_Model_Type_Onepage
     */
    public function getOnepage()
    {
        return Mage::getSingleton('checkout/type_onepage');
    }

    /**
     * Checkout page
     */
    public function indexAction()
    {
        if (!Mage::helper('checkout')->canOnepageCheckout()) {
            Mage::getSingleton('checkout/session')->addError($this->__('The onepage checkout is disabled.'));
            $this->_redirect('checkout/cart');
            return;
        }
        $quote = $this->getOnepage()->getQuote();
 	
		
        if (!$quote->hasItems() || $quote->getHasError()) {
            $this->_redirect('checkout/cart');
            return;
        }
        if (!$quote->validateMinimumAmount()) {
            $error = Mage::getStoreConfig('sales/minimum_order/error_message') ?
                Mage::getStoreConfig('sales/minimum_order/error_message') :
                Mage::helper('checkout')->__('Subtotal must exceed minimum order amount');

            Mage::getSingleton('checkout/session')->addError($error);
            $this->_redirect('checkout/cart');
            return;
        }
        Mage::getSingleton('checkout/session')->setCartWasUpdated(false);
        Mage::getSingleton('customer/session')->setBeforeAuthUrl(Mage::getUrl('*/*/*', array('_secure' => true)));
		
		
        $this->getOnepage()->initCheckout();
        $this->loadLayout();
        $this->_initLayoutMessages('customer/session');
        $this->getLayout()->getBlock('head')->setTitle($this->__('Checkout'));
        $this->renderLayout();
    }

    /**
     * Refreshes the previous step
     * Loads the block corresponding to the current step and sets it
     * in to the response body
     *
     * This function is called from the reloadProgessBlock
     * function from the javascript
     *
     * @return string|null
     */
    public function progressAction()
    {
        // previous step should never be null. We always start with billing and go forward
        $prevStep = $this->getRequest()->getParam('prevStep', false);

        if ($this->_expireAjax() || !$prevStep) {
            return null;
        }

        $layout = $this->getLayout();
        $update = $layout->getUpdate();
        /* Load the block belonging to the current step*/
        $update->load('checkout_onepage_progress_' . $prevStep);
        $layout->generateXml();
        $layout->generateBlocks();
        $output = $layout->getOutput();
        $this->getResponse()->setBody($output);
        return $output;
    }

    /**
     * Shipping method action
     */
    public function shippingMethodAction()
    {
        if ($this->_expireAjax()) {
            return;
        }
        $this->loadLayout(false);
        $this->renderLayout();
    }

    /**
     * Review page action
     */
    public function reviewAction()
    {
        if ($this->_expireAjax()) {
            return;
        }
        $this->loadLayout(false);
        $this->renderLayout();
    }

    /**
     * Order success action
     */
    public function successAction()
    {
        $session = $this->getOnepage()->getCheckout();
        if (!$session->getLastSuccessQuoteId()) {
            $this->_redirect('checkout/cart');
            return;
        }

        $lastQuoteId = $session->getLastQuoteId();
        $lastOrderId = $session->getLastOrderId();
        $lastRecurringProfiles = $session->getLastRecurringProfileIds();
        if (!$lastQuoteId || (!$lastOrderId && empty($lastRecurringProfiles))) {
            $this->_redirect('checkout/cart');
            return;
        }

        $session->clear();
        $this->loadLayout();
        $this->_initLayoutMessages('checkout/session');
        Mage::dispatchEvent('checkout_onepage_controller_success_action', array('order_ids' => array($lastOrderId)));
        $this->renderLayout();
    }

    /**
     * Failure action
     */
    public function failureAction()
    {
        $lastQuoteId = $this->getOnepage()->getCheckout()->getLastQuoteId();
        $lastOrderId = $this->getOnepage()->getCheckout()->getLastOrderId();

        if (!$lastQuoteId || !$lastOrderId) {
            $this->_redirect('checkout/cart');
            return;
        }

        $this->loadLayout();
        $this->renderLayout();
    }


    /**
     * Get additional info action
     */
    public function getAdditionalAction()
    {
        $this->getResponse()->setBody($this->_getAdditionalHtml());
    }

    /**
     * Address JSON
     */
    public function getAddressAction()
    {
        if ($this->_expireAjax()) {
            return;
        }
        $addressId = $this->getRequest()->getParam('address', false);
        if ($addressId) {
            $address = $this->getOnepage()->getAddress($addressId);

            if (Mage::getSingleton('customer/session')->getCustomer()->getId() == $address->getCustomerId()) {
                $this->getResponse()->setHeader('Content-type', 'application/x-json');
                $this->getResponse()->setBody($address->toJson());
            } else {
                $this->getResponse()->setHeader('HTTP/1.1','403 Forbidden');
            }
        }
    }

    /**
     * Save checkout method
     */
    public function saveMethodAction()
    {
        if ($this->_expireAjax()) {
            return;
        }
        if ($this->getRequest()->isPost()) {
            $method = $this->getRequest()->getPost('method');
            $result = $this->getOnepage()->saveCheckoutMethod($method);
            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
        }
    }

    /**
     * Save checkout billing address
     */
    public function saveBillingAction()
    {
        if ($this->_expireAjax()) {
            return;
        }
        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost('billing', array());
            $customerAddressId = $this->getRequest()->getPost('billing_address_id', false);

            if (isset($data['email'])) {
                $data['email'] = trim($data['email']);
            }
            $result = $this->getOnepage()->saveBilling($data, $customerAddressId);

            if (!isset($result['error'])) {
                if ($this->getOnepage()->getQuote()->isVirtual()) {
                    $result['goto_section'] = 'payment';
                    $result['update_section'] = array(
                        'name' => 'payment-method',
                        'html' => $this->_getPaymentMethodsHtml()
                    );
                } elseif (isset($data['use_for_shipping']) && $data['use_for_shipping'] == 1) {
                     
					
                $result['goto_section'] = 'payment';
                $result['update_section'] = array(
                    'name' => 'payment-method',
                    'html' => $this->_getPaymentMethodsHtml()
                );					

                    $result['allow_sections'] = array('shipping');
                    $result['duplicateBillingInfo'] = 'true';
                } else {
                    $result['goto_section'] = 'shipping';
                }
            }

            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
        }
    }

    /**
     * Shipping address save action
     */
    public function saveShippingAction()
    {
        if ($this->_expireAjax()) {
            return;
        }
        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost('shipping', array());
            $customerAddressId = $this->getRequest()->getPost('shipping_address_id', false);
            $result = $this->getOnepage()->saveShipping($data, $customerAddressId);

            if (!isset($result['error'])) {
			
                $result['goto_section'] = 'payment';
                $result['update_section'] = array(
                    'name' => 'payment-method',
                    'html' => $this->_getPaymentMethodsHtml()
                );
			
			

            }
            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
        }
    }

	/*****************************************Apply shipping to checkout**************************/
	
function applyShippingToAmount(){
    //$resource = Mage::getSingleton('core/resource');
    $readConnection = Mage::helper('function/more')->getDbReadObject();
    $writeConnection = Mage::helper('function/more')->getDbWriteObject();
	
	$shippingaddress = Mage::getSingleton('checkout/cart')->getQuote()->getShippingAddress();
	$fedex_zip_file_path = Mage::getBaseDir('media').'/fedex_zipcode/fedex_zip.csv';	
	$search_zipCode = $shippingaddress->getPostcode();
	
	$query = "SELECT count(*) as zipCodeCountRow FROM fedex_zipcode where TRIM(zipcode)='".trim($search_zipCode)."'";     
	$zipcodeCountRow = $readConnection->fetchOne($query);
	
	
	/* $zipCodeArray = array();
	
	$file = fopen($fedex_zip_file_path,"r");
	
		while(! feof($file))
		  {
			$lineArr = (fgetcsv($file));
			$zipCodeArray[] = $lineArr[0];
		  }
	
		fclose($file);	*/
	
		if($zipcodeCountRow == 0 && $search_zipCode != '')
		{
			return "zipErr";
		}
		$rateArray = array(); 
        
		$price = Mage::helper('function/more')->getShippingPrice();
        
		//$address->setCollectShippingRates(true);
        // Find if our shipping has been included.
        $rates = $shippingaddress->collectShippingRates()->getGroupedAllShippingRates();
 
			
					foreach ($rates as $carrier) {						
						foreach ($carrier as $rate) {							
							$dataRate = $rate->getData();
							$rateArray[] = $dataRate['code'];
							$rate->setPrice($price);
							$rate->save(); 							
						}
					}
				 
				if(count($rateArray) > 0)
				{

					$shippingaddress->setShippingAmount($price);
					$shippingaddress->setBaseShippingAmount($price);
					$shippingaddress->setCollectShippingRates(true);
				
						if(in_array('fedex_STANDARD_OVERNIGHT',$rateArray))
						$data = 'fedex_STANDARD_OVERNIGHT';
						elseif(in_array('fedex_PRIORITY_OVERNIGHT',$rateArray))
						$data = 'fedex_PRIORITY_OVERNIGHT';
						else
						$data = 'flatrate_flatrate';
				
				 
					$shippingaddress->setShippingMethod($data);
					$shippingaddress->save();
					//$address->setShippingMethod($data);

					
				//$address->setShippingMethod($data)->setCollectShippingRates(true);
                Mage::getSingleton('checkout/session')->getQuote()->save();
			/////////////////////////////////////////////////////////////////////////////////////////
			    $this->getOnepage()->saveShippingMethod($data);
					
				}	
	Mage::getSingleton('checkout/session')->getQuote()->setTotalsCollectedFlag(false);
	Mage::getSingleton('checkout/session')->getQuote()->collectTotals();
	Mage::getSingleton('checkout/session')->getQuote()->save();		
}

	/*********************************************************************************************/		
	
	
	
    /**
     * Shipping method save action
     */
    public function saveShippingMethodAction()
    {
        if ($this->_expireAjax()) {
            return;
        }
        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost('shipping_method', '');
			
			
			
            $result = $this->getOnepage()->saveShippingMethod($data);
            // $result will contain error data if shipping method is empty
            if (!$result) {
                Mage::dispatchEvent(
                    'checkout_controller_onepage_save_shipping_method',
                     array(
                          'request' => $this->getRequest(),
                          'quote'   => $this->getOnepage()->getQuote()));
                $this->getOnepage()->getQuote()->collectTotals();
                $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));

			
                $this->loadLayout('checkout_onepage_review');
                $result['goto_section'] = 'review';
                $result['update_section'] = array(
                    'name' => 'review',
                    'html' => $this->_getReviewHtml()
                );
            }
            $this->getOnepage()->getQuote()->collectTotals()->save();
            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
        }
    }

    /**
     * Save payment ajax action
     *
     * Sets either redirect or a JSON response
     */
    public function savePaymentAction()
    {
        if ($this->_expireAjax()) {
            return;
        }
        try {
            if (!$this->getRequest()->isPost()) {
                $this->_ajaxRedirectResponse();
                return;
            }
		$data = $this->getRequest()->getPost('payment', array());
		
		$shippingaddress = Mage::getSingleton('checkout/cart')->getQuote()->getShippingAddress();
		
		$shippingaddress_getShippingMethod = $shippingaddress->getShippingMethod();
		
		
	/*	
if($data['method'] == 'payucheckout_shared')		
{
	
				$discount = 5; //your discount percent
				
                $grandTotal = $shippingaddress->getGrandTotal();
                $baseGrandTotal = $shippingaddress->getBaseGrandTotal();				
				
				if($shippingaddress->getSubTotalWithDiscount() > 0){
                $subTotalWithDiscount = $shippingaddress->getSubTotalWithDiscount();
                $baseSubTotalWithDiscount = $shippingaddress->getBaseSubTotalWithDiscount();
				}
				else{
				$subTotalWithDiscount = $shippingaddress->getSubTotal();
                $baseSubTotalWithDiscount = $shippingaddress->getBaseSubTotal();
				}
				
				
                $totals = $subTotalWithDiscount;
                $baseTotals = $baseSubTotalWithDiscount;
				 
                $shippingaddress->setFeeAmount(-$totals * $discount / 100);
                $shippingaddress->setBaseFeeAmount(-$baseTotals * $discount / 100);

                $shippingaddress->setGrandTotal($grandTotal + $shippingaddress->getFeeAmount());
				
                $shippingaddress->setBaseGrandTotal($baseGrandTotal + $shippingaddress->getBaseFeeAmount());
				$shippingaddress->save();
				//$this->getOnepage()->getQuote()->collectTotals()->save();
}	*/
	
/////////////////////////////////////////set fedex data///////////////////////////




			$connection = Mage::helper('function/more')->getDbReadObject();
			$write = Mage::helper('function/more')->getDbWriteObject();	
			$read = Mage::helper('function/more')->getDbReadObject();
			
			$cartSubTotal = Mage::helper('checkout/cart')->getQuote()->getSubtotal();
			
			$cartSubTotalWithDiscount = Mage::helper('checkout/cart')->getQuote()->getSubtotalWithDiscount();
	
	$quoteId = Mage::helper('checkout/cart')->getQuote()->getId();
	
	$cartSubTotal = $read->fetchOne("SELECT subtotal FROM sales_flat_quote where entity_id='".$quoteId."'");
	
	$cartSubTotalWithDiscount = $read->fetchOne("SELECT subtotal_with_discount FROM sales_flat_quote where entity_id='".$quoteId."'");


	
			if($cartSubTotalWithDiscount > $cartSubTotal)
			$cartSubTotal = $cartSubTotalWithDiscount;
		
		
			$minimumOrderAmount = Mage::getStoreConfig('carriers/freeshipping/free_shipping_subtotal');			
			$getQuote = Mage::getSingleton('checkout/session')->getQuote();
			
			
			Mage::getSingleton('core/session')->setDropCategory($data['drop_category']);
			
			$key = 'setFedexCommercialInvoicePurposeKey';


						$sessionId = Mage::getSingleton("core/session")->getEncryptedSessionId();
			$paymentMethod = $data['method'];
		 
			if($data['method'] == 'phoenix_cashondelivery')
			{
			$value = 'SOLD';
			$value = 'NOT_SOLD';
			
			Mage::register($key, $value, false); 
						$query = 'SELECT clr_purpose FROM a_fedex_clr_purpose WHERE mag_session_id = "'.$sessionId.'" and quote_id="'.$getQuote->getId().'"  LIMIT 1';
				 
						$clr_purpose = $read->fetchOne($query);			
						
						if($clr_purpose != '')
						 {
								$query = 'UPDATE a_fedex_clr_purpose SET clr_purpose = "'.$value.'" WHERE mag_session_id = "'.$sessionId.'" and quote_id="'.$getQuote->getId().'" LIMIT 1';
								$write->query($query);		 
						 }
						else{
								$query = 'INSERT INTO a_fedex_clr_purpose SET clr_purpose = "'.$value.'",mag_session_id = "'.$sessionId.'",quote_id="'.$getQuote->getId().'" ';
								$write->query($query);		 		
						}			
			
			}
			else{
			
							
							$cartSubTotal = Mage::helper('checkout/cart')->getQuote()->getSubtotal();			

							if($cartSubTotal <= 5000)
								$value = 'NOT_SOLD';
							else
								$value = 'SOLD';
							$value = 'NOT_SOLD';
							
							$query = 'SELECT clr_purpose FROM a_fedex_clr_purpose WHERE mag_session_id = "'.$sessionId.'" and quote_id="'.$getQuote->getId().'"  LIMIT 1';
					 
							$clr_purpose = $read->fetchOne($query);			
							
							if($clr_purpose != '')
							 {
									$query = 'UPDATE a_fedex_clr_purpose SET clr_purpose = "'.$value.'" WHERE mag_session_id = "'.$sessionId.'" and quote_id="'.$getQuote->getId().'" LIMIT 1';
									$write->query($query);
							 }
							else{
									$query = 'INSERT INTO a_fedex_clr_purpose SET clr_purpose = "'.$value.'",mag_session_id = "'.$sessionId.'",quote_id="'.$getQuote->getId().'" ';
									$write->query($query);
							}		
			
			}			
/////////////////////////////////////////////////////////////////////////////////////////////			
			

            		
            $result = $this->getOnepage()->savePayment($data);
            // get section and redirect data
            $redirectUrl = $this->getOnepage()->getQuote()->getPayment()->getCheckoutRedirectUrl();
            if (empty($result['error']) && !$redirectUrl) {
			
			
			$query = 'SELECT clr_purpose FROM a_fedex_clr_purpose WHERE mag_session_id = "'.$sessionId.'" and quote_id="'.$getQuote->getId().'"  LIMIT 1';
			 
			 $setFedexCommercialInvoicePurpose_Key = $read->fetchOne($query);	
					
			if($setFedexCommercialInvoicePurpose_Key=='SOLD')
			 $data = 'fedex_STANDARD_OVERNIGHT';
			else
			 $data = 'fedex_PRIORITY_OVERNIGHT';
			
			 /*if($cartSubTotal >= $minimumOrderAmount)	
			 $data = 'freeshipping_freeshipping';
			 else
			 $data = 'flatrate_flatrate';
			 $data = 'flatrate_flatrate';*/
			
			//////////////////////////////////Collect shipping address///////////////////////////////	

		$rateArray = array(); 

        $price = Mage::helper('function/more')->getShippingPrice();
		//$address->setCollectShippingRates(true);
        // Find if our shipping has been included.
		
		
		
        $rates = $shippingaddress->collectShippingRates()->getGroupedAllShippingRates();
 
			
					foreach ($rates as $carrier) {						
						foreach ($carrier as $rate) {							
							$dataRate = $rate->getData();
							$rateArray[] = $dataRate['code'];
							$rate->setPrice($price);
							$rate->save(); 							
						}
					}
				 
				if(count($rateArray) > 0)
				{
        $shippingaddress->setShippingAmount($price);
        $shippingaddress->setBaseShippingAmount($price);
        $shippingaddress->setCollectShippingRates(true);
				
				if(in_array('fedex_STANDARD_OVERNIGHT',$rateArray))
				$data = 'fedex_STANDARD_OVERNIGHT';
				elseif(in_array('fedex_PRIORITY_OVERNIGHT',$rateArray))	
				$data = 'fedex_PRIORITY_OVERNIGHT';
				else
				$data = 'flatrate_flatrate';	
				
				
			
				$shippingaddress->setShippingMethod($data);
				$shippingaddress->save();
				//$address->setShippingMethod($data);
				
				//$address->setShippingMethod($data)->setCollectShippingRates(true);
                Mage::getSingleton('checkout/session')->getQuote()->save();				
			/////////////////////////////////////////////////////////////////////////////////////////
			$result = $this->getOnepage()->saveShippingMethod($data);
			
			$shippingaddress2 = Mage::getSingleton('checkout/cart')->getQuote()->getShippingAddress();	
		
			
		
		
		
		
		
            //$result will contain error data if shipping method is empty
            if (!$result) {
                Mage::dispatchEvent(
                    'checkout_controller_onepage_save_shipping_method',
                     array(
                          'request' => $this->getRequest(),
                          'quote'   => $this->getOnepage()->getQuote()));
						  
                $this->getOnepage()->getQuote()->collectTotals();
                $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));

			
                $this->loadLayout('checkout_onepage_review');
                $result['goto_section'] = 'review';
                $result['update_section'] = array(
                    'name' => 'review',
                    'html' => $this->_getReviewHtml()
                );
            }
            $this->getOnepage()->getQuote()->collectTotals()->save();
            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));		
				}

            }
			 
            if ($redirectUrl) {
                $result['redirect'] = $redirectUrl;
            }
        } catch (Mage_Payment_Exception $e) {
            if ($e->getFields()) {
                $result['fields'] = $e->getFields();
            }
            $result['error'] = $e->getMessage();
        } catch (Mage_Core_Exception $e) {
            $result['error'] = $e->getMessage();
        } catch (Exception $e) {
            Mage::logException($e);
            $result['error'] = $this->__('Unable to set Payment Method.');
        }
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
        
     
        
    }

    /**
     * Get Order by quoteId
     *
     * @throws Mage_Payment_Model_Info_Exception
     * @return Mage_Sales_Model_Order
     */
    protected function _getOrder()
    {
        if (is_null($this->_order)) {
            $this->_order = Mage::getModel('sales/order')->load($this->getOnepage()->getQuote()->getId(), 'quote_id');
            if (!$this->_order->getId()) {
                throw new Mage_Payment_Model_Info_Exception(Mage::helper('core')->__("Can not create invoice. Order was not found."));
            }
        }
        return $this->_order;
    }

    /**
     * Create invoice
     *
     * @return Mage_Sales_Model_Order_Invoice
     */
    protected function _initInvoice()
    {
        $items = array();
        foreach ($this->_getOrder()->getAllItems() as $item) {
            $items[$item->getId()] = $item->getQtyOrdered();
        }
        /* @var $invoice Mage_Sales_Model_Service_Order */
        $invoice = Mage::getModel('sales/service_order', $this->_getOrder())->prepareInvoice($items);
        $invoice->setEmailSent(true)->register();

        Mage::register('current_invoice', $invoice);
        return $invoice;
    }

    /**
     * Create order action
     */
    public function saveOrderAction()
    {
        if (!$this->_validateFormKey()) {
            $this->_redirect('*/*');
            return;
        }

        if ($this->_expireAjax()) {
            return;
        }

        $result = array();
        try {
            $requiredAgreements = Mage::helper('checkout')->getRequiredAgreementIds();
            if ($requiredAgreements) {
                $postedAgreements = array_keys($this->getRequest()->getPost('agreement', array()));
                $diff = array_diff($requiredAgreements, $postedAgreements);
                if ($diff) {
                    $result['success'] = false;
                    $result['error'] = true;
                    $result['error_messages'] = $this->__('Please agree to all the terms and conditions before placing the order.');
                    $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
                    return;
                }
            }

            $data = $this->getRequest()->getPost('payment', array());
            if ($data) {
                $data['checks'] = Mage_Payment_Model_Method_Abstract::CHECK_USE_CHECKOUT
                    | Mage_Payment_Model_Method_Abstract::CHECK_USE_FOR_COUNTRY
                    | Mage_Payment_Model_Method_Abstract::CHECK_USE_FOR_CURRENCY
                    | Mage_Payment_Model_Method_Abstract::CHECK_ORDER_TOTAL_MIN_MAX
                    | Mage_Payment_Model_Method_Abstract::CHECK_ZERO_TOTAL;
                $this->getOnepage()->getQuote()->getPayment()->importData($data);
            }

            $this->getOnepage()->saveOrder();

            $redirectUrl = $this->getOnepage()->getCheckout()->getRedirectUrl();
            $result['success'] = true;
            $result['error']   = false;
			
			Mage::getSingleton('core/session')->unsEncryptedSessionId();
			
        } catch (Mage_Payment_Model_Info_Exception $e) {
            $message = $e->getMessage();
            if (!empty($message)) {
                $result['error_messages'] = $message;
            }
            $result['goto_section'] = 'payment';
            $result['update_section'] = array(
                'name' => 'payment-method',
                'html' => $this->_getPaymentMethodsHtml()
            );
        } catch (Mage_Core_Exception $e) {
            Mage::logException($e);
            Mage::helper('checkout')->sendPaymentFailedEmail($this->getOnepage()->getQuote(), $e->getMessage());
            $result['success'] = false;
            $result['error'] = true;
            $result['error_messages'] = $e->getMessage();

            $gotoSection = $this->getOnepage()->getCheckout()->getGotoSection();
            if ($gotoSection) {
                $result['goto_section'] = $gotoSection;
                $this->getOnepage()->getCheckout()->setGotoSection(null);
            }
            $updateSection = $this->getOnepage()->getCheckout()->getUpdateSection();
            if ($updateSection) {
                if (isset($this->_sectionUpdateFunctions[$updateSection])) {
                    $updateSectionFunction = $this->_sectionUpdateFunctions[$updateSection];
                    $result['update_section'] = array(
                        'name' => $updateSection,
                        'html' => $this->$updateSectionFunction()
                    );
                }
                $this->getOnepage()->getCheckout()->setUpdateSection(null);
            }
        } catch (Exception $e) {
            Mage::logException($e);
            Mage::helper('checkout')->sendPaymentFailedEmail($this->getOnepage()->getQuote(), $e->getMessage());
            $result['success']  = false;
            $result['error']    = true;
            $result['error_messages'] = $this->__('There was an error processing your order. Please contact us or try again later.');
        }
        $this->getOnepage()->getQuote()->save();
        /**
         * when there is redirect to third party, we don't want to save order yet.
         * we will save the order in return action.
         */
        if (isset($redirectUrl)) {
            $result['redirect'] = $redirectUrl;
        }

        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }
    /**
     * Filtering posted data. Converting localized data if needed
     *
     * @param array
     * @return array
     */
    protected function _filterPostData($data)
    {
        $data = $this->_filterDates($data, array('dob'));
        return $data;
    }

    /**
     * Check can page show for unregistered users
     *
     * @return boolean
     */
    protected function _canShowForUnregisteredUsers()
    {
        return Mage::getSingleton('customer/session')->isLoggedIn()
            || $this->getRequest()->getActionName() == 'index'
            || Mage::helper('checkout')->isAllowedGuestCheckout($this->getOnepage()->getQuote())
            || !Mage::helper('checkout')->isCustomerMustBeLogged();
    }
}