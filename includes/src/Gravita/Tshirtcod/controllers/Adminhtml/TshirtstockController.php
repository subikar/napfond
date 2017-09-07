<?php
class Gravita_Tshirtcod_Adminhtml_TshirtstockController extends Mage_Adminhtml_Controller_Action
{
	public function indexAction()
    {

		$read = Mage::helper('function/more')->getDbReadObject();
		$write = Mage::helper('function/more')->getDbWriteObject();
				$scurl = curl_init();
				curl_setopt_array($scurl, array(
					CURLOPT_RETURNTRANSFER => 1,
					CURLOPT_URL => 'http://23.251.150.147/api/merchant/stock.php',
					CURLOPT_USERAGENT => 'Codular Sample cURL Request',
					CURLOPT_POST => 1,
					CURLOPT_POSTFIELDS => array(
						'api_key' => 'XFnq6pdVNYCjZwyJ'
					)
				));
				$sresp = curl_exec($scurl);
				curl_close($scurl);		
				$sxml = simplexml_load_string($sresp);
		//echo '<pre>';
		//print_r($sxml);
		//exit;


					foreach($sxml as $data)
					{
					  $style_id = $data->style_id;
					  if(!in_array($style_id,$style_id_array))
					   $style_id_array[] = $style_id;
					  else
						 continue; 
						foreach($data->style_color as $color)
						{
						  $color_name = addslashes($color->style_color_name);
						  foreach($color->style_size as $size)
						  {
							$size_name = addslashes($size->size_name);
							$stock = $size->size_stock;
							$inventory_array["$style_id"]["$color_name"]["$size_name"] = array("$stock");       
						  }     
						}   
					}  

					foreach($style_id_array as $style_id_array_val)
					{
					   //echo $style_id_array_val;
					   //echo '<br/>';
					   
					  $getStyleCode = $read->fetchOne("select designId from a_create_tshirt_inventory where designId = '".$style_id_array_val."'");
					  if($getStyleCode != '') 
					  $write->query("update a_create_tshirt_inventory set inVentory = '".base64_encode(serialize($inventory_array["$style_id_array_val"]))."' where designId = '".$style_id_array_val."'");
					  else
					  $write->query("insert into a_create_tshirt_inventory set inVentory = '".base64_encode(serialize($inventory_array["$style_id_array_val"]))."', designId = '".$style_id_array_val."'");		
					}

		
	$this->loadLayout()->renderLayout();
	
    } 
	

}