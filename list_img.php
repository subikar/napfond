<?php session_start();
include_once('app/Mage.php');
Mage::app();
$pathinfo = (pathinfo($this->helper('catalog/image')->init($_product, 'small_image')->constrainOnly(true)->keepAspectRatio(true)->keepFrame(false)->resize(400, null)));

$dirpath = str_replace(Mage::getStoreConfig(Mage_Core_Model_Url::XML_PATH_SECURE_URL),'',$pathinfo['dirname']);
$exclude_front_source = Mage::getBaseDir().'/'.$dirpath.$pathinfo['basename'];

$exclude_front_source_size =  getimagesize($exclude_front_source);

	  //$exclude_front_source =  Mage::getBaseDir().'/media/gomage/productdesigner/designs/catalog/product/'.basename($rows[$ii]['image']);
		


		
		    /*$mask1 = imagecreatefrompng($exclude_front);
			$this->imagealphamask($exclude_front_source, $mask1);		
			imagepng($exclude_front_source, Mage::getBaseDir().'/media/gomage/productdesigner/designs/catalog/product'.$rows[0]['image']); */	
			
			
 $image = imagecreatefromjpeg($exclude_front_source);

 $frame = imagecreatefrompng($blank_backImagePath);

 imagecopyresampled($image, $frame, 0, 0, 0, 0, $exclude_front_source_size[0], $exclude_front_source_size[1], $exclude_front_source_size[0], $exclude_front_source_size[1]);
 $white = imagecolorallocate($image, 255, 255, 255);
imagecolortransparent($image, $white);


 imagejpeg($image);
?>