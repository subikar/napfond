<?php session_start();
include_once('../app/Mage.php');
Mage::app();
 if($_SERVER['HTTP_HOST'] == ' dev.gravitainfotech.com:8282')
exit;
/*
require 'src/facebook.php';  // Include facebook SDK file
//require 'functions.php';  // Include functions
$facebook = new Facebook(array(
  'appId'  => '753880228035779',   // Facebook App ID 
  'secret' => '082370d776e5bd823c4d3cbf4bbe7e57',  // Facebook App Secret
  'cookie' => true 
));

       $user = $facebook->getUser(); 
	   */
  
  /*
  $albums = $facebook->api('/me/feed');
  print_r($albums);
foreach($albums['data'] as $album)  {
  print ('<a href="albumPhotos.php?album_id='.$album['id'].'">'.$album['name'].'</a>'.'</br>' ) ;
}
  */
  
 
  
 
if ($user) {$access_token = $facebook->getAccessToken();
//echo 'http://graph.facebook.com/'.$user.'/picture?type=large';exit;
/*$data = file_get_contents('http://graph.facebook.com/'.$user.'/picture?width=800&height=800');
 

$img_name = 'fb_profilepic'.rand(1,1092834765).'.jpg';

while(is_file("uploads/".$img_name))
 {
   $img_name = 'fb_profilepic'.rand(1,1092834765).'.jpg';
 }

$file = fopen("uploads/".$img_name, 'w+');
fputs($file, $data);
fclose($file);*/

//mysqli_query($db, "INSERT into webzen_profile_image set user_id='".$_SESSION["user_id"]."',image='".$img_name."'");


  /*try {
		$user_profile = $facebook->api('/me');

  	    $fbid = $user_profile['id'];                 // To Get Facebook ID
 	    $fbuname = $user_profile['username'];  // To Get Facebook Username
		
 	    $fbsaxeli = $user_profile['first_name']; // To Get Facebook saxeli
		$fbgvari = $user_profile['last_name']; // To Get Facebook gvari
		
	    $femail = $user_profile['email'];    // To Get Facebook email ID
		$user_gender = $user_profile['gender']; 
		 
		$_SESSION['FBID'] = $fbid; 
		$_SESSION['user_id'] = $fbid;  
		
	    $_SESSION['USERNAME'] = $fbuname;
        $_SESSION['FULLNAME'] = $fbsaxeli.' '.$fbgvari;
	    $_SESSION['EMAIL'] =  $femail;

		$_SESSION['GENDER'] = $user_gender;
		if(isset($_SESSION['FBID']))
		{
			define('DB_SERVER', 'localhost');
			define('DB_USERNAME', 'soc');    // DB username
			define('DB_PASSWORD', 'soc');    // DB password
			define('DB_DATABASE', 'soc');      // DB name
			$connection = mysql_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD) or die( "Unable to connect");
			$database = mysql_select_db(DB_DATABASE) or die( "Unable to select database");
			$check1 = mysql_query("SELECT * FROM webzen_users WHERE Fuid='$fbid'");
			$check = mysql_num_rows($check1);
			$row = mysql_fetch_array($check1);
			
			if($user_gender=='male'){ $sqesi = '1'; }else{ $sqesi = '2'; }
			
			
			if (empty($check)) { // if new user . Insert a new record		
			$query = mysql_query("INSERT INTO webzen_users SET Fuid='$fbid', username='$fbsaxeli', gvari='$fbgvari', sqesi='$sqesi', active='1', status='user', mail='$femail'");
			
			if($query){
			
			$_SESSION['status'] = 'user';
			$_SESSION['user_id'] = mysql_insert_id();
			
			}
			
			} else {   // If Returned user . update the user record		
			$query = mysql_query("UPDATE webzen_users SET Fuid='$fbid', username='$fbsaxeli', gvari='$fbgvari', sqesi='$sqesi', mail='$femail' WHERE Fuid='$fbid'");
		
			if($query){
			$_SESSION['status'] = 'user';
			$_SESSION['user_id'] = $row['id'];
			}
			
		}
		}
		
  } catch (FacebookApiException $e) {
    error_log($e);
   $user = null;
  }*/
}
/*
echo '<input type="hidden" name="myFbHiddenFlag" id="myFbHiddenFlag" value="Exist">';

if ($user) {
	//header("Location: index.php");exit;
	
	echo "<script type='text/javascript'>
	if (window.opener){
	
	window.opener.document.getElementById('filter_loading_image_msg').style.display = 'none';
	window.opener.document.getElementById('filter_loading_image_msg2').style.display = '';
	
	
            window.close();			
    window.opener.location.href='index.php'
	}else{
	 
	location.href='index.php'
	}
        </script>";
	exit;
	
} else {
 
 $loginUrl = $facebook->getLoginUrl(array(
		'scope'		=> 'email', // Permissions to request from the user
		));
		*/
?>
<div id="fb-root"></div>
	 <div id="filter_loading_image" style="background-color: #ffffff;height: 80%;left: 0;opacity: 0.5;position: absolute;width: 100%;z-index: 555;padding-top: 1%; overflow:hidden;">
					 
		<strong id="filter_loading_image_msg">Please wait facebook login window is opening.</strong>
		<strong id="filter_loading_image_msg2" style="display:none;">Fetching your facebook photos.</strong>
						
<img src="<?php echo Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_SKIN)?>frontend/rwd/default/images/opc-ajax-loader.gif" style="margin:0 auto"/>
</div>	
<?php 
 //header("Location: ".$loginUrl); 
 /*
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
  
  
  
  return window.document.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
} 
</script>"; 



 echo '<script type="text/javascript">popupCenter("'.$loginUrl.'", "myWindow", "800", "800");
 
 window.opener.document.getElementById("filter_loading_image_msg").innerHTML = "Please login to facebook.";
 
 </script>';
 
 exit;
 
 }
*/ ?>

 <script type='text/javascript'>
<?php /*?>
      window.fbAsyncInit = function() {
        FB.init({
          appId      : '729062020517600',
          xfbml      : true,
          version    : 'v2.2',
			status: false,  
	        cookie: true,
	        oauth: true	
        });
		
		
		
      };

      (function(d, s, id){
         var js, fjs = d.getElementsByTagName(s)[0];
         if (d.getElementById(id)) {return;}
         js = d.createElement(s); js.id = id;
         js.src = '//connect.facebook.net/en_US/sdk.js';
         fjs.parentNode.insertBefore(js, fjs);
       }(document, 'script', 'facebook-jssdk'));
	   
	   
	   window.onload = function(){
	   
FB.getLoginStatus(function(response) {
  if (response.status === 'connected') {
    //console.log('Logged in.');
	//window.location.reload();
		 location.href='index.php';
	 
  }
  else {
    FB.login(function(response) {
        //   if (response.authResponse) 
     //       {
      //           location.href='index.php';
      //      } else
     //       {
      //       console.log('Authorization failed.');
     //       }
    //     });	
	//document.getElementById('filter_loading_image_msg').style.display = 'none';
	//document.getElementById('filter_loading_image_msg2').style.display = '';
	
  }
});	   
	   
	   } <?php */?>
		   location.href='index.php';
</script>