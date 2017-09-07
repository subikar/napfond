<?php set_time_limit(0);
require_once('app/Mage.php');
umask(0);
Mage::app();

$main_image = $_REQUEST['image'];
$mask = $_REQUEST['mask'];
			 
$exclude_front_source_size = getimagesize($main_image);
$image = imagecreatefromjpeg($main_image);
$frame = imagecreatefrompng($mask);
imagecopyresampled($image, $frame, 0, 0, 0, 0, $exclude_front_source_size[0], $exclude_front_source_size[1], $exclude_front_source_size[0], $exclude_front_source_size[1]);
$white = imagecolorallocate($image, 255, 255, 255);
imagecolortransparent($image, $white);
/*$pathinfo = pathinfo($main_image);
$saveIMageNmae = $pathinfo['filename'].'.jpg';*/
header( "Content-type: image/jpeg");
imagejpeg($image);				 
		 
/*$image = imagecreatefromjpeg('media/gomage/productdesigner/designs/catalog/product/LhJcXSDvGodcCETy.png');
$frame = imagecreatefrompng('media/catalog/product/cache/1/image/1000x1000/9df78eab33525d08d6e5fb8d27136e95/f/r/front-iphone6-skin_5.png');
imagecopyresampled($image, $frame, 0, 0, 0, 0, 1000, 1000, 1000, 1000);
$black = imagecolorallocate($image, 0, 0, 0);
imagecolortransparent($image, $black);
# Save the image to a file
//imagepng($image, '/path/to/save/image.png');
# Output straight to the browser.
header( "Content-type: image/png");
imagepng($image);*/			 
?>