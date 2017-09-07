<?php

class Mks_Rotateproduct_Adminhtml_RotateproductController extends Mage_Adminhtml_Controller_Action
{
		protected function _initAction()
		{
				$this->loadLayout()->_setActiveMenu("rotateproduct/rotateproduct")->_addBreadcrumb(Mage::helper("adminhtml")->__("Rotateproduct  Manager"),Mage::helper("adminhtml")->__("Rotateproduct Manager"));
				return $this;
		}
		public function indexAction() 
		{
			    $this->_title($this->__("Rotateproduct"));
			    $this->_title($this->__("Manager Rotateproduct"));

				$this->_initAction();
				$this->renderLayout();
		}
		public function editAction()
		{			    
			    $this->_title($this->__("Rotateproduct"));
				$this->_title($this->__("Rotateproduct"));
			    $this->_title($this->__("Edit Item"));
				
				$id = $this->getRequest()->getParam("id");
				$model = Mage::getModel("rotateproduct/rotateproduct")->load($id);
				if ($model->getId()) {
					Mage::register("rotateproduct_data", $model);
					$this->loadLayout();
					$this->_setActiveMenu("rotateproduct/rotateproduct");
					$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Rotateproduct Manager"), Mage::helper("adminhtml")->__("Rotateproduct Manager"));
					$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Rotateproduct Description"), Mage::helper("adminhtml")->__("Rotateproduct Description"));
					$this->getLayout()->getBlock("head")->setCanLoadExtJs(true);
					$this->_addContent($this->getLayout()->createBlock("rotateproduct/adminhtml_rotateproduct_edit"))->_addLeft($this->getLayout()->createBlock("rotateproduct/adminhtml_rotateproduct_edit_tabs"));
					$this->renderLayout();
				} 
				else {
					Mage::getSingleton("adminhtml/session")->addError(Mage::helper("rotateproduct")->__("Item does not exist."));
					$this->_redirect("*/*/");
				}
		}

		public function newAction()
		{

		$this->_title($this->__("Rotateproduct"));
		$this->_title($this->__("Rotateproduct"));
		$this->_title($this->__("New Item"));

        $id   = $this->getRequest()->getParam("id");
		$model  = Mage::getModel("rotateproduct/rotateproduct")->load($id);

		$data = Mage::getSingleton("adminhtml/session")->getFormData(true);
		if (!empty($data)) {
			$model->setData($data);
		}

		Mage::register("rotateproduct_data", $model);

		$this->loadLayout();
		$this->_setActiveMenu("rotateproduct/rotateproduct");

		$this->getLayout()->getBlock("head")->setCanLoadExtJs(true);

		$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Rotateproduct Manager"), Mage::helper("adminhtml")->__("Rotateproduct Manager"));
		$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Rotateproduct Description"), Mage::helper("adminhtml")->__("Rotateproduct Description"));


		$this->_addContent($this->getLayout()->createBlock("rotateproduct/adminhtml_rotateproduct_edit"))->_addLeft($this->getLayout()->createBlock("rotateproduct/adminhtml_rotateproduct_edit_tabs"));

		$this->renderLayout();

		}
		public function saveAction()
		{

			$post_data=$this->getRequest()->getPost();


				if ($post_data) {

					try {

						
				 //save image
		try{

if((bool)$post_data['productimage']['delete']==1) {

	        $post_data['productimage']='';

}
else {

	unset($post_data['productimage']);

	if (isset($_FILES)){

		if ($_FILES['productimage']['name']) {

			if($this->getRequest()->getParam("id")){
				$model = Mage::getModel("rotateproduct/rotateproduct")->load($this->getRequest()->getParam("id"));
				if($model->getData('productimage')){
						$io = new Varien_Io_File();
						$io->rm(Mage::getBaseDir('media').DS.implode(DS,explode('/',$model->getData('productimage'))));	
				}
			}
						$path = Mage::getBaseDir('media') . DS . 'rotateproduct' . DS .'rotateproduct'.DS;
						$uploader = new Varien_File_Uploader('productimage');
						$uploader->setAllowedExtensions(array('jpg','png','gif'));
						$uploader->setAllowRenameFiles(false);
						$uploader->setFilesDispersion(false);
						$destFile = $path.$_FILES['productimage']['name'];
						$filename = $uploader->getNewFileName($destFile);
						$uploader->save($path, $filename);

						$post_data['productimage']='rotateproduct/rotateproduct/'.$filename;
		}
    }
}

        } catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
				return;
        }
//save image


						$model = Mage::getModel("rotateproduct/rotateproduct")
						->addData($post_data)
						->setId($this->getRequest()->getParam("id"))
						->save();

						Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Rotateproduct was successfully saved"));
						Mage::getSingleton("adminhtml/session")->setRotateproductData(false);

						if ($this->getRequest()->getParam("back")) {
							$this->_redirect("*/*/edit", array("id" => $model->getId()));
							return;
						}
						$this->_redirect("*/*/");
						return;
					} 
					catch (Exception $e) {
						Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
						Mage::getSingleton("adminhtml/session")->setRotateproductData($this->getRequest()->getPost());
						$this->_redirect("*/*/edit", array("id" => $this->getRequest()->getParam("id")));
					return;
					}

				}
				$this->_redirect("*/*/");
		}



		public function deleteAction()
		{
				if( $this->getRequest()->getParam("id") > 0 ) {
					try {
						$model = Mage::getModel("rotateproduct/rotateproduct");
						$model->setId($this->getRequest()->getParam("id"))->delete();
						Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Item was successfully deleted"));
						$this->_redirect("*/*/");
					} 
					catch (Exception $e) {
						Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
						$this->_redirect("*/*/edit", array("id" => $this->getRequest()->getParam("id")));
					}
				}
				$this->_redirect("*/*/");
		}

		
		public function massRemoveAction()
		{
			try {
				$ids = $this->getRequest()->getPost('ids', array());
				foreach ($ids as $id) {
                      $model = Mage::getModel("rotateproduct/rotateproduct");
					  $model->setId($id)->delete();
				}
				Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Item(s) was successfully removed"));
			}
			catch (Exception $e) {
				Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
			}
			$this->_redirect('*/*/');
		}
			
		/**
		 * Export order grid to CSV format
		 */
		public function exportCsvAction()
		{
			$fileName   = 'rotateproduct.csv';
			$grid       = $this->getLayout()->createBlock('rotateproduct/adminhtml_rotateproduct_grid');
			$this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
		} 
		/**
		 *  Export order grid to Excel XML format
		 */
		public function exportExcelAction()
		{
			$fileName   = 'rotateproduct.xml';
			$grid       = $this->getLayout()->createBlock('rotateproduct/adminhtml_rotateproduct_grid');
			$this->_prepareDownloadResponse($fileName, $grid->getExcelFile($fileName));
		}
}
