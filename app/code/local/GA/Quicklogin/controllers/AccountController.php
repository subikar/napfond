<?php
require_once Mage::getModuleDir('controllers', 'Mage_Customer').DS.'AccountController.php';
class GA_Quicklogin_AccountController extends Mage_Customer_AccountController
{
      protected $_validActions = array('create','login','logoutSuccess','forgotpassword','forgotpasswordpost','confirm','confirmation','resetpassword','resetpasswordpost');
      protected $_customActions = array('signupformpopup','ajaxLogin','ajaxCreate','ajaxForgotPassword');

     public function preDispatch()
     {
   		   $action = $this->getRequest()->getActionName();
	  
		   if (preg_match('/^('.$this->_getCustomActions().')/i', $action))
		   {
			$this->getRequest()->setActionName($this->_validActions[1]);
		   }

		   parent::preDispatch();

		   /**
			* Parent check is complete, reset request action name to origional value
			*/
		   if ($action != $this->getRequest()->getActionName())
		   {
			$this->getRequest()->setActionName($action);
		   }

		   if (!$this->getRequest()->isDispatched()) {
			return;
		   }

		   if (!preg_match('/^('.$this->_getValidActions().')/i', $action)) {
			if (!$this->_getSession()->authenticate($this)) {
			 $this->setFlag('', 'no-dispatch', true);
			}
		   } else {
			$this->_getSession()->setNoReferer(true);
		   }

     }
	  protected function _getValidActions()
	  {
	  return implode("|", array_merge($this->_validActions, $this->_customActions));
	  }

	 /**
	  * Gets custom action names and returns them as a pipe separated string
	  *
	  * @return string
	  */
	 protected function _getCustomActions()
	 {
	  return implode("|", $this->_customActions);
	 }


