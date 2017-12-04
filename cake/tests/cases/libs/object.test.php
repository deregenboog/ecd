<?php
/**
 * ObjectTest file.
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
App::import('Core', ['Object', 'Controller', 'Model']);

/**
 * RequestActionPost class.
 */
class RequestActionPost extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'ControllerPost'
     */
    public $name = 'RequestActionPost';

    /**
     * useTable property.
     *
     * @var string 'posts'
     */
    public $useTable = 'posts';
}

/**
 * RequestActionController class.
 */
class RequestActionController extends Controller
{
    /**
     * uses property.
     *
     * @var array
     */
    public $uses = ['RequestActionPost'];

    /**
     * test_request_action method.
     */
    public function test_request_action()
    {
        return 'This is a test';
    }

    /**
     * another_ra_test method.
     *
     * @param mixed $id
     * @param mixed $other
     */
    public function another_ra_test($id, $other)
    {
        return $id + $other;
    }

    /**
     * normal_request_action method.
     */
    public function normal_request_action()
    {
        return 'Hello World';
    }

    /**
     * returns $this->here.
     */
    public function return_here()
    {
        return $this->here;
    }

    /**
     * paginate_request_action method.
     */
    public function paginate_request_action()
    {
        $data = $this->paginate();

        return true;
    }

    /**
     * post pass, testing post passing.
     *
     * @return array
     */
    public function post_pass()
    {
        return $this->data;
    }

    /**
     * test param passing and parsing.
     *
     * @return array
     */
    public function params_pass()
    {
        return $this->params;
    }
}

/**
 * RequestActionPersistentController class.
 */
class RequestActionPersistentController extends Controller
{
    /**
     * uses property.
     *
     * @var array
     */
    public $uses = ['PersisterOne'];

    /**
     * persistModel property.
     *
     * @var array
     */
    public $persistModel = true;

    /**
     * post pass, testing post passing.
     *
     * @return array
     */
    public function index()
    {
        return 'This is a test';
    }
}

/**
 * TestObject class.
 */
class TestObject extends Object
{
    /**
     * firstName property.
     *
     * @var string 'Joel'
     */
    public $firstName = 'Joel';

    /**
     * lastName property.
     *
     * @var string 'Moss'
     */
    public $lastName = 'Moss';

    /**
     * methodCalls property.
     *
     * @var array
     */
    public $methodCalls = [];

    /**
     * emptyMethod method.
     */
    public function emptyMethod()
    {
        $this->methodCalls[] = 'emptyMethod';
    }

    /**
     * oneParamMethod method.
     *
     * @param mixed $param
     */
    public function oneParamMethod($param)
    {
        $this->methodCalls[] = ['oneParamMethod' => [$param]];
    }

    /**
     * twoParamMethod method.
     *
     * @param mixed $param
     * @param mixed $param2
     */
    public function twoParamMethod($param, $param2)
    {
        $this->methodCalls[] = ['twoParamMethod' => [$param, $param2]];
    }

    /**
     * threeParamMethod method.
     *
     * @param mixed $param
     * @param mixed $param2
     * @param mixed $param3
     */
    public function threeParamMethod($param, $param2, $param3)
    {
        $this->methodCalls[] = ['threeParamMethod' => [$param, $param2, $param3]];
    }

    /**
     * fourParamMethod method.
     *
     * @param mixed $param
     * @param mixed $param2
     * @param mixed $param3
     * @param mixed $param4
     */
    public function fourParamMethod($param, $param2, $param3, $param4)
    {
        $this->methodCalls[] = ['fourParamMethod' => [$param, $param2, $param3, $param4]];
    }

    /**
     * fiveParamMethod method.
     *
     * @param mixed $param
     * @param mixed $param2
     * @param mixed $param3
     * @param mixed $param4
     * @param mixed $param5
     */
    public function fiveParamMethod($param, $param2, $param3, $param4, $param5)
    {
        $this->methodCalls[] = ['fiveParamMethod' => [$param, $param2, $param3, $param4, $param5]];
    }

