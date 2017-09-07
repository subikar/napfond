<?php
class MageCoders_DefaultSearch_Model_Mysql4_Fulltext extends Mage_CatalogSearch_Model_Mysql4_Fulltext{
	
	public function prepareResult($object, $queryText, $query)
    {   
		 $adapter = $this->_getWriteAdapter();
		 
	     if ($query->getIsProcessed()) { return $this; }
		 $mainTable = $this->getTable('catalogsearch/result');
		 $queryText = mysql_escape_string(trim($queryText));	
		 $product = Mage::getModel('catalog/product')->loadByAttribute('sku',$queryText);	
		 if($product){
		 	$sql = "INSERT IGNORE INTO {$mainTable} SET query_id = '".$query->getId()."',
												 product_id = '".$product->getId()."',
												 relevance = '2'";
			$adapter->query($sql);									 
		 }else{
		 	$name = '%'.$queryText.'%';
		 	$collection = Mage::getResourceModel('catalog/product_collection')
								->addAttributeToFilter('name',array('like'=>$name));
								
			if($collection->count()){	
				foreach($collection as $item){
						$sql = "INSERT IGNORE INTO {$mainTable} SET query_id = '".$query->getId()."',
															 product_id = '".$item->getId()."',
															 relevance = '1'";
						$adapter->query($sql);									 
				}
			}					
		 }
		
    }  
	
}