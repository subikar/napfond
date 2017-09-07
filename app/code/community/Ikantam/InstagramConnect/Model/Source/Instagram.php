<?php
/**
 * iKantam
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade InstagramConnect to newer
 * versions in the future.
 *
 * @category    Ikantam
 * @package     Ikantam_InstagramConnect
 * @copyright   Copyright (c) 2012 iKantam LLC (http://www.ikantam.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Ikantam_InstagramConnect_Model_Source_Instagram extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{
    /**
     * Retrieve All options
     *
     * @return array
     */
    public function getAllOptions()
    {
        if (is_null($this->_options)) {
		    $tags  = explode(',', Mage::getStoreConfig('ikantam_instagramconnect/module_options/tags'));
		    
		    foreach ($tags as $tag) {
		    	$tag = ltrim(trim($tag), '#');
		    	if (empty($tag)) continue;
		    	$tag = '#' . $tag;
		    	$this->_options[] = array('label' => $tag, 'value' => base64_encode($tag));
		    }
            $helper = Mage::helper('instagramconnect');

            // No show images
            $this->_options[] = array('label' => $helper->__('Do not show') , 'value' => 0);
        }

        return $this->_options;
    }
}
