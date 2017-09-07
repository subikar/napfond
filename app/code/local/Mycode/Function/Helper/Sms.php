<?php
class Mycode_Function_Helper_Sms extends Mage_Core_Helper_Abstract
{
	public function __construct() { 

	}	
	public function sendDeliverySms($shipMentId)
	{
		$shipment = Mage::getModel('sales/order_shipment')->load($shipMentId);
		$shipMent_Item_collection=Mage::getResourceModel('sales/order_shipment_item_collection')->addAttributeToSelect('*')->addAttributeToFilter('parent_id',$shipMentId);
		
		$oid = $shipment->getData('order_id');
		$order = Mage::getModel('sales/order')->load($oid);
		
		$smsItemName = '';
		$smsItemNameComma = '';			

			foreach($shipMent_Item_collection as $shipMent_Item) 
			{
				$itemName = $shipMent_Item['name'];
				$smsItemName = $smsItemName.$smsItemNameComma.'"'.str_replace(' ','+',trim($itemName)).'"';
				$smsItemNameComma = ',+';
				$i++;
			}		
			
		
			//$smsOrderIncrementId = $oid;
			$txt="Your+package+with+item(s):+".$smsItemName."+has+been+successfully+delivered.+Thank+you+for+choosing+us.+Hope+to+see+you+again+soon.";	
			$smsMobilePhone = $order->getBillingAddress()->getTelephone();

			$this->sendDeliverySMSFinal($smsItemName,$smsMobilePhone);
			Mage::helper('function/sms')->setOrderToDelivered($order);
	}
	
	public function sendDeliverySms2($smsItemName,$smsMobilePhone)
	{
			$txt="Your+package+with+item(s):+".$smsItemName."+has+been+successfully+delivered.+Thank+you+for+choosing+us.+Hope+to+see+you+again+soon.";		
			$this->sendDeliverySMSFinal($txt,$smsMobilePhone);
	}	
	
	public function setOrderToDelivered($order)
	{
				$order->setData('state', "complete");
				$order->setStatus("delivered");
				$history = $order->addStatusHistoryComment('Order was set to Delivered after sending the delivery email and sms.');
				$history->setIsCustomerNotified(false);
				$order->save();
	}	
	
	
	public function sendDeliverySMSFinal($txt,$smsMobilePhone){
		
					$callURL = 'http://manage.sarvsms.com/api/send_transactional_sms.php?username=u7892&msg_token=1ST5G8&sender_id=BISHOM&message='.$txt.'&mobile='.$smsMobilePhone;
					//$resp = Kojoman_Twilio_Model_Observer::call_url_for_sms($callURL);					
					$ch = curl_init(); 
					curl_setopt($ch, CURLOPT_URL, $callURL); 
					curl_setopt($ch, CURLOPT_HEADER, 1); 
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
					$data = curl_exec($ch); 
					curl_close($ch);					
		
	}
	
	
	public function sendOutForDeliverySms($shipMentId)
	{
		$shipment = Mage::getModel('sales/order_shipment')->load($shipMentId);
		$shipMent_Item_collection=Mage::getResourceModel('sales/order_shipment_item_collection')->addAttributeToSelect('*')->addAttributeToFilter('parent_id',$shipMentId);
		
		$oid = $shipment->getData('order_id');
		$order = Mage::getModel('sales/order')->load($oid);
		
		$smsItemName = '';
		$smsItemNameComma = '';			

			foreach($shipMent_Item_collection as $shipMent_Item) 
			{
				$itemName = $shipMent_Item['name'];
				$smsItemName = $smsItemName.$smsItemNameComma.'"'.str_replace(' ','+',trim($itemName)).'"';
				$smsItemNameComma = ',+';
				$i++;
			}		
			
		
			//$smsOrderIncrementId = $oid;
			$txt="Arriving+today:+Your+package+with+item(s):+".$smsItemName."+is+out+for+delivery.";
			
			$smsMobilePhone = $order->getBillingAddress()->getTelephone();
			
			//$smsMobilePhone = '9983877700';

			if($smsItemName!=''){
					$callURL = 'http://manage.sarvsms.com/api/send_transactional_sms.php?username=u7892&msg_token=1ST5G8&sender_id=BISHOM&message='.$txt.'&mobile='.$smsMobilePhone;
					//$resp = Kojoman_Twilio_Model_Observer::call_url_for_sms($callURL);					
					$ch = curl_init(); 
					curl_setopt($ch, CURLOPT_URL, $callURL); 
					curl_setopt($ch, CURLOPT_HEADER, 1); 
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
					$data = curl_exec($ch); 
					curl_close($ch);					
					//$dataToSave = array('sms_to' => $phone,'sms_body' => $txt,'date' => date('Y-m-d'),'timestamp' => date('Y-m-d h:i:s'),'api_response' => $data); 
					//$model = Mage::getModel('twilio/twilio')->setData($dataToSave); 					
					//$model->save();					
					//$query = "insert into twilio set sms_to='".$phone."',sms_body='test message and that is', date='".date('Y-m-d')."',timestamp='".date('Y-m-d h:i:s')."',api_response='".$data."'";
					//$writeConnection->query($query); 			
			}
	}
}

?>