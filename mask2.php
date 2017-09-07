<?php /** * GoMage Product Designer Extension * * @category Extension * @copyright Copyright (c) 2013 GoMage (http://www.gomage.com) * @author GoMage * @license http://www.gomage.com/license-agreement/ Single domain license * @terms of use http://www.gomage.com/terms-of-use/ * @version Release: 1.0.0 * @since Available since Release 1.0.0 */ /** * Designer helper * * @category GoMage * @package GoMage_ProductDesigner * @subpackage Helper * @author Roman Bublik  */ 


class GoMage_ProductDesigner_Helper_Data extends Mage_Core_Helper_Abstract {

 const ADVANCED_NAVIGATION_MODULE_NAME = 'GoMage_Navigation'; 
 
 
 protected $_allowedProductTypes = array( Mage_Catalog_Model_Product_Type::TYPE_SIMPLE, Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE ); 
 
 protected $_productSettings; 
 protected $_productDesign = null; 
 protected $_editorConfig = null; 
 
 public function isEnabled() {
 return Mage::getStoreConfig('gomage_designer/general/enabled', Mage::app()->getStore()); 
 
 } 
 
 public function isNavigationEnabled() {

 return Mage::getStoreConfig('gomage_designer/navigation/enabled', Mage::app()->getStore()); 
 
 } 
 
 public function advancedNavigationEnabled() {

 $modules = (array) Mage::getConfig()->getNode('modules')->children(); 
 
 if (isset($modules[self::ADVANCED_NAVIGATION_MODULE_NAME])) {
 $module = $modules[self::ADVANCED_NAVIGATION_MODULE_NAME]; 
 
 return $module->is('active') && Mage::getStoreConfig('gomage_navigation/general/mode'); 
 
 } 
 
 return false; 
 
 } 
 
 
 public function isEnterpriseEdition() {

 $modules = (array) Mage::getConfig()->getNode('modules')->children(); return isset($modules['Enterprise_TargetRule']); } 
 
 public function getIsAnymoreVersion($major, $minor, $revision = 0) {

 $version_info = Mage::getVersionInfo(); if ($version_info['major'] > $major) { return true; } elseif ($version_info['major'] == $major) { if ($version_info['minor'] > $minor) { return true; } elseif ($version_info['minor'] == $minor) { if ($version_info['revision'] >= $revision) { return true; } } } return false; 
 
 } 
 
 /** * Return allowed product types * * @return array */ 
 
 public function getAllowedProductTypes() { return $this->_allowedProductTypes; } 
 
 public function hasColorAttribute() { $attributeCode = Mage::getStoreConfig('gomage_designer/navigation/color_attribute'); $attribute = Mage::getSingleton('eav/config') ->getAttribute(Mage_Catalog_Model_Product::ENTITY, $attributeCode); return $attribute->getId(); } 
 
 /** * Return Image Settings * * @param Mage_Catalog_Model_Product $product Product * @param int $imageId Image Id * @return boolean|array */ 
 
 public function getImageSettings(Mage_Catalog_Model_Product $product, $imageId) {

 $settings = $this->getSettings($product); if (isset($settings[$imageId])) { return $settings[$imageId]; } return false; 
 
 } 
 
 /** * Return Product images settings * * @param Mage_Catalog_Model_Product $product Product * @return array */ 
 
 public function getSettings(Mage_Catalog_Model_Product $product) {

 if (!$this->_productSettings) { $settings = array(); 
 
 $images = $product->getMediaGallery('images'); 
 
 foreach ($images as $image) { 
 
 $designArea = Mage::helper('core')->jsonDecode($image['design_area']);

 $imageId = $image['value_id']; if ($designArea && !empty($designArea)) {
 $imageUrl = $this->getDesignImageUrl($product, $image);
 $dimensions = $this->getImageDimensions($imageUrl); 
 $baseUrl = Mage::getBaseUrl('media'); 
 $baseDir = Mage::getBaseDir('media') . DS; 
 $designArea['path'] = str_replace($baseUrl, $baseDir, $imageUrl); 
 $designArea['dimensions'] = array( 'width' => $dimensions[0], 'height' => $dimensions[1] ); 
 $designArea['original_image'] = $this->getOriginalImage($product, $image); 
 
 if (isset($designArea['original_image']['url'])) {
 $designArea['original_image']['path'] = str_replace( $baseUrl, $baseDir, $designArea['original_image']['url'] );
 } 
 $settings[$imageId] = $designArea; 
 } 
 } 
 $this->_productSettings = $settings; 
 } 
 return $this->_productSettings; 
 
 } 
 
