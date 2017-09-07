<?php
class Gravita_Stockimage_Adminhtml_StockimageformController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->loadLayout()->renderLayout();	
    } 
	
	public function postAction()
    {
        $post = $this->getRequest()->getPost();
		
		$readConnection = Mage::getSingleton('core/resource')->getConnection('core_read');
		$writeConnection = Mage::getSingleton('core/resource')->getConnection('core_write');	
		
		$writeConnection->beginTransaction();
		$fields = array();
		$fields['cat_id1'] = $post['cat1'];
		$fields['cat_id2'] = $post['sub_cat1'];
		$fields['cat_id3'] = $post['sub_cat2'];
		
		$media = 'media/filter_img/';
		
		if($post['col_count'] > 0)
		{
			for($i=0;$i<$post['col_count'];$i++)
			{
				$col_name = $post['col_'.$i];
				$col_image = $_FILES['file_'.$col_name]['name'][0];
				$col_tmp_image = $_FILES['file_'.$col_name]['tmp_name'][0];
				
				if($col_image != '')
				{
					$fields['attr_id'] = $post['color_id'];
					$fields['attr_name'] = 'color';
					$fields['attr_val'] = $col_name;
					
					$random_digit = rand(0,456764);
					$filename = $random_digit.$col_image;
					$uploadedfile =  $col_tmp_image;
					$new_filename = $media."/".$filename;
					move_uploaded_file($uploadedfile, $new_filename);
					
					$fields['attr_img'] = $filename;
					$fields['position'] = 0;
					$writeConnection->insert('stock_img', $fields);
					$writeConnection->commit();		
				}
			}
		}
		
		if($post['case_count'] > 0)
		{
			for($i=0;$i<$post['case_count'];$i++)
			{
				$case_name = $post['case_'.$i];
				$case_image = $_FILES['file_'.$case_name]['name'][0];
				$case_tmp_image = $_FILES['file_'.$case_name]['tmp_name'][0];
				
				if($case_image != '')
				{
					$fields['attr_id'] = $post['case_style_id'];
					$fields['attr_name'] = 'style';
					$fields['attr_val'] = $case_name;
					
					$random_digit = rand(0,456764);
					$filename = $random_digit.$case_image;
					$uploadedfile =  $case_tmp_image;
					$new_filename = $media."/".$filename;
					move_uploaded_file($uploadedfile, $new_filename);
					
					$fields['attr_img'] = $filename;
					$fields['position'] = 0;
					$writeConnection->insert('stock_img', $fields);
					$writeConnection->commit();			
				}
			}
			
		}
		
		
		/*if($_FILES['col_img']['name'] != '')
		{
			$fields['attr_id'] = $post['color_id'];
			$fields['attr_name'] = 'color';
			$fields['attr_val'] = $post['col_name'];
			
			$random_digit = rand(0,456764);
			$filename = $random_digit.$_FILES['col_img']['name'];
			$uploadedfile =  $_FILES['col_img']['tmp_name'];
			$new_filename = $media."/".$filename;
			move_uploaded_file($uploadedfile, $new_filename);
			
			$fields['attr_img'] = $filename;
			$fields['position'] = 0;
			$writeConnection->insert('stock_img', $fields);
			$writeConnection->commit();		
		}*/
		
		/*if($_FILES['mod_img']['name'] != '')
		{
			$fields['attr_id'] = $post['model_id'];
			$fields['attr_name'] = 'model';
			$fields['attr_val'] = $post['mod_name'];
			
			$random_digit = rand(0,456764);
			$filename = $random_digit.$_FILES['mod_img']['name'];
			$uploadedfile =  $_FILES['mod_img']['tmp_name'];
			$new_filename = $media."/".$filename;
			move_uploaded_file($uploadedfile, $new_filename);
			
			$fields['attr_img'] = $filename;
			$fields['position'] = 0;
			$writeConnection->insert('stock_img', $fields);
			$writeConnection->commit();	
		}	
			
		if($_FILES['des_img']['name'] != '')
		{
			$fields['attr_id'] = $post['case_designer_id'];
			$fields['attr_name'] = 'designer';
			$fields['attr_val'] = $post['des_name'];
			
			$random_digit = rand(0,456764);
			$filename = $random_digit.$_FILES['des_img']['name'];
			$uploadedfile =  $_FILES['des_img']['tmp_name'];
			$new_filename = $media."/".$filename;
			move_uploaded_file($uploadedfile, $new_filename);
			
			$fields['attr_img'] = $filename;
			$fields['position'] = 0;
			$writeConnection->insert('stock_img', $fields);
			$writeConnection->commit();	
		}*/
		
		Mage::getSingleton('core/session')->addSuccess(Mage::helper('stockimage')->__('Images Inserted Successfully .'));	
        $this->_redirect('*/*');
    } 	
}