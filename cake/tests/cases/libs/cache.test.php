<?php
/**
 * CacheTest file.
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
 * @since         CakePHP(tm) v 1.2.0.5432
 *
 * @license       http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */
if (!class_exists('Cache')) {
    require LIBS.'cache.php';
}

/**
 * CacheTest class.
 */
class CacheTest extends CakeTestCase
{
    /**
     * setUp method.
     */
    public function setUp()
    {
        $this->_cacheDisable = Configure::read('Cache.disable');
        Configure::write('Cache.disable', false);

        $this->_defaultCacheConfig = Cache::config('default');
        Cache::config('default', ['engine' => 'File', 'path' => TMP.'tests']);
    }

    /**
     * tearDown method.
     */
    public function tearDown()
    {
        Configure::write('Cache.disable', $this->_cacheDisable);
        Cache::config('default', $this->_defaultCacheConfig['settings']);
    }

    /**
     * testConfig method.
     */
    public function testConfig()
    {
        $settings = ['engine' => 'File', 'path' => TMP.'tests', 'prefix' => 'cake_test_'];
        $results = Cache::config('new', $settings);
        $this->assertEqual($results, Cache::config('new'));
        $this->assertTrue(isset($results['engine']));
        $this->assertTrue(isset($results['settings']));
    }

    /**
     * Check that no fatal errors are issued doing normal things when Cache.disable is true.
     */
    public function testNonFatalErrorsWithCachedisable()
    {
        Configure::write('Cache.disable', true);
        Cache::config('test', ['engine' => 'File', 'path' => TMP, 'prefix' => 'error_test_']);

        Cache::write('no_save', 'Noooo!', 'test');
        Cache::read('no_save', 'test');
        Cache::delete('no_save', 'test');
        Cache::set('duration', '+10 minutes');

        Configure::write('Cache.disable', false);
    }

    /**
     * test configuring CacheEngines in App/libs.
     */
    public function testConfigWithLibAndPluginEngines()
    {
        App::build([
            'libs' => [TEST_CAKE_CORE_INCLUDE_PATH.'tests'.DS.'test_app'.DS.'libs'.DS],
            'plugins' => [TEST_CAKE_CORE_INCLUDE_PATH.'tests'.DS.'test_app'.DS.'plugins'.DS],
        ], true);

        $settings = ['engine' => 'TestAppCache', 'path' => TMP, 'prefix' => 'cake_test_'];
        $result = Cache::config('libEngine', $settings);
        $this->assertEqual($result, Cache::config('libEngine'));

        $settings = ['engine' => 'TestPlugin.TestPluginCache', 'path' => TMP, 'prefix' => 'cake_test_'];
        $result = Cache::config('pluginLibEngine', $settings);
        $this->assertEqual($result, Cache::config('pluginLibEngine'));

        Cache::drop('libEngine');
        Cache::drop('pluginLibEngine');

        App::build();
    }

    /**
     * testInvalidConfig method.
     *
     * Test that the cache class doesn't cause fatal errors with a partial path
     */
    public function testInvaidConfig()
    {
        $this->expectError();
        Cache::config('invalid', [
            'engine' => 'File',
            'duration' => '+1 year',
            'prefix' => 'testing_invalid_',
            'path' => 'data/',
            'serialize' => true,
            'random' => 'wii',
        ]);
        $read = Cache::read('Test', 'invalid');
        $this->assertEqual($read, null);
    }

    /**
     * testConfigChange method.
     */
    public function testConfigChange()
    {
        $_cacheConfigSessions = Cache::config('sessions');
        $_cacheConfigTests = Cache::config('tests');

        $result = Cache::config('sessions', ['engine' => 'File', 'path' => TMP.'sessions']);
        $this->assertEqual($result['settings'], Cache::settings('sessions'));

        $result = Cache::config('tests', ['engine' => 'File', 'path' => TMP.'tests']);
        $this->assertEqual($result['settings'], Cache::settings('tests'));

        Cache::config('sessions', $_cacheConfigSessions['settings']);
        Cache::config('tests', $_cacheConfigTests['settings']);
    }

    /**
     * test that calling config() sets the 'default' configuration up.
     */
    public function testConfigSettingDefaultConfigKey()
    {
        Cache::config('test_name', ['engine' => 'File', 'prefix' => 'test_name_']);

        Cache::config('test_name');
        Cache::write('value_one', 'I am cached');
        $result = Cache::read('value_one');
        $this->assertEqual($result, 'I am cached');

        Cache::config('default');
        $result = Cache::read('value_one');
        $this->assertEqual($result, null);

        Cache::write('value_one', 'I am in default config!');
        $result = Cache::read('value_one');
        $this->assertEqual($result, 'I am in default config!');

        Cache::config('test_name');
        $result = Cache::read('value_one');
        $this->assertEqual($result, 'I am cached');

        Cache::delete('value_one');
        Cache::config('default');
        Cache::delete('value_one');
    }

