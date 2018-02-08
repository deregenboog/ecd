<?php
/**
 * ErrorHandlerTest file.
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
if (class_exists('TestErrorHandler')) {
    return;
}
if (!defined('CAKEPHP_UNIT_TEST_EXECUTION')) {
    define('CAKEPHP_UNIT_TEST_EXECUTION', 1);
}

/**
 * BlueberryComponent class.
 */
class BlueberryComponent extends Object
{
    /**
     * testName property.
     */
    public $testName = null;

    /**
     * initialize method.
     */
    public function initialize(&$controller)
    {
        $this->testName = 'BlueberryComponent';
    }
}

/**
 * BlueberryDispatcher class.
 */
class BlueberryDispatcher extends Dispatcher
{
    /**
     * cakeError method.
     */
    public function cakeError($method, $messages = [])
    {
        $error = new TestErrorHandler($method, $messages);

        return $error;
    }
}

/**
 * Short description for class.
 */
class AuthBlueberryUser extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'AuthBlueberryUser'
     */
    public $name = 'AuthBlueberryUser';

    /**
     * useTable property.
     *
     * @var string
     */
    public $useTable = false;
}
if (!class_exists('AppController')) {
    /**
     * AppController class.
     */
    class AppController extends Controller
    {
        /**
         * components property.
         */
        public $components = ['Blueberry'];

        /**
         * beforeRender method.
         */
        public function beforeRender()
        {
            echo $this->Blueberry->testName;
        }

        /**
         * header method.
         */
        public function header($header)
        {
            echo $header;
        }

        /**
         * _stop method.
         */
        public function _stop($status = 0)
        {
            echo 'Stopped with status: '.$status;
        }
    }
} elseif (!defined('APP_CONTROLLER_EXISTS')) {
    define('APP_CONTROLLER_EXISTS', true);
}
App::import('Core', ['Error', 'Controller']);

/**
 * TestErrorController class.
 */
class TestErrorController extends AppController
{
    /**
     * uses property.
     *
     * @var array
     */
    public $uses = [];

    /**
     * index method.
     */
    public function index()
    {
        $this->autoRender = false;

        return 'what up';
    }
}

/**
 * BlueberryController class.
 */
class BlueberryController extends AppController
{
    /**
     * name property.
     */
    public $name = 'BlueberryController';

    /**
     * uses property.
     */
    public $uses = ['AuthBlueberryUser'];

    /**
     * components property.
     */
    public $components = ['Auth'];
}

/**
 * MyCustomErrorHandler class.
 */
class MyCustomErrorHandler extends ErrorHandler
{
    /**
     * custom error message type.
     */
    public function missingWidgetThing()
    {
        echo 'widget thing is missing';
    }

    /**
     * stop method.
     */
    public function _stop()
    {
        return;
    }
}

/**
 * TestErrorHandler class.
 */
class TestErrorHandler extends ErrorHandler
{
    /**
     * stop method.
     */
    public function _stop()
    {
        return;
    }
}

/**
 * ErrorHandlerTest class.
 */
class ErrorHandlerTest extends CakeTestCase
{
    /**
     * skip method.
     */
    public function skip()
    {
        $this->skipIf(PHP_SAPI === 'cli', '%s Cannot be run from console');
    }

    /**
     * test that methods declared in an ErrorHandler subclass are not converted
     * into error404 when debug == 0.
     */
    public function testSubclassMethodsNotBeingConvertedToError()
    {
        $back = Configure::read('debug');
        Configure::write('debug', 2);
        ob_start();
        $ErrorHandler = new MyCustomErrorHandler('missingWidgetThing', ['message' => 'doh!']);
        $result = ob_get_clean();
        $this->assertEqual($result, 'widget thing is missing');

        Configure::write('debug', 0);
        ob_start();
        $ErrorHandler = new MyCustomErrorHandler('missingWidgetThing', ['message' => 'doh!']);
        $result = ob_get_clean();
        $this->assertEqual($result, 'widget thing is missing', 'Method declared in subclass converted to error404. %s');

        Configure::write('debug', 0);
        ob_start();
        $ErrorHandler = new MyCustomErrorHandler('missingController', [
            'className' => 'Missing', 'message' => 'Page not found',
        ]);
        $result = ob_get_clean();
        $this->assertPattern('/Not Found/', $result, 'Method declared in error handler not converted to error404. %s');

        Configure::write('debug', $back);
    }

