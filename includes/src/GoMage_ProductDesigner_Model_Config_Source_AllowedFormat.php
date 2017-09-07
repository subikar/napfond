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
 * Source model for Allowed image formats
 *
 * @category   GoMage
 * @package    GoMage_ProductDesigner
 * @subpackage Model
 * @author     Roman Bublik <rb@gomage.com>
 */
class GoMage_ProductDesigner_Model_Config_Source_AllowedFormat
{
    public function toOptionArray()
    {
      return array(
        array('value' => 'png', 'label' => 'PNG'),
        array('value' => 'jpg/jpeg', 'label' => 'JPEG, JPG'),
        array('value' => 'gif', 'label' => 'GIF'),
		array('value' => 'svg', 'label' => 'SVG')
		);
    }
}