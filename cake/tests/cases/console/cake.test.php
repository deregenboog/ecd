<?php
/**
 * ShellDispatcherTest file.
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) Tests <http://book.cakephp.org/1.3/en/The-Manual/Common-Tasks-With-CakePHP/Testing.html>
 * Copyright 2005-2012, Cake Software Foundation, Inc.
 *
 *  Licensed under The Open Group Test Suite License
 *  Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc.
 *
 * @see          http://book.cakephp.org/1.3/en/The-Manual/Common-Tasks-With-CakePHP/Testing.html CakePHP(tm) Tests
 * @since         CakePHP(tm) v 1.2.0.5432
 *
 * @license       http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */
if (!defined('DISABLE_AUTO_DISPATCH')) {
    define('DISABLE_AUTO_DISPATCH', true);
}

if (!class_exists('ShellDispatcher')) {
    ob_start();
    $argv = false;
    require CAKE.'console'.DS.'cake.php';
    ob_end_clean();
}

require_once CONSOLE_LIBS.'shell.php';

/**
 * TestShellDispatcher class.
 */
class TestShellDispatcher extends ShellDispatcher
{
    /**
     * params property.
     *
     * @var array
     */
    public $params = [];

    /**
     * stdout property.
     *
     * @var string
     */
    public $stdout = '';

    /**
     * stderr property.
     *
     * @var string
     */
    public $stderr = '';

    /**
     * stopped property.
     *
     * @var string
     */
    public $stopped = null;

    /**
     * TestShell.
     *
     * @var mixed
     */
    public $TestShell;

    /**
     * _initEnvironment method.
     */
    public function _initEnvironment()
    {
    }

    /**
     * stderr method.
     */
    public function stderr($string)
    {
        $this->stderr .= rtrim($string, ' ');
    }

    /**
     * stdout method.
     */
    public function stdout($string, $newline = true)
    {
        if ($newline) {
            $this->stdout .= rtrim($string, ' ')."\n";
        } else {
            $this->stdout .= rtrim($string, ' ');
        }
    }

    /**
     * clear method.
     */
    public function clear()
    {
    }

    /**
     * _stop method.
     */
    public function _stop($status = 0)
    {
        $this->stopped = 'Stopped with status: '.$status;

        return $status;
    }

    /**
     * getShell.
     *
     * @param mixed $plugin
     *
     * @return mixed
     */
    public function getShell($plugin = null)
    {
        return $this->_getShell($plugin);
    }

    /**
     * _getShell.
     *
     * @param mixed $plugin
     *
     * @return mixed
     */
    public function _getShell($plugin = null)
    {
        if (isset($this->TestShell)) {
            return $this->TestShell;
        }

        return parent::_getShell($plugin);
    }
}

/**
 * ShellDispatcherTest.
 */
class ShellDispatcherTest extends CakeTestCase
{
    /**
     * setUp method.
     */
    public function setUp()
    {
        App::build([
            'plugins' => [
                TEST_CAKE_CORE_INCLUDE_PATH.'tests'.DS.'test_app'.DS.'plugins'.DS,
            ],
            'shells' => [
                CORE_PATH ? CONSOLE_LIBS : ROOT.DS.CONSOLE_LIBS,
                TEST_CAKE_CORE_INCLUDE_PATH.'tests'.DS.'test_app'.DS.'vendors'.DS.'shells'.DS,
            ],
        ], true);
    }

    /**
     * tearDown method.
     */
    public function tearDown()
    {
        App::build();
    }

