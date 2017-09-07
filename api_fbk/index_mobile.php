<?php session_start();
 include_once('../app/Mage.php');
Mage::app();
 if($_SERVER['HTTP_HOST'] == ' dev.gravitainfotech.com:8282')
exit;
/*require 'src/facebook.php';  // Include facebook SDK file
//require 'functions.php';  // Include functions
$facebook = new Facebook(array(
  'appId'  => '753880228035779',   // Facebook App ID 
  'secret' => '082370d776e5bd823c4d3cbf4bbe7e57',  // Facebook App Secret
  'cookie' => true 
));

    $user = $facebook->getUser(); 
	
	 
	
 if($user) {
   $access_token = $facebook->getAccessToken();
 
  //$albums = $facebook->api('/1376631492640455/photos');
  $albums2 = $facebook->api('/me/albums');

  $albums2 = $albums2['data'];
  
  //echo '<pre>';
  //print_r($albums); 
  
  //echo '<pre>';
  //print_r($albums2);exit; 
}
 */
?>
 
<?php /*?> 
<link rel="stylesheet" type="text/css" href="<?php echo Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_SKIN)?>frontend/rwd/default/css/jquery.fancybox.css" />
<link rel="stylesheet" type="text/css" href="<?php echo Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_SKIN)?>frontend/rwd/default/css/styles.css" />
<?php */?>


	<a href="javascript:void(0)" class="floatRightCss show_popup_mobile_close fontSize20pxImp">X</a>

	<h2>Gallery</h2>
	<div class="a-center">
	    <select id="fbAlbumNameLeft" onchange="showHideDiv(this.value)">
			
        </select>
		<br/><br/>
    </div>
					
 		   
				
					
					
			<div class="sright_mobile a-center clear marTOP20pxCss">
					 <div id="filter_loading_image" style="background-color: #ffffff;height: 80%;left: 0;opacity: 0.5;position: absolute;width: 100%;z-index: 555;padding-top: 1%; overflow:hidden;display:none;">
						<img src="<?php echo Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_SKIN)?>frontend/rwd/default/images/opc-ajax-loader.gif" style="margin:0 auto"/>
					 </div>	
			  <div id="fbAlbumPhotosRight">
	 
			  </div>
			</div>
                  
				 



 <script type='text/javascript'>
      window.fbAsyncInit = function() {
        FB.init({
          appId      : '729062020517600',
          xfbml      : true,
          version    : 'v2.2',
			status: false,  
	        cookie: true,
	        oauth: true						
		  
        });
		
		
		
	   
FB.getLoginStatus(function(response) {
  if (response.status === 'connected') {
    //console.log('Logged in.');
	//window.location.reload();
	//parent.jQuery('#img_display').hide();
	getFaceBookAlbum();
	document.getElementById('filter_loading_image').style.display = 'none';
  }
  else {
    FB.login(function(response) {
           if (response.authResponse) 
            {
                getFaceBookAlbum();
				 
            } else
            {
				console.log('Authorization failed.');
            }
         });	
	document.getElementById('filter_loading_image').style.display = 'none';
	
  }
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
	   
	
	document.getElementById('filter_loading_image').style.display = 'none';
	   
   
	   
	   }
	   
	   
    </script> 
				  
<script type="text/javascript">
var mytestVar = new Array();
ii = 0;
style = '';
function getFaceBookAlbum()
{
show = '';


					FB.api('/me/albums?fields=id,name', function(response) {
  for (var i=0; i<response.data.length; i++) {
    var album = response.data[i];
	
 
 
		

			mytestVar[ii] = album.id;
			ii++;
			 
		
		//document.getElementById('fbAlbumNameLeft').innerHTML = document.getElementById('fbAlbumNameLeft').innerHTML + ('<li><div onclick="showHideDiv('+album.id+')">'+album.name+'</div></li>');
		
		
		
		document.getElementById('fbAlbumNameLeft').innerHTML = document.getElementById('fbAlbumNameLeft').innerHTML + ('<option value="'+album.id+'">'+album.name+'</option>');
		
		// document.getElementById('fbAlbumPhotosRight').appendChild('<div id="'+album.id+'" style="'+style+'">');
		 
		fetFaceBookPhotos(album.id)

       
  }
});


}



function fetFaceBookPhotos(albumid)
 {
 
 
      FB.api('/'+albumid+'/photos', function(photos){
	  
	//document.getElementById('fbAlbumPhotosRight').innerHTML = document.getElementById('fbAlbumPhotosRight').innerHTML + document.getElementById('fbAlbumPhotosRight').innerHTML + ('<div id="'+album.id+'" style="'+style+'">');  
	   
        if (photos && photos.data && photos.data.length){
          for (var j=0; j<photos.data.length; j++){
		  
		  if(style == '')
		  style=albumid
		  
		  
		  
		  
	 if(style != albumid)
		style2='display:none';
	 else 
	    style2='';
		  
		  
		  
            var photo = photos.data[j];
            // photo.picture contain the link to picture
            var image = document.createElement('img');
            image.src = photo.picture;
            //document.body.appendChild(image);
			data_origin_url = photo.source;
			//document.getElementById('fbAlbumPhotosRight').appendChild;
			
			document.getElementById('fbAlbumPhotosRight').innerHTML = document.getElementById('fbAlbumPhotosRight').innerHTML +  ('<div class="image-ppc '+albumid+'" style="'+style2+'"><img class="clipart-image" src="'+image.src+'" alt="<?php echo Mage::getStoreConfig(Mage_Core_Model_Url::XML_PATH_SECURE_URL);?>api_fbk/read_image.php?bhishoom_img_url='+data_origin_url+'" data-origin-url="<?php echo Mage::getStoreConfig(Mage_Core_Model_Url::XML_PATH_SECURE_URL);?>api_fbk/read_image.php?bhishoom_img_url='+data_origin_url+'"></div>');
			
			<?php /*?>
			document.getElementById('fbAlbumPhotosRight').innerHTML = document.getElementById('fbAlbumPhotosRight').innerHTML +  ('<div class="image-ppc '+albumid+'" style="'+style2+'"><img class="clipart-image" src="'+image.src+'" alt="'+data_origin_url+'" data-origin-url="'+data_origin_url+'" onclick="onClickUserInstImages(this.src,this.alt)"></div>');
			<?php */?>
          }
        }
		// document.getElementById('fbAlbumPhotosRight').innerHTML = document.getElementById('fbAlbumPhotosRight').innerHTML + ('</div>');
      }); 
 
 }
/*
function onClickUserInstImages(imgSrc,data_origin_url)
{
parent.jQuery('#uploadedImages_fb_img').attr('src',imgSrc);
parent.jQuery('#uploadedImages_fb_img').attr('data-origin-url',data_origin_url);




parent.jQuery('#uploadedImages_fb_img').click();
parent.jQuery.fancybox.close();
parent.jQuery('#social_media_img_container').append('<li><img class="clipart-image" src="'+imgSrc+'" data-origin-url="'+data_origin_url+'"><span onclick="jQuery(this).parent().remove();">X</span></li>')
}
*/
	function showHideDiv(divid)
     {  
	   for(ii=0;ii<mytestVar.length;ii++)
	   {  
	      if(mytestVar[ii]==divid){
   
					
					var y = document.getElementsByClassName(mytestVar[ii]);
					var i;
					for (i = 0; i < y.length; i++) {
					  y[i].style.display = '';
					}	  
				
		   }
		  else { 
	   
					var y = document.getElementsByClassName(mytestVar[ii]); 
					
					var i;
					for (i = 0; i < y.length; i++) {
					  y[i].style.display = 'none';
					}

		   }
		   
	   }
	 
	 }

</script>				  