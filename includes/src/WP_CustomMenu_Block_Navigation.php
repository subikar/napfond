<?php

class WP_CustomMenu_Block_Navigation extends Mage_Catalog_Block_Navigation
{
    const CUSTOM_BLOCK_TEMPLATE = "wp_custom_menu_%d";

    private $_productsCount     = null;
    private $_topMenu           = array();
    private $_popupMenu         = array();
	
	public static $tShirtHtmlIncludeShop = true;
	public static $tShirtHtmlIncludeCreate = true;
	public static $tShirtHtmlIncludeCounter = 1;

	
    public function drawCustomMenuMobileItem($category, $level = 0, $last = false)
    {
        if (!$category->getIsActive()) return '';
        $html = array();
        $id = $category->getId();
        // --- Sub Categories ---
        $activeChildren = $this->_getActiveChildren($category, $level);
        // --- class for active category ---
        $active = ''; if ($this->isCategoryActive($category)) $active = ' act';
        $hasSubMenu = count($activeChildren);
        $catUrl = rtrim($this->getCategoryUrl($category),'/');
        $html[] = '<div id="menu-mobile-' . $id . '" class="menu-mobile level0' . $active . '">';
        $html[] = '<div class="parentMenu">';
        // --- Top Menu Item ---
        $html[] = '<a href="' . $catUrl .'">';
        $name = $this->escapeHtml($category->getName());
        if (Mage::getStoreConfig('custom_menu/general/non_breaking_space')) {
            $name = str_replace(' ', '&nbsp;', $name);
        }
        $html[] = '<span>' . $name . '</span>';
        $html[] = '</a>';
        if ($hasSubMenu) {
            $html[] = '<span class="button" rel="submenu-mobile-' . $id . '" onclick="wpSubMenuToggle(this, \'menu-mobile-' . $id . '\', \'submenu-mobile-' . $id . '\');">&nbsp;</span>';
        }
        $html[] = '</div>';
        // --- Add Popup block (hidden) ---
        if ($hasSubMenu) {
            // --- draw Sub Categories ---
            $html[] = '<div id="submenu-mobile-' . $id . '" rel="level' . $level . '" class="wp-custom-menu-submenu" style="display: none;">';
            $html[] = $this->drawMobileMenuItem($activeChildren);
            $html[] = '<div class="clearBoth"></div>';
            $html[] = '</div>';
        }
        $html[] = '</div>';
        $html = implode("\n", $html);
        return $html;
    }

    public function getTopMenuArray()
    {
        return $this->_topMenu;
    }

    public function getPopupMenuArray()
    {
        return $this->_popupMenu;
    }

