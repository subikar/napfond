<?php
class Mycode_Function_Model_Gracat_Observer
{
   

    public function __construct()
    {

    }
    /**
     * Applies the special price percentage discount
     * @param   Varien_Event_Observer $observer
     * @return  Xyz_Catalog_Model_Price_Observer
     */
    public function update_website_gra_menu($observer)
    {
	
	
	
		$connection = Mage::helper('function/more')->getDbReadObject();
		$write = Mage::helper('function/more')->getDbWriteObject();
	
	
		$sql = "update amenu_update_tracking set up_flag='y' where up_flag_type = 'upper'";
		$write->query($sql);	
		
		$sql = "update amenu_update_tracking set up_flag='y' where up_flag_type = 'mana_menu_model'";
		$write->query($sql);	

		$sql = "update amenu_update_tracking set up_flag='y' where up_flag_type = 'upper_menu_foooter'";
		$write->query($sql);	 

		//////////////////////////////////Update front Top Menu////////////////////////////////////
		 /*$menuContent = Mage::helper('custommenu')->getMenuContent();
		  
		 $mobileMenuContent = Mage::helper('custommenu')->getMobileMenuContent();		 
 
		file_put_contents(Mage::getBaseDir('media')."/menu_cache/menu_text_top.txt", ($menuContent['topMenu'])); 
		file_put_contents(Mage::getBaseDir('media')."/menu_cache/menu_text_top.txt", php_strip_whitespace (Mage::getBaseDir('media')."/menu_cache/menu_text_top.txt"));
		
		
		
		file_put_contents(Mage::getBaseDir('media')."/menu_cache/menu_text_popup.txt", ($menuContent['popupMenu'])); 
		file_put_contents(Mage::getBaseDir('media')."/menu_cache/menu_text_popup.txt", php_strip_whitespace (Mage::getBaseDir('media')."/menu_cache/menu_text_popup.txt"));
		
		
		
		file_put_contents(Mage::getBaseDir('media')."/menu_cache/menu_text_mobile.txt", ($mobileMenuContent)); 
		file_put_contents(Mage::getBaseDir('media')."/menu_cache/menu_text_mobile.txt", php_strip_whitespace (Mage::getBaseDir('media')."/menu_cache/menu_text_mobile.txt"));*/
		///////////////////////////////////////////////////////////////////////////////////////////
		
		//////////////////////////////////////Update front bottom menu/////////////////////////////
			$rootcatId= '24';
			$categories = Mage::getModel('catalog/category')->getCategories($rootcatId);
			//print_r($categories);
	

	
			file_put_contents(Mage::getBaseDir('media')."/menu_cache/menu.txt", Mage::helper('function')->get_categories($categories));
	
	
			file_put_contents(Mage::getBaseDir('media')."/menu_cache/menu.txt", php_strip_whitespace (Mage::getBaseDir('media')."/menu_cache/menu.txt"));
			
			file_put_contents(Mage::getBaseDir('media')."/menu_cache/mobile_menu_bhishoom.txt", Mage::helper('function/more')->getMobileMenuBhishoom());
	
	
			file_put_contents(Mage::getBaseDir('media')."/menu_cache/mobile_menu_bhishoom.txt", php_strip_whitespace (Mage::getBaseDir('media')."/menu_cache/mobile_menu_bhishoom.txt"));

	    /////////////////////////////////////////////////////////////////////////////////////////////

		$event      = $observer->getEvent();
        $_category   = $event->getCategory();
		
		$path = $_category->getPath();
		$ids  = explode('/', $path);
		$subcat_arr = array();
		$subcat_arr_id = array();
		 
		
		 
		if(in_array(34,$ids))
		 {
			$subchildren = Mage::getModel('catalog/category')->getCategories(34);
				foreach ($subchildren as $subcategory) {
					$sub_subchildren = Mage::getModel('catalog/category')->getCategories($subcategory->getId());
					foreach ($sub_subchildren as $sub_subcategory) {
					 $subcat_arr[] =  $sub_subcategory->getUrlKey();
					 $subcat_arr_id[] =  $sub_subcategory->getId();
					} 
				}
			$sql = "update amenu_update_tracking set menu_cnt_top='".implode(',',$subcat_arr)."',menu_cnt_pop='".implode(',',$subcat_arr_id)."' where up_flag_type = 'mobile_skin'";
			$write->query($sql); 
				
		 }
		else if(in_array(3,$ids))	
		 {
			$subchildren = Mage::getModel('catalog/category')->getCategories(3);
				foreach ($subchildren as $subcategory) {
					$sub_subchildren = Mage::getModel('catalog/category')->getCategories($subcategory->getId());
					foreach ($sub_subchildren as $sub_subcategory) {
					 $subcat_arr[] =  $sub_subcategory->getUrlKey();
					 $subcat_arr_id[] =  $sub_subcategory->getId();
					} 
				}
				
			$sql = "update amenu_update_tracking set menu_cnt_top='".implode(',',$subcat_arr)."',menu_cnt_pop='".implode(',',$subcat_arr_id)."' where up_flag_type = 'mobile_case'";
			$write->query($sql); 
				
		 }	
    }
	
	
   public function filterpaymentmethod(Varien_Event_Observer $observer) {
    /* call get payment method */
	$event = $observer->getEvent();
    $method = $event->getMethodInstance();
	$result = $event->getResult();
	
    /*   get  Quote  */
    $quote = $observer->getEvent()->getQuote();
	
        /* Disable Your payment method for   adminStore */
   // if($method->getCode()=='cashondelivery' ){
    if($method->getCode()=='phoenix_cashondelivery' ){
			$ablebToShip = '';	 
			$otherProduct = false;	 
			
            foreach ($quote->getAllItems() as $item) {
                // get Cart item product Type //				
                if($item->getProductType()=='ugiftcert' || $item->getProductType()=='virtual' ):
				 $ablebToShip = 'The COD facility is not available for downloadable and virtual products.';
                //$result->isAvailable = false;
				else:
				$otherProduct = true;
                endif;
            }
			$key = 'ablebToShip';
			Mage::unregister($key);

			$value = $ablebToShip;
			Mage::register($key, $value,false);

			$key = 'otherProduct';
			Mage::unregister($key);

			$value = $otherProduct;
			Mage::register($key, $value,false);
        }
    }
   public function setFedexCommercialInvoicePurpose(Varien_Event_Observer $observer) {
   
			$quote = $observer->getEvent()->getQuote();			
			$cartSubTotal = Mage::helper('checkout/cart')->getQuote()->getSubtotal();			
			$key = 'setFedexCommercialInvoicePurposeKey';
			Mage::unregister($key);

			
			if($cartSubTotal <= 5000)
				$value = 'NOT_SOLD';
			else
				$value = 'SOLD';
			
			
			
			
		$connection = Mage::helper('function/more')->getDbReadObject();
		$write = Mage::helper('function/more')->getDbWriteObject();
		$read = Mage::helper('function/more')->getDbReadObject();
		$sessionId = Mage::getSingleton("core/session")->getEncryptedSessionId();
		$query = 'SELECT clr_purpose FROM a_fedex_clr_purpose WHERE mag_session_id = "'.$sessionId.'" LIMIT 1';
 
		$clr_purpose = $read->fetchOne($query);
		
		if($clr_purpose != '')
		 {
				$query = 'UPDATE a_fedex_clr_purpose SET clr_purpose = "'.$value.'" WHERE mag_session_id = "'.$sessionId.'" LIMIT 1';
				$write->query($query);		 
		 }
		else
		 {
				$query = 'INSERT INTO a_fedex_clr_purpose SET clr_purpose = "'.$value.'",mag_session_id = "'.$sessionId.'" ';
				$write->query($query);		 		
		 }
		
			Mage::register($key, $value, false);
    }
	
