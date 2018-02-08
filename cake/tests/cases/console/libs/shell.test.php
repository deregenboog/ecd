<?php
/**
 * ShellTest file.
 *
 * Test Case for Shell
 *
 * PHP versions 4 and 5
 *
 * CakePHP :  Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc.
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc.
 *
 * @see          http://cakephp.org CakePHP Project
 * @since         CakePHP v 1.2.0.7726
 *
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::import('Core', 'Folder');
App::import('Shell', 'Shell', false);

if (!defined('DISABLE_AUTO_DISPATCH')) {
    define('DISABLE_AUTO_DISPATCH', true);
}

if (!class_exists('ShellDispatcher')) {
    ob_start();
    $argv = false;
    require CAKE.'console'.DS.'cake.php';
    ob_end_clean();
}

Mock::generatePartial('ShellDispatcher', 'TestShellMockShellDispatcher', [
    'getInput', 'stdout', 'stderr', '_stop', '_initEnvironment',
]);

/**
 * TestShell class.
 */
class TestShell extends Shell
{
    /**
     * name property.
     *
     * @var name
     */
    public $name = 'TestShell';
    /**
     * stopped property.
     *
     * @var int
     */
    public $stopped;

    /**
     * stop method.
     *
     * @param int $status
     */
    public function _stop($status = 0)
    {
        $this->stopped = $status;
    }
}

/**
 * TestAppleTask class.
 */
class TestAppleTask extends Shell
{
}

/**
 * TestBananaTask class.
 */
class TestBananaTask extends Shell
{
}

/**
 * ShellTest class.
 */
class ShellTest extends CakeTestCase
{
    /**
     * Fixtures used in this test case.
     *
     * @var array
     */
    public $fixtures = [
        'core.post', 'core.comment', 'core.article', 'core.user',
        'core.tag', 'core.articles_tag', 'core.attachment',
    ];

    /**
     * setUp method.
     */
    public function setUp()
    {
        $this->Dispatcher = new TestShellMockShellDispatcher();
        $this->Shell = new TestShell($this->Dispatcher);
    }

    /**
     * tearDown method.
     */
    public function tearDown()
    {
        ClassRegistry::flush();
    }

    /**
     * testConstruct method.
     */
    public function testConstruct()
    {
        $this->assertIsA($this->Shell->Dispatch, 'TestShellMockShellDispatcher');
        $this->assertEqual($this->Shell->name, 'TestShell');
        $this->assertEqual($this->Shell->alias, 'TestShell');
    }

    /**
     * testInitialize method.
     */
    public function testInitialize()
    {
        App::build([
            'plugins' => [TEST_CAKE_CORE_INCLUDE_PATH.'tests'.DS.'test_app'.DS.'plugins'.DS],
            'models' => [TEST_CAKE_CORE_INCLUDE_PATH.'tests'.DS.'test_app'.DS.'models'.DS],
        ], true);

        $this->Shell->uses = ['TestPlugin.TestPluginPost'];
        $this->Shell->initialize();

        $this->assertTrue(isset($this->Shell->TestPluginPost));
        $this->assertIsA($this->Shell->TestPluginPost, 'TestPluginPost');
        $this->assertEqual($this->Shell->modelClass, 'TestPluginPost');

        $this->Shell->uses = ['Comment'];
        $this->Shell->initialize();
        $this->assertTrue(isset($this->Shell->Comment));
        $this->assertIsA($this->Shell->Comment, 'Comment');
        $this->assertEqual($this->Shell->modelClass, 'Comment');

        $this->Shell->uses = true;
        $this->Shell->initialize();
        $this->assertTrue(isset($this->Shell->AppModel));
        $this->assertIsA($this->Shell->AppModel, 'AppModel');

        App::build();
    }

