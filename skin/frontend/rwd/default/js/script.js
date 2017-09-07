/*
* Author : Ali Aboussebaba
* Email : bewebdeveloper@gmail.com
* Website : http://www.bewebdeveloper.com
* Subject : Crop photo using PHP and jQuery
*/

// the target size
var TARGET_W = 600;
var TARGET_H = 300;

// show loader while uploading photo
function submit_photo(id,classnam) {
	// display the loading texte
	jQuery('#'+id+' > div.'+classnam+' > div.popup_upload > div.form_upload > form > .loading_progress').html('<img src="'+baseUrl+'images/loader.gif"> Uploading your photo...');
}

// show_popup : show the popup
function show_popup(id,classnam) { 
	jQuery('#'+id+' > div.'+classnam).show();
}

// close_popup : close the popup
function close_popup(id) {
	// hide the popup
	jQuery('#'+id).hide();
}

// show_popup_crop : show the crop popup
function show_popup_crop(url,basepath,imgname,popupid,wdh,hgt) {
	// change the photo source
	url = baseUrlForMedia+'gomage/productdesigner/uploadedImage/'+url+'/'+imgname;	
	//basepath = baseDirForMedia+'gomage/productdesigner/uploadedImage/'+basepath+'/'+imgname;	
	TARGET_W = wdh
	TARGET_H = 
	jQuery('#cropbox').attr('src', url);
	// destroy the Jcrop object to create a new one
	try {
		jcrop_api.destroy();
	} catch (e) {
		// object not defined
	}
	// Initialize the Jcrop using the TARGET_W and TARGET_H that initialized before
    jQuery('#cropbox').Jcrop({
      aspectRatio: wdh / hgt,
      setSelect:   [ 100, 100, wdh, hgt ],
      onSelect: updateCoords
    },function(){
        jcrop_api = this;
    });

    // store the current uploaded photo url in a hidden input to use it later
	jQuery('#photo_url').val(imgname);
	// hide and reset the upload popup
	//jQuery('#popup_upload').hide();
	jQuery('#loading_progress').html('');
	jQuery('#photo').val('');
	jQuery('#popup_id').val(popupid);

	// show the crop popup
	jQuery('#popup_crop').show();
}

// crop_photo : 
function crop_photo() {
	var x_ = jQuery('#x').val();
	var y_ = jQuery('#y').val();
	var w_ = jQuery('#w').val();
	var h_ = jQuery('#h').val();
	var photo_url_ = jQuery('#photo_url').val();
	 
	// hide thecrop  popup
	jQuery('#popup_crop').hide();
	
	// display the loading texte
	//jQuery('#photo_container').html('<img src="'+baseUrl+'images/loader.gif"> Processing...');
	
	
	
	
	// crop photo with a php file using ajax call
	jQuery.ajax({
		url: baseUrl+'crop_photo.php',
		type: 'POST',
		data: {x:x_, y:y_, w:w_, h:h_, photo_url:photo_url_, targ_w:TARGET_W, targ_h:TARGET_H,UploadedDir:currentUploadedDir},
		success:function(data){
			 
 
			 /*
			jQuery('#_popimg' + popup_id+' > div.popup_upload > div.form_upload > .photo_container').append(data);
			jQuery('#_popimg' + popup_id+' > div.popup_upload > div.form_upload > .photo_container > .loadingIMGTplCustom').hide();
			
		 
			
			  jQuery('#_popimg' + popup_id+" > div.popup_upload > div.form_upload > .photo_container > img").click(function(){ 
				 
				var img=new Image(); 
					 
				
					 
				img.onload=function(){ 
					var tmp=objs[popup_id].getElement();
					tmp.setAttribute("src", img.src); 
					canvas.renderAll();
					canvas.calcOffset();
				}	 
					 
				img.src = this.src;	 
      				
				
				 
			  });	*/	

				jQuery('#social_media_img_container').append('<li><img class="clipart-image" src="'+data+'" data-origin-url="'+data+'"><span onclick="jQuery(this).parent().remove();">X</span></li>')

			  
		}
	});
}

// updateCoords : updates hidden input values after every crop selection
function updateCoords(c) {
	jQuery('#x').val(c.x);
	jQuery('#y').val(c.y);
	jQuery('#w').val(c.w);
	jQuery('#h').val(c.h);
}

function baseNameTplCust(str)
{
   var base = new String(str).substring(str.lastIndexOf('/') + 1); 
    //if(base.lastIndexOf(".") != -1)       
        //base = base.substring(0, base.lastIndexOf("."));
   return base;
}