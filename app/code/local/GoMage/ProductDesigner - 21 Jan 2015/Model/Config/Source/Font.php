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
 * Source model for fonts
 *
 * @category   GoMage
 * @package    GoMage_ProductDesigner
 * @subpackage Model
 * @author     Roman Bublik <rb@gomage.com>
 */
class GoMage_ProductDesigner_Model_Config_Source_Font
{
    public function toOptionArray()
    {
      return array(
        array('value' => 'Arial', 'label' => 'Arial'),
        array('value' => 'Comic Sans MS', 'label' => 'Comic Sans'),
        array('value' => 'Impact', 'label' => 'Impact'),
        array('value' => 'Times New Roman', 'label' => 'Times New Roman'),
      );
    }
}
