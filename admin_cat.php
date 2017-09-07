<?php session_start();
include_once('app/Mage.php');
Mage::app();

$connection = Mage::helper('function/more')->getDbReadObject();
$write = Mage::helper('function/more')->getDbWriteObject();	

if(isset($_REQUEST['get_subcat']) && $_REQUEST['get_subcat'] != '')
{
	$children = Mage::getModel('catalog/category')->getCategories($_REQUEST['get_subcat']);?>
    <div class="cat_lbl"><label>Select Sub-category1 : </label></div>
    <div class="cat_names">
	<select name="sub_cat1" id="cat2" onchange="get_subcat1(this.value)">
     <?php
     echo '<option value="0">-- Please Select Category--</option>';    
	  foreach ($children as $category) {
		echo '<option value="'.$category->getId().'">'.$category->getName().'</option>';
 	}?>
    </select></div>
	<?php 
}

if(isset($_REQUEST['get_subcat1']) && $_REQUEST['get_subcat1'] != '')
{
	$children = Mage::getModel('catalog/category')->getCategories($_REQUEST['get_subcat1']);?>
    <div class="cat_lbl"><label>Select Sub-category2 : </label></div>
	<div class="cat_names">
    <select name="sub_cat2" id="cat3" onchange="get_attr(this.value)">
     <?php
     echo '<option value="0">-- Please Select Category--</option>';    
	  foreach ($children as $category) {
		echo '<option value="'.$category->getId().'">'.$category->getName().'</option>';
 	}?>
    </select></div>
	<?php 
}

if(isset($_REQUEST['get_attr']) && $_REQUEST['get_attr'] != '')
{
	if($_REQUEST['get_attr'] > 0)
	{		
		$prdIds = array();
		$category = new Mage_Catalog_Model_Category();
		$category->load($_REQUEST['get_attr']);
		$prodCollection = $category->getProductCollection();
		foreach ($prodCollection as $product) 
		{
		  $prdIds = $product->getId();		  
		  $colors[] = Mage::getModel('catalog/product')->load($prdIds)->getAttributeText('color');
		  $models[] = Mage::getModel('catalog/product')->load($prdIds)->getAttributeText('model');
		  $case_styles[] = Mage::getModel('catalog/product')->load($prdIds)->getAttributeText('case_style');
		  $case_designers[] = Mage::getModel('catalog/product')->load($prdIds)->getAttributeText('case_designer');
		} ?>
        <br />
		<div class="main_cat">
		 <div class="cat_lbl"><label>Color : </label></div>
		 <div class="cat_names"> 
         <?php
		   $color = array();
		   $color = array_values(array_unique($colors));
		   for($i=0;$i<count($color);$i++)
		   {
			 //echo '<option value="'.$color[$i].'">'.$color[$i].'</option>';
			 echo '<div class="fil_col_name">'.$color[$i].'</div>';
             echo '<div class="fil_col_val"> 
			        <input type="hidden" name="col_count" value="'.count($color).'">
			        <input type="hidden" name="col_'.$i.'" value="'.$color[$i].'">
					<input type="file" name="file_'.$color[$i].'[]" /></div>';
			 echo '<br>';
		   }?>            
           </div>
          </div> 
          <br />
          <div class="main_cat">
           <div class="cat_lbl"><label>Case Style : </label></div>
		   <div class="cat_names"> 
           <?php
		    $case_style = array();
		    $case_style = array_values(array_unique($case_styles));
		   for($i=0;$i<count($case_style);$i++)
		   {
			 echo '<div class="fil_col_name">'.$case_style[$i].'</div>';
             echo '<div class="fil_col_val"> 
			        <input type="hidden" name="case_count" value="'.count($case_style).'">
			        <input type="hidden" name="case_'.$i.'" value="'.$case_style[$i].'">
					<input type="file" name="file_'.$case_style[$i].'[]" /></div>';
			 echo '<br>';
		   }?> 
         	</div> 
           </div>              
		  <?php /*?><select name="col_name" id="col_name">
		   <option value="0">-- Please Select Color--</option>
		   <?php
		   $color = array();
		   $color = array_values(array_unique($colors));
		   for($i=0;$i<count($color);$i++)
		   {
			 echo '<option value="'.$color[$i].'">'.$color[$i].'</option>';
		   }?> 
		  </select> <?php */?>     
		 </div> 
		 <?php /*?><div class="cat_img"> 
		  <input type="file" name="col_img" id="col_img" /> 
		 </div><?php */?>
		</div> 
		
		<?php /*?><div class="main_cat"> 
		 <div class="cat_lbl"><label>Select Model : </label></div>
		 <div class="cat_names">      
		  <select name="mod_name" id="mod_name">
		   <option value="0">-- Please Select Model--</option>
		   <?php
		   $model = array();
		   $model = array_values(array_unique($models));
		   for($i=0;$i<count($model);$i++)
		   {
			 echo '<option value="'.$model[$i].'">'.$model[$i].'</option>';
		   }?> 
		  </select>
		 </div> 
		 <div class="cat_img"> 
		  <input type="file" name="mod_img" id="mod_img" /> 
		 </div>
		</div> 
		<br /><?php */?>
		<?php /*?><div class="main_cat">
		 <div class="cat_lbl"><label>Select Case Style : </label></div>
		 <div class="cat_names">      
		  <select name="style_name" id="style_name">
		   <option value="0">-- Please Select Case Style--</option>
		   <?php
		   $case_style = array();
		   $case_style = array_values(array_unique($case_styles));
		   for($i=0;$i<count($case_style);$i++)
		   {
			 echo '<option value="'.$case_style[$i].'">'.$case_style[$i].'</option>';
		   }?> 
		  </select>
		 </div> 
		 <div class="cat_img"> 
		  <input type="file" name="style_img" id="style_img" /> 
		 </div>
		</div><?php */?>
	
		<?php /*?><div class="main_cat"> 
		 <div class="cat_lbl"><label>Select Case Designer : </label></div>
		 <div class="cat_names">      
		  <select name="des_name" id="des_name">
		   <option value="0">-- Please Select Case Designer--</option>
		   <?php
		   $case_designer = array();
		   $case_designer = array_values(array_unique($case_designers));
		   for($i=0;$i<count($case_designer);$i++)
		   {
			 echo '<option value="'.$case_designer[$i].'">'.$case_designer[$i].'</option>';
		   }?> 
		  </select>
		 </div>  
		 <div class="cat_img"> 
		  <input type="file" name="des_img" id="des_img" /> 
		 </div>
		</div><?php */?> 
		<?php 
	}
	else
	{
		echo "No Data Found.";
	}
}

