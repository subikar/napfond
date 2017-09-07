<?php
class Gravita_Order_Adminhtml_OrderformController extends Mage_Adminhtml_Controller_Action
{
	public function indexAction()
    {
        $this->loadLayout()->renderLayout();	
    } 
	
	public function bpostAction()
	{		
		$params = $this->getRequest()->getParams();
		$getOrder = $params['getshipping'];
		if($getOrder != '')
		{
			$orderId = $params['orderid'];	
			$order = Mage::getModel('sales/order')->loadByIncrementId($orderId);
			$shipping_address = $order->getShippingAddress();
			$prefix = $shipping_address->getPrefix();
			$fname = $shipping_address->getFirstname();
			$lname = $shipping_address->getLastname();
			$street = $shipping_address->getStreetFull();
			$city = $shipping_address->getCity();
			$region = $shipping_address->getRegion();
			$phone = $shipping_address->getTelephone(); 
			$post = $shipping_address->getPostcode(); 
			$country_id = $shipping_address->getCountry_id();
			$country = Mage::getModel('directory/country')->loadByCode($country_id);
			$country_name = $country->getName();
			echo '<fieldset><address>'.$prefix.' '.$fname. ' '.$lname.'<br>'.$street.'<br>'.$city.',  '.$region.', '.$post.'<br>'.$country_name.'<br>T : '.$phone.'</address></fieldset>';
			exit();		
		}
	}

	public function itemsAction()
	{
		$params = $this->getRequest()->getParams();
		$getItems = $params['getitems'];
		if($getItems != '')
		{
			$orderId = $params['orderid'];	
			$order = Mage::getModel('sales/order')->loadByIncrementId($orderId);
			$ordered_total = $order->getBaseGrandTotal();
			$ordered_items = $order->getAllItems();
			$i=1;
			foreach ($ordered_items as $items) 
			{
				$itemId = $items->getId();
				$itemName = $items->getName();
				$itemSku = $items->getSku();
        		$itemPrice = $items->getPrice();
        		$itemQty = $items->getQtyOrdered();	
        		$itemSubTotal = $items->getRowTotal();
        		$finelName = $itemName;
        		$itemOptions = $items->getProductOptions();	
        		if($itemOptions)
        		{
        		//print_r($itemOptions);exit();
	        		$op = $itemOptions['options'];        		
	        		foreach ($op as $options) 
	        		{
	        			$optionLabel = $options['label'];
	        			if($optionLabel == 'Product Detail')
	        			{
		        			$optionVal = $options['value'];
		        			$val = explode('/', $optionVal);
		        			$finelName = $val[1].' '.$val[2].' '.$itemName.' '.$val[0];  
		        		}       		
					}
				}		
				echo '<tr class="border">
				            <td class="a-center"><input type="checkbox" name="chk[]" id="chk_'.$itemId.'" value="'.$itemId.'" checked="checked" onclick="update_total('.$orderId.','.$itemId.');" /></td>	
				            <td class="a-center">
				            	<div class="item-container" id="order_item_'.$itemId.'">
				                  <div class="item-text">
				                    <h5 class="title"><span id="order_item_'.$itemId.'_title">'.$itemName.'</span></h5>
				                  </div>
				                </div>
				            </td>					              
				            <td class="a-center"><strong>'.round($itemQty).'</strong></td>				              
				            <td class="a-center"><span class="price">'.number_format($itemPrice, 2).'</span></td>
				            <td class="a-center"><span class="price">'.number_format($itemSubTotal, 2).'</span></td>
			              </tr>';
					$i++;			
			}
			echo '<tr>
	              	<td colspan="3"></td>
	              	<td class="a-center"><strong>Grand Total</strong></td>
	              	<td class="a-center" id="g-total">'.$ordered_total.'</td>
	              </tr>';
		}
	}

	public function updateTotalAction()		
	{
		$read = Mage::helper('function/more')->getDbReadObject();
		$params = $this->getRequest()->getParams();
		$updatetotal = $params['updatetotal'];
		if($updatetotal != '')
		{
			$itemId = $params['itemid'];
			$incrementId = $params['orderid'];	
			$order = Mage::getModel('sales/order')->loadByIncrementId($incrementId);
   			$orderId = $order->getId();
			$get_total_qry = $read->query("SELECT `row_total` FROM `sales_flat_order_item` WHERE `order_id` = ".$orderId." AND `item_id` = ".$itemId);
			$get_total_res = $get_total_qry->fetch();
			echo $row_total = $get_total_res['row_total'];exit();
		}
	}

