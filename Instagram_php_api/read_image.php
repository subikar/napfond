<?php
/* require_once('../app/Mage.php');
umask(0);
Mage::app();*/
$bhishoom_img_url =  explode('bhishoom_img_url=',$_SERVER['QUERY_STRING']);

$bhishoom_img_url = $bhishoom_img_url[1];
/*
$ch = curl_init();
curl_setopt ($ch, CURLOPT_URL, $_GET['bhishoom_img_url']);
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
$data = curl_exec($ch);
curl_close($ch);

 $ext = pathinfo($_GET['bhishoom_img_url'], PATHINFO_EXTENSION);
 $ext = explode('?',$ext); 
 $type = strtolower($ext[0]);

  $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
 $fileName = 'temp.'.$type;
 file_put_contents($fileName, $data);
 */

 
 $remote_img = $bhishoom_img_url;
$img = imagecreatefromjpeg($remote_img);
$path = '';
imagejpeg($img);
 
?>