    /**
     * testIn method.
     */
    public function testIn()
    {
        $this->Shell->Dispatch->setReturnValueAt(0, 'getInput', 'n');
        $this->Shell->Dispatch->expectAt(0, 'getInput', ['Just a test?', ['y', 'n'], 'n']);
        $result = $this->Shell->in('Just a test?', ['y', 'n'], 'n');
        $this->assertEqual($result, 'n');

        $this->Shell->Dispatch->setReturnValueAt(1, 'getInput', 'Y');
        $this->Shell->Dispatch->expectAt(1, 'getInput', ['Just a test?', ['y', 'n'], 'n']);
        $result = $this->Shell->in('Just a test?', ['y', 'n'], 'n');
        $this->assertEqual($result, 'Y');

        $this->Shell->Dispatch->setReturnValueAt(2, 'getInput', 'y');
        $this->Shell->Dispatch->expectAt(2, 'getInput', ['Just a test?', 'y,n', 'n']);
        $result = $this->Shell->in('Just a test?', 'y,n', 'n');
        $this->assertEqual($result, 'y');

        $this->Shell->Dispatch->setReturnValueAt(3, 'getInput', 'y');
        $this->Shell->Dispatch->expectAt(3, 'getInput', ['Just a test?', 'y/n', 'n']);
        $result = $this->Shell->in('Just a test?', 'y/n', 'n');
        $this->assertEqual($result, 'y');

        $this->Shell->Dispatch->setReturnValueAt(4, 'getInput', 'y');
        $this->Shell->Dispatch->expectAt(4, 'getInput', ['Just a test?', 'y', 'y']);
        $result = $this->Shell->in('Just a test?', 'y', 'y');
        $this->assertEqual($result, 'y');

        $this->Shell->Dispatch->setReturnValueAt(5, 'getInput', 'y');
        $this->Shell->Dispatch->expectAt(5, 'getInput', ['Just a test?', [0, 1, 2], 0]);
        $result = $this->Shell->in('Just a test?', [0, 1, 2], 0);
        $this->assertEqual($result, 0);
    }

    /**
     * Test in() when not interactive.
     */
    public function testInNonInteractive()
    {
        $this->Shell->interactive = false;

        $result = $this->Shell->in('Just a test?', 'y/n', 'n');
        $this->assertEqual($result, 'n');
    }

    /**
     * testOut method.
     */
    public function testOut()
    {
        $this->Shell->Dispatch->expectAt(0, 'stdout', ["Just a test\n", false]);
        $this->Shell->out('Just a test');

        $this->Shell->Dispatch->expectAt(1, 'stdout', ["Just\na\ntest\n", false]);
        $this->Shell->out(['Just', 'a', 'test']);

        $this->Shell->Dispatch->expectAt(2, 'stdout', ["Just\na\ntest\n\n", false]);
        $this->Shell->out(['Just', 'a', 'test'], 2);

        $this->Shell->Dispatch->expectAt(3, 'stdout', ["\n", false]);
        $this->Shell->out();
    }

    /**
     * testErr method.
     */
    public function testErr()
    {
        $this->Shell->Dispatch->expectAt(0, 'stderr', ["Just a test\n"]);
        $this->Shell->err('Just a test');

        $this->Shell->Dispatch->expectAt(1, 'stderr', ["Just\na\ntest\n"]);
        $this->Shell->err(['Just', 'a', 'test']);

        $this->Shell->Dispatch->expectAt(2, 'stderr', ["Just\na\ntest\n\n"]);
        $this->Shell->err(['Just', 'a', 'test'], 2);

        $this->Shell->Dispatch->expectAt(3, 'stderr', ["\n"]);
        $this->Shell->err();
    }

    /**
     * testNl.
     */
    public function testNl()
    {
        $this->assertEqual($this->Shell->nl(), "\n");
        $this->assertEqual($this->Shell->nl(true), "\n");
        $this->assertEqual($this->Shell->nl(false), '');
        $this->assertEqual($this->Shell->nl(2), "\n\n");
        $this->assertEqual($this->Shell->nl(1), "\n");
    }

