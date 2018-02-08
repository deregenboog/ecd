<?php
/**
 * RequestHandlerComponentTest file.
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
 * @since         CakePHP(tm) v 1.2.0.5435
 *
 * @license       http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */
App::import('Controller', 'Controller', false);
App::import('Component', ['RequestHandler']);

Mock::generatePartial('RequestHandlerComponent', 'NoStopRequestHandler', ['_stop', '_header']);
Mock::generatePartial('Controller', 'RequestHandlerMockController', ['header']);

/**
 * RequestHandlerTestController class.
 */
class RequestHandlerTestController extends Controller
{
    /**
     * name property.
     *
     * @var string
     */
    public $name = 'RequestHandlerTest';

    /**
     * uses property.
     *
     * @var mixed null
     */
    public $uses = null;

    /**
     * construct method.
     *
     * @param array $params
     */
    public function __construct($params = [])
    {
        foreach ($params as $key => $val) {
            $this->{$key} = $val;
        }
        parent::__construct();
    }

    /**
     * test method for ajax redirection.
     */
    public function destination()
    {
        $this->viewPath = 'posts';
        $this->render('index');
    }

    /**
     * test method for ajax redirection + parameter parsing.
     */
    public function param_method($one = null, $two = null)
    {
        echo "one: $one two: $two";
        $this->autoRender = false;
    }

    /**
     * test method for testing layout rendering when isAjax().
     */
    public function ajax2_layout()
    {
        if ($this->autoLayout) {
            $this->layout = 'ajax2';
        }
        $this->destination();
    }
}

/**
 * RequestHandlerTestDisabledController class.
 */
class RequestHandlerTestDisabledController extends Controller
{
    /**
     * uses property.
     *
     * @var mixed null
     */
    public $uses = null;

    /**
     * construct method.
     *
     * @param array $params
     */
    public function __construct($params = [])
    {
        foreach ($params as $key => $val) {
            $this->{$key} = $val;
        }
        parent::__construct();
    }

    /**
     * beforeFilter method.
     */
    public function beforeFilter()
    {
        $this->RequestHandler->enabled = false;
    }
}

/**
 * RequestHandlerComponentTest class.
 */
class RequestHandlerComponentTest extends CakeTestCase
{
    /**
     * Controller property.
     *
     * @var RequestHandlerTestController
     */
    public $Controller;

    /**
     * RequestHandler property.
     *
     * @var RequestHandlerComponent
     */
    public $RequestHandler;

    /**
     * startTest method.
     */
    public function startTest()
    {
        $this->_init();
    }

    /**
     * init method.
     */
    public function _init()
    {
        $this->Controller = new RequestHandlerTestController(['components' => ['RequestHandler']]);
        $this->Controller->constructClasses();
        $this->RequestHandler = &$this->Controller->RequestHandler;
    }

    /**
     * endTest method.
     */
    public function endTest()
    {
        unset($this->RequestHandler);
        unset($this->Controller);
        if (!headers_sent()) {
            header('Content-type: text/html'); //reset content type.
        }
        App::build();
    }

    /**
     * testInitializeCallback method.
     */
    public function testInitializeCallback()
    {
        $this->assertNull($this->RequestHandler->ext);

        $this->_init();
        $this->Controller->params['url']['ext'] = 'rss';
        $this->RequestHandler->initialize($this->Controller);
        $this->assertEqual($this->RequestHandler->ext, 'rss');

        $settings = [
            'ajaxLayout' => 'test_ajax',
        ];
        $this->RequestHandler->initialize($this->Controller, $settings);
        $this->assertEqual($this->RequestHandler->ajaxLayout, 'test_ajax');
    }

    /**
     * testDisabling method.
     */
    public function testDisabling()
    {
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
        $this->_init();
        $this->Controller->Component->initialize($this->Controller);
        $this->Controller->beforeFilter();
        $this->Controller->Component->startup($this->Controller);
        $this->assertEqual($this->Controller->params, ['isAjax' => true]);

        $this->Controller = new RequestHandlerTestDisabledController(['components' => ['RequestHandler']]);
        $this->Controller->constructClasses();
        $this->Controller->Component->initialize($this->Controller);
        $this->Controller->beforeFilter();
        $this->Controller->Component->startup($this->Controller);
        $this->assertEqual($this->Controller->params, []);
        unset($_SERVER['HTTP_X_REQUESTED_WITH']);
    }

