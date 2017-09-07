<?php

class Mks_Rotateproduct_Block_Adminhtml_Rotateproduct_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

		public function __construct()
		{
				parent::__construct();
				$this->setId("rotateproductGrid");
				$this->setDefaultSort("id");
				$this->setDefaultDir("DESC");
				$this->setSaveParametersInSession(true);
		}

		protected function _prepareCollection()
		{
				$collection = Mage::getModel("rotateproduct/rotateproduct")->getCollection();
				$this->setCollection($collection);
				return parent::_prepareCollection();
		}
		protected function _prepareColumns()
		{
				$this->addColumn("id", array(
				"header" => Mage::helper("rotateproduct")->__("ID"),
				"align" =>"right",
				"width" => "50px",
			    "type" => "number",
				"index" => "id",
				));
                
				$this->addColumn("productid", array(
				"header" => Mage::helper("rotateproduct")->__("Product Id"),
				"index" => "productid",
				));
				$this->addColumn("imagename", array(
				"header" => Mage::helper("rotateproduct")->__("Image Name"),
				"index" => "imagename",
				));
				$this->addColumn("imageorder", array(
				"header" => Mage::helper("rotateproduct")->__("Image Order"),
				"index" => "imageorder",
				));
						$this->addColumn('status', array(
						'header' => Mage::helper('rotateproduct')->__('Status'),
						'index' => 'status',
						'type' => 'options',
						'options'=>Mks_Rotateproduct_Block_Adminhtml_Rotateproduct_Grid::getOptionArray4(),				
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
			$this->getMassactionBlock()->addItem('remove_rotateproduct', array(
					 'label'=> Mage::helper('rotateproduct')->__('Remove Rotateproduct'),
					 'url'  => $this->getUrl('*/adminhtml_rotateproduct/massRemove'),
					 'confirm' => Mage::helper('rotateproduct')->__('Are you sure?')
				));
			return $this;
		}
			
		static public function getOptionArray4()
		{
            $data_array=array(); 
			$data_array[0]='Yes';
			$data_array[1]='No';
            return($data_array);
		}
		static public function getValueArray4()
		{
            $data_array=array();
			foreach(Mks_Rotateproduct_Block_Adminhtml_Rotateproduct_Grid::getOptionArray4() as $k=>$v){
               $data_array[]=array('value'=>$k,'label'=>$v);		
			}
            return($data_array);

		}
		

}