    /**
     * crazyMethod method.
     *
     * @param mixed $param
     * @param mixed $param2
     * @param mixed $param3
     * @param mixed $param4
     * @param mixed $param5
     * @param mixed $param6
     * @param mixed $param7
     */
    public function crazyMethod($param, $param2, $param3, $param4, $param5, $param6, $param7 = null)
    {
        $this->methodCalls[] = ['crazyMethod' => [$param, $param2, $param3, $param4, $param5, $param6, $param7]];
    }

    /**
     * methodWithOptionalParam method.
     *
     * @param mixed $param
     */
    public function methodWithOptionalParam($param = null)
    {
        $this->methodCalls[] = ['methodWithOptionalParam' => [$param]];
    }

    /**
     * testPersist.
     */
    public function testPersist($name, $return = null, &$object, $type = null)
    {
        return $this->_persist($name, $return, $object, $type);
    }
}

/**
 * ObjectTestModel class.
 */
class ObjectTestModel extends CakeTestModel
{
    public $useTable = false;
    public $name = 'ObjectTestModel';
}

/**
 * Object Test class.
 */
class ObjectTest extends CakeTestCase
{
    /**
     * fixtures.
     *
     * @var string
     */
    public $fixtures = ['core.post', 'core.test_plugin_comment', 'core.comment'];

    /**
     * setUp method.
     */
    public function setUp()
    {
        $this->object = new TestObject();
    }

    /**
     * tearDown method.
     */
    public function tearDown()
    {
        unset($this->object);
    }

    /**
     * endTest.
     */
    public function endTest()
    {
        App::build();
    }

    /**
     * testLog method.
     */
    public function testLog()
    {
        @unlink(LOGS.'error.log');
        $this->assertTrue($this->object->log('Test warning 1'));
        $this->assertTrue($this->object->log(['Test' => 'warning 2']));
        $result = file(LOGS.'error.log');
        $this->assertPattern('/^2[0-9]{3}-[0-9]+-[0-9]+ [0-9]+:[0-9]+:[0-9]+ Error: Test warning 1$/', $result[0]);
        $this->assertPattern('/^2[0-9]{3}-[0-9]+-[0-9]+ [0-9]+:[0-9]+:[0-9]+ Error: Array$/', $result[1]);
        $this->assertPattern('/^\($/', $result[2]);
        $this->assertPattern('/\[Test\] => warning 2$/', $result[3]);
        $this->assertPattern('/^\)$/', $result[4]);
        unlink(LOGS.'error.log');

        @unlink(LOGS.'error.log');
        $this->assertTrue($this->object->log('Test warning 1', LOG_WARNING));
        $this->assertTrue($this->object->log(['Test' => 'warning 2'], LOG_WARNING));
        $result = file(LOGS.'error.log');
        $this->assertPattern('/^2[0-9]{3}-[0-9]+-[0-9]+ [0-9]+:[0-9]+:[0-9]+ Warning: Test warning 1$/', $result[0]);
        $this->assertPattern('/^2[0-9]{3}-[0-9]+-[0-9]+ [0-9]+:[0-9]+:[0-9]+ Warning: Array$/', $result[1]);
        $this->assertPattern('/^\($/', $result[2]);
        $this->assertPattern('/\[Test\] => warning 2$/', $result[3]);
        $this->assertPattern('/^\)$/', $result[4]);
        unlink(LOGS.'error.log');
    }

    /**
     * testSet method.
     */
    public function testSet()
    {
        $this->object->_set('a string');
        $this->assertEqual($this->object->firstName, 'Joel');

        $this->object->_set(['firstName']);
        $this->assertEqual($this->object->firstName, 'Joel');

        $this->object->_set(['firstName' => 'Ashley']);
        $this->assertEqual($this->object->firstName, 'Ashley');

        $this->object->_set(['firstName' => 'Joel', 'lastName' => 'Moose']);
        $this->assertEqual($this->object->firstName, 'Joel');
        $this->assertEqual($this->object->lastName, 'Moose');
    }