    /**
     * testParseParams method.
     */
    public function testParseParams()
    {
        $Dispatcher = new TestShellDispatcher();

        $params = [
            '/cake/1.2.x.x/cake/console/cake.php',
            'bake',
            '-app',
            'new',
            '-working',
            '/var/www/htdocs',
        ];
        $expected = [
            'app' => 'new',
            'webroot' => 'webroot',
            'working' => '/var/www/htdocs/new',
            'root' => '/var/www/htdocs',
        ];
        $Dispatcher->parseParams($params);
        $this->assertEqual($expected, $Dispatcher->params);

        $params = ['cake.php'];
        $expected = [
            'app' => 'app',
            'webroot' => 'webroot',
            'working' => str_replace('\\', '/', CAKE_CORE_INCLUDE_PATH.DS.'app'),
            'root' => str_replace('\\', '/', CAKE_CORE_INCLUDE_PATH),
        ];
        $Dispatcher->params = $Dispatcher->args = [];
        $Dispatcher->parseParams($params);
        $this->assertEqual($expected, $Dispatcher->params);

        $params = [
            'cake.php',
            '-app',
            'new',
        ];
        $expected = [
            'app' => 'new',
            'webroot' => 'webroot',
            'working' => str_replace('\\', '/', CAKE_CORE_INCLUDE_PATH.DS.'new'),
            'root' => str_replace('\\', '/', CAKE_CORE_INCLUDE_PATH),
        ];
        $Dispatcher->params = $Dispatcher->args = [];
        $Dispatcher->parseParams($params);
        $this->assertEqual($expected, $Dispatcher->params);

        $params = [
            './cake.php',
            'bake',
            '-app',
            'new',
            '-working',
            '/cake/1.2.x.x/cake/console',
        ];

        $expected = [
            'app' => 'new',
            'webroot' => 'webroot',
            'working' => str_replace('\\', '/', CAKE_CORE_INCLUDE_PATH.DS.'new'),
            'root' => str_replace('\\', '/', CAKE_CORE_INCLUDE_PATH),
        ];

        $Dispatcher->params = $Dispatcher->args = [];
        $Dispatcher->parseParams($params);
        $this->assertEqual($expected, $Dispatcher->params);

        $params = [
            './console/cake.php',
            'bake',
            '-app',
            'new',
            '-working',
            '/cake/1.2.x.x/cake',
        ];
        $expected = [
            'app' => 'new',
            'webroot' => 'webroot',
            'working' => str_replace('\\', '/', CAKE_CORE_INCLUDE_PATH.DS.'new'),
            'root' => str_replace('\\', '/', CAKE_CORE_INCLUDE_PATH),
        ];
        $Dispatcher->params = $Dispatcher->args = [];
        $Dispatcher->parseParams($params);
        $this->assertEqual($expected, $Dispatcher->params);

        $params = [
            './console/cake.php',
            'bake',
            '-app',
            'new',
            '-dry',
            '-working',
            '/cake/1.2.x.x/cake',
        ];
        $expected = [
            'app' => 'new',
            'webroot' => 'webroot',
            'working' => str_replace('\\', '/', CAKE_CORE_INCLUDE_PATH.DS.'new'),
            'root' => str_replace('\\', '/', CAKE_CORE_INCLUDE_PATH),
            'dry' => 1,
        ];
        $Dispatcher->params = $Dispatcher->args = [];
        $Dispatcher->parseParams($params);
        $this->assertEqual($expected, $Dispatcher->params);

        $params = [
            './console/cake.php',
            '-working',
            '/cake/1.2.x.x/cake',
            'schema',
            'run',
            'create',
            '-dry',
            '-f',
            '-name',
            'DbAcl',
        ];
        $expected = [
            'app' => 'app',
            'webroot' => 'webroot',
            'working' => str_replace('\\', '/', CAKE_CORE_INCLUDE_PATH.DS.'app'),
            'root' => str_replace('\\', '/', CAKE_CORE_INCLUDE_PATH),
            'dry' => 1,
            'f' => 1,
            'name' => 'DbAcl',
        ];
        $Dispatcher->params = $Dispatcher->args = [];
        $Dispatcher->parseParams($params);
        $this->assertEqual($expected, $Dispatcher->params);

        $expected = ['./console/cake.php', 'schema', 'run', 'create'];
        $this->assertEqual($expected, $Dispatcher->args);

        $params = [
            '/cake/1.2.x.x/cake/console/cake.php',
            '-working',
            '/cake/1.2.x.x/app',
            'schema',
            'run',
            'create',
            '-dry',
            '-name',
            'DbAcl',
        ];
        $expected = [
            'app' => 'app',
            'webroot' => 'webroot',
            'working' => '/cake/1.2.x.x/app',
            'root' => '/cake/1.2.x.x',
            'dry' => 1,
            'name' => 'DbAcl',
        ];
        $Dispatcher->params = $Dispatcher->args = [];
        $Dispatcher->parseParams($params);
        $this->assertEqual($expected, $Dispatcher->params);

        $expected = ['/cake/1.2.x.x/cake/console/cake.php', 'schema', 'run', 'create'];
        $this->assertEqual($expected, $Dispatcher->args);
        $params = [
            'cake.php',
            '-working',
            'C:/wamp/www/cake/app',
            'bake',
            '-app',
            'C:/wamp/www/apps/cake/app',
        ];
        $expected = [
            'app' => 'app',
            'webroot' => 'webroot',
            'working' => 'C:\wamp\www\apps\cake\app',
            'root' => 'C:\wamp\www\apps\cake',
        ];

        $Dispatcher->params = $Dispatcher->args = [];
        $Dispatcher->parseParams($params);
        $this->assertEqual($expected, $Dispatcher->params);

        $params = [
            'cake.php',
            '-working',
            'C:\wamp\www\cake\app',
            'bake',
            '-app',
            'C:\wamp\www\apps\cake\app',
        ];
        $expected = [
            'app' => 'app',
            'webroot' => 'webroot',
            'working' => 'C:\wamp\www\apps\cake\app',
            'root' => 'C:\wamp\www\apps\cake',
        ];
        $Dispatcher->params = $Dispatcher->args = [];
        $Dispatcher->parseParams($params);
        $this->assertEqual($expected, $Dispatcher->params);

        $params = [
            'cake.php',
            '-working',
            'C:\wamp\www\apps',
            'bake',
            '-app',
            'cake\app',
            '-url',
            'http://example.com/some/url/with/a/path',
        ];
        $expected = [
            'app' => 'app',
            'webroot' => 'webroot',
            'working' => 'C:\wamp\www\apps\cake\app',
            'root' => 'C:\wamp\www\apps\cake',
            'url' => 'http://example.com/some/url/with/a/path',
        ];
        $Dispatcher->params = $Dispatcher->args = [];
        $Dispatcher->parseParams($params);
        $this->assertEqual($expected, $Dispatcher->params);

        $params = [
            '/home/amelo/dev/cake-common/cake/console/cake.php',
            '-root',
            '/home/amelo/dev/lsbu-vacancy',
            '-working',
            '/home/amelo/dev/lsbu-vacancy',
            '-app',
            'app',
        ];
        $expected = [
            'app' => 'app',
            'webroot' => 'webroot',
            'working' => '/home/amelo/dev/lsbu-vacancy/app',
            'root' => '/home/amelo/dev/lsbu-vacancy',
        ];
        $Dispatcher->params = $Dispatcher->args = [];
        $Dispatcher->parseParams($params);
        $this->assertEqual($expected, $Dispatcher->params);

        $params = [
            'cake.php',
            '-working',
            'D:\www',
            'bake',
            'my_app',
        ];
        $expected = [
            'working' => 'D:\www',
            'app' => 'www',
            'root' => 'D:',
            'webroot' => 'webroot',
        ];

        $Dispatcher->params = $Dispatcher->args = [];
        $Dispatcher->parseParams($params);
        $this->assertEqual($expected, $Dispatcher->params);

        $params = [
            'cake.php',
            '-working',
            'D:\ ',
            'bake',
            'my_app',
        ];
        $expected = [
            'working' => '.',
            'app' => 'D:',
            'root' => '.',
            'webroot' => 'webroot',
        ];
        $Dispatcher->params = $Dispatcher->args = [];
        $Dispatcher->parseParams($params);
        $this->assertEqual($expected, $Dispatcher->params);
    }