    /**
     * testError method.
     */
    public function testError()
    {
        ob_start();
        $TestErrorHandler = new TestErrorHandler('error404', ['message' => 'Page not found']);
        ob_clean();
        ob_start();
        $TestErrorHandler->error([
            'code' => 404,
            'message' => 'Page not Found',
            'name' => "Couldn't find what you were looking for",
        ]);
        $result = ob_get_clean();
        $this->assertPattern("/<h2>Couldn't find what you were looking for<\/h2>/", $result);
        $this->assertPattern('/Page not Found/', $result);
    }

    /**
     * testError404 method.
     */
    public function testError404()
    {
        App::build([
            'views' => [TEST_CAKE_CORE_INCLUDE_PATH.'libs'.DS.'view'.DS],
        ], true);

        ob_start();
        $TestErrorHandler = new TestErrorHandler('error404', ['message' => 'Page not found', 'url' => '/test_error']);
        $result = ob_get_clean();
        $this->assertPattern('/<h2>Not Found<\/h2>/', $result);
        $this->assertPattern("/<strong>'\/test_error'<\/strong>/", $result);

        ob_start();
        $TestErrorHandler = new TestErrorHandler('error404', ['message' => 'Page not found']);
        ob_get_clean();
        ob_start();
        $TestErrorHandler->error404([
            'url' => 'pages/<span id=333>pink</span></id><script>document.body.style.background = t=document.getElementById(333).innerHTML;window.alert(t);</script>',
            'message' => 'Page not found',
        ]);
        $result = ob_get_clean();
        $this->assertNoPattern('#<script>#', $result);
        $this->assertNoPattern('#</script>#', $result);

        App::build();
    }

    /**
     * testError500 method.
     */
    public function testError500()
    {
        ob_start();
        $TestErrorHandler = new TestErrorHandler('error500', [
            'message' => 'An Internal Error Has Occurred',
        ]);
        $result = ob_get_clean();
        $this->assertPattern('/<h2>An Internal Error Has Occurred<\/h2>/', $result);

        ob_start();
        $TestErrorHandler = new TestErrorHandler('error500', [
            'message' => 'An Internal Error Has Occurred',
            'code' => '500',
        ]);
        $result = ob_get_clean();
        $this->assertPattern('/<h2>An Internal Error Has Occurred<\/h2>/', $result);
    }

    /**
     * testMissingController method.
     */
    public function testMissingController()
    {
        $this->skipIf(defined('APP_CONTROLLER_EXISTS'), '%s Need a non-existent AppController');

        ob_start();
        $TestErrorHandler = new TestErrorHandler('missingController', ['className' => 'PostsController']);
        $result = ob_get_clean();
        $this->assertPattern('/<h2>Missing Controller<\/h2>/', $result);
        $this->assertPattern('/<em>PostsController<\/em>/', $result);
        $this->assertPattern('/BlueberryComponent/', $result);
    }

    /**
     * testMissingAction method.
     */
    public function testMissingAction()
    {
        ob_start();
        $TestErrorHandler = new TestErrorHandler('missingAction', ['className' => 'PostsController', 'action' => 'index']);
        $result = ob_get_clean();
        $this->assertPattern('/<h2>Missing Method in PostsController<\/h2>/', $result);
        $this->assertPattern('/<em>PostsController::<\/em><em>index\(\)<\/em>/', $result);

        ob_start();
        $dispatcher = new BlueberryDispatcher('/blueberry/inexistent');
        $result = ob_get_clean();
        $this->assertPattern('/<h2>Missing Method in BlueberryController<\/h2>/', $result);
        $this->assertPattern('/<em>BlueberryController::<\/em><em>inexistent\(\)<\/em>/', $result);
        $this->assertNoPattern('/Location: (.*)\/users\/login/', $result);
        $this->assertNoPattern('/Stopped with status: 0/', $result);
    }

