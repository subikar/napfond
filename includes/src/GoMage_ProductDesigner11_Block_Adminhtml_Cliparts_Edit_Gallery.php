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
 * Cliparts gallery block
 *
 * @category   GoMage
 * @package    GoMage_ProductDesigner
 * @subpackage Block
 * @author     Roman Bublik <rb@gomage.com>
 */
class GoMage_ProductDesigner_Block_Adminhtml_Cliparts_Edit_Gallery
    extends Mage_Adminhtml_Block_Catalog_Product_Helper_Form_Gallery_Content
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('gomage/productdesigner/cliparts/edit/gallery.phtml');
    }

    protected function _prepareLayout()
    {
        $preparedLayout = parent::_prepareLayout();

        $this->setChild('uploader',
            $this->getLayout()->createBlock('adminhtml/media_uploader')
        );

        $this->getUploader()->getConfig()
            ->setUrl(Mage::getModel('adminhtml/url')->addSessionParam()->getUrl('*/*/uploadImage', array('_current' => true)))
            ->setFileField('image')
            ->setFilters(array(
                'images' => array(
                    'label' => Mage::helper('adminhtml')->__('Images (.gif, .jpg, .png)'),
                    'files' => array('*.gif', '*.jpg','*.jpeg', '*.png')
                )
            ));

        Mage::dispatchEvent('cliparts_gallery_prepare_layout', array('block' => $this));
        return $preparedLayout;
    }

    public function getHtmlId() {
        return 'media_gallery_content';
    }

    public function getImagesJson()
    {
        $values = array();
        $category = $this->getCategory();
        $galleryConfig = Mage::getSingleton('gomage_designer/clipart_gallery_config');
        $mediaUrl = $galleryConfig->getBaseMediaUrl();

        if($category) {
            foreach($category->getClipartsCollection() as $image) {
                $imageData = $image->getData();
                $imageUrl = $imageData['image'];
                $valueId = $imageData['clipart_id'];

                unset($imageData['image']);
                unset($imageData['entity_type_id']);
                unset($imageData['clipart_id']);
                unset($imageData['category_id']);

                $additionalData['value_id'] = $valueId;
                $additionalData['url'] = $mediaUrl.$imageUrl;
                $additionalData['file'] = $imageUrl;
                $additionalData['removed'] = 0;
                $data = array_merge($additionalData, $imageData);
                $values[] = $data;
            }
        }
        return Mage::helper('core')->jsonEncode($values);
    }

    public function getImagesValuesJson()
    {
        $values = array();
        return Mage::helper('core')->jsonEncode($values);
    }

    /**
     * Enter description here...
     *
     * @return array
     */
    public function getImageTypes()
    {
        return array();
    }

    public function hasUseDefault()
    {
        return false;
    }

    public function getImageTypesJson()
    {
        return json_encode($this->getImageTypes(), JSON_FORCE_OBJECT);
    }

    public function getCategory() {
        /* @var $category GoMage_ProductDesigner_Model_Clipart_Category */
        if($this->hasData('category')) {
            $category = $this->getData('category');
            return $category;
        }
        $category = Mage::registry('category');
        if($category){
            $this->setData('category', $category);
            return $category;
        }
    }
}