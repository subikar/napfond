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

$installer = $this;
/* @var $installer GoMage_ProductDesigner_Model_Resource_Setup */

$installer->startSetup();

try {
    /* Clipart category */

    $table = $installer->getConnection()->newTable($installer->getTable('gomage_designer/clipart_category'))
        ->addColumn('category_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 12, array(
            'identity' => true,
            'unsigned' => true,
            'nullable' => false,
            'primary'  => true,
        ), "Category Id")
        ->addColumn('parent_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 12, array(
            'unsigned' => true,
            'nullable' => false,
        ), 'Parent Id')
        ->addColumn('name', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(), 'Name')
        ->addColumn('path', Varien_Db_Ddl_Table::TYPE_VARCHAR, 64, array(), 'Path')
        ->addColumn('level', Varien_Db_Ddl_Table::TYPE_INTEGER, 12, array(
            'unsigned' => true,
            'nullable' => false
        ), 'Level')
        ->addColumn('position', Varien_Db_Ddl_Table::TYPE_INTEGER, 12, array(
            'unsigned' => true,
            'nullable' => false,
            'default' => 0
        ), 'Position')
        ->addColumn('sort_order', Varien_Db_Ddl_Table::TYPE_INTEGER, 12, array(
            'unsigned' => true,
        ), 'Sort Order')
        ->addColumn('is_active', Varien_Db_Ddl_Table::TYPE_TINYINT, 1, array(
            'unsigned' => true,
            'default'  => 1
        ), 'Is Active');
    $installer->getConnection()->createTable($table);

    $installer->addAutoIncrement($installer->getTable('gomage_designer/clipart_category'), 'category_id');

    $installer->addEntityType('clipart_category', array(
        'entity_model'          => 'gomage_designer/clipart_category',
        'table'                 => 'gomage_designer/category',
        'increment_per_store'   => false
    ));

    $installer->run("INSERT INTO `{$installer->getTable('gomage_designer/clipart_category')}` VALUES ('1', '0', 'Root Category', '1', '0', '0', '0', '0')");

    /* Clipart category end */

    /* Cliparts */

    $table = $installer->getConnection()->newTable($installer->getTable('gomage_designer/clipart'))
        ->addColumn('clipart_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 12, array(
            'identity' => true,
            'unsigned' => true,
            'nullable' => false,
            'primary'  => true,
        ), "Clipart Id")
        ->addColumn('category_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 12, array(
            'unsigned' => true,
            'nullable' => false,
        ), 'Category Id')
        ->addColumn('entity_type_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 12, array(
            'unsigned' => true,
            'nullable' => false
        ), 'Entity Type Id')
        ->addColumn('position', Varien_Db_Ddl_Table::TYPE_INTEGER, 12, array(
            'unsigned' => true,
        ), 'Position')
        ->addColumn('label', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(), 'Label')
        ->addColumn('image', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(), 'Image')
        ->addColumn('tags', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(), 'Tags')
        ->addColumn('disabled', Varien_Db_Ddl_Table::TYPE_TINYINT, 1, array(
            'unsigned' => true,
            'nullable' => false,
            'default'  => 0
        ), 'Disabled');
    $installer->getConnection()->createTable($table);

    $installer->addAutoIncrement($installer->getTable('gomage_designer/clipart'), 'clipart_id');
    $installer->addForeignKey(
        $installer->getFkName('gomage_designer/clipart', 'category_id', 'gomage_designer/clipart_category', 'category_id'),
        $installer->getTable('gomage_designer/clipart'), 'category_id',
        $installer->getTable('gomage_designer/clipart_category'), 'category_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE
    );

    $installer->addEntityType('clipart_image', array(
        'entity_model'          => 'gomage_designer/clipart',
        'table'                 => 'gomage_designer/clipart',
        'increment_per_store'   => false
    ));

    $categoryId = 2;
    $installer->run("INSERT INTO `{$installer->getTable('gomage_designer/clipart_category')}` VALUES ('2', '1', 'Cliparts', '1/2', '1', '0', '0', '1')");
    $installer->addClipartsToCategory($categoryId);

    /* Cliparts end*/

    /* Uploaded Images */

    $table = $installer->getConnection()->newTable($installer->getTable('gomage_designer/uploadedImage'))
        ->addColumn('image_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 12, array(
            'identity' => true,
            'unsigned' => true,
            'nullable' => false,
            'primary'  => true,
        ), "Image Id")
        ->addColumn('customer_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 12, array(
            'unsigned' => true,
            'nullable' => false,
        ), 'Customer Id')
        ->addColumn('image', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(), 'Image')
        ->addColumn('session_id', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(), 'Session Id');
    $installer->getConnection()->createTable($table);

    $installer->addAutoIncrement($installer->getTable('gomage_designer/uploadedImage'), 'image_id');

    /* Uploaded Images end */

    /* Fonts */

    $table = $installer->getConnection()->newTable($installer->getTable('gomage_designer/font'))
        ->addColumn('font_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 12, array(
            'identity' => true,
            'unsigned' => true,
            'nullable' => false,
            'primary'  => true,
        ), "Font Id")
        ->addColumn('label', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(), 'Label')
        ->addColumn('font', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(), 'Font')
        ->addColumn('disabled', Varien_Db_Ddl_Table::TYPE_TINYINT, 1, array(
            'unsigned' => true,
            'nullable' => false,
            'default'  => 0
        ), "Disabled")
        ->addColumn('position', Varien_Db_Ddl_Table::TYPE_INTEGER, 12, array(
            'unsigned' => true,
        ), "Disabled");
    $installer->getConnection()->createTable($table);

    $installer->addAutoIncrement($installer->getTable('gomage_designer/font'), 'font_id');

    /* Fonts end */

    /* Designs */

    $table = $installer->getConnection()->newTable($installer->getTable('gomage_designer/design'))
        ->addColumn('design_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 12, array(
            'identity' => true,
            'unsigned' => true,
            'nullable' => false,
            'primary'  => true,
        ), "Font Id")
        ->addColumn('customer_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 12, array(
            'unsigned' => true,
        ), "Customer Id")
        ->addColumn('product_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 12, array(
            'unsigned' => true,
            'nullable' => false,
        ), "Customer Id")
        ->addColumn('comment', Varien_Db_Ddl_Table::TYPE_LONGVARCHAR, 2048, array(), 'Comment')
        ->addColumn('session_id', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(), 'Session Id')
        ->addColumn('created_date', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(), 'Created Date')
        ->addColumn('price', Varien_Db_Ddl_Table::TYPE_DECIMAL, null, array(
            'scale'     => 4,
            'precision' => 12,
        ), 'Design Price')
        ->addColumn('color', Varien_Db_Ddl_Table::TYPE_INTEGER, 12, array(
            'unsigned' => true,
        ), "Design color");
    $installer->getConnection()->createTable($table);

    $installer->addAutoIncrement($installer->getTable('gomage_designer/design'), 'design_id');
    $installer->updateDecimalField($installer->getTable('gomage_designer/design'), 'price');

    /* Design end */

    /* Design Images */

    $table = $installer->getConnection()->newTable($installer->getTable('gomage_designer/design_image'))
        ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'identity'  => true,
            'unsigned'  => true,
            'nullable'  => false,
            'primary'   => true,
        ), "Image Id")
        ->addColumn('design_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'unsigned'  => true,
            'nullable'  => false,
        ), "Design Id")
        ->addColumn('product_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'unsigned'  => true,
            'nullable'  => false,
        ), "Product Id")
        ->addColumn('image_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'unsigned'  => true,
            'nullable'  => false,
        ), "Original Image Id")
        ->addColumn('image', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(), "Image")
        ->addColumn('layer', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(), "Layer Image")
        ->addColumn('price', Varien_Db_Ddl_Table::TYPE_DECIMAL, null, array(
            'scale'     => 4,
            'precision' => 12,
        ), "Price")
        ->addColumn('created_date', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(), "Price");
    $installer->getConnection()->createTable($table);
    $installer->addAutoIncrement($installer->getTable('gomage_designer/design_image'), 'id');
    $installer->updateDecimalField($installer->getTable('gomage_designer/design_image'), 'price');

    $installer->addForeignKey(
        $installer->getFkName('gomage_designer/design_image', 'design_id', 'gomage_designer/design', 'design_id'),
        $installer->getTable('gomage_designer/design_image'), 'design_id',
        $installer->getTable('gomage_designer/design'), 'design_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE
    );
    /* Design Images end */

    /**
     * Enable/disable designer attribute
     */
    $installer->addAttribute(Mage_Catalog_Model_Product::ENTITY, 'enable_product_designer', array(
        'type'              => 'int',
        'label'             => 'Enable Product Designer',
        'input'             => 'boolean',
        'source'            => 'eav/entity_attribute_source_boolean',
        'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'visible'           => false,
        'required'          => false,
        'user_defined'      => true,
        'default'           => 0,
        'searchable'        => false,
        'filterable'        => false,
        'comparable'        => false,
        'visible_on_front'  => false,
        'unique'            => false,
        'apply_to'          => 'simple,configurable',
        'group'             => 'General',
        'is_configurable'   => 0
    ));

    $this->updateAttribute(Mage_Catalog_Model_Product::ENTITY,
        'enable_product_designer',
        'is_configurable',
        0
    );

    /* Add Color field to product image */
    $installer->getConnection()->addColumn(
        $installer->getTable('catalog/product_attribute_media_gallery_value'),
        'color',
        "INTEGER(12) UNSIGNED DEFAULT NULL"
    );

    /* Add Design Area to image */
    $installer->getConnection()->addColumn(
        $installer->getTable('catalog/product_attribute_media_gallery_value'),
        'design_area',
        "VARCHAR (1024) DEFAULT NULL"
    );

    /* Add option table for attribute */

    $table = $installer->getConnection()->newTable($installer->getTable('gomage_designer/attribute_option'))
        ->addColumn('option_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'unsigned'  => true,
            'nullable'  => false,
            'primary'   => true,
        ), "Option Id")
        ->addColumn('attribute_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'unsigned'  => true,
            'nullable'  => false,
        ), "Attribute Id")
        ->addColumn('filename', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(), 'File Name')
        ->addColumn('name', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(), 'Name')
        ->addColumn('size', Varien_Db_Ddl_Table::TYPE_DECIMAL, 255, array(
            'nullable' => false,
            'scale'     => 4,
            'precision' => 12,
        ), 'Size');
    $installer->getConnection()->createTable($table);
    $installer->updateDecimalField($installer->getTable('gomage_designer/attribute_option'), 'size', false);

    $installer->addForeignKey(
        $installer->getFkName('gomage_designer/attribute_option', 'option_id', 'eav/attribute_option', 'option_id'),
        $installer->getTable('gomage_designer/attribute_option'), 'option_id',
        $installer->getTable('eav/attribute_option'), 'option_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE
    );

    $installer->endSetup();
} catch (Exception $e) {
    Mage::logException($e);
} catch (Mage_Core_Exception $e) {
    Mage::logException($e);
}