    /**
     * testPrivateAction method.
     */
    public function testPrivateAction()
    {
        ob_start();
        $TestErrorHandler = new TestErrorHandler('privateAction', ['className' => 'PostsController', 'action' => '_secretSauce']);
        $result = ob_get_clean();
        $this->assertPattern('/<h2>Private Method in PostsController<\/h2>/', $result);
        $this->assertPattern('/<em>PostsController::<\/em><em>_secretSauce\(\)<\/em>/', $result);
    }

    /**
     * testMissingTable method.
     */
    public function testMissingTable()
    {
        ob_start();
        $TestErrorHandler = new TestErrorHandler('missingTable', ['className' => 'Article', 'table' => 'articles']);
        $result = ob_get_clean();
        $this->assertPattern('/HTTP\/1\.0 500 Internal Server Error/', $result);
        $this->assertPattern('/<h2>Missing Database Table<\/h2>/', $result);
        $this->assertPattern('/table <em>articles<\/em> for model <em>Article<\/em>/', $result);
    }

    /**
     * testMissingDatabase method.
     */
    public function testMissingDatabase()
    {
        ob_start();
        $TestErrorHandler = new TestErrorHandler('missingDatabase', []);
        $result = ob_get_clean();
        $this->assertPattern('/HTTP\/1\.0 500 Internal Server Error/', $result);
        $this->assertPattern('/<h2>Missing Database Connection<\/h2>/', $result);
        $this->assertPattern('/Confirm you have created the file/', $result);
    }

    /**
     * testMissingView method.
     */
    public function testMissingView()
    {
        restore_error_handler();
        ob_start();
        $TestErrorHandler = new TestErrorHandler('missingView', ['className' => 'Pages', 'action' => 'display', 'file' => 'pages/about.ctp', 'base' => '']);
        $expected = ob_get_clean();
        set_error_handler('simpleTestErrorHandler');
        $this->assertPattern('/PagesController::/', $expected);
        $this->assertPattern("/pages\/about.ctp/", $expected);
    }

    /**
     * testMissingLayout method.
     */
    public function testMissingLayout()
    {
        restore_error_handler();
        ob_start();
        $TestErrorHandler = new TestErrorHandler('missingLayout', ['layout' => 'my_layout', 'file' => 'layouts/my_layout.ctp', 'base' => '']);
        $expected = ob_get_clean();
        set_error_handler('simpleTestErrorHandler');
        $this->assertPattern('/Missing Layout/', $expected);
        $this->assertPattern("/layouts\/my_layout.ctp/", $expected);
    }

    /**
     * testMissingConnection method.
     */
    public function testMissingConnection()
    {
        ob_start();
        $TestErrorHandler = new TestErrorHandler('missingConnection', ['className' => 'Article']);
        $result = ob_get_clean();
        $this->assertPattern('/<h2>Missing Database Connection<\/h2>/', $result);
        $this->assertPattern('/Article requires a database connection/', $result);
    }

    /**
     * testMissingHelperFile method.
     */
    public function testMissingHelperFile()
    {
        ob_start();
        $TestErrorHandler = new TestErrorHandler('missingHelperFile', ['helper' => 'MyCustom', 'file' => 'my_custom.php']);
        $result = ob_get_clean();
        $this->assertPattern('/<h2>Missing Helper File<\/h2>/', $result);
        $this->assertPattern('/Create the class below in file:/', $result);
        $this->assertPattern('/(\/|\\\)my_custom.php/', $result);
    }