    /**
     * testBuildPaths method.
     */
    public function testBuildPaths()
    {
        $Dispatcher = new TestShellDispatcher();

        $result = $Dispatcher->shellPaths;

        $expected = [
            TEST_CAKE_CORE_INCLUDE_PATH.'tests'.DS.'test_app'.DS.'plugins'.DS.'test_plugin'.DS.'vendors'.DS.'shells'.DS,
            TEST_CAKE_CORE_INCLUDE_PATH.'tests'.DS.'test_app'.DS.'plugins'.DS.'test_plugin_two'.DS.'vendors'.DS.'shells'.DS,
            APP.'vendors'.DS.'shells'.DS,
            VENDORS.'shells'.DS,
            CORE_PATH ? CONSOLE_LIBS : ROOT.DS.CONSOLE_LIBS,
            TEST_CAKE_CORE_INCLUDE_PATH.'tests'.DS.'test_app'.DS.'vendors'.DS.'shells'.DS,
        ];
        $this->assertIdentical(array_diff($result, $expected), []);
        $this->assertIdentical(array_diff($expected, $result), []);
    }

    /**
     * Verify loading of (plugin-) shells.
     */
    public function testGetShell()
    {
        $this->skipIf(class_exists('SampleShell'), '%s SampleShell Class already loaded');
        $this->skipIf(class_exists('ExampleShell'), '%s ExampleShell Class already loaded');

        $Dispatcher = new TestShellDispatcher();

        $Dispatcher->shell = 'sample';
        $Dispatcher->shellName = 'Sample';
        $Dispatcher->shellClass = 'SampleShell';

        $result = $Dispatcher->getShell();
        $this->assertIsA($result, 'SampleShell');

        $Dispatcher = new TestShellDispatcher();

        $Dispatcher->shell = 'example';
        $Dispatcher->shellName = 'Example';
        $Dispatcher->shellClass = 'ExampleShell';

        $result = $Dispatcher->getShell('test_plugin');
        $this->assertIsA($result, 'ExampleShell');
    }

