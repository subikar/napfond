<?php session_start();
include_once('../../app/Mage.php');
Mage::app();

require '../src/Instagram.php';
use MetzWeb\Instagram\Instagram;

// initialize class
$instagram = new Instagram(array(
  'apiKey'      => '36f371f423a545eba474a641dca534ec',
  'apiSecret'   => '7eedfb34966b4d4aa790b26cf937aa01',
  'apiCallback' => Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB).'Instagram_php_api/code/success.php' // must point to success.php
));

// create login URL
$loginUrl = $instagram->getLoginUrl();
header("location:".$loginUrl);

?>

					 <div id="filter_loading_image" style="background-color: #ffffff;height: 80%;left: 0;opacity: 0.5;position: absolute;width: 100%;z-index: 555;padding-top: 1%; overflow:hidden;">
					 
						<strong id="filter_loading_image_msg">Please wait instagram login window is opening.</strong>
						<strong id="filter_loading_image_msg2" style="display:none;">Fetching your instagram photos.</strong>
						<input type="button" name="theButton" id="theButton" onclick='popupCenter("<?php echo htmlentities($loginUrl);?>", "myWindow", "800", "800");' value="Click Here to Login to Instagram"/>
						<img src="<?php echo Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_SKIN)?>frontend/rwd/default/images/opc-ajax-loader.gif" style="margin:0 auto"/>
					 </div>
<?php
echo "<script type='text/javascript'>
function PopupCenterDual(url, title, w, h) {
    // Fixes dual-screen position   Most browsers   Firefox
    var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : screen.left;
    var dualScreenTop = window.screenTop != undefined ? window.screenTop : screen.top;
    width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
    height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

    var left = ((width / 2) - (w / 2)) + dualScreenLeft;
    var top = ((height / 2) - (h / 2)) + dualScreenTop;
    var newWindow = window.open(url, title, 'scrollbars=yes, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);

    // Puts focus on the newWindow
    if (window.focus) {
        newWindow.focus();
    }
}


function popupCenter(url, title, w, h) {
  var left = (screen.width/2)-(w/2);
  var top = (screen.height/2)-(h/2);
  return window.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
} 

</script>
"; 



 echo '
 <script type="text/javascript">
 parent.jQuery("#theButton").click()
 //document.getElementById("theButton").click()
 //window.opener.document.getElementById("filter_loading_image_msg").innerHTML = "Please login to facebook.";
 
 </script>';
 exit;
/*
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instagram - OAuth Login</title>
    <link rel="stylesheet" type="text/css" href="assets/style.css">
    <style>
      .login {
        display: block;
        font-size: 20px;
        font-weight: bold;
        margin-top: 50px;
      }
    </style>
  </head>
  <body>
    <div class="container">
      <header class="clearfix">
        <h1>Instagram <span>display your photo stream</span></h1>
      </header>
      <div class="main">
        <ul class="grid">
          <li><img src="assets/instagram-big.png" alt="Instagram logo"></li>
          <li>
            <a class="login" href="<?php echo $loginUrl ?>">» Login with Instagram</a>
            <h4>Use your Instagram account to login.</h4>
          </li>
        </ul>
        <!-- GitHub project -->
        <footer>
          <p>created by <a href="https://github.com/cosenary/Instagram-PHP-API">cosenary's Instagram class</a>, available on GitHub</p>
        </footer>
      </div>
    </div>
  </body>
</html><?php */?>