    /**
     * testAutoResponseType method.
     */
    public function testAutoResponseType()
    {
        $this->Controller->ext = '.thtml';
        $this->Controller->params['url']['ext'] = 'rss';
        $this->RequestHandler->initialize($this->Controller);
        $this->RequestHandler->startup($this->Controller);
        $this->assertEqual($this->Controller->ext, '.ctp');
    }

    /**
     * testAutoAjaxLayout method.
     */
    public function testAutoAjaxLayout()
    {
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
        $this->RequestHandler->startup($this->Controller);
        $this->assertTrue($this->Controller->layout, $this->RequestHandler->ajaxLayout);

        $this->_init();
        $this->Controller->params['url']['ext'] = 'js';
        $this->RequestHandler->initialize($this->Controller);
        $this->RequestHandler->startup($this->Controller);
        $this->assertNotEqual($this->Controller->layout, 'ajax');

        unset($_SERVER['HTTP_X_REQUESTED_WITH']);
    }

    /**
     * testStartupCallback method.
     */
    public function testStartupCallback()
    {
        $_SERVER['REQUEST_METHOD'] = 'PUT';
        $_SERVER['CONTENT_TYPE'] = 'application/xml';
        $this->RequestHandler->startup($this->Controller);
        $this->assertTrue(is_array($this->Controller->data));
        $this->assertFalse(is_object($this->Controller->data));
    }

    /**
     * testStartupCallback with charset.
     */
    public function testStartupCallbackCharset()
    {
        $_SERVER['REQUEST_METHOD'] = 'PUT';
        $_SERVER['CONTENT_TYPE'] = 'application/xml; charset=UTF-8';
        $this->RequestHandler->startup($this->Controller);
        $this->assertTrue(is_array($this->Controller->data));
        $this->assertFalse(is_object($this->Controller->data));
    }

    /**
     * testNonAjaxRedirect method.
     */
    public function testNonAjaxRedirect()
    {
        $this->RequestHandler->initialize($this->Controller);
        $this->RequestHandler->startup($this->Controller);
        $this->assertNull($this->RequestHandler->beforeRedirect($this->Controller, '/'));
    }

    /**
     * testRenderAs method.
     */
    public function testRenderAs()
    {
        $this->assertFalse(in_array('Xml', $this->Controller->helpers));
        $this->RequestHandler->renderAs($this->Controller, 'xml');
        $this->assertTrue(in_array('Xml', $this->Controller->helpers));

        $this->Controller->viewPath = 'request_handler_test\\xml';
        $this->RequestHandler->renderAs($this->Controller, 'js');
        $this->assertEqual($this->Controller->viewPath, 'request_handler_test'.DS.'js');
    }

    /**
     * test that respondAs works as expected.
     */
    public function testRespondAs()
    {
        $RequestHandler = new NoStopRequestHandler();
        $RequestHandler->expectAt(0, '_header', ['Content-Type: application/json']);
        $RequestHandler->expectAt(1, '_header', ['Content-Type: text/xml']);

        $result = $RequestHandler->respondAs('json');
        $this->assertTrue($result);

        $result = $RequestHandler->respondAs('text/xml');
        $this->assertTrue($result);
    }

    /**
     * test that attachment headers work with respondAs.
     */
    public function testRespondAsWithAttachment()
    {
        $RequestHandler = new NoStopRequestHandler();
        $RequestHandler->expectAt(0, '_header', ['Content-Disposition: attachment; filename="myfile.xml"']);
        $RequestHandler->expectAt(1, '_header', ['Content-Type: text/xml']);

        $result = $RequestHandler->respondAs('xml', ['attachment' => 'myfile.xml']);
        $this->assertTrue($result);
    }

    /**
     * test that calling renderAs() more than once continues to work.
     *
     * @see #6466
     */
    public function testRenderAsCalledTwice()
    {
        $this->RequestHandler->renderAs($this->Controller, 'xml');
        $this->assertEqual($this->Controller->viewPath, 'request_handler_test'.DS.'xml');
        $this->assertEqual($this->Controller->layoutPath, 'xml');

        $this->assertTrue(in_array('Xml', $this->Controller->helpers));

        $this->RequestHandler->renderAs($this->Controller, 'js');
        $this->assertEqual($this->Controller->viewPath, 'request_handler_test'.DS.'js');
        $this->assertEqual($this->Controller->layoutPath, 'js');
        $this->assertTrue(in_array('Js', $this->Controller->helpers));
    }

