<?php
class Mks_Rotateproduct_Block_Adminhtml_Rotateproduct_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
		public function __construct()
		{
				parent::__construct();
				$this->setId("rotateproduct_tabs");
				$this->setDestElementId("edit_form");
				$this->setTitle(Mage::helper("rotateproduct")->__("Item Information"));
		}
		protected function _beforeToHtml()
		{
				$this->addTab("form_section", array(
				"label" => Mage::helper("rotateproduct")->__("Item Information"),
				"title" => Mage::helper("rotateproduct")->__("Item Information"),
				"content" => $this->getLayout()->createBlock("rotateproduct/adminhtml_rotateproduct_edit_tab_form")->toHtml(),
				));
				return parent::_beforeToHtml();
		}

}
