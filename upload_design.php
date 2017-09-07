<?php
ini_set("memory_limit","1500M");
ini_set('max_execution_time', 18000);
error_reporting(E_ALL);
include_once('app/Mage.php');
Mage::app();

$read = Mage::getSingleton( 'core/resource' )->getConnection( 'core_read' ); // To read from the database
$write = Mage::getSingleton( 'core/resource' )->getConnection( 'core_write' ); // To write to the database

// delete category from asus

/*Mage::register("isSecureArea", 1);
for($i=1050;$i<1980;$i++)
{		
	Mage::getModel("catalog/category")->load($i)->delete();
	echo $i." delete";
	echo '<br>';
}*/

/*$cat_id = 3; // a category id that you can get from admin
//$category = Mage::getModel('catalog/category')->load($categoryId);

$category = Mage::getModel('catalog/category')->load($cat_id); //put your category id here
$child = $category->getChildren();
if($child != '')
{
	$children = explode(",", $child);
	for($i=0;$i<count($children);$i++)
	{
		$sub_cat = Mage::getModel('catalog/category')->load($children[$i]);
		//print_r($sub_cat);die;
		echo '<strong>'.$sub_cat->getName().'</strong>';
		echo '<br>';
		$sub_cat_child = $sub_cat->getChildren();
		if($sub_cat_child != '')
		{
			$sub_cat_children = explode(",", $sub_cat_child);
			for($j=0;$j<count($sub_cat_children);$j++)
			{
				$sub_sub_cat = Mage::getModel('catalog/category')->load($sub_cat_children[$j]);
				echo $sub_sub_cat->getName();
				echo '<br>';
			}
		}
	}
}*/


//For case

/*$categoryIds = array (66);		
 for($cat=0;$cat<count($categoryIds);$cat++)
 { 
    $prod_collection = Mage::getModel('catalog/category')->load($categoryIds[$cat]); //put your category id here
	$collection  = $prod_collection->getProductCollection()->addAttributeToSelect('*'); 
	//$prod_id = array(); 	
	foreach($collection as $product) 
	{
		
		$prod_id = $product->getId();
		$select = $read->select()->from('catalog_product_entity_media_gallery', array('value_id'))->where('entity_id = '.$prod_id.' and attribute_id = 88');
		$rowArray = $read->fetchRow($select); 
		$val_id = $rowArray['value_id'];
		echo $val_id."<br>"; 
		if(isset($val_id) && $val_id != '')
		{
			$select_val = $read->select()->from('catalog_product_entity_media_gallery_value', array('design_area'))->where('value_id = '.$val_id);
			$select_val_row = $read->fetchRow($select_val); 
			$design_val = $select_val_row['design_area'];
			
				$write->beginTransaction();
				$fields = array();
				$fields['color'] = 0;
				$fields['design_area'] = '{"t":200,"l":200,"w":400,"h":400,"image_id":"'.$val_id.'","ip":"0","form_key":"2uwpsDrIIT3bTLkB"}';
				$where = $write->quoteInto('value_id =?', $val_id);
				$write->update('catalog_product_entity_media_gallery_value', $fields, $where);
				$write->commit();
			
		}
	}
	
	echo $categoryIds[$cat].' Complete';
	echo '<br>';
}*/

// For Skin
/*$categoryIds = array(75);		
for($cat=0;$cat<count($categoryIds);$cat++)
{ 
    $prod_collection = Mage::getModel('catalog/category')->load($categoryIds[$cat]);
	$collection  = $prod_collection->getProductCollection()->addAttributeToSelect('*'); 
	//$prod_id = array(); 	
	foreach($collection as $product) 
	{
		$prod_id = $product->getId();
		$select = $read->select()->from('catalog_product_entity_media_gallery', array('value_id'))->where('entity_id = '.$prod_id.' and attribute_id = 88');
		$rowArray = $read->fetchAll($select); 
		for($j=0;$j<count($rowArray);$j++)
		{
			$val_id = $rowArray[$j]['value_id'];
			if(isset($val_id) && $val_id != '')
			{
				$select_val = $read->select()->from('catalog_product_entity_media_gallery_value', array('design_area', 'label'))->where('value_id = '.$val_id);
				$select_val_row = $read->fetchRow($select_val); 
				$design_val = $select_val_row['design_area'];
				$label = $select_val_row['label'];
				if($select_val_row['label'] == 'Front')
				{
					$write->beginTransaction();
					$fields = array();
					$fields['color'] = 0;
					$fields['design_area'] = '{"t":200,"l":200,"w":400,"h":400,"image_id":"'.$val_id.'","ip":"0","form_key":"2uwpsDrIIT3bTLkB"}';
											
					$where = $write->quoteInto('value_id =?', $val_id);
					$write->update('catalog_product_entity_media_gallery_value', $fields, $where);
					$write->commit();
				}
				else
				{
					$write->beginTransaction();
					$fields = array();
					$fields['color'] = 0;
					$fields['design_area'] = '{"t":200,"l":200,"w":400,"h":400,"image_id":"'.$val_id.'","ip":"0","form_key":"2uwpsDrIIT3bTLkB"}';
					$where = $write->quoteInto('value_id =?', $val_id);
					$write->update('catalog_product_entity_media_gallery_value', $fields, $where);
					$write->commit();
				}			
			}	
		}		
	}
	echo $categoryIds[$cat].' Complete';
	echo '<br>';
}*/