   public function saveOrderCustom($observer)
   {
			$controllerAction = $observer->getEvent()->getControllerAction();
				$response = $controllerAction->getResponse();
			$paymentResponse = Mage::helper('core')->jsonDecode($response->getBody());
				if (!isset($paymentResponse['error']) || !$paymentResponse['error']) {

				$controllerAction->getRequest()->setParam('form_key', Mage::getSingleton('core/session')->getFormKey());
				$controllerAction->getRequest()->setPost('agreement', array_flip(Mage::helper('checkout')->getRequiredAgreementIds()));
				$controllerAction->saveOrderAction();
				$orderResponse = Mage::helper('core')->jsonDecode($response->getBody());

				if ($orderResponse['error'] === false && $orderResponse['success'] === true) {
					if (!isset($orderResponse['redirect']) || !$orderResponse['redirect']) {
						$orderResponse['redirect'] = Mage::getUrl('*/*/success');
					}

				$controllerAction->getResponse()->setBody(Mage::helper('core')->jsonEncode($orderResponse));
				}
			}
	}

public function defaultShpricemanaually($observer)    {        
		$controller = $observer->getControllerAction();
		$shippingaddress = Mage::getSingleton('checkout/cart')->getQuote()->getShippingAddress();
		 
     if (!$shippingaddress->getShippingMethod()) {  
	 $shippingaddress->setShippingMethod('flatrate_flatrate')->setCollectShippingRates(true);        
	 }         
	 
	 if (!$shippingaddress->getCountryId() | !$shippingaddress->getPostcode()) {            
	 $defaultcountry = Mage::getStoreConfig('general/country/default');
	 $shippingaddress->setCountryId($defaultcountry);
	 $shippingaddress->setPostcode('302017');
	 $shippingaddress->setRegionId('513');
	 $shippingaddress->setRegion('Rajasthan');
	 $shippingaddress->setShippingMethod('fedex_PRIORITY_OVERNIGHT')->setCollectShippingRates(true); 
 	Mage::getSingleton('checkout/session')->getQuote()->setTotalsCollectedFlag(false);
	Mage::getSingleton('checkout/session')->getQuote()->collectTotals();
	Mage::getSingleton('checkout/cart')->getQuote()->save(); 
	 
	 $controller->getResponse()->setRedirect(Mage::getUrl('*/*/*',array('_current'=>true)));
	 }        
/*
		$totalItemInCart = Mage::helper('checkout/cart')->getSummaryCount();
		$mainItem = 1;
		$thresHoldItem = $totalItemInCart - 1;
		
		$mainShippinPrice = Mage::getStoreConfig('mycustom_section/mycustom_group/mycustom_field');
		$thresHoldShippinPrice = Mage::getStoreConfig('mycustom_section/mycustom_group/mycustom_field2');
		$freeShippingCharge = Mage::getStoreConfig('mycustom_section/mycustom_group/mycustom_field3');
	 
	$resource = Mage::getSingleton('core/resource');
	
	$readConnection = $resource->getConnection('core_read');
	
	$writeConnection = $resource->getConnection('core_write');

	$quoteId = Mage::helper('checkout/cart')->getQuote()->getId();
	
	$shipping_address_id = $readConnection->fetchOne("SELECT address_id FROM sales_flat_quote_address where quote_id='".$quoteId."' and address_type = 'shipping'");
	 
	
	$chk_shipping_rate_id = $readConnection->fetchOne("SELECT rate_id FROM sales_flat_quote_shipping_rate where address_id='".$shipping_address_id."'");
	
	
	
	$subTotal = $readConnection->fetchOne("SELECT subtotal FROM sales_flat_quote where entity_id='".$quoteId."'");
	
	$subTotalWithDiscount = $readConnection->fetchOne("SELECT subtotal_with_discount FROM sales_flat_quote where entity_id='".$quoteId."'");
	
	//$subTotal = Mage::helper('checkout/cart')->getQuote()->getSubtotal();	
	//$subTotalWithDiscount = Mage::helper('checkout/cart')->getQuote()->getSubtotalWithDiscount();
			
		if($subTotalWithDiscount > 0)
		$subTotal = $subTotalWithDiscount;

				if($subTotal > $freeShippingCharge)
				$price = 0;	
				else
				$price = $mainShippinPrice + ($thresHoldShippinPrice * $thresHoldItem);
	
	if($rate_id > 0){
	$writeConnection->query("UPDATE sales_flat_quote_shipping_rate set updated_at='NOW()',carrier='fedex',carrier_title='Federal Express',code='fedex_PRIORITY_OVERNIGHT' ,method='PRIORITY_OVERNIGHT',price='".$price."',method_title='Priority Overnight' where entity_id='".$quoteId."'");
	}
	else{
	$writeConnection->query("INSERT INTO sales_flat_quote_shipping_rate set address_id='".$shipping_address_id."',created_at='NOW()',updated_at='NOW()',carrier='fedex',carrier_title='Federal Express',code='fedex_PRIORITY_OVERNIGHT' ,method='PRIORITY_OVERNIGHT',price='".$price."',method_title='Priority Overnight'");	
	}*/
}
	
public function salesQuoteCollectTotalsBefore2(Varien_Event_Observer $observer)
    {  
		$events=$observer->getEvent();
        $address=$events->getQuoteAddress();				
		if($address->getAddressType() == 'billing')
		return;
		
		
		/*if(!$address->getPostcode())
		$address->setPostcode('302017');
		if(!$address->getRegion())
		$address->setRegion('Rajasthan');
		$address->setRegionId('513');
		$address->save();
		$address->setCollectShippingRates(true);
		
		
		
		
		$minimumOrderAmount = Mage::getStoreConfig('carriers/fedex/free_shipping_subtotal');
		$totals = Mage::getSingleton('checkout/session')->getQuote()->getTotals();
		 $cartSubTotal = $totals["subtotal"]->getValue();
		
		if($cartSubTotal > $minimumOrderAmount)
		$price=0;
		else
		$price=50;
		
		$price = 50;		
        $rates = $address->collectShippingRates()->getGroupedAllShippingRates();

			foreach ($rates as $carrier) {
				foreach ($carrier as $rate) {
					$rate->setPrice($price);
					$rate->save();
				}
			}*/
/*	 		$address->setCollectShippingRates(true); 
		
	
 $request = Mage::app()->getFrontController()->getRequest();
 $module = $request->getModuleName();
$controller = $request->getControllerName();
 $action = $request->getActionName();
 if($module == 'checkout' && $controller == 'cart' && $action == 'index')
 {
   $address->setCollectShippingRates(false);
 }		
 elseif($module == 'mergeinfo' && $controller == 'onepage' && $action == 'index'){

   $address->setCollectShippingRates(false);	 
 }		
		*/
			
    } 	
	
public function salesQuoteCollectTotalsBefore(Varien_Event_Observer $observer)
    {

		$request = Mage::app()->getFrontController()->getRequest();
		$module = $request->getModuleName();
		$controller = $request->getControllerName();
		$action = $request->getActionName();
 
			if($module == 'mergeinfo' && $controller == 'onepage' && $action == 'savePayment')
			{
					return;	 
			}

	
       //Mage::getSingleton('core/session')->set<YOUR SESSION NAME>("Set Session"); 
        /** @var Mage_Sales_Model_Quote $quote */
        /*$quote = $observer->getQuote();
        $someConditions = true; //this can be any condition based on your requirements
        $newHandlingFee = 15;
        $store    = Mage::app()->getStore($quote->getStoreId());
        $carriers = Mage::getStoreConfig('carriers', $store);
		$minimumOrderAmount = Mage::getStoreConfig('carriers/fedex/free_shipping_subtotal');
		$cartSubTotal = Mage::helper('checkout/cart')->getQuote()->getSubtotal();
        foreach ($carriers as $carrierCode => $carrierConfig) {
            if($carrierCode == 'fedex'){
                 //echo "sdfsdfas";
                    Mage::log('Handling Fee(Before):' . $store->getConfig("carriers/{$carrierCode}/handling_fee"), null, 'shipping-price.log');
                    //$store->setConfig("carriers/{$carrierCode}/handling_type", 'F'); #F - Fixed, P - Percentage                  
                    //$store->setConfig("carriers/{$carrierCode}/handling_fee", $newHandlingFee);
     
                    ###If you want to set the price instead of handling fee you can simply use as:
					
					if($cartSubTotal > $minimumOrderAmount)
					$store->setConfig("carriers/{$carrierCode}/price", 0);
					else
                    $store->setConfig("carriers/{$carrierCode}/price", 50);
     
                    Mage::log('Handling Fee(After):' . $store->getConfig("carriers/{$carrierCode}/handling_fee"), null, 'shipping-price.log');
                 
  
        }*/

		//$connection = Mage::helper('function/more')->getDbReadObject();
		$write = Mage::helper('function/more')->getDbWriteObject();
		$read = Mage::helper('function/more')->getDbReadObject();
			
		$sessionId = Mage::getSingleton("core/session")->getEncryptedSessionId();
		$query = 'SELECT clr_purpose FROM a_fedex_clr_purpose WHERE mag_session_id = "'.$sessionId.'" LIMIT 1';
		$clr_purpose = $read->fetchOne($query);
		
		//if(!$clr_purpose){
		if(1){
		
		$events=$observer->getEvent();
        $address=$events->getQuoteAddress();
		$quote = Mage::getSingleton('checkout/session')->getQuote();
				
		
		if($address->getAddressType() == 'billing')
		return;
		
		//echo $address->getAddressType();exit;

		/*if(is_object($address)){			
        if($address->getAddressType()=='shipping' && $address->getPostcode() !=''){
		$address->save();*/
		/*
		$minimumOrderAmount = Mage::getStoreConfig('carriers/fedex/free_shipping_subtotal');
		$totals = Mage::getSingleton('checkout/session')->getQuote()->getTotals();
		$cartSubTotal = $totals["subtotal"]->getValue();
		
		if($cartSubTotal > $minimumOrderAmount)
		$price=0;
		else
		$price=50;
		$price=50;		
			
			if($cartSubTotal > 5000)
			 $data = 'fedex_STANDARD_OVERNIGHT';
			else
			 $data = 'fedex_PRIORITY_OVERNIGHT';*/
		
			 //$data = 'fedex_PRIORITY_OVERNIGHT';
			//$data = 'fedex_STANDARD_OVERNIGHT';		
				
        
		$rateArray = array(); 
		
       
        
        //$address->setShippingAmount($price);
        //$address->setBaseShippingAmount($price);
        
		$address->setCollectShippingRates(true);
        // Find if our shipping has been included.
        $rates = $address->collectShippingRates()->getGroupedAllShippingRates();
 
				
					foreach ($rates as $carrier) {						
						foreach ($carrier as $rate) {							
							$dataRate = $rate->getData();
							$rateArray[] = $dataRate['code'];
							//$rate->setPrice($price);
							//$rate->save(); 
							
						}
					}
			 
				 
				if(in_array('fedex_STANDARD_OVERNIGHT',$rateArray))	
				$data = 'fedex_STANDARD_OVERNIGHT';
				elseif(in_array('fedex_PRIORITY_OVERNIGHT',$rateArray))	
				$data = 'fedex_PRIORITY_OVERNIGHT';
				else
				$data = 'flatrate_flatrate';	
			
				
				$address->setShippingMethod($data);


		
/*				
if($module == 'checkout' && $controller == 'cart' && $action == 'index')
 {
   $address->setCollectShippingRates(false);
 }		
 elseif($module == 'mergeinfo' && $controller == 'checkout' && $action == 'index'){
   $address->setCollectShippingRates(true);	 
 }	*/	

				
								
				$address->save();
				//$address->setShippingMethod($data);
				
				//$address->setShippingMethod($data)->setCollectShippingRates(true);
                Mage::getSingleton('checkout/session')->getQuote()->save();
				
			
				

			/*}			
		}*/
			//$this->collectTotals($quote,$price);
		//}
			//$totals = Mage::getSingleton('checkout/session')->getQuote()->getTotals();
			//$address->collectTotals();
			//$address->save(); 
			
        }
		
    }
     
	 
	 
