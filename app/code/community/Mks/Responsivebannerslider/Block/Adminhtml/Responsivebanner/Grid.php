<?php

class Mks_Responsivebannerslider_Block_Adminhtml_Responsivebanner_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

		public function __construct()
		{
				parent::__construct();
				$this->setId("responsivebannerGrid");
				$this->setDefaultSort("id");
				$this->setDefaultDir("DESC");
				$this->setSaveParametersInSession(true);
		}

		protected function _prepareCollection()
		{
				$collection = Mage::getModel("responsivebannerslider/responsivebanner")->getCollection();
				$this->setCollection($collection);
				return parent::_prepareCollection();
		}
		protected function _prepareColumns()
		{
				$this->addColumn("id", array(
				"header" => Mage::helper("responsivebannerslider")->__("ID"),
				"align" =>"right",
				"width" => "50px",
			    "type" => "number",
				"index" => "id",
				));
                
				$this->addColumn("title", array(
				"header" => Mage::helper("responsivebannerslider")->__("Title"),
				"index" => "title",
				));
				$this->addColumn("url", array(
				"header" => Mage::helper("responsivebannerslider")->__("URL"),
				"index" => "url",
				));
				$this->addColumn("status", array(
				"header" => Mage::helper("responsivebannerslider")->__("Status"),
				"index" => "status",
				));				
		 $this->addRssList('responsivebannerslider/adminhtml_rss_rss/responsivebanner', Mage::helper('responsivebannerslider')->__('RSS'));
			$this->addExportType('*/*/exportCsv', Mage::helper('sales')->__('CSV')); 
			$this->addExportType('*/*/exportExcel', Mage::helper('sales')->__('Excel'));

				return parent::_prepareColumns();
		}

		public function getRowUrl($row)
		{
			   return $this->getUrl("*/*/edit", array("id" => $row->getId()));
		}


		
		protected function _prepareMassaction()
		{
			$this->setMassactionIdField('id');
			$this->getMassactionBlock()->setFormFieldName('ids');
			$this->getMassactionBlock()->setUseSelectAll(true);
			$this->getMassactionBlock()->addItem('remove_responsivebanner', array(
					 'label'=> Mage::helper('responsivebannerslider')->__('Remove Responsivebanner'),
					 'url'  => $this->getUrl('*/adminhtml_responsivebanner/massRemove'),
					 'confirm' => Mage::helper('responsivebannerslider')->__('Are you sure?')
				));
			return $this;
		}
			

}