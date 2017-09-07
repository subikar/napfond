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
 * Admin controller for product design area
 *
 * @category   GoMage
 * @package    GoMage_ProductDesigner
 * @subpackage controllers
 * @author     Roman Bublik <rb@gomage.com>
 */
class GoMage_ProductDesigner_Adminhtml_Designer_ProductController
    extends Mage_Adminhtml_Controller_Action
{
    public function dispatch($action)
    {
        if(!Mage::helper('gomage_designer')->isEnabled()) {
            $action = 'noRoute';
        }
        parent::dispatch($action);
    }

    /**
     * Initialize product
     *
     * @return false|Mage_Catalog_Model_Product
     */
    protected function _initializeProduct()
    {
        $productId = $this->getRequest()->getParam('product_id');
        $product = Mage::getModel('catalog/product');

        if ($productId) {
            $product->load($productId);
        }
        Mage::register('product', $product);
        return $product;
    }

    /**
     * Edit Design area action
     *
     * @return void
     */
    public function editAction()
    {
        $product = $this->_initializeProduct();
        if ($product->getId()) {
            $image = $this->_initializeProductImage($product);
            if ($image && $image->getId()) {
                $html = $this->_getProductEditHtml();
                Mage::helper('gomage_designer/ajax')->sendSuccess(array(
                    'design_area' => $html
                ));
            } else {
                Mage::helper('gomage_designer/ajax')->sendError(Mage::helper('gomage_designer')->__('You can not choose this image for design'));
            }
        }
    }

    /**
     * Return design area edit block html
     *
     * @return string
     */
    protected function _getProductEditHtml()
    {
        $layout = $this->getLayout();
        $update = $layout->getUpdate();
        $update->load('adminhtml_designer_product_edit');
        $layout->generateXml();
        $layout->generateBlocks();
        return $layout->getOutput();
    }

    /**
     * Initialize product image
     *
     * @param Mage_Catalog_Model_Product $product Product
     * @param string                     $idField Image Field
     * @return bool|Varien_Object
     */
    protected function _initializeProductImage($product, $idField = 'img')
    {
        $imageId = $this->getRequest()->getParam($idField);
        if ($product->getId() && $imageId) {
            $images  = $product->getMediaGallery('images');
            $image = new Varien_Object();
            foreach ($images as $_image) {
                if ($_image['value_id'] == $imageId) {
                    $_image['id'] = $_image['value_id'];
                    $image->setData($_image);
                    break;
                }
            }
            if ($image && $image->getId()) {
                Mage::register('current_image', $image);
                return $image;
            }
        }

        return false;
    }

    /**
     * Save design area action
     *
     * @return void
     */
    public function saveAction()
    {
        $product = $this->_initializeProduct();

        try {
            if (!$product || !$product->getId()){
                throw new Exception(Mage::helper('gomage_designer')->__('Product with id %d not found', $this->getRequest()->getParam('product_id')));
            }
            $image = $this->_initializeProductImage($product, 'image_id');
            if (!$image || !$image->getId()) {
                throw new Exception(Mage::helper('gomage_designer')->__('Image with id %d not found', $this->getRequest()->getParam('image_id')));
            }
            if ($mediaGalleryAttribute = $product->getMediaGalleryAttribute()){
                $mediaGalleryAttribute->updateImage($product, $image->getFile(), array('design_area' => $this->_prepareDesignAreaSettings()));
                $product->save();
            }
            Mage::helper('gomage_designer/ajax')->sendSuccess();
        } catch (Exception $e) {
            Mage::logException($e);
            Mage::helper('gomage_designer/ajax')->sendError($e->getMessage());
        }
    }

    /**
     * Prepare design area settings
     *
     * @return array
     */
    protected function _prepareDesignAreaSettings()
    {
        $params  = $this->getRequest()->getParams();
        $settings = array(
            't'  => isset($params['t']) ? $params['t'] : null, // offset top
            'l'  => isset($params['l']) ? $params['l'] : null, // offset left
            'h'  => isset($params['h']) ? $params['h'] : null, // design area height
            'w'  => isset($params['w']) ? $params['w'] : null, // design area width
            's'  => isset($params['s']) ? $params['s'] : null, // side type [front, back, left, right]
            'ip' => isset($params['ip']) ? $params['ip'] : null
        );

        return Mage::helper('core')->jsonEncode($settings);
    }

    /**
     * Update state action
     *
     * @return void
     */
    public function updateStateAction()
    {
        $productId = (int) $this->getRequest()->getParam('product_id');
        $imageId   = (int) $this->getRequest()->getParam('image_id');
        $state = (int) $this->getRequest()->getParam('state');

        try {
            if (!$state) {
                $product  = $this->_initializeProduct();
                if ($product && $product->getId()) {
                    $image = $this->_initializeProductImage($product, 'image_id');
                    if (!$image || !$image->getId()) {
                        throw new Exception(Mage::helper('gomage_designer')->__('Image with id %d not found', $imageId));
                    }
                    if ($mediaGalleryAttribute = $product->getMediaGalleryAttribute()){
                        $mediaGalleryAttribute->updateImage(
                            $product,
                            $image->getFile(),
                            array('design_area' => false)
                        );
                        $product->save();
                    }
                    Mage::helper('gomage_designer/ajax')->sendSuccess();
                } else {
                    throw new Exception(Mage::helper('gomage_designer')->__('Product with id %d not found', $productId));
                }
            }
        } catch (Exception $e) {
            Mage::helper('gomage_designer/ajax')->sendError($e->getMessage());
        }
    }

    public function uploadAttributeImageAction()
    {
        file_put_contents(Mage::getBaseDir('var').'/data.txt', print_r($_FILES, true));

        $result = array();
        try {
            $uploader = new Varien_File_Uploader('option_image');
            $uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
            $uploader->setAllowRenameFiles(true);
            $uploader->setFilesDispersion(true);
            $result = $uploader->save(
                Mage::getSingleton('catalog/product_media_config')->getBaseTmpMediaPath()
            );

            $result['url'] = Mage::getSingleton('catalog/product_media_config')->getTmpMediaUrl($result['file']);
            $result['file'] = $result['file'] . '.tmp';
            $result['cookie'] = array(
                'name'     => session_name(),
                'value'    => $this->_getSession()->getSessionId(),
                'lifetime' => $this->_getSession()->getCookieLifetime(),
                'path'     => $this->_getSession()->getCookiePath(),
                'domain'   => $this->_getSession()->getCookieDomain()
            );
        } catch (Exception $e) {
            $result = array('error'=>$e->getMessage(), 'errorcode'=>$e->getCode());
        }

        file_put_contents(Mage::getBaseDir('var').'/data-response.txt', print_r($result, true));

        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }
}