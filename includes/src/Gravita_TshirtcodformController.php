<?php
class Gravita_Tshirtcod_Adminhtml_TshirtcodformController extends Mage_Adminhtml_Controller_Action
{
	public function indexAction()
    {
        $this->loadLayout()->renderLayout();	
    } 
	
	public function bpostAction()
	{	
		$read = Mage::getSingleton( 'core/resource' )->getConnection( 'core_read' );
		$write = Mage::getSingleton( 'core/resource' )->getConnection( 'core_write' );
		$params = $this->getRequest()->getParams();
		$sendOrder = $params['sendorder'];
		if($sendOrder != '')
		{
			$orderId = $params['orderid'];	
			$order = Mage::getModel('sales/order')->loadByIncrementId($orderId);
			$order_id = $order->getEntity_id();
			$payment_method = $order->getPayment()->getMethodInstance()->getTitle();
			if($payment_method == 'Cash on Delivery')
			{				
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
			    
			    if ($order->hasInvoices()) 
			    {
				    foreach ($order->getInvoiceCollection() as $inv) 
				    {
					    $invIncrementIDs = $inv->getIncrementId();
					    $inv_total = round($inv->getGrandTotal());
				    } 
			    }
			    
			    $order_date = $order->getCreatedAt();
			    $order_qty = $order->getTotalQtyOrdered();
			    $subtotal = $order->getSubtotal();
			    //$grandtotal = $order->getGrandTotal();    
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
			    
			    $orderItem = $read->query("SELECT `item_id`, `product_id`, `price`, `row_total` FROM `sales_flat_order_item` WHERE `order_id` = '".$order_id."' AND `product_type` = 'configurable'");
			    $allOrderItem = $orderItem->fetchAll();
			    for($i=0;$i<count($allOrderItem);$i++)
			    { 
			      //print_r($allOrderItem);
			      $configProductid = $allOrderItem[$i]['product_id'];
			      $configProduct = Mage::getModel('catalog/product')->load($configProductid);  
			      $ids = $configProduct->getCategoryIds();
			      $categoryId = (isset($ids[0]) ? $ids[0] : $ids[1]);
			      $style_code = $configProduct['style_code'];
			      $design_code = $configProduct['design_code'];

			      $getSimple = $read->query("SELECT * FROM `sales_flat_order_item` WHERE `order_id` = '".$order_id."' AND `parent_item_id` = '".$allOrderItem[$i]['item_id']."'");
			      $getSimpleRes = $getSimple->fetch();  
			      $pr_qty = $getSimpleRes['qty_ordered'];
                  $pr_price = $allOrderItem[$i]['price']; 
                  $pr_row_total = $allOrderItem[$i]['row_total'];  
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
			      $product_detail->addChild('product_design_id', $design_code);			      
			      $product_detail->addChild('tax', '0');
			      $product_size = $product_detail->addChild('product_size');
			      $product_size->addChild('size_name', $size);
			      $product_size->addChild('size_quantity', round($pr_qty));
			      $product_size->addChild('unit_price', round($pr_price));  

			      $total_style_code[] = array($style_code, $_product['color'], $color, $_product['size'], $size, $categoryId);   
			    }
			    
			    // code for invoice pdf		    

			    require('html2pdf_sample/html2fpdf.php');
    $pdf = new HTML2FPDF();
    $pdf->AddPage();
    /*$fp = fopen("order_shipped.html","r");
    $strContent = fread($fp, filesize("order_shipped.html"));
    fclose($fp);*/
    $inv_no = rand(0, 100);
    $html = '';
    $html .= '<!doctype html>
      <html>
      <head>
      <meta charset="utf-8">
      <title>:: Retail Invoice dfdfd::</title>
      <style type="text/css">
      body { background: #fff; font-family: Arial, Gotham, Helvetica, sans-serif; font-size:13px }
      table { border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; /*font-size: 0*/ }
      table td { border-collapse: collapse; font-size: 0 }
      </style>
      <style type="text/css">
      @media only screen and (max-width: 599px) {
      .social-icon { top: 100px!important }
      }
      </style>
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
                </table>
                  
                <table width="100%" border="0" cellspacing="0" cellpadding="5">
                    <tbody>
                      <tr>
                       <td colspan="4" align="center" style="font-size:22px; font-weight:bold; text-align:center; height:50px; text-transform:uppercase">Retails Invoice</td>
                      <tr>
                        <td bgcolor="#CCCCCC">SHIP T0 </td>
                        <td>&nbsp;</td>
                        <td colspan="2" bgcolor="#CCCCCC">SHIP T0</td>
                      </tr>
                      <tr>
                        <td><strong>Name - </strong>'.$fname.'</td>
                        <td>&nbsp;</td>
                        <td bgcolor="#CCCCCC" id="iin"> Invoice No. </td>
                        <td>#'.$invIncrementIDs.'</td>
                      </tr>
                      <tr>
                        <td>[Company Name]</td>
                        <td>&nbsp;</td>
                        <td bgcolor="#CCCCCC">Order No.</td>
                        <td>#'.$order_id.'</td>
                      </tr>
                      <tr>
                        <td><strong>Street Address - </strong>'.$saddress1.'</td>
                        <td>&nbsp;</td>
                        <td bgcolor="#CCCCCC">Order Date</td>
                        <td>'.$order_date.'</td>
                      </tr>
                      <tr>
                        <td><strong>City, ST -</strong> '.$scity.', '.$sstate.'</td>
                        <td>&nbsp;</td>
                        <td bgcolor="#CCCCCC">Payment </td>
                        <td>COD</td>
                      </tr>
                      <tr>
                        <td>ZIP - '.$spostcode.'</td>
                        <td>&nbsp;</td>
                        <td bgcolor="#CCCCCC">TIN No. </td>
                        <td>987654123</td>
                      </tr>
                      <tr>
                        <td>Phone - '.$stelephone.'</td>
                        <td>&nbsp;</td>
                        <td bgcolor="#CCCCCC">&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td>Email Address - '.$email.'</td>
                        <td>&nbsp;</td>
                        <td bgcolor="#CCCCCC">&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                    </tbody>
                  </table>
                 <div style=" height:1px; margin:5px 0 15px; width:100%"></div>
 
                  <div style=" height:1px; margin:25px 0 15px; width:100%"></div>
                  <table width="100%" border="1" cellspacing="15" cellpadding="10">
                    <tbody>
                      <tr>
                        <td align="center" bgcolor="#D4D4D4"><div style="font-size:16px">S. NO.</div></td>
                        <td align="center" bgcolor="#D4D4D4"><div style="font-size:16px">ITEM DESCRIPTION</div></td>
                        <td align="center" bgcolor="#D4D4D4"><div style="font-size:16px">PRICE</div></td>
                        <td align="center" bgcolor="#D4D4D4"><div style="font-size:16px">TAX</div></td>
                        <td align="center" bgcolor="#D4D4D4"><div style="font-size:16px">QTY</div></td>
                        <td align="center" bgcolor="#D4D4D4"><div style="font-size:16px">SUB TOTAL</div></td>
                      </tr>
                      '; 


                      // for shipping charge
                      $getTot = $read->query("SELECT * FROM `sales_flat_order_item` WHERE `order_id` = '".$order_id."' AND `product_type` = 'simple'");
                      $getTotProd = $getTot->fetchAll();
                      $t=0;$t_qty=0;
                      foreach ($getTotProd as $value) 
                      {//echo $value['parent_item_id'];
                          if($value['parent_item_id'] == NULL)
                          {
                            ++$t;
                            $t_qty += $value['qty_ordered'];
                          }
                          else
                          {
                            break;
                          }
                      }  

                      $pr_discount_amount = 0;$pr_ship_qty = 0;$j=1;$pr_finel_total=0;$pr_discount_percent=0;
                      $orderItem = $read->query("SELECT `item_id`, `product_id`, `price`, `row_total`, `discount_amount`, `discount_percent` FROM `sales_flat_order_item` WHERE `order_id` = '".$order_id."' AND `product_type` = 'configurable'");
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
                            $pr_qty = $getSimpleRes['qty_ordered'];
                            $pr_ship_qty += $getSimpleRes['qty_ordered'];
                            $pr_price = $allOrderItem[$i]['price']; 
                            $pr_row_total = $allOrderItem[$i]['row_total']; 
                            $pr_finel_total += $allOrderItem[$i]['row_total']; 
                            $pr_discount_amount += $allOrderItem[$i]['discount_amount']; 
                            $pr_discount_percent = $allOrderItem[$i]['discount_percent'];
                          $_product = Mage::getModel('catalog/product')->load($getSimpleRes['product_id']); 
                              
                          $color_qry = $read->query("SELECT `value` FROM `eav_attribute_option_value` WHERE `option_id` = '".$_product['color']."'");
                          $color_row = $color_qry->fetch();
                            
                          $size_qry = $read->query("SELECT `value` FROM `eav_attribute_option_value` WHERE `option_id` = '".$_product['size']."'");
                          $size_row = $size_qry->fetch(); 
                          
                          $color = $color_row['value'];
                          $size = $size_row['value'];
                          $price = $_product['price'];
                          $name = $_product['name']; 
                          $html.= '<tr><td align="center"><div style="font-size:16px">'.$j.'</div></td>
                          <td ><div style="font-size:16px">'.$name.' Cotton Tshirt<br>Color - '.$color.'<br>Size - '.$size.'</div></td>
                          <td align="center" ><div style="font-size:16px">INR '.round($pr_price).'</div></td>
                          <td align="center" ><div style="font-size:16px">NILL</div></td>
                          <td align="center" ><div style="font-size:16px">'.round($pr_qty).'</div></td>
                          <td align="right" ><div style="font-size:16px">INR '.round($pr_row_total).'</div></td></tr>';
                          $j++;
                        } 
                        if($shipping_amount > 0)                       
                        {
                          if($t > 0)
                          {
                            $shipping_amount = $pr_ship_qty*20;
                          }
                          else
                          {
                            $shipping_amount = ($pr_ship_qty*20)+19;
                          }
                        }
						$cod_charge = 40;
                      $html.='
                      <tr>
                        <td colspan="5" align="center" ><div style="font-size:16px">Shipping Charges</div></td>
                        <td align="center" ></td>
                        <td align="right" ><div style="font-size:16px">INR '.$shipping_amount.'</div></td>
                      </tr>
                      <tr>
                        <td colspan="5" align="center" ><div style="font-size:16px">Cash on Delivery Charge</div></td>
                        <td align="center" ></td>
                        <td align="right" ><div style="font-size:16px">INR '.$cod_charge.'</div></td>
                      </tr>';
                      	if($pr_discount_percent != 5 && $pr_discount_amount > 0)
                      	{
                      		$html.='<tr>
		                        <td colspan="5" align="center" ><div style="font-size:16px">Discount Coupon</div></td>
		                        <td align="center" ></td>
		                        <td align="right" ><div style="font-size:16px">-INR '.$pr_discount_amount.'</div></td>
		                      </tr>';
                      	}
                       $grandtotal = ($pr_finel_total+$shipping_amount+$cod_charge)-$pr_discount_amount;                        
                      $html.='<tr>
                        <td colspan="5" align="center" ><div style="font-size:16px"><em>Thank you for purchasing item on Bhishoom</em></div></td>
                        <td align="center" ><div style="font-size:16px"><strong>TOTAL</strong></div></td>
                        <td align="right" ><div style="font-size:16px"><strong>INR '.$grandtotal.'</strong></div></td>
                      </tr>
                    </tbody>
                  </table>
                  <div style=" height:1px; margin:25px 0 15px; width:100%"></div>
                  <div style="font-size:16px; text-align:center"><strong>AMOUNT TO BE COLLECTED: INR '.$grandtotal.'</strong></div>
                  <div style=" height:1px; margin:25px 0 15px; width:100%"></div>
                  <table width="100%" border="0" cellspacing="0" cellpadding="0" >
                    <tbody>
                      <tr>
                        <td height="5px"></td>
                      </tr>
                      <tr>
                        <td><div style=" font-size:12px; text-align:center; padding:5px; color:#666"> This is a computer generated invoice and does not required signature.<br>
                            In case of any feedback or query mail us at support@bhishoom.com or call +91 96100 55577.</div></td>
                      </tr>
                      <tr>
                        <td height="5px"></td>
                      </tr>
                    </tbody>
                  </table>
                </div></td>
            </tr>
          </tbody>
        </table>
      </div>
      </body>
      </html>
      ';
    $pdf->WriteHTML($html);
			    $pdf->Output("invoice/file001.pdf");

			    $invoice_url = 'http://www.bhishoom.com/invoice/file001.pdf';

			    $estore = $xml->addChild('estore');
			    $estore->addChild('estore_invoice_available', 'yes');
			    $estore->addChild('estore_invoice_url', $invoice_url);
			    $estore->addChild('estore_clearance_available', 'yes');
			    $estore->addChild('estore_clearance_url', $invoice_url);
			    $estore->addChild('estore_manifest_required', 'yes');
			    
			    $xml->asXML('xml-file1.xml');
			    $xml_str = $xml->asXML();
			    
			    $curl = curl_init();
			    curl_setopt_array($curl, array(
			      CURLOPT_RETURNTRANSFER => 1,
			      CURLOPT_URL => 'http://www.99prints.in/api/create_order.php',
			      //CURLOPT_SSL_VERIFYPEER => false,
			      CURLOPT_USERAGENT => 'Codular Sample cURL Request',
			      CURLOPT_POST => 1,
			      CURLOPT_POSTFIELDS => array(
			      "api_key" => "bPx2vVzhHY37FyXJ",
			      "order_data" => $xml_str
			      )
			    ));
			    $resp = curl_exec($curl);
			    curl_close($curl);
			    //print_r($resp);

			    $new_style_code = array_unique($total_style_code);
			    $scurl = curl_init();
			    curl_setopt_array($scurl, array(
			        CURLOPT_RETURNTRANSFER => 1,
			        CURLOPT_URL => 'http://23.251.150.147/api_test/merchant/stock.php',
			        CURLOPT_USERAGENT => 'Codular Sample cURL Request',
			        CURLOPT_POST => 1,
			        CURLOPT_POSTFIELDS => array(
			            'api_key' => 'CM6NJKcmB2d4RTG8'
			        )
			    ));
			    $sresp = curl_exec($scurl);
			    curl_close($scurl);
			    $sxml = simplexml_load_string($sresp);

			    for($i=0;$i<count($total_style_code);$i++)
			    {
			      $code = $total_style_code[$i][0];
			      $color_id = $total_style_code[$i][1];
			      $color_name = $total_style_code[$i][2];
			      $size_id = $total_style_code[$i][3];
			      $size_name = $total_style_code[$i][4];
			      $cat_id = $total_style_code[$i][5];
			      $this->update_stock($sxml, $code, $color_id, $color_name, $size_id, $size_name, $cat_id);
			    }			    

			    $style_id_array = array();
			    $inventory_array = array();

			  	foreach($sxml as $data)
			  	{
			  		$style_id = $data->style_id;
			  		if(!in_array($style_id,$style_id_array))
			  		 $style_id_array[] = $style_id;
			  		else
			  	     continue; 
		  			foreach($data->style_color as $color)
		  			{
		  				$color_name = addslashes($color->style_color_name);
		  				foreach($color->style_size as $size)
		  				{
		  					$size_name = addslashes($size->size_name);
		  					$stock = $size->size_stock;
		  					$inventory_array["$style_id"]["$color_name"]["$size_name"] = array("$stock");				
		  				}			
		  			}		
			  	}  

			  	foreach($style_id_array as $style_id_array_val)
			    {
			  	   	$write->query("update a_create_tshirt_inventory set inVentory = '".base64_encode(serialize($inventory_array["$style_id_array_val"]))."' where designId = '".$style_id_array_val."'");
			  	}   
			  }
			} 				
		}
		if($resp == '')
		{
			echo "Order Successfully Sent";exit();
		}
		else
		{
			echo $resp;exit();
		}		
	}

	public function update_stock($sxml, $code, $color_id, $color_name, $size_id, $size_name, $cat_id)
	{		
		$write = Mage::getSingleton( 'core/resource' )->getConnection('core_write');
		foreach ($sxml as $data) 
		{
			foreach ($data->style_id as $datastyle)
			{
				if($datastyle == $code)
				{
					foreach ($data->style_color as $datacolor) 
					{
						if($datacolor->style_color_name == $color_name)
						{
							foreach ($datacolor->style_size as $datasize) 
							{
								if($datasize->size_name == $size_name)
								{
									$main_stock = $datasize->size_stock;
									$prod_collection = Mage::getModel('catalog/category')->load($cat_id);
									$collection  = $prod_collection->getProductCollection()->addAttributeToSelect('*');
									foreach($collection as $product) 
									{
										if($product->isConfigurable())
										{
											$prod_id = $product->getId();
											$conf = Mage::getModel('catalog/product_type_configurable')->setProduct($product);
											$simple_collection = $conf->getUsedProductCollection()->addAttributeToSelect('*');
											$simple_collection->addAttributeToFilter('color', $color_id)->addAttributeToFilter('Size', $size_id);
											foreach($simple_collection as $simple_product)
											{
												$id = $simple_product->getId();
												$write->query("Update `cataloginventory_stock_item` SET `qty` = ".$main_stock." WHERE `product_id` = ".$id);
												$write->query("Update `cataloginventory_stock_status` SET `qty` = ".$main_stock." WHERE `product_id` = ".$id);
												$write->query("Update `cataloginventory_stock_status_idx` SET `qty` = ".$main_stock." WHERE `product_id` = ".$id);
											}
										}			
									}
								}
							}
						}
					}
				}
			}
		}
	}	
}