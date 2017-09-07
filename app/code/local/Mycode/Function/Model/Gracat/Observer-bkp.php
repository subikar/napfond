<?php session_start();
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
	
	
	
		$connection = Mage::getSingleton('core/resource')->getConnection('core_read');
		$write = Mage::getSingleton('core/resource')->getConnection('core_write');	
	
	
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
	

	
			file_put_contents(Mage::getBaseDir('media')."/menu_cache/menu.txt", Mage::helper('function')->get_categories($categories));
	
	
			file_put_contents(Mage::getBaseDir('media')."/menu_cache/menu.txt", php_strip_whitespace (Mage::getBaseDir('media')."/menu_cache/menu.txt"));

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
			
			
			
			
		$connection = Mage::getSingleton('core/resource')->getConnection('core_read');
		$write = Mage::getSingleton('core/resource')->getConnection('core_write');	
		$read = Mage::getSingleton('core/resource')->getConnection('core_read');	
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




public function salesQuoteCollectTotalsBefore2(Varien_Event_Observer $observer)
    {  

		
		$events=$observer->getEvent();
        	$address=$events->getQuoteAddress();
				
		if($address->getAddressType() == 'billing')
		return;

		
		
		
		
		
		
		$address->setCollectShippingRates(true);
		
	$price = 50;	
		
        $rates = $address->collectShippingRates()->getGroupedAllShippingRates();

					foreach ($rates as $carrier) {
											 
						foreach ($carrier as $rate) {
							$rate->setPrice($price);
							$rate->save(); 
						}
					}		
		
		
		
    } 	
	
