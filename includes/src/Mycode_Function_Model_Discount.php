<?php 

class Mycode_Function_Model_Discount extends Mage_Sales_Model_Quote_Address_Total_Abstract {



    public function collect(Mage_Sales_Model_Quote_Address $address) {
            if ($address->getData('address_type') == 'billing')
                return $this;
 
 
			$mcode = Mage::getSingleton('checkout/session')
				->getQuote()
				->getPayment()
				->getMethodInstance()
				->getTitle();

			//echo Mage::getStoreConfig('payment/' . $mcode . '/title'); 
			
			if($mcode == 'payucheckout_shared')
			{
				$discount = 5; //your discount percent
                
                $grandTotal = $address->getGrandTotal();
                $baseGrandTotal = $address->getBaseGrandTotal();

                $totals = array_sum($address->getAllTotalAmounts());
                $baseTotals = array_sum($address->getAllBaseTotalAmounts());

                $address->setFeeAmount(-$totals * $discount / 100);
                $address->setBaseFeeAmount(-$baseTotals * $discount / 100);

                $address->setGrandTotal($grandTotal + $address->getFeeAmount());
                $address->setBaseGrandTotal($baseGrandTotal + $address->getBaseFeeAmount());
			}	
        return $this;
    }

    public function fetch(Mage_Sales_Model_Quote_Address $address) {
        
            if ($address->getData('address_type') == 'billing')
                return $this;

            $amt = $address->getDiscountAmount();
            if ($amt != 0) {
                
                
                $address->addTotal(array(
                    'code' => 'Discount',
                    'title' => 'Discount',
                    'value' => $amt
                ));
            }
        
        return $address;
    }


}