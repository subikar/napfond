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
 * @package     Mage_CatalogSearch
 * @copyright   Copyright (c) 2014 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Catalog Search Controller
 */
class Mage_CatalogSearch_ResultController extends Mage_Core_Controller_Front_Action
{
    /**
     * Retrieve catalog session
     *
     * @return Mage_Catalog_Model_Session
     */
    protected function _getSession()
    {
        return Mage::getSingleton('catalog/session');
    }
    /**
     * Display search result
     */
    public function indexAction()
    {
        $query = Mage::helper('catalogsearch')->getQuery();
        /* @var $query Mage_CatalogSearch_Model_Query */
		
		if(!$query->getQueryText()){
			
		Mage::getSingleton('core/session')->addError('Please specify at least on word to search.'); 
		$this->_redirectReferer();
		
		}
	else{
		
		$search_query_text = $query->getQueryText();
		
		$read = Mage::getSingleton( 'core/resource' )->getConnection( 'core_read' ); 
		// To read from the database
		
	 $query2 = "SELECT * FROM a_search_matching_words where LOWER(match_word) = '".trim(strtolower($query->getQueryText()))."'";
			
			$results = $read->fetchAll($query2);
		if(count($results) == 0)
		$queryArr = explode(' ',$query->getQueryText());
		else
		$queryArr = array($query->getQueryText());
	
		$maximumWordCount = 0;
		$queryStringMatchKeyword = '';
		$queryStringMatchCategory = '';
		$queryStringMatchStyleName = '';
		$queryString = '';
		
		$prefix = '?';
		$prefixStringMatchCategory = '?';
		$prefixStringMatchStyleName = '?';
		
		$tempArr = array();
		
		
		$firstCategory = '';
		
		foreach($queryArr as $queryArrVal)
		{
			 $query = "SELECT * FROM a_search_matching_words where LOWER(match_word) = '".trim(strtolower($queryArrVal))."'";	 
			
			$results = $read->fetchAll($query);
			
			if(count($results) == 0){
			
				
			$query = "SELECT * FROM a_search_matching_words where match_word like '".trim($queryArrVal)."%'";	 
			$results = $read->fetchAll($query);
			}

			if(count($results) == 0){
			$query = "SELECT * FROM a_search_matching_words where match_word like '%".$queryArrVal."%'";	 
			$results = $read->fetchAll($query);
			}
			 
			foreach($results as $resultsVal)
			{
				
				if(!in_array($resultsVal,$tempArr))	
				$tempArr[] = $resultsVal;
				else
					continue;
				if($resultsVal["match_word_type"] == 'prdtitle'){					
					$queryStringMatchKeyword .= $prefix.'name[]='.$resultsVal['match_word'];
					$queryString .= $prefix.'name[]='.$resultsVal['match_word'];
					$prefix = '&';
				}
				elseif($resultsVal["match_word_type"] == 'category')
				{
					
				if($firstCategory == ''){
					$firstCategory = $resultsVal['match_word_db_id'];
					$queryStringMatchCategory .= $prefixStringMatchCategory.'cat='.$firstCategory.'&categories[]='.$resultsVal['match_word_db_id'].'&categories_inc='.$resultsVal['match_word_db_id'];
				}
				else{
					//$queryStringMatchCategory .= $prefixStringMatchCategory.'categories_inc[]='.$resultsVal['match_word_db_id'];
					$queryStringMatchCategory .= '_'.$resultsVal['match_word_db_id'];
				}	
					$queryString .= $prefix.'categories_inc[]='.$resultsVal['match_word_db_id'];
					$prefix = '&';
					$prefixStringMatchCategory = '&';
					
				}
				elseif($resultsVal["match_word_type"] == 'style')
				{
					$queryStringMatchStyleName .= $prefix.'case_style[]='.$resultsVal['match_word_db_id'];
					$queryString .= $prefix.'case_style[]='.$resultsVal['match_word_db_id'].'&case_style='.$resultsVal['match_word_db_id'];
					$prefix = '&';
					$prefixStringMatchStyleName = '&';
				}
				
			}
		}
		//echo Mage::getBaseUrl();
		
		if($queryStringMatchCategory!='')
		$redirectUrl = Mage::getStoreConfig(Mage_Core_Model_Url::XML_PATH_SECURE_URL).'catalogsearch/advanced/result'.$queryStringMatchCategory.$queryString.$prefix.'search_query_text='.$search_query_text;			
		else	
		$redirectUrl = Mage::getStoreConfig(Mage_Core_Model_Url::XML_PATH_SECURE_URL).'catalogsearch/advanced/result'.$queryString.$prefix.'search_query_text='.$search_query_text;
		header('location:'.$redirectUrl);
		exit;
        $query->setStoreId(Mage::app()->getStore()->getId());

        if ($query->getQueryText() != '') {
            if (Mage::helper('catalogsearch')->isMinQueryLength()) {
                $query->setId(0)
                    ->setIsActive(1)
                    ->setIsProcessed(1);
            }
            else {
                if ($query->getId()) {
                    $query->setPopularity($query->getPopularity()+1);
                }
                else {
                    $query->setPopularity(1);
                }

                if ($query->getRedirect()){
                    $query->save();
                    $this->getResponse()->setRedirect($query->getRedirect());
                    return;
                }
                else {
                    $query->prepare();
                }
            }

            Mage::helper('catalogsearch')->checkNotes();

            $this->loadLayout();
            $this->_initLayoutMessages('catalog/session');
            $this->_initLayoutMessages('checkout/session');
            $this->renderLayout();

            if (!Mage::helper('catalogsearch')->isMinQueryLength()) {
                $query->save();
            }
        }
        else {
            $this->_redirectReferer();
        }
    
	  }	
	
	}
}
