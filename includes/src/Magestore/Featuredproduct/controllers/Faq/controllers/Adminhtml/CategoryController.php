<?php

class Magestore_Faq_Adminhtml_CategoryController extends Mage_Adminhtml_Controller_action
{

	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('faq/category')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Categories Manager'), Mage::helper('adminhtml')->__('Category Manager'));
		
		return $this;
	}   
 
	public function indexAction() {
		$this->_initAction()
			->renderLayout();
	}

	public function editAction() {
		$id     = $this->getRequest()->getParam('id');
		$store_id = $this->getRequest()->getParam('store');
		$stores = Mage::app()->getStores();
		if(count($stores) == 1)
		{
			foreach($stores as $store)
			{
				$store_id = $store->getStoreId();
			}
		}
		
		
		$model  = Mage::getModel('faq/category')
					->setStoreId($store_id)					
					->load($id);
			
		//if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}

			Mage::register('faqcategory_data', $model);

			$this->loadLayout();
			$this->_setActiveMenu('faq/category');

			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Category Manager'), Mage::helper('adminhtml')->__('Category Manager'));
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Category News'), Mage::helper('adminhtml')->__('Category News'));

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

			$this->_addContent($this->getLayout()->createBlock('faq/adminhtml_category_edit'))
				->_addLeft($this->getLayout()->createBlock('faq/adminhtml_category_edit_tabs'));

			$this->renderLayout();
		//} else {
		//	Mage::getSingleton('adminhtml/session')->addError(Mage::helper('faq')->__('Item does not exist'));
		//	$this->_redirect('*/*/');
		//}
	}
 
	public function newAction() {
		$this->editAction();
	}
 
	public function saveAction() {
		
		$store_id = $this->getRequest()->getParam('store');
		$id = $this->getRequest()->getParam('id');
		
		if ($data = $this->getRequest()->getPost()) {	
			
			if(isset($data['url_key']) && $data['url_key'])
			{				
				$data['url_key'] = Mage::helper('faq')->normalizeUrlKey($data['url_key']);
			}
			elseif(isset($data['name']))			
			{
				$data['url_key'] = Mage::helper('faq')->normalizeUrlKey($data['name']);
			}
			
			//$model = Mage::getModel('faq/category');		
			//$model->setData($data)
				//->setStoreId($store_id)
				//->setId($this->getRequest()->getParam('id'));
			
			try {
			
				if(!$store_id)
				{
					$model = Mage::getModel('faq/category');		
					$model->setData($data)					
					->setId($this->getRequest()->getParam('id'));
					$model->save();
					//$model->updateUrlKey();			

					$id = $model->getCategoryId();	
				}
								
				$categoryStore = Mage::getModel("faq/categorystore")->loadByCatIdStore($id,$store_id);
				if($store_id && $categoryStore->getUrlKey() )
				{
					$data['url_key'] = $categoryStore->getUrlKey();
				}
				$categoryStore->addData($data);
				$categoryStore->setCategoryId($id);
				
				if(!$this->getRequest()->getParam('id'))
				{					
					$stores = Mage::app()->getStores();
					foreach($stores as $store)
					{
						$categoryStore->setId(null);
						$categoryStore->setStoreId($store->getStoreId())
								->save();	
						$categoryStore->updateUrlKey();			
					}
										
				}
				else
				{																				
					$categoryStore->setStoreId($store_id);					
					$categoryStore->save();															
				}
				
				
				$categoryStore->setStoreId($store_id);
								
				Mage::helper('faq')->cloneCategoryStoreData($categoryStore);	
				
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('faq')->__('Item was successfully saved'));
				Mage::getSingleton('adminhtml/session')->setFormData(false);

				if ($this->getRequest()->getParam('back')) {
					$this->_redirect('*/*/edit', array('id' => $id,'store'=>$store_id));
					return;
				}
				$this->_redirect('*/*/');
				return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $id,'store'=>$store_id));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('faq')->__('Unable to find item to save'));
        $this->_redirect('*/*/');
	}
 
	public function deleteAction() {
		if( $this->getRequest()->getParam('id') > 0 ) {
			try {
				$model = Mage::getModel('faq/category');
				 
				$model->load($this->getRequest()->getParam('id'));
				$model->deleteAllUrlKey();
				$model->delete();
					 
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully deleted'));
				$this->_redirect('*/*/');
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
		}
		$this->_redirect('*/*/');
	}

    public function massDeleteAction() {
        $faqIds = $this->getRequest()->getParam('category');
        if(!is_array($faqIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($faqIds as $faqId) {
                    $category = Mage::getModel('faq/category')->load($faqId);
					$category->deleteAllUrlKey();
                    $category->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($faqIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
	
    public function massStatusAction()
    {
        $faqIds = $this->getRequest()->getParam('category');
        if(!is_array($faqIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($faqIds as $faqId) {
                    $faq = Mage::getSingleton('faq/category')
                        ->load($faqId)
                        ->setStatus($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($faqIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
  
    public function exportCsvAction()
    {
        $fileName   = 'faqcategory.csv';
        $content    = $this->getLayout()->createBlock('faq/adminhtml_category_grid')
            ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction()
    {
        $fileName   = 'faqcategory.xml';
        $content    = $this->getLayout()->createBlock('faq/adminhtml_category_grid')
            ->getXml();

        $this->_sendUploadResponse($fileName, $content);
    }

    protected function _sendUploadResponse($fileName, $content, $contentType='application/octet-stream')
    {
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK','');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename='.$fileName);
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($content));
        $response->setHeader('Content-type', $contentType);
        $response->setBody($content);
        $response->sendResponse();
        die;
    }
}