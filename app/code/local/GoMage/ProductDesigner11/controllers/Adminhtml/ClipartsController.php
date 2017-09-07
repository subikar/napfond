<?php
/**
 * GoMage Product Designer Extension
 *
 * @category     Extension
 * @copyright    Copyright (c) 2013 GoMage (http://www.gomage.com)
 * @author       GoMage
 * @license      http://www.gomage.com/license-agreement/  Single domain license
 * @terms of use http://www.gomage.com/terms-of-use/
 * @version      Release: 1.0.0
 * @since        Available since Release 1.0.0
 */

/**
 * Admin cliparts controller
 *
 * @category   GoMage
 * @package    GoMage_ProductDesigner
 * @subpackage controllers
 * @author     Roman Bublik <rb@gomage.com>
 */
class GoMage_ProductDesigner_Adminhtml_ClipartsController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->_forward('edit');
    }

    public function addAction() {
        $this->_forward('edit');
    }

    public function editAction()
    {
        /**
         * @var $request Mage_Core_Controller_Request_Http
         * @var $category GoMage_ProductDesigner_Model_Clipart_Category
         */
        $request = $this->getRequest();
        $categoryId = (int) $request->getParam('id', false);
        $category = Mage::getModel('gomage_designer/clipart_category');

        //If edit exist category
        if ($categoryId) {
            $category->load($categoryId);
            if($category->getId())
            {
                Mage::register('category', $category);
            }
        }
        $this->loadLayout();
        // If Request is AJAX
        if ($this->getRequest()->getQuery('isAjax')) {
            $breadcrumbsPath = '';
            if($category->getId()) {
                // prepare breadcrumbs of selected category, if any
                $breadcrumbsPath = $category->getPath();
                if (!empty($breadcrumbsPath)) {
                    $breadcrumbsPath = explode('/', $breadcrumbsPath);
                    // no need to get parent breadcrumbs if deleting category level 1
                    if (count($breadcrumbsPath) <= 1) {
                        $breadcrumbsPath = '';
                    }
                    else {
                        $breadcrumbsPath = implode('/', $breadcrumbsPath);
                    }
                }
            }

            $clipartBlock = $this->getLayout()->getBlock('cliparts_edit');
            $eventResponse = new Varien_Object(array(
                'content' => $clipartBlock->getFormHtml()
                    . $this->getLayout()->getBlock('cliparts_categories_tree')
                        ->getBreadcrumbsJavascript($breadcrumbsPath, 'editingCategoryBreadcrumbs'),
                'messages' => $this->getLayout()->getMessagesBlock()->getGroupedHtml(),
            ));

            $this->getResponse()->setBody(
                Mage::helper('core')->jsonEncode($eventResponse->getData())
            );

            return;
        }
        $this->renderLayout();
    }

    /**
     * Delete category action
     */
    public function deleteAction()
    {
        if ($id = (int) $this->getRequest()->getParam('id')) {
            try {
                $category = Mage::getModel('gomage_designer/clipart_category')->load($id);
                Mage::getSingleton('admin/session')->setDeletedPath($category->getPath());
                $category->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('catalog')->__('The category has been deleted.'));
            }
            catch (Mage_Core_Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->getResponse()->setRedirect($this->getUrl('*/*/edit', array('_current'=>true)));
                return;
            }
            catch (Exception $e){
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('catalog')->__('An error occurred while trying to delete the category.'));
                $this->getResponse()->setRedirect($this->getUrl('*/*/edit', array('_current'=>true)));
                return;
            }
        }
        $this->getResponse()->setRedirect($this->getUrl('*/*/', array('_current'=>true, 'id'=>null)));
    }

    public function saveAction()
    {
        /**
         * @var $category GoMage_ProductDesigner_Model_Clipart_Category
         * @var $clipartObj GoMage_ProductDesigner_Model_Clipart
         */

        if ($data = $this->getRequest()->getPost('general')) {
            try {
                //Unset Default state
                $data['is_active'] = 1;
                $category = Mage::getModel('gomage_designer/clipart_category');
                $category->setData($data);
                $category->save();

                $media = $data['media_gallery'];
                $images = json_decode($media['images'], true);

                if(count($images) > 0) {
                    foreach($images as $image) {
                        if(!empty($image)) {
                            $clipartObj = Mage::getModel('gomage_designer/clipart');

                            $imagePath = $clipartObj->getImagePath($image['url']);
                            $clipartObj->setData(array(
                                'clipart_id' => @$image['value_id'],
                                'category_id' => $category->getId(),
                                'label' => $image['label'],
                                'image' => $imagePath,
                                'tags' => $image['tags'],
                                'position' => $image['position'],
                                'disabled' => $image['disabled'],
                            ));

                            $tmpFile = $clipartObj->getTempPath($imagePath);
                            $destinationFile = $clipartObj->getDestinationPath($imagePath);
                            $destinationDir = $clipartObj->getDestinationDir($imagePath);

                            if($image['removed'] == 0) {
                                $clipartObj->save();
                            } else {
                                if($clipartObj->getClipartId()) {
                                    $clipartObj->delete();
                                    @unlink($destinationFile);
                                }
                            }

                            if(!@$image['value_id']) {
                                if(!@is_dir($destinationDir)) {
                                    @mkdir($destinationDir, 0777, true);
                                }

                                $result = copy($tmpFile, $destinationFile);

                                if ($result) {
                                    @chmod($destinationFile, 0777);
                                    $clipartObj->resizeClipart($destinationFile);
                                    @unlink($tmpFile);
                                }
                            }
                        }
                    }
                }

                $refreshTree = true;
                $this->_getSession()->addSuccess(Mage::helper('gomage_designer')->__('Clipart category changes saved'));

            } catch(Exception $e) {
                $this->_getSession()->addError($e->getMessage())
                    ->setCategoryData($data);
                $refreshTree = 'false';
            }
        }

        $url = $this->getUrl('*/*/edit', array('id' => $category->getId()));
        $this->getResponse()->setBody(
            '<script type="text/javascript">parent.updateContent("' . $url . '", {}, '.$refreshTree.');</script>'
        );
    }

    public function uploadImageAction() {
        try {
            $uploader = new GoMage_ProductDesigner_Model_File_Uploader('image');
            $uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
            $uploader->addValidateCallback('catalog_product_image',
                Mage::helper('catalog/image'), 'validateUploadFile');
            $uploader->setAllowRenameFiles(true);
            $uploader->setFilesDispersion(true);
            $result = $uploader->save(
                Mage::getSingleton('gomage_designer/clipart_gallery_config')->getBaseTmpMediaPath()
            );

            Mage::dispatchEvent('catalog_product_gallery_upload_image_after', array(
                'result' => $result,
                'action' => $this
            ));

            /**
             * Workaround for prototype 1.7 methods "isJSON", "evalJSON" on Windows OS
             */
            $result['tmp_name'] = str_replace(DS, "/", $result['tmp_name']);
            $result['path'] = str_replace(DS, "/", $result['path']);

            $result['url'] = Mage::getSingleton('gomage_designer/clipart_gallery_config')->getTmpMediaUrl($result['file']);
            $result['file'] = $result['file'] . '.tmp';
            $result['cookie'] = array(
                'name'     => session_name(),
                'value'    => $this->_getSession()->getSessionId(),
                'lifetime' => $this->_getSession()->getCookieLifetime(),
                'path'     => $this->_getSession()->getCookiePath(),
                'domain'   => $this->_getSession()->getCookieDomain()
            );

        } catch (Exception $e) {
            $result = array(
                'error' => $e->getMessage(),
                'errorcode' => $e->getCode());
        }

        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }

    public function categoriesJsonAction()
    {
        if ($this->getRequest()->getParam('expand_all')) {
            Mage::getSingleton('admin/session')->setIsTreeWasExpanded(true);
        } else {
            Mage::getSingleton('admin/session')->setIsTreeWasExpanded(false);
        }
        if ($categoryId = (int) $this->getRequest()->getPost('id')) {
            $this->getRequest()->setParam('id', $categoryId);

            if (!$category = $this->_initCategory()) {
                return;
            }
            $this->getResponse()->setBody(
                $this->getLayout()->createBlock('gomage_designer/adminhtml_cliparts_tree')
                    ->getTreeJson($category)
            );
        }
    }

    /**
     * Move category action
     */
    public function moveAction()
    {
        $category = $this->_initCategory();
        if (!$category) {
            $this->getResponse()->setBody(Mage::helper('catalog')->__('Category move error'));
            return;
        }
        /**
         * New parent category identifier
         */
        $parentNodeId   = $this->getRequest()->getPost('pid', false);
        /**
         * Category id after which we have put our category
         */
        $prevNodeId     = $this->getRequest()->getPost('aid', false);

        try {
            $category->move($parentNodeId, $prevNodeId);
            $this->getResponse()->setBody("SUCCESS");
        }
        catch (Mage_Core_Exception $e) {
            $this->getResponse()->setBody($e->getMessage());
        }
        catch (Exception $e){
            $this->getResponse()->setBody(Mage::helper('catalog')->__('Category move error'.$e));
            Mage::logException($e);
        }

    }

    protected function _initCategory()
    {
        $categoryId = (int) $this->getRequest()->getParam('id',false);
        $category = Mage::getModel('gomage_designer/clipart_category');

        if ($categoryId) {
            $category->load($categoryId);
        }

        return $category;
    }
}