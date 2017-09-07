<?php
/*
* Author : Ali Aboussebaba
* Email : bewebdeveloper@gmail.com
* Website : http://www.bewebdeveloper.com
* Subject : Crop photo using PHP and jQuery
*/
$base_url = 'http://localhost/crop-photo/';
$base_dir = $_SERVER["DOCUMENT_ROOT"].'/crop-photo/';
// Target siz
$targ_w = $_POST['targ_w'];
$targ_h = $_POST['targ_h'];
// quality
$jpeg_quality = 90;
// photo path
$src = $base_dir.'/images/'.$_POST['photo_url'];

$suffix = 1;
$Crop_file = $_POST['photo_url'];

while(is_file($base_dir.'/images/'.$Crop_file))
 {
   $path_info = pathinfo($src);   
   $Crop_file = $path_info["filename"]."_".$suffix.".".$path_info["extension"];
 }

$nsrc = $base_url.'images/'.$_POST['photo_url'];

// create new jpeg image based on the target sizes
$img_r = imagecreatefromjpeg($src);
$dst_r = ImageCreateTrueColor( $targ_w, $targ_h );
// crop photo
imagecopyresampled($dst_r,$img_r,0,0,$_POST['x'],$_POST['y'], $targ_w,$targ_h,$_POST['w'],$_POST['h']);
// create the physical photo
imagejpeg($dst_r,$src,$jpeg_quality);
// display the  photo - "?time()" to force refresh by the browser
echo '<img src="'.$nsrc.'?'.time().'" class="cropTplCustomizeImgClass">';
exit;
?>