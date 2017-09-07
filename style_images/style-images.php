<?php
function implodeArrayKeys($array) {
        return implode(",",array_keys($array));
}

if(@$_POST["submitUpload"])
{
	$target_dir = "";
	$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);

		$mimes = array('application/vnd.ms-excel','text/plain','text/csv','text/tsv');
		if(in_array($_FILES['fileToUpload']['type'],$mimes)){
		 
		 copy($_FILES['fileToUpload']['tmp_name'],"final_categories3.csv");
		  echo "CSV File is updated";
		} else {
		  die("Sorry, mime type not allowed <a href='style-images.php'>Go back</a>");
		}
	
}

if(@$_POST["submit_form"])
{
   $imageArr = array();
   
   $order_style_post = $_POST['order_style'];
   $image_name_post = $_POST['image_name'];
   
   $new_array_set = array();
   foreach($order_style_post as $order_style_post_key=>$order_style_post_val)
   {
     $new_array_set[$image_name_post[$order_style_post_key]] = $order_style_post_val;
   }
   
   //print_r($new_array_set);
   asort($new_array_set);
   //echo '<br/>';
   //print_r($new_array_set);
   //echo '<br/>';
   $new_array_set_string = implodeArrayKeys($new_array_set);
   //echo $new_array_set_string;
   //print_r($new_array_set_string);exit;
   $file = fopen($_POST['case_style'].".csv","w");   
   fputcsv($file,array($new_array_set_string));
   fclose($file);
}


$file = fopen("final_categories3.csv","r");

$firstLine = true;

$imgArr = array();
$styleArr = array();
$styleMainArray = array();

while(! feof($file))
  {
	$arr = fgetcsv($file);
	
	if($firstLine == false)
	 {
	   if(trim($arr[4]) != '')
	    $styleMainArray[] = trim($arr[4]);
		
	   if(trim($arr[3]) != '')
	    $styleArr[] = trim($arr[3]);
		
	   if(trim($arr[0]) != '')
	    $imgArr[] = trim($arr[0]);
		
	 }
	$firstLine = false;
  }

fclose($file);



?>
<script type="text/javascript">
incluedMoreImage = 'no';
function calc()
{
  if (document.getElementById('addMoreImages').checked) 
  {
      incluedMoreImage = 'yes';
  } else {
     incluedMoreImage = 'no';
  }
}
function redirectStylePage()
{
	location.href='style-images.php?case_style_para='+document.getElementById('case_style').value+'&includMoreImages='+incluedMoreImage
}
</script>

<form method="post" id="frmUploadcaseStyleCSV" style="display:none;" enctype="multipart/form-data">
<a href="final_categories3.csv">Download Csv from here and edit and upload it.</a>
<input type="file" name="fileToUpload" />&nbsp;&nbsp;
<input type="submit" name="submitUpload" value="Upload" />
</form>


<form method="post" action="">

<!--<input type="checkbox" name="addMoreImages"  id="addMoreImages" value="addmoreimages" onclick="calc()"/>&nbsp;Add more images&nbsp;&nbsp;&nbsp;&nbsp;-->
<a href="javascript:void(0)" onclick="document.getElementById('frmUploadcaseStyleCSV').style.display=''">Add more images.</a>

<select name="case_style" id="case_style">
<option value=""></option>
<?php foreach($styleMainArray as $styleMainArrayVal){?>
<option <?php if(@$_GET['case_style_para'] == $styleMainArrayVal) echo 'selected';?> value="<?php echo $styleMainArrayVal?>"><?php echo $styleMainArrayVal?></option>
<?php }?>
</select>
<input type="button" value="Go" onclick="redirectStylePage()" />

<?php if(@$_GET['case_style_para']){
if(is_file($_GET['case_style_para'].'.csv')){
$file = fopen($_GET['case_style_para'].'.csv', 'r');
$style_wise_images = '';
while (($line = fgetcsv($file)) !== FALSE) {
  $style_wise_images = $line[0];
}
fclose($file);

$style_wise_images_arr = explode(',',$style_wise_images);
}
?>

<table width="100%" cellpadding="2" cellspacing="2" border="0">
<tr>
<td><input type="submit" name="submit_form" value="Submit Form" /></td>
</tr>
<tr>
	<td width="100%" align="left">
<?php 
$arrFinalImagesVal = array();

foreach($imgArr as $imgArrKey=>$imgArrVal){
$inlucdeInStyle = explode(',',$styleArr[$imgArrKey]);
$inlucdeInStyle=array_map('trim',$inlucdeInStyle);
if(!in_array(trim($_GET['case_style_para']),$inlucdeInStyle))
continue;

$key = @array_search($imgArrVal, $style_wise_images_arr); // $key = 2;
$arrFinalImagesVal[$key] = $imgArrVal;
}


//foreach($arrFinalImagesVal as $imgArrKey=>$imgArrVal){

for($ii=0;$ii<count($arrFinalImagesVal);$ii++){
?>

		<div style="width:350px;height:110px;border:1px solid #000;float:left;display:block;margin-top:5px;margin-left:5px;padding:5px;">
		  <div style="float:left;"><img src="../media/stock/img-100/<?php echo $arrFinalImagesVal[$ii];?>" /></div>
		  <div style="float:left;margin-left:5px;"><input type="checkbox" name="includeInStyle[]" <?php if(in_array(trim($_GET['case_style_para']),$inlucdeInStyle)) echo 'checked';?> ><br/><br/>
			<input type="text" name="order_style[]" placeholder="order" value="<?php echo ($ii+1);?>" /><br/><br/>
			<?php echo $imgArrVal;?>			
			<input type="hidden" name="image_name[]" value="<?php echo ($arrFinalImagesVal[$ii]);?>" /></div>
		</div>
<?php }?>
</td>
</tr>

</table><?php }?>	
</form>
