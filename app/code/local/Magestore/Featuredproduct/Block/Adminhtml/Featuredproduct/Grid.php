<?php

class Magestore_Featuredproduct_Block_Adminhtml_Featuredproduct_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

  	public function __construct()
  	{
     	 parent::__construct();
      	$this->setId('featuredproductGrid');
      	$this->setDefaultSort('entity_id');
      	$this->setDefaultDir('DESC');
      	$this->setSaveParametersInSession(true);
	 	// $this->setUseAjax(true);
      	//$this->setVarNameFilter('product_filter');
  	}
	
	protected function _getStore()
    {
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        return Mage::app()->getStore($storeId);
    }	

  	protected function _prepareCollection()
  	{
      	$store = $this->_getStore();
        $collection = Mage::getModel('catalog/product')->getCollection()
            ->addAttributeToSelect('sku')
            ->addAttributeToSelect('name')
            ->addAttributeToSelect('attribute_set_id')
            ->addAttributeToSelect('type_id')
			->addAttributeToSelect('fb_product')
			//->addAttributeToFilter('fb_product', array('eq'=>1))
            ->joinField('qty',
                'cataloginventory/stock_item',
                'qty',
                'product_id=entity_id',
                '{{table}}.stock_id=1',
                'left');
		if ($store->getId()) {
            //$collection->setStoreId($store->getId());
            $adminStore = Mage_Core_Model_App::ADMIN_STORE_ID;
            $collection->addStoreFilter($store);
            $collection->joinAttribute('name', 'catalog_product/name', 'entity_id', null, 'inner', $adminStore);
            $collection->joinAttribute('custom_name', 'catalog_product/name', 'entity_id', null, 'inner', $store->getId());
            $collection->joinAttribute('status', 'catalog_product/status', 'entity_id', null, 'inner', $store->getId());
            $collection->joinAttribute('visibility', 'catalog_product/visibility', 'entity_id', null, 'inner', $store->getId());
            $collection->joinAttribute('price', 'catalog_product/price', 'entity_id', null, 'left', $store->getId());
        }
        else {
            $collection->addAttributeToSelect('price');
            $collection->addAttributeToSelect('status');
            $collection->addAttributeToSelect('visibility');
        }

      	$this->setCollection($collection);
		parent::_prepareCollection();
        $this->getCollection()->addWebsiteNamesToResult();
        return $this;
      	
  	}
	protected function _addColumnFilterToCollection($column)
    {
        if ($this->getCollection()) {
            if ($column->getId() == 'websites') {
                $this->getCollection()->joinField('websites',
                    'catalog/product_website',
                    'website_id',
                    'product_id=entity_id',
                    null,
                    'left');
            }
        }
        return parent::_addColumnFilterToCollection($column);
    }

	protected function _prepareColumns()
    {
		/* $this->addColumn('fb_product', array(
                'header_css_class'  => 'a-center',
                'type'              => 'checkbox',
                'values'            => $this->_getSelectedProducts(),
                'align'             => 'center',
                'index'             => 'fb_product'
            )); */
		
        $this->addColumn('entity_id',
            array(
                'header'=> Mage::helper('featuredproduct')->__('ID'),
                'width' => '50px',
                'type'  => 'number',
                'index' => 'entity_id',
        ));
        $this->addColumn('name',
            array(
                'header'=> Mage::helper('featuredproduct')->__('Name'),
                'index' => 'name',
        ));

        $store = $this->_getStore();
        if ($store->getId()) {
            $this->addColumn('custom_name',
                array(
                    'header'=> Mage::helper('featuredproduct')->__('Name in %s', $store->getName()),
                    'index' => 'custom_name',
            ));
        }


        $sets = Mage::getResourceModel('eav/entity_attribute_set_collection')
            ->setEntityTypeFilter(Mage::getModel('catalog/product')->getResource()->getTypeId())
            ->load()
            ->toOptionHash();

        $this->addColumn('set_name',
            array(
                'header'=> Mage::helper('featuredproduct')->__('Attrib. Set Name'),
                'width' => '100px',
                'index' => 'attribute_set_id',
                'type'  => 'options',
                'options' => $sets,
        ));

       
        $store = $this->_getStore();
        $this->addColumn('price',
            array(
                'header'=> Mage::helper('featuredproduct')->__('Price'),
                'type'  => 'price',
                'currency_code' => $store->getBaseCurrency()->getCode(),
                'index' => 'price',
        ));

        $this->addColumn('qty',
            array(
                'header'=> Mage::helper('featuredproduct')->__('Qty'),
                'width' => '100px',
                'type'  => 'number',
                'index' => 'qty',
        ));

        

        $this->addColumn('status',
            array(
                'header'=> Mage::helper('featuredproduct')->__('Status'),
                'width' => '70px',
                'index' => 'status',
                'type'  => 'options',
                'options' => Mage::getSingleton('catalog/product_status')->getOptionArray(),
        ));
		
		$this->addColumn('fb_product',
            array(
                'header'=> Mage::helper('featuredproduct')->__('Featured Product'),
                'width' => '70px',
                'index' => 'fb_product',
                'type'  => 'options',
                'options' => array(
              		1 => 'Yes',
              		0 => 'No',
          		),
        ));

        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('websites',
                array(
                    'header'=> Mage::helper('featuredproduct')->__('Websites'),
                    'width' => '100px',
                    'sortable'  => false,
                    'index'     => 'websites',
                    'type'      => 'options',
                    'options'   => Mage::getModel('core/website')->getCollection()->toOptionHash(),
            ));
        }

        

        $this->addRssList('rss/catalog/notifystock', Mage::helper('featuredproduct')->__('Notify Low Stock RSS'));

        return parent::_prepareColumns();
    }  	

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('featuredproduct');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('featuredproduct')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('featuredproduct')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('catalog/product_status')->getOptionArray();

        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('status', array(
             'label'=> Mage::helper('featuredproduct')->__('Change status'),
             'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
             'additional' => array(
                    'visibility' => array(
                         'name' => 'status',
                         'type' => 'select',
                         'class' => 'required-entry',
                         'label' => Mage::helper('featuredproduct')->__('Status'),
                         'values' => $statuses
                     )
             )
        ));

		$featuredproduct = array(
              		1 => 'Yes',
              		0 => 'No',
          		);

		array_unshift($featuredproduct, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('fb_product', array(
             'label'=> Mage::helper('featuredproduct')->__('Change featured product'),
             'url'  => $this->getUrl('*/*/massFeaturedproduct', array('_current'=>true)),
             'additional' => array(
                    'visibility' => array(
                         'name' => 'fb_product',
                         'type' => 'select',
                         'class' => 'required-entry',
                         'label' => Mage::helper('featuredproduct')->__('Featured Product'),
                         'values' => $featuredproduct
                     )
             )
        ));

        return $this;
    }
}
