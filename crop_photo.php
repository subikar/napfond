<?php include_once('app/Mage.php');
Mage::app();
$UploadedDir = $_POST['UploadedDir'];

if($_POST['photo_url_type']=='upload'){

$photo_url = $_POST['photo_url'];

$base_url = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB).'media/gomage/productdesigner/uploadedImage/'.$UploadedDir.'/';
$dbase_url = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB).'media/gomage/productdesigner/uploadedImage/'.$UploadedDir.'/';
$base_dir = Mage::getBaseDir('media').'/gomage/productdesigner/uploadedImage/'.$UploadedDir.'/';
$dbase_dir = Mage::getBaseDir('media').'/gomage/productdesigner/uploadedImage/'.$UploadedDir.'/';
}
else if($_POST['photo_url_type']=='stock'){

$photo_url_arr = explode('/',$_POST['photo_url']);
$photo_url = $photo_url_arr[1];

$base_url = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB).'media/stock/img-600/'.$photo_url_arr[0].'/';
$base_dir = Mage::getBaseDir('media').'/stock/img-600/'.$photo_url_arr[0].'/';

$dbase_url = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB).'media/gomage/productdesigner/uploadedImage/'.$UploadedDir.'/';
$dbase_dir = Mage::getBaseDir('media').'/gomage/productdesigner/uploadedImage/'.$UploadedDir.'/';
}
else if($_POST['photo_url_type']=='clipart'){
$photo_url = $_POST['photo_url'];
$str_split_arr = str_split($photo_url);
$base_url = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB).'media/stock/img-1000/'.$str_split_arr[0].'/'.$str_split_arr[1].'/';
$base_dir = Mage::getBaseDir('media').'/stock/img-1000/'.$str_split_arr[0].'/'.$str_split_arr[1].'/';
$dbase_url = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB).'media/gomage/productdesigner/uploadedImage/'.$UploadedDir.'/';
$dbase_dir = Mage::getBaseDir('media').'/gomage/productdesigner/uploadedImage/'.$UploadedDir.'/';
}
// Target siz
$targ_w = $_POST['targ_w'];
$targ_h = $_POST['targ_h'];


$jpeg_quality = 90;

$src = $base_dir.$photo_url;

$suffix = 1;
$Crop_file = $photo_url;

if(!is_dir($dbase_dir))
 {
    mkdir($dbase_dir);
 }

while(is_file($dbase_dir.$Crop_file))
 {
   $path_info = pathinfo($src);   
   $Crop_file = $path_info["filename"]."_".$suffix.".".$path_info["extension"];
   $suffix++;
 }

 $ndirsrc = $dbase_dir.$Crop_file;
 $nsrc = $dbase_url.$Crop_file;


$img_r = imagecreatefromjpeg($src);
$dst_r = ImageCreateTrueColor( $targ_w, $targ_h );

imagecopyresampled($dst_r,$img_r,0,0,$_POST['x'],$_POST['y'], $targ_w,$targ_h,$_POST['w'],$_POST['h']);

imagejpeg($dst_r,$ndirsrc,$jpeg_quality);

echo $nsrc;

exit;
?>