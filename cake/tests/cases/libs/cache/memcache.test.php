<?php
/**
 * MemcacheEngineTest file.
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) Tests <http://book.cakephp.org/1.3/en/The-Manual/Common-Tasks-With-CakePHP/Testing.html>
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 *  Licensed under The Open Group Test Suite License
 *  Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * @see          http://book.cakephp.org/1.3/en/The-Manual/Common-Tasks-With-CakePHP/Testing.html CakePHP(tm) Tests
 * @since         CakePHP(tm) v 1.2.0.5434
 *
 * @license       http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */
if (!class_exists('Cache')) {
    require LIBS.'cache.php';
}
App::import('Core', 'cache/Memcache');

class TestMemcacheEngine extends MemcacheEngine
{
    /**
     * public accessor to _parseServerString.
     *
     * @param string $server
     *
     * @return array
     */
    public function parseServerString($server)
    {
        return $this->_parseServerString($server);
    }

    public function setMemcache(&$memcache)
    {
        $this->__Memcache = $memcache;
    }
}

Mock::generate('Memcache', 'MemcacheMockMemcache');

/**
 * MemcacheEngineTest class.
 */
class MemcacheEngineTest extends CakeTestCase
{
    /**
     * skip method.
     */
    public function skip()
    {
        $skip = true;
        if (class_exists('Memcache')) {
            $skip = false;
        }
        $this->skipIf($skip, '%s Memcache is not installed or configured properly.');
    }

    /**
     * setUp method.
     */
    public function setUp()
    {
        $this->_cacheDisable = Configure::read('Cache.disable');
        Configure::write('Cache.disable', false);
        Cache::config('memcache', [
            'engine' => 'Memcache',
            'prefix' => 'cake_',
            'duration' => 3600,
        ]);
    }

    /**
     * tearDown method.
     */
    public function tearDown()
    {
        Configure::write('Cache.disable', $this->_cacheDisable);
        Cache::drop('memcache');
        Cache::config('default');
    }

    /**
     * testSettings method.
     */
    public function testSettings()
    {
        $settings = Cache::settings();
        unset($settings['serialize'], $settings['path']);
        $expecting = [
            'prefix' => 'cake_',
            'duration' => 3600,
            'probability' => 100,
            'servers' => ['127.0.0.1'],
            'compress' => false,
            'engine' => 'Memcache',
            'persistent' => true,
        ];
        $this->assertEqual($settings, $expecting);
    }

    /**
     * testSettings method.
     */
    public function testMultipleServers()
    {
        $servers = ['127.0.0.1:11211', '127.0.0.1:11222'];
        $available = true;
        $Memcache = new Memcache();

        foreach ($servers as $server) {
            list($host, $port) = explode(':', $server);
            if (!@$Memcache->connect($host, $port)) {
                $available = false;
            }
        }

        if ($this->skipIf(!$available, '%s Need memcache servers at '.implode(', ', $servers).' to run this test')) {
            return;
        }
        $Memcache = new MemcacheEngine();
        $Memcache->init(['engine' => 'Memcache', 'servers' => $servers]);

        $servers = array_keys($Memcache->__Memcache->getExtendedStats());
        $settings = $Memcache->settings();
        $this->assertEqual($servers, $settings['servers']);
        Cache::drop('dual_server');
    }

    /**
     * testConnect method.
     */
    public function testConnect()
    {
        $Memcache = new MemcacheEngine();
        $Memcache->init(Cache::settings('memcache'));
        $result = $Memcache->connect('127.0.0.1');
        $this->assertTrue($result);
    }

    /**
     * test connecting to an ipv6 server.
     */
    public function testConnectIpv6()
    {
        $Memcache = new MemcacheEngine();
        $result = $Memcache->init([
            'prefix' => 'cake_',
            'duration' => 200,
            'engine' => 'Memcache',
            'servers' => [
                '[::1]:11211',
            ],
        ]);
        $this->assertTrue($result);
    }

    /**
     * test non latin domains.
     */
    public function testParseServerStringNonLatin()
    {
        $Memcache = new TestMemcacheEngine();
        $result = $Memcache->parseServerString('schülervz.net:13211');
        $this->assertEqual($result, ['schülervz.net', '13211']);

        $result = $Memcache->parseServerString('sülül:1111');
        $this->assertEqual($result, ['sülül', '1111']);
    }

    /**
     * test unix sockets.
     */
    public function testParseServerStringUnix()
    {
        $Memcache = new TestMemcacheEngine();
        $result = $Memcache->parseServerString('unix:///path/to/memcached.sock');
        $this->assertEqual($result, ['unix:///path/to/memcached.sock', 0]);
    }

    /**
     * testReadAndWriteCache method.
     */
    public function testReadAndWriteCache()
    {
        Cache::set(['duration' => 1]);

        $result = Cache::read('test');
        $expecting = '';
        $this->assertEqual($result, $expecting);

        $data = 'this is a test of the emergency broadcasting system';
        $result = Cache::write('test', $data);
        $this->assertTrue($result);

        $result = Cache::read('test');
        $expecting = $data;
        $this->assertEqual($result, $expecting);

        Cache::delete('test');
    }

