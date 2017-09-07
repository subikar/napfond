<?php
require_once 'app/Mage.php';
Mage::app(1);

$rootCatId = 3;

function getTreeCategories($parentId, $isChild){
    $allCats = Mage::getModel('catalog/category')->getCollection()
                ->addAttributeToSelect('*')
                ->addAttributeToFilter('is_active','1')
                ->addAttributeToFilter('parent_id',array('eq' => $parentId));

    $class = ($isChild) ? "sub-cat-list" : "cat-list";
    //$html .= '<ul class="'.$class.'">';
    //$children = Mage::getModel('catalog/category')->getCategories(7);
    foreach ($allCats as $category) 
    {
        //$html .= '<li>'.$category->getName()."";
        $subcats = $category->getChildren();
        if($subcats != ''){
            getTreeCategories($category->getId(), true);
        }
		else{
			if(!is_file('media/series/'.$category->getUrlKey().'/cover.png')){
			 echo $category->getUrlKey();
			 echo '<br/>';
			}		
		}
        //$html .= '</li>';
    }
    //$html .= '</ul>';
    //return $html;
}
$catlistHtml = getTreeCategories($rootCatId, false);

echo $catlistHtml;
?>