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
 * @package     Mage_Adminhtml
 * @copyright   Copyright (c) 2014 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Adminhtml sales order shipment controller
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Mage_Adminhtml_Sales_Order_ShipmentController extends Mage_Adminhtml_Controller_Sales_Shipment
{
    /**
     * Initialize shipment items QTY
     */
    protected function _getItemQtys()
    {
        $data = $this->getRequest()->getParam('shipment');
        if (isset($data['items'])) {
            $qtys = $data['items'];
        } else {
            $qtys = array();
        }
        return $qtys;
    }

    /**
     * Initialize shipment model instance
     *
     * @return Mage_Sales_Model_Order_Shipment|bool
     */
    protected function _initShipment()
    {
        $this->_title($this->__('Sales'))->_title($this->__('Shipments'));

        $shipment = false;
        $shipmentId = $this->getRequest()->getParam('shipment_id');
        $orderId = $this->getRequest()->getParam('order_id');
        if ($shipmentId) {
            $shipment = Mage::getModel('sales/order_shipment')->load($shipmentId);
        } elseif ($orderId) {
            $order      = Mage::getModel('sales/order')->load($orderId);

            /**
             * Check order existing
             */
            if (!$order->getId()) {
                $this->_getSession()->addError($this->__('The order no longer exists.'));
                return false;
            }
            /**
             * Check shipment is available to create separate from invoice
             */
            if ($order->getForcedDoShipmentWithInvoice()) {
                $this->_getSession()->addError($this->__('Cannot do shipment for the order separately from invoice.'));
                return false;
            }
            /**
             * Check shipment create availability
             */
            if (!$order->canShip()) {
                $this->_getSession()->addError($this->__('Cannot do shipment for the order.'));
                return false;
            }
            $savedQtys = $this->_getItemQtys();
            $shipment = Mage::getModel('sales/service_order', $order)->prepareShipment($savedQtys);

            $tracks = $this->getRequest()->getPost('tracking');
            if ($tracks) {
                foreach ($tracks as $data) {
                    if (empty($data['number'])) {
                        Mage::throwException($this->__('Tracking number cannot be empty.'));
                    }
                    $track = Mage::getModel('sales/order_shipment_track')
                        ->addData($data);
                    $shipment->addTrack($track);
                }
            }
        }

        Mage::register('current_shipment', $shipment);
        return $shipment;
    }

    /**
     * Save shipment and order in one transaction
     *
     * @param Mage_Sales_Model_Order_Shipment $shipment
     * @return Mage_Adminhtml_Sales_Order_ShipmentController
     */
    protected function _saveShipment($shipment)
    {
        $shipment->getOrder()->setIsInProcess(true);
        $transactionSave = Mage::getModel('core/resource_transaction')
            ->addObject($shipment)
            ->addObject($shipment->getOrder())
            ->save();

        return $this;
    }

    /**
     * Shipment information page
     */
    public function viewAction()
    {
        if ($this->_initShipment()) {
            $this->_title($this->__('View Shipment'));

            $this->loadLayout();
            $this->getLayout()->getBlock('sales_shipment_view')
                ->updateBackButtonUrl($this->getRequest()->getParam('come_from'));
            $this->_setActiveMenu('sales/order')
                ->renderLayout();
        } else {
            $this->_forward('noRoute');
        }
    }

    /**
     * Start create shipment action
     */
    public function startAction()
    {
        /**
         * Clear old values for shipment qty's
         */
        $this->_redirect('*/*/new', array('order_id'=>$this->getRequest()->getParam('order_id')));
    }

    /**
     * Shipment create page
     */
    public function newAction()
    {
        if ($shipment = $this->_initShipment()) {
            $this->_title($this->__('New Shipment'));

            $comment = Mage::getSingleton('adminhtml/session')->getCommentText(true);
            if ($comment) {
                $shipment->setCommentText($comment);
            }

            $this->loadLayout()
                ->_setActiveMenu('sales/order')
                ->renderLayout();
        } else {
            $this->_redirect('*/sales_order/view', array('order_id'=>$this->getRequest()->getParam('order_id')));
        }
    }

    /**
     * Save shipment
     * We can save only new shipment. Existing shipments are not editable
     *
     * @return null
     */
    public function saveAction()
    {
        
     $data = $this->getRequest()->getPost('shipment');
		
		
    //$resource = Mage::getSingleton('core/resource');
	
    $readConnection = Mage::helper('function/more')->getDbReadObject();
	
    $writeConnection = Mage::helper('function/more')->getDbWriteObject();
		
		
		//////////////////////////////Code for valid shipment/////////////////////////////////////
		
		//$itemDetails = Mage::getModel('sales/order_items')->load(2443); 
		
		$itemData = $data["items"];
		
				
		$pkgType = array();
		foreach($itemData as $itemkey=>$itemid){
		if($itemid > 0){
			$prdType = $readConnection->fetchCol("select prdType from sales_flat_order_item where item_id='".$itemkey."'");
			
			if(!in_array($prdType[0],$pkgType))
			$pkgType[] = $prdType[0];			
		}
		}
		
		//print_r($pkgType);
		
		if(count($pkgType) > 1 && in_array('t-shirt',$pkgType)){			
				//echo "sdafsdf";exit;

				$isAjax = Mage::app()->getRequest()->getParam('isAjax');
				if($isAjax==true){
					
					
					 $responseAjax = new Varien_Object();
					$responseAjax->setError(true);
                $responseAjax->setMessage(
                    Mage::helper('sales')->__('Invalid shipment type, you can not mix tshirt and other products in one shipment.'));
					
					$this->getResponse()->setBody($responseAjax->toJson());
					
					
					//echo '<script type="text/javascript">alert("An error occurred while creating shipping label.")</script>';
				}
				else{
		        $this->_getSession()->addError($this->__('Invalid shipment type, you can not mix tshirt and other products in one shipment.'));
                $this->_redirect('*/*/new', array('order_id' => $this->getRequest()->getParam('order_id')));
				}
				
		}
		else{
		//echo '<pre>';
		//print_r($data);
		
		
		//////////////////////////////////////////////////////////////////////////////////////////
		
        if (!empty($data['comment_text'])) {
            Mage::getSingleton('adminhtml/session')->setCommentText($data['comment_text']);
        }

        try {
            $shipment = $this->_initShipment();
            if (!$shipment) {
                $this->_forward('noRoute');
                return;
            }

            $shipment->register();
            $comment = '';
            if (!empty($data['comment_text'])) {
                $shipment->addComment(
                    $data['comment_text'],
                    isset($data['comment_customer_notify']),
                    isset($data['is_visible_on_front'])
                );
                if (isset($data['comment_customer_notify'])) {
                    $comment = $data['comment_text'];
                }
            }

            if (!empty($data['send_email'])) {
                $shipment->setEmailSent(true);
            }

            $shipment->getOrder()->setCustomerNoteNotify(!empty($data['send_email']));
            $responseAjax = new Varien_Object();
            $isNeedCreateLabel = isset($data['create_shipping_label']) && $data['create_shipping_label'];

            if ($isNeedCreateLabel && $this->_createShippingLabel($shipment)) {
                $responseAjax->setOk(true);
            }
			//echo '<pre>';
			//echo $_POST["expected_delivery_date"];
			//print_r($shipment->getPackages());
			
			//print_r($shipment->getAllTracks());
			
			

			
            $this->_saveShipment($shipment);

            $shipment->sendEmail(!empty($data['send_email']), $comment);

            $shipmentCreatedMessage = $this->__('The shipment has been created.');
            $labelCreatedMessage    = $this->__('The shipping label has been created.');

            $this->_getSession()->addSuccess($isNeedCreateLabel ? $shipmentCreatedMessage . ' ' . $labelCreatedMessage
                : $shipmentCreatedMessage);
            Mage::getSingleton('adminhtml/session')->getCommentText(true);
        } catch (Mage_Core_Exception $e) {
            if ($isNeedCreateLabel) {
                $responseAjax->setError(true);
                $responseAjax->setMessage($e->getMessage());
            } else {
                $this->_getSession()->addError($e->getMessage());
                $this->_redirect('*/*/new', array('order_id' => $this->getRequest()->getParam('order_id')));
            }
        } catch (Exception $e) {
            Mage::logException($e);
            if ($isNeedCreateLabel) {
                $responseAjax->setError(true);
                $responseAjax->setMessage(
                    Mage::helper('sales')->__('An error occurred while creating shipping label.'));
            } else {
                $this->_getSession()->addError($this->__('Cannot save shipment.'));
                $this->_redirect('*/*/new', array('order_id' => $this->getRequest()->getParam('order_id')));
            }

        }
        if ($isNeedCreateLabel) {
			
			$this->getResponse()->setBody($responseAjax->toJson());
        } else {
            $this->_redirect('*/sales_order/view', array('order_id' => $shipment->getOrderId()));
        }
		
		}	
    }

    /**
     * Send email with shipment data to customer
     */
    public function emailAction()
    {		
        try {
            $shipment = $this->_initShipment();
            if ($shipment) {
                $shipment->sendEmail(true)
                    ->setEmailSent(true)
                    ->save();
                $historyItem = Mage::getResourceModel('sales/order_status_history_collection')
                    ->getUnnotifiedForInstance($shipment, Mage_Sales_Model_Order_Shipment::HISTORY_ENTITY_NAME);
                if ($historyItem) {
                    $historyItem->setIsCustomerNotified(1);
                    $historyItem->save();
                }
                $this->_getSession()->addSuccess($this->__('The shipment has been sent.'));
				
				
			/*	
			$expected_delivery_date = urldecode(Mage::app()->getRequest()->getParam('estimated_date'));	
			if($expected_delivery_date!=''){	
    $readConnection = Mage::helper('function/more')->getDbReadObject();
	
    $writeConnection = Mage::helper('function/more')->getDbWriteObject();				
	$baseUrl = Mage::helper('function/more')->getBhishoomSiteBaseUrl();
	
	
	
		    $shipmentPackages = (unserialize($shipment->getPackages()));
				
			$itemsArray = array();
			
			foreach($shipmentPackages as $packageArr){
				
				$itemsArray = $packageArr["items"];
				
			}
			
			
			if(count($itemsArray)==0)
			{
				
				$results = $readConnection->fetchAll("select * from sales_flat_shipment_item where parent_id='".$shipment->getData('entity_id')."'");			
				foreach($results as $resultsVal){
					
					$itemsArray[] = array('name'=>$resultsVal["name"]);
				}
			}
			
			
			$itemsArray["expected_date_delivery"] = $expected_delivery_date;
			//print_r($itemsArray);
			//echo $shipment->getOrder()->getIncrementId();
			/////////////////////////////////////Code for sending SMS to DISPATCHED Items to client/////////////////////////////////////////			
			//Mage::dispatchEvent('bhishoom_sales_order_shipment_save_after', array($itemsArray));
			$itemsArrayValStr = '';
			$comma = '';
			
			
			foreach($itemsArray as $itemsArrayKey=>$itemsArrayVal){
				if($itemsArrayKey === 'expected_date_delivery')
					continue;
						
				$itemsArrayValStr = $itemsArrayValStr.$comma.'"'.str_replace(' ','+',$itemsArrayVal["name"]).'"';	
				$comma = ',+';
			}
			

			$smsOrderIncrementId = $shipment->getOrder()->getIncrementId();
			$expected_date_delivery = str_replace(' ','+',$itemsArray["expected_date_delivery"]);
			$trackorderLink = $baseUrl.'/trackorder?order_num='.$smsOrderIncrementId;
			
			$txt="Dispatched:+Following+item(s)+of+your+order+%23".$smsOrderIncrementId."+has+been+dispatched:+".$itemsArrayValStr.".+It+will+reach+you+on+or+before+".$expected_date_delivery.".+Track+your+order+at+".$trackorderLink.".";

			$smsMobilePhone = $shipment->getBillingAddress()->getTelephone();

			if($shipment->getBillingAddress()->getTelephone()!='' && count($itemsArray) > 0){			
			$callURL = 'http://manage.sarvsms.com/api/send_transactional_sms.php?username=u7892&msg_token=1ST5G8&sender_id=BISHOM&message='.$txt.'&mobile='.$smsMobilePhone;
			//$resp = Kojoman_Twilio_Model_Observer::call_url_for_sms($callURL);			
			$ch = curl_init(); 
			curl_setopt($ch, CURLOPT_URL, trim($callURL)); 
			curl_setopt($ch, CURLOPT_HEADER, 1); 
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
			$data = curl_exec($ch); 
			curl_close($ch);
			$query = "insert into twilio set sms_to='".$phone."',sms_body='test message and that is', date='".date('Y-m-d')."',timestamp='".date('Y-m-d h:i:s')."',api_response='".$data."'";
			$writeConnection->query($query); 		
			}			
					
			////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////				
		}	*/								
				
            }
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Exception $e) {
            $this->_getSession()->addError($this->__('Cannot send shipment information.'));
        }
        $this->_redirect('*/*/view', array(
            'shipment_id' => $this->getRequest()->getParam('shipment_id')
        ));
    }

	
	
	
	
	

    /**
     * Request Pickup
     */
    public function requestpickupAction()
    {
		
		$readConnection = Mage::helper('function/more')->getDbReadObject();
	
		$writeConnection = Mage::helper('function/more')->getDbWriteObject();
		
		$pickDateTime = urldecode($this->getRequest()->getParam('val_pickup_date'));
		$pickDateTime = explode(' ',$pickDateTime);
			
		$pickUpDateOnly = explode('/',$pickDateTime[0]);
		$pickUpTimeOnly = explode(':',$pickDateTime[1]);
		
		
		$readyTimeStamp = mktime($pickUpTimeOnly[0], 0, 0, date($pickUpDateOnly[1])  , date($pickUpDateOnly[2]), date($pickUpDateOnly[0])); 
		$companyCloseTimeStamp = '20:00:00';
	
	
        try {
            $shipment = $this->_initShipment(); 		 
			$shipmentPackages = (unserialize($shipment->getPackages()));				 
			$itemsArray = array();
			
			foreach($shipmentPackages as $packageArr){				
				$itemsArray = $packageArr["items"];				
			}
			

		
		
		$pkgType = array();
		foreach($itemsArray as $itemsArrayKey=>$itemsArrayVal){
		if($itemsArrayVal['order_item_id'] > 0){
			$prdType = $readConnection->fetchCol("select prdType from sales_flat_order_item where item_id='".$itemsArrayVal['order_item_id']."'");
			
			if(!in_array($prdType[0],$pkgType))
			$pkgType[] = $prdType[0];			
		}
		}			
		
		
	
			
		$order = $shipment->getOrder();
		$shipmentStoreId = $shipment->getStoreId();
		$admin = Mage::getSingleton('admin/session')->getUser();
		
		
		/*const XML_PATH_STORE_ADDRESS1     = 'shipping/origin/street_line1';
		const XML_PATH_STORE_ADDRESS2     = 'shipping/origin/street_line2';
		const XML_PATH_STORE_CITY         = 'shipping/origin/city';
		const XML_PATH_STORE_REGION_ID    = 'shipping/origin/region_id';
		const XML_PATH_STORE_ZIP          = 'shipping/origin/postcode';
		const XML_PATH_STORE_COUNTRY_ID   = 'shipping/origin/country_id';
			*/
		
		
        $originStreet1 = Mage::getStoreConfig('shipping/origin/street_line1', $shipmentStoreId);
        $originStreet2 = Mage::getStoreConfig('shipping/origin/street_line2', $shipmentStoreId);
		
		$storeInfo = new Varien_Object(Mage::getStoreConfig('general/store_information', $shipmentStoreId));
		
		
        $shipperRegionCode = Mage::getStoreConfig('shipping/origin/region_id', $shipmentStoreId);
        if (is_numeric($shipperRegionCode)) {
            $shipperRegionCode = Mage::getModel('directory/region')->load($shipperRegionCode)->getCode();
        }
		
		
        if (!$admin->getFirstname() || !$admin->getLastname() || !$storeInfo->getName() || !$storeInfo->getPhone()
            || !$originStreet1 || !Mage::getStoreConfig('shipping/origin/city', $shipmentStoreId)
            || !$shipperRegionCode || !Mage::getStoreConfig('shipping/origin/postcode', $shipmentStoreId)
            || !Mage::getStoreConfig('shipping/origin/country_id', $shipmentStoreId)
        ) {
            Mage::throwException(
                Mage::helper('sales')->__('Insufficient information to request pickup.')
            );
        }		
		
		
		
		$wsdlBasePath = Mage::getModuleDir('etc', 'Mage_Usa')  . DS . 'wsdl' . DS . 'FedEx' . DS;
		$path_to_wsdl = $wsdlBasePath . 'PickupService_v11.wsdl';
							
							
					ini_set("soap.wsdl_cache_enabled", "0");

					$client = new SoapClient($path_to_wsdl, array('trace' => 1)); // Refer to http://us3.php.net/manual/en/ref.soap.php for more information
										
					//$client->__setLocation('https://wsbeta.fedex.com:443/web-services');
					
					$client->__setLocation(Mage::getStoreConfig('carriers/fedex/sandbox_mode')
            ? 'https://wsbeta.fedex.com:443/web-services'
            : 'https://ws.fedex.com:443/web-services'
        );

					
					$request['WebAuthenticationDetail'] = array(
						'ParentCredential' => array(
							'Key' => '',
							'Password' => ''
						),
						'UserCredential' => array(
							'Key' => Mage::getStoreConfig('carriers/fedex/key'), 
							'Password' => Mage::getStoreConfig('carriers/fedex/password')
						)
					);
					$request['ClientDetail'] = array(
						'AccountNumber' => Mage::getStoreConfig('carriers/fedex/account'), 
						'MeterNumber' => Mage::getStoreConfig('carriers/fedex/meter_number')
					);
					$request['TransactionDetail'] = array('CustomerTransactionId' => '*** Create Pickup Request for Dream box pvt ltd. ***');
					$request['Version'] = array(
						'ServiceId' => 'disp', 
						'Major' => 11, 
						'Intermediate' => 0, 
						'Minor' => 0
					);
					
					
					if(count($pkgType) == 1 && in_array('t-shirt',$pkgType)){
						
						
        $shipperRegionCode = Mage::getStoreConfig('shipping/origin_tshirt/tshirt_region_id', $shipmentStoreId);
        if (is_numeric($shipperRegionCode)) {
            $shipperRegionCode = Mage::getModel('directory/region')->load($shipperRegionCode)->getCode();
        }						
						
					$request['OriginDetail'] = array(
						'PickupLocation' => array(
							'Contact' => array(
								'PersonName' => Mage::getStoreConfig('shipping/origin_tshirt/tshirt_contact_person'),
								'CompanyName' => Mage::getStoreConfig('shipping/origin_tshirt/tshirt_contact_company'),
								'PhoneNumber' => Mage::getStoreConfig('shipping/origin_tshirt/tshirt_contact_phone')
							),
							'Address' => array(
								'StreetLines' => array(trim(Mage::getStoreConfig('shipping/origin_tshirt/tshirt_street_line1')),trim(Mage::getStoreConfig('shipping/origin_tshirt/tshirt_street_line2'))),
								'City' => Mage::getStoreConfig('shipping/origin_tshirt/tshirt_city', $shipmentStoreId),
								'StateOrProvinceCode' => $shipperRegionCode,
								'PostalCode' => Mage::getStoreConfig('shipping/origin_tshirt/tshirt_postcode', $shipmentStoreId),
								'CountryCode' => Mage::getStoreConfig('shipping/origin_tshirt/tshirt_country_id', $shipmentStoreId))
							),
						//'PackageLocation' => 'FRONT', // valid values NONE, FRONT, REAR and SIDE
						//'BuildingPartCode' => 'SUITE', // valid values APARTMENT, BUILDING, DEPARTMENT, SUITE, FLOOR and ROOM
						//'BuildingPartDescription' => '3B',
						'ReadyTimestamp' => $readyTimeStamp, // Replace with your ready date time
						'CompanyCloseTime' => $companyCloseTimeStamp
					);
						
					
					}
					else{
						
						
					$request['OriginDetail'] = array(
						'PickupLocation' => array(
							'Contact' => array(
								'PersonName' => '',
								'CompanyName' => $storeInfo->getName(),
								'PhoneNumber' => $storeInfo->getPhone()
							),
							'Address' => array(
								'StreetLines' => array(trim($originStreet1),trim($originStreet2)),
								'City' => Mage::getStoreConfig('shipping/origin/city', $shipmentStoreId),
								'StateOrProvinceCode' => $shipperRegionCode,
								'PostalCode' => Mage::getStoreConfig('shipping/origin/postcode', $shipmentStoreId),
								'CountryCode' => Mage::getStoreConfig('shipping/origin/country_id', $shipmentStoreId))
							),
						//'PackageLocation' => 'FRONT', // valid values NONE, FRONT, REAR and SIDE
						//'BuildingPartCode' => 'SUITE', // valid values APARTMENT, BUILDING, DEPARTMENT, SUITE, FLOOR and ROOM
						//'BuildingPartDescription' => '3B',
						'ReadyTimestamp' => $readyTimeStamp, // Replace with your ready date time
						'CompanyCloseTime' => $companyCloseTimeStamp
					);
					
					
					}
					
					
					
					if($shipmentPackages[1]['params']['weight_units']=='KILOGRAM')
					$weightUnit = 'KG';
					else
					$weightUnit = 'LB';	
					
					$request['PackageCount'] = '1';
					$request['TotalWeight'] = array(
						'Value' => $shipmentPackages[1]['params']['weight'], 
						'Units' => $weightUnit // valid values LB and KG
					); 
					$request['CarrierCode'] = 'FDXE'; // valid values FDXE-Express, FDXG-Ground, FDXC-Cargo, FXCC-Custom Critical and FXFR-Freight
					//$request['OversizePackageCount'] = '1';
					$request['CourierRemarks'] = 'Request for pickup';

				
					
					try {
						/*if(setEndpoint('changeEndpoint')){
							$newLocation = $client->__setLocation(setEndpoint('endpoint'));
						}*/
						
						
						$response = $client->createPickup($request);
						
						
						if ($response -> HighestSeverity != 'FAILURE' && $response -> HighestSeverity != 'ERROR'){
							//echo 'Pickup confirmation number is: '.$response -> PickupConfirmationNumber .Newline;
							//echo 'Location: '.$response -> Location .Newline;
							//printSuccess($client, $response);
														
							$pickUpInfo = array("Pickup Number" => $response->PickupConfirmationNumber,"Location" => $response->Location,"PickupReadyTimestamp" => $readyTimeStamp);
							
							$pickUpInfo = serialize($pickUpInfo);
							
    $readConnection = Mage::helper('function/more')->getDbReadObject();
	
    $writeConnection = Mage::helper('function/more')->getDbWriteObject();

							$query = "update sales_flat_shipment set bhishoom_pickup_info = '".$pickUpInfo."' where entity_id='".$shipment->getData('entity_id')."'";
							
							$writeConnection->query($query); 	
	
							$this->_getSession()->addSuccess($this->__('Pickup Request created successfully.'));
														
							
						}else{
							$this->_getSession()->addError($this->__('Cannot create pickup request.'));
						} 
						
						//writeToLog($client);    // Write to log file   
					} catch (Mage_Core_Exception $e) {
							$this->_getSession()->addError($e->getMessage());
					}catch (Exception $e) {
							$this->_getSession()->addError($e->getMessage());
					}	
							
				} catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        }			
	
		 $this->_redirect('*/*/view', array(
            'shipment_id' => $this->getRequest()->getParam('shipment_id')
        ));
		
    }	
	
	
	


    /**
     * Request Pickup
     */
    public function cancelpickupAction()
    {

        try {
            $shipment = $this->_initShipment(); 		 
			$shipmentPackages = (unserialize($shipment->getPackages()));				 
			$itemsArray = array();
			
			foreach($shipmentPackages as $packageArr){				
				$itemsArray = $packageArr["items"];				
			}
			
		$order = $shipment->getOrder();
		$shipmentStoreId = $shipment->getStoreId();
		$admin = Mage::getSingleton('admin/session')->getUser();
		
		
		/*const XML_PATH_STORE_ADDRESS1     = 'shipping/origin/street_line1';
		const XML_PATH_STORE_ADDRESS2     = 'shipping/origin/street_line2';
		const XML_PATH_STORE_CITY         = 'shipping/origin/city';
		const XML_PATH_STORE_REGION_ID    = 'shipping/origin/region_id';
		const XML_PATH_STORE_ZIP          = 'shipping/origin/postcode';
		const XML_PATH_STORE_COUNTRY_ID   = 'shipping/origin/country_id';
			*/
		
		
        $originStreet1 = Mage::getStoreConfig('shipping/origin/street_line1', $shipmentStoreId);
        $originStreet2 = Mage::getStoreConfig('shipping/origin/street_line2', $shipmentStoreId);
		
		$storeInfo = new Varien_Object(Mage::getStoreConfig('general/store_information', $shipmentStoreId));
		
		
        $shipperRegionCode = Mage::getStoreConfig('shipping/origin/region_id', $shipmentStoreId);
        if (is_numeric($shipperRegionCode)) {
            $shipperRegionCode = Mage::getModel('directory/region')->load($shipperRegionCode)->getCode();
        }
		
		
        if (!$admin->getFirstname() || !$admin->getLastname() || !$storeInfo->getName() || !$storeInfo->getPhone()
            || !$originStreet1 || !Mage::getStoreConfig('shipping/origin/city', $shipmentStoreId)
            || !$shipperRegionCode || !Mage::getStoreConfig('shipping/origin/postcode', $shipmentStoreId)
            || !Mage::getStoreConfig('shipping/origin/country_id', $shipmentStoreId)
        ) {
            Mage::throwException(
                Mage::helper('sales')->__('Insufficient information to request pickup.')
            );
        }		
		
					$pickUpDetails = $shipment->getData("bhishoom_pickup_info");

			$pickupDetailsArr = unserialize($pickUpDetails);
		
		$wsdlBasePath = Mage::getModuleDir('etc', 'Mage_Usa')  . DS . 'wsdl' . DS . 'FedEx' . DS;
		$path_to_wsdl = $wsdlBasePath . 'PickupService_v11.wsdl';
							
							
					ini_set("soap.wsdl_cache_enabled", "0");

					$client = new SoapClient($path_to_wsdl, array('trace' => 1)); // Refer to http://us3.php.net/manual/en/ref.soap.php for more information
					
					
					//$client->__setLocation('https://wsbeta.fedex.com:443/web-services');
					
					$client->__setLocation(Mage::getStoreConfig('carriers/fedex/sandbox_mode')
            ? 'https://wsbeta.fedex.com:443/web-services'
            : 'https://ws.fedex.com:443/web-services'
        );

					
					$request['WebAuthenticationDetail'] = array(
						'ParentCredential' => array(
							'Key' => '',
							'Password' => ''
						),
						'UserCredential' => array(
							'Key' => Mage::getStoreConfig('carriers/fedex/key'), 
							'Password' => Mage::getStoreConfig('carriers/fedex/password')
						)
					);
					$request['ClientDetail'] = array(
						'AccountNumber' => Mage::getStoreConfig('carriers/fedex/account'), 
						'MeterNumber' => Mage::getStoreConfig('carriers/fedex/meter_number')
					);
					$request['TransactionDetail'] = array('CustomerTransactionId' => '*** Create Pickup Request using PHP ***');
					
					$request['Version'] = array(
						'ServiceId' => 'disp', 
						'Major' => '11', 
						'Intermediate' => '0', 
						'Minor' => '0'
					);
					
					$request['CarrierCode'] = 'FDXE'; // valid values FDXE-Express, FDXG-Ground, etc
					$request['PickupConfirmationNumber'] = $pickupDetailsArr["Pickup Number"]; // Replace 'XXX' with your Pickup confirmation number
					$request['ScheduledDate'] = $pickupDetailsArr["PickupReadyTimestamp"];
					$request['Location'] = $pickupDetailsArr["Location"]; // Replace 'XXX' with your Pickip Loaction Code/ID
					$request['CourierRemarks'] = 'Do not pickup.  This is a test';					



					try {
						/*if(setEndpoint('changeEndpoint')){
							$newLocation = $client->__setLocation(setEndpoint('endpoint'));
						}*/
						
						$response = $client->cancelPickup($request);
						
						
						if ($response -> HighestSeverity != 'FAILURE' && $response -> HighestSeverity != 'ERROR'){
							//echo 'Pickup confirmation number is: '.$response -> PickupConfirmationNumber .Newline;
							//echo 'Location: '.$response -> Location .Newline;
							//printSuccess($client, $response);
														
							//$pickUpInfo = array("pickupnumber" => $response->PickupConfirmationNumber);
							
							//$pickUpInfo = serialize($pickUpInfo);
							
    $readConnection = Mage::helper('function/more')->getDbReadObject();
	
    $writeConnection = Mage::helper('function/more')->getDbWriteObject();

							$query = "update sales_flat_shipment set bhishoom_pickup_info = '' where entity_id='".$shipment->getData('entity_id')."'";
							
							$writeConnection->query($query); 	
	
							$this->_getSession()->addSuccess($this->__('Pickup Request cancelled successfully.'));
														
							
						}else{
							$this->_getSession()->addError($this->__('Cannot cancelled pickup request.'));
						} 
						
						//writeToLog($client);    // Write to log file   
					} catch (Mage_Core_Exception $e) {
							$this->_getSession()->addError($e->getMessage());
					}catch (Exception $e) {
							$this->_getSession()->addError($e->getMessage());
					}	
							
				} catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        }			
	
		 $this->_redirect('*/*/view', array(
            'shipment_id' => $this->getRequest()->getParam('shipment_id')
        ));
		
    }


	
   public function cancelshipmentAction()
    {
		
        try {
            $shipment = $this->_initShipment(); 		 
			$shipmentPackages = (unserialize($shipment->getPackages()));				 
			$itemsArray = array();
			
			foreach($shipmentPackages as $packageArr){				
				$itemsArray = $packageArr["items"];				
			}
			
		$order = $shipment->getOrder();
		$shipmentStoreId = $shipment->getStoreId();
		$admin = Mage::getSingleton('admin/session')->getUser();
		
		
		/*const XML_PATH_STORE_ADDRESS1     = 'shipping/origin/street_line1';
		const XML_PATH_STORE_ADDRESS2     = 'shipping/origin/street_line2';
		const XML_PATH_STORE_CITY         = 'shipping/origin/city';
		const XML_PATH_STORE_REGION_ID    = 'shipping/origin/region_id';
		const XML_PATH_STORE_ZIP          = 'shipping/origin/postcode';
		const XML_PATH_STORE_COUNTRY_ID   = 'shipping/origin/country_id';
			*/
		
		$tracking_id = $this->getRequest()->getParam('track_id');
        $originStreet1 = Mage::getStoreConfig('shipping/origin/street_line1', $shipmentStoreId);
        $originStreet2 = Mage::getStoreConfig('shipping/origin/street_line2', $shipmentStoreId);
		
		$storeInfo = new Varien_Object(Mage::getStoreConfig('general/store_information', $shipmentStoreId));
		
        $shipperRegionCode = Mage::getStoreConfig('shipping/origin/region_id', $shipmentStoreId);
        if (is_numeric($shipperRegionCode)) {
            $shipperRegionCode = Mage::getModel('directory/region')->load($shipperRegionCode)->getCode();
        }
		
        if (!$admin->getFirstname() || !$admin->getLastname() || !$storeInfo->getName() || !$storeInfo->getPhone()
            || !$originStreet1 || !Mage::getStoreConfig('shipping/origin/city', $shipmentStoreId)
            || !$shipperRegionCode || !Mage::getStoreConfig('shipping/origin/postcode', $shipmentStoreId)
            || !Mage::getStoreConfig('shipping/origin/country_id', $shipmentStoreId)
            || !$tracking_id
        ) {
			
            Mage::throwException(
                Mage::helper('sales')->__('Insufficient information to cancel shipment.')
            );
        }		
		
					$pickUpDetails = $shipment->getData("bhishoom_pickup_info");

			$pickupDetailsArr = unserialize($pickUpDetails);
		
		$wsdlBasePath = Mage::getModuleDir('etc', 'Mage_Usa')  . DS . 'wsdl' . DS . 'FedEx' . DS;
		echo $path_to_wsdl = $wsdlBasePath . 'ShipService_v17.wsdl';
							
							
					ini_set("soap.wsdl_cache_enabled", "0");

					$client = new SoapClient($path_to_wsdl, array('trace' => 1)); // Refer to http://us3.php.net/manual/en/ref.soap.php for more information
					
					
					//$client->__setLocation('https://wsbeta.fedex.com:443/web-services');
					
					$client->__setLocation(Mage::getStoreConfig('carriers/fedex/sandbox_mode')
            ? 'https://wsbeta.fedex.com:443/web-services'
            : 'https://ws.fedex.com:443/web-services'
        );
				
					$request['WebAuthenticationDetail'] = array(
						'ParentCredential' => array(
							'Key' => '',
							'Password' => ''
						),
						'UserCredential' => array(
							'Key' => Mage::getStoreConfig('carriers/fedex/key'), 
							'Password' => Mage::getStoreConfig('carriers/fedex/password')
						)
					);
					$request['ClientDetail'] = array(
						'AccountNumber' => Mage::getStoreConfig('carriers/fedex/account'), 
						'MeterNumber' => Mage::getStoreConfig('carriers/fedex/meter_number')
					);
					$request['TransactionDetail'] = array('CustomerTransactionId' => '*** Cancel Shipment Request using PHP ***');
					
					$request['Version'] = array(
						'ServiceId' => 'ship', 
						'Major' => '17', 
						'Intermediate' => '0', 
						'Minor' => '0'
					);
					
					$request['ShipTimestamp'] = date('c');
					$request['TrackingId'] = array(
						'TrackingIdType' =>'EXPRESS', // valid values EXPRESS, GROUND, USPS, etc
						'TrackingNumber'=>$tracking_id
					);  
					$request['DeletionControl'] = 'DELETE_ONE_PACKAGE'; // Package/Shipment


					try {
						/*if(setEndpoint('changeEndpoint')){
							$newLocation = $client->__setLocation(setEndpoint('endpoint'));
						}*/
						
						$response = $client ->deleteShipment($request);
					
						if ($response -> HighestSeverity != 'FAILURE' && $response -> HighestSeverity != 'ERROR'){
							//echo 'Pickup confirmation number is: '.$response -> PickupConfirmationNumber .Newline;
							//echo 'Location: '.$response -> Location .Newline;
							//printSuccess($client, $response);
														
							//$pickUpInfo = array("pickupnumber" => $response->PickupConfirmationNumber);
							
							//$pickUpInfo = serialize($pickUpInfo);
							//$track = Mage::getModel('sales/order_shipment_track')->load($trackId);
							//$track->delete();
							$this->_getSession()->addSuccess($this->__('Shipment Request cancelled & delete successfully.'));
														
							
						}else{
							$this->_getSession()->addError($this->__('Cannot cancelled Shipment request.'));
						} 
						
						//writeToLog($client);    // Write to log file   
					} catch (Mage_Core_Exception $e) {
							$this->_getSession()->addError($e->getMessage());
					}catch (Exception $e) {
							$this->_getSession()->addError($e->getMessage());
					}	
							
				} catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        }			
	
		 $this->_redirect('*/*/view', array(
            'shipment_id' => $this->getRequest()->getParam('shipment_id')
        ));
		
    }


	
	
	
    /**
     * Add new tracking number action
     */
    public function addTrackAction()
    {
        try {
            $carrier = $this->getRequest()->getPost('carrier');
            $number  = $this->getRequest()->getPost('number');
            $title  = $this->getRequest()->getPost('title');
            if (empty($carrier)) {
                Mage::throwException($this->__('The carrier needs to be specified.'));
            }
            if (empty($number)) {
                Mage::throwException($this->__('Tracking number cannot be empty.'));
            }
            $shipment = $this->_initShipment();
            if ($shipment) {
                $track = Mage::getModel('sales/order_shipment_track')
                    ->setNumber($number)
                    ->setCarrierCode($carrier)
                    ->setTitle($title);
                $shipment->addTrack($track)
                    ->save();

                $this->loadLayout();
                $response = $this->getLayout()->getBlock('shipment_tracking')->toHtml();
            } else {
                $response = array(
                    'error'     => true,
                    'message'   => $this->__('Cannot initialize shipment for adding tracking number.'),
                );
            }
        } catch (Mage_Core_Exception $e) {
            $response = array(
                'error'     => true,
                'message'   => $e->getMessage(),
            );
        } catch (Exception $e) {
            $response = array(
                'error'     => true,
                'message'   => $this->__('Cannot add tracking number.'),
            );
        }
        if (is_array($response)) {
            $response = Mage::helper('core')->jsonEncode($response);
        }
        $this->getResponse()->setBody($response);
    }

    /**
     * Remove tracking number from shipment
     */
    public function removeTrackAction()
    {
        $trackId    = $this->getRequest()->getParam('track_id');
        $shipmentId = $this->getRequest()->getParam('shipment_id');
        $track = Mage::getModel('sales/order_shipment_track')->load($trackId);
        if ($track->getId()) {
            try {
                if ($this->_initShipment()) {
                    $track->delete();

                    $this->loadLayout();
                    $response = $this->getLayout()->getBlock('shipment_tracking')->toHtml();
                } else {
                    $response = array(
                        'error'     => true,
                        'message'   => $this->__('Cannot initialize shipment for delete tracking number.'),
                    );
                }
            } catch (Exception $e) {
                $response = array(
                    'error'     => true,
                    'message'   => $this->__('Cannot delete tracking number.'),
                );
            }
        } else {
            $response = array(
                'error'     => true,
                'message'   => $this->__('Cannot load track with retrieving identifier.'),
            );
        }
        if (is_array($response)) {
            $response = Mage::helper('core')->jsonEncode($response);
        }
        $this->getResponse()->setBody($response);
    }

    /**
     * View shipment tracking information
     */
    public function viewTrackAction()
    {
        $trackId    = $this->getRequest()->getParam('track_id');
        $shipmentId = $this->getRequest()->getParam('shipment_id');
        $track = Mage::getModel('sales/order_shipment_track')->load($trackId);
        if ($track->getId()) {
            try {
                $response = $track->getNumberDetail();
            } catch (Exception $e) {
                $response = array(
                    'error'     => true,
                    'message'   => $this->__('Cannot retrieve tracking number detail.'),
                );
            }
        } else {
            $response = array(
                'error'     => true,
                'message'   => $this->__('Cannot load track with retrieving identifier.'),
            );
        }

        if ( is_object($response)){
            $className = Mage::getConfig()->getBlockClassName('adminhtml/template');
            $block = new $className();
            $block->setType('adminhtml/template')
                ->setIsAnonymous(true)
                ->setTemplate('sales/order/shipment/tracking/info.phtml');

            $block->setTrackingInfo($response);

            $this->getResponse()->setBody($block->toHtml());
        } else {
            if (is_array($response)) {
                $response = Mage::helper('core')->jsonEncode($response);
            }

            $this->getResponse()->setBody($response);
        }
    }

    /**
     * Add comment to shipment history
     */
    public function addCommentAction()
    {
        try {
            $this->getRequest()->setParam(
                'shipment_id',
                $this->getRequest()->getParam('id')
            );
            $data = $this->getRequest()->getPost('comment');
            if (empty($data['comment'])) {
                Mage::throwException($this->__('Comment text field cannot be empty.'));
            }
            $shipment = $this->_initShipment();
            $shipment->addComment(
                $data['comment'],
                isset($data['is_customer_notified']),
                isset($data['is_visible_on_front'])
            );
            $shipment->sendUpdateEmail(!empty($data['is_customer_notified']), $data['comment']);
            $shipment->save();

            $this->loadLayout(false);
            $response = $this->getLayout()->getBlock('shipment_comments')->toHtml();
        } catch (Mage_Core_Exception $e) {
            $response = array(
                'error'     => true,
                'message'   => $e->getMessage()
            );
            $response = Mage::helper('core')->jsonEncode($response);
        } catch (Exception $e) {
            $response = array(
                'error'     => true,
                'message'   => $this->__('Cannot add new comment.')
            );
            $response = Mage::helper('core')->jsonEncode($response);
        }
        $this->getResponse()->setBody($response);
    }



    /**
     * Decides if we need to create dummy shipment item or not
     * for eaxample we don't need create dummy parent if all
     * children are not in process
     *
     * @deprecated after 1.4, Mage_Sales_Model_Service_Order used
     * @param Mage_Sales_Model_Order_Item $item
     * @param array $qtys
     * @return bool
     */
    protected function _needToAddDummy($item, $qtys) {
        if ($item->getHasChildren()) {
            foreach ($item->getChildrenItems() as $child) {
                if ($child->getIsVirtual()) {
                    continue;
                }
                if ((isset($qtys[$child->getId()]) && $qtys[$child->getId()] > 0)
                        || (!isset($qtys[$child->getId()]) && $child->getQtyToShip())) {
                    return true;
                }
            }
            return false;
        } else if($item->getParentItem()) {
            if ($item->getIsVirtual()) {
                return false;
            }
            if ((isset($qtys[$item->getParentItem()->getId()]) && $qtys[$item->getParentItem()->getId()] > 0)
                || (!isset($qtys[$item->getParentItem()->getId()]) && $item->getParentItem()->getQtyToShip())) {
                return true;
            }
            return false;
        }
    }

    /**
     * Create shipping label for specific shipment with validation.
     *
     * @param Mage_Sales_Model_Order_Shipment $shipment
     * @return bool
     */
    protected function _createShippingLabel(Mage_Sales_Model_Order_Shipment $shipment)
    {
        if (!$shipment) {
            return false;
        }
		
        $data = $this->getRequest()->getPost('shipment');		
		
		$readConnection = Mage::helper('function/more')->getDbReadObject();
	
		$writeConnection = Mage::helper('function/more')->getDbWriteObject();
		
		
		//print_r($this->getRequest()->getParam('packages'));
		
		//////////////////////////////Code for valid shipment/////////////////////////////////////
		$itemData = array();
		//$itemDetails = Mage::getModel('sales/order_items')->load(2443); 
		
		$unserializeData = ($this->getRequest()->getParam('packages'));
		
		foreach($unserializeData as $unserializeDataKey=>$unserializeDataVal_val)
		{
			
			
			//foreach($unserializeDataVal as $unserializeDataVal_val){
				foreach($unserializeDataVal_val['items'] as $unserializeDataVal_val2){
				$itemData[$unserializeDataVal_val2["order_item_id"]] = $unserializeDataVal_val2["qty"];
				}
			//}
			
			
		}
		
		
		//if(count($itemData) == 0)
			//echo $itemData = $data["items"];		
			
		$pkgType = array();
		foreach($itemData as $itemkey=>$itemid){
		if($itemid > 0){
			
			$prdType = $readConnection->fetchCol("select prdType from sales_flat_order_item where item_id='".$itemkey."'");
			
			if(!in_array($prdType[0],$pkgType))
			$pkgType[] = $prdType[0];			
		}
		}			
			
	
		
		

		
        $carrier = $shipment->getOrder()->getShippingCarrier();
        if (!$carrier->isShippingLabelsAvailable()) {
            return false;
        }
        $shipment->setPackages($this->getRequest()->getParam('packages'));
		
		
		
		
        
		
		
		
			if(count($pkgType) == 1 && in_array('t-shirt',$pkgType)){
			
			
				
			$response = Mage::getModel('shipping/shipping')->requestToShipmentTshirt($shipment);
			}
			else{
				
			
				
			$response = Mage::getModel('shipping/shipping')->requestToShipment($shipment);
			}			
		
			
		
		
		
        if ($response->hasErrors()) {
            Mage::throwException($response->getErrors());
        }
        if (!$response->hasInfo()) {
            return false;
        }
        $labelsContent = array();
        $labelsCodReturnContent = array();
        $trackingNumbers = array();
        $info = $response->getInfo();
        foreach ($info as $inf) {
            if (!empty($inf['tracking_number']) && !empty($inf['label_content'])) {
                $labelsContent[] = $inf['label_content'];
				if($inf['cod_return_content'] != '')
                $labelsContent[] = $inf['cod_return_content'];
                $trackingNumbers[] = $inf['tracking_number'];
            }
        }
        $outputPdf = $this->_combineLabelsPdf($labelsContent);
        $shipment->setShippingLabel($outputPdf->render());
		/*
		if(count($labelsCodReturnContent) > 0){
		$outputPdfCodReturn = $this->_combineLabelsPdf($labelsCodReturnContent);
        $shipment->setCodReturnShippingLabel($outputPdfCodReturn->render());
		}*/
		
		
        $carrierCode = $carrier->getCarrierCode();
        $carrierTitle = Mage::getStoreConfig('carriers/'.$carrierCode.'/title', $shipment->getStoreId());
        if ($trackingNumbers) {
            foreach ($trackingNumbers as $trackingNumber) {
                $track = Mage::getModel('sales/order_shipment_track')
                        ->setNumber($trackingNumber)
                        ->setCarrierCode($carrierCode)
                        ->setTitle($carrierTitle);
                $shipment->addTrack($track);
            }
        }
        return true;
    }

    /**
     * Create shipping label action for specific shipment
     *
     */
    public function createLabelAction()
    {
		
        $response = new Varien_Object();
        try {
            $shipment = $this->_initShipment();
            if ($this->_createShippingLabel($shipment)) {
                $shipment->save();
                $this->_getSession()->addSuccess(Mage::helper('sales')->__('The shipping label has been created.'));
                $response->setOk(true);
            }
        } catch (Mage_Core_Exception $e) {
            $response->setError(true);
            $response->setMessage($e->getMessage());
        } catch (Exception $e) {
            Mage::logException($e);
            $response->setError(true);
            $response->setMessage(Mage::helper('sales')->__('An error occurred while creating shipping label.'));
        }

        $this->getResponse()->setBody($response->toJson());
    }

    /**
     * Print label for one specific shipment
     *
     */
    public function printLabelAction()
    {
        try {
            $shipment = $this->_initShipment();
            $labelContent = $shipment->getShippingLabel();
            if ($labelContent) {
                $pdfContent = null;
                if (stripos($labelContent, '%PDF-') !== false) {
                    $pdfContent = $labelContent;
                } else {
                    $pdf = new Zend_Pdf();
                    $page = $this->_createPdfPageFromImageString($labelContent);
                    if (!$page) {
                        $this->_getSession()->addError(Mage::helper('sales')->__('File extension not known or unsupported type in the following shipment: %s', $shipment->getIncrementId()));
                    }
                    $pdf->pages[] = $page;
                    $pdfContent = $pdf->render();
                }

                return $this->_prepareDownloadResponse(
                    'ShippingLabel(' . $shipment->getIncrementId() . ').pdf',
                    $pdfContent,
                    'application/pdf'
                );
            }
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Exception $e) {
            Mage::logException($e);
            $this->_getSession()
                ->addError(Mage::helper('sales')->__('An error occurred while creating shipping label.'));
       }
       $this->_redirect('*/sales_order_shipment/view', array(
           'shipment_id' => $this->getRequest()->getParam('shipment_id')
       ));
    }

    /**
     * Create pdf document with information about packages
     *
     * @return void
     */
    public function printPackageAction()
    {
        $shipment = $this->_initShipment();

        if ($shipment) {
            $pdf = Mage::getModel('sales/order_pdf_shipment_packaging')->getPdf($shipment);
            $this->_prepareDownloadResponse('packingslip'.Mage::getSingleton('core/date')->date('Y-m-d_H-i-s').'.pdf',
                $pdf->render(), 'application/pdf'
            );
        }
        else {
            $this->_forward('noRoute');
        }
    }

    /**
     * Batch print shipping labels for whole shipments.
     * Push pdf document with shipping labels to user browser
     *
     * @return null
     */
    public function massPrintShippingLabelAction()
    {
        $request = $this->getRequest();
        $ids = $request->getParam('order_ids');
        $createdFromOrders = !empty($ids);
        $shipments = null;
        $labelsContent = array();
        switch ($request->getParam('massaction_prepare_key')) {
            case 'shipment_ids':
                $ids = $request->getParam('shipment_ids');
                array_filter($ids, 'intval');
                if (!empty($ids)) {
                    $shipments = Mage::getResourceModel('sales/order_shipment_collection')
                        ->addFieldToFilter('entity_id', array('in' => $ids));
                }
                break;
            case 'order_ids':
                $ids = $request->getParam('order_ids');
                array_filter($ids, 'intval');
                if (!empty($ids)) {
                    $shipments = Mage::getResourceModel('sales/order_shipment_collection')
                        ->setOrderFilter(array('in' => $ids));
                }
                break;
        }

        if ($shipments && $shipments->getSize()) {
            foreach ($shipments as $shipment) {
                $labelContent = $shipment->getShippingLabel();
                if ($labelContent) {
                    $labelsContent[] = $labelContent;
                }
            }
        }

        if (!empty($labelsContent)) {
            $outputPdf = $this->_combineLabelsPdf($labelsContent);
            $this->_prepareDownloadResponse('ShippingLabels.pdf', $outputPdf->render(), 'application/pdf');
            return;
        }

        if ($createdFromOrders) {
            $this->_getSession()
                ->addError(Mage::helper('sales')->__('There are no shipping labels related to selected orders.'));
            $this->_redirect('*/sales_order/index');
        } else {
            $this->_getSession()
                ->addError(Mage::helper('sales')->__('There are no shipping labels related to selected shipments.'));
            $this->_redirect('*/sales_order_shipment/index');
        }
    }

    /**
     * Combine array of labels as instance PDF
     *
     * @param array $labelsContent
     * @return Zend_Pdf
     */
    protected function _combineLabelsPdf(array $labelsContent)
    {
        $outputPdf = new Zend_Pdf();
        foreach ($labelsContent as $content) {
            if (stripos($content, '%PDF-') !== false) {
                $pdfLabel = Zend_Pdf::parse($content);
                foreach ($pdfLabel->pages as $page) {
                    $outputPdf->pages[] = clone $page;
                }
            } else {
                $page = $this->_createPdfPageFromImageString($content);
                if ($page) {
                    $outputPdf->pages[] = $page;
                }
            }
        }
        return $outputPdf;
    }

    /**
     * Create Zend_Pdf_Page instance with image from $imageString. Supports JPEG, PNG, GIF, WBMP, and GD2 formats.
     *
     * @param string $imageString
     * @return Zend_Pdf_Page|bool
     */
    protected function _createPdfPageFromImageString($imageString)
    {
        $image = imagecreatefromstring($imageString);
        if (!$image) {
            return false;
        }

        $xSize = imagesx($image);
        $ySize = imagesy($image);
        $page = new Zend_Pdf_Page($xSize, $ySize);

        imageinterlace($image, 0);
        $tmpFileName = sys_get_temp_dir() . DS . 'shipping_labels_'
                     . uniqid(mt_rand()) . time() . '.png';
        imagepng($image, $tmpFileName);
        $pdfImage = Zend_Pdf_Image::imageWithPath($tmpFileName);
        $page->drawImage($pdfImage, 0, 0, $xSize, $ySize);
        unlink($tmpFileName);
        return $page;
    }

    /**
     * Return grid with shipping items for Ajax request
     *
     * @return Mage_Core_Controller_Response_Http
     */
    public function getShippingItemsGridAction()
    {
        $this->_initShipment();
        return $this->getResponse()->setBody(
            $this->getLayout()
                ->createBlock('adminhtml/sales_order_shipment_packaging_grid')
                ->setIndex($this->getRequest()->getParam('index'))
                ->toHtml()
           );
    }
}