if(isset($_REQUEST['get_device']) && $_REQUEST['get_device'] != '')
{
	$categories = Mage::getModel('catalog/category')->getCategories($_REQUEST['get_device']);
	echo '<option value="0">'.$_REQUEST['select'].'</option>';
	foreach($categories as $category)
    {
	   echo '<option value="'.$category->getId().'">'.$category->getName().'</option>';
	}
}
if(isset($_REQUEST['get_bselect']) && $_REQUEST['get_bselect'] != '')
{
	$cat = Mage::getModel('catalog/category')->load($_REQUEST['get_bselect']);
	$key1 = $cat->getUrl_key();
	$cat1 = Mage::getModel('catalog/category')->load($_REQUEST['get_bvalue']);
	$key2 = $cat1->getUrl_key();	
	echo $link = Mage::getBaseUrl().'shop/mobile-skin/'.$key1.'/'.$key2.'.html';	
}

if(isset($_REQUEST['pincode']) && $_REQUEST['pincode'] != '')
{
	$pincode = $_REQUEST['pincode'];
	$flag = 0;
 
	
	$query = "SELECT count(*) as zipCodeCountRow FROM fedex_zipcode where TRIM(zipcode)='".trim($pincode)."' and TRIM(LOWER(cod_servc)) = 'cod'";     
	$zipcodeCountRow = $connection->fetchOne($query);	
	
	if($zipcodeCountRow > 0)
	{
		echo '<span class="cod_success">We have COD services for the pin code '.$pincode.'</span>';exit();
	}
	else
	{
		echo '<span class="cod_fail">Sorry, currently COD service is not available at this pin code.</span>';exit();
	}
}

