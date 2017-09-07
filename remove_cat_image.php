<?php set_time_limit(0);
require_once 'app/Mage.php';
Mage::app('admin');

//$categoryIdArr = array(225); // set your category ID here
 

foreach($categoryIdArr as $categoryId){

 
$products = Mage::getModel('catalog/product')
                ->getCollection()
                ->joinField('category_id', 'catalog/category_product', 'category_id', 'product_id = entity_id', null, 'left')
                ->addAttributeToFilter('category_id', array('eq' => $categoryId));

$mediaApi = Mage::getModel("catalog/product_attribute_media_api");

foreach(array_keys($products->getItems()) as $productId) {
    $items = $mediaApi->items($productId);
    if (count($items) > 0) { 
        foreach($items as $item) {
            $mediaApi->remove($productId, $item['file']);
			 
        }
        echo $productId . " done \n";
    } else { 
        echo $productId . " has no images \n";
    }
	
	 
	
}
}