 public function collectTotals($quote,$price){
	 
	  
        $quoteid=$quote->getId(); 
        $shippingcode='freeshipping_freeshipping';
        if($quoteid) {                    
                try{
                $quote->setSubtotal(0);
                $quote->setBaseSubtotal(0);
                $quote->setSubtotalWithDiscount(0);
                $quote->setBaseSubtotalWithDiscount(0);
                $quote->setGrandTotal(0);
                $quote->setBaseGrandTotal(0);

                $quote->getShippingAddress()->setShippingMethod($shippingcode)/* ->collectTotals() */->save();
                $quote->save();
                foreach ($quote->getAllAddresses() as $address) {
					
					
			$onLineDiscount = $address->getFeeAmount();
			$baseOnLineDiscount = $address->getBaseFeeAmount(); 
					
                    $address->setSubtotal(0);
                    $address->setBaseSubtotal(0);

                    $address->setGrandTotal(0);
                    $address->setBaseGrandTotal(0);

                    $address->collectTotals();

                    $quote->setSubtotal((float) $quote->getSubtotal() + $address->getSubtotal());
                    $quote->setBaseSubtotal((float) $quote->getBaseSubtotal() + $address->getBaseSubtotal());

                    $quote->setSubtotalWithDiscount(
                        (float) $quote->getSubtotalWithDiscount() + $address->getSubtotalWithDiscount()
                    );
                    $quote->setBaseSubtotalWithDiscount(
                        (float) $quote->getBaseSubtotalWithDiscount() + $address->getBaseSubtotalWithDiscount()
                    );

                    $quote->setGrandTotal((float) $quote->getGrandTotal() + $address->getGrandTotal());
                    $quote->setBaseGrandTotal((float) $quote->getBaseGrandTotal() + $address->getBaseGrandTotal());

                    $address->setShippingAmount($price);
                    $address->setBaseShippingAmount($price);
                    $address->save();
                }

                    $response['message'] = 'Succcess';
                } catch (Exception $e) {            
                        Mage::logException($e);
                        $response['error'] = $e->getMessage();
                 }
        }
    }
	 
	 
	 
	 
		public function newcoupondiscountcal(Varien_Event_Observer $observer)
		{ 
				$item=$observer['item'];
				$rule=$observer['rule'];				
				$rulePercent = max(0, 100-$rule->getDiscountAmount());
		
		//print_r($rule->getData()); exit;
		 $customOptionPrice = Mage::helper('function/more')->getProductFinalPriceInCart($item);
		 
		//echo '<pre>';
		//print_r($options);
				 
				if($rule->getSimpleAction()=='by_percent') {
				$disPer=$rule->getDiscountAmount();
 
				$product=Mage::getModel('catalog/product')->load($item->getProductId());
				$productName = $product->getName();
				
				$productFinalPrice = $product->getPrice() + $customOptionPrice;
				
				
				$ratdisc=($productFinalPrice*$disPer)/100;	
				$applidedRuleIdsArr = explode(',',$item->getAppliedRuleIds());
				//$validateRule = Mage::helper('function/more')->checkSalesRuleIsApplicableOnProduct($item->getProductId(),$rule->getData("rule_id"));
				
				$applicable = (bool)$rule->getConditions()->validate($product);
				
				if($rule->getData("rule_id")==7)
					{				
					if($item->getDiscountAmount() > 0){
						$ratdisc=(($product->getPrice() - ($item->getDiscountAmount()/$item->getQty()))*$disPer)/100;
					}
					else{
						$ratdisc=(($item->getPrice() - ($item->getDiscountAmount()/$item->getQty()))*$disPer)/100;
					}
				
				
					$DiscountAmount=round($ratdisc) * $item->getQty();
					$BaseDiscountAmount=round($ratdisc) * $item->getQty();
				
							//$item->setDiscountAmount(28);
							//$item->setBaseDiscountAmount(28);
				
					$item->getDiscountAmount();
					$result = $observer['result'];
					$result->setDiscountAmount($DiscountAmount);
					$result->setBaseDiscountAmount($BaseDiscountAmount);	
					
					}
				elseif(($productFinalPrice - $ratdisc) < $item->getPrice() && /*$validateRule==true*/ in_array($rule->getData("rule_id"),$applidedRuleIdsArr))
					{
					
							
							$DiscountAmount=round($ratdisc) * $item->getQty();
							$BaseDiscountAmount=round($ratdisc) * $item->getQty();
							
							//$item->setDiscountAmount($DiscountAmount);
							//$item->setBaseDiscountAmount($BaseDiscountAmount);
							
								//$ratdisc = $product->getSpecialPrice() - ($product->getPrice() - $DiscountAmount);
								//$DiscountAmount=$ratdisc+$item->getDiscountAmount();
							Mage::getSingleton('core/session')->setCouponAppliedProduct($item->getId());
							
								$result = $observer['result'];
								
								$item->setOriginalPrice($productFinalPrice);
								$item->setBaseOriginalPrice($productFinalPrice);
								
								$item->setPrice($productFinalPrice);
								$item->setBasePrice($productFinalPrice);
								
								$item->setPriceInclTax($productFinalPrice);
								$item->setBasePriceInclTax($productFinalPrice);
								
								
								$item->setCartTotal($productFinalPrice * $item->getQty());
								$item->setBaseCartTotal($productFinalPrice * $item->getQty());
								
								$item->setRowTotal(($productFinalPrice) * $item->getQty());
								$item->setBaseRowTotal(($productFinalPrice) * $item->getQty());
								
								$item->setRowTotalInclTax(($productFinalPrice) * $item->getQty());
								$item->setBaseRowTotalInclTax(($productFinalPrice) * $item->getQty());
								
								//$item->setRowTotalWithDiscount((($productFinalPrice) * $item->getQty()) - $DiscountAmount);
								
								
								$item->save();
								$result->setDiscountAmount($DiscountAmount);
								$result->setBaseDiscountAmount($BaseDiscountAmount); 
								
			//Mage::getSingleton('core/session')->addSuccess('Coupon code was successfully applied for product: "'.$productName.'"');
								
					}
				else{							
								$item->setDiscountAmount(0);
							
								//Mage::getSingleton('core/session')->addError('Coupon code was not valid for product: "'.$productName.'"');
								$result = $observer['result'];
								$result->setDiscountAmount(0);
								$result->setBaseDiscountAmount(0);
					}
			}
		}	

