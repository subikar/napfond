<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Catalog
 * @copyright   Copyright (c) 2014 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


/**
 * Product list
 *
 * @category   Mage
 * @package    Mage_Catalog
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Mage_Catalog_Block_Product_List extends Mage_Catalog_Block_Product_Abstract
{
    /**
     * Default toolbar block name
     *
     * @var string
     */
    protected $_defaultToolbarBlock = 'catalog/product_list_toolbar';

    /**
     * Product Collection
     *
     * @var Mage_Eav_Model_Entity_Collection_Abstract
     */
    protected $_productCollection;

    /**
     * Retrieve loaded category collection
     *
     * @return Mage_Eav_Model_Entity_Collection_Abstract
     */
    protected function _getProductCollection()
    {
		$params = $this->getRequest()->getParams();
		//$case_style = $params['case_style'];
        if (is_null($this->_productCollection)) {
            $layer = $this->getLayer();
            /* @var $layer Mage_Catalog_Model_Layer */
            if ($this->getShowRootCategory()) {
                $this->setCategoryId(Mage::app()->getStore()->getRootCategoryId());
            }

            // if this is a product view page
            if (Mage::registry('product')) {
                // get collection of categories this product is associated with
                $categories = Mage::registry('product')->getCategoryCollection()
                    ->setPage(1, 1)
                    ->load();
                // if the product is associated with any category
                if ($categories->count()) {
                    // show products from this category
                    $this->setCategoryId(current($categories->getIterator()));
                }
            }

            $origCategory = null;
            if ($this->getCategoryId()) {
                $category = Mage::getModel('catalog/category')->load($this->getCategoryId());
                if ($category->getId()) {
                    $origCategory = $layer->getCurrentCategory();
                    $layer->setCurrentCategory($category);
                    $this->addModelTags($category);
                }
            }
						
			$this->_productCollection = $layer->getProductCollection();
			
            $this->prepareSortableFieldsByCategory($layer->getCurrentCategory());
			//$this->prepareSortableFieldsByAttribute('case_style', $case_style);

            if ($origCategory) {
                $layer->setCurrentCategory($origCategory);
            }
        }
		
	    $getCurrentCategory = Mage::registry('current_category');
				
		/*if($getCurrentCategory->getId() != 31 && $getCurrentCategory->getId() != 32  && $getCurrentCategory->getId() != 33  && $getCurrentCategory->getId() != 34 && $getCurrentCategory->getId() != 4)
		{
			//$this->_productCollection->addAttributeToFilter('entity_id', array('from' => '110798'));
		}*/
		
		/*if(isset($params['p']) && $params['p'] != '')
		{		
			$cat = Mage::getModel('catalog/category')->load(229);
			$this->_productCollection->distinct(true)->addAttributeToFilter('category_id', array('in' => array(229,39)));
			 //$this->_productCollection->addAttributeToSelect('*')->addAttributeToFilter('case_style', $case_style)->getSelect()->order(new Zend_Db_Expr('RAND()'));
		}*/
		
		//print_r($this->_productCollection);die;
		
		$currentUrl = Mage::helper('core/url')->getCurrentUrl();
		$url = Mage::getSingleton('core/url')->parseUrl($currentUrl);
		$path = $url->getPath();		
		
		
		if(is_object($getCurrentCategory))
		{
			if($getCurrentCategory->getId() == 24)	
			$this->_productCollection->getSelect()->order(new Zend_Db_Expr('RAND()'));
		}
		else if(strpos($path,'result') > 0){		
			//$this->_productCollection->getSelect()->order(new Zend_Db_Expr('RAND()'));
		}
		
		if($params['texturedPrdStr']!=''){
		$texturedPrdStr = explode(',',$_GET['texturedPrdStr']);
		
			if($params['laptabtexturedskin']=='yes' && $getCurrentCategory->getId() == 24){					
				$ele1 = array_pop($texturedPrdStr);
				$ele2 = array_pop($texturedPrdStr);
				$texturedPrdStr[] = $ele1;			
			}
		}
	 
		
		if(count($texturedPrdStr) > 0){
			
		 if($params['case_style']!='')
		 {
			
			
				// remove previous items, keep them in $items
				$items = $this->_productCollection->getItems();
				foreach ($this->_productCollection as $key => $item) {
				 $this->_productCollection->removeItemByKey($key);
				}

				// add new item
				
				foreach($texturedPrdStr as $texturedPrdStrVal){
				$product = Mage::getModel('catalog/product')->load($texturedPrdStrVal);
				$this->_productCollection->addItem($product);
		        }
				// re-add $items
				foreach ($items as $item) {
				  if(!in_array($item->getId(),$texturedPrdStr))
					$this->_productCollection->addItem($item);
				}			
			
		 }	 
		 else{
		   $this->_productCollection = $this->_productCollection->addAttributeToFilter('entity_id', array('in' => $texturedPrdStr));
		 }
		}

		
  	    return $this->_productCollection;
    }

    /**
     * Get catalog layer model
     *
     * @return Mage_Catalog_Model_Layer
     */
    public function getLayer()
    {
        $layer = Mage::registry('current_layer');
        if ($layer) {
            return $layer;
        }
        return Mage::getSingleton('catalog/layer');
    }

    /**
     * Retrieve loaded category collection
     *
     * @return Mage_Eav_Model_Entity_Collection_Abstract
     */
    public function getLoadedProductCollection()
    {
        return $this->_getProductCollection();
    }

    /**
     * Retrieve current view mode
     *
     * @return string
     */
    public function getMode()
    {
        return $this->getChild('toolbar')->getCurrentMode();
    }

    /**
     * Need use as _prepareLayout - but problem in declaring collection from
     * another block (was problem with search result)
     */
    protected function _beforeToHtml()
    {
        $toolbar = $this->getToolbarBlock();

        // called prepare sortable parameters
        $collection = $this->_getProductCollection();

        // use sortable parameters
        if ($orders = $this->getAvailableOrders()) {
            $toolbar->setAvailableOrders($orders);
        }
        if ($sort = $this->getSortBy()) {
            $toolbar->setDefaultOrder($sort);
        }
        if ($dir = $this->getDefaultDirection()) {
            $toolbar->setDefaultDirection($dir);
        }
        if ($modes = $this->getModes()) {
            $toolbar->setModes($modes);
        }

        // set collection to toolbar and apply sort
        $toolbar->setCollection($collection);

        $this->setChild('toolbar', $toolbar);
        Mage::dispatchEvent('catalog_block_product_list_collection', array(
            'collection' => $this->_getProductCollection()
        ));

        $this->_getProductCollection()->load();

        return parent::_beforeToHtml();
    }

    /**
     * Retrieve Toolbar block
     *
     * @return Mage_Catalog_Block_Product_List_Toolbar
     */
    public function getToolbarBlock()
    {
        if ($blockName = $this->getToolbarBlockName()) {
            if ($block = $this->getLayout()->getBlock($blockName)) {
                return $block;
            }
        }
        $block = $this->getLayout()->createBlock($this->_defaultToolbarBlock, microtime());
        return $block;
    }

    /**
     * Retrieve additional blocks html
     *
     * @return string
     */
    public function getAdditionalHtml()
    {
        return $this->getChildHtml('additional');
    }

    /**
     * Retrieve list toolbar HTML
     *
     * @return string
     */
    public function getToolbarHtml()
    {
        return $this->getChildHtml('toolbar');
    }

    public function setCollection($collection)
    {
        $this->_productCollection = $collection;
        return $this;
    }

    public function addAttribute($code)
    {
        $this->_getProductCollection()->addAttributeToSelect($code);
        return $this;
    }

    public function getPriceBlockTemplate()
    {
        return $this->_getData('price_block_template');
    }

    /**
     * Retrieve Catalog Config object
     *
     * @return Mage_Catalog_Model_Config
     */
    protected function _getConfig()
    {
        return Mage::getSingleton('catalog/config');
    }

    /**
     * Prepare Sort By fields from Category Data
     *
     * @param Mage_Catalog_Model_Category $category
     * @return Mage_Catalog_Block_Product_List
     */
    public function prepareSortableFieldsByCategory($category) {
        if (!$this->getAvailableOrders()) {
            $this->setAvailableOrders($category->getAvailableSortByOptions());
        }
        $availableOrders = $this->getAvailableOrders();
        if (!$this->getSortBy()) {
            if ($categorySortBy = $category->getDefaultSortBy()) {
                if (!$availableOrders) {
                    $availableOrders = $this->_getConfig()->getAttributeUsedForSortByArray();
                }
                if (isset($availableOrders[$categorySortBy])) {
                    $this->setSortBy($categorySortBy);
                }
            }
        }

        return $this;
    }

    /**
     * Retrieve block cache tags based on product collection
     *
     * @return array
     */
    public function getCacheTags()
    {
        return array_merge(
            parent::getCacheTags(),
            $this->getItemsTags($this->_getProductCollection())
        );
    }
}
