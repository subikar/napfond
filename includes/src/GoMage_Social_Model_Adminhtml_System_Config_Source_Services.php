<?php
/**
 * GoMage Social Connector Extension
 *
 * @category     Extension
 * @copyright    Copyright (c) 2013 GoMage (http://www.gomage.com)
 * @author       GoMage
 * @license      http://www.gomage.com/license-agreement/  Single domain license
 * @terms of use http://www.gomage.com/terms-of-use
 * @version      Release: 1.0
 * @since        Class available since Release 1.0
 */ 

class GoMage_Social_Model_Adminhtml_System_Config_Source_Services{

    public function toOptionArray()
    {
    	$helper = Mage::helper('gomage_social');
    	
        return array(
        	array('value' => '', 'label' => ''),
            array('value' => GoMage_Social_Model_Type::FACEBOOK, 'label' => $helper->__('FaceBook')),            
            array('value' => GoMage_Social_Model_Type::LINKEDIN, 'label' => $helper->__('LinkedIn')),
            array('value' => GoMage_Social_Model_Type::GOOGLE, 'label' => $helper->__('Google')),
        );
    }

}