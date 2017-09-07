<?php
class Mycode_Function_Block_Sales_Creditmemo extends Mage_Sales_Block_Order_Creditmemo_Totals {

    protected function _initTotals() {
        parent::_initTotals();
        $amt = $this->getSource()->getFeeAmount();
        $baseAmt = $this->getSource()->getBaseFeeAmount();
        if ($amt != 0) {

            $this->addTotal(new Varien_Object(array(
                        'code' => 'Discount',
                        'value' => $amt,
                        'base_value' => $baseAmt,
                        'label' => 'Discount',
                    )), 'discount');
        }
        return $this;
    }
}
?>