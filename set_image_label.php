<?php
$product = mage::getModel('catalog/product')->load(16);
$attributes = $product->getTypeInstance(true)->getSetAttributes($product);
$gallery = $attributes['media_gallery'];
$images = $product->getMediaGalleryImages();
foreach ($images as $image) {
    $backend = $gallery->getBackend();
    $backend->updateImage(
        $product,
        $image->getFile(),
        array('label' => 'Blah')
    );
}
$product->getResource()->saveAttribute($product, 'media_gallery');

?>