		public function setSutotalWithDiscount($observer)
		{
			
			$quote=$observer->getEvent()->getQuote();
			$quoteid=$quote->getId();
			 
			$data = Mage::app()->getRequest()->getPost('payment', array());			
		 
			$discountAmount=1;
			
			$getCouponAppliedProductVar = Mage::getSingleton('core/session')->getCouponAppliedProduct();
			$oCoupon = Mage::getModel('salesrule/rule')->load($quote->getData('applied_rule_ids'), 'rule_id');

			$subTotalWithDiscount = 0;
			$subTotalWithDiscountFlag = false;
			$subTotal = 0;
						   
			if($quote->getSubtotalWithDiscount() < $quote->getSubtotal())
			    $extraAmount = $quote->getGrandTotal() - $quote->getSubtotalWithDiscount();
			else
			    $extraAmount = $quote->getGrandTotal() - $quote->getSubtotal();

				
			if($oCoupon->getSimpleAction()=='by_percent') {
				   
				foreach ($quote->getAllItems() as $item) {	
					//if($item->getProductType() === 'configurable') 
						//continue;
				$customOptionPrice = Mage::helper('function/more')->getProductFinalPriceInCart($item);   
				//$disPer=$oCoupon->getDiscountAmount();
				$product=Mage::getModel('catalog/product')->load($item->getProductId());
				$productName = $product->getName();
				
				$productFinalPrice = $product->getPrice() + $customOptionPrice;
				//$ratdisc=($product->getPrice()*$disPer)/100;
				//$DiscountAmount=$ratdisc+$item->getDiscountAmount();
				//$BaseDiscountAmount=$ratdisc+$item->setBaseDiscountAmount();
				$item->getDiscountAmount();
				
 
						if($item->getDiscountAmount() > 0)
						{						
							$item->setDiscountAmount($item->getDiscountAmount());
													
							if( (($productFinalPrice - ($item->getDiscountAmount()/$item->getQty()))) < ($item->getPrice() ) )
							 $subTotalWithDiscount = $subTotalWithDiscount + (($productFinalPrice - ($item->getDiscountAmount()/$item->getQty())) * $item->getQty());
							else
							 $subTotalWithDiscount = $subTotalWithDiscount + (($item->getPrice() - ($item->getDiscountAmount()/$item->getQty())) * $item->getQty());
							 
							//$subTotal = $subTotal + ($item->getPrice() * $item->getQty());
							$subTotal = $subTotal + ($item->getPrice() * $item->getQty());
							$subTotalWithDiscountFlag = true;

 
							
						}
						else{
							$item->setDiscountAmount(0);
							$subTotalWithDiscount = $subTotalWithDiscount + ($item->getPrice() * $item->getQty());
							$subTotal = $subTotal + ($item->getPrice() * $item->getQty());
						}
					
						
						
						
						
						
						
						
					}
					
						
    if($quoteid && $subTotalWithDiscountFlag == true) {      
        if($discountAmount>0) {
			$total=$quote->getBaseSubtotal();
			$quote->setSubtotal(0);
			$quote->setBaseSubtotal(0);

			$quote->setSubtotalWithDiscount(0);
			$quote->setBaseSubtotalWithDiscount(0);

			$quote->setGrandTotal(0);
			$quote->setBaseGrandTotal(0);
  
    
			$canAddItems = $quote->isVirtual()? ('billing') : ('shipping'); 
			foreach ($quote->getAllAddresses() as $address) {
    
			$address->setSubtotal(0);
            $address->setBaseSubtotal(0);

            $address->setGrandTotal(0);
            $address->setBaseGrandTotal(0);

            $address->collectTotals(); 
			
            $quote->setSubtotalWithDiscount(
                (float) $subTotalWithDiscount
            );
            $quote->setBaseSubtotalWithDiscount(
                (float) $subTotalWithDiscount
            );
            $quote->setSubtotal((float) $subTotal);
            $quote->setBaseSubtotal((float) $subTotal);


            $quote->setGrandTotal((float) ($subTotalWithDiscount + $extraAmount));
            $quote->setBaseGrandTotal((float) ($subTotalWithDiscount + $extraAmount));
 
			$quote ->save(); 
 
			$quote->setGrandTotal(($subTotalWithDiscount + $extraAmount))
			->setBaseGrandTotal(($subTotalWithDiscount + $extraAmount))
			->setSubtotalWithDiscount($subTotalWithDiscount)
			->setBaseSubtotalWithDiscount($subTotalWithDiscount)
			->setSubtotal($subTotal)
			->setBaseSubtotal($subTotal)
			->save(); 
      
    
			if($address->getAddressType()==$canAddItems) {
				//echo $address->setDiscountAmount; exit;
				$address->setSubtotal($subTotal);
				$address->setBaseSubtotal($subTotal);
				
				$address->setSubtotalWithDiscount($subTotalWithDiscount);
				$address->setGrandTotal(($subTotalWithDiscount + $extraAmount));
				$address->setBaseSubtotalWithDiscount($subTotalWithDiscount);
				$address->setBaseGrandTotal(($subTotalWithDiscount + $extraAmount));
	  
				 /*if($address->getDiscountDescription()){
				 $address->setDiscountAmount(-($address->getDiscountAmount()-$discountAmount));
				 $address->setDiscountDescription($address->getDiscountDescription().', Custom Discount');
				 $address->setBaseDiscountAmount(-($address->getBaseDiscountAmount()-$discountAmount));
				 }else {
				 $address->setDiscountAmount(-($discountAmount));
				 $address->setDiscountDescription('Custom Discount');
				 $address->setBaseDiscountAmount(-($discountAmount));
				 }*/
	 
	 
				$address->save();
			}//end: if
		} //end: foreach
			//echo $quote->getGrandTotal();
  
 
            
                
				}
            
			}
		}



		if($data['method'] == 'payucheckout_shared')		
		{
			
			
//////////////////////////////////////Check Gift Certificate Balance In Cart If it is applied/////////////////////////////
$giftCertificatBalance = 0;

if($quote->getGiftcertCode()!=''){
$cert = Mage::getModel('ugiftcert/cert')->load($quote->getGiftcertCode(), 'cert_number');
$giftCertificatBalance =  $cert->getBaseBalance();
}
///////////////////////////////////////////////////////////////////////////////////////////////////////			
			
			
				$shippingaddress = Mage::getSingleton('checkout/cart')->getQuote()->getShippingAddress();
				$discount = 5; //your discount percent
				
                $grandTotal = $shippingaddress->getGrandTotal();
                $baseGrandTotal = $shippingaddress->getBaseGrandTotal();				
				
				if($shippingaddress->getSubTotalWithDiscount() > 0){
                $subTotalWithDiscount = $shippingaddress->getSubTotalWithDiscount();
                $baseSubTotalWithDiscount = $shippingaddress->getBaseSubTotalWithDiscount();
				}
				else{
				$subTotalWithDiscount = $shippingaddress->getSubTotal();
                $baseSubTotalWithDiscount = $shippingaddress->getBaseSubTotal();
				}
				
				
                $totals = $subTotalWithDiscount - $giftCertificatBalance;
                $baseTotals = $baseSubTotalWithDiscount - $giftCertificatBalance;;
				 
				 
				 $feeAmount = (ceil($totals * $discount / 100));
				 $baseFeeAmount = (ceil($baseTotals * $discount / 100));
				 
				 
				 if($feeAmount < 0)
				 $feeAmount = 0;
				 if($baseFeeAmount < 0)
				 $baseFeeAmount = 0;
			 
			 
				 $feeAmount = $feeAmount * -1;
				 $baseFeeAmount = $baseFeeAmount * -1;
			 
				 
                $shippingaddress->setFeeAmount($feeAmount);
                $shippingaddress->setBaseFeeAmount($baseFeeAmount);

                $shippingaddress->setGrandTotal($grandTotal + $shippingaddress->getFeeAmount());
				
                $shippingaddress->setBaseGrandTotal($baseGrandTotal + $shippingaddress->getBaseFeeAmount());
				$shippingaddress->save();
				//$this->getOnepage()->getQuote()->collectTotals()->save();
			}

		
	 
	}
 public function updateProductName(Varien_Event_Observer $observer)
    {
	
	$product = $observer->getProduct();
	$productId = $product->getId();
	
//$_SESSION[$currentProductId ]['model'] = $model;
//$_SESSION[$currentProductId ]['brand'] = $brand;
//$_SESSION[$currentProductId ]['type'] = $type;
//$_SESSION[$currentProductId ]['category_id'] = $category_id;
		
		
		//$connection = Mage::helper('function/more')->getDbReadObject();
		$write = Mage::helper('function/more')->getDbWriteObject();
		$read = Mage::helper('function/more')->getDbReadObject();

		//$model = Mage::getSingleton('core/session')->getModel();
		//$brand = Mage::getSingleton('core/session')->getBrand();
		//$type = Mage::getSingleton('core/session')->getType();
		//$category_id = Mage::getSingleton('core/session')->getCid();
		
		
		$getPrdetails = Mage::getSingleton('core/session')->getPrdetails();
		//echo '<pre>';
		//print_r($getPrdetails);
		
		$option_array = $_REQUEST['options'];
		//print_r($option_array);
		
		foreach($option_array as $option_array_val)
		{
		  $option_array_val_exp = explode("/",$option_array_val);
		  if(trim($option_array_val_exp[0])=='Mobile Case' || trim($option_array_val_exp[0])=='Mobile Skin' || trim($option_array_val_exp[0])=='Tablet Skin' || trim($option_array_val_exp[0])=='Laptop Skin' || trim($option_array_val_exp[0])=='T-Shirt')
		  {		  
		  	$option_array_val_catid = $option_array_val_exp[count($option_array_val_exp)-1];
		  	break;		  
		  }
		  
		  	
		}
				
		
		
		foreach($getPrdetails as $getPrdetailsKey => $getPrdetailsVal){	
		
		  foreach($getPrdetailsVal as $getPrdetailsValKey=>$getPrdetailsValVal)	
		   {	
		   //echo '<br/>';
		   //echo $getPrdetailsValVal[$productId]['category_id'];
		   //echo '<br/>';
		   //echo $getPrdetailsValKey;
		  
		  	
		  
		     if($getPrdetailsValVal[$productId]['category_id']==$getPrdetailsValKey && $getPrdetailsValVal[$productId]['category_id'] == $option_array_val_catid ){
		     	$model = $getPrdetailsVal[$getPrdetailsValKey][$productId]['model'];
			$brand = $getPrdetailsVal[$getPrdetailsValKey][$productId]['brand'];
			$type = $getPrdetailsVal[$getPrdetailsValKey][$productId]['type'];
			 $category_id = $getPrdetailsVal[$getPrdetailsValKey][$productId]['category_id'];
			 $dtlURL = $getPrdetailsVal[$getPrdetailsValKey][$productId]['detail_url'];
			break;
			
		     }			
		  }
		}
		
		if($_POST['fromListPage'] == 'yes')
		{	
		    $model = $_POST['fromListModel'];
			$brand = $_POST['fromListBrand'];
			$type = $_POST['fromListType'];
			$category_id = $_POST['fromListCat'];
		}
		
		 
		$_category = Mage::getModel('catalog/category')->load($category_id);
		$categoryUrl_key = $_category->getUrl_key();
		
		if($dtlURL == 'noURL')
		$urlFull = 'javascript:void(0)';
		else
		$urlFull = Mage::helper('function')->getUrlPath($product,$_category);
			//echo $item->getName();
		//$sessionId = Mage::getSingleton("core/session")->getEncryptedSessionId();		 
		$query = 'SELECT * FROM sales_flat_quote_item order by item_id desc LIMIT 1';
		$itemDetails = $read->fetchRow($query);
		
		
		$quote_id = $itemDetails['quote_id'];
		
		if($category_id == 477 || $category_id == 1144 || $category_id == 21 || $category_id == 42 || $category_id == 44 || $category_id == 972 || $category_id == 8)
		$nameSuffix = $type;	
		elseif($category_id == 535 || $category_id == 84 || $category_id == 85 || $category_id == 86 || $category_id == 87 || $category_id == 88 || $category_id == 92 || $category_id == 96 || $category_id == 329)
		$nameSuffix = '';	
		elseif($category_id == 39 || $category_id == 292)
		$nameSuffix = 'cotton T-shirt'. " for " . $brand;	
		elseif($model!='')
		$nameSuffix = $type. " for " . $model;	
		else
		$nameSuffix = $type. " for " . $brand;	
		
		
		 
	
	
		$name = $itemDetails['name'];
		
		
		$newName = $nameSuffix ;
		
		$item_id = $itemDetails['item_id'];
		
		
		if(trim($type) == 'Mobile Case')
		$modelKey = 'mobile-case';
		elseif(trim($type) == 'Mobile Skin')
		$modelKey = 'mobile-skin';
		elseif(trim($type) == 'Laptop Skin')
		$modelKey = 'laptop-skin';
		elseif(trim($type) == 'Tablet Skin')
		$modelKey = 'tablet-skin';
		elseif(trim($type) == 'T-Shirt')
		$modelKey = 't-shirt';

		//echo addslashes($newName);
		//exit;
		
		$saveUrlKey = $modelKey .'/'.$categoryUrl_key;
		
		if($productId == 113906){
		$saveUrlKey = 'gift/gift-certificate';
		$newName = '';
		$_category = Mage::getModel('catalog/category')->load(359);
		$urlFull = Mage::helper('function')->getUrlPath($product,$_category);
		} 
		
		$designId = Mage::app()->getRequest()->getParam('design');
		
		
		if($designId > 0)
		{
			$newName = '';
			$urlFull = 'javascript:void(0)';
		}
		
		
		$query = "update sales_flat_quote_item set nameSuffix = '".addslashes($newName)."',prdUrl = '".$saveUrlKey."',prdfullUrl = '".$urlFull."',prdType = '".$modelKey."' where item_id = '".$item_id."'";
		$write->query($query);
		//echo $item->getId();exit;
		
		
		
		$shownOptionsArray = '';
		
		$query = 'SELECT value FROM sales_flat_quote_item_option WHERE item_id = "'.$item_id.'" and code="option_ids" LIMIT 1';
		$option_ids = $read->fetchOne($query);
		$shownOptionsArray  = explode(',',$option_ids);
		
		
		$query = 'SELECT * FROM sales_flat_quote_item_option where item_id="'.$item_id.'"';
		
		
		$itemOptionsDetailsAll = $read->fetchAll($query);
		
		foreach($itemOptionsDetailsAll as $itemOptionsDetails)
		{
			if(trim($itemOptionsDetails["value"]) == trim('not_show')){
			$searchArrayOptionIdValue = str_replace('option_','',$itemOptionsDetails["code"]);
				if (($key = array_search($searchArrayOptionIdValue , $shownOptionsArray)) !== false) {
						    unset($shownOptionsArray[$key]);
					}
			
			
			
			
			}
			
		}
$options_id_str = implode(',',$shownOptionsArray);
$query = "update sales_flat_quote_item_option set value= '".$options_id_str ."' where item_id = '".$item_id."' and code='option_ids'";
		$write->query($query);
		
		
		$mainShippinPrice = Mage::getStoreConfig('mycustom_section/mycustom_group/mycustom_field');
		$thresHoldShippinPrice = Mage::getStoreConfig('mycustom_section/mycustom_group/mycustom_field2');
		
		
		$query = "update sales_flat_quote set main_shipping_price = '".$mainShippinPrice."',thersh_hold_shipping_price='".$thresHoldShippinPrice."' where entity_id = '".$quote_id."'";
		$write->query($query);			
		
		
	}
	
	
	
	
 public function updateProductsName(Varien_Event_Observer $observer)
    {
	
	
	
			$quote=Mage::getSingleton('checkout/session')->getQuote();
			$quoteid=$quote->getId();
	
	

		$connection = Mage::helper('function/more')->getDbReadObject();
		$write = Mage::helper('function/more')->getDbWriteObject();
		$read = Mage::helper('function/more')->getDbReadObject();


	
	
			//echo $item->getName();
		//$sessionId = Mage::getSingleton("core/session")->getEncryptedSessionId();		 
		$query = 'SELECT * FROM sales_flat_quote_item where quote_id="'.$quoteid.'"';
		
		
		$itemDetailsAll = $read->fetchAll($query);
		
		foreach($itemDetailsAll as $itemDetails)
		{
			$nameSuffix = $itemDetails['nameSuffix'];
			$name = $itemDetails['name'];
			$newName = $name.' '.$nameSuffix ;		
			$item_id = $itemDetails['item_id'];
			$parent_item_id = $itemDetails['parent_item_id'];
			$prdURL = $itemDetails['prdUrl'];
			$urlFull = $itemDetails['prdfullUrl'];
			
			
			
			$query = "update sales_flat_quote_item set name = '".addslashes($newName)."' where item_id = '".$item_id."'";
			$write->query($query);	
		   if($parent_item_id > 0){	
			 $query = "update sales_flat_quote_item set name = '".addslashes($newName)."',nameSuffix='".$nameSuffix."',prdURL='".$prdURL ."',prdfullUrl = '".$urlFull."' where item_id = '".$parent_item_id."'";
			$write->query($query);	
			}	
		}
		
				
		//if(trim($type) == 'Mobile Case')
		//$modelKey = 'mobile-case';
		
		//$saveUrlKey = $modelKey .'/'.$categoryUrl_key;
		
		//echo $item->getId();exit; 		
		
	}
	
	
	
public function updateOrderItemInfo(Varien_Event_Observer $observer)
	{
		$order = $observer->getEvent()->getOrder();
		$orderId = $order->getId();
		$quoteId = $order->getQuoteId();
		$codFee = $order->getCodFee();
		
		$connection = Mage::helper('function/more')->getDbReadObject();
		$write = Mage::helper('function/more')->getDbWriteObject();
		$read = Mage::helper('function/more')->getDbReadObject();
		 
		$query = 'SELECT * FROM sales_flat_order_item where order_id="'.$orderId.'"';		
		$itemDetailsAll = $read->fetchAll($query);
		
		
		$query = "SELECT * FROM sales_flat_quote where entity_id='".$quoteId."'";
		$quoteShippingDetails = $read->fetchRow($query);			
		
		$shippingAmount = $order->getShippingAmount();
		
		$mainPrice = 0;		
		$thershHoldShippingPrice = 0;
		
		
		$settings_main_price = $quoteShippingDetails["main_shipping_price"];
		
		$settings_thershhold_shipping_price = $quoteShippingDetails["thersh_hold_shipping_price"];
				
		$query = "update sales_flat_order set main_shipping_price = '".$settings_main_price."',thersh_hold_shipping_price='".$settings_thershhold_shipping_price."' where entity_id = '".$orderId."'";			
		$write->query($query);		
		
		$codFeeApplicable = 'n';
		
		if($codFee > 0)
		$codFeeApplicable = 'y';
		
		foreach($itemDetailsAll as $itemDetailsOrder)
		{
			
			if($itemDetailsOrder["product_type"]=='configurable')
				continue;
			
			
			
				$itemShippingPrice = 0;
			
				   if(!$itemDetailsOrder["is_virtual"] && $shippingAmount > 0)
					{
						if($mainPrice == 0){
							$mainPrice = $settings_main_price;
							$totalThershHoldPrice = ($itemDetailsOrder["qty_ordered"] - 1) * $settings_thershhold_shipping_price;
							$itemShippingPrice = $mainPrice + $totalThershHoldPrice;
						}
						else{
							$totalThershHoldPrice = ($itemDetailsOrder["qty_ordered"]) * $settings_thershhold_shipping_price;
							$itemShippingPrice = $totalThershHoldPrice;
						}
					}


			 $query = "update sales_flat_order_item set item_shipping_price='".$itemShippingPrice."',cod_applicable='".$codFeeApplicable."' where item_id = '".$itemDetailsOrder['item_id']."'";			
			 $write->query($query);	
			
			$parent_item_id = $itemDetailsOrder['parent_item_id'];
			
		    if($parent_item_id > 0){	
			 $query = "update sales_flat_order_item set item_shipping_price='".$itemShippingPrice."',cod_applicable='".$codFeeApplicable."' where item_id = '".$parent_item_id."'";
			 $write->query($query);
			}			
			
			$codFeeApplicable = 'n';
					
			
			if($itemDetailsOrder["nameSuffix"] == "" || $itemDetailsOrder["nameSuffix"] == 0)
			{
					$query = 'SELECT * FROM sales_flat_quote_item where item_id = "'.$itemDetailsOrder["quote_item_id"].'"';
					$itemDetails = $read->fetchRow($query);
			
				$nameSuffix = $itemDetails['nameSuffix'];
				$name = $itemDetails['name'];
				$newName = $name.' '.$nameSuffix ;
				$item_id = $itemDetails['item_id'];
				$prdType = $itemDetails['prdType'];
				
				$prdURL = $itemDetails['prdUrl'];
				$urlFull = $itemDetails['prdfullUrl'];
				
				$parent_item_id = $itemDetailsOrder['parent_item_id'];
				
				 $query = "update sales_flat_order_item set name = '".addslashes($newName)."',nameSuffix='".$nameSuffix."',prdURL='".$prdURL ."',prdfullUrl = '".$urlFull."',prdType = '".$prdType."' where item_id = '".$itemDetailsOrder['item_id']."'";			
				 $write->query($query);	
			
			
					if($parent_item_id > 0){
					 $query = "update sales_flat_order_item set name = '".addslashes($newName)."',nameSuffix='".$nameSuffix."',prdURL='".$prdURL ."',prdfullUrl = '".$urlFull."',prdType = '".$prdType."' where item_id = '".$parent_item_id."'";
					 $write->query($query);
					}
						
			}
			
			
			

			
		}
		
		//////////////////////////////////////////////////////////////////////////
		
		$query = 'SELECT quote_id FROM sales_flat_order where entity_id="'.$orderId.'"';
		$quote_id = $read->fetchOne($query);		
		
		$query = 'SELECT fee_amount,base_fee_amount FROM sales_flat_quote_address where quote_id="'.$quote_id.'" and address_type="shipping"';
		
		$feeAmountAll = $read->fetchAll($query);		
		
		foreach($feeAmountAll as $feeAmountOrder)
		{
			$query = "update sales_flat_order set fee_amount = '".$feeAmountOrder["fee_amount"]."',base_fee_amount='".$feeAmountOrder["base_fee_amount"]."' where entity_id = '".$orderId."'";			
			$write->query($query);				
		}
		
		
		
	///////////////////////////////////////Code to update shipping method/////////////////////////////////////	
		$paymentMethod = $order->getPayment()->getMethodInstance()->getCode();
		
		if($paymentMethod == 'phoenix_cashondelivery' /*|| $cartSubTotal >= 5000*/)
		{
		
	                $address_id = $read->fetchOne("SELECT address_id FROM sales_flat_quote_address where quote_id='".$quote_id ."' and address_type='shipping'");	
	                 
	                $write->query("UPDATE sales_flat_quote_address set shipping_method = 'fedex_STANDARD_OVERNIGHT',shipping_description='Federal Express - Standard Overnight'  where quote_id='".$quote_id ."' and address_type='shipping'");  
	                
	              
	                
	                $write->query("UPDATE sales_flat_quote_shipping_rate set code = 'fedex_STANDARD_OVERNIGHT',method='STANDARD_OVERNIGHT',method_title='Standard Overnight'  where  address_id='".$address_id."'");  
	                
	                
	                $write->query("UPDATE sales_flat_order set shipping_description = 'Federal Express - Standard Overnight',shipping_method='fedex_STANDARD_OVERNIGHT'  where  entity_id='".$orderId."'");  
	                
	                
	                
	                 
		/*echo $data = 'fedex_STANDARD_OVERNIGHT';
			$shippingaddress->setShippingMethod($data);
			$shippingaddress->save();
			$this->getOnepage()->saveShippingMethod($data);
			Mage::getSingleton('checkout/session')->getQuote()->save();*/
			
		}
		
			
 	}
	
	
	
public function updateOrderItemInfoForShipping(Varien_Event_Observer $observer)
	{
		$orderIds = $observer->getData('order_ids');
		foreach($orderIds as $_orderId){
		$order = Mage::getModel('sales/order')->load($_orderId);
		$orderId = $order->getId();
		$quoteId = $order->getQuoteId();
		$codFee = $order->getCodFee();
		
		$connection = Mage::helper('function/more')->getDbReadObject();
		$write = Mage::helper('function/more')->getDbWriteObject();
		$read = Mage::helper('function/more')->getDbReadObject();
		 
		$query = 'SELECT * FROM sales_flat_order_item where order_id="'.$orderId.'"';		
		$itemDetailsAll = $read->fetchAll($query);
		
		
		$query = "SELECT * FROM sales_flat_quote where entity_id='".$quoteId."'";
		$quoteShippingDetails = $read->fetchRow($query);			
		
		$shippingAmount = $order->getShippingAmount();
		
		$mainPrice = 0;		
		$thershHoldShippingPrice = 0;		
		
		$settings_main_price = $quoteShippingDetails["main_shipping_price"];
		
		$settings_thershhold_shipping_price = $quoteShippingDetails["thersh_hold_shipping_price"];
				
		$query = "update sales_flat_order set main_shipping_price = '".$settings_main_price."',thersh_hold_shipping_price='".$settings_thershhold_shipping_price."' where entity_id = '".$orderId."'";			
		$write->query($query);		
		
		$codFeeApplicable = 'n';
		
		if($codFee > 0)
		$codFeeApplicable = 'y';
		
		foreach($itemDetailsAll as $itemDetailsOrder)
		{
			
			if($itemDetailsOrder["product_type"]=='configurable')
				continue;		
			
				$itemShippingPrice = 0;
			
				   if(!$itemDetailsOrder["is_virtual"] && $shippingAmount > 0)
					{
						if($mainPrice == 0){
							$mainPrice = $settings_main_price;
							$totalThershHoldPrice = ($itemDetailsOrder["qty_ordered"] - 1) * $settings_thershhold_shipping_price;
							$itemShippingPrice = $mainPrice + $totalThershHoldPrice;
						}
						else{
							$totalThershHoldPrice = ($itemDetailsOrder["qty_ordered"]) * $settings_thershhold_shipping_price;
							$itemShippingPrice = $totalThershHoldPrice;
						}
					}


			 $query = "update sales_flat_order_item set item_shipping_price='".$itemShippingPrice."',cod_applicable='".$codFeeApplicable."' where item_id = '".$itemDetailsOrder['item_id']."'";			
			 $write->query($query);	
			
			$parent_item_id = $itemDetailsOrder['parent_item_id'];
			
		    if($parent_item_id > 0){	
			 $query = "update sales_flat_order_item set item_shipping_price='".$itemShippingPrice."',cod_applicable='".$codFeeApplicable."' where item_id = '".$parent_item_id."'";
			 $write->query($query);
			}			
			
			$codFeeApplicable = 'n';
			
		}
		
		
		}
		
			
 	}
	
	
	
	
	public function setUnderProcessing(){
		
			$orderCollection = Mage::getResourceModel('sales/order_collection');
	 
			$orderCollection->addFieldToFilter('state', 'pending_payment')->addFieldToFilter('DATE(created_at)', array('eq' =>  new Zend_Db_Expr("CURDATE(), INTERVAL -1 DAY")))->getSelect()->order('e.entity_id');			
			//->limit(10);
	 
				$orders ="";
				foreach($orderCollection->getItems() as $order)
				{
				  $orderModel = Mage::getModel('sales/order');
				  $orderModel->load($order['entity_id']);	 
				  if(!$orderModel->canCancel())
					continue;
	 
				  //$orderModel->cancel();
				  //$orderModel->setStatus('canceled_pendings');
				  //$orderModel->save();	
				  
					$orderModel->setData('state', "processing");
					$orderModel->setStatus("under_processing");
					$history = $orderModel->addStatusHistoryComment('Order was set to Under Processing by our automation tool.', false);
					$history->setIsCustomerNotified(false);
					$orderModel->save();
		
				}
				
				$to = "yadavlokesh583@gmail.com";
				$subject = "My subject";
				$txt = "Hello world!";
				$headers = "From: info@bhishoom.com" . "\r\n";

				mail($to,$subject,$txt,$headers);				
				
	}
	
	public function updateTrackingInfoFunction(){
		
		Mage::helper('function/cron')->updateTrackingInfo();
		
	}
		public function modifySaleable($observer){
			$saleable = $observer->getSalable();
			$saleable->setIsSalable(true);
		}		
}