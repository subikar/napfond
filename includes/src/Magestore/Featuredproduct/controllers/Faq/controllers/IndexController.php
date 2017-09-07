<?php
class Magestore_Faq_IndexController extends Mage_Core_Controller_Front_Action
{
    public function viewAction()
    {  		
		$store_id = Mage::app()->getStore()->getId();
		$id = $this->getRequest()->getParam("id",0);
		$category_id = $this->getRequest()->getParam("category_id",0);
		$question = Mage::getModel("faq/faq")
								->setStoreId($store_id)
								->load($id);
								
							
		if($question->getId() || $category_id)
		{
			if(!$category_id)
			{
				$category_id = $question->getCategoryId();
			}
			$questions = Mage::getModel("faq/faq")
									->setStoreId($store_id)
									->getQuestions($category_id);
			Mage::register("questions", $questions);
			Mage::register("faq_id",$question->getId());
			Mage::register("category_id",$category_id);
			
			
		}
		else
		{
			Mage::register("questions", null);
		}
		
		
		$this->loadLayout();     
		$this->renderLayout();
    }
	public function indexAction()
    {	
		$this->loadLayout();     
		$this->renderLayout();
    }
}