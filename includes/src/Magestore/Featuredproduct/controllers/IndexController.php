<?php
class Magestore_Featuredproduct_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
    	
    	/*
    	 * Load an object by id 
    	 * Request looking like:
    	 * http://site.com/featuredproduct?id=15 
    	 *  or
    	 * http://site.com/featuredproduct/id/15 	
    	 */
    	/* 
		$featuredproduct_id = $this->getRequest()->getParam('id');

  		if($featuredproduct_id != null && $featuredproduct_id != '')	{
			$featuredproduct = Mage::getModel('featuredproduct/featuredproduct')->load($featuredproduct_id)->getData();
		} else {
			$featuredproduct = null;
		}	
		*/
		
		 /*
    	 * If no param we load a the last created item
    	 */ 
    	/*
    	if($featuredproduct == null) {
			$resource = Mage::getSingleton('core/resource');
			$read= $resource->getConnection('core_read');
			$featuredproductTable = $resource->getTableName('featuredproduct');
			
			$select = $read->select()
			   ->from($featuredproductTable,array('featuredproduct_id','title','content','status'))
			   ->where('status',1)
			   ->order('created_time DESC') ;
			   
			$featuredproduct = $read->fetchRow($select);
		}
		Mage::register('featuredproduct', $featuredproduct);
		*/

			
		$this->loadLayout();     
		$this->renderLayout();
    }
}