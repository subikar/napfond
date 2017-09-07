<?php
require_once("app/Mage.php");

Mage::app('default'); 

$pid = 199802;
$_product= Mage::getModel('catalog/product')->load($pid);

$coll = Mage::getResourceModel('salesrule/rule_collection')->addFieldToFilter('rule_id',array("in"=>array("2")))->load();



foreach($coll as $rule){
  $rule->afterLoad(); 
}  

 //$quoteId = Mage::getSingleton('checkout/session')->getQuoteId(); 
 //$real_quote = Mage::getSingleton('sales/quote')->load($quoteId);                
 //$product = Mage::getModel('sales/quote_item')->setQuote($real_quote)->setProduct($_product);  
 //$product->setAllItems(array($_product));                 
 //$product->getProduct()->setProductId($_product->getEntityId());   
$product = Mage::getModel('catalog/product')->load($pid); 
foreach($coll as $rule) 
{  

	//echo $rule->getId();
	//echo '<br/>';
    $applicable = (bool)$rule->getConditions()->validate($product); 
    var_dump($applicable);     
}   
exit; 
?>