    /**
     * Verify correct dispatch of Shell subclasses with a main method.
     */
    public function testDispatchShellWithMain()
    {
        Mock::generate('Shell', 'MockWithMainShell', ['main', '_secret']);

        $Dispatcher = new TestShellDispatcher();

        $Shell = new MockWithMainShell();
        $Shell->setReturnValue('main', true);
        $Shell->expectOnce('initialize');
        $Shell->expectOnce('loadTasks');
        $Shell->expectOnce('startup');
        $Shell->expectOnce('main');
        $Dispatcher->TestShell = &$Shell;

        $Dispatcher->args = ['mock_with_main'];
        $result = $Dispatcher->dispatch();
        $this->assertTrue($result);
        $this->assertEqual($Dispatcher->args, []);

        $Shell = new MockWithMainShell();
        $Shell->setReturnValue('main', true);
        $Shell->expectOnce('startup');
        $Shell->expectOnce('main');
        $Dispatcher->TestShell = &$Shell;

        $Dispatcher->args = ['mock_with_main', 'initdb'];
        $result = $Dispatcher->dispatch();
        $this->assertTrue($result);
        $this->assertEqual($Dispatcher->args, ['initdb']);

        $Shell = new MockWithMainShell();
        $Shell->setReturnValue('main', true);
        $Shell->expectOnce('startup');
        $Shell->expectOnce('help');
        $Dispatcher->TestShell = &$Shell;

        $Dispatcher->args = ['mock_with_main', 'help'];
        $result = $Dispatcher->dispatch();
        $this->assertNull($result);
        $this->assertEqual($Dispatcher->args, []);

        $Shell = new MockWithMainShell();
        $Shell->setReturnValue('main', true);
        $Shell->expectNever('hr');
        $Shell->expectOnce('startup');
        $Shell->expectOnce('main');
        $Dispatcher->TestShell = &$Shell;

        $Dispatcher->args = ['mock_with_main', 'hr'];
        $result = $Dispatcher->dispatch();
        $this->assertTrue($result);
        $this->assertEqual($Dispatcher->args, ['hr']);

        $Shell = new MockWithMainShell();
        $Shell->setReturnValue('main', true);
        $Shell->expectOnce('startup');
        $Shell->expectOnce('main');
        $Dispatcher->TestShell = &$Shell;

        $Dispatcher->args = ['mock_with_main', 'dispatch'];
        $result = $Dispatcher->dispatch();
        $this->assertTrue($result);
        $this->assertEqual($Dispatcher->args, ['dispatch']);

        $Shell = new MockWithMainShell();
        $Shell->setReturnValue('main', true);
        $Shell->expectOnce('startup');
        $Shell->expectOnce('main');
        $Dispatcher->TestShell = &$Shell;

        $Dispatcher->args = ['mock_with_main', 'idontexist'];
        $result = $Dispatcher->dispatch();
        $this->assertTrue($result);
        $this->assertEqual($Dispatcher->args, ['idontexist']);

        $Shell = new MockWithMainShell();
        $Shell->expectNever('startup');
        $Shell->expectNever('main');
        $Shell->expectNever('_secret');
        $Dispatcher->TestShell = &$Shell;

        $Dispatcher->args = ['mock_with_main', '_secret'];
        $result = $Dispatcher->dispatch();
        $this->assertFalse($result);
    }

