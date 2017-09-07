<?php session_start();
include_once('../../app/Mage.php');
Mage::app();

/**
 * Instagram PHP API
 *
 * @link https://github.com/cosenary/Instagram-PHP-API
 * @author Christian Metz
 * @since 01.10.2013
 */

require '../src/Instagram.php';
use MetzWeb\Instagram\Instagram;

// initialize class
$instagram = new Instagram(array(
  'apiKey'      => '36f371f423a545eba474a641dca534ec',
  'apiSecret'   => '7eedfb34966b4d4aa790b26cf937aa01',
  'apiCallback' => Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB).'Instagram_php_api/code/success.php' // must 
  ));

// receive OAuth code parameter
$code = $_GET['code'];
?>
<div id="fb-root"></div>
					 <div id="filter_loading_image" style="background-color: #ffffff;height: 80%;left: 0;opacity: 0.5;position: absolute;width: 100%;z-index: 555;padding-top: 1%; overflow:hidden;">
					 
						<strong id="filter_loading_image_msg">Please wait instagram login window is opening.</strong>
						<strong id="filter_loading_image_msg2" style="display:none;">Fetching your instagram photos.</strong>
						
						<img src="<?php echo Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_SKIN)?>frontend/rwd/default/images/opc-ajax-loader.gif" style="margin:0 auto"/>
					 </div>
<?php
if ($code) {
	//header("Location: index.php");exit;
	
	echo "<script type='text/javascript'>
	if (window.opener){
	
	 document.getElementById('filter_loading_image_msg').style.display = 'none';
	 document.getElementById('filter_loading_image_msg2').style.display = '';
	
	 
	}else{
	 
	 
	}
        </script>";
 
	
} 

// check whether the user has granted access
if (isset($code)) {

  // receive OAuth token object
  echo $data = $instagram->getOAuthToken($code);
  echo $username = $username = $data->user->username;

  // store user access token
  $instagram->setAccessToken($data);

  // now you have access to all authenticated user methods
  $result = $instagram->getUserMedia();
print_r($result->data);exit;
} else {

  // check whether an error occurred
  if (isset($_GET['error'])) {
    echo 'An error occurred: ' . $_GET['error_description'];
  }

}

$img_arr = array(); 
          // display all user likes
  foreach ($result->data as $media) {
            $content = "<li>";

            // output media
            if ($media->type === 'video') {
              // video
         
            } else {
              // image
			  
              $image = $media->images->standard_resolution->url;
              $img_arr[] = $image;
            }
 
          }
$_SESSION['bshm_inst_usr_imgs'] = $img_arr;
echo "      <script type='text/javascript'>
//window.opener.document.getElementById('filter_loading_image_msg').style.display = 'none';    
//window.opener.document.getElementById('filter_loading_image_msg2').style.display = ''; 
//alert('hello and that is website.');

window.opener.jQuery('.UploadPhotoDivCustomizerInsta').hide();
window.opener.instaFeedFunction();   
window.close();
//window.opener.location.href='images.php';
</script>";
?>