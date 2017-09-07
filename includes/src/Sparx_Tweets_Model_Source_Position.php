<?php
class Sparx_Tweets_Model_Source_Position
{
	/**
     * Options getter
     *
     * @return array
     */
	public function toOptionArray()
    {
        return array(
			array('value' => 1, 'label'=>Mage::helper('tweets')->__('Left')),
            array('value' => 2, 'label'=>Mage::helper('tweets')->__('Right')),
            array('value' => 0, 'label'=>Mage::helper('tweets')->__('Custom'))
        );
    }
}
