<?php
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
        'api_key' => 'bDNymqvhpxKWg7fr'
    )
));
$resp = curl_exec($curl);
curl_close($curl);
$xml = simplexml_load_string($resp);
echo '<pre>';
//print_r($xml);die;

$style_id_array = array();
$inventory_array = array();

foreach($xml as $data)
{
	$style_id = '99-RF-100112-190/SJ';
	if($style_id == '99-RF-100112-190/SJ')
	{
		foreach($data->style_color as $color)
		{
			$color_name = addslashes($color->style_color_name);
			
			foreach($color->style_size as $size)
			{
				$size_name = addslashes($size->size_name);
				$stock = $size->size_stock;
				$inventory_array[$style_id]["$color_name"]["$size_name"] = array("$stock");
			}			
		}
	}
	if($style_id == '99-RF-200112-190/SJ')
	{
		foreach($data->style_color as $color)
		{
			$color_name = addslashes($color->style_color_name);
			foreach($color->style_size as $size)
			{
				$size_name = addslashes($size->size_name);
				$stock = $size->size_stock;
				$inventory_array[$style_id]["$color_name"]["$size_name"] = array("$stock");				
			}			
		}
	}
	if($style_id == '99-300112-190/SJ')
	{
		foreach($data->style_color as $color)
		{
			$color_name = addslashes($color->style_color_name);
			foreach($color->style_size as $size)
			{
				$size_name = addslashes($size->size_name);
				$stock = $size->size_stock;
				$inventory_array[$style_id]["$color_name"]["$size_name"] = array("$stock");				
			}			
		}
	}
	
	if(!in_array($style_id,$style_id_array))
	 $style_id_array[] = $style_id;
	
}

foreach($style_id_array as $style_id_array_val){

echo $style_id_array_val;
echo '<br/>';

$write->query("update a_create_tshirt_inventory set inVentory = '".base64_encode(serialize($inventory_array[$style_id_array_val]))."' where designId = '".$style_id_array_val."'");
}
//print_r($men_val);
//print_r($inventory_array);
?>