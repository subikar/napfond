<?php

class Magestore_Faq_Adminhtml_FaqController extends Mage_Adminhtml_Controller_action
{

	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('faq/faq')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('FAQ Manager'), Mage::helper('adminhtml')->__('FAQ Manager'));
		
		return $this;
	}   
 
	public function indexAction() {
		$this->_initAction()
			->renderLayout();
	}

	public function editAction() {
	
		$store_id = $this->getRequest()->getParam('store');
		$stores = Mage::app()->getStores();
		if(count($stores) == 1)
		{
			foreach($stores as $store)
			{
				$store_id = $store->getStoreId();
			}
		}
        		
	
	
		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('faq/faq')
					->setStoreId($store_id)
					->load($id);

		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}

			Mage::register('faq_data', $model);

			$this->loadLayout();
			$this->_setActiveMenu('faq/faq');

			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('FAQ Manager'), Mage::helper('adminhtml')->__('FAQ Manager'));
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('FAQ News'), Mage::helper('adminhtml')->__('FAQ News'));

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

			$this->_addContent($this->getLayout()->createBlock('faq/adminhtml_faq_edit'))
				->_addLeft($this->getLayout()->createBlock('faq/adminhtml_faq_edit_tabs'));

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('faq')->__('Item does not exist'));
			$this->_redirect('*/*/');
		}
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
			elseif(isset($data['title']))
			{
				$data['url_key'] = Mage::helper('faq')->normalizeUrlKey($data['title']);
			}
			
			
			$model = Mage::getModel('faq/faq');		
			$model->setData($data)
				->setStoreId($store_id)
				->setId($this->getRequest()->getParam('id'));
			
			try {	
				
				if ($model->getCreatedTime == NULL || $model->getUpdateTime() == NULL) {
					$model->setCreatedTime(now())
						->setUpdateTime(now());
				} else {
					$model->setUpdateTime(now());
				}	
				
				if(!$store_id)
				{
					$model->save();
					$id = $model->getId();
				}
				
				$faqStore = Mage::getModel("faq/faqstore")->loadByFaqIdStore($id,$store_id);
				
				if($store_id && $faqStore->getUrlKey() )
				{
					$data['url_key'] = $faqStore->getUrlKey();
				}
				$faqStore->addData($data);
				$faqStore->setFaqId($id);
				
				if(!$this->getRequest()->getParam('id'))
				{
					//$model->updateUrlKey();
					$stores = Mage::app()->getStores();
					foreach($stores as $store)
					{
						$faqStore->setId(null);
						$faqStore->setStoreId($store->getStoreId())
								->save();	
						//print_r($faqStore->getData()); die();		
						$faqStore->updateUrlKey();			
					}
					
					//$faqStore->setId(null);
					//$faqStore->setStoreId(0)
					//		->save();
					//$categoryStore->updateUrlKey();	
				}
				
				
				$faqStore->setStoreId($store_id);
				
				Mage::helper('faq')->cloneFaqStoreData($faqStore);
							
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
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id'),'store'=>$store_id));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('faq')->__('Unable to find item to save'));
        $this->_redirect('*/*/');
	}
	
	
	
	
 
	public function deleteAction() {
		if( $this->getRequest()->getParam('id') > 0 ) {
			try {
				$model = Mage::getModel('faq/faq');
				 
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
        $faqIds = $this->getRequest()->getParam('faq');
        if(!is_array($faqIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($faqIds as $faqId) {
                    $faq = Mage::getModel('faq/faq')->load($faqId);
                    $faq->deleteAllUrlKey();
					$faq->delete();
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
        $faqIds = $this->getRequest()->getParam('faq');
        if(!is_array($faqIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($faqIds as $faqId) {
                    $faq = Mage::getSingleton('faq/faq')
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
        $fileName   = 'faq.csv';
        $content    = $this->getLayout()->createBlock('faq/adminhtml_faq_grid')
            ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction()
    {
        $fileName   = 'faq.xml';
        $content    = $this->getLayout()->createBlock('faq/adminhtml_faq_grid')
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