    /**
     * testRequestClientTypes method.
     */
    public function testRequestClientTypes()
    {
        $this->assertFalse($this->RequestHandler->isFlash());
        $_SERVER['HTTP_USER_AGENT'] = 'Shockwave Flash';
        $this->assertTrue($this->RequestHandler->isFlash());
        unset($_SERVER['HTTP_USER_AGENT'], $_SERVER['HTTP_X_REQUESTED_WITH']);

        $this->assertFalse($this->RequestHandler->isAjax());
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
        $_SERVER['HTTP_X_PROTOTYPE_VERSION'] = '1.5';
        $this->assertTrue($this->RequestHandler->isAjax());
        $this->assertEqual($this->RequestHandler->getAjaxVersion(), '1.5');

        unset($_SERVER['HTTP_X_REQUESTED_WITH'], $_SERVER['HTTP_X_PROTOTYPE_VERSION']);
        $this->assertFalse($this->RequestHandler->isAjax());
        $this->assertFalse($this->RequestHandler->getAjaxVersion());
    }

    /**
     * Tests the detection of various Flash versions.
     */
    public function testFlashDetection()
    {
        $_agent = env('HTTP_USER_AGENT');
        $_SERVER['HTTP_USER_AGENT'] = 'Shockwave Flash';
        $this->assertTrue($this->RequestHandler->isFlash());

        $_SERVER['HTTP_USER_AGENT'] = 'Adobe Flash';
        $this->assertTrue($this->RequestHandler->isFlash());

        $_SERVER['HTTP_USER_AGENT'] = 'Adobe Flash Player 9';
        $this->assertTrue($this->RequestHandler->isFlash());

        $_SERVER['HTTP_USER_AGENT'] = 'Adobe Flash Player 10';
        $this->assertTrue($this->RequestHandler->isFlash());

        $_SERVER['HTTP_USER_AGENT'] = 'Shock Flash';
        $this->assertFalse($this->RequestHandler->isFlash());

        $_SERVER['HTTP_USER_AGENT'] = $_agent;
    }

    /**
     * testRequestContentTypes method.
     */
    public function testRequestContentTypes()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $this->assertNull($this->RequestHandler->requestedWith());

        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['CONTENT_TYPE'] = 'application/json';
        $this->assertEqual($this->RequestHandler->requestedWith(), 'json');

        $result = $this->RequestHandler->requestedWith(['json', 'xml']);
        $this->assertEqual($result, 'json');

        $result = $this->RequestHandler->requestedWith(['rss', 'atom']);
        $this->assertFalse($result);

        $_SERVER['HTTP_ACCEPT'] = 'text/xml,application/xml,application/xhtml+xml,text/html,text/plain,image/png,*/*';
        $this->_init();
        $this->assertTrue($this->RequestHandler->isXml());
        $this->assertFalse($this->RequestHandler->isAtom());
        $this->assertFalse($this->RequestHandler->isRSS());

        $_SERVER['HTTP_ACCEPT'] = 'application/atom+xml,text/xml,application/xml,application/xhtml+xml,text/html,text/plain,image/png,*/*';
        $this->_init();
        $this->assertTrue($this->RequestHandler->isAtom());
        $this->assertFalse($this->RequestHandler->isRSS());

        $_SERVER['HTTP_ACCEPT'] = 'application/rss+xml,text/xml,application/xml,application/xhtml+xml,text/html,text/plain,image/png,*/*';
        $this->_init();
        $this->assertFalse($this->RequestHandler->isAtom());
        $this->assertTrue($this->RequestHandler->isRSS());

        $this->assertFalse($this->RequestHandler->isWap());
        $_SERVER['HTTP_ACCEPT'] = 'text/vnd.wap.wml,text/html,text/plain,image/png,*/*';
        $this->_init();
        $this->assertTrue($this->RequestHandler->isWap());

