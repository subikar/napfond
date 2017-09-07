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
 * Source model for font size
 *
 * @category   GoMage
 * @package    GoMage_ProductDesigner
 * @subpackage Model
 * @author     Roman Bublik <rb@gomage.com>
 */
class GoMage_ProductDesigner_Model_Config_Source_Font_Size
{
    public function toOptionArray()
    {
        $sizes = array();
        $step = 2;
        for ($size = 12; $size <= 72; $size += $step) {
            $sizes[] = array('value' => $size, 'label' => $size);
            if ($size == 24 || $size == 52) {
                $step *= 2;
            } elseif ($size == 60) {
                $step = 12;
            }
        }
      return $sizes;
    }
}
