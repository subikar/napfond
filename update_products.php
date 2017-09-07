<?php
require_once('app/Mage.php');
umask(0);
Mage::app('admin');
 
error_reporting(1);
set_time_limit(0);
ini_set('memory_limit', '2048M');
 
//  COPY Products From => To
//  5 => 10
/*$from = 201471;
$to = 201474;
 
$category = Mage::getModel('catalog/category')->load($from);
 
$productCollection = $category->setStoreId(34)->getProductCollection();
 
foreach($productCollection as $_product) {
  $product = Mage::getModel('catalog/product')->load($_product->getId());
 
  $newCategories = $origCats = $product->getCategoryIds();
  if(!in_array($to, $origCats)) {
    $newCategories = array_merge($origCats, array($to));
    $product->setCategoryIds($newCategories)->save();
    echo 'Assigned -- ' . $product->getId() . '<br />';
  }
}
echo 'Completed Execution!!!';
*/
$categoryId = 34; 
$category = Mage::getModel('catalog/category')->setStoreId(Mage_Core_Model_App::ADMIN_STORE_ID)->load($categoryId);
//get the current products
$products = $category->getProductsPosition();
//print_r($products);exit;
//now attach the other products.
$newProductIds = array(201470,201471,201472,201473,201474);
foreach ($newProductIds as $id){
    $products[$id] = 1;//you can put any other position number instead of 1.
}
//attach all the products to the category
$category->setPostedProducts($products);
//save the category.
$category->save();
?>