        $_SERVER['HTTP_ACCEPT'] = 'application/rss+xml ;q=0.9 ,  text/xml,  application/xml,application/xhtml+xml';
        $this->_init();
        $this->assertFalse($this->RequestHandler->isAtom());
        $this->assertTrue($this->RequestHandler->isRSS());
    }

    /**
     * testResponseContentType method.
     */
    public function testResponseContentType()
    {
        $this->assertNull($this->RequestHandler->responseType());
        $this->assertTrue($this->RequestHandler->respondAs('atom'));
        $this->assertEqual($this->RequestHandler->responseType(), 'atom');
    }

    /**
     * testMobileDeviceDetection method.
     */
    public function testMobileDeviceDetection()
    {
        $this->assertFalse($this->RequestHandler->isMobile());

        $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (iPhone; U; CPU like Mac OS X; en) AppleWebKit/420+ (KHTML, like Gecko) Version/3.0 Mobile/1A543a Safari/419.3';
        $this->assertTrue($this->RequestHandler->isMobile());

        $_SERVER['HTTP_USER_AGENT'] = 'Some imaginary UA';
        $this->RequestHandler->mobileUA[] = 'imaginary';
        $this->assertTrue($this->RequestHandler->isMobile());
        array_pop($this->RequestHandler->mobileUA);
    }

    /**
     * testRequestProperties method.
     */
    public function testRequestProperties()
    {
        $_SERVER['HTTPS'] = 'on';
        $this->assertTrue($this->RequestHandler->isSSL());

        unset($_SERVER['HTTPS']);
        $this->assertFalse($this->RequestHandler->isSSL());

        $_ENV['SCRIPT_URI'] = 'https://localhost/';
        $s = $_SERVER;
        $_SERVER = [];
        $this->assertTrue($this->RequestHandler->isSSL());
        $_SERVER = $s;
    }

    /**
     * testRequestMethod method.
     */
    public function testRequestMethod()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $this->assertTrue($this->RequestHandler->isGet());
        $this->assertFalse($this->RequestHandler->isPost());
        $this->assertFalse($this->RequestHandler->isPut());
        $this->assertFalse($this->RequestHandler->isDelete());

        $_SERVER['REQUEST_METHOD'] = 'POST';
        $this->assertFalse($this->RequestHandler->isGet());
        $this->assertTrue($this->RequestHandler->isPost());
        $this->assertFalse($this->RequestHandler->isPut());
        $this->assertFalse($this->RequestHandler->isDelete());

        $_SERVER['REQUEST_METHOD'] = 'PUT';
        $this->assertFalse($this->RequestHandler->isGet());
        $this->assertFalse($this->RequestHandler->isPost());
        $this->assertTrue($this->RequestHandler->isPut());
        $this->assertFalse($this->RequestHandler->isDelete());

        $_SERVER['REQUEST_METHOD'] = 'DELETE';
        $this->assertFalse($this->RequestHandler->isGet());
        $this->assertFalse($this->RequestHandler->isPost());
        $this->assertFalse($this->RequestHandler->isPut());
        $this->assertTrue($this->RequestHandler->isDelete());
    }

    /**
     * testClientContentPreference method.
     */
    public function testClientContentPreference()
    {
        $_SERVER['HTTP_ACCEPT'] = 'text/xml,application/xml,application/xhtml+xml,text/html,text/plain,image/png,*/*';
        $this->_init();
        $this->assertNotEqual($this->RequestHandler->prefers(), 'rss');
        $this->RequestHandler->ext = 'rss';
        $this->assertEqual($this->RequestHandler->prefers(), 'rss');
        $this->assertFalse($this->RequestHandler->prefers('xml'));
        $this->assertEqual($this->RequestHandler->prefers(['js', 'xml', 'xhtml']), 'xml');
        $this->assertTrue($this->RequestHandler->accepts('xml'));

        $_SERVER['HTTP_ACCEPT'] = 'text/xml,application/xml,application/xhtml+xml,text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5';
        $this->_init();
        $this->assertEqual($this->RequestHandler->prefers(), 'xml');
        $this->assertEqual($this->RequestHandler->accepts(['js', 'xml', 'html']), 'xml');
        $this->assertFalse($this->RequestHandler->accepts(['gif', 'jpeg', 'foo']));

        $_SERVER['HTTP_ACCEPT'] = '*/*;q=0.5';
        $this->_init();
        $this->assertEqual($this->RequestHandler->prefers(), 'html');
        $this->assertFalse($this->RequestHandler->prefers('rss'));
        $this->assertFalse($this->RequestHandler->accepts('rss'));
    }

    /**
     * testCustomContent method.
     */
    public function testCustomContent()
    {
        $_SERVER['HTTP_ACCEPT'] = 'text/x-mobile,text/html;q=0.9,text/plain;q=0.8,*/*;q=0.5';
        $this->_init();
        $this->RequestHandler->setContent('mobile', 'text/x-mobile');
        $this->RequestHandler->startup($this->Controller);
        $this->assertEqual($this->RequestHandler->prefers(), 'mobile');

        $this->_init();
        $this->RequestHandler->setContent(['mobile' => 'text/x-mobile']);
        $this->RequestHandler->startup($this->Controller);
        $this->assertEqual($this->RequestHandler->prefers(), 'mobile');
    }

    /**
     * testClientProperties method.
     */
    public function testClientProperties()
    {
        $_SERVER['HTTP_HOST'] = 'localhost:80';
        $this->assertEqual($this->RequestHandler->getReferer(), 'localhost');
        $_SERVER['HTTP_HOST'] = null;
        $_SERVER['HTTP_X_FORWARDED_HOST'] = 'cakephp.org';
        $this->assertEqual($this->RequestHandler->getReferer(), 'cakephp.org');

        $_SERVER['HTTP_X_FORWARDED_FOR'] = '192.168.1.5, 10.0.1.1, proxy.com';
        $_SERVER['HTTP_CLIENT_IP'] = '192.168.1.2';
        $_SERVER['REMOTE_ADDR'] = '192.168.1.3';
        $this->assertEqual($this->RequestHandler->getClientIP(false), '192.168.1.5');
        $this->assertEqual($this->RequestHandler->getClientIP(), '192.168.1.2');

        unset($_SERVER['HTTP_X_FORWARDED_FOR']);
        $this->assertEqual($this->RequestHandler->getClientIP(), '192.168.1.2');

        unset($_SERVER['HTTP_CLIENT_IP']);
        $this->assertEqual($this->RequestHandler->getClientIP(), '192.168.1.3');

        $_SERVER['HTTP_CLIENTADDRESS'] = '10.0.1.2, 10.0.1.1';
        $this->assertEqual($this->RequestHandler->getClientIP(), '10.0.1.2');
    }

    /**
     * test that ajax requests involving redirects trigger requestAction instead.
     */
    public function testAjaxRedirectAsRequestAction()
    {
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
        $this->_init();
        App::build([
            'views' => [TEST_CAKE_CORE_INCLUDE_PATH.'tests'.DS.'test_app'.DS.'views'.DS],
        ], true);

        $this->Controller->RequestHandler = new NoStopRequestHandler($this);
        $this->Controller->RequestHandler->expectOnce('_stop');

        ob_start();
        $this->Controller->RequestHandler->beforeRedirect(
            $this->Controller, ['controller' => 'request_handler_test', 'action' => 'destination']
        );
        $result = ob_get_clean();
        $this->assertPattern('/posts index/', $result, 'RequestAction redirect failed.');

        unset($_SERVER['HTTP_X_REQUESTED_WITH']);
        App::build();
    }

    /**
     * test that ajax requests involving redirects don't force no layout
     * this would cause the ajax layout to not be rendered.
     */
    public function testAjaxRedirectAsRequestActionStillRenderingLayout()
    {
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
        $this->_init();
        App::build([
            'views' => [TEST_CAKE_CORE_INCLUDE_PATH.'tests'.DS.'test_app'.DS.'views'.DS],
        ], true);

        $this->Controller->RequestHandler = new NoStopRequestHandler($this);
        $this->Controller->RequestHandler->expectOnce('_stop');

        ob_start();
        $this->Controller->RequestHandler->beforeRedirect(
            $this->Controller, ['controller' => 'request_handler_test', 'action' => 'ajax2_layout']
        );
        $result = ob_get_clean();
        $this->assertPattern('/posts index/', $result, 'RequestAction redirect failed.');
        $this->assertPattern('/Ajax!/', $result, 'Layout was not rendered.');

        unset($_SERVER['HTTP_X_REQUESTED_WITH']);
        App::build();
    }

    /**
     * test that the beforeRedirect callback properly converts
     * array urls into their correct string ones, and adds base => false so
     * the correct urls are generated.
     *
     * @see http://cakephp.lighthouseapp.com/projects/42648-cakephp-1x/tickets/276
     */
    public function testBeforeRedirectCallbackWithArrayUrl()
    {
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';

        Router::setRequestInfo([
            ['plugin' => null, 'controller' => 'accounts', 'action' => 'index', 'pass' => [], 'named' => [], 'form' => [], 'url' => ['url' => 'accounts/']],
            ['base' => '/officespace', 'here' => '/officespace/accounts/', 'webroot' => '/officespace/'],
        ]);

        $RequestHandler = new NoStopRequestHandler();

        ob_start();
        $RequestHandler->beforeRedirect(
            $this->Controller,
            ['controller' => 'request_handler_test', 'action' => 'param_method', 'first', 'second']
        );
        $result = ob_get_clean();
        $this->assertEqual($result, 'one: first two: second');
    }

    /**
     * assure that beforeRedirect with a status code will correctly set the status header.
     */
    public function testBeforeRedirectCallingHeader()
    {
        $controller = new RequestHandlerMockController();
        $RequestHandler = new NoStopRequestHandler();

        $controller->expectOnce('header', ['HTTP/1.1 403 Forbidden']);

        ob_start();
        $RequestHandler->beforeRedirect($controller, 'request_handler_test/param_method/first/second', 403);
        $result = ob_get_clean();
    }
}
