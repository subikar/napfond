<?php
require_once 'app/Mage.php';

    Mage::app();
    Mage::app()->getStore()->setId(Mage_Core_Model_App::ADMIN_STORE_ID);
$productid=211515; // Product id of that product which image you want to update
//for remove existing Images
$loadpro=Mage::getModel('catalog/product')->load($productid);
$mediaApi = Mage::getModel("catalog/product_attribute_media_api");
$mediaApiItems = $mediaApi->items($loadpro->getId());
 
foreach($mediaApiItems as $item) {
    $datatemp=$mediaApi->remove($loadpro->getId(), $item['file']);
}
$loadpro->save(); //before adding need to save product
 
//for add new images
// $loadpro=Mage::getModel('catalog/product')->load($loadpro->getId());
// $loadpro->addImageToMediaGallery($loadpro, array ('image','small_image','thumbnail'), true, false);
?>