    /**
     * testHr.
     */
    public function testHr()
    {
        $bar = '---------------------------------------------------------------';

        $this->Shell->Dispatch->expectAt(0, 'stdout', ['', false]);
        $this->Shell->Dispatch->expectAt(1, 'stdout', [$bar."\n", false]);
        $this->Shell->Dispatch->expectAt(2, 'stdout', ['', false]);
        $this->Shell->hr();

        $this->Shell->Dispatch->expectAt(3, 'stdout', ["\n", false]);
        $this->Shell->Dispatch->expectAt(4, 'stdout', [$bar."\n", false]);
        $this->Shell->Dispatch->expectAt(5, 'stdout', ["\n", false]);
        $this->Shell->hr(true);

        $this->Shell->Dispatch->expectAt(3, 'stdout', ["\n\n", false]);
        $this->Shell->Dispatch->expectAt(4, 'stdout', [$bar."\n", false]);
        $this->Shell->Dispatch->expectAt(5, 'stdout', ["\n\n", false]);
        $this->Shell->hr(2);
    }

    /**
     * testError.
     */
    public function testError()
    {
        $this->Shell->Dispatch->expectAt(0, 'stderr', ["Error: Foo Not Found\n"]);
        $this->Shell->error('Foo Not Found');
        $this->assertIdentical($this->Shell->stopped, 1);

        $this->Shell->stopped = null;

        $this->Shell->Dispatch->expectAt(1, 'stderr', ["Error: Foo Not Found\n"]);
        $this->Shell->Dispatch->expectAt(2, 'stderr', ["Searched all...\n"]);
        $this->Shell->error('Foo Not Found', 'Searched all...');
        $this->assertIdentical($this->Shell->stopped, 1);
    }

    /**
     * testLoadTasks method.
     */
    public function testLoadTasks()
    {
        $this->assertTrue($this->Shell->loadTasks());

        $this->Shell->tasks = null;
        $this->assertTrue($this->Shell->loadTasks());

        $this->Shell->tasks = false;
        $this->assertTrue($this->Shell->loadTasks());

        $this->Shell->tasks = true;
        $this->assertTrue($this->Shell->loadTasks());

        $this->Shell->tasks = [];
        $this->assertTrue($this->Shell->loadTasks());

        // Fatal Error
        // $this->Shell->tasks = 'TestIDontExist';
        // $this->assertFalse($this->Shell->loadTasks());
        // $this->assertFalse(isset($this->Shell->TestIDontExist));

        $this->Shell->tasks = 'TestApple';
        $this->assertTrue($this->Shell->loadTasks());
        $this->assertIsA($this->Shell->TestApple, 'TestAppleTask');

        $this->Shell->tasks = 'TestBanana';
        $this->assertTrue($this->Shell->loadTasks());
        $this->assertIsA($this->Shell->TestApple, 'TestAppleTask');
        $this->assertIsA($this->Shell->TestBanana, 'TestBananaTask');

        unset($this->Shell->ShellTestApple, $this->Shell->TestBanana);

        $this->Shell->tasks = ['TestApple', 'TestBanana'];
        $this->assertTrue($this->Shell->loadTasks());
        $this->assertIsA($this->Shell->TestApple, 'TestAppleTask');
        $this->assertIsA($this->Shell->TestBanana, 'TestBananaTask');
    }

    /**
     * testShortPath method.
     */
    public function testShortPath()
    {
        $path = $expected = DS.'tmp'.DS.'ab'.DS.'cd';
        $this->assertEqual($this->Shell->shortPath($path), $expected);

        $path = $expected = DS.'tmp'.DS.'ab'.DS.'cd'.DS;
        $this->assertEqual($this->Shell->shortPath($path), $expected);

        $path = $expected = DS.'tmp'.DS.'ab'.DS.'index.php';
        $this->assertEqual($this->Shell->shortPath($path), $expected);

        // Shell::shortPath needs Folder::realpath
        // $path = DS . 'tmp' . DS . 'ab' . DS . '..' . DS . 'cd';
        // $expected = DS . 'tmp' . DS . 'cd';
        // $this->assertEqual($this->Shell->shortPath($path), $expected);

        $path = DS.'tmp'.DS.'ab'.DS.DS.'cd';
        $expected = DS.'tmp'.DS.'ab'.DS.'cd';
        $this->assertEqual($this->Shell->shortPath($path), $expected);

        $path = 'tmp'.DS.'ab';
        $expected = 'tmp'.DS.'ab';
        $this->assertEqual($this->Shell->shortPath($path), $expected);

        $path = 'tmp'.DS.'ab';
        $expected = 'tmp'.DS.'ab';
        $this->assertEqual($this->Shell->shortPath($path), $expected);

        $path = APP;
        $expected = DS.basename(APP).DS;
        $this->assertEqual($this->Shell->shortPath($path), $expected);

        $path = APP.'index.php';
        $expected = DS.basename(APP).DS.'index.php';
        $this->assertEqual($this->Shell->shortPath($path), $expected);
    }

