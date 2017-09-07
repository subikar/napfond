<?php
class Gravita_Dropdown_Adminhtml_DropdownformController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->loadLayout()->renderLayout();	
    } 
	
	//public function postAction()
//    {
//        $post = $this->getRequest()->getPost();
//		print_r($post);die;	
//		$readConnection = Mage::getSingleton('core/resource')->getConnection('core_read');
//		$writeConnection = Mage::getSingleton('core/resource')->getConnection('core_write');	
//		
//		Mage::getSingleton('core/session')->addSuccess(Mage::helper('dropdown')->__('Category Inserted Successfully .'));	
//        $this->_redirect('*/*');
//    } 
	
	public function bpostAction()
	{
		$readConnection = Mage::helper('function/more')->getDbReadObject();
		$writeConnection = Mage::helper('function/more')->getDbWriteObject();
		$post = $this->getRequest()->getPost();	
		
		$dname_qry = $readConnection->select()->from('drop_device', array('device_name'))->where("id=".$post['type_id']);
		$dname_res = $readConnection->fetchRow($dname_qry); 
		
		$base_url = Mage::getStoreConfig('web/unsecure/base_url');
		$bname =  Mage::getModel('catalog/product_url')->formatUrlKey($post['bname']);
		$bvalue = Mage::getModel('catalog/product_url')->formatUrlKey($post['bvalue']);
		$type_name = Mage::getModel('catalog/product_url')->formatUrlKey($dname_res['device_name']);
		
		$select = $readConnection->select()->from('drop_data', array('id'))->where("type_id=".$post['type_id']." and brand_name = '".$post['bname']."' and device = '".$post['bvalue']."'"); 
		$rowArray = $readConnection->fetchRow($select);  
		if($rowArray['id'] != '')
		{			
			Mage::getSingleton('core/session')->addError(Mage::helper('dropdown')->__('Category Value Already Exist .'));	
       		$this->_redirect('*/*');
		}
		else
		{
			$writeConnection->beginTransaction();
			$fields = array();
			$fields['type_id'] = $post['type_id'];
			$fields['brand_name'] = $post['bname'];
			$fields['device'] = $post['bvalue'];
			$fields['link'] = $base_url.'shop/'.$type_name.'/'.$bname.'/'.$bvalue.'.html';
			$fields['position'] = '0';
			$fields['active'] = '1';
			$writeConnection->insert('drop_data', $fields);
			$writeConnection->commit();
			Mage::getSingleton('core/session')->addSuccess(Mage::helper('dropdown')->__('Category Value Added Successfully .'));	
       		$this->_redirect('*/*');
		}
	}
}