<?php

class Magestore_Faq_Block_Adminhtml_Faq_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('faqGrid');
      $this->setDefaultSort('faq_id');
      $this->setDefaultDir('ASC');
      $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
      $collection = Mage::getModel('faq/faq')->getCollection();
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
      $this->addColumn('faq_id', array(
          'header'    => Mage::helper('faq')->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'faq_id',
      ));

	  
	  
      $this->addColumn('title', array(
          'header'    => Mage::helper('faq')->__('Title'),
          'align'     =>'left',
          'index'     => 'title',
		  'width'     => '250',
      ));

	  $this->addColumn('ordering', array(
          'header'    => Mage::helper('faq')->__('Ordering'),
          'align'     =>'left',
		   'width'     => '80',
          'index'     => 'ordering',
      ));
	  
	  $this->addColumn('category_id', array(
         'header'    => Mage::helper('faq')->__('Category'),
          'align'     => 'left',
		  'width'     => '150',
          'index'     => 'category_id',
		  'type'      =>'options',
		  'options'   => Mage::helper('faq')->getCategoryOptions1(),
      ));
	  
	  
      $this->addColumn('url_key', array(
			'header'    => Mage::helper('faq')->__('Url Key'),
			'width'     => '250px',
			'index'     => 'url_key',			
      ));
	  
      $this->addColumn('status', array(
          'header'    => Mage::helper('faq')->__('Status'),
          'align'     => 'left',
          'width'     => '80px',
          'index'     => 'status',
          'type'      => 'options',
          'options'   => array(
              1 => 'Enabled',
              2 => 'Disabled',
          ),
      ));
	  
        $this->addColumn('action',
            array(
                'header'    =>  Mage::helper('faq')->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('faq')->__('Edit'),
                        'url'       => array('base'=> '*/*/edit'),
                        'field'     => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));
		
		$this->addExportType('*/*/exportCsv', Mage::helper('faq')->__('CSV'));
		$this->addExportType('*/*/exportXml', Mage::helper('faq')->__('XML'));
	  
      return parent::_prepareColumns();
  }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('faq_id');
        $this->getMassactionBlock()->setFormFieldName('faq');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('faq')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('faq')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('faq/status')->getOptionArray();

        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('status', array(
             'label'=> Mage::helper('faq')->__('Change status'),
             'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
             'additional' => array(
                    'visibility' => array(
                         'name' => 'status',
                         'type' => 'select',
                         'class' => 'required-entry',
                         'label' => Mage::helper('faq')->__('Status'),
                         'values' => $statuses
                     )
             )
        ));
        return $this;
    }

  public function getRowUrl($row)
  {
      return $this->getUrl('*/*/edit', array('id' => $row->getId()));
  }

}