    /**
     * testPersist method.
     */
    public function testPersist()
    {
        ClassRegistry::flush();

        $cacheDisable = Configure::read('Cache.disable');
        Configure::write('Cache.disable', false);
        @unlink(CACHE.'persistent'.DS.'testmodel.php');
        $test = new stdClass();
        $this->assertFalse($this->object->testPersist('TestModel', null, $test));
        $this->assertFalse($this->object->testPersist('TestModel', true, $test));
        $this->assertTrue($this->object->testPersist('TestModel', null, $test));
        $this->assertTrue(file_exists(CACHE.'persistent'.DS.'testmodel.php'));
        $this->assertTrue($this->object->testPersist('TestModel', true, $test));
        $this->assertEqual($this->object->TestModel, $test);

        @unlink(CACHE.'persistent'.DS.'testmodel.php');

        $model = new ObjectTestModel();
        $expected = ClassRegistry::keys();

        ClassRegistry::flush();
        $data = ['object_test_model' => $model];
        $this->assertFalse($this->object->testPersist('ObjectTestModel', true, $data));
        $this->assertTrue(file_exists(CACHE.'persistent'.DS.'objecttestmodel.php'));

        $this->object->testPersist('ObjectTestModel', true, $model, 'registry');

        $result = ClassRegistry::keys();
        $this->assertEqual($result, $expected);

        $newModel = ClassRegistry::getObject('object_test_model');
        $this->assertEqual('ObjectTestModel', $newModel->name);

        @unlink(CACHE.'persistent'.DS.'objecttestmodel.php');

        Configure::write('Cache.disable', $cacheDisable);
    }

    /**
     * testPersistWithRequestAction method.
     */
    public function testPersistWithBehavior()
    {
        ClassRegistry::flush();

        $cacheDisable = Configure::read('Cache.disable');
        Configure::write('Cache.disable', false);

        App::build([
            'models' => [TEST_CAKE_CORE_INCLUDE_PATH.'tests'.DS.'test_app'.DS.'models'.DS],
            'plugins' => [TEST_CAKE_CORE_INCLUDE_PATH.'tests'.DS.'test_app'.DS.'plugins'.DS],
            'behaviors' => [TEST_CAKE_CORE_INCLUDE_PATH.'tests'.DS.'test_app'.DS.'models'.DS.'behaviors'.DS],
        ], true);

        $this->assertFalse(class_exists('PersisterOneBehaviorBehavior'));
        $this->assertFalse(class_exists('PersisterTwoBehaviorBehavior'));
        $this->assertFalse(class_exists('TestPluginPersisterBehavior'));
        $this->assertFalse(class_exists('TestPluginAuthors'));

        $Controller = new RequestActionPersistentController();
        $Controller->persistModel = true;
        $Controller->constructClasses();

        $this->assertTrue(file_exists(CACHE.'persistent'.DS.'persisterone.php'));
        $this->assertTrue(file_exists(CACHE.'persistent'.DS.'persisteroneregistry.php'));

        $contents = file_get_contents(CACHE.'persistent'.DS.'persisteroneregistry.php');
        $contents = str_replace('"PersisterOne"', '"PersisterTwo"', $contents);
        $contents = str_replace('persister_one', 'persister_two', $contents);
        $contents = str_replace('test_plugin_comment', 'test_plugin_authors', $contents);
        $result = file_put_contents(CACHE.'persistent'.DS.'persisteroneregistry.php', $contents);

        $this->assertTrue(class_exists('PersisterOneBehaviorBehavior'));
        $this->assertTrue(class_exists('TestPluginPersisterOneBehavior'));
        $this->assertTrue(class_exists('TestPluginComment'));
        $this->assertFalse(class_exists('PersisterTwoBehaviorBehavior'));
        $this->assertFalse(class_exists('TestPluginPersisterTwoBehavior'));
        $this->assertFalse(class_exists('TestPluginAuthors'));

        $Controller = new RequestActionPersistentController();
        $Controller->persistModel = true;
        $Controller->constructClasses();

        $this->assertTrue(class_exists('PersisterOneBehaviorBehavior'));
        $this->assertTrue(class_exists('PersisterTwoBehaviorBehavior'));
        $this->assertTrue(class_exists('TestPluginPersisterTwoBehavior'));
        $this->assertTrue(class_exists('TestPluginAuthors'));

        @unlink(CACHE.'persistent'.DS.'persisterone.php');
        @unlink(CACHE.'persistent'.DS.'persisteroneregistry.php');
    }

