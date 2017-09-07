<?php
class Gravita_Trending_Adminhtml_EditController extends Mage_Adminhtml_Controller_Action
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
	
	public function blockEditAction()
	{
		$this->initConnection();
		$params = $this->getRequest()->getParams();
		$bid = $params['bid'];
		$block_style = $params['block_style'];
		$this->write->beginTransaction();
    	$fields = array();
		$fields['case_style'] = $block_style;
		$fields['updated_dt'] = date('Y-m-d H:i:s');
		$where = $this->write->quoteInto('bid =?', $bid);
		$this->write->update('trandingblock', $fields, $where);
		$this->write->commit();
		Mage::getSingleton('core/session')->addSuccess(Mage::helper('trending')->__('Block Updated Successfully .'));	
       	$this->_redirect('*/adminhtml_trendingform/');
	}
	public function editAction()
	{
		$this->initConnection();
		$params = $this->getRequest()->getParams();
		$filename = '';
		$tid = $params['tid'];
		$style_val = $params['style_val'];
		$pname = $params['pname'];
		$plink = $params['plink'];
		$pimg = $_FILES['pimage']['name'];
		$media = 'media/home_banner_img/';
		if($pimg != '')
		{
			$random_digit = rand(0,456764);
			$filename = $random_digit.$_FILES['pimage']['name'];
			$uploadedfile =  $_FILES['pimage']['tmp_name'];
			$new_filename = $media."/".$filename;
			move_uploaded_file($uploadedfile, $new_filename);
		}		
		
   		$this->write->beginTransaction();
    	$fields = array();
		$fields['style_val'] = $style_val;
		$fields['prod_name'] = $pname;
		$fields['prod_link'] = $plink;
		if($filename != '')
		{
			$fields['prod_img'] = $filename;
		}				
		$fields['updated_dt'] = date('Y-m-d H:i:s');
		$where = $this->write->quoteInto('tid =?', $tid);
		$this->write->update('trendingproduct', $fields, $where);
		$this->write->commit();
		Mage::getSingleton('core/session')->addSuccess(Mage::helper('trending')->__('Product Updated Successfully .'));	
       	$this->_redirect('*/adminhtml_trendingform/');
	}
}