    /**
     * Verify correct dispatch of Shell subclasses without a main method.
     */
    public function testDispatchShellWithoutMain()
    {
        Mock::generate('Shell', 'MockWithoutMainShell', ['initDb', '_secret']);

        $Dispatcher = new TestShellDispatcher();

        $Shell = new MockWithoutMainShell();
        $Shell->setReturnValue('initDb', true);
        $Shell->expectOnce('initialize');
        $Shell->expectOnce('loadTasks');
        $Shell->expectNever('startup');
        $Dispatcher->TestShell = &$Shell;

        $Dispatcher->args = ['mock_without_main'];
        $result = $Dispatcher->dispatch();
        $this->assertFalse($result);
        $this->assertEqual($Dispatcher->args, []);

        $Shell = new MockWithoutMainShell();
        $Shell->setReturnValue('initDb', true);
        $Shell->expectOnce('startup');
        $Shell->expectOnce('initDb');
        $Dispatcher->TestShell = &$Shell;

        $Dispatcher->args = ['mock_without_main', 'initdb'];
        $result = $Dispatcher->dispatch();
        $this->assertTrue($result);
        $this->assertEqual($Dispatcher->args, []);

        $Shell = new MockWithoutMainShell();
        $Shell->setReturnValue('initDb', true);
        $Shell->expectNever('startup');
        $Shell->expectNever('hr');
        $Dispatcher->TestShell = &$Shell;

        $Dispatcher->args = ['mock_without_main', 'hr'];
        $result = $Dispatcher->dispatch();
        $this->assertFalse($result);
        $this->assertEqual($Dispatcher->args, ['hr']);

        $Shell = new MockWithoutMainShell();
        $Shell->setReturnValue('initDb', true);
        $Shell->expectNever('startup');
        $Dispatcher->TestShell = &$Shell;

        $Dispatcher->args = ['mock_without_main', 'dispatch'];
        $result = $Dispatcher->dispatch();
        $this->assertFalse($result);

        $Shell = new MockWithoutMainShell();
        $Shell->expectNever('startup');
        $Dispatcher->TestShell = &$Shell;

        $Dispatcher->args = ['mock_without_main', 'idontexist'];
        $result = $Dispatcher->dispatch();
        $this->assertFalse($result);

        $Shell = new MockWithoutMainShell();
        $Shell->expectNever('startup');
        $Shell->expectNever('_secret');
        $Dispatcher->TestShell = &$Shell;

        $Dispatcher->args = ['mock_without_main', '_secret'];
        $result = $Dispatcher->dispatch();
        $this->assertFalse($result);
    }