 public function jsonDecode($string) {

 $settings = Mage::helper('core')->jsonDecode($string);
 $tmp = array(); foreach ($settings as $i => $v) {
 $tmp[$i] = (array)$v; if (!isset($tmp[$i]['s'])) {
 $tmp[$i]['s'] = 1; 
 } 
 } 
 $settings = $tmp; return $settings; 
 
 } 
 
 public function getImageDimensions($imagePath) {

 $dirImg = Mage::getBaseDir().str_replace("/", DS, strstr($imagePath, '/media')); 
 if (file_exists($dirImg)) { 
 $imageObj = new Varien_Image($dirImg);
 $width = $imageObj->getOriginalWidth();
 $height = $imageObj->getOriginalHeight();
 return array($width, $height); 
 } 
 return array(0, 0); 
 } 
 
 public function prepareDesignerSessionId() {

 $customerSession = $this->_getCustomerSession();
 if(!$customerSession->getDesignerSessionId()) {
 $customerSession->setDesignerSessionId(sha1(rand(0,1000).microtime(true))); 
 } 
 
 } 
 
 public function getDesignerSessionId() {

 $sessionId = $this->_getCustomerSession()->getEncryptedSessionId(); return $sessionId; 
 
 } 
 
 protected function _getCustomerSession() {

 return Mage::getSingleton('customer/session'); 
 
 } 
 
 public function getDesignWidth() {

 return GoMage_ProductDesigner_Model_Design::DESIGN_SIZE_WIDTH; 
 
 } 
 
 public function getDesignHeight() {

 return GoMage_ProductDesigner_Model_Design::DESIGN_SIZE_HEIGHT; 
 
 } 
 
 /** * Return Image Url * * @param Mage_Catalog_Model_Product $product Product * @param Varien_Object $image Image * @param array $size Size * @return string */ 
 
 public function getDesignImageUrl(Mage_Catalog_Model_Product $product, $image, $size = array()) {

 if (empty($size)) {
 $imageWidth = Mage::helper('gomage_designer')->getDesignWidth();
 $imageHeight = Mage::helper('gomage_designer')->getDesignHeight();
 } 
 else {
 list($imageWidth, $imageHeight) = $size;
 } 
 $imageFile = is_object($image) ? $image->getFile() : $image['file']; 
 $url = Mage::helper('catalog/image')->init($product, 'base_image', $imageFile)->keepFrame(false) ->resize($imageWidth, $imageHeight)->__toString(); 
 return $url; 
 }

 public function getOriginalImage(Mage_Catalog_Model_Product $product, $image) {

 $imageFile = is_object($image) ? $image->getFile() : $image['file'];
 $imagePath = $product->getMediaConfig()->getMediaPath($imageFile);
 $minWidth = Mage::getStoreConfig('gomage_designer/general/zoom_size_width');
 $minHeight = Mage::getStoreConfig('gomage_designer/general/zoom_size_height');
 if (file_exists($imagePath)) {
 $imageObj = new Varien_Image($imagePath);
 $width = $imageObj->getOriginalWidth();
 $height = $imageObj->getOriginalHeight();
 $dimensions = array();
 if ($width < $minWidth && $height < $minHeight) {
 $dimensions[0] = $minWidth;
 $dimensions[1] = $minHeight;
 }
 else {
 $dimensions[0] = $width;
 $dimensions[1] = $height;
 } 
 return array( 'url' => $this->getDesignImageUrl($product, $image, $dimensions), 'dimensions' => $dimensions ); 
 } 
 return array(); 
 } 
 
 
 public function getProductSettingForEditor(Mage_Catalog_Model_Product $product = null) {

 if (!$this->_editorConfig) {
 if (is_null($product)) {
 $product = Mage::registry('product'); 
 } 
 $editorConfig = array( 'images' => array() );

 if (!$product->getId()) {
 return $editorConfig; 
 } 
 $images = $product->getMediaGallery('images');
 $colorAttributeCode = Mage::getStoreConfig('gomage_designer/navigation/color_attribute');
 $defaultColor = null; foreach ($images as $image) {
 $id = $image['value_id']; 
 $settings = Mage::helper('core')->jsonDecode($image['design_area']);
 if (!$settings || empty($settings)) {
 continue; 
 } 
 $imageUrl = $this->getDesignImageUrl($product, $image);
 $conf = $settings;
 $conf['id'] = $id;
 $conf['u'] = $imageUrl;
 $conf['d'] = $this->getImageDimensions($imageUrl);
 $conf['orig_image'] = $this->getOriginalImage($product, $image);
 if ($product->getTypeId() == Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE) {
 if ($image['color']) {
 if (is_null($defaultColor)) {
 $defaultColor = $image['color']; 
 } 
 if (!isset($editorConfig['images'][$image['color']])) {
 $editorConfig['images'][$image['color']] = array(); 
 } 
 $editorConfig['images'][$image['color']][$id] = $conf; 
 
 } 
 else {
 $defaultColor = $product->getData($colorAttributeCode) ?:'none_color';
 $editorConfig['images'][$defaultColor][$id] = $conf;
 } 
 } 
 else {
 $defaultColor = 'none_color'; 
 $editorConfig['images'][$defaultColor][$id] = $conf;
 } 
 $editorConfig['default_color'] = $defaultColor;
 $editorConfig['url'] = $product->getProductUrl();
 } 
 $this->_editorConfig = $editorConfig;
 } 
 return $this->_editorConfig;
 } 
 
