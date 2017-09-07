<?php 
ini_set('display_errors', 1);
ini_set("memory_limit","1500M");
ini_set('max_execution_time', 18000);
//ini_set('display_errors', 0);
error_reporting(E_ALL);
include_once('app/Mage.php');
umask(0);
Mage::app('');

Mage::setIsDeveloperMode(true);

$read = Mage::getSingleton( 'core/resource' )->getConnection( 'core_read' ); // To read from the database
$write = Mage::getSingleton( 'core/resource' )->getConnection( 'core_write' ); // To write to the database


$collection  = Mage::getResourceModel('catalog/product_collection')->addAttributeToSelect('*'); 		

$prdSku = array();

$prdCount = 1;

		foreach ($collection as $_product)
		{
		  $catArr = $_product->getCategoryIds();
		  if(!in_array(17,$catArr))
		   continue;
	   
		  echo $_product->getSku(); echo '<br/>';	
		       
			   $product_id = Mage::getModel('catalog/product')->getResource()->getIdBySku($_product->getSku());
						
			   $product = Mage::getModel('catalog/product')->load($product_id);

			   $values  = array();
			   
			/******************* Code for update custom option price ********************************/
			/*
			   foreach ($product->getOptions() as $o) {

				   $p = $o->getValues();
					 
				   foreach($p as $v) {
					if ($v->getTitle() == 'Back') {
						
						echo $v->getId();echo '<br/>';
						
					   $write->query("UPDATE `catalog_product_option_type_price` SET `price` = '-91.0000' WHERE `catalog_product_option_type_price`.`option_type_id` = '".$v->getId()."'");
					   
					}
				  }
				}	
		 	*/
			/******************* End Code for update custom option price ********************************/
			
			
			$product->setPrice('699');
			$product->setSpecialPrice('490');
			$product->save();
			
			exit;
		  $prdCount++;
		}

?>