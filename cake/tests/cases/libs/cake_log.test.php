<?php
/**
 * CakeLogTest file.
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
App::import('Core', 'Log');
App::import('Core', 'log/FileLog');

/**
 * CakeLogTest class.
 */
class CakeLogTest extends CakeTestCase
{
    /**
     * Start test callback, clears all streams enabled.
     */
    public function startTest()
    {
        $streams = CakeLog::configured();
        foreach ($streams as $stream) {
            CakeLog::drop($stream);
        }
    }

    /**
     * test importing loggers from app/libs and plugins.
     */
    public function testImportingLoggers()
    {
        App::build([
            'libs' => [TEST_CAKE_CORE_INCLUDE_PATH.'tests'.DS.'test_app'.DS.'libs'.DS],
            'plugins' => [TEST_CAKE_CORE_INCLUDE_PATH.'tests'.DS.'test_app'.DS.'plugins'.DS],
        ], true);

        $result = CakeLog::config('libtest', [
            'engine' => 'TestAppLog',
        ]);
        $this->assertTrue($result);
        $this->assertEqual(CakeLog::configured(), ['libtest']);

        $result = CakeLog::config('plugintest', [
            'engine' => 'TestPlugin.TestPluginLog',
        ]);
        $this->assertTrue($result);
        $this->assertEqual(CakeLog::configured(), ['libtest', 'plugintest']);

        App::build();
    }

    /**
     * test all the errors from failed logger imports.
     */
    public function testImportingLoggerFailure()
    {
        $this->expectError('Missing logger classname');
        CakeLog::config('fail', []);

        $this->expectError('Could not load logger class born to fail');
        CakeLog::config('fail', ['engine' => 'born to fail']);

        $this->expectError('logger class stdClass does not implement a write method.');
        CakeLog::config('fail', ['engine' => 'stdClass']);
    }

    /**
     * Test that CakeLog autoconfigures itself to use a FileLogger with the LOGS dir.
     * When no streams are there.
     */
    public function testAutoConfig()
    {
        @unlink(LOGS.'error.log');
        CakeLog::write(LOG_WARNING, 'Test warning');
        $this->assertTrue(file_exists(LOGS.'error.log'));

        $result = CakeLog::configured();
        $this->assertEqual($result, ['default']);
        unlink(LOGS.'error.log');
    }

    /**
     * test configuring log streams.
     */
    public function testConfig()
    {
        CakeLog::config('file', [
            'engine' => 'FileLog',
            'path' => LOGS,
        ]);
        $result = CakeLog::configured();
        $this->assertEqual($result, ['file']);

        @unlink(LOGS.'error.log');
        CakeLog::write(LOG_WARNING, 'Test warning');
        $this->assertTrue(file_exists(LOGS.'error.log'));

        $result = file_get_contents(LOGS.'error.log');
        $this->assertPattern('/^2[0-9]{3}-[0-9]+-[0-9]+ [0-9]+:[0-9]+:[0-9]+ Warning: Test warning/', $result);
        unlink(LOGS.'error.log');
    }

    /**
     * explict tests for drop().
     *
     **/
    public function testDrop()
    {
        CakeLog::config('file', [
            'engine' => 'FileLog',
            'path' => LOGS,
        ]);
        $result = CakeLog::configured();
        $this->assertEqual($result, ['file']);

        CakeLog::drop('file');
        $result = CakeLog::configured();
        $this->assertEqual($result, []);
    }

    /**
     * testLogFileWriting method.
     */
    public function testLogFileWriting()
    {
        @unlink(LOGS.'error.log');
        $result = CakeLog::write(LOG_WARNING, 'Test warning');
        $this->assertTrue($result);
        $this->assertTrue(file_exists(LOGS.'error.log'));
        unlink(LOGS.'error.log');

        CakeLog::write(LOG_WARNING, 'Test warning 1');
        CakeLog::write(LOG_WARNING, 'Test warning 2');
        $result = file_get_contents(LOGS.'error.log');
        $this->assertPattern('/^2[0-9]{3}-[0-9]+-[0-9]+ [0-9]+:[0-9]+:[0-9]+ Warning: Test warning 1/', $result);
        $this->assertPattern('/2[0-9]{3}-[0-9]+-[0-9]+ [0-9]+:[0-9]+:[0-9]+ Warning: Test warning 2$/', $result);
        unlink(LOGS.'error.log');
    }

    /**
     * Test logging with the error handler.
     */
    public function testLoggingWithErrorHandling()
    {
        @unlink(LOGS.'debug.log');
        Configure::write('log', E_ALL & ~E_DEPRECATED & ~E_STRICT);
        Configure::write('debug', 0);

        set_error_handler(['CakeLog', 'handleError']);
        $out .= '';

        $result = file(LOGS.'debug.log');
        $this->assertEqual(count($result), 1);
        $this->assertPattern(
            '/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2} Notice: Notice \(8\): Undefined variable:\s+out in \[.+ line \d+\]$/',
            $result[0]
        );
        @unlink(LOGS.'debug.log');
    }
}
