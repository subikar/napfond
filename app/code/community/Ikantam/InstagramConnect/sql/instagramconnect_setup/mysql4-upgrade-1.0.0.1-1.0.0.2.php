<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * @category    Phoenix
 * @package     Phoenix_Moneybookers
 * @copyright   Copyright (c) 2012 Phoenix Medien GmbH & Co. KG (http://www.phoenix-medien.de)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/* @var $installer Ikantam_InstagramConnect_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();


/**
 * Create product attribute 'instagram_source_user'
 */
$installer->addAttribute('catalog_product', 'instagram_source_user', array(
    'type'                       => 'varchar',
    'label'                      => 'Used Instagram Users',
    'input'                      => 'multiselect',
    'backend'                    => 'eav/entity_attribute_backend_array',
    'required'                   => false,
    'sort_order'                 => 7,
    'global'                     => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE,
    'apply_to'                   => 'simple,configurable,virtual',
    'searchable'        => false,
    'filterable'        => false,
    'source' => 'instagramconnect/source_instagram_user',
));



/** adding category attribute */
$installer->addAttribute('catalog_category', 'instagram_category_source', array(
    'group'         => 'General Information',
    'type'          => 'varchar',
    'label'         => 'Used Instagram Tags',
    'input'         => 'multiselect',
    'backend'       => 'eav/entity_attribute_backend_array',
    'required'      => false,
    'sort_order'    => 90,
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE,
    'source'        => 'instagramconnect/source_instagram'
));


/** adding category attribute  */
$installer->addAttribute('catalog_category', 'instagram_category_source_user', array(
    'group'         => 'General Information',
    'type'          => 'varchar',
    'label'         => 'Used Instagram Users',
    'input'         => 'multiselect',
    'backend'       => 'eav/entity_attribute_backend_array',
    'required'      => false,
    'sort_order'    => 100,
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE,
    'source'        => 'instagramconnect/source_instagram_user'
));




$installer->endSetup();
