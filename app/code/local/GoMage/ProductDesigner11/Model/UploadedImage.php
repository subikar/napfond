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
 * Uploaded image model
 *
 * @category   GoMage
 * @package    GoMage_ProductDesigner
 * @subpackage Model
 * @author     Roman Bublik <rb@gomage.com>
 */
class GoMage_ProductDesigner_Model_UploadedImage extends Mage_Core_Model_Abstract
{
    protected $_urlModel;

    protected function _construct()
    {
        $this->_init('gomage_designer/uploadedImage');
    }

    public function getImagePath($imageUrl)
    {
        $imageUrl = str_replace($this->getConfig()->getBaseTmpMediaUrl(), '', $imageUrl);
        $imageUrl = str_replace($this->getConfig()->getBaseMediaUrl(), '', $imageUrl);
        return $imageUrl;
    }

    public function getUrl($imagePath)
    {
        return $this->getConfig()->getBaseTmpMediaUrl() . $imagePath;
    }

    public function getDestinationPath($imagePath)
    {
        return $this->getConfig()->getBaseMediaPath() . $imagePath;
    }

    public function getTempPath($imagePath)
    {
        return $this->getConfig()->getBaseTmpMediaPath() . $imagePath;
    }

    public function getDestinationDir($imagePath)
    {
        $expImagePath = explode('/', $imagePath);
        array_pop($expImagePath);
        $imagePath = implode('/', $expImagePath);
        if(strpos($imagePath, $this->getConfig()->getBaseMediaPath()) === false) {
            $imagePath = $this->getConfig()->getBaseMediaPath() . $imagePath;
        }

        return $imagePath;
    }

    public function getConfig()
    {
        return Mage::getSingleton('gomage_designer/uploadedImage_config');
    }

    public function getCustomerUploadedImages()
    {
        $customerSession = $this->_getCustomerSession();
        $uploadedImages = array();
        if($customerId = $customerSession->getCustomerId()) {
            $uploadedImages = $this->getCustomerImages($customerId)->getItems();
        }
        if($this->_getDesignerSessionId()) {
            $uploadedSessionImages = $this->_getUploadedImagesFromSession()->getItems();
            $uploadedImages = array_merge($uploadedSessionImages, $uploadedImages);
        }
        return $uploadedImages;
    }

    private function _getCustomerSession()
    {
        return Mage::getSingleton('customer/session');
    }

    public function getCustomerImages($customerId)
    {
        /**
         * @var $collection GoMage_ProductDesigner_Model_Mysql4_UploadedImage_Collection
         */
        $collection = $this->getResourceCollection();
        $collection->addFieldToFilter('customer_id', $customerId);

        return $collection;
    }

    protected function _getUploadedImagesFromSession()
    {
        /**
         * @var $collection GoMage_ProductDesigner_Model_Mysql4_UploadedImage_Collection
         */
        $designerSessionId = $this->_getDesignerSessionId();
        $collection = $this->getResourceCollection();
        $collection->addFieldToFilter('session_id', $designerSessionId);

        return $collection;
    }

    protected function _getDesignerSessionId()
    {
        return Mage::helper('gomage_designer')->getDesignerSessionId();
    }

    public function removeCustomerUploadedImages()
    {
        $customerSession = $this->_getCustomerSession();
        $ids = array();

        if($customerId = $customerSession->getCustomerId()) {
            $ids = $this->getCustomerImages($customerId)->getAllIds();
        }

        if($this->_getDesignerSessionId()) {
            $uploadedSessionImagesIds = $this->_getUploadedImagesFromSession()->getAllIds();
            $ids = array_merge($ids, $uploadedSessionImagesIds);
        }

        if (!empty($ids)) {
            $this->getResource()->removeImagesByIds($ids);
        }

        return $this;
    }

    public function resizeImage($file)
    {
        return Mage::getModel('gomage_designer/clipart')->resizeClipart($file);
    }
}