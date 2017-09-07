<?php
class Gravita_Sform_IndexController extends Mage_Core_Controller_Front_Action
{
	public function indexAction()
	{
		$this->loadLayout();
		$this->renderLayout();
	}
	
	public function sellerdetailAction()
	{
		$params = $this->getRequest()->getParams();	
		$cur_url = $params['cur_url'];
		$cur_name = strtolower(str_replace(" ", "", $params['name']));
		$cur_date = date("Y-m-d");
		$link = '';	
		$link_count = count($params['sform_link']);
		if($link_count > 1)
		{
			for($i=0;$i<$link_count;$i++)
			{
				$link.= $params['sform_link'][$i];
				if($i<$link_count-1){$link.=',';}
			}
		}	
		else
		{
			$link = $params['sform_link'][0];
		}
		
		$rar = $_FILES['c_img']['tmp_name'];
		
		$mediaDir = Mage::getBaseDir('media');
		
		$mediaDirUrl = Mage::getBaseUrl('media');
		
		$targetFolder = $mediaDir."/seller/";
		
		$c_img_url = '';
		$p_img_url = '';
		
		if($_FILES['c_img']['name'] != '')
		 {
			$c_img_url = $this->uploadSellerImageFiles($_FILES['c_img'],$targetFolder);
			
			if($c_img_url == '')
			 {
				Mage::app()->getFrontController()->getResponse()->setRedirect($cur_url);
			 }
			$c_img_url = $mediaDirUrl. "seller/". $c_img_url;
		 }
		if($_FILES['p_img']['name'] != '')
		 {
			$p_img_url = $this->uploadSellerImageFiles($_FILES['p_img'],$targetFolder);
			
			if($p_img_url == '')
			 {
				Mage::app()->getFrontController()->getResponse()->setRedirect($cur_url);
			 }
			 $p_img_url = $mediaDirUrl. "seller/" .$p_img_url;
		 }
		 
		if($c_img_url == '' && $p_img_url == '' && $link == '')
		{
			Mage::getSingleton('core/session')->setSellonbhisoomMsghead1('Error!!!');
			Mage::getSingleton('core/session')->setSellonbhisoomMsghead2('you missing to fill some details.');
			Mage::getSingleton('core/session')->setSellonbhisoomMsg('Please upload or provide link(s) to your example work.');		
			Mage::app()->getFrontController()->getResponse()->setRedirect($cur_url);
		}		 
		else
		{
			$name = $params['name'];
			$name =  explode(" ",$name);
		    $name =  $name[0];
			$email = $params['email'];
			$address = $params['address'];
			$country_code = $params['country'];
			$countryModel = Mage::getModel('directory/country')->loadByCode($country_code);
			$countryName = $countryModel->getName();
			$phone = $params['phone'];			
			$processedTemplate = 'Name : '.$name.' <br> Email : '.$email.' <br> Address : '.$address.' <br> Country : '.$countryName.' <br> Telephone : '.$phone.'';
			if($link!='')
			 $processedTemplate .= '<br> Design Example Link: '.$link;
			if($p_img_url != '') 
			 $processedTemplate .= '<br> Design Image Link: '.$p_img_url;			
		
			$senderName =  Mage::getStoreConfig('trans_email/ident_general/name'); 
			$senderEmail = Mage::getStoreConfig('trans_email/ident_general/email');			
			/* Sender Name */
			$toName = 'Artist'; 
			/* Sender Email */
			$toEmail = 'artist@bhishoom.com';
			$mail = Mage::getModel('core/email')
			 ->setToName($toName)
			 ->setToEmail($toEmail)
			 ->setBody($processedTemplate)
			 ->setSubject('Sale Page : Designer work example from sell page.')
			 ->setFromEmail($senderEmail)
			 ->setFromName($senderName)
			 ->setType('html');
			  $mail->send();
			try
			{
			 	$emailTemplate = Mage::getModel('core/email_template')->loadDefault('sform_email_template');	 	
			 	$emailTemplateVariables = array();
				$emailTemplateVariables['name'] = $name;	
				$emailTemplate->setSenderName($senderName);
	            $emailTemplate->setSenderEmail($senderEmail);
	            $emailTemplate->setType('html');
	            $emailTemplate->setTemplateSubject('Your design submission confirmation at Bhishoom.com');
	            $emailTemplate->send($email, $name, $emailTemplateVariables);
				Mage::getSingleton('core/session')->setSellonbhisoomMsghead1('Thanks.');
				Mage::getSingleton('core/session')->setSellonbhisoomMsghead2('for submitting your work.');
				Mage::getSingleton('core/session')->setSellonbhisoomMsg('Thank you for submitting your details.Our team will contact you shortly.');
				Mage::getSingleton('core/session')->addSuccess(Mage::helper('sform')->__('Your design has been submitted.'));
			}
			catch(Exception $error)
			{
				 Mage::getSingleton('core/session')->addError($error->getMessage());
				//return false;
			}					
			Mage::app()->getFrontController()->getResponse()->setRedirect($cur_url);
		}
	}
	
	public function promodetailAction()
	{
		$params = $this->getRequest()->getParams();	
		$cur_url = $params['cur_url'];
		$email = $params['email'];
		$firstname = $params['fname'];
		//$lastname = $params['lname'];
		$pwd_length = 7;
		
		$customer = Mage::getModel('customer/customer');
		$customer->setWebsiteId(Mage::app()->getWebsite()->getId());
		$customer->loadByEmail($email);
		if(!$customer->getId()) 
		{
		  $customer->setEmail($email); 
		  $customer->setFirstname($firstname);
		  $customer->setLastname($firstname);
		  $customer->setPassword($customer->generatePassword($pwd_length));		
		}
		try
		{		
		  $customer->save();
		  $customer->setConfirmation(null);
		  $customer->save();
		  $customer->sendNewAccountEmail();	
		  $customerId = $customer->getId();
		  $session = Mage::getSingleton('customer/session');
          $session->loginById($customerId);	
		} 
		catch(Exception $e)
		{
		   Mage::log($e->__toString());
		}
		Mage::app()->getFrontController()->getResponse()->setRedirect($cur_url);
	}
	
	
	public function uploadSellerImageFiles($sourceFile,$targetFolder)
	 {
	 
					$errors     = array();
					$maxsize    = 2097152;
					$acceptable = array(
						'application/pdf',
						'image/jpeg',
						'image/jpg',
						'image/gif',
						'image/png'
					);

					if(($sourceFile['size'] >= $maxsize) || ($sourceFile["size"] == 0)) {
						Mage::getSingleton('core/session')->addError('File too large. File must be less than 2 megabytes.');
					}	
					else if((!in_array($sourceFile['type'], $acceptable)) && (!empty($sourceFile["type"]))) {
						Mage::getSingleton('core/session')->addError('Invalid file type. Only PDF, JPG, GIF and PNG types are accepted.');
					}
					else{
				 
					$fname = strtolower($sourceFile['name']);

					$rawBaseName = pathinfo($fname, PATHINFO_FILENAME );
					$extension = pathinfo($fname, PATHINFO_EXTENSION );
					$counter = 0;
					while(file_exists($targetFolder.$fname)) {
						$fname = $rawBaseName . $counter . '.' . $extension;
						$counter++;
					};

					copy($sourceFile['tmp_name'],$targetFolder.$fname); 
					return $fname;		
					}
				
	 }
	
	
}
?>