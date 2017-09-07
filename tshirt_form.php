<?php
ini_set("memory_limit","1500M");
ini_set('max_execution_time', 18000);
include_once('app/Mage.php');
Mage::app();
$read = Mage::getSingleton( 'core/resource' )->getConnection( 'core_read' );
$write = Mage::getSingleton( 'core/resource' )->getConnection( 'core_write' );


$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => 'http://23.251.150.147/api_test/merchant/stock.php',
    CURLOPT_USERAGENT => 'Codular Sample cURL Request',
    CURLOPT_POST => 1,
    CURLOPT_POSTFIELDS => array(
        'api_key' => 'CM6NJKcmB2d4RTG8'
    )
));
$resp = curl_exec($curl);
curl_close($curl);
$xml = simplexml_load_string($resp);
echo '<pre>';
//print_r($xml);die;

foreach($xml as $data)
{
	$style_id = $data->style_id;
	if($style_id == '99-RF-100112-190/SJ')
	{
		foreach($data->style_color as $color)
		{
			$color_name = $color->style_color_name;
			foreach($color->style_size as $size)
			{
				$size_name = $size->size_name;
				$stock = $size->size_stock;
				$men_val["$color_name"]["$size_name"] = array("$stock");				
			}			
		}
	}
	if($style_id == '99-RF-200112-190/SJ')
	{
		foreach($data->style_color as $color)
		{
			$color_name = $color->style_color_name;
			foreach($color->style_size as $size)
			{
				$size_name = $size->size_name;
				$stock = $size->size_stock;
				$women_val["$color_name"]["$size_name"] = array("$stock");				
			}			
		}
	}
	if($style_id == '99-300112-190/SJ')
	{
		foreach($data->style_color as $color)
		{
			$color_name = $color->style_color_name;
			foreach($color->style_size as $size)
			{
				$size_name = $size->size_name;
				$stock = $size->size_stock;
				$kid_val["$color_name"]["$size_name"] = array("$stock");				
			}			
		}
	}
}
// Update men stock
$prod_collection = Mage::getModel('catalog/category')->load(39);
$collection  = $prod_collection->getProductCollection()->addAttributeToSelect('*');
foreach($collection as $product) 
{
	if($product->isConfigurable())
	{
		$prod_id = $product->getId();
		$write->query("INSERT INTO `catalog_product_entity_varchar` (`entity_type_id`, `attribute_id`, `store_id`, `entity_id`, `value`) VALUES (4, 171, 0, '".$prod_id."', '99-RF-100112-190/SJ')");	
		$conf = Mage::getModel('catalog/product_type_configurable')->setProduct($product);
		$simple_collection = $conf->getUsedProductCollection()->addAttributeToSelect('*')->addFilterByRequiredOptions();
		foreach($simple_collection as $simple_product)
		{
			$id = $simple_product->getId();
			$prod_color = get_val($simple_product->getColor());
			$prod_size = get_val($simple_product->getSize());
			//if($prod_color == 'Gray'){$prod_color = 'Grey Melange';}
			$main_stock = $men_val[$prod_color][$prod_size][0];
			$write->query("Update `cataloginventory_stock_item` SET `qty` = '".number_format($main_stock, 2)."' WHERE `product_id` = '".$id."'");
			$write->query("Update `cataloginventory_stock_status` SET `qty` = '".number_format($main_stock, 2)."' WHERE `product_id` = '".$id."'");
			$write->query("Update `cataloginventory_stock_status_idx` SET `qty` = '".number_format($main_stock, 2)."' WHERE `product_id` = '".$id."'");
			//echo '<br>';			
		}
		// query for insert style code
		//$write->query("UPDATE `catalog_product_entity_varchar` SET `value` = '99-RF-100112-190/SJ' WHERE `attribute_id` = 171 AND `entity_id` = '".$prod_id."'");	
		//$write->query("INSERT INTO `catalog_product_entity_varchar` (`entity_type_id`, `attribute_id`, `store_id`, `entity_id`, `value`) VALUES (4, 171, 0, '".$prod_id."', '99-RF-100112-190/SJ')");
	}
}

