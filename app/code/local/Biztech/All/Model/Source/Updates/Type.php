<?php
    /**
    
    *
    * NOTICE OF LICENSE
    *
    * This source file is subject to the EULA
    * that is bundled with this package in the file LICENSE.txt.
    * It is also available through the world-wide-web at this URL:
    */

    class Biztech_All_Model_Source_Updates_Type extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
    {
        const TYPE_UPDATE_RELEASE = 'UPDATE_RELEASE';
        const TYPE_INSTALLED_UPDATE = 'INSTALLED_UPDATE';
        public function toOptionArray()
        {
            return array(
                array('value' => self::TYPE_INSTALLED_UPDATE, 'label' => Mage::helper('all')->__('My extensions updates')),
                array('value' => self::TYPE_UPDATE_RELEASE, 'label' => Mage::helper('all')->__('All extensions updates')),
            );
        }

        public function getAllOptions()
        {
            return $this->toOptionArray();
        }


        public function getLabel($value)
        {
            $options = $this->toOptionArray();
            foreach ($options as $v) {
                if ($v['value'] == $value) {
                    return $v['label'];
                }
            }
            return '';
        }
         
        public function getGridOptions()
        {
            $items = $this->getAllOptions();
            $out = array();
            foreach ($items as $item) {
                $out[$item['value']] = $item['label'];
            }
            return $out;
        }
    }
