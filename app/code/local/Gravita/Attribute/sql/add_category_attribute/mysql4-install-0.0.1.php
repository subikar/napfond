<?php
$installer = $this;
$installer->startSetup();
 
$entityTypeId     = $installer->getEntityTypeId('catalog_category');
$attributeSetId   = $installer->getDefaultAttributeSetId($entityTypeId);
$attributeGroupId = $installer->getDefaultAttributeGroupId($entityTypeId, $attributeSetId);
 
$installer->addAttribute('catalog_category', 'phone_series',  array(
    'type'     => 'varchar',
    'label'    => 'Phone Series',
    'input'    => 'text',
    'global'   => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'visible'           => true,
    'required'          => true,
    'user_defined'      => false,
    'default'           => 0
));

$installer->addAttribute('catalog_category', 'blank_image_folder',  array(
    'type'     => 'varchar',
    'label'    => 'Blank Image Folder Name',
    'input'    => 'text',
    'global'   => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'visible'           => true,
    'required'          => true,
    'user_defined'      => false,
    'default'           => 0
));
 
 
/*$installer->addAttributeToGroup(
    $entityTypeId,
    $attributeSetId,
    $attributeGroupId,
    'phone_series',
    '10'					//last Magento's attribute position in General tab is 10
);*/
 
$attributeId = $installer->getAttributeId($entityTypeId, 'phone_series');
$attributeId = $installer->getAttributeId($entityTypeId, 'blank_image_folder');
 
/*$installer->run("
INSERT INTO `{$installer->getTable('catalog_category_entity_varchar')}`
(`entity_type_id`, `attribute_id`, `entity_id`, `value`)
    SELECT '{$entityTypeId}', '{$attributeId}', `entity_id`, 'iPhone Series'
        FROM `{$installer->getTable('catalog_category_entity')}`;
");*/
 
 
//this will set data of your custom attribute for root category
/*Mage::getModel('catalog/category')
    ->load(1)
    ->setImportedCatId(0)
    ->setInitialSetupFlag(true)
    ->save();
 
//this will set data of your custom attribute for default category
Mage::getModel('catalog/category')
    ->load(2)
    ->setImportedCatId(0)
    ->setInitialSetupFlag(true)
    ->save();*/
 
$installer->endSetup();
?>