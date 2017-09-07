<?php
include_once('app/Mage.php');
Mage::app();
		$write = Mage::helper('function/more')->getDbWriteObject();	
		$read = Mage::helper('function/more')->getDbReadObject();

 $SKU = Mage::app()->getRequest()->getParam('skU');
 $ColoR = Mage::app()->getRequest()->getParam('colOr');
 $sizE = Mage::app()->getRequest()->getParam('siZe');

 //$SKU = 'ZSSSSSSSSSSSSS333333';
 
 $getInventory = $read->query("select count(*) as totalRec from  a_create_tshirt_inventory  where designId = '".$SKU."'");
 $inventory_details = $getInventory->fetchAll();
if($inventory_details[0]['totalRec'] == 0){
echo 'Not Required';
} 
else if(SKU != '')
{
$getInventory = $read->query("select * from  a_create_tshirt_inventory  where designId = '".$SKU."'");
$inventory_details = $getInventory->fetchAll();
$incentoRy = unserialize(base64_decode($inventory_details[0]['inVentory']));
/*
if(array_key_exists ( $ColoR , $incentoRy ))
echo ($incentoRy[$ColoR][$sizE][0]);
else
echo "0";*/
echo '50';
}

?>