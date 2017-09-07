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
 * Uploaded image collection
 *
 * @category   GoMage
 * @package    GoMage_ProductDesigner
 * @subpackage Model
 * @author     Roman Bublik <rb@gomage.com>
 */
class GoMage_ProductDesigner_Model_Mysql4_UploadedImage_Collection
    extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    /**
     * Event prefix
     *
     * @var string
     */
    protected $_eventPrefix              = 'designer_uploadedImage_collection';

    /**
     * Event object name
     *
     * @var string
     */
    protected $_eventObject              = 'designer_uploadedImage_collection';

    protected function _construct()
    {
        $this->_init('gomage_designer/uploadedImage');
        $this->addFilterToMap('image_id', 'main_table.image_id');
    }
}