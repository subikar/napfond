<?php
/**
* Strapp Changeproductposition Module
*
* @category    Strapp
* @package     Strapp_Changeproductposition
**/
?>
<?php
class Strapp_Changeproductposition_Adminhtml_ImportpositionController extends Mage_Adminhtml_Controller_Action
{
    public function preDispatch() 
    {
        parent::preDispatch();
    }
        
    public function indexAction() 
    {	
	$this->loadLayout();
	$this->_setActiveMenu('changeproductposition/import');	
	$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
	$this->_addContent($this->getLayout()->createBlock('changeproductposition/adminhtml_changeproductposition_edit'))->_addLeft($this->getLayout()->createBlock('changeproductposition/adminhtml_changeproductposition_edit_tabs'));
	$this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        $this->renderLayout(); 	
    }
    
    public function saveAction() 
    {	
	if(isset($_FILES['csvfile']['name']) && $_FILES['csvfile']['name'] != null) 
	{
	    try 
	    {	
		/* Starting upload */
		$uploader = new Varien_File_Uploader('csvfile');
		$uploader->setAllowedExtensions(array('csv'));
		$uploader->setAllowRenameFiles(false);
		$uploader->setFilesDispersion(false);
		$path = Mage::getBaseDir('var').DS.'strapp/changeproductposition'.DS ;
		$uploader->save($path, $_FILES['csvfile']['name'] );	
		Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('changeproductposition')->__('CSV file was successfully saved'));
	    } 
	    catch (Exception $e) 
	    {
		Mage::getSingleton('adminhtml/session')->addError(Mage::helper('changeproductposition')->__('Unable to find CSV file to save'));      
	    }
	    
	    Mage::getSingleton('adminhtml/session')->setFormData(false);
	    $this->_redirect('*/*/');
	    return;
	}
	Mage::getSingleton('adminhtml/session')->addError(Mage::helper('changeproductposition')->__('Unable to find CSV file to save'));
        $this->_redirect('*/*/');
    } 
    
    public function runAction()
    {
	$importFileName    =    $this->getRequest()->getParam('files');
	Mage::register('importFileName', $importFileName);
	$this->loadLayout();
	$this->renderLayout();
    }
        
    public function exportAction()
    {
	$cat_id = $this->getRequest()->getParam('categrory');	
	Mage::register('exportCategoryId', $cat_id);
	$file = Mage::getModel('changeproductposition/export_csv')->exportCategoryProductPosition();
	$this->_prepareDownloadResponse($file, file_get_contents(Mage::getBaseDir('var').'/export/'.$file));
    }
    
    public function processrecordAction()
    {	
	$cat_id = $this->getRequest()->getParam('cat_id');
	$product_id = $this->getRequest()->getParam('product_id');
	$position = $this->getRequest()->getParam('position');
	$connection     = $this->_getConnection('core_write');
	$sql = "UPDATE " . $this->_getTableName('catalog_category_product') . " ccp
                SET  ccp.position = ?
            WHERE  ccp.category_id = ?
            AND ccp.product_id = ?";
	try
	{	 
	  $connection->query($sql, array($position, $cat_id, $product_id));
	  $response['status'] = 'SUCCESS';	       
	}
	
	catch (Exception $e) 
        {
            Mage::log($e->getMessage());
            $response['status'] = 'ERROR';
            $response['message'] = $this->__('An error occurred while importing.');
        }
	$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
	return;
    }
    
    public function processindexAction()
    {
	try
	{
	  $process = Mage::getModel('index/process')->load(6);
	  $process->reindexAll();
	  $response['status'] = 'SUCCESS';
	}
	
	catch (Exception $e) 
        {
            Mage::log($e->getMessage());
            $response['status'] = 'ERROR';
            $response['message'] = $this->__('An error occurred while indexing.');
        }
	$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
	return;	
    }
    
    function _getConnection($type = 'core_read')
    {
	return Mage::getSingleton('core/resource')->getConnection($type);
    }

    function _getTableName($tableName)
    {
	return Mage::getSingleton('core/resource')->getTableName($tableName);
    } 
}
?>