    public function drawCustomMenuItem($category, $level = 0, $last = false)
    {
        if (!$category->getIsActive()) return;
        $htmlTop = array();
        $id = $category->getId();
        // --- Static Block ---
        $blockId = sprintf(self::CUSTOM_BLOCK_TEMPLATE, $id); // --- static block key
        #Mage::log($blockId);
        $collection = Mage::getModel('cms/block')->getCollection()
            ->addFieldToFilter('identifier', array(array('like' => $blockId . '_w%'), array('eq' => $blockId)))
            ->addFieldToFilter('is_active', 1);
        $blockId = $collection->getFirstItem()->getIdentifier();
        #Mage::log($blockId);
        $blockHtml = Mage::app()->getLayout()->createBlock('cms/block')->setBlockId($blockId)->toHtml();
        // --- Sub Categories ---
        $activeChildren = $this->_getActiveChildren($category, $level);
        // --- class for active category ---
        $active = ''; if ($this->isCategoryActive($category)) $active = ' act';
        // --- Popup functions for show ---
        $drawPopup = ($blockHtml || count($activeChildren));
        if ($drawPopup) {
            $htmlTop[] = '<div id="menu' . $id . '" class="menu' . $active . '" onmouseover="wpShowMenuPopup(this, event, \'popup' . $id . '\');" onmouseout="wpHideMenuPopup(this, event, \'popup' . $id . '\', \'menu' . $id . '\')">';
        } else {
            $htmlTop[] = '<div id="menu' . $id . '" class="menu' . $active . '">';
        }
        // --- Top Menu Item ---
        $htmlTop[] = '<div class="parentMenu">';
		
		$name = $this->escapeHtml($category->getName());
		
		if(trim($name) == 'Gift'){
			//$htmlTop[] = '<a href="'.Mage::helper('cms/page')->getPageUrl( 14 ).'" rel="'.Mage::helper('cms/page')->getPageUrl( 14 ).'">';
			$htmlTop[] = '<a href="#" rel="#">';
		}	
		else if(trim($name) == 'Shop'){ 
		
			$htmlTop[] = '<a href="'.Mage::helper('cms/page')->getPageUrl( 13 ).'" rel="'.Mage::helper('cms/page')->getPageUrl( 13 ).'">';
		}	
        else if ($level == 0 && $drawPopup) {
            $htmlTop[] = '<a href="javascript:void(0);" rel="'.rtrim($this->getCategoryUrl($category),'/').'">';
        } else {
            $htmlTop[] = '<a href="'.rtrim($this->getCategoryUrl($category),'/').'">';
        }
        
        if (Mage::getStoreConfig('custom_menu/general/non_breaking_space')) {
            $name = str_replace(' ', '&nbsp;', $name);
        }
        $htmlTop[] = '<span>' . $name . '</span>';
        $htmlTop[] = '</a>';
        $htmlTop[] = '</div>';
        $htmlTop[] = '</div>';
        $this->_topMenu[] = implode("\n", $htmlTop);
        // --- Add Popup block (hidden) ---
        if ($drawPopup) {
            $htmlPopup = array();
            // --- Popup function for hide ---
            $htmlPopup[] = '<div id="popup' . $id . '" class="wp-custom-menu-popup" onmouseout="wpHideMenuPopup(this, event, \'popup' . $id . '\', \'menu' . $id . '\')" onmouseover="wpPopupOver(this, event, \'popup' . $id . '\', \'menu' . $id . '\')">';
            // --- draw Sub Categories ---
            if (count($activeChildren)) {
                $columns = (int)Mage::getStoreConfig('custom_menu/columns/count');
                $htmlPopup[] = '<div class="block1">';
                $htmlPopup[] = $this->drawColumns($activeChildren, $columns, $id);
				if($id == 24){
                $htmlPopup[] = '<div class="row">
                                 <div class="drop-footer">
                                  <div class="col-sm-12"><div class="col-md-7">
                                   <div class="row aj_R-flyoutPodRow">        
                                    <div class="aj_R-imagePod">                                                    
                                     <a href="'.Mage::getBaseUrl().'promotional_gift'.'" class="aj_R-flyoutPodBlockLink">                                                        
                                      <img src="'.$this->getSkinUrl('images/gift-drop.png').'" class="aj_R-podImage">Save big with bhishoom coupons & promotional offers
                                      <div style="" class="aj_R-pseudoLink">See our deals ></div>
                                     </a>
                                    </div>   
                                   </div>
                                  </div>
                                  <div class="col-md-5">
                                   <div class="row aj_R-flyoutPodRow">
                                    <div class="aj_R-imagePod1">
                                     <a href="'.Mage::getBaseUrl().'create.html'.'" class="aj_R-flyoutPodBlockLink">
                                      <img src="'.$this->getSkinUrl('images/getimage1.png').'" class="aj_R-podImage-right">Create you own
                                      <div style="" class="aj_R-pseudoLink">(Design your own product)</div>
                                     </a>
                                    </div>   
                                   </div></div>
                                  </div>
                                 </div>
                                </div>';	
				}
				if($id == 27){
                $htmlPopup[] = '<div class="row1">
                                 <div class="drop-footer">
                                  <div class="col-sm-12"><div class="col-md-7">
                                   <div class="row aj_R-flyoutPodRow">        
                                    <div class="aj_R-imagePod">                                                    
                                     <a href="'.Mage::getBaseUrl().'eform" class="aj_R-flyoutPodBlockLink">                                                        
                                      <img src="'.$this->getSkinUrl('images/git-certificate.jpg').'" class="aj_R-podImage"><span class="aj_R-podspan">Corporate Gift</span>
                                      <div style="" class="aj_R-pseudoLink1">Please contact us at "bulkorder@bhishoom.com" for all your bulk order/corporate gifting </div>
                                     </a>
                                    </div>   
                                   </div>
                                  </div>
                                  <div class="col-md-5">
                                   <div class="row aj_R-flyoutPodRow">
                                    <div class="aj_R-imagePod1">
                                     <a href="'.Mage::getBaseUrl().'gift/gift-certificate/gift-certificate.html" class="aj_R-flyoutPodBlockLink">
                                      <img src="'.$this->getSkinUrl('images/gift-drop.jpg').'" class="aj_R-podImage-right"><span class="aj_R-podspan">Gift Certificate</span>
                                      <div style="" class="aj_R-pseudoLink">Buy a gift certificate and give your special one freedom to choose their gift from Bhishoom.</div>
                                     </a>
                                    </div>   
                                   </div>
                                  </div></div>
                                 </div>
                                </div>';	
				}
				$htmlPopup[] = '</div>';
            }
            // --- draw Custom User Block ---
			
			if($blockHtml) 
			{
				$htmlPopup[] = '<div id="' . $blockId . '" class="mobile-case">';
				$htmlPopup[] = $blockHtml;
				$htmlPopup[] = '</div>';
			}            
            $htmlPopup[] = '</div>';
            $this->_popupMenu[] = implode("\n", $htmlPopup);
        }
    }

