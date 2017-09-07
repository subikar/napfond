<?php
class Gravita_Stockimage_Adminhtml_StockimageformController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->loadLayout()->renderLayout();
		/*$resource = Mage::getSingleton('core/resource');
		$readConnection = $resource->getConnection('core_read');
		$writeConnection = $resource->getConnection('core_write');*/		
    } 
	
	public function postAction()
    {
        $post = $this->getRequest()->getPost();			
		$cat_name = strtolower($post['cat_name']);	
		
		$readConnection = Mage::getSingleton('core/resource')->getConnection('core_read');
		$writeConnection = Mage::getSingleton('core/resource')->getConnection('core_write');	
		
		$select = $readConnection->select()
		->from('stock_cat', array('cat_id')) // select * from tablename or use array('id','name') selected values
		->where('cat_name=?',$cat_name);         // group by name
		$rowsArray = $readConnection->fetchAll($select); // return all rows
		$rowArray = $readConnection->fetchRow($select);   //return row
		if($rowArray['cat_id'] != '')
		{
			$cat_id = $rowArray['cat_id'];
			if(!is_dir('media/stock/'.$cat_name))
			{
				mkdir('media/stock/'.$cat_name, 0777, true);
			}
		}
		else
		{					
			$writeConnection->beginTransaction();
			$fields = array();
			$fields['cat_name'] = $cat_name;
			$fields['active'] = '1';
			$fields['position'] = '0';
			$fields['created_dt'] = date("y-m-d H:i:s");
			$fields['updated_dt'] = date("y-m-d H:i:s");
			$writeConnection->insert('stock_cat', $fields);
			$writeConnection->commit();
			
			$select = $readConnection->select()
			->from('stock_cat', array('cat_id')) 
			->where('cat_name=?',$cat_name);   
			$rowArray = $readConnection->fetchRow($select);
			$cat_id = $rowArray['cat_id'];
			
			if(!is_dir('media/stock/'.$cat_name))
			{
				mkdir('media/stock/'.$cat_name, 0777, true);
			}
		}
				
		/*//$media = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA);
		
		
		$media = 'media/stock/'.$cat_name;
        $filename = $_FILES['simg']['name'];
		if($filename!='')
		{
			$temp_image_type = $_FILES['simg']['type'];
			if($temp_image_type == 'image/png' || $temp_image_type == 'image/gif' || $temp_image_type == 'image/jpg' || $temp_image_type == 'image/jpeg') 
			{
				//$random_digit = rand(0,456764);
				//$filename = $random_digit.$_FILES['simg']['name'];
				$filename = $_FILES['simg']['name'];
				$uploadedfile =  $_FILES['simg']['tmp_name'];
				$new_filename = $media."/".$filename;
				move_uploaded_file($uploadedfile, $new_filename);
				
				$writeConnection->beginTransaction();
				$fields = array();
				$fields['img_name'] = $filename;
				$fields['cat_id'] = $cat_id;
				$fields['position'] = '0';
				$writeConnection->insert('stock_img', $fields);
				$writeConnection->commit();
			}
		}*/
		Mage::getSingleton('core/session')->addSuccess(Mage::helper('stockimage')->__('Category Added Successfully.'));		
        $this->_redirect('*/*');
    } 
	
	public function editAction()
	{
		$post = $this->getRequest()->getPost();	
		$writeConnection = Mage::getSingleton('core/resource')->getConnection('core_write');
		if($post['del_cat'] == 0)
		{			
			$writeConnection->beginTransaction();
			$fields = array();
			$fields['cat_name'] = $post['edit_cat_name'];
			$where = $writeConnection->quoteInto('cat_id =?', $post['edit_cat_id']);
			
			$writeConnection->update('stock_cat', $fields, $where);
			$writeConnection->commit();
			Mage::getSingleton('core/session')->addSuccess(Mage::helper('stockimage')->__('Category Updated Successfully.'));
			$this->_redirect('*/*');
		}
		else
		{
			$condition = array($writeConnection->quoteInto('cat_id=?', $post['edit_cat_id']));
    		$writeConnection->delete('stock_cat', $condition);
			Mage::getSingleton('core/session')->addSuccess(Mage::helper('stockimage')->__('Category Deleted Successfully.'));
			$this->_redirect('*/*');
		}
	}
	
	public function img_postAction()
    {
		$post = $this->getRequest()->getPost();
		$cat_id = strtolower($post['img_cat']);
		$readConnection = Mage::getSingleton('core/resource')->getConnection('core_read');
		$writeConnection = Mage::getSingleton('core/resource')->getConnection('core_write');
		
		$select = $readConnection->select()
		->from('stock_cat', array('cat_name')) // select * from tablename or use array('id','name') selected values
		->where('cat_id=?',$cat_id);         // group by name
		$rowArray = $readConnection->fetchRow($select);   //return row
		if($rowArray['cat_name'] != '')
		{
			$cat_name = $rowArray['cat_name'];
		}
				
		$media = 'media/stock/'.$cat_name;
		foreach ( $_FILES['simage']['name'] as $i => $name )
		{
			$temp_image_type = $_FILES['simage']['type'][$i];
			if($temp_image_type == 'image/png' || $temp_image_type == 'image/gif' || $temp_image_type == 'image/jpg' || $temp_image_type == 'image/jpeg') 
			{
				//$random_digit = rand(0,456764);
				//$filename = $random_digit.$_FILES['simg']['name'];
				$filename = $_FILES['simage']['name'][$i];
				$uploadedfile =  $_FILES['simage']['tmp_name'][$i];
				$new_filename = $media."/".$filename;
				move_uploaded_file($uploadedfile, $new_filename);
				
				$writeConnection->beginTransaction();
				$fields = array();
				$fields['img_name'] = $filename;
				$fields['cat_id'] = $cat_id;
				$fields['position'] = '0';
				$writeConnection->insert('stock_img', $fields);
				$writeConnection->commit();
				
				$imageObj = new Varien_Image($new_filename);
				
				$imageObj->constrainOnly(TRUE);
				
				$imageObj->keepAspectRatio(TRUE);
				
				$imageObj->keepFrame(false);
				
				$imageObj->keepTransparency(True);
				
				$imageObj->setImageBackgroundColor(false);
				
				$imageObj->backgroundColor(false);
				
				$imageObj->quality(100);
				
				$imageObj->setWatermarkImageOpacity(0);
				
				$imageObj->resize(600, 600); 
				
				$imageObj->save($new_filename);
			}			
		}
		
		Mage::getSingleton('core/session')->addSuccess(Mage::helper('stockimage')->__('Images Insert Successfully.'));		
        $this->_redirect('*/*');
	}
}