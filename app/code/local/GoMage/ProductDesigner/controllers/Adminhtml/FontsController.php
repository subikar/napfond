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
 * Admin font controller
 *
 * @category   GoMage
 * @package    GoMage_ProductDesigner
 * @subpackage controllers
 * @author     Roman Bublik <rb@gomage.com>
 */
class GoMage_ProductDesigner_Adminhtml_FontsController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->_forward('edit');
    }

    public function editAction() {
        $this->loadLayout();

        $this->renderLayout();
    }

    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('general')) {
            try {
                $media = $data['media_gallery'];
                $fonts = json_decode($media['images'], true);

                if(count($fonts) > 0) {
                    foreach($fonts as $font) {
                        if(!empty($font)) {
                            $fontObj = Mage::getModel('gomage_designer/font');
                            $fontPath = $fontObj->getFontPath($font['url']);
                            if (isset($font['value_id'])) {
                                $fontObj->load((int) $font['value_id']);
                            }

                            $fontObj->addData(array(
                                'label' => $font['label'],
                                'position' => $font['position'],
                                'font' => $fontPath,
                                'disabled' => $font['disabled'],
                            ));

                            $tmpFile = $fontObj->getTempPath($fontPath);
                            $destinationFile = $fontObj->getDestinationPath($fontPath);
                            $destinationDir = $fontObj->getDestinationDir($fontPath);
                            if($font['removed'] == 0) {
                                $fontObj->save();
                            } else {
                                if($fontObj->getFontId()) {
                                    $fontObj->delete();
                                    @unlink($destinationFile);
                                }
                            }

                            if(!@$font['value_id']) {
                                if(!@is_dir($destinationDir)) {
                                    @mkdir($destinationDir, 0777, true);
                                }

                                $result = copy($tmpFile, $destinationFile);

                                if ($result) {
                                    @chmod($destinationFile, 0777);
                                    @unlink($tmpFile);
                                }
                            }
                        }
                    }
                }
                $this->_getSession()->addSuccess(Mage::helper('gomage_designer')->__('Fonts have been saved'));

            } catch(Exception $e) {
                $this->_getSession()->addError($e->getMessage())
                    ->setCategoryData($data);
            }
        }

        $url = $this->getUrl('*/*/index');
        $this->getResponse()->setRedirect($url);
    }

    public function uploadImageAction()
    {
        try {
            $uploader = new GoMage_ProductDesigner_Model_File_Uploader('image');
            $uploader->setAllowedExtensions(array('ttf', 'otf', 'woff', 'eot'));
            $uploader->setAllowRenameFiles(true);
            $uploader->setFilesDispersion(true);
            $result = $uploader->save(
                Mage::getSingleton('gomage_designer/font_gallery_config')->getBaseTmpMediaPath()
            );

            Mage::dispatchEvent('catalog_product_gallery_upload_font_after', array(
                'result' => $result,
                'action' => $this
            ));

            /**
             * Workaround for prototype 1.7 methods "isJSON", "evalJSON" on Windows OS
             */
            $result['tmp_name'] = str_replace(DS, "/", $result['tmp_name']);
            $result['path'] = str_replace(DS, "/", $result['path']);
            $result['label'] = pathinfo($result['name'], PATHINFO_FILENAME);
            $result['url'] = Mage::getSingleton('gomage_designer/font_gallery_config')->getTmpMediaUrl($result['file']);
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
}