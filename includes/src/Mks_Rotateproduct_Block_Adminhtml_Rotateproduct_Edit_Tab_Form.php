<?php
class Mks_Rotateproduct_Block_Adminhtml_Rotateproduct_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
		protected function _prepareForm()
		{

				$form = new Varien_Data_Form();
				$this->setForm($form);
				$fieldset = $form->addFieldset("rotateproduct_form", array("legend"=>Mage::helper("rotateproduct")->__("Item information")));

				
						$fieldset->addField("productid", "text", array(
						"label" => Mage::helper("rotateproduct")->__("Product Id"),					
						"class" => "required-entry",
						"required" => true,
						"name" => "productid",
						));
					
						$fieldset->addField("imagename", "text", array(
						"label" => Mage::helper("rotateproduct")->__("Image Name"),					
						"class" => "required-entry",
						"required" => true,
						"name" => "imagename",
						));
									
						$fieldset->addField('productimage', 'image', array(
						'label' => Mage::helper('rotateproduct')->__('Product Image'),
						'name' => 'productimage',
						'note' => '(*.jpg, *.png, *.gif)',
						));
						$fieldset->addField("imageorder", "text", array(
						"label" => Mage::helper("rotateproduct")->__("Image Order"),					
						"class" => "required-entry",
						"required" => true,
						"name" => "imageorder",
						));
									
						 $fieldset->addField('status', 'select', array(
						'label'     => Mage::helper('rotateproduct')->__('Status'),
						'values'   => Mks_Rotateproduct_Block_Adminhtml_Rotateproduct_Grid::getValueArray4(),
						'name' => 'status',					
						"class" => "required-entry",
						"required" => true,
						));

				if (Mage::getSingleton("adminhtml/session")->getRotateproductData())
				{
					$form->setValues(Mage::getSingleton("adminhtml/session")->getRotateproductData());
					Mage::getSingleton("adminhtml/session")->setRotateproductData(null);
				} 
				elseif(Mage::registry("rotateproduct_data")) {
				    $form->setValues(Mage::registry("rotateproduct_data")->getData());
				}
				return parent::_prepareForm();
		}
}