 /** * Save product design images * * @return GoMage_ProductDesigner_Model_Design * @throws Exception */ 
 
 public function saveProductDesignedImages() {

 $product = $this->initializeProduct();
 $images = Mage::app()->getRequest()->getParam('images');
 if ($product->getId() && $images && !empty($images)) {
 $design = Mage::getModel('gomage_designer/design')->saveDesign($product, $this->_getRequest()->getParams());
 return $design; 
 } 
 elseif(!$product->getId()) {
 throw new Exception(Mage::helper('gomage_designer')->__('Product is not defined'));
 } 
 elseif(!$images || empty($images)) {
 throw new Exception(Mage::helper('gomage_designer')->__('Designed images are empty')); 
 } 
 
 } 
 
 /** * Initialize current product from request * * @return Mage_Catalog_Model_Product */ 
 
 public function initializeProduct() {

 $product = Mage::registry('product');
 if ($product) {
 return $product; 
 } 
 $request = Mage::app()->getRequest();
 $productId = $request->getParam("id", false);
 $product = Mage::getModel('catalog/product'); 
 if ($productId) { 
 $product->load($productId);
 } 
 Mage::register('product', $product); 
 return $product; 
 
 } 
 
 /** * Price calculation depending on product options * * @return array */ 
 
 public function getProductPriceConfig() {

 $config = array(); 
 $_request = Mage::getSingleton('tax/calculation')->getRateRequest(false, false, false); /* @var $product Mage_Catalog_Model_Product */ 
 $product = $this->initializeProduct(); 
 $_request->setProductClassId($product->getTaxClassId());
 $defaultTax = Mage::getSingleton('tax/calculation')->getRate($_request);
 $_request = Mage::getSingleton('tax/calculation')->getRateRequest();
 $_request->setProductClassId($product->getTaxClassId());
 $currentTax = Mage::getSingleton('tax/calculation')->getRate($_request);
 $_regularPrice = $product->getPrice();
 $_finalPrice = $product->getFinalPrice();
 $_priceInclTax = Mage::helper('tax')->getPrice($product, $_finalPrice, true);
 $_priceExclTax = Mage::helper('tax')->getPrice($product, $_finalPrice);
 $_tierPrices = array(); 
 $_tierPricesInclTax = array();
 foreach ($product->getTierPrice() as $tierPrice) {
 $_tierPrices[] = Mage::helper('core')->currency($tierPrice['website_price'], false, false);
 $_tierPricesInclTax[] = Mage::helper('core')->currency( Mage::helper('tax')->getPrice($product, (int)$tierPrice['website_price'], true), false, false); 
 } 
 $config = array( 'productId' => $product->getId(), 
 'priceFormat' => Mage::app()->getLocale()->getJsPriceFormat(),
 'includeTax' => Mage::helper('tax')->priceIncludesTax() ? 'true' : 'false',
 'showIncludeTax' => Mage::helper('tax')->displayPriceIncludingTax(),
 'showBothPrices' => Mage::helper('tax')->displayBothPrices(),
 'productPrice' => Mage::helper('core')->currency($_finalPrice, false, false),
 'productOldPrice' => Mage::helper('core')->currency($_regularPrice, false, false),
 'priceInclTax' => Mage::helper('core')->currency($_priceInclTax, false, false), 'priceExclTax' => Mage::helper('core')->currency($_priceExclTax, false, false), /** * @var skipCalculate * @deprecated after 1.5.1.0 */ 
 'skipCalculate' => ($_priceExclTax != $_priceInclTax ? 0 : 1),
 'defaultTax' => $defaultTax,
 'currentTax' => $currentTax,
 'idSuffix' => '_clone',
 'oldPlusDisposition' => 0,
 'plusDisposition' => 0,
 'plusDispositionTax' => 0,
 'oldMinusDisposition' => 0,
 'minusDisposition' => 0,
 'tierPrices' => $_tierPrices,
 'tierPricesInclTax' => $_tierPricesInclTax, 
 );

 $responseObject = new Varien_Object();
 Mage::dispatchEvent('catalog_product_view_config', array('response_object'=>$responseObject));
 if (is_array($responseObject->getAdditionalOptions())) {
 foreach ($responseObject->getAdditionalOptions() as $option=>$value) {
 $config[$option] = $value; 
 } 
 } 
 return $config; 
 
 } 
 