	/**
     * Login post action
     */
    public function ajaxLoginAction() {
	    
        if ($this->_getSession()->isLoggedIn()) {
            $this->_redirect('*/*/');
            return;
        }
		
        $session = $this->_getSession();
		$result=array();

        if ($this->getRequest()->isPost()) {
            $login = $this->getRequest()->getPost('login');
            if (!empty($login['username']) && !empty($login['password'])) {
                try {
                    $session->login($login['username'], $login['password']);
                    if ($session->getCustomer()->getIsJustConfirmed()) {
                        $this->_welcomeCustomer($session->getCustomer(), true);
                    }
					$result['success'] = true;
					$result['redirecturl'] = Mage::getUrl('customer/account/edit');
                    

                } catch (Mage_Core_Exception $e) {
                    switch ($e->getCode()) {
                        case Mage_Customer_Model_Customer::EXCEPTION_EMAIL_NOT_CONFIRMED:
                            /*$message = Mage::helper('customer')->__('This account is not confirmed. <a href="%s">Click here</a> to resend confirmation email.', Mage::helper('customer')->getEmailConfirmationUrl($login['username']));*/
						    $result['success'] = false;
							$result['message'] = Mage::helper('customer')->__('This account is not confirmed.');
                            break;
                        case Mage_Customer_Model_Customer::EXCEPTION_INVALID_EMAIL_OR_PASSWORD:
                            $message = $e->getMessage();
					        $result['success'] = false;
							//$result['message'] = Mage::helper('customer')->__($message);
							$result['message'] = Mage::helper('customer')->__('Either email or password entered by you is incorrect.');
                            break;
                        default:
                            $message = $e->getMessage();
						    $result['success'] = false;
							$result['message'] = Mage::helper('customer')->__($message);
                    }
                    //$session->addError($message);
                    $session->setUsername($login['username']);
                } catch (Exception $e) {
                    // Mage::logException($e); // PA DSS violation: this exception log can disclose customer password
                }
            } else {
                //$session->addError($this->__('Login and password are required.'));
				$result['success'] = false;
				$result['message'] = Mage::helper('customer')->__('Login and password are required.');
            }
        }
        $this->getResponse()->setBody(Zend_Json::encode($result));
        //$this->_loginPostRedirect();
    }
    public function signupformpopupAction(){
	
        $layout = $this->getLayout();
        $update = $layout->getUpdate();
        $update->load('customer_account_signupformpopup');
        $layout->generateXml();
        $layout->generateBlocks();
        $output = $layout->getOutput();
         echo $output;
  
	}
	/**
     * Login post action
     */
    public function ajaxCreateAction()
    {
        $session = $this->_getSession();
        if ($session->isLoggedIn()) {
            $this->_redirect('*/*/');
            return;
        }
        $session->setEscapeMessages(true); // prevent XSS injection in user input
        if ($this->getRequest()->isPost()) {
            $errors = array();

            if (!$customer = Mage::registry('current_customer')) {
                $customer = Mage::getModel('customer/customer')->setId(null);
            }

            /* @var $customerForm Mage_Customer_Model_Form */
            $customerForm = Mage::getModel('customer/form');
            $customerForm->setFormCode('customer_account_create')
                ->setEntity($customer);

            $customerData = $customerForm->extractData($this->getRequest());

            if ($this->getRequest()->getParam('is_subscribed', false)) {
                $customer->setIsSubscribed(1);
            }

            /**
             * Initialize customer group id
             */
            $customer->getGroupId();

            if ($this->getRequest()->getPost('create_address')) {
                /* @var $address Mage_Customer_Model_Address */
                $address = Mage::getModel('customer/address');
                /* @var $addressForm Mage_Customer_Model_Form */
                $addressForm = Mage::getModel('customer/form');
                $addressForm->setFormCode('customer_register_address')
                    ->setEntity($address);

                $addressData    = $addressForm->extractData($this->getRequest(), 'address', false);
                $addressErrors  = $addressForm->validateData($addressData);
                if ($addressErrors === true) {
                    $address->setId(null)
                        ->setIsDefaultBilling($this->getRequest()->getParam('default_billing', false))
                        ->setIsDefaultShipping($this->getRequest()->getParam('default_shipping', false));
                    $addressForm->compactData($addressData);
                    $customer->addAddress($address);

                    $addressErrors = $address->validate();
                    if (is_array($addressErrors)) {
                        $errors = array_merge($errors, $addressErrors);
                    }
                } else {
                    $errors = array_merge($errors, $addressErrors);
                }
            }

            try {
                $customerErrors = $customerForm->validateData($customerData);
                if ($customerErrors !== true) {
                    $errors = array_merge($customerErrors, $errors);
                } else {
                    $customerForm->compactData($customerData);
                    $customer->setPassword($this->getRequest()->getPost('password'));
                    $customer->setConfirmation($this->getRequest()->getPost('confirmation'));
                    $customerErrors = $customer->validate();
                    if (is_array($customerErrors)) {
                        $errors = array_merge($customerErrors, $errors);
                    }
                }

                $validationResult = count($errors) == 0;
                $result = array();
                if (true === $validationResult) {
                    $customer->save();

                    if ($customer->isConfirmationRequired()) {
                        $customer->sendNewAccountEmail('confirmation', $session->getBeforeAuthUrl());
                      	$result['success'] = true;
						$result['message'] = $this->__('Account confirmation is required. Please, check your email for the confirmation link. To resend the confirmation email please <a href="%s">click here</a>.', Mage::helper('customer')->getEmailConfirmationUrl($customer->getEmail()));
                    } else {
                        $session->setCustomerAsLoggedIn($customer);
                        $url = $this->_welcomeCustomer($customer);
                        //$this->_redirectSuccess($url);
                        //return;
						$result['success'] = true;
						$result['message'] = $this->__('You are successfully registered');
                    }
                } else {
                    $session->setCustomerFormData($this->getRequest()->getPost());
                    if (is_array($errors)) {
						$result['success'] = false;
                        foreach ($errors as $errorMessage) {
                            //$session->addError($errorMessage);
							$result['message'] .= $errorMessage;
                        }
                    } else {
                        //$session->addError($this->__('Invalid customer data'));
						$result['success'] = false;
						$result['message'] = $this->__('Invalid customer data');
                    }
                }
            } catch (Mage_Core_Exception $e) {
                $session->setCustomerFormData($this->getRequest()->getPost());
                if ($e->getCode() === Mage_Customer_Model_Customer::EXCEPTION_EMAIL_EXISTS) {
                    $url = Mage::getUrl('customer/account/forgotpassword');
					$result['success'] = false;
					$result['message'] = $this->__('There is already an account with this email address. If you are sure that it is your email address.');

                } else {
					$result['success'] = false;
					$result['message'] = $e->getMessage();
                   
                }
               
            } catch (Exception $e) {
              	$result['success'] = false;
				$result['message'] = $this->__('Cannot save the customer.');
            }
        }
		$this->getResponse()->setBody(Zend_Json::encode($result));
    }
	  /** Forgot Password **/
	  public function ajaxForgotPasswordAction()
      {
	    $email = (string) $this->getRequest()->getPost('forgotemail');
        $result = array();
        if ($email) {
            if (!Zend_Validate::is($email, 'EmailAddress')) {
                $result['success'] = false;
                $result['message'] = $this->__('Invalid email address.');
                $this->getResponse()->setBody(Zend_Json::encode($result));
                return;
            }
            $customer = Mage::getModel('customer/customer')
                ->setWebsiteId(Mage::app()->getStore()->getWebsiteId())
                ->loadByEmail($email);

            if ($customer->getId()) {
                try {
                    $newResetPasswordLinkToken = Mage::helper('customer')->generateResetPasswordLinkToken();
                    $customer->changeResetPasswordLinkToken($newResetPasswordLinkToken);
                    $customer->sendPasswordResetConfirmationEmail();
					
					$result['message'] = Mage::helper('customer')->__('An email has been sent to you to reset your password.');
					
                } catch (Exception $exception) {
                    $this->_getSession()->addError($exception->getMessage());
                    $result['success'] = false;
                    $result['message'] = $exception->getMessage();
                    $this->getResponse()->setBody(Zend_Json::encode($result));

                    return;
                }
            }
			else
				$result['message'] = Mage::helper('customer')->__('You are not registered with us.');
                $result['success'] = true;
               // $result['message'] = Mage::helper('customer')->__('If there is an account associated with %s you will receive an email with a link to reset your password.', Mage::helper('customer')->htmlEscape($email));
                $this->getResponse()->setBody(Zend_Json::encode($result));

                 return;
        }   else {
                    $result['success'] = false;
                    $result['message'] = $this->__('Please enter your email.');
                    $this->getResponse()->setBody(Zend_Json::encode($result));
                    return;
            }
    
	  } //forget password function ends
}