<?php

//require_once(Mage::getBaseDir('lib') . '/twilio-php/Services/Twilio.php');

//require_once('twilio-php/Services/Twilio.php');


class Kojoman_Twilio_Model_Observer
{

    protected $AccountSid;
    protected $AuthToken;
    protected $twilioNumber;
    protected $smsNotificationNumber;

    // Dummy variables for testing. Change to something better
    // or use data passed from Observer.
    protected $newOrderMessage = "Just got a new order...";

    //Mage::log($twilio_number);

    //protected $twilio = new Services_Twilio($this->AccountSid, $this->AuthToken);

    //protected $twilio = Services_Twilio("abc", "$this->AuthToken");

    /**
     * Constructor
     *
     * Set up the twilio object and assign the numbers to use.
     */
    public function __construct()
    {
        $decryptor = Mage::helper('core');

        $this->AccountSid = Mage::helper('twilio')->getAccountSID();
        $this->AuthToken = Mage::helper('twilio')->getAuthToken();
        $this->twilioNumber = Mage::helper('twilio')->getTwilioNumber();
        $this->smsNotificationNumber = Mage::helper('twilio')->getSMSNotificationNumber();

        //parent::__construct($this->AccountSid, $this->AuthToken);
    }

    /**
     * Send Notification when there's a new order.
     *
     */
    public function notifyNewOrder(Varien_Event_Observer $observer)
    {
        //Mage::log($observer->getEvent());

        //Check to see if module is enabled
        if (!Mage::helper('twilio')->isEnabled() || !Mage::helper('twilio')->sendSmsForNewOrders()) {
            Mage::log("Magento Twilio module is not enabled or should not send SMS for new orders.");
            return;
        }

        //Send SMS via twilio
        // try {
        // 	$sms = $this->account->messages->sendMessage(
        // 		$this->twilioNumber,
        // 		$this->smsNotificationNumber,
        // 		$this->message
        // 	);
        // } catch (Exception $e) {
        // 	Mage::logException($e);
        // }

        //Send SMS via twilio
        /*try {
            $sms = $this->account->messages->create(
                array(
                    'To'             => $this->twilioNumber,
                    'From'           => $this->smsNotificationNumber,
                    'Body'           => $this->message,
                    'StatusCallback' => Mage::helper('twilio')->getStatusCallbackUrl() //TODO: Verify this is set first
                )
            );
        } catch (Exception $e) {

        }*/

$orderIds = $observer->getData('order_ids');
		
foreach($orderIds as $_orderId){
            $order     = Mage::getModel('sales/order')->load($_orderId);
 $incrementId = $order->getIncrementId();
$order->loadByIncrementId($incrementId);		
	
//$incrementId = $observer->getOrder()->getIncrementId();
$phone = $order->getBillingAddress()->getTelephone();

$fname = $order->getBillingAddress()->getFirstname();

$lname = $order->getBillingAddress()->getLastname();

$fullname = ucwords($fname."+".$lname);

//$incrementId = $observer->getOrder()->getIncrementId();
$phone = $order->getBillingAddress()->getTelephone();

//$shipping_phone = $order->getShippingAddress()->getTelephone();

$payment_method = $order->getPayment()->getMethodInstance()->getCode();

$myfile = fopen(Mage::getBaseDir()."/sms_testing.txt", "w") or die("Unable to open file!");

if($payment_method=='payucheckout_shared')
$txt = "Your+order+%23".$incrementId."+amounting+".Mage::helper('core')->currency($order->getBaseGrandTotal(), true, false)."+has+been+successfully+placed.+It+is+being+processed+and+you+will+be+intimated+once+it+is+ready+for+shipment.+Thank+you+for+shopping+at+Bhishoom.";
elseif($payment_method=='phoenix_cashondelivery')
$txt="Your+COD+order+%23".$incrementId."+amounting+".Mage::helper('core')->currency($order->getBaseGrandTotal(), true, false)."+has+been+successfully+placed.+Our+team+will+contact+you+within+next+24+hrs+for+order+confirmation.+Thank+you+for+shopping+at+Bhishoom.";


fwrite($myfile, $txt);
fclose($myfile);


if($phone!='' && $order->getData('shipping_address_id') > 0){
$callURL = 'http://manage.sarvsms.com/api/send_transactional_sms.php?username=u7892&msg_token=1ST5G8&sender_id=BISHOM&message='.$txt.'&mobile='.$phone;
$resp = $this->call_url_for_sms($callURL);
}


/////////////////////////////////////Send Gift Certificate  SMS////////////////////////////////////////


 foreach ($order->getAllItems() as $item) {
                if ($item->getProductType()!='ugiftcert') {
                    continue;
                }

			  $opts = $item->getProductOptions(); 			
              foreach($opts['info_buyRequest'] as $optsKey => $optsVal)
              {
                if(trim($optsKey) == 'recipient_name')
                $recipient_name = str_replace(' ','+',$optsVal);
                if(trim($optsKey) == 'recipient_email')
                $recipient_email = $optsVal;             
                if(trim($optsKey) == 'recipient_phone')
                $recipient_phone = $optsVal;             
                //if(trim($optsKey) == 'recipient_message')
               // echo '<span class="fontSize11px fontStyleItalic"><strong>Recipient Message: </strong></span><span class="fontSize11px fontStyleItalic fontStyleNormal">'.$optsVal.'</span><br/>';                
              }				
				
/////////////////Messge for GC sender////////////////////////////
$txt = "Thank+you+for+purchasing+gift+voucher+amounting+".str_replace(' ','+',Mage::helper('core')->currency($item->getPrice(), true, false))."+for+".ucwords($recipient_name)."+at+Bhishoom.+Gift+voucher+details+have+been+sent+to+".ucwords($recipient_name)."+at+email+provided+by+you.+Hope+to+see+you+again+soon.";


if($phone!=''){		
$callURL = 'http://manage.sarvsms.com/api/send_transactional_sms.php?username=u7892&msg_token=1ST5G8&sender_id=BISHOM&message='.$txt.'&mobile='.$phone;
$resp = $this->call_url_for_sms($callURL);


$myfile = fopen(Mage::getBaseDir()."/sms_testing.txt", "w") or die("Unable to open file!");

fwrite($myfile, $callURL);
fclose($myfile);
}
//////////////////////////////////////////////////////////////////

/////////////////Messge for GC receiver////////////////////////////
$txt ="A+gift+voucher+amounting+".str_replace(' ','+',Mage::helper('core')->currency($item->getPrice(), true, false))."+has+been+purchased+for+you+by+".$fullname."+at+www.bhishoom.com.+Gift+voucher+details+have+been+mailed+to+you+at+".trim($recipient_email).".+Just+use+the+voucher+code+at+checkout+to+redeem+it.+Hope+to+see+you+soon.";


if($recipient_phone!=''){		
$callURL = 'http://manage.sarvsms.com/api/send_transactional_sms.php?username=u7892&msg_token=1ST5G8&sender_id=BISHOM&message='.$txt.'&mobile='.$recipient_phone;
$resp = $this->call_url_for_sms($callURL);


$myfile = fopen(Mage::getBaseDir()."/sms_testing.txt", "w") or die("Unable to open file!");

fwrite($myfile, $callURL);
fclose($myfile);
//////////////////////////////////////////////////////////////////


}				
				
 }
///////////////////////////////////////////////////////////////////////////////////////////////////////




}

        return $this;
    }