    /**
     * Verify correct dispatch of custom classes with a main method.
     */
    public function testDispatchNotAShellWithMain()
    {
        Mock::generate('Object', 'MockWithMainNotAShell',
            ['main', 'initialize', 'loadTasks', 'startup', '_secret']);

        $Dispatcher = new TestShellDispatcher();

        $Shell = new MockWithMainNotAShell();
        $Shell->setReturnValue('main', true);
        $Shell->expectNever('initialize');
        $Shell->expectNever('loadTasks');
        $Shell->expectOnce('startup');
        $Shell->expectOnce('main');
        $Dispatcher->TestShell = &$Shell;

        $Dispatcher->args = ['mock_with_main_not_a'];
        $result = $Dispatcher->dispatch();
        $this->assertTrue($result);
        $this->assertEqual($Dispatcher->args, []);

        $Shell = new MockWithMainNotAShell();
        $Shell->setReturnValue('main', true);
        $Shell->expectOnce('startup');
        $Shell->expectOnce('main');
        $Dispatcher->TestShell = &$Shell;

        $Dispatcher->args = ['mock_with_main_not_a', 'initdb'];
        $result = $Dispatcher->dispatch();
        $this->assertTrue($result);
        $this->assertEqual($Dispatcher->args, ['initdb']);

        $Shell = new MockWithMainNotAShell();
        $Shell->setReturnValue('main', true);
        $Shell->expectOnce('startup');
        $Shell->expectOnce('main');
        $Dispatcher->TestShell = &$Shell;

        $Dispatcher->args = ['mock_with_main_not_a', 'hr'];
        $result = $Dispatcher->dispatch();
        $this->assertTrue($result);
        $this->assertEqual($Dispatcher->args, ['hr']);

        $Shell = new MockWithMainNotAShell();
        $Shell->setReturnValue('main', true);
        $Shell->expectOnce('startup');
        $Shell->expectOnce('main');
        $Dispatcher->TestShell = &$Shell;

        $Dispatcher->args = ['mock_with_main_not_a', 'dispatch'];
        $result = $Dispatcher->dispatch();
        $this->assertTrue($result);
        $this->assertEqual($Dispatcher->args, ['dispatch']);

        $Shell = new MockWithMainNotAShell();
        $Shell->setReturnValue('main', true);
        $Shell->expectOnce('startup');
        $Shell->expectOnce('main');
        $Dispatcher->TestShell = &$Shell;

        $Dispatcher->args = ['mock_with_main_not_a', 'idontexist'];
        $result = $Dispatcher->dispatch();
        $this->assertTrue($result);
        $this->assertEqual($Dispatcher->args, ['idontexist']);

        $Shell = new MockWithMainNotAShell();
        $Shell->expectNever('startup');
        $Shell->expectNever('main');
        $Shell->expectNever('_secret');
        $Dispatcher->TestShell = &$Shell;

        $Dispatcher->args = ['mock_with_main_not_a', '_secret'];
        $result = $Dispatcher->dispatch();
        $this->assertFalse($result);
    }

    /**
     * Verify correct dispatch of custom classes without a main method.
     */
    public function testDispatchNotAShellWithoutMain()
    {
        Mock::generate('Object', 'MockWithoutMainNotAShell',
            ['initDb', 'initialize', 'loadTasks', 'startup', '_secret']);

        $Dispatcher = new TestShellDispatcher();

        $Shell = new MockWithoutMainNotAShell();
        $Shell->setReturnValue('initDb', true);
        $Shell->expectNever('initialize');
        $Shell->expectNever('loadTasks');
        $Shell->expectNever('startup');
        $Dispatcher->TestShell = &$Shell;

        $Dispatcher->args = ['mock_without_main_not_a'];
        $result = $Dispatcher->dispatch();
        $this->assertFalse($result);

        $Shell = new MockWithoutMainNotAShell();
        $Shell->setReturnValue('initDb', true);
        $Shell->expectOnce('startup');
        $Shell->expectOnce('initDb');
        $Dispatcher->TestShell = &$Shell;

        $Dispatcher->args = ['mock_without_main_not_a', 'initdb'];
        $result = $Dispatcher->dispatch();
        $this->assertTrue($result);
        $this->assertEqual($Dispatcher->args, []);

        $Shell = new MockWithoutMainNotAShell();
        $Shell->setReturnValue('initDb', true);
        $Shell->expectNever('startup');
        $Dispatcher->TestShell = &$Shell;

        $Dispatcher->args = ['mock_without_main_not_a', 'hr'];
        $result = $Dispatcher->dispatch();
        $this->assertFalse($result);

        $Shell = new MockWithoutMainNotAShell();
        $Shell->setReturnValue('initDb', true);
        $Shell->expectNever('startup');
        $Dispatcher->TestShell = &$Shell;

        $Dispatcher->args = ['mock_without_main_not_a', 'dispatch'];
        $result = $Dispatcher->dispatch();
        $this->assertFalse($result);

        $Shell = new MockWithoutMainNotAShell();
        $Shell->expectNever('startup');
        $Dispatcher->TestShell = &$Shell;

        $Dispatcher->args = ['mock_without_main_not_a', 'idontexist'];
        $result = $Dispatcher->dispatch();
        $this->assertFalse($result);

        $Shell = new MockWithoutMainNotAShell();
        $Shell->expectNever('startup');
        $Shell->expectNever('_secret');
        $Dispatcher->TestShell = &$Shell;

        $Dispatcher->args = ['mock_without_main_not_a', '_secret'];
        $result = $Dispatcher->dispatch();
        $this->assertFalse($result);
    }