 public function formatFileName($file) {

 $formatResult = Mage::helper('catalog/product_url')->format($file);
 return preg_replace('#[\s]+#i', '-', $formatResult); 
 
 } 
 
 public function canApplyMsrp($product, $visibility = null, $checkAssociatedItems = true) {

 $version = Mage::getVersionInfo(); 
 if (($version['major'] === '1') && ((int) $version['minor'] <= 5)) {
 return false; 
 } 
 elseif (method_exists(Mage::helper('catalog'), 'canApplyMsrp')) {
 return Mage::helper('catalog')->canApplyMsrp($product, $visibility, $checkAssociatedItems); 
 } 
 return false; 
 
 } 
 
 /** * Retrieve url for adding product to wishlist * * @param int $itemId * * @return string */ 
 
 public function getMoveFromCartUrl($itemId) {

 $version = Mage::getVersionInfo();
 if (($version['major'] === '1') && ((int) $version['minor'] <= 5)) {
 return false; 
 } 
 else if (method_exists(Mage::helper('wishlist'), 'getMoveFromCartUrl')) {
 return Mage::helper('wishlist')->getMoveFromCartUrl($itemId); 
 } 
 return false; 
 
 } 
 
 /** * Get JSON encoded configuration array which can be used for JS dynamic * * @return string */ 
 
 public function getProductPriceConfigJson() {

 return Mage::helper('core')->jsonEncode($this->getProductPriceConfig()); 
 
 } 
 
 /** * Return product design from request * * @param Mage_Catalog_Model_Product $product Product * @return bool|Mage_Core_Model_Abstract */ 
 
 public function getProductDesign($product) {

 if (is_null($this->_productDesign)) {
 $designId = (int) Mage::app()->getRequest()->getParam('design_id', false);

 if ($designId) {
 $design = Mage::getModel('gomage_designer/design')->load($designId);
 if ($design->getId() && $design->getProductId() == $product->getId())
 { 
 $this->_productDesign = $design;
 } 
 else {
 $this->_productDesign = false; 
 } 
 } 
 } 
 return $this->_productDesign;
 } 
 
 public function getAllStoreDomains(){

 $domains = array(); 
 foreach (Mage::app()->getWebsites() as $website) {
 $url = $website->getConfig('web/unsecure/base_url');
 if($domain = trim(preg_replace('/^.*?\\/\\/(.*)?\\//', '$1', $url)))
 { 
 $domains[] = $domain;
 } 
 $url = $website->getConfig('web/secure/base_url');
 if ($domain = trim(preg_replace('/^.*?\\/\\/(.*)?\\//', '$1', $url))) {
 $domains[] = $domain; 
 } 
 } 
 return array_unique($domains); 
 
 } 
 
 public function getWindowCssPath() {

 if(!$this->getIsAnymoreVersion(1, 7)) {
 return 'prototype/windows/themes/magento.css'; 
 } else {
 return 'lib/prototype/windows/themes/magento.css';
 } 
 
 } 
 
 public function getCssType() {

 if(!$this->getIsAnymoreVersion(1, 7)) {
 return 'js_css'; 
 } else {
 return 'skin_css'; 
 } 
 
 } 
 
 public function getAvailableWebsites() {

 return $this->_w(); 
 
 } 
 
 protected function _w() {

 if(!Mage::getStoreConfig('gomage_activation/designer/installed') || (intval(Mage::getStoreConfig('gomage_activation/designer/count')) > 10)) {
 return array(); 
 } 
 $time_to_update = 60*60*24*15;
 $r = Mage::getStoreConfig('gomage_activation/designer/ar');
 $t = Mage::getStoreConfig('gomage_activation/designer/time');
 $s = Mage::getStoreConfig('gomage_activation/designer/websites'); 
 $last_check = str_replace($r, '', Mage::helper('core')->decrypt($t));
 $allsites = explode(',', str_replace($r, '', Mage::helper('core')->decrypt($s)));
 $allsites = array_diff($allsites, array(""));
 if(($last_check+$time_to_update) < time()){
 
 $this->a(Mage::getStoreConfig('gomage_activation/designer/key'), intval(Mage::getStoreConfig('gomage_activation/designer/count')), implode(',', $allsites)); 
 
 } 
 return $allsites; 
 
 } 
 
