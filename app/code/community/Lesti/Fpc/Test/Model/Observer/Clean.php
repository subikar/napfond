<?php
/**
 * Lesti_Fpc (http:gordonlesti.com/lestifpc)
 *
 * PHP version 5
 *
 * @link      https://github.com/GordonLesti/Lesti_Fpc
 * @package   Lesti_Fpc
 * @author    Gordon Lesti <info@gordonlesti.com>
 * @copyright Copyright (c) 2013-2014 Gordon Lesti (http://gordonlesti.com)
 * @license   http://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

/**
 * Class Lesti_Fpc_Test_Model_Observer_Clean
 */
class Lesti_Fpc_Test_Model_Observer_Clean extends Lesti_Fpc_Test_TestCase
{
    /**
     * @var Lesti_Fpc_Model_Observer_Clean
     */
    protected $_cleanObserver;

    protected function setUp()
    {
        parent::setUp();
        $this->_cleanObserver = Mage::getSingleton('fpc/observer_clean');
    }

    protected function tearDown()
    {
        parent::tearDown();
        Mage::unregister('_singleton/fpc/observer_clean');
    }

    /**
     * @test
     */
    public function testCoreCleanCache()
    {
        $data = new \Lesti_Fpc_Model_Fpc_CacheItem('fpc_old_data', time(), 'text/html');
        $this->_fpc->save($data, 'fpc_old_id', array('fpc'), 1);
        sleep(2);
        $this->_cleanObserver->coreCleanCache();
        $this->assertFalse($this->_fpc->remove('fpc_old_id'));
    }

    /**
     * @test
     */
    public function testAdminhtmlCacheFlushAll()
    {
        $data = new \Lesti_Fpc_Model_Fpc_CacheItem('fpc_old_data', time(), 'text/html');
        $this->_fpc->save($data, 'fpc_id');
        Mage::dispatchEvent('adminhtml_cache_flush_all');
        $this->assertFalse($this->_fpc->remove('fpc_id'));
    }

    /**
     * @test
     */
    public function testControllerActionPredispatchAdminhtmlCacheMassRefresh()
    {
        $data = new \Lesti_Fpc_Model_Fpc_CacheItem('test_data', time(), 'text/html');
        $this->_fpc->save($data, 'test_id');
        Mage::app()->getRequest()->setParam('types', array('core'));
        $this->_cleanObserver->
            controllerActionPredispatchAdminhtmlCacheMassRefresh();
        $this->assertEquals($data, $this->_fpc->load('test_id'));

        $this->_fpc->clean();
        $this->_fpc->save($data, 'test_id');
        Mage::app()->getRequest()->setParam(
            'types',
            array('core', Lesti_Fpc_Model_Observer_Clean::CACHE_TYPE)
        );
        $this->_cleanObserver->
            controllerActionPredispatchAdminhtmlCacheMassRefresh();
        $this->assertFalse($this->_fpc->load('test_id'));

        $this->_fpc->clean();
        $this->_fpc->save($data, 'test_id');
        Mage::app()->getRequest()->setParam(
            'types',
            array('core', Lesti_Fpc_Model_Observer_Clean::CACHE_TYPE)
        );
        Mage::app()->getCacheInstance()->banUse('fpc');
        $this->_cleanObserver->
            controllerActionPredispatchAdminhtmlCacheMassRefresh();
        $this->assertEquals($data, $this->_fpc->load('test_id'));
    }
}
