<?php
class Gravita_Fproduct_Adminhtml_FproductformController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->loadLayout()->renderLayout();	
    } 
	
	public function bpostAction()
	{
		$read = Mage::helper('function/more')->getDbReadObject();
		$write = Mage::helper('function/more')->getDbWriteObject();
		$params = $this->getRequest()->getParams();
		$img = $_FILES['pimage']['name'];
		$media = 'media/home_banner_img/';
		if($img != '')
		{
			$random_digit = rand(0,456764);
			$filename = $random_digit.$_FILES['pimage']['name'];
			$uploadedfile =  $_FILES['pimage']['tmp_name'];
			$new_filename = $media."/".$filename;
			move_uploaded_file($uploadedfile, $new_filename);				
		}
		$select = $read->query("SELECT `fid` FROM `featuredproduct` WHERE `prod_name` = '".$params['pname']."' AND `prod_link` = '".$params['plink']."' AND active = 1"); 
		$rowArray = $select->fetch();  
		if($rowArray['fid'] == '')
		{
			$write->beginTransaction();
			$fields = array();
			$fields['cat_id'] = $params['type_id'];
			$fields['prod_name'] = $params['pname'];
			$fields['prod_link'] = $params['plink'];
			$fields['prod_img'] = $filename;
			$fields['position'] = '0';
			$fields['active'] = '1';
			$fields['created_dt'] = date('Y-m-d H:i:s');
			$fields['updated_dt'] = date('Y-m-d H:i:s');
			$write->insert('featuredproduct', $fields);
			$write->commit();
			Mage::getSingleton('core/session')->addSuccess(Mage::helper('fproduct')->__('Image Inserted Successfully .'));	
       		$this->_redirect('*/*');
		}
		
		
		
		
		
		//$dname_qry = $readConnection->select()->from('drop_device', array('device_name'))->where("id=".$post['type_id']);
//		$dname_res = $readConnection->fetchRow($dname_qry); 
//		
//		$base_url = Mage::getStoreConfig('web/unsecure/base_url');
//		$bname =  Mage::getModel('catalog/product_url')->formatUrlKey($post['bname']);
//		$bvalue = Mage::getModel('catalog/product_url')->formatUrlKey($post['bvalue']);
//		$type_name = Mage::getModel('catalog/product_url')->formatUrlKey($dname_res['device_name']);
//		
//		$select = $readConnection->select()->from('drop_data', array('id'))->where("type_id=".$post['type_id']." and brand_name = '".$post['bname']."' and device = '".$post['bvalue']."'"); 
//		$rowArray = $readConnection->fetchRow($select);  
//		if($rowArray['id'] != '')
//		{			
//			Mage::getSingleton('core/session')->addError(Mage::helper('dropdown')->__('Category Value Already Exist .'));	
//       		$this->_redirect('*/*');
//		}
//		else
//		{
//			$writeConnection->beginTransaction();
//			$fields = array();
//			$fields['type_id'] = $post['type_id'];
//			$fields['brand_name'] = $post['bname'];
//			$fields['device'] = $post['bvalue'];
//			$fields['link'] = $base_url.'shop/'.$type_name.'/'.$bname.'/'.$bvalue.'.html';
//			$fields['position'] = '0';
//			$fields['active'] = '1';
//			$writeConnection->insert('drop_data', $fields);
//			$writeConnection->commit();
//			Mage::getSingleton('core/session')->addSuccess(Mage::helper('dropdown')->__('Category Value Added Successfully .'));	
//       		$this->_redirect('*/*');
//		}
	}
}