<?php
/**
* PHXSolution Mergeinfo
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@magentocommerce.com so you can be sent a copy immediately.
*
* Original code copyright (c) 2008 Irubin Consulting Inc. DBA Varien
*
* @category   Phxsolution_Mergeinfo_OnepageController
* @package    Phxsolution_Mergeinfo
* @author     Prakash Vaniya
* @contact    contact@phxsolution.com
* @site       www.phxsolution.com
* @copyright  Copyright (c) 2014 PHXSolution Mergeinfo
* @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*/
?>

<?php
require_once 'Mage/Checkout/controllers/OnepageController.php';
class Phxsolution_Mergeinfo_OnepageController extends Mage_Checkout_OnepageController
{
	public function saveBillingAction()
	{   
		if ($this->_expireAjax()) {
			return;
		}
		if ($this->getRequest()->isPost()) {
			$billingData = $this->getRequest()->getPost('billing', array());
			$customerBillingAddressId = $this->getRequest()->getPost('billing_address_id', false);
			
			if (isset($billingData['email'])) {
				$billingData['email'] = trim($billingData['email']);
			}
			$result = $this->getOnepage()->saveBilling($billingData, $customerBillingAddressId);
			
			if (!isset($result['error'])) {
				
				$shippingData = $this->getRequest()->getPost('shipping', array());
				$customerShippingAddressId = $this->getRequest()->getPost('shipping_address_id', false);
				$result = $this->getOnepage()->saveShipping($shippingData, $customerShippingAddressId);
			
				
				$returnVal = $this->applyShippingToAmount();
			
			
				if($returnVal == "zipErr"){
					
                    $result['success'] = false;
                    $result['error'] = true;
                    $result['error_messages'] = $this->__('Currently we do not ship to this pincode.');
                    $result['message'] = $this->__('Currently we do not ship to this pincode.');
				}	
				elseif (!isset($result['error'])) {
					if ($this->getOnepage()->getQuote()->isVirtual()) {
						$result['goto_section'] = 'payment';
						$result['update_section'] = array(
							'name' => 'payment-method',
							'html' => $this->_getPaymentMethodsHtml()
						);
					} else {
								$result['goto_section'] = 'payment';
								$result['update_section'] = array(
									'name' => 'payment-method',
									'html' => $this->_getPaymentMethodsHtml()
								);
						//$result['duplicateBillingInfo'] = 'false';
					}
				}
			}
			$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
		}
	}
}