    public function drawMobileMenuItem($children, $level = 1)
    {
        $keyCurrent = $this->getCurrentCategory()->getId();
        $html = '';
        foreach ($children as $child) {
            if (is_object($child) && $child->getIsActive()) {
                // --- class for active category ---
                $active = '';
                $id = $child->getId();
                $activeChildren = $this->_getActiveChildren($child, $level);
                if ($this->isCategoryActive($child)) {
                    $active = ' actParent';
                    if ($id == $keyCurrent) $active = ' act';
                }
                $html.= '<div id="menu-mobile-' . $id . '" class="itemMenu level' . $level . $active . '">';
                // --- format category name ---
                $name = $this->escapeHtml($child->getName());
                if (Mage::getStoreConfig('custom_menu/general/non_breaking_space')) $name = str_replace(' ', '&nbsp;', $name);
                $html.= '<div class="parentMenu">';
                $html.= '<a class="itemMenuName level' . $level . '" href="' . $this->getCategoryUrl($child) . '"><span>' . $name . '</span></a>';
                if (count($activeChildren) > 0) {
                    $html.= '<span class="button" rel="submenu-mobile-' . $id . '" onclick="wpSubMenuToggle(this, \'menu-mobile-' . $id . '\', \'submenu-mobile-' . $id . '\');">&nbsp;</span>';
                }
                $html.= '</div>';
                if (count($activeChildren) > 0) {
                    $html.= '<div id="submenu-mobile-' . $id . '" rel="level' . $level . '" class="wp-custom-menu-submenu level' . $level . '" style="display: none;">';
                    $html.= $this->drawMobileMenuItem($activeChildren, $level + 1);
                    $html.= '<div class="clearBoth"></div>';
                    $html.= '</div>';
                }
                $html.= '</div>';
            }
        }
        return $html;
    }