    /**
     * testWritingWithConfig method.
     */
    public function testWritingWithConfig()
    {
        $_cacheConfigSessions = Cache::config('sessions');

        Cache::write('test_somthing', 'this is the test data', 'tests');

        $expected = [
            'path' => TMP.'sessions',
            'prefix' => 'cake_',
            'lock' => false,
            'serialize' => true,
            'duration' => 3600,
            'probability' => 100,
            'engine' => 'File',
            'isWindows' => DIRECTORY_SEPARATOR == '\\',
        ];
        $this->assertEqual($expected, Cache::settings('sessions'));

        Cache::config('sessions', $_cacheConfigSessions['settings']);
    }

    /**
     * test that configured returns an array of the currently configured cache
     * settings.
     */
    public function testConfigured()
    {
        $result = Cache::configured();
        $this->assertTrue(in_array('_cake_core_', $result));
        $this->assertTrue(in_array('default', $result));
    }

    /**
     * testInitSettings method.
     */
    public function testInitSettings()
    {
        Cache::config('for_test', ['engine' => 'File', 'path' => TMP.'tests']);

        $settings = Cache::settings();
        $expecting = [
            'engine' => 'File',
            'duration' => 3600,
            'probability' => 100,
            'path' => TMP.'tests',
            'prefix' => 'cake_',
            'lock' => false,
            'serialize' => true,
            'isWindows' => DIRECTORY_SEPARATOR == '\\',
        ];
        $this->assertEqual($settings, $expecting);
    }

    /**
     * test that drop removes cache configs, and that further attempts to use that config
     * do not work.
     */
    public function testDrop()
    {
        App::build([
            'libs' => [TEST_CAKE_CORE_INCLUDE_PATH.'tests'.DS.'test_app'.DS.'libs'.DS],
            'plugins' => [TEST_CAKE_CORE_INCLUDE_PATH.'tests'.DS.'test_app'.DS.'plugins'.DS],
        ], true);

        $result = Cache::drop('some_config_that_does_not_exist');
        $this->assertFalse($result);

        $_testsConfig = Cache::config('tests');
        $result = Cache::drop('tests');
        $this->assertTrue($result);

        Cache::config('unconfigTest', [
            'engine' => 'TestAppCache',
        ]);
        $this->assertTrue(Cache::isInitialized('unconfigTest'));

        $this->assertTrue(Cache::drop('unconfigTest'));
        $this->assertFalse(Cache::isInitialized('TestAppCache'));

        Cache::config('tests', $_testsConfig);
        App::build();
    }

    /**
     * testWriteEmptyValues method.
     */
    public function testWriteEmptyValues()
    {
        Cache::write('App.falseTest', false);
        $this->assertIdentical(Cache::read('App.falseTest'), false);

        Cache::write('App.trueTest', true);
        $this->assertIdentical(Cache::read('App.trueTest'), true);

        Cache::write('App.nullTest', null);
        $this->assertIdentical(Cache::read('App.nullTest'), null);

        Cache::write('App.zeroTest', 0);
        $this->assertIdentical(Cache::read('App.zeroTest'), 0);

        Cache::write('App.zeroTest2', '0');
        $this->assertIdentical(Cache::read('App.zeroTest2'), '0');
    }

    /**
     * testCacheDisable method.
     *
     * Check that the "Cache.disable" configuration and a change to it
     * (even after a cache config has been setup) is taken into account.
     *
     * @see https://trac.cakephp.org/ticket/6236
     */
    public function testCacheDisable()
    {
        Configure::write('Cache.disable', false);
        Cache::config('test_cache_disable_1', ['engine' => 'File', 'path' => TMP.'tests']);

        $this->assertTrue(Cache::write('key_1', 'hello'));
        $this->assertIdentical(Cache::read('key_1'), 'hello');

        Configure::write('Cache.disable', true);

        $this->assertFalse(Cache::write('key_2', 'hello'));
        $this->assertFalse(Cache::read('key_2'));

        Configure::write('Cache.disable', false);

        $this->assertTrue(Cache::write('key_3', 'hello'));
        $this->assertIdentical(Cache::read('key_3'), 'hello');

        Configure::write('Cache.disable', true);
        Cache::config('test_cache_disable_2', ['engine' => 'File', 'path' => TMP.'tests']);

        $this->assertFalse(Cache::write('key_4', 'hello'));
        $this->assertFalse(Cache::read('key_4'));

        Configure::write('Cache.disable', false);

        $this->assertTrue(Cache::write('key_5', 'hello'));
        $this->assertIdentical(Cache::read('key_5'), 'hello');

        Configure::write('Cache.disable', true);

        $this->assertFalse(Cache::write('key_6', 'hello'));
        $this->assertFalse(Cache::read('key_6'));
    }

    /**
     * testSet method.
     */
    public function testSet()
    {
        $_cacheSet = Cache::set();

        Cache::set(['duration' => '+1 year']);
        $data = Cache::read('test_cache');
        $this->assertFalse($data);

        $data = 'this is just a simple test of the cache system';
        $write = Cache::write('test_cache', $data);
        $this->assertTrue($write);

        Cache::set(['duration' => '+1 year']);
        $data = Cache::read('test_cache');
        $this->assertEqual($data, 'this is just a simple test of the cache system');

        Cache::delete('test_cache');

        $global = Cache::settings();

        Cache::set($_cacheSet);
    }
}
