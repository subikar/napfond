<?php
class Gravita_Relatedbhishoom_Adminhtml_RelatedbhishoomformController extends Mage_Adminhtml_Controller_Action
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
		
		if($post['submit_img_rel_othr']){
			
			
			
				/*if(is_uploaded_file($_FILES['filename']['tmp_name'])) {
					echo "<h1>" . "File ". $_FILES['filename']['name'] ." uploaded successfully." . "</h1>";
					echo "<h2>Displaying contents:</h2>";
					readfile($_FILES['filename']['tmp_name']);
				}*/

				//Import uploaded file to Database
			if(trim($_FILES['related_product']['name']) != ''){	
				$handle = fopen($_FILES['related_product']['tmp_name'], "r");
				$temp = 0;
				while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
					
					if($temp ==0)
					{
						$temp++;
						continue;
					}
					
					$catid = $data[0];
					$product_sku = addslashes($data[1]);
					$realted_product_skus = addslashes($data[2]);
					
					$query = 'SELECT id FROM a_related_products WHERE cat_id = "'. $catid . '" and product_sku = "'.$product_sku.'" LIMIT 1';
					$tableRowId = $readConnection->fetchOne($query);
					
					if($tableRowId > 0){
						$import="UPDATE a_related_products set cat_id = '".$catid."',product_sku = '".$product_sku."',realted_product_skus = '".$realted_product_skus."' where cat_id = '".$catid."' and product_sku = '".$product_sku."'";						
					}
					else{ 
						$import="INSERT into a_related_products(cat_id,product_sku,realted_product_skus) values('".$catid."','".$product_sku."','".$realted_product_skus."')";
					}
					
					$writeConnection->query($import);
					//mysql_query($import) or die(mysql_error());
				}

				fclose($handle);			
			}
			if(trim($_FILES['other_product']['name']) != ''){
				$handle = fopen($_FILES['other_product']['tmp_name'], "r");
$temp = 0;
				while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
					
					
					if($temp ==0)
					{
						$temp++;
						continue;
					}
					
					
					$catid = $data[0];
					$product_sku = addslashes($data[1]);
					$realted_product_skus = addslashes($data[2]);
					$realted_product_catids = $data[3];
					
					$query = 'SELECT id FROM a_other_products WHERE cat_id = "'. $catid . '" and product_sku = "'.$product_sku.'" LIMIT 1';
					$tableRowId = $readConnection->fetchOne($query);
					
					if($tableRowId > 0){
						$import="UPDATE a_other_products set cat_id = '".$catid."',product_sku = '".$product_sku."',realted_product_skus = '".$realted_product_skus."',related_cat_ids='".$realted_product_catids."' where cat_id = '".$catid."' and product_sku = '".$product_sku."'";						
					}
					else{ 
						$import="INSERT into a_other_products(cat_id,product_sku,realted_product_skus,related_cat_ids) values('".$catid."','".$product_sku."','".$realted_product_skus."','".$realted_product_catids."')";
					}
					
					$writeConnection->query($import);
					//mysql_query($import) or die(mysql_error());
				}

				fclose($handle);			
			}
			
			
		}
				
		
		
		/*$fields = array();
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
			
		}*/
		
		
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
		
		Mage::getSingleton('core/session')->addSuccess(Mage::helper('stockimage')->__('Uploaded Successfully .'));	
        $this->_redirect('*/*');
    } 	
}