// Update women stock
$prod_collection = Mage::getModel('catalog/category')->load(292);
$collection  = $prod_collection->getProductCollection()->addAttributeToSelect('*');
foreach($collection as $product) 
{
	if($product->isConfigurable())
	{
		$prod_id = $product->getId();
		$write->query("INSERT INTO `catalog_product_entity_varchar` (`entity_type_id`, `attribute_id`, `store_id`, `entity_id`, `value`) VALUES (4, 171, 0, '".$prod_id."', '99-RF-200112-190/SJ')");			
		$conf = Mage::getModel('catalog/product_type_configurable')->setProduct($product);
		$simple_collection = $conf->getUsedProductCollection()->addAttributeToSelect('*')->addFilterByRequiredOptions();
		foreach($simple_collection as $simple_product)
		{
			$id = $simple_product->getId();
			$prod_color = get_val($simple_product->getColor());
			$prod_size = get_val($simple_product->getSize());
			//if($prod_color == 'Gray'){$prod_color = 'Grey Melange';}
			$main_stock = $women_val[$prod_color][$prod_size][0];
			$write->query("Update `cataloginventory_stock_item` SET `qty` = '".number_format($main_stock, 2)."' WHERE `product_id` = '".$id."'");
			$write->query("Update `cataloginventory_stock_status` SET `qty` = '".number_format($main_stock, 2)."' WHERE `product_id` = '".$id."'");
			$write->query("Update `cataloginventory_stock_status_idx` SET `qty` = '".number_format($main_stock, 2)."' WHERE `product_id` = '".$id."'");
			//echo '<br>';
		}	
		// query for insert style code
		//$write->query("UPDATE `catalog_product_entity_varchar` SET `value` = '99-RF-200112-190/SJ' WHERE `attribute_id` = 171 AND `entity_id` = '".$prod_id."'");
		//$write->query("INSERT INTO `catalog_product_entity_varchar` (`entity_type_id`, `attribute_id`, `store_id`, `entity_id`, `value`) VALUES (4, 171, 0, '".$prod_id."', '99-RF-200112-190/SJ')");		
	}
}

// Update kid stock
$prod_collection = Mage::getModel('catalog/category')->load(295);
$collection  = $prod_collection->getProductCollection()->addAttributeToSelect('*');
foreach($collection as $product) 
{
	if($product->isConfigurable())
	{
		$prod_id = $product->getId();
		$write->query("INSERT INTO `catalog_product_entity_varchar` (`entity_type_id`, `attribute_id`, `store_id`, `entity_id`, `value`) VALUES (4, 171, 0, '".$prod_id."', '99-300112-190/SJ')");	
		$conf = Mage::getModel('catalog/product_type_configurable')->setProduct($product);
		$simple_collection = $conf->getUsedProductCollection()->addAttributeToSelect('*')->addFilterByRequiredOptions();
		foreach($simple_collection as $simple_product)
		{
			$id = $simple_product->getId();
			$prod_color = get_val($simple_product->getColor());
			$prod_size = get_val($simple_product->getSize());
			//if($prod_color == 'Gray'){$prod_color = 'Grey Melange';}
			$main_stock = $kid_val[$prod_color][$prod_size][0];
			$write->query("Update `cataloginventory_stock_item` SET `qty` = '".number_format($main_stock, 2)."' WHERE `product_id` = '".$id."'");
			$write->query("Update `cataloginventory_stock_status` SET `qty` = '".number_format($main_stock, 2)."' WHERE `product_id` = '".$id."'");
			$write->query("Update `cataloginventory_stock_status_idx` SET `qty` = '".number_format($main_stock, 2)."' WHERE `product_id` = '".$id."'");
			//echo '<br>';			
		}	
		// query for insert style code
		//$write->query("UPDATE `catalog_product_entity_varchar` SET `value` = '99-300112-190/SJ' WHERE `attribute_id` = 171 AND `entity_id` = '".$prod_id."'");
		//$write->query("INSERT INTO `catalog_product_entity_varchar` (`entity_type_id`, `attribute_id`, `store_id`, `entity_id`, `value`) VALUES (4, 171, 0, '".$prod_id."', '99-300112-190/SJ')");
	}
}
// get color and size name from option id
function get_val($val)
{
	$read = Mage::getSingleton( 'core/resource' )->getConnection( 'core_read' );
	$qry = $read->query("SELECT `value` FROM `eav_attribute_option_value` WHERE `option_id` = '".$val."'");
	$row = $qry->fetch();
	return $row['value'];
}

?>