if(isset($_REQUEST['saveGuest']) && $_REQUEST['saveGuest'] == 1)
//if(isset($_REQUEST['userEmail']) && $_REQUEST['userEmail'] != '')
{
	$usermail = $_REQUEST['email'];
	$mobile = $_REQUEST['mobile'];
	$prefix = $_REQUEST['prefix'];
	$firstname = $_REQUEST['firstname'];
	$lastname = $_REQUEST['lastname'];
	$street1 = $_REQUEST['street1'];
	$city = $_REQUEST['city'];
	$postcode = $_REQUEST['postcode'];
	$region_id = $_REQUEST['region_id'];

   	/*$select = $connection->query("SELECT subscriber_id,	mobile FROM newsletter_subscriber WHERE  subscriber_email = '$usermail'");
    $result1 = $select->fetch();	 
	if($result1['subscriber_id'] != '')
	{
		$update = "UPDATE newsletter_subscriber SET mobile='$mobile' WHERE subscriber_id = '".$result1['subscriber_id']."'";
		$result3 = $write->query($update);
	}
	else
	{
		$insert = ("INSERT INTO  `newsletter_subscriber` (subscriber_email,mobile) VALUES ('$usermail',$mobile)");
    	$result = $write->query($insert); 
	}*/

	$select = $connection->query("SELECT entity_id FROM customer_entity WHERE  email = '$usermail'");
    $result1 = $select->fetch();	 
	if($result1['entity_id'] != '')
	{
		/*$update = "UPDATE newsletter_subscriber SET mobile='$mobile' WHERE subscriber_id = '".$result1['subscriber_id']."'";
		$result3 = $write->query($update);*/
	}
	else
	{
		$websiteId = Mage::app()->getWebsite()->getId();
		$store = Mage::app()->getStore();		 
		$customer = Mage::getModel("customer/customer");
		$pwd_length = 7; //auto-generated password length
		$customer->setWebsiteId($websiteId)
		         ->setStore($store)
		         /*->setPrefix($prefix)
	             ->setFirstname($firstname)
	             ->setLastname($lastname)*/
		         ->setEmail($usermail)
		         ->setPassword($customer->generatePassword($pwd_length));
		try
		{
		    $customer->save();
		    $customer->sendNewAccountEmail(); //send confirmation email to customer
		}
		catch (Exception $e) 
		{
		    Zend_Debug::dump($e->getMessage());
		}	


		/*$_custom_address = array (
	        'firstname'  => $firstname,
	        'lastname'   => $lastname,
	        'street'     => $street1,
	        'city'       => $city,
	        'postcode'   => $postcode,
	        'country_id' => 'IN',
	        'telephone'  => $mobile,
	    );

	    $customAddress = Mage::getModel('customer/address');
	    $customAddress->setData($_custom_address)
			        ->setCustomerId($customer->getId()) // this is the most important part
			        ->setIsDefaultBilling('1')  // set as default for billing
			        ->setIsDefaultShipping('1') // set as default for shipping
			        ->setSaveInAddressBook('1');
	    $customAddress->save();*/
	}
}

// Get record of email id from subscription table.
// If not exists then insert a new record in table with specified details.
// If record already exists then compare saved data with new specified data.
// If found change in data and the new values are non-empty values then update the appropriate record.

if(isset($_REQUEST['featured_id']) && $_REQUEST['featured_id'] != '')
{
	$fid = $_REQUEST['featured_id'];
	$select = $connection->query("DELETE FROM `featuredproduct` WHERE `fid` = '".$fid."'");
	Mage::getSingleton('core/session')->addSuccess(Mage::helper('fproduct')->__('Product Deleted Successfully .'));	
}

if(isset($_REQUEST['block_id']) && $_REQUEST['block_id'] != '')
{
	$bid = $_REQUEST['block_id'];
	$select = $connection->query("DELETE FROM `trandingblock` WHERE `bid` = '".$bid."'");
	Mage::getSingleton('core/session')->addSuccess(Mage::helper('trending')->__('Block Deleted Successfully .'));	
}

?>