    /**
     * testPersistWithBehaviorAndRequestAction method.
     *
     * @see testPersistWithBehavior
     */
    public function testPersistWithBehaviorAndRequestAction()
    {
        ClassRegistry::flush();

        $cacheDisable = Configure::read('Cache.disable');
        Configure::write('Cache.disable', false);

        $this->assertFalse(class_exists('ContainableBehavior'));

        App::build([
            'models' => [TEST_CAKE_CORE_INCLUDE_PATH.'tests'.DS.'test_app'.DS.'models'.DS],
            'behaviors' => [TEST_CAKE_CORE_INCLUDE_PATH.'tests'.DS.'test_app'.DS.'models'.DS.'behaviors'.DS],
        ], true);

        $this->assertFalse(class_exists('PersistOneBehaviorBehavior'));
        $this->assertFalse(class_exists('PersistTwoBehaviorBehavior'));

        $Controller = new RequestActionPersistentController();
        $Controller->persistModel = true;
        $Controller->constructClasses();

        $this->assertTrue(file_exists(CACHE.'persistent'.DS.'persisterone.php'));
        $this->assertTrue(file_exists(CACHE.'persistent'.DS.'persisteroneregistry.php'));

        $keys = ClassRegistry::keys();
        $this->assertEqual($keys, [
            'persister_one',
            'comment',
            'test_plugin_comment',
            'test_plugin.test_plugin_comment',
            'persister_one_behavior_behavior',
            'test_plugin_persister_one_behavior',
            'test_plugin.test_plugin_persister_one_behavior',
        ]);

        ob_start();
        $Controller->set('content_for_layout', 'cool');
        $Controller->render('index', 'ajax', '/layouts/ajax');
        $result = ob_get_clean();

        $keys = ClassRegistry::keys();
        $this->assertEqual($keys, [
            'persister_one',
            'comment',
            'test_plugin_comment',
            'test_plugin.test_plugin_comment',
            'persister_one_behavior_behavior',
            'test_plugin_persister_one_behavior',
            'test_plugin.test_plugin_persister_one_behavior',
            'view',
        ]);
        $result = $this->object->requestAction('/request_action_persistent/index');
        $expected = 'This is a test';
        $this->assertEqual($result, $expected);

        @unlink(CACHE.'persistent'.DS.'persisterone.php');
        @unlink(CACHE.'persistent'.DS.'persisteroneregistry.php');

        $Controller = new RequestActionPersistentController();
        $Controller->persistModel = true;
        $Controller->constructClasses();

        @unlink(CACHE.'persistent'.DS.'persisterone.php');
        @unlink(CACHE.'persistent'.DS.'persisteroneregistry.php');

        Configure::write('Cache.disable', $cacheDisable);
    }

    /**
     * testToString method.
     */
    public function testToString()
    {
        $result = strtolower($this->object->toString());
        $this->assertEqual($result, 'testobject');
    }

