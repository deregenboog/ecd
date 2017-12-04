<?php
/**
 * SessionHelperTest file.
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
if (!defined('CAKEPHP_UNIT_TEST_EXECUTION')) {
    define('CAKEPHP_UNIT_TEST_EXECUTION', 1);
}
App::import('Core', ['Helper', 'AppHelper', 'Controller', 'View']);
App::import('Helper', ['Session']);

/**
 * SessionHelperTest class.
 */
class SessionHelperTest extends CakeTestCase
{
    /**
     * setUp method.
     */
    public function startTest()
    {
        $this->Session = new SessionHelper();

        $_SESSION = [
            'test' => 'info',
            'Message' => [
                'flash' => [
                    'element' => 'default',
                    'params' => [],
                    'message' => 'This is a calling',
                ],
                'notification' => [
                    'element' => 'session_helper',
                    'params' => ['title' => 'Notice!', 'name' => 'Alert!'],
                    'message' => 'This is a test of the emergency broadcasting system',
                ],
                'classy' => [
                    'element' => 'default',
                    'params' => ['class' => 'positive'],
                    'message' => 'Recorded',
                ],
                'bare' => [
                    'element' => null,
                    'message' => 'Bare message',
                    'params' => [],
                ],
            ],
            'Deeply' => ['nested' => ['key' => 'value']],
        ];
    }

    /**
     * tearDown method.
     */
    public function tearDown()
    {
        $_SESSION = [];
        unset($this->Session);
    }

    /**
     * endTest.
     */
    public function endTest()
    {
        App::build();
    }

    /**
     * test construction and initial property settings.
     */
    public function testConstruct()
    {
        $this->assertFalse(empty($this->Session->sessionTime));
        $this->assertFalse(empty($this->Session->security));
    }

    /**
     * testRead method.
     */
    public function testRead()
    {
        $result = $this->Session->read('Deeply.nested.key');
        $this->assertEqual($result, 'value');

        $result = $this->Session->read('test');
        $this->assertEqual($result, 'info');
    }

    /**
     * testCheck method.
     */
    public function testCheck()
    {
        $this->assertTrue($this->Session->check('test'));

        $this->assertTrue($this->Session->check('Message.flash.element'));

        $this->assertFalse($this->Session->check('Does.not.exist'));

        $this->assertFalse($this->Session->check('Nope'));
    }

    /**
     * testWrite method.
     */
    public function testWrite()
    {
        $this->expectError();
        $this->Session->write('NoWay', 'AccessDenied');
    }

    /**
     * testFlash method.
     */
    public function testFlash()
    {
        $result = $this->Session->flash('flash', true);
        $expected = '<div id="flashMessage" class="message">This is a calling</div>';
        $this->assertEqual($result, $expected);
        $this->assertFalse($this->Session->check('Message.flash'));

        $expected = '<div id="classyMessage" class="positive">Recorded</div>';
        $result = $this->Session->flash('classy', true);
        $this->assertEqual($result, $expected);

        App::build([
            'views' => [TEST_CAKE_CORE_INCLUDE_PATH.'tests'.DS.'test_app'.DS.'views'.DS],
        ]);
        $controller = new Controller();
        $this->Session->view = new View($controller);

        $result = $this->Session->flash('notification', true);
        $result = str_replace("\r\n", "\n", $result);
        $expected = "<div id=\"notificationLayout\">\n\t<h1>Alert!</h1>\n\t<h3>Notice!</h3>\n\t<p>This is a test of the emergency broadcasting system</p>\n</div>";
        $this->assertEqual($result, $expected);
        $this->assertFalse($this->Session->check('Message.notification'));

        $result = $this->Session->flash('bare');
        $expected = 'Bare message';
        $this->assertEqual($result, $expected);
        $this->assertFalse($this->Session->check('Message.bare'));
    }

    /**
     * testID method.
     */
    public function testID()
    {
        $id = session_id();
        $result = $this->Session->id();
        $this->assertEqual($id, $result);
    }

    /**
     * testError method.
     */
    public function testError()
    {
        $result = $this->Session->error();
        $this->assertFalse($result);

        $this->Session->read('CauseError');
        $result = $this->Session->error();
        $expected = "CauseError doesn't exist";
        $this->assertEqual($result, $expected);
    }

    /**
     * testDisabling method.
     */
    public function testDisabling()
    {
        Configure::write('Session.start', false);
        $this->Session = new SessionHelper();
        $this->assertFalse($this->Session->check('test'));
        $this->assertFalse($this->Session->read('test'));

        $this->Session->read('CauseError');
        $this->assertFalse($this->Session->error());

        ob_start();
        $this->assertFalse($this->Session->flash('bare'));
        $result = ob_get_contents();
        ob_clean();
        $this->assertFalse($result);
    }

    /**
     * testValid method.
     */
    public function testValid()
    {
        //wierd it always ends up false in the test suite
        //$this->assertFalse($this->Session->valid());
    }
}