    public function drawMenuItem($children, $level = 1,$iiVar)
    {
		
 		
		
$keyCurrent = $this->getCurrentCategory()->getId();
if($iiVar == 3 && self::$tShirtHtmlIncludeShop == true && self::$tShirtHtmlIncludeCounter == 5){
$html = '
<a href="javascript:void(0)" class="itemMenuName level1"><span>T-Shirt</span></a>
<div class="itemSubMenu level1">
<div class="itemMenu level2">
<a href="http://www.bhishoom.com/shop/t-shirt/him.html" class="itemMenuName level2"><span>Him</span></a>
<a href="http://www.bhishoom.com/shop/t-shirt/her.html" class="itemMenuName level2"><span>Her</span></a>
</div>
</div>
';
$html .= '<div class="itemMenu level' . $level . '">';
self::$tShirtHtmlIncludeShop = false;

}
elseif($iiVar == 3 && self::$tShirtHtmlIncludeCreate == true && self::$tShirtHtmlIncludeCounter == 12){
$html = '
<a href="javascript:void(0)" class="itemMenuName level1"><span>T-Shirt</span></a>
<div class="itemSubMenu level1">
<div class="itemMenu level2">
<a href="http://www.bhishoom.com/create/t-shirt/him.html" class="itemMenuName level2"><span>Him</span></a>
<a href="http://www.bhishoom.com/create/t-shirt/her.html" class="itemMenuName level2"><span>Her</span></a>
</div>
</div>
';
$html .= '<div class="itemMenu level' . $level . '">';
self::$tShirtHtmlIncludeCreate = false;

}
else
  $html = '<div class="itemMenu level' . $level . '">';
		 
self::$tShirtHtmlIncludeCounter++;		

		
        
        foreach ($children as $child) {
            if (is_object($child) && $child->getIsActive()) {
                // --- class for active category ---
                $active = '';
                if ($this->isCategoryActive($child)) {
                    $active = ' actParent';
                    if ($child->getId() == $keyCurrent) $active = ' act';
                }
                // --- format category name ---
                $name = $this->escapeHtml($child->getName());
					
				if(trim(strtolower($name)) == 't-shirt')
					continue;		
					
                if (Mage::getStoreConfig('custom_menu/general/non_breaking_space')) 
					$name = str_replace(' ', '&nbsp;', $name);
				if(trim(strtolower($name)) == 't-shirt')
					$html.= '<a class="itemMenuName level' . $level . $active . '" href="javascript:void(0)"><span>T-Shirt</span></a>';
				else
					$html.= '<a class="itemMenuName level' . $level . $active . '" href="' . rtrim($this->getCategoryUrl($child),'/') . '"><span>' . $name . '</span></a>';
				
				if(trim(strtolower($name)) == '17inch/18inch')
					$html.= '<a class="itemMenuName level' . $level . $active . '" href="' . rtrim(Mage::getModel("catalog/category")->load(84)->getUrl(),'/') . '"><span>Custom Size</span></a>';
				/*if(trim(strtolower($name)) == '17 inch-18 inch')
					$html.= '<a class="itemMenuName level' . $level . $active . '" href="' . Mage::getModel("catalog/category")->load(8)->getUrl() . '"><span>Custom Size</span></a>';*/
				
                $activeChildren = $this->_getActiveChildren($child, $level);
                if (count($activeChildren) > 0) {
                    $html.= '<div class="itemSubMenu level' . $level . '">';
                    $html.= $this->drawMenuItem($activeChildren, $level + 1,$iiVar);
                    $html.= '</div>';
                }
				
            }
        }
        $html.= '</div>';
        return $html;
    }

