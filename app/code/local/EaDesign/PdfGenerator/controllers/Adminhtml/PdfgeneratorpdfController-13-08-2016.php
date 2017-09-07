<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PdfgeneratorPdfConstroller
 *
 * @author Ea Design
 */
class EaDesign_PdfGenerator_Adminhtml_PdfgeneratorpdfController extends Mage_Adminhtml_Controller_Action
{

    public function invoicepdfgenratorAction()    {$read = Mage::getSingleton( 'core/resource' )->getConnection( 'core_read' );$write = Mage::getSingleton( 'core/resource' )->getConnection( 'core_write' );  $siteBaseUrl = Mage::helper('function/more')->getBhishoomSiteBaseUrl();  	$order = Mage::getModel('sales/order')->load($this->getRequest()->getParam('orderid'));

	$codFeeApplicable = false;

	$order_id = $this->getRequest()->getParam('orderid');     $shipping_address = $order->getShippingAddress(); if(is_object($shipping_address)){    $shipping_data = $shipping_address->getData();     $spostcode = $shipping_data['postcode'];     $saddress1 = $shipping_data['street'];     $fname = $shipping_data['firstname'];     $lname = $shipping_data['lastname'];     $scity = $shipping_data['city'];     $sstate = $shipping_data['region'];     $stelephone = $shipping_data['telephone'];     $scountry_id = $shipping_data['country_id'];     $countryModel = Mage::getModel('directory/country')->loadByCode($scountry_id);    $scountryName = $countryModel->getName(); }       	    $billing_address = $order->getBillingAddress();    $billing_data = $billing_address->getData();    $bpostcode = $billing_data['postcode'];    $bsaddress1 = $billing_data['street'];    $bcity = $billing_data['city'];    $bstate = $shipping_data['region'];    $btelephone = $billing_data['telephone'];    $bcountry_id = $billing_data['country_id'];    $countryModel = Mage::getModel('directory/country')->loadByCode($bcountry_id);    $bcountryName = $countryModel->getName();	$email = $order->getCustomerEmail();			$orderItemsId =	$this->getRequest()->getParam('items');	$shipping_amount = $order->getShippingAmount();		$payment_method = $order->getPayment()->getMethodInstance()->getTitle();	$payment_method_code = $order->getPayment()->getMethodInstance()->getCode();		
	
	
	if($payment_method_code == 'payucheckout_shared'){	 $img_payment_type_logo = 'http://bhishoom.com/skin/frontend/rwd/default/images/prepaid.jpg';	 	}	
	
	
	else if($payment_method_code == 'phoenix_cashondelivery'){	 $cod_charge = Mage::getStoreConfig('payment/phoenix_cashondelivery/inlandcosts');	 
	
	
	$img_payment_type_logo = 'http://bhishoom.com/skin/frontend/rwd/default/images/cod.jpg';	
	
	$img_payment_type_logo = 'COD';
	
	}    
	
	
	
	
	if ($order->hasInvoices()) {      foreach ($order->getInvoiceCollection() as $inv) {      $invIncrementIDs = $inv->getIncrementId();      $inv_total = round($inv->getGrandTotal());      }     }				   $date = date('Y'); 				$order_date = $order->getCreatedAt();				$orderdate =   $order->getCreatedAtStoreDate();				$orderdate =  explode(',',$orderdate);				$orderdate =  $orderdate[0].', '.$date;					$email = $order->getCustomerEmail();	    require(Mage::getBaseDir().'/html2pdf_sample/html2fpdf.php');			    $pdf = new HTML2FPDF();    $pdf->AddPage();    $inv_no = rand(0, 100);    $html = '';    
	
	
	$shtml = '';
		
		
		$pr_discount_amount = 0;$pr_ship_qty = 0;$j=1;$pr_finel_total=0;					  $pr_discount_amount_temp = 0;					  
	
	if($order->getFeeAmount() < 0)
		$onLinePaymentDiscountPer = 5;										  					  
	else
		$onLinePaymentDiscountPer = 0;		
	
	$orderItem = $read->query("SELECT * FROM `sales_flat_order_item` WHERE `order_id` = '".$order_id."' AND find_in_set(item_id,'".$orderItemsId."')>0");                        
	
	$shippingprice = 0;
	$cod_applicable = false;
	$allOrderItem = $orderItem->fetchAll();                        
	
	
	for($i=0;$i<count($allOrderItem);$i++) {    
    	$pr_qty = $allOrderItem[$i]['qty_ordered'];
		
		
		$chkParentItem = $read->query("SELECT * FROM `sales_flat_order_item` WHERE `parent_item_id` = '".$allOrderItem[$i]["item_id"]."'");
		$getParentItem = $chkParentItem->fetchAll(); 


		if(count($getParentItem) > 0){
			
		if($getParentItem[0]["cod_applicable"]=='y')
			$cod_applicable = true;
			$shippingprice = $shippingprice + $getParentItem[0]['item_shipping_price'];	
			
		}else{
			
		$shippingprice = $shippingprice + $allOrderItem[$i]['item_shipping_price'];	
	
		if($allOrderItem[$i]["cod_applicable"]=='y')
			$cod_applicable = true;
			
		}

		
		
	$pr_ship_qty += $allOrderItem[$i]['qty_ordered'];
	
	$product_options = unserialize($allOrderItem[$i]['product_options']);
	
	$pr_price = $allOrderItem[$i]['row_total'] + $allOrderItem[$i]['discount_amount'];
		
	$pr_discount_amount = $allOrderItem[$i]['discount_amount'];
	
	$pr_discount_amount_temp += $pr_discount_amount;
	
	$onlinePaymentDiscount = ceil((($pr_price * $onLinePaymentDiscountPer)/100)) * -1;
	
	$pr_price = $pr_price + $onlinePaymentDiscount;
	
	$pr_discount_amount_temp += ($onlinePaymentDiscount);
	
	$tax = ceil($pr_price*5/105);
	
	$pr_price = $pr_price - $tax;
	
	$pr_row_total = $allOrderItem[$i]['row_total'] + ($pr_discount_amount + $onlinePaymentDiscount);
	
	$pr_finel_total += $pr_row_total;									
		
	$_product = Mage::getModel('catalog/product')->load($getSimpleRes['product_id']);                                                         $color_qry = $read->query("SELECT `value` FROM `eav_attribute_option_value` WHERE `option_id` = '".$_product['color']."'");                          $color_row = $color_qry->fetch();                                                      $size_qry = $read->query("SELECT `value` FROM `eav_attribute_option_value` WHERE `option_id` = '".$_product['size']."'");                          $size_row = $size_qry->fetch();                                                     $color = $color_row['value'];                          $size = $size_row['value'];                          $price = $_product['price'];                          $name = $allOrderItem[$i]['name']; 						  						  $product_options = $product_options['options'];						  $sep = '';						  $prdOptionsStr = '';						  foreach($product_options as $product_options_val){								if(trim(strtolower($product_options_val['label'])) == 'product detail')									continue;							  $prdOptionsStr .= $sep.'<b>'.$product_options_val['label'].'</b>:&nbsp;'.$product_options_val['print_value'];							  $sep = '&nbsp;&nbsp;';
						  }						                            $shtml.= '						  <tr>						  <td align="center" width="5px">'.$j.'</td>                          <td colspan="5" align="left">'.$name.'<br>						  '.$prdOptionsStr.'</td>                          <td align="center" ><div style="font-size:14px">'.round($pr_qty).'</div></td>                          <td align="center" ><div style="font-size:14px">'.round($pr_price).'</div></td>						  <td align="center" ><div style="font-size:14px">'.$tax.'</div></td>                          <td  align="center" ><div style="font-size:14px">'.round($pr_row_total).'</div></td>						  </tr>';						                            $j++;                        }




						  /*if($shipping_amount > 0)                                               {                          if($t > 0)                          {                            $shipping_amount = $pr_ship_qty*20;                          }                          else                          {                            $shipping_amount = ($pr_ship_qty*20)+19;                          }                        }                        
						  */
						  
						  if($cod_applicable == false)
						   $cod_charge = 0;
					   
						  $shipping_amount = $shippingprice;
						  $grandtotal = ($pr_finel_total+$shipping_amount);												
						  
						  
						  
						  if($cod_charge > 0){							$grandtotal = ($grandtotal+$cod_charge);                      $shtml.='					     <tr>                        <td colspan="9" align="right" ><div style="font-size:14px">COD Handling Charges<br>												</div></td>';												$shtml.='<td align="center" ><div style="font-size:14px">'.$cod_charge.'</div></td>';																	   $shtml.='</tr>';																					
						}


						

						$shtml.='					     <tr>                        <td colspan="9" align="right" ><div style="font-size:14px">Shipping Charges<br>												</div></td>';						
						
						if($shipping_amount > 0)						{							$shtml.='<td align="center" ><div style="font-size:14px">'.$shipping_amount.'</div></td>';						}						else 						{							  $shtml.='<td align="center" ><div style="font-size:14px">Free</div></td>';													}

	
	
	
	
	
	
	$html .= '	<!doctype html>      <html>      <head>      <meta charset="utf-8">      <title>:: Retail Invoice dfdfd::</title>      <style type="text/css">      body { background: #fff; font-family: Arial, Gotham, Helvetica, sans-serif; font-size:13px }      table { border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; /*font-size: 0*/ }      table td { border-collapse: collapse; }      </style>      <style type="text/css">      @media only screen and (max-width: 599px) {      .social-icon { top: 100px!important }      }      </style>      </head>      <body style="padding:0; margin:0" >      <div style="background:#fff" class="main-box">        <table width="100%" border="0" cellspacing="0" cellpadding="0">          <tbody>            <tr>              <td align="center"><div style="width:100%; max-width:840px">               <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#fff">                    <tbody>                      <tr>                        <td colspan="3" height="5px"></td>                      </tr>                      <tr>                        <td width="150"><img src="http://www.bhishoom.com/skin/frontend/rwd/default/images/logoFooterInvoice.jpg" style="display:block; max-width:150px; width:100%; max-height:124px" alt="bhishoom"/></td>                        <td width="20px"></td>                        <td align="">						<div style=" font-size:14px">							Dreambox Retail Pvt. Ltd.<br>                            1st Floor, 37-A, Bhagat Vatika, Raj Bhawan Road,<br>                           Civil Lines, Jaipur, Rajasthan, India - 302006						</div>						<br>                          <div style="font-size:14px">Call Us: - +91 96100 55577</div><br>                          <div style="font-size:14px">Email: - support@bhishoom.com</div><br>                          <div style="font-size:14px">Website: - www.bhishoom.com</div></td>                        <td align="center" width="150" valign="middle" border="1">';	
	
	if($img_payment_type_logo == 'COD')
	$html .='
	<strong style="font-size:50px;">COD</strong><br><br>
	Amount Due<br>
	'.Mage::helper('core')->currency($grandtotal, true, false).'';
	else
	$html .= '<div style=""><img src="'.$img_payment_type_logo.'" /></div>';

	if(!is_object($shipping_address)){
		$heading = '';
		$s_email = '';
	}	
	else{
		$heading = 'SHIP To';
		$s_email = $email;
	}
	$html .= '</td></tr>
	<tr>                        <td colspan="3" height="10px"></td>                      </tr>                    </tbody>                  </table>				  				   <table width="100%" border="0" cellspacing="0" cellpadding="10">                    <tbody>					<div style=" height:1px; margin:5px 0 15px; width:100%"></div>                      <tr>                       <td colspan="4" align="center" style="font-size:22px; font-weight:bold; text-align:center; height:50px; text-transform:uppercase"><h2>Retail Invoice</h2></td><br><br>					   </tr>                      <tr>                        <td valign="top" colspan="4" align="left" ><strong >'.$heading.'</strong> </td>                                              </tr>                      <tr>                        <td valign="top" colspan="2">'.$fname.' '.$lname.'</td>                        <td valign="top"  id="iin">Invoice No.</td>                        <td valign="top" align="right">'.$invIncrementIDs.'</td>                      </tr>                                            <tr>                        <td colspan="2">'.$saddress1.','.$spostcode.'</td>							<td valign="top">Order No.</td>						  <td valign="top" align="right">'.$order->getIncrementId().'</td>                                             </tr>					  					  <tr>						<td valign="top" colspan="2">'.$scity.'</td>						<td valign="top">Order Date</td>                       <td valign="top" align="right">'.$orderdate.'</td>                      </tr>                      <tr>						<td valign="top" colspan="2">'.$stelephone.'</td>                        <td valign="top" >Payment</td>                        <td valign="top" align="right">'.$payment_method.'</td>                      </tr>                      <tr>                       <td valign="top" colspan="2">'.$s_email.'</td>                        <td valign="top">TIN No.</td>                        <td valign="top"  align="right">08304358064</td>                      </tr>                                          </tbody>                  </table>				  <div style=" height:1px; margin:5px 0 15px; width:100%"></div>                    <div style=" height:1px; margin:25px 0 15px; width:100%"></div>				  <br><br>                  <table width="100%" border="1" cellspacing="15" cellpadding="0">                    <tbody>                      <tr>                        <td align="center" width="5px">S.No.</td>                        <td  colspan="5" align="center" ><div style="font-size:14px">ITEM DESCRIPTION</div></td>                                                <td align="center" ><div style="font-size:14px">QTY</div></td>						<td align="center" ><div style="font-size:14px">PRICE</div></td>                        <td align="center" ><div style="font-size:14px">TAX</div></td>                        <td align="center" ><div style="font-size:14px">SUB TOTAL</div></td>                      </tr>';                                  
		
		
		
		$html = $html . $shtml;
		
		

						  
						  
						  




						$html.='					  </tr>					  <tr>                        <td colspan="8" align="center" ><div style="font-size:14px"><em>Thank you for purchasing item on Bhishoom</em></div></td>                        <td align="center" ><div style="font-size:14px"><strong>TOTAL</strong></div></td>                        <td align="center"><div style="font-size:14px"><strong>'.round($grandtotal).'</strong></div></td>                      </tr>					  					                      </tbody>                  </table>                       	<div style=" height:1px; margin:25px 0 15px; width:100%"></div>		<br><br>		<div style=" height:1px; margin:10px 0 10px; width:100%"></div>		<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>				  		<table width="100%" border="0" cellspacing="0" cellpadding="0">              <tbody>                <tr>                  <td height="5px"></td>                </tr>                <tr>                  <td align="center">				   <div style=""><img src="http://bhishoom.com/skin/frontend/rwd/default/images/footertext.jpg" width="700px" alt=""/></div>				  </td>                </tr>                <tr>                  <td height="5px"></td>                </tr>              </tbody>		</table>                  </div></td>            </tr>          </tbody>        </table>      </div>      </body>      </html>';	








						$pdf->WriteHTML($html);		$nameInvoiceFile = "invoice_product_".$invIncrementIDs.".pdf";	    $pdf->Output(Mage::getBaseDir()."/invoice/".$nameInvoiceFile);$this->getResponse ()                    ->setHttpResponseCode ( 200 )                    ->setHeader ( 'Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true )                     ->setHeader ( 'Pragma', 'public', true )                    ->setHeader ( 'Content-type', 'application/force-download' )                    ->setHeader ( 'Content-Length', filesize(Mage::getBaseDir()."/invoice/".$nameInvoiceFile) )                    ->setHeader ('Content-Disposition', 'attachment' . '; filename=' . basename(Mage::getBaseDir()."/invoice/".$nameInvoiceFile) );        $this->getResponse ()->clearBody ();        $this->getResponse ()->sendHeaders ();        readfile ( Mage::getBaseDir()."/invoice/".$nameInvoiceFile);        exit;	  }/*
    public function invoicepdfgenratorAction()    {        if (!$invoiceId = $this->getRequest()->getParam('invoice_id')) {            return false;        }        try {            $pdfFile = Mage::getSingleton('eadesign/entity_invoicepdf')->getThePdf((int)$invoiceId, 999922222);            $this->_prepareDownloadResponse($pdfFile->getData('filename') .                '.pdf', $pdfFile->getData('pdfbody'), 'application/pdf');        } catch (Exception $e) {            Mage::log($e->getMessage());            return null;        }    }*/
    public function invoicepdfmassAction()    {
        $ids = $this->getRequest()->getPost('invoice_ids');
        $templateId = $this->getRequest()->getPost('template');

        if (!$templateId) {
            $this->_redirect('adminhtml/sales_invoice');
            $error = Mage::helper('sales')->__('You have no templates selected!');
            Mage::getSingleton('core/session')->addError($error);
            return;
        }

        $pdfData = Mage::getSingleton('eadesign/entity_masspdf')->getPdfData($ids, $templateId);
        $this->_prepareDownloadResponse('ea_invoice_mass_print' .
            '.pdf', $pdfData, 'application/pdf');
    }

    public function orderpdfmassAction()
    {

        $ids = $this->getRequest()->getPost('order_ids');
        $templateId = $this->getRequest()->getPost('template');

        if (!$templateId) {
            $this->_redirect('adminhtml/sales_order');
            $error = Mage::helper('sales')->__('You have no templates selected!');
            Mage::getSingleton('core/session')->addError($error);
            return;
        }

        $invoiceId = array();
        foreach ($ids as $id) {
            $order = Mage::getModel('sales/order')->load($id);
            if ($order->hasInvoices()) {
                foreach ($order->getInvoiceCollection() as $invoiceCollection) {
                    $invoiceId[] = $invoiceCollection->getData('entity_id');
                }
            }
        }

        if (empty($invoiceId)) {
            $this->_redirect('adminhtml/sales_order');
            $error = Mage::helper('sales')->__('You have no files to get');
            Mage::getSingleton('core/session')->addError($error);
            return;
        }

        $pdfData = Mage::getSingleton('eadesign/entity_masspdf')->getPdfData($invoiceId, $templateId);
        $this->_prepareDownloadResponse('ea_invoice_mass_print' .
            '.pdf', $pdfData, 'application/pdf');
    }

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('pdfadmin_menu/invoice_page');
    }

}
