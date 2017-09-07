<?php
class Gravita_Trending_Adminhtml_StyleController extends Mage_Adminhtml_Controller_Action
{
	var $read, $write;
	public function indexAction()
    {
        $this->loadLayout()->renderLayout();	
    }
	
	public function initConnection()
	{
		$this->read = Mage::helper('function/more')->getDbReadObject();
        $this->write = Mage::helper('function/more')->getDbWriteObject();
	}
	
	public function addProdAction()
	{
		$this->initConnection();
		$params = $this->getRequest()->getParams();
		$main_style = $params['main_style'];
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
		$select = $this->read->query("SELECT `tid` FROM `trendingproduct` WHERE `prod_name` = '".$params['pname']."' AND `prod_link` = '".$params['plink']."' AND active = 1"); 
		$rowArray = $select->fetch();  
		if($rowArray['tid'] == '')
		{
			$this->write->beginTransaction();
			$fields = array();
			$fields['style_val'] = $params['style_val'];
			$fields['prod_name'] = $params['pname'];
			$fields['prod_link'] = $params['plink'];
			$fields['prod_img'] = $filename;
			$fields['position'] = '0';
			$fields['active'] = '1';
			$fields['created_dt'] = date('Y-m-d H:i:s');
			$fields['updated_dt'] = date('Y-m-d H:i:s');
			$this->write->insert('trendingproduct', $fields);
			$this->write->commit();
			Mage::getSingleton('core/session')->addSuccess(Mage::helper('trending')->__('Product Inserted Successfully .'));	
       		$this->_redirect('*/adminhtml_style/index/style/'.$main_style);
		}		
	}
}