	public function emailsendAction()
	{
		$readConnection = Mage::helper('function/more')->getDbReadObject();	
		$writeConnection = Mage::helper('function/more')->getDbWriteObject();
		
		
		
		//$params = $this->getRequest()->getParams();
		if(isset($_POST['oid']) && $_POST['oid'] != '')
		{
			$chk = array();
			$oid = $_POST['oid'];
			$chk = $_POST['chk'];
			$time = Mage::getModel('core/date')->date('H:i:s');
			$delivery_date = $_POST['delivery_date'];
			$delivery_date =  date('l, F d, Y',strtotime ($delivery_date));
			//$delivery_date = explode('-',$delivery_date);
			//$delivery_date = $delivery_date[1].' '.$delivery_date[0].', '.$delivery_date[2];
			$order = Mage::getModel('sales/order')->loadByIncrementId($oid);
			$customername = $order->getCustomerName();
			$shipping_address = $order->getShippingAddress();
			//$prefix = $shipping_address->getPrefix();
			$fname = $shipping_address->getFirstname();
			$lname = $shipping_address->getLastname();
			$street = $shipping_address->getStreetFull();
			$city = $shipping_address->getCity();
			$region = $shipping_address->getRegion();
			$phone = $shipping_address->getTelephone(); 
			$post = $shipping_address->getPostcode(); 
			$country_id = $shipping_address->getCountry_id();
			$country = Mage::getModel('directory/country')->loadByCode($country_id);
			$country_name = $country->getName();
			$ship_address = '<strong>'.$prefix.' '.$fname. ' '.$lname.' </strong><br>'.$street.'<br>'.$city.', '.$region.', '.$post.
			'<br>'.$country_name.'<br>'.'T '.$phone;
			$ordered_total = $order->getBaseGrandTotal();
			$ordered_items = $order->getAllItems();
			$i=1;$order_detail = '';$total = 0;
			
			
			$smsItemName = '';
			$smsItemNameComma = '';
			$order_detail .= '
               <tr>
        <td class="inner" style="padding-top:10px;padding-bottom:10px;padding-right:10px;padding-left:10px;" ><table width="100%" border="0" class="tableborder" cellspacing="5" cellpadding="0" style="border-spacing:0;font-family:sans-serif;color:#333333;border-width:1px;border-style:solid;border-color:#A6A6A6;border-top-width:0px;border-left-width:0px;" >
		<tbody>
              <tr style="border-width:1px;border-style:solid;border-color:#A6A6A6;border-top-width:0px;border-left-width:0px;" >
                <td bgcolor="#c71b1b" style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;border-width:1px;border-style:solid;border-color:#A6A6A6;border-right-width:0px;border-bottom-width:0px;" ><p class="tableHead oneWord" style="Margin:0;line-height:1.5;font-weight:bold;padding-top:5px;padding-bottom:5px;padding-right:5px;padding-left:5px;font-size:12px;color:#fff;background-color:#c71b1b;background-image:none;background-repeat:repeat;background-position:top left;background-attachment:scroll;text-align:center;white-space:nowrap;" >S. No.</p></td>
				 <td bgcolor="#c71b1b" style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;border-width:1px;border-style:solid;border-color:#A6A6A6;border-right-width:0px;border-bottom-width:0px;" ><p class="tableHead" style="Margin:0;line-height:1.5;font-weight:bold;padding-top:5px;padding-bottom:5px;padding-right:5px;padding-left:5px;font-size:12px;color:#fff;background-color:#c71b1b;background-image:none;background-repeat:repeat;background-position:top left;background-attachment:scroll;text-align:center;" >ITEM DETAILS</p></td>  
                  <td  bgcolor="#c71b1b" style="max-width:150px;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;border-width:1px;border-style:solid;border-color:#A6A6A6;border-right-width:0px;border-bottom-width:0px;" ><p class="tableHead" style="Margin:0;line-height:1.5;font-weight:bold;padding-top:5px;padding-bottom:5px;padding-right:5px;padding-left:5px;font-size:12px;color:#fff;background-color:#c71b1b;background-image:none;background-repeat:repeat;background-position:top left;background-attachment:scroll;text-align:center;" >QTY.</p></td>				 
				</tr>';			                
			foreach ($ordered_items as $items) 
			{
				$itemId = $items->getId();
				if(in_array($itemId, $chk))
				{				
					$itemName = $items->getName();
					$itemSku = $items->getSku();
		    		$itemPrice = $items->getPrice();
		    		$itemQty = $items->getQtyOrdered();	
		    		$itemSubTotal = $items->getRowTotal();
		    		$total += $itemSubTotal;
		    		$finelName = $itemName;
					
		    		$itemOptions = $items->getProductOptions();	
		    		if($itemOptions)
		    		{
		    			$op = $itemOptions['options'];        		
		        		foreach ($op as $options) 
		        		{
		        			$optionLabel = $options['label'];
		        			if($optionLabel == 'Product Detail')
		        			{
			        			$optionVal = $options['value'];
			        			$val = explode('/', $optionVal);
			        			$finelName = $val[1].' '.$itemName;
			        		}       		
						}
					}
					$order_detail .= '<tr style="border-width:1px;border-style:solid;border-color:#A6A6A6;border-top-width:0px;border-left-width:0px;" ><td style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;border-width:1px;border-style:solid;border-color:#A6A6A6;border-right-width:0px;border-bottom-width:0px;" ><p class="tableData center" style="Margin:0;color:#000;line-height:1.5;padding-top:5px;padding-bottom:5px;padding-right:5px;padding-left:5px;font-size:12px;text-align:center;" >'.$i.'</p></td>
					<td style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;border-width:1px;border-style:solid;border-color:#A6A6A6;border-right-width:0px;border-bottom-width:0px;" ><p class="tableData" style="Margin:0;color:#000;text-align:left;line-height:1.5;padding-top:5px;padding-bottom:5px;padding-right:5px;padding-left:5px;font-size:12px;" >'.$itemName.'</p></td>
					<td style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;border-width:1px;border-style:solid;border-color:#A6A6A6;border-right-width:0px;border-bottom-width:0px;" ><p class="tableData center" style="Margin:0;color:#000;line-height:1.5;padding-top:5px;padding-bottom:5px;padding-right:5px;padding-left:5px;font-size:12px;text-align:center;" >'.$itemQty*1 .'</p></td>
              </tr>';              	
					                  	
					                  		
					$i++;	


				$smsItemName = $smsItemName.$smsItemNameComma.'"'.str_replace(' ','+',trim($itemName)).'"';
				$smsItemNameComma = ',+';


					
				}					
			}
			$shipping_amount = $order->getShippingAmount();
			$total += $shipping_amount;
			$order_detail .= '</tbody>
          </table></td>';
			//echo '<fieldset><address>'.$prefix.' '.$fname. ' '.$lname.'<br>'.$street.'<br>'.$city.',  '.$region.', '.$post.'<br>'.$country_name.'<br>T : '.$phone.'</address></fieldset>';
			$senderName =  Mage::getStoreConfig('trans_email/ident_general/name'); 
			$senderEmail = Mage::getStoreConfig('trans_email/ident_general/email');
			$email = $order->getCustomerEmail();
			try
			{		 	 	
				$emailTemplate = Mage::getModel('core/email_template')->loadDefault('order_email_template');
				$emailTemplateVariables = array();
				$emailTemplateVariables['name'] = $fname;
				$emailTemplateVariables['order_id'] = $oid;	
				$emailTemplateVariables['delivery_date'] = $delivery_date;	
				$emailTemplateVariables['ship_address'] = $ship_address;	
				$emailTemplateVariables['order_detail'] = $order_detail;
				$emailTemplateVariables['customername'] = $customername;
				$emailTemplateVariables['fname'] = $fname;
				$emailTemplate->setSenderName($senderName);
	            $emailTemplate->setSenderEmail($senderEmail);
	            $emailTemplate->setType('html');
	            $emailTemplate->setTemplateSubject('Your Bhishoom.com order (#'.$oid.') delivery confirmation');
	            $emailTemplate->send($email, $fname, $emailTemplateVariables);		
				Mage::getSingleton('core/session')->addSuccess(Mage::helper('order')->__('Mail Sent.'));
				
				
				
				
				
				
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
						 
			//echo $shipment->getOrder()->getIncrementId();
			/////////////////////////////////////Code for sending SMS to DISPATCHED Items to client/////////////////////////////////////////			
			//Mage::dispatchEvent('bhishoom_sales_order_shipment_save_after', array($itemsArray));
			$smsMobilePhone = $order->getBillingAddress()->getTelephone();
			
			Mage::helper('function/sms')->sendDeliverySms2($smsItemName,$smsMobilePhone);
			
			Mage::helper('function/sms')->setOrderToDelivered($order);
			/*$smsOrderIncrementId = $oid;
			
			$txt="Your+package+with+item(s):+".$smsItemName."+has+been+successfully+delivered.+Thank+you+for+choosing+us.+Hope+to+see+you+again+soon.";	

			$smsMobilePhone = $order->getBillingAddress()->getTelephone();

			if($smsItemName!=''){
			
					$callURL = 'http://manage.sarvsms.com/api/send_transactional_sms.php?username=u7892&msg_token=1ST5G8&sender_id=BISHOM&message='.$txt.'&mobile='.$smsMobilePhone;
			
					$ch = curl_init(); 
					curl_setopt($ch, CURLOPT_URL, $callURL); 
					curl_setopt($ch, CURLOPT_HEADER, 1); 
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
					$data = curl_exec($ch); 
					curl_close($ch); 
					
					$query = "insert into twilio set sms_to='".$phone."',sms_body='test message and that is', date='".date('Y-m-d')."',timestamp='".date('Y-m-d h:i:s')."',api_response='".$data."'";
					$writeConnection->query($query); 			
			}*/
			
		
			////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////				
				
				
				
				
				
				
				
			 }
			 catch(Exception $error)
			 {
				 Mage::getSingleton('core/session')->addError($error->getMessage());
				 return false;
			 }					
			$this->_redirect('*/*');
		}
	}
}