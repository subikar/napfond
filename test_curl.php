<?php 

$xmlData = '<?xml version="1.0" encoding="utf-8"?>
<order><order_id>32459bh</order_id><order_date>2015-02-20 10:52:19</order_date><customer_details><title>Mr.</title><first_name>hunny</first_name><last_name>bohara</last_name><email>hunny.bohara@gravitainfotech.com</email></customer_details><shipping_address><title>Mr.</title><first_name>hunny</first_name><last_name>hunny</last_name><address_line1>Raja Park</address_line1><address_line2>Raja Park</address_line2><country>India</country><city>jaipur</city><state>Rajasthan</state><pincode>302020</pincode><mobile>7737442845</mobile></shipping_address><self_shipping>no</self_shipping><billing_address><title>Mr.</title><first_name>hunny</first_name><last_name>hunny</last_name><address_line1>Raja Park</address_line1><address_line2>Raja Park</address_line2><country>India</country><city>jaipur</city><state>Rajasthan</state><pincode>302020</pincode><mobile>7737442845</mobile></billing_address><invoice><invoice_number>100000009</invoice_number><invoice_value>650</invoice_value><payment_mode>Cash On Delivery</payment_mode><special_instruction>Test</special_instruction><discount>0</discount><shipping_charges>50</shipping_charges><order_comment>Test</order_comment><voucher_text>Test</voucher_text><voucher_value>123</voucher_value><cod_convenience_fee>50</cod_convenience_fee><currency>INR</currency></invoice><courier><courier_name>Delhivery COD</courier_name><docket_number></docket_number></courier><products><product_detail><product_name>Killer APP</product_name><product_color>Navy Blue</product_color><product_design_id>11014_M</product_design_id><product_size><size_name>S</size_name><size_quantity>1</size_quantity><unit_price>600</unit_price></product_size></product_detail></products></order>
';

function httpPost($url,$params)
{
  $postData = '';
   //create name value pairs seperated by &
   foreach($params as $k => $v) 
   { 
      $postData .= $k . '='.$v.'&'; 
   }
   rtrim($postData, '&');
 
    $ch = curl_init();  
 
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
    curl_setopt($ch,CURLOPT_HEADER, false); 
    curl_setopt($ch, CURLOPT_POST, count($postData));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);    
 
    $output=curl_exec($ch);
 
    curl_close($ch);
    return $output;
 
}


$params = array(
   "api_key" => "234yNCmXtkVcFY9P",
   "order_data" => $xmlData
);
 
echo httpPost("https://www.99-dot-com-website-testing.com/api/merchant/create_order.php",$params);

?>