    /**
     * testMissingHelperClass method.
     */
    public function testMissingHelperClass()
    {
        ob_start();
        $TestErrorHandler = new TestErrorHandler('missingHelperClass', ['helper' => 'MyCustom', 'file' => 'my_custom.php']);
        $result = ob_get_clean();
        $this->assertPattern('/<h2>Missing Helper Class<\/h2>/', $result);
        $this->assertPattern('/The helper class <em>MyCustomHelper<\/em> can not be found or does not exist./', $result);
        $this->assertPattern('/(\/|\\\)my_custom.php/', $result);
    }

    /**
     * test missingBehaviorFile method.
     */
    public function testMissingBehaviorFile()
    {
        ob_start();
        $TestErrorHandler = new TestErrorHandler('missingBehaviorFile', ['behavior' => 'MyCustom', 'file' => 'my_custom.php']);
        $result = ob_get_clean();
        $this->assertPattern('/<h2>Missing Behavior File<\/h2>/', $result);
        $this->assertPattern('/Create the class below in file:/', $result);
        $this->assertPattern('/(\/|\\\)my_custom.php/', $result);
    }

    /**
     * test MissingBehaviorClass method.
     */
    public function testMissingBehaviorClass()
    {
        ob_start();
        $TestErrorHandler = new TestErrorHandler('missingBehaviorClass', ['behavior' => 'MyCustom', 'file' => 'my_custom.php']);
        $result = ob_get_clean();
        $this->assertPattern('/<h2>Missing Behavior Class<\/h2>/', $result);
        $this->assertPattern('/The behavior class <em>MyCustomBehavior<\/em> can not be found or does not exist./', $result);
        $this->assertPattern('/(\/|\\\)my_custom.php/', $result);
    }

    /**
     * testMissingComponentFile method.
     */
    public function testMissingComponentFile()
    {
        ob_start();
        $TestErrorHandler = new TestErrorHandler('missingComponentFile', ['className' => 'PostsController', 'component' => 'Sidebox', 'file' => 'sidebox.php']);
        $result = ob_get_clean();
        $this->assertPattern('/<h2>Missing Component File<\/h2>/', $result);
        $this->assertPattern('/Create the class <em>SideboxComponent<\/em> in file:/', $result);
        $this->assertPattern('/(\/|\\\)sidebox.php/', $result);
    }

    /**
     * testMissingComponentClass method.
     */
    public function testMissingComponentClass()
    {
        ob_start();
        $TestErrorHandler = new TestErrorHandler('missingComponentClass', ['className' => 'PostsController', 'component' => 'Sidebox', 'file' => 'sidebox.php']);
        $result = ob_get_clean();
        $this->assertPattern('/<h2>Missing Component Class<\/h2>/', $result);
        $this->assertPattern('/Create the class <em>SideboxComponent<\/em> in file:/', $result);
        $this->assertPattern('/(\/|\\\)sidebox.php/', $result);
    }

    /**
     * testMissingModel method.
     */
    public function testMissingModel()
    {
        ob_start();
        $TestErrorHandler = new TestErrorHandler('missingModel', ['className' => 'Article', 'file' => 'article.php']);
        $result = ob_get_clean();
        $this->assertPattern('/<h2>Missing Model<\/h2>/', $result);
        $this->assertPattern('/<em>Article<\/em> could not be found./', $result);
        $this->assertPattern('/(\/|\\\)article.php/', $result);
    }

    /**
     * testing that having a code => 500 in the cakeError call makes an
     * internal server error.
     */
    public function testThatCode500Works()
    {
        Configure::write('debug', 0);
        ob_start();
        $TestErrorHandler = new TestErrorHandler('missingTable', [
            'className' => 'Article',
            'table' => 'articles',
            'code' => 500,
        ]);
        $result = ob_get_clean();
        $this->assertPattern('/<h2>An Internal Error Has Occurred<\/h2>/', $result);
    }
}
