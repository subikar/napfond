<?php

class Biztech_Trackorder_Block_Trackwidget extends Mage_Core_Block_Template implements Mage_Widget_Block_Interface {

    /*protected function _toHtml() {

        if(Mage::getStoreConfig('trackorder/trackorder_general/enabled'))
        {
        $html ='<div class = "block block-subscribe">';
        $html .= '<div class = "block-title">';
        $html .= '<strong><span>Track Order Here</span></strong>';
        $html .= '</div>';

        $html .= '<div class = "block-content">';
        $html .= '<div class = "form-subscribe-header">';
        $html .= '<label for = "newsletter"><a target="_blank" href="' . Mage::getBaseUrl() . 'trackorder/index"> Track Order</a></label>';
        $html .= '</div></div></div>';
        return $html;
        }
    }
*/
    
    	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
}