    /**
     * Verify that a task is called instead of the shell if the first arg equals
     * the name of the task.
     */
    public function testDispatchTask()
    {
        Mock::generate('Shell', 'MockWeekShell', ['main']);
        Mock::generate('Shell', 'MockOnSundayTask', ['execute']);

        $Dispatcher = new TestShellDispatcher();

        $Shell = new MockWeekShell();
        $Shell->expectOnce('initialize');
        $Shell->expectOnce('loadTasks');
        $Shell->expectNever('startup');
        $Shell->expectNever('main');

        $Task = new MockOnSundayTask();
        $Task->setReturnValue('execute', true);
        $Task->expectOnce('initialize');
        $Task->expectOnce('loadTasks');
        $Task->expectOnce('startup');
        $Task->expectOnce('execute');

        $Shell->MockOnSunday = &$Task;
        $Shell->taskNames = ['MockOnSunday'];
        $Dispatcher->TestShell = &$Shell;

        $Dispatcher->args = ['mock_week', 'mock_on_sunday'];
        $result = $Dispatcher->dispatch();
        $this->assertTrue($result);
        $this->assertEqual($Dispatcher->args, []);

        $Shell = new MockWeekShell();
        $Task = new MockOnSundayTask();
        $Task->expectNever('execute');
        $Task->expectOnce('help');

        $Shell->MockOnSunday = &$Task;
        $Shell->taskNames = ['MockOnSunday'];
        $Dispatcher->TestShell = &$Shell;

        $Dispatcher->args = ['mock_week', 'mock_on_sunday', 'help'];
        $result = $Dispatcher->dispatch();
        $this->assertTrue($result);
    }

    /**
     * Verify shifting of arguments.
     */
    public function testShiftArgs()
    {
        $Dispatcher = new TestShellDispatcher();

        $Dispatcher->args = ['a', 'b', 'c'];
        $this->assertEqual($Dispatcher->shiftArgs(), 'a');
        $this->assertIdentical($Dispatcher->args, ['b', 'c']);

        $Dispatcher->args = ['a' => 'b', 'c', 'd'];
        $this->assertEqual($Dispatcher->shiftArgs(), 'b');
        $this->assertIdentical($Dispatcher->args, ['c', 'd']);

        $Dispatcher->args = ['a', 'b' => 'c', 'd'];
        $this->assertEqual($Dispatcher->shiftArgs(), 'a');
        $this->assertIdentical($Dispatcher->args, ['b' => 'c', 'd']);

        $Dispatcher->args = [0 => 'a',  2 => 'b', 30 => 'c'];
        $this->assertEqual($Dispatcher->shiftArgs(), 'a');
        $this->assertIdentical($Dispatcher->args, [0 => 'b', 1 => 'c']);

        $Dispatcher->args = [];
        $this->assertNull($Dispatcher->shiftArgs());
        $this->assertIdentical($Dispatcher->args, []);
    }

    /**
     * testHelpCommand method.
     */
    public function testHelpCommand()
    {
        $Dispatcher = new TestShellDispatcher();

        $expected = "/example \[.*TestPlugin, TestPluginTwo.*\]/";
        $this->assertPattern($expected, $Dispatcher->stdout);

        $expected = "/welcome \[.*TestPluginTwo.*\]/";
        $this->assertPattern($expected, $Dispatcher->stdout);

        $expected = "/acl \[.*CORE.*\]/";
        $this->assertPattern($expected, $Dispatcher->stdout);

        $expected = "/api \[.*CORE.*\]/";
        $this->assertPattern($expected, $Dispatcher->stdout);

        $expected = "/bake \[.*CORE.*\]/";
        $this->assertPattern($expected, $Dispatcher->stdout);

        $expected = "/console \[.*CORE.*\]/";
        $this->assertPattern($expected, $Dispatcher->stdout);

        $expected = "/i18n \[.*CORE.*\]/";
        $this->assertPattern($expected, $Dispatcher->stdout);

        $expected = "/schema \[.*CORE.*\]/";
        $this->assertPattern($expected, $Dispatcher->stdout);

        $expected = "/testsuite \[.*CORE.*\]/";
        $this->assertPattern($expected, $Dispatcher->stdout);

        $expected = "/sample \[.*test_app.*\]/";
        $this->assertPattern($expected, $Dispatcher->stdout);
    }
}