public function salesQuoteCollectTotalsBefore(Varien_Event_Observer $observer)
    {  

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
		
		$connection = Mage::getSingleton('core/resource')->getConnection('core_read');
		$write = Mage::getSingleton('core/resource')->getConnection('core_write');
		$read = Mage::getSingleton('core/resource')->getConnection('core_read');
			
		$sessionId = Mage::getSingleton("core/session")->getEncryptedSessionId();
		$query = 'SELECT clr_purpose FROM a_fedex_clr_purpose WHERE mag_session_id = "'.$sessionId.'" LIMIT 1';
		$clr_purpose = $read->fetchOne($query);
		
		if(!$clr_purpose){
		
		$events=$observer->getEvent();
        	$address=$events->getQuoteAddress();
		$quote = Mage::getSingleton('checkout/session')->getQuote();
				
		
		if($address->getAddressType() == 'billing')
		return;
		
		//echo $address->getAddressType();exit;
		
		/*if(is_object($address)){
			
			
			
        if($address->getAddressType()=='shipping' && $address->getPostcode() !=''){
		*/
		
		$minimumOrderAmount = Mage::getStoreConfig('carriers/fedex/free_shipping_subtotal');
		$cartSubTotal = Mage::helper('checkout/cart')->getQuote()->getSubtotal();

		if($cartSubTotal > $minimumOrderAmount)
		$price=0;
		else
		$price=50;
				
			
			if($cartSubTotal > 5000)
			 $data = 'fedex_STANDARD_OVERNIGHT';
			else
			 $data = 'fedex_PRIORITY_OVERNIGHT';
		
			//$data = 'flatrate_flatrate';
			//$data = 'fedex_STANDARD_OVERNIGHT';
			 
		
				
        $price=50;
        $address->setShippingAmount($price);
        $address->setBaseShippingAmount($price);
        $address->save();
        // Find if our shipping has been included.
        $rates = $address->collectShippingRates()->getGroupedAllShippingRates();

					foreach ($rates as $carrier) {
											 
						foreach ($carrier as $rate) {
							$rate->setPrice($price);
							$rate->save(); 
						}
					}
					
				
				$address->setCollectShippingRates(true);
				$address->setShippingMethod($data);
				//$address->setShippingMethod($data);
				
				
				
				
				

			/*}			
		}*/
			//$this->collectTotals($quote,$price);
		//}
			//Mage::getSingleton('checkout/session')->getQuote()->save();
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
				
				if($rule->getSimpleAction()=='by_percent') {
				$disPer=$rule->getDiscountAmount();
				$product=Mage::getModel('catalog/product')->load($item->getProductId());
				$productName = $product->getName();				
				$ratdisc=($product->getPrice()*$disPer)/100;
				
				
				
				
						if(($product->getPrice() - $ratdisc) < $item->getPrice())
						{
							//$item->setDiscountAmount($ratdisc);
							
							$DiscountAmount=$ratdisc * $item->getQty();
							$BaseDiscountAmount=$ratdisc * $item->getQty();
							
							
							
								//$ratdisc = $product->getSpecialPrice() - ($product->getPrice() - $DiscountAmount);
								//$DiscountAmount=$ratdisc+$item->getDiscountAmount();
							Mage::getSingleton('core/session')->setCouponAppliedProduct($item->getId());
							
								$result = $observer['result'];
								$result->setDiscountAmount($DiscountAmount);
								$result->setBaseDiscountAmount($BaseDiscountAmount); 
								
			Mage::getSingleton('core/session')->addSuccess('Coupon code was successfully applied for product: "'.$productName.'"');
								
						}
						else{
							
								$item->setDiscountAmount(0);
							
			Mage::getSingleton('core/session')->addError('Coupon code was not valid for product: "'.$productName.'"');
								$result = $observer['result'];
								$result->setDiscountAmount(0);
								$result->setBaseDiscountAmount(0); 							
						}
				}
		}	

		public function setSutotalWithDiscount($observer)
		{
			   $quote=Mage::getSingleton('checkout/session')->getQuote();
			   $quoteid=$quote->getId();
			   $discountAmount=10;
			   
			 $getCouponAppliedProductVar = Mage::getSingleton('core/session')->getCouponAppliedProduct();	  
			 $oCoupon = Mage::getModel('salesrule/rule')->load($quote->getData('applied_rule_ids'), 'rule_id');
			   
			   
			 $subTotalWithDiscount = 0;
			   
			   
			   if($quote->getSubtotalWithDiscount() < $quote->getSubtotal())
					$extraAmount = $quote->getGrandTotal() - $quote->getSubtotalWithDiscount();
			   else
				    $extraAmount = $quote->getGrandTotal() - $quote->setSubtotal();
  //$quote->setSubtotal(0);
   //$quote->setBaseSubtotal(0);

   $quote->setSubtotalWithDiscount(0);
   $quote->setBaseSubtotalWithDiscount(0);

   $quote->setGrandTotal(0);
   $quote->setBaseGrandTotal(0);
 			   
		$quote ->save();	   
			   
			   if($oCoupon->getSimpleAction()=='by_percent') {
				   
				foreach ($quote->getAllItems() as $item) {	
					if($item->getProductType() === 'configurable') 
						continue;				   
				   
				//$disPer=$oCoupon->getDiscountAmount();
				$product=Mage::getModel('catalog/product')->load($item->getProductId());
				//$productName = $product->getName();
				//$ratdisc=($product->getPrice()*$disPer)/100;
				//$DiscountAmount=$ratdisc+$item->getDiscountAmount();
				//$BaseDiscountAmount=$ratdisc+$item->setBaseDiscountAmount();
				
						if($item->getDiscountAmount() > 0)
						{
							$item->setDiscountAmount($item->getDiscountAmount() * $item->getQty());
							$subTotalWithDiscount = $subTotalWithDiscount + ($product->getPrice() - $item->getDiscountAmount());
						}
						else{
							$item->setDiscountAmount(0);
							$subTotalWithDiscount = $subTotalWithDiscount + ($item->getPrice() * $item->getQty());
						}
					}
					
				}
				//$quote->setTotalsCollectedFlag(false)->collectTotals();
				//$quote->setTotalsCollectedFlag(false)->collectTotals();
				//$quote->setTotalsCollectedFlag(false);				
				//$quote->setSubtotal(1050);
				//$quote->setBaseSubtotal(1050);
				
				
				$quote->setSubtotalWithDiscount($subTotalWithDiscount);
				$quote->setBaseSubtotalWithDiscount($subTotalWithDiscount);	
				
				$quote->setGrandTotal(($subTotalWithDiscount + $extraAmount));
				$quote->setBaseGrandTotal(($subTotalWithDiscount + $extraAmount));
				$quote ->save();
				//echo ($subTotalWithDiscount + $extraAmount);exit;
				//$quote=$observer->getEvent()->getQuote();	
				//echo '<pre>';
				//print_r($quote->getData()); exit;
			
		}
 public function updateProductName(Varien_Event_Observer $observer)
    {
	
	$product = $observer->getProduct();
	$productId = $product->getId();
	
//$_SESSION[$currentProductId ]['model'] = $model;
//$_SESSION[$currentProductId ]['brand'] = $brand;
//$_SESSION[$currentProductId ]['type'] = $type;
//$_SESSION[$currentProductId ]['category_id'] = $category_id;
		
		
		$connection = Mage::getSingleton('core/resource')->getConnection('core_read');
		$write = Mage::getSingleton('core/resource')->getConnection('core_write');
		$read = Mage::getSingleton('core/resource')->getConnection('core_read');

		//$model = Mage::getSingleton('core/session')->getModel();
		//$brand = Mage::getSingleton('core/session')->getBrand();
		//$type = Mage::getSingleton('core/session')->getType();
		//$category_id = Mage::getSingleton('core/session')->getCid();
		
		
		$getPrdetails = Mage::getSingleton('core/session')->getPrdetails();
		//echo '<pre>';
		//print_r($getPrdetails);
		
		$option_array = $_POST['options'];
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
			break;
			
		     }			
		  }
		}
		
		 
		$_category = Mage::getModel('catalog/category')->load($category_id);
		$categoryUrl_key = $_category->getUrl_key();
		
			//echo $item->getName();
		//$sessionId = Mage::getSingleton("core/session")->getEncryptedSessionId();		 
		$query = 'SELECT * FROM sales_flat_quote_item order by item_id desc LIMIT 1';
		$itemDetails = $read->fetchRow($query);
		
		if($model!='')
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



		
		$saveUrlKey = $modelKey .'/'.$categoryUrl_key;
		
		$query = "update sales_flat_quote_item set nameSuffix = '".addslashes($newName)."',prdUrl = '".$saveUrlKey."',prdType = '".$modelKey."' where item_id = '".$item_id."'";
		$write->query($query);
		//echo $item->getId();exit; 		
		
	}
	
	
	
	
 public function updateProductsName(Varien_Event_Observer $observer)
    {
	
	
	
	$quote=Mage::getSingleton('checkout/session')->getQuote();
	$quoteid=$quote->getId();	

		$connection = Mage::getSingleton('core/resource')->getConnection('core_read');
		$write = Mage::getSingleton('core/resource')->getConnection('core_write');	
		$read = Mage::getSingleton('core/resource')->getConnection('core_read');

	
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
			$prdURL = $itemDetails['prdURL'];
			
			
			
			$query = "update sales_flat_quote_item set name = '".addslashes($newName)."' where item_id = '".$item_id."'";
			$write->query($query);	
		   if($parent_item_id > 0){	
			$query = "update sales_flat_quote_item set name = '".addslashes($newName)."',nameSuffix='".$nameSuffix."',prdURL='".$prdURL ."' where item_id = '".$parent_item_id."'";
			$write->query($query);	
			}	
		}
		
				
		//if(trim($type) == 'Mobile Case')
		//$modelKey = 'mobile-case';
		
		//$saveUrlKey = $modelKey .'/'.$categoryUrl_key;
		
		//echo $item->getId();exit; 		
		
	}
	
	
	
	
		
}