    public function drawColumns($children, $columns = 1, $parent_id)
    {
        $html = '';$sec = '';
        // --- explode by columns ---
        if ($columns < 1) $columns = 1;
        $chunks = $this->_explodeByColumns($children, $columns);
        // --- draw columns ---
        $lastColumnNumber = count($chunks);
        $i = 1;
        foreach ($chunks as $key => $value) {
            if (!count($value)) continue;
            $class = '';
            if ($i == 1)
			{
				$class.= ' first';
				if($parent_id == 27)
				{
					$html.= '<div class="col-md-7 custom-left-menu gift"><a href="'.Mage::getBaseUrl().'create.html"><img src="'.$this->getSkinUrl('images/gift1_create_drop.jpg').'"></a><div class="create-btn marginTop8px"><a href="'.Mage::getBaseUrl().'create.html">Create now</a></div>';
					$sec = 'Gift';
					$pagename = 'giftbystyle';
				}
				else if($parent_id == 24)
				{
					$html.= '<div class="col-md-7 custom-left-menu shop">';
					$sec = 'Shop';
					$pagename = 'shopbystyle';
				}
				else if($parent_id == 25)
				{
					$html.= '<div class="col-md-7 custom-left-menu create">';
				}
				else
				{
					$html.= '<div class="col-md-9 custom-left-menu">';
				}
				
			}
            if ($i == $lastColumnNumber) $class.= ' last';
			if ($i == $lastColumnNumber-1) $class.= ' second-last';
            if ($i % 2) $class.= ' odd'; else $class.= ' even';
            $html.= '<div class="column' . $class . '">';
            $html.= $this->drawMenuItem($value, 1,$i);					
            $html.= '</div>';
			if ($i == $lastColumnNumber)
			{
				$html.= '</div>';
				if($parent_id == 24 || $parent_id == 27)
				{
					$html.= '<div class="col-md-5 custom-right-menu">
								<a class="itemMenuName level1" href="javascript:void(0);"><span class="itemMenuStyle">'.$sec.' By Style</span></a>
                                <div class="column odd new-class left">                                    
									<div class="itemSubMenu level1">
										<div class="itemMenu level2">
										  <a class="itemMenuName level2" href="'.Mage::getBaseUrl().$pagename.'?case_style=189&p=1&ref=shopby'.strtolower($sec).'"><span>3D</span></a>
										  <a class="itemMenuName level2" href="'.Mage::getBaseUrl().$pagename.'?case_style=41&p=1&ref=shopby'.strtolower($sec).'"><span>Animals</span></a>
										  <a class="itemMenuName level2" href="'.Mage::getBaseUrl().$pagename.'?case_style=331&p=1&ref=shopby'.strtolower($sec).'"><span>Auto & Transportation</span></a>
										  <a class="itemMenuName level2" href="'.Mage::getBaseUrl().$pagename.'?case_style=58&p=1&ref=shopby'.strtolower($sec).'"><span>Comic & Cartoon</span></a>
										  <a class="itemMenuName level2" href="'.Mage::getBaseUrl().$pagename.'?case_style=48&p=1&ref=shopby'.strtolower($sec).'"><span>Cities & Travel</span></a>
										  <a class="itemMenuName level2" href="'.Mage::getBaseUrl().$pagename.'?case_style=193&p=1&ref=shopby'.strtolower($sec).'"><span>Desi</span></a>
										  <a class="itemMenuName level2" href="'.Mage::getBaseUrl().$pagename.'?case_style=109&p=1&ref=shopby'.strtolower($sec).'"><span>Floral</span></a>
										  <a class="itemMenuName level2" href="'.Mage::getBaseUrl().$pagename.'?case_style=38&p=1&ref=shopby'.strtolower($sec).'"><span>Famous</span></a>
										  <a class="itemMenuName level2" href="'.Mage::getBaseUrl().$pagename.'?case_style=67&p=1&ref=shopby'.strtolower($sec).'"><span>Fashion</span></a>										  
										  <a class="itemMenuName level2" href="'.Mage::getBaseUrl().$pagename.'?case_style=333&p=1&ref=shopby'.strtolower($sec).'"><span>Flags</span></a>										  
										  <a class="itemMenuName level2" href="'.Mage::getBaseUrl().$pagename.'?case_style=227&p=1&ref=shopby'.strtolower($sec).'"><span>Graphic</span></a>
										  <a class="itemMenuName level2" href="'.Mage::getBaseUrl().$pagename.'?case_style=335&p=1&ref=shopby'.strtolower($sec).'"><span>Gym & Health</span></a>
										  <a class="itemMenuName level2" href="'.Mage::getBaseUrl().$pagename.'?case_style=332&p=1&ref=shopby'.strtolower($sec).'"><span>Humour</span></a>
										  <a class="itemMenuName level2" href="'.Mage::getBaseUrl().$pagename.'?case_style=337&p=1&ref=shopby'.strtolower($sec).'"><span>Hearts</span></a>
										</div>
									</div>	  
                                </div>
								<div class="column even new-class right">
									<div class="itemSubMenu level1">
										<div class="itemMenu level2">											  
										  <a class="itemMenuName level2" href="'.Mage::getBaseUrl().$pagename.'?case_style=329&p=1&ref=shopby'.strtolower($sec).'"><span>Illustration</span></a>
										  <a class="itemMenuName level2" href="'.Mage::getBaseUrl().$pagename.'?case_style=113&p=1&ref=shopby'.strtolower($sec).'"><span>Jerseys</span></a>									  
										  <a class="itemMenuName level2" href="'.Mage::getBaseUrl().$pagename.'?case_style=200&p=1&ref=shopby'.strtolower($sec).'"><span>Movies & Television</span></a>							  
										  <a class="itemMenuName level2" href="'.Mage::getBaseUrl().$pagename.'?case_style=225&p=1&ref=shopby'.strtolower($sec).'"><span>Music</span></a>										  
										  <a class="itemMenuName level2" href="'.Mage::getBaseUrl().$pagename.'?case_style=199&p=1&ref=shopby'.strtolower($sec).'"><span>Nature & Landscape</span></a>								  
										  <a class="itemMenuName level2" href="'.Mage::getBaseUrl().$pagename.'?case_style=192&p=1&ref=shopby'.strtolower($sec).'"><span>Party</span></a>
										  <a class="itemMenuName level2" href="'.Mage::getBaseUrl().$pagename.'?case_style=195&p=1&ref=shopby'.strtolower($sec).'"><span>Political</span></a>
										  <a class="itemMenuName level2" href="'.Mage::getBaseUrl().$pagename.'?case_style=334&p=1&ref=shopby'.strtolower($sec).'"><span>Profession & Personality</span></a>					  
										  <a class="itemMenuName level2" href="'.Mage::getBaseUrl().$pagename.'?case_style=224&p=1&ref=shopby'.strtolower($sec).'"><span>Quirky</span></a>										  
										  <a class="itemMenuName level2" href="'.Mage::getBaseUrl().$pagename.'?case_style=198&p=1&ref=shopby'.strtolower($sec).'"><span>Religion & Spirituality</span></a>
										  <a class="itemMenuName level2" href="'.Mage::getBaseUrl().$pagename.'?case_style=196&p=1&ref=shopby'.strtolower($sec).'"><span>Retro</span></a>
										  <a class="itemMenuName level2" href="'.Mage::getBaseUrl().$pagename.'?case_style=56&p=1&ref=shopby'.strtolower($sec).'"><span>Sports</span></a>
										  <a class="itemMenuName level2" href="'.Mage::getBaseUrl().$pagename.'?case_style=115&p=1&ref=shopby'.strtolower($sec).'"><span>Textures & Patterns</span></a>
										  <a class="itemMenuName level2" href="'.Mage::getBaseUrl().$pagename.'?case_style=57&p=1&ref=shopby'.strtolower($sec).'"><span>Typography</span></a>
										  <a class="itemMenuName level2" href="'.Mage::getBaseUrl().$pagename.'?case_style=336&p=1&ref=shopby'.strtolower($sec).'"><span>Weapon</span></a>
										  <a class="itemMenuName level2" href="'.Mage::getBaseUrl().$pagename.'?case_style=53&p=1&ref=shopby'.strtolower($sec).'"><span>Zodiac</span></a>
										</div>
									</div>	  
                                </div>
                             </div>';
				}
			}	
            $i++;
        }
        return $html;
    }