    //Send notification when new customer signs up
    public function notifyNewCustomer(Varien_Event_Observer $observer)
    {
        /* @var $order Mage_Sales_Model_Order */
        if (!Mage::helper('twilio')->isEnabled() || !Mage::helper('twilio')->sendSmsForNewCustomers()) {
            Mage::log("Magento Twilio module is not enabled or should not send SMS for new customers");
            return;
        }

        try {
            /*$sms = $this->account->messages->sendMessage(
                $this->twilioNumber,
                $this->smsNotificationNumber,
                'A new customer just signed up'
            );
            Mage::log($sms);*/

        } catch (Exception $e) {
            Mage::logException($e);
        }

        return $this;
    }

    //Send notification when new customer signs up
    public function notifyNewShipment(Varien_Event_Observer $observer)
    {
		
echo '<pre>';		
print_r($observer->getData());		
exit;		
		
		
        /* @var $order Mage_Sales_Model_Order */
        if (!Mage::helper('twilio')->isEnabled() || !Mage::helper('twilio')->sendSmsForNewShipments()) {
            Mage::log("Magento Twilio module is not enabled or should not send SMS for new shipments.");
            return;
        } else {
            Mage::log("Magento Twilio module is enabled");
        }
		/*
        try {
            $sms = $this->account->messages->sendMessage(
                $this->twilio_number,
                $this->smsNotificationNumber,
                'Your order has been just been shipped.'
            );
        } catch (Exception $e) {
            Mage::logException($e);
        }*/

echo '<pre>';		
print_r($observer->getData());		
exit;		
$incrementId = $observer->getOrder()->getIncrementId();
$phone = $observer->getOrder()->getBillingAddress()->getTelephone();
if($phone!=''){		
$callURL = ' http://manage.sarvsms.com/api/send_transactional_sms.php?username=u7892&msg_token=1ST5G8&sender_id=BISHOM&message=DEAR+CUSTOMER%0D%0AYOUR+ORDER+IS+OUT+OF+SHIPMENT&mobile='.$phone;

$this->call_url_for_sms($callURL);
}		
		
        return $this;
    }
	
	public function call_url_for_sms($url)
	{
			$ch = curl_init(); 
			curl_setopt($ch, CURLOPT_URL, $url); 
			curl_setopt($ch, CURLOPT_HEADER, 1); 
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
			$data = curl_exec($ch); 
			curl_close($ch); 
			
	
	
$readConnection = Mage::helper('function/more')->getDbReadObject(); // To read from the database
$writeConnection = Mage::helper('function/more')->getDbWriteObject(); // To write to the database
	
	

$query = "insert into twilio set sms_to='".$phone."',sms_body='test message and that is', date='".date('Y-m-d')."',timestamp='".date('Y-m-d h:i:s')."',api_response='".$data."'";
     
    /**
     * Execute the query
     */
    $writeConnection->query($query); 			
			
			
			return $data;
	}	
	
}