    /**
     * testMethodDispatching method.
     */
    public function testMethodDispatching()
    {
        $this->object->emptyMethod();
        $expected = ['emptyMethod'];
        $this->assertIdentical($this->object->methodCalls, $expected);

        $this->object->oneParamMethod('Hello');
        $expected[] = ['oneParamMethod' => ['Hello']];
        $this->assertIdentical($this->object->methodCalls, $expected);

        $this->object->twoParamMethod(true, false);
        $expected[] = ['twoParamMethod' => [true, false]];
        $this->assertIdentical($this->object->methodCalls, $expected);

        $this->object->threeParamMethod(true, false, null);
        $expected[] = ['threeParamMethod' => [true, false, null]];
        $this->assertIdentical($this->object->methodCalls, $expected);

        $this->object->crazyMethod(1, 2, 3, 4, 5, 6, 7);
        $expected[] = ['crazyMethod' => [1, 2, 3, 4, 5, 6, 7]];
        $this->assertIdentical($this->object->methodCalls, $expected);

        $this->object = new TestObject();
        $this->assertIdentical($this->object->methodCalls, []);

        $this->object->dispatchMethod('emptyMethod');
        $expected = ['emptyMethod'];
        $this->assertIdentical($this->object->methodCalls, $expected);

        $this->object->dispatchMethod('oneParamMethod', ['Hello']);
        $expected[] = ['oneParamMethod' => ['Hello']];
        $this->assertIdentical($this->object->methodCalls, $expected);

        $this->object->dispatchMethod('twoParamMethod', [true, false]);
        $expected[] = ['twoParamMethod' => [true, false]];
        $this->assertIdentical($this->object->methodCalls, $expected);

        $this->object->dispatchMethod('threeParamMethod', [true, false, null]);
        $expected[] = ['threeParamMethod' => [true, false, null]];
        $this->assertIdentical($this->object->methodCalls, $expected);

        $this->object->dispatchMethod('fourParamMethod', [1, 2, 3, 4]);
        $expected[] = ['fourParamMethod' => [1, 2, 3, 4]];
        $this->assertIdentical($this->object->methodCalls, $expected);

        $this->object->dispatchMethod('fiveParamMethod', [1, 2, 3, 4, 5]);
        $expected[] = ['fiveParamMethod' => [1, 2, 3, 4, 5]];
        $this->assertIdentical($this->object->methodCalls, $expected);

        $this->object->dispatchMethod('crazyMethod', [1, 2, 3, 4, 5, 6, 7]);
        $expected[] = ['crazyMethod' => [1, 2, 3, 4, 5, 6, 7]];
        $this->assertIdentical($this->object->methodCalls, $expected);

        $this->object->dispatchMethod('methodWithOptionalParam', ['Hello']);
        $expected[] = ['methodWithOptionalParam' => ['Hello']];
        $this->assertIdentical($this->object->methodCalls, $expected);

        $this->object->dispatchMethod('methodWithOptionalParam');
        $expected[] = ['methodWithOptionalParam' => [null]];
        $this->assertIdentical($this->object->methodCalls, $expected);
    }

    /**
     * testRequestAction method.
     */
    public function testRequestAction()
    {
        App::build([
            'models' => [TEST_CAKE_CORE_INCLUDE_PATH.'tests'.DS.'test_app'.DS.'models'.DS],
            'views' => [TEST_CAKE_CORE_INCLUDE_PATH.'tests'.DS.'test_app'.DS.'views'.DS],
            'controllers' => [TEST_CAKE_CORE_INCLUDE_PATH.'tests'.DS.'test_app'.DS.'controllers'.DS],
        ]);
        $result = $this->object->requestAction('');
        $this->assertFalse($result);

        $result = $this->object->requestAction('/request_action/test_request_action');
        $expected = 'This is a test';
        $this->assertEqual($result, $expected);

        $result = $this->object->requestAction('/request_action/another_ra_test/2/5');
        $expected = 7;
        $this->assertEqual($result, $expected);

        $result = $this->object->requestAction('/tests_apps/index', ['return']);
        $expected = 'This is the TestsAppsController index view';
        $this->assertEqual($result, $expected);

        $result = $this->object->requestAction('/tests_apps/some_method');
        $expected = 5;
        $this->assertEqual($result, $expected);

        $result = $this->object->requestAction('/request_action/paginate_request_action');
        $this->assertTrue($result);

        $result = $this->object->requestAction('/request_action/normal_request_action');
        $expected = 'Hello World';
        $this->assertEqual($result, $expected);

        App::build();
    }

    /**
     * test requestAction() and plugins.
     */
    public function testRequestActionPlugins()
    {
        App::build([
            'plugins' => [TEST_CAKE_CORE_INCLUDE_PATH.'tests'.DS.'test_app'.DS.'plugins'.DS],
        ]);
        App::objects('plugin', null, false);
        Router::reload();

        $result = $this->object->requestAction('/test_plugin/tests/index', ['return']);
        $expected = 'test plugin index';
        $this->assertEqual($result, $expected);

        $result = $this->object->requestAction('/test_plugin/tests/index/some_param', ['return']);
        $expected = 'test plugin index';
        $this->assertEqual($result, $expected);

        $result = $this->object->requestAction(
            ['controller' => 'tests', 'action' => 'index', 'plugin' => 'test_plugin'], ['return']
        );
        $expected = 'test plugin index';
        $this->assertEqual($result, $expected);

        $result = $this->object->requestAction('/test_plugin/tests/some_method');
        $expected = 25;
        $this->assertEqual($result, $expected);

        $result = $this->object->requestAction(
            ['controller' => 'tests', 'action' => 'some_method', 'plugin' => 'test_plugin']
        );
        $expected = 25;
        $this->assertEqual($result, $expected);

        App::build();
        App::objects('plugin', null, false);
    }