    protected function _getActiveChildren($parent, $level)
    {
        $activeChildren = array();
        // --- check level ---
        $maxLevel = (int)Mage::getStoreConfig('custom_menu/general/max_level');
        if ($maxLevel > 0) {
            if ($level >= ($maxLevel - 1)) return $activeChildren;
        }
        // --- / check level ---
        if (Mage::helper('catalog/category_flat')->isEnabled()) {
            $children = $parent->getChildrenNodes();
            $childrenCount = count($children);
        } else {
            $children = $parent->getChildren();
            $childrenCount = $children->count();
        }
        $hasChildren = $children && $childrenCount;
        if ($hasChildren) {
            foreach ($children as $child) {
                if ($this->_isCategoryDisplayed($child)) {
                    array_push($activeChildren, $child);
                }
            }
        }
        return $activeChildren;
    }

    private function _isCategoryDisplayed(&$child)
    {
        if (!$child->getIsActive()) return false;
        // === check products count ===
        // --- get collection info ---
        if (!Mage::getStoreConfig('custom_menu/general/display_empty_categories')) {
            $data = $this->_getProductsCountData();
            // --- check by id ---
            $id = $child->getId();
            #Mage::log($id); Mage::log($data);
            if (!isset($data[$id]) || !$data[$id]['product_count']) return false;
        }
        // === / check products count ===
        return true;
    }

