<?php
class Gravita_Maskimage_Adminhtml_MaskimageformController extends Mage_Adminhtml_Controller_Action
{
	public function indexAction()
    {
		
		if($_POST){
			
			echo "sdfadsfasdfas";exit;
			
		}
		
		$this->loadLayout()->renderLayout();		
	}
	
	public function postAction()
    {
		$post = $this->getRequest()->getPost();
		
		if($post)
		{
			echo "sdfasdfas";
			
		}
		exit;
	}
	
	
	
}