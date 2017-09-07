<?php
class Magestore_Featuredproduct_Model_Typeshow extends Varien_Object 
{
    public function toOptionArray()
    {
        return array(
            array('value'=>'static', 'label'=>Mage::helper('featuredproduct')->__('Static')),
            array('value'=>'slider', 'label'=>Mage::helper('featuredproduct')->__('Slider')),
            array('value'=>'slider2', 'label'=>Mage::helper('featuredproduct')->__('Slider2')),                                 
        );
    }

}
?>