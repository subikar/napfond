<?php
include_once('app/Mage.php');
Mage::app();

$refererUrl = Mage::app()->getRequest()->getServer('HTTP_REFERER');


if(!strpos($refererUrl,$_SERVER['HTTP_HOST']))
exit;

$firstDirToRead = Mage::app()->getRequest()->getParam('cat_id');

$tshirtCustomizerPage = Mage::app()->getRequest()->getParam('tshirtCustomizerPage');

if($tshirtCustomizerPage == 'yes'){
$stockDirectory = 'stock-tshirt';
$styleDirectory = 'style_images_tshirt';
}
else{
$stockDirectory = 'stock';	
$styleDirectory = 'style_images';	
}

if($firstDirToRead != ''){
$media = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA);

$mediaDir = Mage::getBaseDir('media').'/'.$stockDirectory.'/img-100/';
						 
$imglist = array();


$file = fopen(Mage::getBaseDir()."/".$styleDirectory."/".$firstDirToRead.".csv","r");
$styleNameArray = '';
while(! feof($file))
  {
	$arr = fgetcsv($file);
	

	
	$styleNameArray = trim($arr[0]);	
	break;
  }

fclose($file);

$imglist = explode(',',$styleNameArray);



if(count($imglist) == 0)
 echo 'Images not available';

//display image
foreach($imglist as $image) {
$data_origin_url =  $media.''.$stockDirectory.'/img-600/'.$image; 
?>
<div class="image-ppc"><img class="clipart-image" src="<?php echo $media.''.$stockDirectory.'/img-100/'.$image?>" data-origin-url="<?php echo $data_origin_url;?>" onclick="parent.jQuery.fancybox.close();"></div>
<?php
}
}
?>