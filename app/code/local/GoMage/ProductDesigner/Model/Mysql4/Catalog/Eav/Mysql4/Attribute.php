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
 * Product attribute resource model
 *
 * @category   GoMage
 * @package    GoMage_ProductDesigner
 * @subpackage Model
 * @author     Roman Bublik <rb@gomage.com>
 */
class GoMage_ProductDesigner_Model_Mysql4_Catalog_Eav_Mysql4_Attribute
    extends Mage_Catalog_Model_Resource_Eav_Mysql4_Attribute
{
    protected $_navigationAttributeModel;
    protected function _saveOption(Mage_Core_Model_Abstract $object)
    {
        if ($navigationResourceModel = $this->_getNavigationAttributeResourceModel()) {
            $navigationResourceModel->_saveOption($object);
        }
        $colorAttributeCode = Mage::getStoreConfig('gomage_designer/navigation/color_attribute');
        if (!$colorAttributeCode || $object->getAttributeCode() !=$colorAttributeCode) {
            return parent::_saveOption($object);
        }

        $option = $object->getOption();
        if (is_array($option)) {
            $write = $this->_getWriteAdapter();
            $optionTable        = $this->getTable('attribute_option');
            $optionValueTable   = $this->getTable('attribute_option_value');
            $stores = Mage::getModel('core/store')
                ->getResourceCollection()
                ->setLoadDefault(true)
                ->load();

            if (isset($option['value'])) {
                $attributeDefaultValue = array();
                if (!is_array($object->getDefault())) {
                    $object->setDefault(array());
                }

                foreach ($option['value'] as $optionId => $values) {
                    $intOptionId = (int) $optionId;
                    if (!empty($option['delete'][$optionId])) {
                        if ($intOptionId) {
                            $condition = $write->quoteInto('option_id=?', $intOptionId);
                            $write->delete($optionTable, $condition);
                        }

                        continue;
                    }

                    if (!$intOptionId) {
                        $data = array(
                            'attribute_id'  => $object->getId(),
                            'sort_order'    => isset($option['order'][$optionId]) ? $option['order'][$optionId] : 0,
                        );
                        $write->insert($optionTable, $data);
                        $intOptionId = $write->lastInsertId();
                    }
                    else {
                        $data = array(
                            'sort_order'    => isset($option['order'][$optionId]) ? $option['order'][$optionId] : 0,
                        );
                        $write->update($optionTable, $data, $write->quoteInto('option_id=?', $intOptionId));
                    }

                    $attribute_id = $object->getAttributeId();
                    $table = Mage::getSingleton('core/resource')->getTableName('gomage_productdesigner_attribute_option');

                    $connection =  $this->_getReadAdapter();


                    if(isset($option['remove_image'][$intOptionId]) && $option['remove_image'][$intOptionId] > 0){

                        $connection->query("DELETE FROM {$table} WHERE `attribute_id` = {$attribute_id} AND `option_id` = {$intOptionId}; ");

                    }

                    if(isset($option['image'][$optionId])){

                        $imageInfo = Zend_Json::decode($option['image'][$optionId]);
                        if(isset($imageInfo[0])){
                            $imageInfo = $imageInfo[0];

                        }else{
                            $imageInfo = null;
                        }

                        if(!empty($imageInfo) && isset($imageInfo['status']) && $imageInfo['status'] == 'new'){

                            $image 	= $this->_moveImageFromTmp($imageInfo['file']);
                            $name	= $imageInfo['name'];
                            $size	= (float)$imageInfo['size'];

                            if($connection->fetchOne("SELECT COUNT(*) FROM {$table} WHERE `attribute_id` = {$attribute_id} AND `option_id` = {$intOptionId};") > 0){
                                $connection->query("UPDATE {$table} SET `filename` = '{$image}', `name` = '{$name}', `size` = {$size} WHERE `attribute_id` = {$attribute_id} AND `option_id` = {$intOptionId}; ");
                            }else{
                                $connection->query("INSERT INTO {$table} VALUES ({$intOptionId},{$attribute_id},'{$image}','{$name}',{$size});");

                            }
                        }
                    }

                    if (in_array($optionId, $object->getDefault())) {
                        if ($object->getFrontendInput() == 'multiselect') {
                            $attributeDefaultValue[] = $intOptionId;
                        } else if ($object->getFrontendInput() == 'select') {
                            $attributeDefaultValue = array($intOptionId);
                        }
                    }


                    // Default value
                    if (!isset($values[0])) {
                        Mage::throwException(Mage::helper('eav')->__('Default option value is not defined.'));
                    }

                    $write->delete($optionValueTable, $write->quoteInto('option_id=?', $intOptionId));
                    foreach ($stores as $store) {
                        if (isset($values[$store->getId()]) && (!empty($values[$store->getId()]) || $values[$store->getId()] == "0")) {
                            $data = array(
                                'option_id' => $intOptionId,
                                'store_id'  => $store->getId(),
                                'value'     => $values[$store->getId()],
                            );
                            $write->insert($optionValueTable, $data);
                        }
                    }
                }

                $write->update($this->getMainTable(), array(
                    'default_value' => implode(',', $attributeDefaultValue)
                ), $write->quoteInto($this->getIdFieldName() . '=?', $object->getId()));
            }
        }
        return $this;
    }

    protected function _moveImageFromTmp($file) {

        $ioObject = new Varien_Io_File();
        $destDirectory = Mage::getBaseDir('media') . '/option_image';

        try {
            $ioObject->open(array('path' => $destDirectory));
        }
        catch (Exception $e) {
            $ioObject->mkdir($destDirectory, 0777, true);
            $ioObject->open(array('path' => $destDirectory));
        }

        if (strrpos($file, '.tmp') == strlen($file) - 4) {
            $file = substr($file, 0, strlen($file) - 4);
        }

        $destFile = Varien_File_Uploader::getNewFileName($file);

        $dest = $destDirectory . '/' . $destFile;

        $ioObject->mv(Mage::getSingleton('catalog/product_media_config')->getTmpMediaPath($file), $dest);

        return $destFile;
    }

    protected function _getNavigationAttributeResourceModel()
    {
        if (is_null($this->_navigationAttributeModel)) {
            if (Mage::helper('gomage_designer')->advancedNavigationEnabled()
                && class_exists('GoMage_Navigation_Model_Resource_Eav_Mysql4_Entity_Attribute')) {
                $this->_navigationAttributeModel = Mage::getModel('gomage_navigation/resource_eav_mysql4_entity_attribute');
            } else {
                $this->_navigationAttributeModel = false;
            }
        }

        return $this->_navigationAttributeModel;
    }
}
