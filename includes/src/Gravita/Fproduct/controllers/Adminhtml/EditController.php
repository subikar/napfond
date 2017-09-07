<?php
class Gravita_Fproduct_Adminhtml_EditController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->loadLayout()->renderLayout();	
    }
	public function editAction()
	{
		$params = $this->getRequest()->getParams();
		//print_r($params);
		$filename = '';
		$fid = $params['fid'];
		$cat_id = $params['type_id'];
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
		
		$write = Mage::getSingleton('core/resource')->getConnection('core_write');
   		$write->beginTransaction();
    	$fields = array();
		$fields['cat_id'] = $cat_id;
		$fields['prod_name'] = $pname;
		$fields['prod_link'] = $plink;
		if($filename != '')
		{
			$fields['prod_img'] = $filename;
		}				
		$fields['updated_dt'] = date('Y-m-d H:i:s');
		$where = $write->quoteInto('fid =?', $fid);
		$write->update('featuredproduct', $fields, $where);
		$write->commit();
		Mage::getSingleton('core/session')->addSuccess(Mage::helper('fproduct')->__('Product Updated Successfully .'));	
       	$this->_redirect('*/adminhtml_fproductform/');
	}
}