<?php
	
class Mks_Rotateproduct_Block_Adminhtml_Rotateproduct_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
		public function __construct()
		{

				parent::__construct();
				$this->_objectId = "id";
				$this->_blockGroup = "rotateproduct";
				$this->_controller = "adminhtml_rotateproduct";
				$this->_updateButton("save", "label", Mage::helper("rotateproduct")->__("Save Item"));
				$this->_updateButton("delete", "label", Mage::helper("rotateproduct")->__("Delete Item"));

				$this->_addButton("saveandcontinue", array(
					"label"     => Mage::helper("rotateproduct")->__("Save And Continue Edit"),
					"onclick"   => "saveAndContinueEdit()",
					"class"     => "save",
				), -100);



				$this->_formScripts[] = "

							function saveAndContinueEdit(){
								editForm.submit($('edit_form').action+'back/edit/');
							}
						";
		}

		public function getHeaderText()
		{
				if( Mage::registry("rotateproduct_data") && Mage::registry("rotateproduct_data")->getId() ){

				    return Mage::helper("rotateproduct")->__("Edit Item '%s'", $this->htmlEscape(Mage::registry("rotateproduct_data")->getId()));

				} 
				else{

				     return Mage::helper("rotateproduct")->__("Add Item");

				}
		}
}