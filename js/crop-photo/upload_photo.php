<?php
/*
* Author : Ali Aboussebaba
* Email : bewebdeveloper@gmail.com
* Website : http://www.bewebdeveloper.com
* Subject : Crop photo using PHP and jQuery
*/
$base_url = 'http://localhost/crop-photo/';
$base_dir = $_SERVER["DOCUMENT_ROOT"].'/crop-photo/';
// get the tmp url
$photo_src = $_FILES['photo']['tmp_name'];
// test if the photo realy exists
if (is_file($photo_src)) {
	// photo path in our example
	$photo_dest = 'photo_'.time().'.jpg';
	
	$photo_url = $base_url.'images/'.$photo_dest;
	
	
	// copy the photo from the tmp path to our path
	copy($photo_src, $base_dir.'images/'.$photo_dest);
	// call the show_popup_crop function in JavaScript to display the crop popup
	echo '<script type="text/javascript">window.top.window.show_popup_crop("'.$photo_url.'","'.$photo_dest.'","'.$_POST['current_popupid'].'")</script>';
}
?>