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
 * File uploader model
 *
 * @category   GoMage
 * @package    GoMage_ProductDesigner
 * @subpackage Model
 * @author     Roman Bublik <rb@gomage.com>
 */
class GoMage_ProductDesigner_Model_File_Uploader extends Mage_Core_Model_File_Uploader {
    public function getFile()
    {
        $file = $this->_file;
        return new Varien_Object($file);
    }

    public function save($destinationFolder, $newFileName = null)
    {
        $this->_file['name'] = Mage::helper('gomage_designer')->formatFileName($this->_file['name']);
        return parent::save($destinationFolder, $newFileName);
    }
}