    /**
     * testCreateFile method.
     */
    public function testCreateFile()
    {
        $this->skipIf(DIRECTORY_SEPARATOR === '\\', '%s Not supported on Windows');

        $path = TMP.'shell_test';
        $file = $path.DS.'file1.php';

        new Folder($path, true);

        $this->Shell->interactive = false;

        $contents = "<?php\necho 'test';\n\$te = 'st';\n?>";
        $result = $this->Shell->createFile($file, $contents);
        $this->assertTrue($result);
        $this->assertTrue(file_exists($file));
        $this->assertEqual(file_get_contents($file), $contents);

        $contents = "<?php\necho 'another test';\n\$te = 'st';\n?>";
        $result = $this->Shell->createFile($file, $contents);
        $this->assertTrue($result);
        $this->assertTrue(file_exists($file));
        $this->assertEqual(file_get_contents($file), $contents);

        $this->Shell->interactive = true;

        $this->Shell->Dispatch->setReturnValueAt(0, 'getInput', 'n');
        $this->Shell->Dispatch->expectAt(1, 'stdout', ['File exists, overwrite?', '*']);

        $contents = "<?php\necho 'yet another test';\n\$te = 'st';\n?>";
        $result = $this->Shell->createFile($file, $contents);
        $this->assertFalse($result);
        $this->assertTrue(file_exists($file));
        $this->assertNotEqual(file_get_contents($file), $contents);

        $this->Shell->Dispatch->setReturnValueAt(1, 'getInput', 'y');
        $this->Shell->Dispatch->expectAt(3, 'stdout', ['File exists, overwrite?', '*']);

        $result = $this->Shell->createFile($file, $contents);
        $this->assertTrue($result);
        $this->assertTrue(file_exists($file));
        $this->assertEqual(file_get_contents($file), $contents);

        $Folder = new Folder($path);
        $Folder->delete();
    }

    /**
     * testCreateFileWindows method.
     */
    public function testCreateFileWindows()
    {
        $this->skipUnless(DIRECTORY_SEPARATOR === '\\', 'testCreateFileWindows supported on Windows only');

        $path = TMP.'shell_test';
        $file = $path.DS.'file1.php';

        new Folder($path, true);

        $this->Shell->interactive = false;

        $contents = "<?php\necho 'test';\r\n\$te = 'st';\r\n?>";
        $result = $this->Shell->createFile($file, $contents);
        $this->assertTrue($result);
        $this->assertTrue(file_exists($file));
        $this->assertEqual(file_get_contents($file), $contents);

        $contents = "<?php\necho 'another test';\r\n\$te = 'st';\r\n?>";
        $result = $this->Shell->createFile($file, $contents);
        $this->assertTrue($result);
        $this->assertTrue(file_exists($file));
        $this->assertEqual(file_get_contents($file), $contents);

        $this->Shell->interactive = true;

        $this->Shell->Dispatch->setReturnValueAt(0, 'getInput', 'n');
        $this->Shell->Dispatch->expectAt(1, 'stdout', ['File exists, overwrite?']);

        $contents = "<?php\necho 'yet another test';\r\n\$te = 'st';\r\n?>";
        $result = $this->Shell->createFile($file, $contents);
        $this->assertFalse($result);
        $this->assertTrue(file_exists($file));
        $this->assertNotEqual(file_get_contents($file), $contents);

        $this->Shell->Dispatch->setReturnValueAt(1, 'getInput', 'y');
        $this->Shell->Dispatch->expectAt(3, 'stdout', ['File exists, overwrite?']);

        $result = $this->Shell->createFile($file, $contents);
        $this->assertTrue($result);
        $this->assertTrue(file_exists($file));
        $this->assertEqual(file_get_contents($file), $contents);

        $Folder = new Folder($path);
        $Folder->delete();
    }
}