    /**
     * testExpiry method.
     */
    public function testExpiry()
    {
        Cache::set(['duration' => 1]);

        $result = Cache::read('test');
        $this->assertFalse($result);

        $data = 'this is a test of the emergency broadcasting system';
        $result = Cache::write('other_test', $data);
        $this->assertTrue($result);

        sleep(2);
        $result = Cache::read('other_test');
        $this->assertFalse($result);

        Cache::set(['duration' => '+1 second']);

        $data = 'this is a test of the emergency broadcasting system';
        $result = Cache::write('other_test', $data);
        $this->assertTrue($result);

        sleep(2);
        $result = Cache::read('other_test');
        $this->assertFalse($result);

        Cache::config('memcache', ['duration' => '+1 second']);
        sleep(2);

        $result = Cache::read('other_test');
        $this->assertFalse($result);

        Cache::config('memcache', ['duration' => '+29 days']);
        $data = 'this is a test of the emergency broadcasting system';
        $result = Cache::write('long_expiry_test', $data);
        $this->assertTrue($result);

        sleep(2);
        $result = Cache::read('long_expiry_test');
        $expecting = $data;
        $this->assertEqual($result, $expecting);

        Cache::config('memcache', ['duration' => 3600]);
    }

    /**
     * testDeleteCache method.
     */
    public function testDeleteCache()
    {
        $data = 'this is a test of the emergency broadcasting system';
        $result = Cache::write('delete_test', $data);
        $this->assertTrue($result);

        $result = Cache::delete('delete_test');
        $this->assertTrue($result);
    }

    /**
     * testDecrement method.
     */
    public function testDecrement()
    {
        $result = Cache::write('test_decrement', 5);
        $this->assertTrue($result);

        $result = Cache::decrement('test_decrement');
        $this->assertEqual(4, $result);

        $result = Cache::read('test_decrement');
        $this->assertEqual(4, $result);

        $result = Cache::decrement('test_decrement', 2);
        $this->assertEqual(2, $result);

        $result = Cache::read('test_decrement');
        $this->assertEqual(2, $result);
    }

    /**
     * testIncrement method.
     */
    public function testIncrement()
    {
        $result = Cache::write('test_increment', 5);
        $this->assertTrue($result);

        $result = Cache::increment('test_increment');
        $this->assertEqual(6, $result);

        $result = Cache::read('test_increment');
        $this->assertEqual(6, $result);

        $result = Cache::increment('test_increment', 2);
        $this->assertEqual(8, $result);

        $result = Cache::read('test_increment');
        $this->assertEqual(8, $result);
    }

    /**
     * test that configurations don't conflict, when a file engine is declared after a memcache one.
     */
    public function testConfigurationConflict()
    {
        Cache::config('long_memcache', [
          'engine' => 'Memcache',
          'duration' => '+2 seconds',
          'servers' => ['127.0.0.1:11211'],
        ]);
        Cache::config('short_memcache', [
          'engine' => 'Memcache',
          'duration' => '+1 seconds',
          'servers' => ['127.0.0.1:11211'],
        ]);
        Cache::config('some_file', ['engine' => 'File']);

        $this->assertTrue(Cache::write('duration_test', 'yay', 'long_memcache'));
        $this->assertTrue(Cache::write('short_duration_test', 'boo', 'short_memcache'));

        $this->assertEqual(Cache::read('duration_test', 'long_memcache'), 'yay', 'Value was not read %s');
        $this->assertEqual(Cache::read('short_duration_test', 'short_memcache'), 'boo', 'Value was not read %s');

        sleep(1);
        $this->assertEqual(Cache::read('duration_test', 'long_memcache'), 'yay', 'Value was not read %s');

        sleep(2);
        $this->assertFalse(Cache::read('short_duration_test', 'short_memcache'), 'Cache was not invalidated %s');
        $this->assertFalse(Cache::read('duration_test', 'long_memcache'), 'Value did not expire %s');

        Cache::delete('duration_test', 'long_memcache');
        Cache::delete('short_duration_test', 'short_memcache');
    }

    /**
     * test that a 0 duration can succesfully write.
     */
    public function testZeroDuration()
    {
        Cache::config('memcache', ['duration' => 0]);
        $result = Cache::write('test_key', 'written!', 'memcache');

        $this->assertTrue($result, 'Could not write with duration 0');
        $result = Cache::read('test_key', 'memcache');
        $this->assertEqual($result, 'written!');
    }

    /**
     * test that durations greater than 30 days never expire.
     */
    public function testLongDurationEqualToZero()
    {
        $memcache = new TestMemcacheEngine();
        $memcache->settings['compress'] = false;

        $mock = new MemcacheMockMemcache();
        $memcache->setMemcache($mock);
        $mock->expectAt(0, 'set', ['key', 'value', false, 0]);

        $value = 'value';
        $memcache->write('key', $value, 50 * DAY);
    }
}
