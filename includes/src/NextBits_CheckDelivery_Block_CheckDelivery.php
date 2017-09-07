<?php
class NextBits_CheckDelivery_Block_CheckDelivery extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getCheckDelivery()     
     { 
        if (!$this->hasData('checkdelivery')) {
            $this->setData('checkdelivery', Mage::registry('checkdelivery'));
        }
        return $this->getData('checkdelivery');
        
    }
}