//for product new

	
	/*for($i=85726;$i<=85832;$i++) 
	{
		$prod_id = $i;
		//$_product = Mage::getModel('catalog/product')->load($prod_id);		
		$select = $read->select()->from('catalog_product_entity_media_gallery', array('value_id'))->where('entity_id = '.$prod_id.' and attribute_id = 88');
		$rowArray = $read->fetchAll($select);
		for($j=0;$j<count($rowArray);$j++)
		{
			$val_id = $rowArray[$j]['value_id'];
			if(isset($val_id) && $val_id != '')
			{
				$select_val = $read->select()->from('catalog_product_entity_media_gallery_value', array('design_area', 'label'))->where('value_id = '.$val_id);
				$select_val_row = $read->fetchRow($select_val); 
				$design_val = $select_val_row['design_area'];
				$label = $select_val_row['label'];
				if($select_val_row['label'] == 'Front')
				{
					$write->beginTransaction();
					$fields = array();
					$fields['color'] = 0;
					$fields['design_area'] = '{"t":200,"l":200,"w":400,"h":400,"image_id":"'.$val_id.'","ip":"0","form_key":"86ikaMyr447QvQl6"}';												
					$where = $write->quoteInto('value_id =?', $val_id);
					$write->update('catalog_product_entity_media_gallery_value', $fields, $where);
					$write->commit();
				}
				else
				{
					$write->beginTransaction();
					$fields = array();
					$fields['label'] = 'Back';
					$fields['position'] = 2;
					$fields['color'] = 0;
					$fields['design_area'] = '{"t":200,"l":200,"w":400,"h":400,"image_id":"'.$val_id.'","ip":"0","form_key":"86ikaMyr447QvQl6"}';
					$where = $write->quoteInto('value_id =?', $val_id);
					$write->update('catalog_product_entity_media_gallery_value', $fields, $where);
					$write->commit();
				}
				echo $i.' Complete';
				echo '<br>';				
			}	
		}
	}*/
		
		
		
		
		$categoryIds = array(214);		
		for($cat=0;$cat<count($categoryIds);$cat++)
		{ 
		    $prod_collection = Mage::getModel('catalog/category')->load($categoryIds[$cat]);
			$collection  = $prod_collection->getProductCollection()->addAttributeToSelect('*'); 			
			foreach($collection as $product) 
			{
				$prod_id = $product->getId();
				$select = $read->select()->from('catalog_product_entity_media_gallery', array('value_id'))->where('entity_id = '.$prod_id.' and attribute_id = 88');
				$rowArray = $read->fetchRow($select); 
				$val_id = $rowArray['value_id'];
				if($rowArray['value_id'] != '')
				{
					$select_val = $read->select()->from('catalog_product_entity_media_gallery_value', array('design_area'))->where('value_id = '.$val_id);
					$select_val_row = $read->fetchRow($select_val); 
					$design_val = $select_val_row['design_area'];			
					
					$write->beginTransaction();
					$fields = array();
					$fields['label'] = 'Front';
					$fields['position'] = 1;
					$fields['color'] = 0;
					$fields['design_area'] = '{"t":200,"l":200,"w":400,"h":400,"image_id":"'.$val_id.'","ip":"0","form_key":"mx5ku8gJggVMtBYC"}';																			
					$where = $write->quoteInto('value_id =?', $val_id);
					$write->update('catalog_product_entity_media_gallery_value', $fields, $where);
					$write->commit();
					echo $i.' Complete';
					echo '<br>';					
				}
			}
		}				

	

 
 
 
 
 	
	/*for($i=113133;$i<=113904;$i++) 
	{
		$prod_id = $i;
		$_product = Mage::getModel('catalog/product')->load($prod_id);
		$ids = $_product->getCategoryIds();
		$categoryId = (isset($ids[0]) ? $ids[0] : null);
		$parentCatName = Mage::getModel('catalog/category')->load($categoryId)->getParentCategory()->getName();
		
		if($parentCatName == 'Tablet Skin')
		{
			$select = $read->select()->from('catalog_product_entity_media_gallery', array('value_id'))->where('entity_id = '.$prod_id.' and attribute_id = 88');
			$rowArray = $read->fetchAll($select);
			for($j=0;$j<count($rowArray);$j++)
			{
				$val_id = $rowArray[$j]['value_id'];
				if(isset($val_id) && $val_id != '')
				{
					$select_val = $read->select()->from('catalog_product_entity_media_gallery_value', array('design_area', 'label'))->where('value_id = '.$val_id);
					$select_val_row = $read->fetchRow($select_val); 
					$design_val = $select_val_row['design_area'];
					$label = $select_val_row['label'];
					if($select_val_row['label'] == 'Front')
					{
						$write->beginTransaction();
						$fields = array();
						$fields['color'] = 0;
						$fields['design_area'] = '{"t":200,"l":199,"w":370,"h":247,"image_id":"'.$val_id.'","ip":"0","form_key":"dmVOBg7qvcM52i9o"}';												
						$where = $write->quoteInto('value_id =?', $val_id);
						$write->update('catalog_product_entity_media_gallery_value', $fields, $where);
						$write->commit();
					}
					else
					{
						$write->beginTransaction();
						$fields = array();
						$fields['label'] = 'Back';
						$fields['position'] = 2;
						$fields['color'] = 0;
						$fields['design_area'] = '{"t":200,"l":199,"w":370,"h":247,"image_id":"'.$val_id.'","ip":"0","form_key":"dmVOBg7qvcM52i9o"}';
						$where = $write->quoteInto('value_id =?', $val_id);
						$write->update('catalog_product_entity_media_gallery_value', $fields, $where);
						$write->commit();
					}				
				}	
			}
		}
	}
	echo $categoryIds[$cat].' Complete';
	echo '<br>';*/

?>