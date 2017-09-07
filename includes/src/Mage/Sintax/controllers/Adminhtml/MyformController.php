<?php

class Mage_Sintax_Adminhtml_MyformController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->loadLayout()->renderLayout();
    }
    
    public function postAction()
    {
        $post = $this->getRequest()->getPost();		
		
        try {
            if (empty($post)) {
                Mage::throwException($this->__('Invalid form data.'));
            }

$read = Mage::helper('function/more')->getDbReadObject(); // To read from the database
$write = Mage::helper('function/more')->getDbWriteObject(); // To write to the database

$mEdia_Base_Dir = Mage::getBaseDir('media');
$mEdia_Base_URL = Mage::getBaseUrl('media');

$fileArray = $_FILES['case_style_image']['name'];
$fileArrayTmp = $_FILES['case_style_image']['tmp_name'];
$fileHideArr = $_POST['case_style_image_id'];
          
foreach($fileArray as $fileArrayKey=>$fileArrayVal)
{	
	if($fileArrayVal != '')
	 {  
	   $pathInfo = pathinfo($fileArrayVal);
	    $newFileName = $fileArrayVal;
	   if(is_file($mEdia_Base_Dir.'/case_style_home/'.$pathInfo['filename'].'.'.$pathInfo['extension'])){
	     $fileSuffix = 1;
	     while(is_file($mEdia_Base_Dir.'/case_style_home/'.$pathInfo['filename'].'_'.$fileSuffix.'.'.$pathInfo['extension'])){
		 
		 $fileSuffix++;
		 }
		 $newFileName = $pathInfo['filename'].'_'.$fileSuffix.'.'.$pathInfo['extension'];
		 }
		 
		 copy($fileArrayTmp[$fileArrayKey],$mEdia_Base_Dir.'/case_style_home/'.$newFileName);
		 
		 $result = $read->query( "select * from case_style_images where attr_id='137' and attr_val_id='".$_POST['case_style_image_id'][$fileArrayKey]."'" );
		 $row = $result->fetch();
		 		  
	   	 if($row['id'] > 0) 
	      {
		     $write->query("update case_style_images set img_name='".$newFileName."' where id='".$row['id']."'" );
		  }
		 else
		  {
		     $write->query("insert into case_style_images set attr_id='".$_POST['attr_id']."',attr_val_id='".$_POST['case_style_image_id'][$fileArrayKey]."',img_name='".$newFileName."'" );
		  
		  }	
	 }	
}	

foreach($post['show_home'] as $key => $val)
{
	$write->query("update case_style_images set active = 1 where attr_val_id = '".$key."'");
	$case_key[] = $key;		
}
$casekey = implode(",", $case_key);
$write->query("update case_style_images set active = 0 where attr_val_id NOT IN (".$casekey.")");
		


	/* here's your form processing */
            
            $message = $this->__('Your form has been submitted successfully.');
            Mage::getSingleton('adminhtml/session')->addSuccess($message);
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }
        $this->_redirect('*/*');
    }
}