<?php
class Gravita_Eform_IndexController extends Mage_Core_Controller_Front_Action
{
	public function indexAction()
	{
		$this->loadLayout();
		$this->renderLayout();
	}
	
	public function enquirydetailAction()
	{
		$params = $this->getRequest()->getParams();	
		//print_r($params);die;
		$cur_url = $params['cur_url'];
		$name = $params['name'];
		$name =  explode(" ",$name);
		$name =  $name[0];
		$company = $params['company'];
		$email = $params['email'];
		$address = $params['address'];
		$phone = $params['phone'];
		$comment = $params['comment'];
		
		$processedTemplate = 'Name : '.$name.' <br> Email : '.$email.' <br> Address : '.$address.' <br> Company : '.$company.' <br> Telephone : '.$phone.'<br> Comment : '.$comment.'';
		$senderName =  Mage::getStoreConfig('trans_email/ident_general/name'); 
		$senderEmail = Mage::getStoreConfig('trans_email/ident_general/email');

		$toName = 'Enquiry'; 
		$toEmail = 'corporate@bhishoom.com';
		$mail = Mage::getModel('core/email')
		 ->setToName($toName)
		 ->setToEmail($toEmail)
		 ->setBody($processedTemplate)
		 ->setSubject('There is a new corporate enquiry')
		 ->setFromEmail($senderEmail)
		 ->setFromName($senderName)
		 ->setType('html');
		$mail->send();
		
		try
		{		 	 	
			$emailTemplate = Mage::getModel('core/email_template')->loadDefault('eform_email_template');			
		 	$emailTemplateVariables = array();
			$emailTemplateVariables['name'] = $name;	
			$emailTemplate->setSenderName($senderName);
            $emailTemplate->setSenderEmail($senderEmail);
            $emailTemplate->setType('html');
            $emailTemplate->setTemplateSubject('We have received your query at Bhishoom.com');
            $emailTemplate->send($email, $name, $emailTemplateVariables);
			Mage::getSingleton('core/session')->setSellonbhisoomMsghead1('Thank you for your query. We will get back to you shortly.');
			Mage::getSingleton('core/session')->setSellonbhisoomMsghead2('for submitting your enquiry.');
			Mage::getSingleton('core/session')->setSellonbhisoomMsg('Thank you for submitting your enquiry.Our team will contact you shortly.');
			Mage::getSingleton('core/session')->addSuccess(Mage::helper('sform')->__('Your enquiry has been submitted.'));
		 }
		 catch(Exception $error)
		 {
			 Mage::getSingleton('core/session')->addError($error->getMessage());
			 return false;
		 }					
		Mage::app()->getFrontController()->getResponse()->setRedirect($cur_url);		
	}
}
?>