    /**
     * test requestAction() with arrays.
     */
    public function testRequestActionArray()
    {
        App::build([
            'models' => [TEST_CAKE_CORE_INCLUDE_PATH.'tests'.DS.'test_app'.DS.'models'.DS],
            'views' => [TEST_CAKE_CORE_INCLUDE_PATH.'tests'.DS.'test_app'.DS.'views'.DS],
            'controllers' => [TEST_CAKE_CORE_INCLUDE_PATH.'tests'.DS.'test_app'.DS.'controllers'.DS],
        ]);

        $result = $this->object->requestAction(
            ['controller' => 'request_action', 'action' => 'test_request_action']
        );
        $expected = 'This is a test';
        $this->assertEqual($result, $expected);

        $result = $this->object->requestAction(
            ['controller' => 'request_action', 'action' => 'another_ra_test'],
            ['pass' => ['5', '7']]
        );
        $expected = 12;
        $this->assertEqual($result, $expected);

        $result = $this->object->requestAction(
            ['controller' => 'tests_apps', 'action' => 'index'], ['return']
        );
        $expected = 'This is the TestsAppsController index view';
        $this->assertEqual($result, $expected);

        $result = $this->object->requestAction(['controller' => 'tests_apps', 'action' => 'some_method']);
        $expected = 5;
        $this->assertEqual($result, $expected);

        $result = $this->object->requestAction(
            ['controller' => 'request_action', 'action' => 'normal_request_action']
        );
        $expected = 'Hello World';
        $this->assertEqual($result, $expected);

        $result = $this->object->requestAction(
            ['controller' => 'request_action', 'action' => 'paginate_request_action']
        );
        $this->assertTrue($result);

        $result = $this->object->requestAction(
            ['controller' => 'request_action', 'action' => 'paginate_request_action'],
            ['pass' => [5], 'named' => ['param' => 'value']]
        );
        $this->assertTrue($result);

        App::build();
    }

    /**
     * Test that requestAction() is populating $this->params properly.
     */
    public function testRequestActionParamParseAndPass()
    {
        $result = $this->object->requestAction('/request_action/params_pass');
        $this->assertTrue(isset($result['url']['url']));
        $this->assertEqual($result['url']['url'], '/request_action/params_pass');
        $this->assertEqual($result['controller'], 'request_action');
        $this->assertEqual($result['action'], 'params_pass');
        $this->assertEqual($result['form'], []);
        $this->assertEqual($result['plugin'], null);

        $result = $this->object->requestAction('/request_action/params_pass/sort:desc/limit:5');
        $expected = ['sort' => 'desc', 'limit' => 5];
        $this->assertEqual($result['named'], $expected);

        $result = $this->object->requestAction(
            ['controller' => 'request_action', 'action' => 'params_pass'],
            ['named' => ['sort' => 'desc', 'limit' => 5]]
        );
        $this->assertEqual($result['named'], $expected);
    }

    /**
     * test requestAction and POST parameter passing, and not passing when url is an array.
     */
    public function testRequestActionPostPassing()
    {
        $_tmp = $_POST;

        $_POST = ['data' => [
            'item' => 'value',
        ]];
        $result = $this->object->requestAction(['controller' => 'request_action', 'action' => 'post_pass']);
        $expected = [];
        $this->assertEqual($expected, $result);

        $result = $this->object->requestAction(['controller' => 'request_action', 'action' => 'post_pass'], ['data' => $_POST['data']]);
        $expected = $_POST['data'];
        $this->assertEqual($expected, $result);

        $result = $this->object->requestAction('/request_action/post_pass');
        $expected = $_POST['data'];
        $this->assertEqual($expected, $result);

        $_POST = $_tmp;
    }

    /**
     * testCakeError.
     */
    public function testCakeError()
    {
    }
}
