<?php
include_once('app/Mage.php');
Mage::app();

$read = Mage::getSingleton( 'core/resource' )->getConnection( 'core_read' ); // To read from the database
$write = Mage::getSingleton( 'core/resource' )->getConnection( 'core_write' ); // To write to the database

//For case 
$categoryIds = array(229, 11, 30, 12, 13, 151, 152, 153, 230, 231, 357, 14, 15, 16, 232, 233, 234, 235, 236, 237, 238, 239, 240, 361, 186, 187, 241, 242, 243, 244, 188, 189, 190, 191, 245, 372, 373, 364, 366, 367, 368, 369, 370, 371, 374, 375, 376, 377, 381, 382, 383, 384, 385, 386, 378, 413, 414, 415, 417, 471, 418, 419);		
for($cat=0;$cat<count($categoryIds);$cat++)
{ 
    $prod_collection = Mage::getModel('catalog/category')->load($categoryIds[$cat]); //put your category id here
	$collection  = $prod_collection->getProductCollection()->addAttributeToSelect('*'); 
	foreach($collection as $product) 
	{
		$prod_id = $product->getId();
		// for type
		/*$select = $read->select()->from('catalog_product_entity_int', array('value_id', 'value'))->where('entity_id = '.$prod_id.' and attribute_id = 152');
		$rowArray = $read->fetchRow($select);		
		$val_id = $rowArray['value_id'];
		$value = $rowArray['value'];
		$arr = array(91, 92, 181);
		$rand = rand(0, 2);
		if(isset($val_id) && $val_id != '')
		{
			$write->beginTransaction();
			$fields = array();
			$fields['value'] = $arr[$rand];			
			$where = $write->quoteInto('value_id =?', $val_id);
			$write->update('catalog_product_entity_int', $fields, $where);
			$write->commit();
		}
		else
		{
			$write->beginTransaction();
			$fields = array();
			$fields['entity_type_id'] = 4;				
			$fields['attribute_id'] = 152;
			$fields['store_id'] = 0;
			$fields['entity_id'] = $prod_id;
			$fields['value'] = $arr[$rand];
			$write->insert('catalog_product_entity_int', $fields);
			$write->commit();
		}	*/
		// for type end
		
		// for desogner
		$select = $read->select()->from('catalog_product_entity_int', array('value_id', 'value'))->where('entity_id = '.$prod_id.' and attribute_id = 138');
		$rowArray = $read->fetchRow($select);		
		$val_id = $rowArray['value_id'];
		$value = $rowArray['value'];
		$arr = array(43, 44, 45, 47);
		$rand = rand(0, 3);
		if(isset($val_id) && $val_id != '')
		{
			$write->beginTransaction();
			$fields = array();
			$fields['value'] = $arr[$rand];			
			$where = $write->quoteInto('value_id =?', $val_id);
			$write->update('catalog_product_entity_int', $fields, $where);
			$write->commit();
		}
		else
		{
			$write->beginTransaction();
			$fields = array();
			$fields['entity_type_id'] = 4;				
			$fields['attribute_id'] = 138;
			$fields['store_id'] = 0;
			$fields['entity_id'] = $prod_id;
			$fields['value'] = $arr[$rand];
			$write->insert('catalog_product_entity_int', $fields);
			$write->commit();
		}
		// for desogner					
	}
	echo $categoryIds[$cat].' Complete';
	echo '<br>';
}
?>