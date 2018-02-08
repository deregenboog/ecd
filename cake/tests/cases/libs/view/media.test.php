<?php
/**
 * ThemeViewTest file.
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
 * @since         CakePHP(tm) v 1.2.0.4206
 *
 * @license       http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */
App::import('Core', ['Media', 'Controller']);

if (!class_exists('ErrorHandler')) {
    App::import('Core', ['Error']);
}
if (!defined('CAKEPHP_UNIT_TEST_EXECUTION')) {
    define('CAKEPHP_UNIT_TEST_EXECUTION', 1);
}

/**
 * ThemePostsController class.
 */
class MediaController extends Controller
{
    /**
     * name property.
     *
     * @var string 'Media'
     */
    public $name = 'Media';

    /**
     * index download.
     */
    public function download()
    {
        $path = TEST_CAKE_CORE_INCLUDE_PATH.'tests'.DS.'test_app'.DS.'vendors'.DS.'css'.DS;
        $id = 'test_asset.css';
        $extension = 'css';
        $this->set(compact('path', 'id', 'extension'));
    }

    public function downloadUpper()
    {
        $path = TEST_CAKE_CORE_INCLUDE_PATH.'tests'.DS.'test_app'.DS.'vendors'.DS.'img'.DS;
        $id = 'test_2.JPG';
        $extension = 'JPG';
        $this->set(compact('path', 'id', 'extension'));
    }
}

/**
 * TestMediaView class.
 */
class TestMediaView extends MediaView
{
    /**
     * headers public property as a copy from protected property _headers.
     *
     * @var array
     */
    public $headers = [];

    /**
     * active property to mock the status of a remote connection.
     *
     * @var bool true
     */
    public $active = true;

    public function _output()
    {
        $this->headers = $this->_headers;
    }

    /**
     * _isActive method. Usted de $active property to mock an active (true) connection,
     * or an aborted (false) one.
     */
    public function _isActive()
    {
        return $this->active;
    }

    /**
     * _clearBuffer method.
     */
    public function _clearBuffer()
    {
        return true;
    }

    /**
     * _flushBuffer method.
     */
    public function _flushBuffer()
    {
    }
}

/**
 * ThemeViewTest class.
 */
class MediaViewTest extends CakeTestCase
{
    /**
     * startTest method.
     */
    public function startTest()
    {
        Router::reload();
        $this->Controller = new Controller();
        $this->MediaController = new MediaController();
        $this->MediaController->viewPath = 'posts';
    }

    /**
     * endTest method.
     */
    public function endTest()
    {
        unset($this->MediaView);
        unset($this->MediaController);
        unset($this->Controller);
        ClassRegistry::flush();
    }

    /**
     * testRender method.
     */
    public function testRender()
    {
        ob_start();
        $this->MediaController->download();
        $this->MediaView = new TestMediaView($this->MediaController);
        $result = $this->MediaView->render();
        $output = ob_get_clean();

        $this->assertTrue(false !== $result);
        $this->assertEqual($output, 'this is the test asset css file');
    }

    public function testRenderUpperExtension()
    {
        ob_start();
        $this->MediaController->downloadUpper();
        $this->MediaView = new TestMediaView($this->MediaController);
        $result = $this->MediaView->render();
        $output = ob_get_clean();

        $this->assertTrue(false !== $result);

        $fileName = TEST_CAKE_CORE_INCLUDE_PATH.'tests'.DS.'test_app'.DS.'vendors'.DS.'img'.DS.'test_2.JPG';
        $file = file_get_contents($fileName, 'r');

        $this->assertEqual(base64_encode($output), base64_encode($file));
    }

    /**
     * testConnectionAborted method.
     */
    public function testConnectionAborted()
    {
        $this->MediaController->download();
        $this->MediaView = new TestMediaView($this->MediaController);
        $this->MediaView->active = false;
        $result = $this->MediaView->render();
        $this->assertFalse($result);
    }
}