 public function a($k, $c = 0, $s = '') {

 $ch = curl_init(); 
 curl_setopt($ch, CURLOPT_URL, sprintf('https://www.gomage.com/index.php/gomage_downloadable/key/check')); 
 curl_setopt($ch, CURLOPT_POST, true); 
 curl_setopt($ch, CURLOPT_POSTFIELDS, 'key='.urlencode($k).'&sku=product-designer&domains='.urlencode(implode(',', $this->getAllStoreDomains())).'&ver='.urlencode('1.0'));
 curl_setopt($ch, CURLOPT_TIMEOUT, 30);
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
 curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
 $content = curl_exec($ch); 
 $r = Zend_Json::decode($content);
 $e = Mage::helper('core');
 if(empty($r)){
 $value1 = Mage::getStoreConfig('gomage_activation/designer/ar');
 $groups = array( 'designer'=>array( 'fields'=>array( 'ar'=>array( 'value'=>$value1 ), 'websites'=>array( 'value'=>(string)Mage::getStoreConfig('gomage_activation/designer/websites') ), 'time'=>array( 'value'=>(string)$e->encrypt($value1.(time()-(60*60*24*15-1800)).$value1) ), 'count'=>array( 'value'=>$c+1) ) ) ); 
 
 Mage::getModel('adminhtml/config_data') ->setSection('gomage_activation') ->setGroups($groups) ->save(); 
 
 Mage::getConfig()->reinit();
 Mage::app()->reinitStores();
 return; 
 } 
 $value1 = '';
 $value2 = '';
 if(isset($r['d']) && isset($r['c'])){
 $value1 = $e->encrypt(base64_encode(Zend_Json::encode($r)));
 if (!$s)
 $s = Mage::getStoreConfig('gomage_activation/designer/websites');
 $s = array_slice(explode(',', $s), 0, $r['c']);
 $value2 = $e->encrypt($value1.implode(',', $s).$value1); 
 } 
 $groups = array( 'designer'=>array( 'fields'=>array( 'ar'=>array( 'value'=>$value1 ), 'websites'=>array( 'value'=>(string)$value2 ), 'time'=>array( 'value'=>(string)$e->encrypt($value1.time().$value1) ), 'installed'=>array( 'value'=>1 ), 'count'=>array( 'value'=>0) ) ) ); 
 Mage::getModel('adminhtml/config_data') ->setSection('gomage_activation') ->setGroups($groups) ->save();
 Mage::getConfig()->reinit(); 
 Mage::app()->reinitStores(); 
 
 } 
 
 
 public function ga() {

 return Zend_Json::decode(base64_decode(Mage::helper('core')->decrypt(Mage::getStoreConfig('gomage_activation/designer/ar')))); } 
 
 public function notify() {

 $frequency = intval(Mage::app()->loadCache('gomage_notifications_frequency'));
 if (!$frequency){
 $frequency = 24; 
 } 
 $last_update = intval(Mage::app()->loadCache('gomage_notifications_last_update'));

 if (($frequency * 60 * 60 + $last_update) > time()) {
 return false; 
 } 
 $timestamp = $last_update; 
 if (!$timestamp){ 
 $timestamp = time(); 
 } 
 try{ $ch = curl_init();
 curl_setopt($ch, CURLOPT_URL, sprintf('https://www.gomage.com/index.php/gomage_notification/index/data'));
 curl_setopt($ch, CURLOPT_POST, true);
 curl_setopt($ch, CURLOPT_POSTFIELDS, 'sku=product-designer×tamp='.$timestamp.'&ver='.urlencode('1.0'));
 curl_setopt($ch, CURLOPT_TIMEOUT, 30);
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
 curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
 $content = curl_exec($ch);
 $result = Zend_Json::decode($content);
 if ($result && isset($result['frequency']) && ($result['frequency'] != $frequency)){

 Mage::app()->saveCache($result['frequency'], 'gomage_notifications_frequency');

 } 
 
 if ($result && isset($result['data'])){

 if (!empty($result['data'])){
 Mage::getModel('adminnotification/inbox')->parse($result['data']);
 } 
 } 
 } 
 catch (Exception $e){
 
 } 
 
 Mage::app()->saveCache(time(), 'gomage_notifications_last_update'); 
 
 } 
 
 }