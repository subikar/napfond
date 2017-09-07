<?php

class Mks_Storelocator_Block_Adminhtml_Storelocator_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

		public function __construct()
		{
				parent::__construct();
				$this->setId("storelocatorGrid");
				$this->setDefaultSort("id");
				$this->setDefaultDir("DESC");
				$this->setSaveParametersInSession(true);
		}

		protected function _prepareCollection()
		{
				$collection = Mage::getModel("storelocator/storelocator")->getCollection();
				$this->setCollection($collection);
				return parent::_prepareCollection();
		}
		protected function _prepareColumns()
		{
				$this->addColumn("id", array(
				"header" => Mage::helper("storelocator")->__("ID"),
				"align" =>"right",
				"width" => "50px",
			    "type" => "number",
				"index" => "id",
				));
                
				$this->addColumn("name", array(
				"header" => Mage::helper("storelocator")->__("Name"),
				"index" => "name",
				));
				$this->addColumn("zipcode", array(
				"header" => Mage::helper("storelocator")->__("Zipcode"),
				"index" => "zipcode",
				));
				$this->addColumn("city", array(
				"header" => Mage::helper("storelocator")->__("City"),
				"index" => "city",
				));
				$this->addColumn("country_id", array(
				"header" => Mage::helper("storelocator")->__("Country"),
				"index" => "country_id",
				));
				$this->addColumn("phone", array(
				"header" => Mage::helper("storelocator")->__("Phone"),
				"index" => "phone",
				));
				$this->addColumn("fax", array(
				"header" => Mage::helper("storelocator")->__("Fax"),
				"index" => "fax",
				));
				$this->addColumn("store_url", array(
				"header" => Mage::helper("storelocator")->__("Store Url"),
				"index" => "store_url",
				));
				$this->addColumn("email", array(
				"header" => Mage::helper("storelocator")->__("Email"),
				"index" => "email",
				));
				$this->addColumn("tradinghours", array(
				"header" => Mage::helper("storelocator")->__("Trading Hours"),
				"index" => "tradinghours",
				));
				$this->addColumn("radius", array(
				"header" => Mage::helper("storelocator")->__("Radius"),
				"index" => "radius",
				));
				$this->addColumn("lat", array(
				"header" => Mage::helper("storelocator")->__("Latitude"),
				"index" => "lat",
				));
				$this->addColumn("longt", array(
				"header" => Mage::helper("storelocator")->__("Longitude"),
				"index" => "longt",
				));
						$this->addColumn('status', array(
						'header' => Mage::helper('storelocator')->__('Status'),
						'index' => 'status',
						'type' => 'options',
						'options'=>Mks_Storelocator_Block_Adminhtml_Storelocator_Grid::getOptionArray15(),				
						));
						
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
			$this->getMassactionBlock()->addItem('remove_storelocator', array(
					 'label'=> Mage::helper('storelocator')->__('Remove Storelocator'),
					 'url'  => $this->getUrl('*/adminhtml_storelocator/massRemove'),
					 'confirm' => Mage::helper('storelocator')->__('Are you sure?')
				));
			return $this;
		}
			
		static public function getOptionArray15()
		{
            $data_array=array(); 
			$data_array[0]='Enable';
			$data_array[1]='Disasble';
            return($data_array);
		}
		static public function getValueArray15()
		{
            $data_array=array();
			foreach(Mks_Storelocator_Block_Adminhtml_Storelocator_Grid::getOptionArray15() as $k=>$v){
               $data_array[]=array('value'=>$k,'label'=>$v);		
			}
            return($data_array);

		}
		

}