    private function _getProductsCountData()
    {
        if (is_null($this->_productsCount)) {
            $collection = Mage::getModel('catalog/category')->getCollection();
            $storeId = Mage::app()->getStore()->getId();
            /* @var $collection Mage_Catalog_Model_Resource_Eav_Mysql4_Category_Collection */
            $collection->addAttributeToSelect('name')
                ->addAttributeToSelect('is_active')
                ->setProductStoreId($storeId)
                ->setLoadProductCount(true)
                ->setStoreId($storeId);
            $productsCount = array();
            foreach($collection as $cat) {
                $productsCount[$cat->getId()] = array(
                    'name' => $cat->getName(),
                    'product_count' => $cat->getProductCount(),
                );
            }
            #Mage::log($productsCount);
            $this->_productsCount = $productsCount;
        }
        return $this->_productsCount;
    }

    private function _explodeByColumns($target, $num)
    {
        if ((int)Mage::getStoreConfig('custom_menu/columns/divided_horizontally')) {
            $target = self::_explodeArrayByColumnsHorisontal($target, $num);
        } else {
            $target = self::_explodeArrayByColumnsVertical($target, $num);
        }
        #return $target;
        if ((int)Mage::getStoreConfig('custom_menu/columns/integrate') && count($target)) {
            // --- combine consistently numerically small column ---
            // --- 1. calc length of each column ---
            $max = 0; $columnsLength = array();
            foreach ($target as $key => $child) {
                $count = 0;
                $this->_countChild($child, 1, $count);
                if ($max < $count) $max = $count;
                $columnsLength[$key] = $count;
            }
            // --- 2. merge small columns with next ---
            $xColumns = array(); $column = array(); $cnt = 0;
            $xColumnsLength = array(); $k = 0;
            foreach ($columnsLength as $key => $count) {
                $cnt+= $count;
                if ($cnt > $max && count($column)) {
                    $xColumns[$k] = $column;
                    $xColumnsLength[$k] = $cnt - $count;
                    $k++; $column = array(); $cnt = $count;
                }
                $column = array_merge($column, $target[$key]);
            }
            $xColumns[$k] = $column;
            $xColumnsLength[$k] = $cnt - $count;
            // --- 3. integrate columns of one element ---
            $target = $xColumns; $xColumns = array(); $nextKey = -1;
            if ($max > 1 && count($target) > 1) {
                foreach($target as $key => $column) {
                    if ($key == $nextKey) continue;
                    if ($xColumnsLength[$key] == 1) {
                        // --- merge with next column ---
                        $nextKey = $key + 1;
                        if (isset($target[$nextKey]) && count($target[$nextKey])) {
                            $xColumns[] = array_merge($column, $target[$nextKey]);
                            continue;
                        }
                    }
                    $xColumns[] = $column;
                }
                $target = $xColumns;
            }
        }
        $_rtl = Mage::getStoreConfigFlag('custom_menu/general/rtl');
        if ($_rtl) {
            $target = array_reverse($target);
        }
        return $target;
    }

    private function _countChild($children, $level, &$count)
    {
        foreach ($children as $child) {
            if ($child->getIsActive()) {
                $count++; $activeChildren = $this->_getActiveChildren($child, $level);
                if (count($activeChildren) > 0) $this->_countChild($activeChildren, $level + 1, $count);
            }
        }
    }

    private static function _explodeArrayByColumnsHorisontal($list, $num)
    {
        if ($num <= 0) return array($list);
        $partition = array();
        $partition = array_pad($partition, $num, array());
        $i = 0;
        foreach ($list as $key => $value) {
            $partition[$i][$key] = $value;
            if (++$i == $num) $i = 0;
        }
        return $partition;
    }

    private static function _explodeArrayByColumnsVertical($list, $num)
    {
        if ($num <= 0) return array($list);
        $listlen = count($list);
        $partlen = floor($listlen / $num);
        $partrem = $listlen % $num;
        $partition = array();
        $mark = 0;
        for ($column = 0; $column < $num; $column++) {
            $incr = ($column < $partrem) ? $partlen + 1 : $partlen;
            $partition[$column] = array_slice($list, $mark, $incr);
            $mark += $incr;
        }
        return $partition;
    }
}
