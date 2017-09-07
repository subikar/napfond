<?php
$installer = $this;

$installer->startSetup();

$installer->run("ALTER TABLE `{$this->getTable('sales/order')}` ADD `track_link` VARCHAR(255) NOT NULL;");



$widgetParameters = array(
    'param1' => 'track widget',
    'template' => 'trackorder/trackwidget.phtml'
);

$instance = Mage::getModel('widget/widget_instance')->setData(array(
    'type' => 'trackorder/trackwidget',
    'package_theme' => 'default/default', // has to match the concrete theme containing the template
    'title' => 'Track Widget',
    'store_ids' => '0', // or comma separated list of ids
    'sort_order' => '0',
    'widget_parameters' => serialize($widgetParameters),
    'page_groups' => array(array(
        'page_group' => 'all_pages',
        'all_pages' => array(
            'page_id' => null,
            'group' => 'all_pages',
            'layout_handle' => 'default',
            'block' => 'left',
            'for' => 'all',
            'template' => $widgetParameters['template'],
            
        )
    ))
))->save();

                

$installer->endSetup();
?>
