<?php
ini_set("memory_limit","1500M");
ini_set('max_execution_time', 18000);
error_reporting(E_ALL);
include_once('app/Mage.php');
Mage::app();
$read = Mage::getSingleton( 'core/resource' )->getConnection( 'core_read' );
$write = Mage::getSingleton( 'core/resource' )->getConnection( 'core_write' );

function get_val($val)
{
	$read = Mage::getSingleton( 'core/resource' )->getConnection( 'core_read' );
	$qry = $read->query("SELECT `value` FROM `eav_attribute_option_value` WHERE `option_id` = '".$val."'");
	$row = $qry->fetch();
	return $row['value'];
}

$orderIncrementId = 100000481;
$order = Mage::getModel('sales/order')->loadByIncrementId($orderIncrementId);
$order_id = $order->getEntity_id();
//$ordered_items = $order->getAllItems();

$payment_method = 'Prepaid';
  $orderItem = $read->query("SELECT `item_id` FROM `sales_flat_order_item` WHERE `order_id` = '".$order_id."' AND `product_type` = 'configurable'");
  $allOrderItem = $orderItem->fetch();
  $item_id = $allOrderItem['item_id'];
  $total_style_code = array();
  if($item_id != '')
  {
    // Shipping Address Data
    $shipping_address = $order->getShippingAddress();
    $shipping_data = $shipping_address->getData();
    $spostcode = $shipping_data['postcode'];
    $saddress1 = $shipping_data['street'];
    $scity = $shipping_data['city'];
    $sstate = $shipping_data['region'];
    $stelephone = $shipping_data['telephone'];
    $scountry_id = $shipping_data['country_id'];
    $countryModel = Mage::getModel('directory/country')->loadByCode($scountry_id);
    $scountryName = $countryModel->getName();
    
    // Billing Address Data
    $billing_address = $order->getBillingAddress();
    $billing_data = $billing_address->getData();
    $bpostcode = $billing_data['postcode'];
    $bsaddress1 = $billing_data['street'];
    $bcity = $billing_data['city'];
    $bstate = $shipping_data['region'];
    $btelephone = $billing_data['telephone'];
    $bcountry_id = $billing_data['country_id'];
    $countryModel = Mage::getModel('directory/country')->loadByCode($bcountry_id);
    $bcountryName = $countryModel->getName();
     
    if($order->canInvoice() and $order->getIncrementId())
    {
      $items = array();
      foreach ($order->getAllItems() as $item1) 
      {
      $items[$item1->getId()] = $item1->getQtyOrdered();
      }
      //public function create($orderIncrementId, $itemsQty, $comment = null, $email = false, $includeComment = false)
      $invoiceId = Mage::getModel('sales/order_invoice_api')->create($order->getIncrementId(),$items,null,false,true);
      //$invoice = Mage::getModel('sales/order_invoice_api')->capture($invoiceId);
    }
    
    if ($order->hasInvoices()) {
      foreach ($order->getInvoiceCollection() as $inv) {
      $invIncrementIDs = $inv->getIncrementId();
      $inv_total = round($inv->getGrandTotal());
      } 
    }
    
    $order_date = $order->getCreatedAt();
    $order_qty = $order->getTotalQtyOrdered();
    $subtotal = $order->getSubtotal();
    $grandtotal = $order->getGrandTotal();    
    $customer_id = $order->getCustomerId();
    $base_currency_code = $order->getBaseCurrencyCode();
    $bill_id = $order->getBillingAddressId();
    $ship_id = $order->getShippingAddressId();
    $email = $order->getCustomerEmail();
    $title = $order->getCustomerPrefix();
    $fname = $order->getCustomerFirstname();
    $lname = $order->getCustomerLastname();
    $shipping_amount = $order->getShippingAmount();
    $discount = $order->getDiscountAmount();
    $coupon = $order->getCouponCode();
    $ship_method = $order->getShippingMethod();
    
    $xml = new SimpleXMLElement("<?xml version=\"1.0\" encoding=\"utf-8\"?><order></order>");
    $xml->addChild('order_id', $order_id);
    $xml->addChild('order_date', $order_date);
    $customer_details = $xml->addChild('customer_details');
    $customer_details->addChild('title', $title);
    $customer_details->addChild('first_name', $fname);
    $customer_details->addChild('last_name', $lname);
    $customer_details->addChild('email', $email);
    $shipping_address = $xml->addChild('shipping_address');
    $shipping_address->addChild('title', $title);
    $shipping_address->addChild('first_name', $fname);
    $shipping_address->addChild('last_name', $fname);
    $shipping_address->addChild('address_line1', $saddress1);
    $shipping_address->addChild('address_line2', $saddress1);
    $shipping_address->addChild('country', $scountryName);
    $shipping_address->addChild('city', $scity);
    $shipping_address->addChild('state', $sstate);
    $shipping_address->addChild('pincode', $spostcode);
    $shipping_address->addChild('mobile', $stelephone);
    $xml->addChild('self_shipping', 'no');
    $billing_address = $xml->addChild('billing_address');
    $billing_address->addChild('title', $title);
    $billing_address->addChild('first_name', $fname);
    $billing_address->addChild('last_name', $fname);
    $billing_address->addChild('address_line1', $saddress1);
    $billing_address->addChild('address_line2', $saddress1);
    $billing_address->addChild('country', $bcountryName);
    $billing_address->addChild('city', $scity);
    $billing_address->addChild('state', $bstate);
    $billing_address->addChild('pincode', $bpostcode);
    $billing_address->addChild('mobile', $stelephone);
    $invoice = $xml->addChild('invoice');
    $invoice->addChild('invoice_number', $invIncrementIDs);
    $invoice->addChild('invoice_value', $inv_total);
    $invoice->addChild('tax_percentage', 0);
    $invoice->addChild('tax_value', 0);  
    $invoice->addChild('payment_mode', $payment_method);
    $invoice->addChild('special_instruction', 'Test');
    $invoice->addChild('discount', round($discount));
    $invoice->addChild('shipping_charges', round($shipping_amount));
    $invoice->addChild('order_comment', 'Test');
    $invoice->addChild('voucher_text', 'Test');
    $invoice->addChild('voucher_value', '123');
    $invoice->addChild('cod_convenience_fee', '50');
    $invoice->addChild('currency', $base_currency_code);
    $courier = $xml->addChild('courier');
    $courier->addChild('courier_name', 'Delhivery COD');
    $courier->addChild('docket_number', '11');
    $products = $xml->addChild('products');
    
    $orderItem = $read->query("SELECT `item_id`, `product_id` FROM `sales_flat_order_item` WHERE `order_id` = '".$order_id."' AND `product_type` = 'configurable'");
    $allOrderItem = $orderItem->fetchAll();
    for($i=0;$i<count($allOrderItem);$i++)
    { 
      //print_r($allOrderItem);
      $configProductid = $allOrderItem[$i]['product_id'];
      $configProduct = Mage::getModel('catalog/product')->load($configProductid);  
      $ids = $configProduct->getCategoryIds();
      $categoryId = (isset($ids[0]) ? $ids[0] : $ids[1]);
      $style_code = $configProduct['style_code'];

      $getSimple = $read->query("SELECT * FROM `sales_flat_order_item` WHERE `order_id` = '".$order_id."' AND `parent_item_id` = '".$allOrderItem[$i]['item_id']."'");
      $getSimpleRes = $getSimple->fetch();  
          
      $_product = Mage::getModel('catalog/product')->load($getSimpleRes['product_id']); 
          
      $color_qry = $read->query("SELECT `value` FROM `eav_attribute_option_value` WHERE `option_id` = '".$_product['color']."'");
      $color_row = $color_qry->fetch();
        
      $size_qry = $read->query("SELECT `value` FROM `eav_attribute_option_value` WHERE `option_id` = '".$_product['size']."'");
      $size_row = $size_qry->fetch(); 
      
      $color = $color_row['value'];
      $size = $size_row['value'];
      $price = $_product['price'];
      $name = $_product['name'];  
              
      $product_detail = $products->addChild('product_detail');
      $product_detail->addChild('product_name', $name);
      $product_detail->addChild('product_code', $style_code);
      $product_detail->addChild('product_color', $color);
      $product_detail->addChild('product_design_id', $style_code);
      $product_detail->addChild('tax', '0');
      $product_size = $product_detail->addChild('product_size');
      $product_size->addChild('size_name', $size);
      $product_size->addChild('size_quantity', round($order_qty));
      $product_size->addChild('unit_price', round($price));  

      $total_style_code[] = array($style_code, $_product['color'], $color, $_product['size'], $size, $categoryId);   
    }
//$orderItem = $read->query("SELECT `item_id`, `product_id` FROM `sales_flat_order_item` WHERE `order_id` = '".$order_id."' AND `product_type` = 'configurable'");
        $allOrderItem = $orderItem->fetchAll();
        for($i=0;$i<count($allOrderItem);$i++)
        {                           
          $configProductid = $allOrderItem[$i]['product_id'];
          $configProduct = Mage::getModel('catalog/product')->load($configProductid);  
          $ids = $configProduct->getCategoryIds();
          $categoryId = (isset($ids[0]) ? $ids[0] : $ids[1]);
          $style_code = $configProduct['style_code'];

          $getSimple = $read->query("SELECT * FROM `sales_flat_order_item` WHERE `order_id` = '".$order_id."' AND `parent_item_id` = '".$allOrderItem[$i]['item_id']."'");
          $getSimpleRes = $getSimple->fetch();  
              
          $_product = Mage::getModel('catalog/product')->load($getSimpleRes['product_id']); 
              
          $color_qry = $read->query("SELECT `value` FROM `eav_attribute_option_value` WHERE `option_id` = '".$_product['color']."'");
          $color_row = $color_qry->fetch();
            
          $size_qry = $read->query("SELECT `value` FROM `eav_attribute_option_value` WHERE `option_id` = '".$_product['size']."'");
          $size_row = $size_qry->fetch(); 
          
          $color = $color_row['value'];
          $size = $size_row['value'];
          $price = $_product['price'];
          $name = $_product['name']; 
          $html = '<tr><td align="center"><div style="font-size:16px">1</div></td>
          <td ><div style="font-size:16px">'.$name.'</div></td>
          <td align="center" ><div style="font-size:14px">'.round($price).'</div></td>
          <td align="center" ><div style="font-size:14px">'.round($price).'</div></td>
          <td align="center" ><div style="font-size:14px">'.round($order_qty).'</div></td>
          <td align="right" ><div style="font-size:14px">'.$subtotal.'</div></td></tr>';
        }       

    // code for invoice pdf
    require('fpdf/WriteHTML.php');

	$pdf=new PDF_HTML();

	$pdf->AliasNbPages();
	$pdf->SetAutoPageBreak(true, 15);

	$pdf->AddPage();

	$pdf->SetFont('Arial','B',7); 

	$html .= '<!doctype html>
      <html>
      <head>
      <meta charset="utf-8">
      <title>:: Retail Invoice dfdfd::</title>      
      </head>
      <body style="padding:0; margin:0" >
      <div style="background:#fff" class="main-box">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tbody>
            <tr>
              <td align="center"><div style="width:100%; max-width:840px">
                 <table width="100%" border="0" cellspacing="0" cellpadding="5">
                  <tbody>
                    <tr>
                      <td rowspan="2" width="150" valign="top"><img src="http://www.bhishoom.com/skin/frontend/rwd/default/images/logoFooterInvoice.jpg" style="display:block; width:100px" alt="bhishoom"/></td>
                      <td rowspan="2" width="20" valign="top">&nbsp;</td>
                      <td rowspan="2" valign="top"><div style=" font-size:12px"><strong>Dreambox Retail Pvt. Ltd. sdsds</strong><br>
                          37A, Bhagat Vatika, Raj Bhavan Road, <br>
                          Civil Lines, Jaipur - 302006</div><br>
                        <div style="font-size:12px"><strong>Call Us:</strong> +91 96100 55577</div><br>
                        <div style="font-size:12px"><strong>Email:</strong> support@bhishoom.com</div><br>
                        <div style="font-size:12px"><strong>Website:</strong> www.bhishoom.com</div></td>
                      <td rowspan="2" width="20" valign="top">&nbsp;</td>
                      <td valign="top" ><img src="http://www.bhishoom.com/skin/frontend/rwd/default/images/barcode.jpg" style=" border:1px solid #353535; padding:10px 10px 10px 10px; text-align:center" height="60" alt="Bhishoom" /></td>
                    </tr>
                    <tr>
                      <td valign="top"><span style="font-size:15px;  font-weight:bold; line-height:1; letter-spacing:15px; padding-left:15px">FEDEX</span><br>
                ( Collect Cash on Delivery )</td>
                    </tr>
                  </tbody>
                </table>';

    $pdf->WriteHTML("$html");

    $pdf->SetFont('Arial','B',7); 
	$htmlTable='<TABLE BORDER="0">
	<TR>
	<TD>Name:</TD>
	<TD>'.$fname.'</TD>
	</TR>
	<TR>
	<TD>[Company Name]:</TD>
	<TD>'.$_POST['email'].'</TD>
	</TR>
	<TR>
	<TD>Street Address:</TD>
	<TD>'.$saddress1.'</TD>
	</TR>
	<TR>
	<TD>City, ST:</TD>
	<TD>'.$scity.', '.$sstate.'</TD>
	</TR>
	<TR>
	<TD>ZIP:</TD>
	<TD>'.$spostcode.'</TD>
	</TR>
	<TR>
	<TD>Phone:</TD>
	<TD>'.$stelephone.'</TD>
	</TR>
	</TABLE>';
	$pdf->WriteHTML2("<br><br><br>$htmlTable");

	
	$pdf->Output(); 

    /*$estore = $xml->addChild('estore');
    $estore->addChild('estore_invoice_available', 'yes');
    $estore->addChild('estore_invoice_url', $invoice_url);
    $estore->addChild('estore_clearance_available', 'yes');
    $estore->addChild('estore_clearance_url', $invoice_url);
    $estore->addChild('estore_manifest_required', 'yes');*/

echo '<pre>';
//print_r($xml);
$xml->asXML('xml-file.xml');
$xml_str = $xml->asXML();

/*$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => 'https://www.99-dot-com-website-testing.com/api/merchant/create_order.php',
	CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_USERAGENT => 'Codular Sample cURL Request',
    CURLOPT_POST => 1,
    CURLOPT_POSTFIELDS => array(
	"api_key" => "234yNCmXtkVcFY9P",
	"order_data" => $xml_str
    )
));
$resp = curl_exec($curl);
curl_close($curl);
print_r($resp);	*/
}
?>