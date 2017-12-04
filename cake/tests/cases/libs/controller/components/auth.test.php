<?php
/**
 * AuthComponentTest file.
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
 * @since         CakePHP(tm) v 1.2.0.5347
 *
 * @license       http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */
App::import('Component', ['Auth', 'Acl']);
App::import('Model', 'DbAcl');
App::import('Core', 'Xml');

Mock::generate('AclComponent', 'AuthTestMockAclComponent');

/**
 * TestAuthComponent class.
 */
class TestAuthComponent extends AuthComponent
{
    /**
     * testStop property.
     *
     * @var bool false
     */
    public $testStop = false;

    /**
     * Sets default login state.
     *
     * @var bool true
     */
    public $_loggedIn = true;

    /**
     * stop method.
     */
    public function _stop()
    {
        $this->testStop = true;
    }
}

/**
 * AuthUser class.
 */
class AuthUser extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'AuthUser'
     */
    public $name = 'AuthUser';

    /**
     * useDbConfig property.
     *
     * @var string 'test_suite'
     */
    public $useDbConfig = 'test_suite';

    /**
     * parentNode method.
     */
    public function parentNode()
    {
        return true;
    }

    /**
     * bindNode method.
     *
     * @param mixed $object
     */
    public function bindNode($object)
    {
        return 'Roles/Admin';
    }

    /**
     * isAuthorized method.
     *
     * @param mixed $user
     * @param mixed $controller
     * @param mixed $action
     */
    public function isAuthorized($user, $controller = null, $action = null)
    {
        if (!empty($user)) {
            return true;
        }

        return false;
    }
}

/**
 * AuthUserCustomField class.
 */
class AuthUserCustomField extends AuthUser
{
    /**
     * name property.
     *
     * @var string 'AuthUser'
     */
    public $name = 'AuthUserCustomField';
}

/**
 * UuidUser class.
 */
class UuidUser extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'AuthUser'
     */
    public $name = 'UuidUser';

    /**
     * useDbConfig property.
     *
     * @var string 'test_suite'
     */
    public $useDbConfig = 'test_suite';

    /**
     * useTable property.
     *
     * @var string 'uuid'
     */
    public $useTable = 'uuids';

    /**
     * parentNode method.
     */
    public function parentNode()
    {
        return true;
    }

    /**
     * bindNode method.
     *
     * @param mixed $object
     */
    public function bindNode($object)
    {
        return 'Roles/Admin';
    }

    /**
     * isAuthorized method.
     *
     * @param mixed $user
     * @param mixed $controller
     * @param mixed $action
     */
    public function isAuthorized($user, $controller = null, $action = null)
    {
        if (!empty($user)) {
            return true;
        }

        return false;
    }
}

/**
 * AuthTestController class.
 */
class AuthTestController extends Controller
{
    /**
     * name property.
     *
     * @var string 'AuthTest'
     */
    public $name = 'AuthTest';

    /**
     * uses property.
     *
     * @var array
     */
    public $uses = ['AuthUser'];

    /**
     * components property.
     *
     * @var array
     */
    public $components = ['Session', 'Auth', 'Acl'];

    /**
     * testUrl property.
     *
     * @var mixed null
     */
    public $testUrl = null;

    /**
     * construct method.
     */
    public function __construct()
    {
        $this->params = Router::parse('/auth_test');
        Router::setRequestInfo([$this->params, ['base' => null, 'here' => '/auth_test', 'webroot' => '/', 'passedArgs' => [], 'argSeparator' => ':', 'namedArgs' => []]]);
        parent::__construct();
    }

    /**
     * beforeFilter method.
     */
    public function beforeFilter()
    {
        $this->Auth->userModel = 'AuthUser';
    }

    /**
     * login method.
     */
    public function login()
    {
    }

    /**
     * admin_login method.
     */
    public function admin_login()
    {
    }

    /**
     * logout method.
     */
    public function logout()
    {
        // $this->redirect($this->Auth->logout());
    }

    /**
     * add method.
     */
    public function add()
    {
        echo 'add';
    }

    /**
     * add method.
     */
    public function camelCase()
    {
        echo 'camelCase';
    }

    /**
     * redirect method.
     *
     * @param mixed $url
     * @param mixed $status
     * @param mixed $exit
     */
    public function redirect($url, $status = null, $exit = true)
    {
        $this->testUrl = Router::url($url);

        return false;
    }

    /**
     * isAuthorized method.
     */
    public function isAuthorized()
    {
        if (isset($this->params['testControllerAuth'])) {
            return false;
        }

        return true;
    }

    /**
     * Mock delete method.
     *
     * @param mixed $url
     * @param mixed $status
     * @param mixed $exit
     */
    public function delete($id = null)
    {
        if (true !== $this->TestAuth->testStop && null !== $id) {
            echo 'Deleted Record: '.var_export($id, true);
        }
    }
}

/**
 * AjaxAuthController class.
 */
class AjaxAuthController extends Controller
{
    /**
     * name property.
     *
     * @var string 'AjaxAuth'
     */
    public $name = 'AjaxAuth';

    /**
     * components property.
     *
     * @var array
     */
    public $components = ['Session', 'TestAuth'];

    /**
     * uses property.
     *
     * @var array
     */
    public $uses = [];

    /**
     * testUrl property.
     *
     * @var mixed null
     */
    public $testUrl = null;

    /**
     * beforeFilter method.
     */
    public function beforeFilter()
    {
        $this->TestAuth->ajaxLogin = 'test_element';
        $this->TestAuth->userModel = 'AuthUser';
        $this->TestAuth->RequestHandler->ajaxLayout = 'ajax2';
    }

    /**
     * add method.
     */
    public function add()
    {
        if (true !== $this->TestAuth->testStop) {
            echo 'Added Record';
        }
    }

    /**
     * redirect method.
     *
     * @param mixed $url
     * @param mixed $status
     * @param mixed $exit
     */
    public function redirect($url, $status = null, $exit = true)
    {
        $this->testUrl = Router::url($url);

        return false;
    }
}

/**
 * AuthTest class.
 */
class AuthTest extends CakeTestCase
{
    /**
     * name property.
     *
     * @var string 'Auth'
     */
    public $name = 'Auth';

    /**
     * fixtures property.
     *
     * @var array
     */
    public $fixtures = ['core.uuid', 'core.auth_user', 'core.auth_user_custom_field', 'core.aro', 'core.aco', 'core.aros_aco', 'core.aco_action'];

    /**
     * initialized property.
     *
     * @var bool false
     */
    public $initialized = false;

    /**
     * startTest method.
     */
    public function startTest()
    {
        $this->_server = $_SERVER;
        $this->_env = $_ENV;

        $this->_securitySalt = Configure::read('Security.salt');
        Configure::write('Security.salt', 'JfIxfs2guVoUubWDYhG93b0qyJfIxfs2guwvniR2G0FgaC9mi');

        $this->_acl = Configure::read('Acl');
        Configure::write('Acl.database', 'test_suite');
        Configure::write('Acl.classname', 'DbAcl');

        $this->Controller = new AuthTestController();
        $this->Controller->Component->init($this->Controller);
        $this->Controller->Component->initialize($this->Controller);
        $this->Controller->beforeFilter();

        ClassRegistry::addObject('view', new View($this->Controller));

        $this->Controller->Session->delete('Auth');
        $this->Controller->Session->delete('Message.auth');

        Router::reload();

        $this->initialized = true;
    }

    /**
     * endTest method.
     */
    public function endTest()
    {
        $_SERVER = $this->_server;
        $_ENV = $this->_env;
        Configure::write('Acl', $this->_acl);
        Configure::write('Security.salt', $this->_securitySalt);

        $this->Controller->Session->delete('Auth');
        $this->Controller->Session->delete('Message.auth');
        ClassRegistry::flush();
        unset($this->Controller, $this->AuthUser);
    }

    /**
     * testNoAuth method.
     */
    public function testNoAuth()
    {
        $this->assertFalse($this->Controller->Auth->isAuthorized());
    }

    /**
     * testIsErrorOrTests.
     */
    public function testIsErrorOrTests()
    {
        $this->Controller->Auth->initialize($this->Controller);

        $this->Controller->name = 'CakeError';
        $this->assertTrue($this->Controller->Auth->startup($this->Controller));

        $this->Controller->name = 'Post';
        $this->Controller->params['action'] = 'thisdoesnotexist';
        $this->assertTrue($this->Controller->Auth->startup($this->Controller));

        $this->Controller->scaffold = null;
        $this->Controller->params['action'] = 'index';
        $this->assertFalse($this->Controller->Auth->startup($this->Controller));
    }

    /**
     * testIdentify method.
     */
    public function testIdentify()
    {
        $this->AuthUser = new AuthUser();
        $user['id'] = 1;
        $user['username'] = 'mariano';
        $user['password'] = Security::hash(Configure::read('Security.salt').'cake');
        $this->AuthUser->save($user, false);

        $this->Controller->Auth->initialize($this->Controller);
        $this->Controller->Auth->userModel = 'AuthUser';
        $this->Controller->Auth->startup($this->Controller);
        $this->assertTrue($this->Controller->Auth->identify($user));
    }

    /**
     * testIdentifyWithConditions method.
     */
    public function testIdentifyWithConditions()
    {
        $this->AuthUser = new AuthUser();
        $user['id'] = 1;
        $user['username'] = 'mariano';
        $user['password'] = Security::hash(Configure::read('Security.salt').'cake');
        $this->AuthUser->save($user, false);

        $this->Controller->Auth->initialize($this->Controller);
        $this->Controller->Auth->startup($this->Controller);
        $this->Controller->Auth->userModel = 'AuthUser';

        $this->assertFalse($this->Controller->Auth->identify($user, ['AuthUser.id >' => 2]));

        $this->Controller->Auth->userScope = ['id >' => 2];
        $this->assertFalse($this->Controller->Auth->identify($user));
        $this->assertTrue($this->Controller->Auth->identify($user, false));
    }

    /**
     * testLogin method.
     */
    public function testLogin()
    {
        $this->AuthUser = new AuthUser();
        $user['id'] = 1;
        $user['username'] = 'mariano';
        $user['password'] = Security::hash(Configure::read('Security.salt').'cake');
        $this->AuthUser->save($user, false);

        $authUser = $this->AuthUser->find();

        $this->Controller->data['AuthUser']['username'] = $authUser['AuthUser']['username'];
        $this->Controller->data['AuthUser']['password'] = 'cake';

        $this->Controller->params = Router::parse('auth_test/login');
        $this->Controller->params['url']['url'] = 'auth_test/login';

        $this->Controller->Auth->initialize($this->Controller);

        $this->Controller->Auth->loginAction = 'auth_test/login';
        $this->Controller->Auth->userModel = 'AuthUser';

        $this->Controller->Auth->startup($this->Controller);
        $user = $this->Controller->Auth->user();
        $expected = ['AuthUser' => [
            'id' => 1, 'username' => 'mariano', 'created' => '2007-03-17 01:16:23', 'updated' => date('Y-m-d H:i:s'),
        ]];
        $this->assertEqual($user, $expected);
        $this->Controller->Session->delete('Auth');

        $this->Controller->data['AuthUser']['username'] = 'blah';
        $this->Controller->data['AuthUser']['password'] = '';

        $this->Controller->Auth->startup($this->Controller);

        $user = $this->Controller->Auth->user();
        $this->assertFalse($user);
        $this->Controller->Session->delete('Auth');

        $this->Controller->data['AuthUser']['username'] = 'now() or 1=1 --';
        $this->Controller->data['AuthUser']['password'] = '';

        $this->Controller->Auth->startup($this->Controller);

        $user = $this->Controller->Auth->user();
        $this->assertFalse($user);
        $this->Controller->Session->delete('Auth');

        $this->Controller->data['AuthUser']['username'] = 'now() or 1=1 # something';
        $this->Controller->data['AuthUser']['password'] = '';

        $this->Controller->Auth->startup($this->Controller);

        $user = $this->Controller->Auth->user();
        $this->assertFalse($user);
        $this->Controller->Session->delete('Auth');

        $this->Controller->Auth->userModel = 'UuidUser';
        $this->Controller->Auth->login('47c36f9c-bc00-4d17-9626-4e183ca6822b');

        $user = $this->Controller->Auth->user();
        $expected = ['UuidUser' => [
            'id' => '47c36f9c-bc00-4d17-9626-4e183ca6822b', 'title' => 'Unique record 1', 'count' => 2, 'created' => '2008-03-13 01:16:23', 'updated' => '2008-03-13 01:18:31',
        ]];
        $this->assertEqual($user, $expected);
        $this->Controller->Session->delete('Auth');
    }

    /**
     * test that being redirected to the login page, with no post data does
     * not set the session value.  Saving the session value in this circumstance
     * can cause the user to be redirected to an already public page.
     */
    public function testLoginActionNotSettingAuthRedirect()
    {
        $_referer = $_SERVER['HTTP_REFERER'];
        $_SERVER['HTTP_REFERER'] = '/pages/display/about';

        $this->Controller->data = [];
        $this->Controller->params = Router::parse('auth_test/login');
        $this->Controller->params['url']['url'] = 'auth_test/login';
        $this->Controller->Session->delete('Auth');

        $this->Controller->Auth->loginRedirect = '/users/dashboard';
        $this->Controller->Auth->loginAction = 'auth_test/login';
        $this->Controller->Auth->userModel = 'AuthUser';

        $this->Controller->Auth->startup($this->Controller);
        $redirect = $this->Controller->Session->read('Auth.redirect');
        $this->assertNull($redirect);
    }

    /**
     * testAuthorizeFalse method.
     */
    public function testAuthorizeFalse()
    {
        $this->AuthUser = new AuthUser();
        $user = $this->AuthUser->find();
        $this->Controller->Session->write('Auth', $user);
        $this->Controller->Auth->userModel = 'AuthUser';
        $this->Controller->Auth->authorize = false;
        $this->Controller->params = Router::parse('auth_test/add');
        $result = $this->Controller->Auth->startup($this->Controller);
        $this->assertTrue($result);

        $this->Controller->Session->delete('Auth');
        $result = $this->Controller->Auth->startup($this->Controller);
        $this->assertFalse($result);
        $this->assertTrue($this->Controller->Session->check('Message.auth'));

        $this->Controller->params = Router::parse('auth_test/camelCase');
        $result = $this->Controller->Auth->startup($this->Controller);
        $this->assertFalse($result);
    }

    /**
     * testAuthorizeController method.
     */
    public function testAuthorizeController()
    {
        $this->AuthUser = new AuthUser();
        $user = $this->AuthUser->find();
        $this->Controller->Session->write('Auth', $user);
        $this->Controller->Auth->userModel = 'AuthUser';
        $this->Controller->Auth->authorize = 'controller';
        $this->Controller->params = Router::parse('auth_test/add');
        $result = $this->Controller->Auth->startup($this->Controller);
        $this->assertTrue($result);

        $this->Controller->params['testControllerAuth'] = 1;
        $result = $this->Controller->Auth->startup($this->Controller);
        $this->assertTrue($this->Controller->Session->check('Message.auth'));
        $this->assertFalse($result);

        $this->Controller->Session->delete('Auth');
    }

    /**
     * testAuthorizeModel method.
     */
    public function testAuthorizeModel()
    {
        $this->AuthUser = new AuthUser();
        $user = $this->AuthUser->find();
        $this->Controller->Session->write('Auth', $user);

        $this->Controller->params['controller'] = 'auth_test';
        $this->Controller->params['action'] = 'add';
        $this->Controller->Auth->userModel = 'AuthUser';
        $this->Controller->Auth->initialize($this->Controller);
        $this->Controller->Auth->authorize = ['model' => 'AuthUser'];
        $result = $this->Controller->Auth->startup($this->Controller);
        $this->assertTrue($result);

        $this->Controller->Session->delete('Auth');
        $this->Controller->Auth->startup($this->Controller);
        $this->assertTrue($this->Controller->Session->check('Message.auth'));
        $result = $this->Controller->Auth->isAuthorized();
        $this->assertFalse($result);
    }

    /**
     * testAuthorizeCrud method.
     */
    public function testAuthorizeCrud()
    {
        $this->AuthUser = new AuthUser();
        $user = $this->AuthUser->find();
        $this->Controller->Session->write('Auth', $user);

        $this->Controller->params['controller'] = 'auth_test';
        $this->Controller->params['action'] = 'add';

        $this->Controller->Acl->name = 'DbAclTest';

        $this->Controller->Acl->Aro->id = null;
        $this->Controller->Acl->Aro->create(['alias' => 'Roles']);
        $result = $this->Controller->Acl->Aro->save();
        $this->assertTrue($result);

        $parent = $this->Controller->Acl->Aro->id;

        $this->Controller->Acl->Aro->create(['parent_id' => $parent, 'alias' => 'Admin']);
        $result = $this->Controller->Acl->Aro->save();
        $this->assertTrue($result);

        $parent = $this->Controller->Acl->Aro->id;

        $this->Controller->Acl->Aro->create([
            'model' => 'AuthUser', 'parent_id' => $parent, 'foreign_key' => 1, 'alias' => 'mariano',
        ]);
        $result = $this->Controller->Acl->Aro->save();
        $this->assertTrue($result);

        $this->Controller->Acl->Aco->create(['alias' => 'Root']);
        $result = $this->Controller->Acl->Aco->save();
        $this->assertTrue($result);

        $parent = $this->Controller->Acl->Aco->id;

        $this->Controller->Acl->Aco->create(['parent_id' => $parent, 'alias' => 'AuthTest']);
        $result = $this->Controller->Acl->Aco->save();
        $this->assertTrue($result);

        $this->Controller->Acl->allow('Roles/Admin', 'Root');
        $this->Controller->Acl->allow('Roles/Admin', 'Root/AuthTest');

        $this->Controller->Auth->initialize($this->Controller);

        $this->Controller->Auth->userModel = 'AuthUser';
        $this->Controller->Auth->authorize = 'crud';
        $this->Controller->Auth->actionPath = 'Root/';

        $this->Controller->Auth->startup($this->Controller);
        $this->assertTrue($this->Controller->Auth->isAuthorized());

        $this->Controller->Session->delete('Auth');
        $this->Controller->Auth->startup($this->Controller);
        $this->assertTrue($this->Controller->Session->check('Message.auth'));
    }

    /**
     * test authorize = 'actions' setting.
     */
    public function testAuthorizeActions()
    {
        $this->AuthUser = new AuthUser();
        $user = $this->AuthUser->find();
        $this->Controller->Session->write('Auth', $user);
        $this->Controller->params['controller'] = 'auth_test';
        $this->Controller->params['action'] = 'add';

        $this->Controller->Acl = new AuthTestMockAclComponent();
        $this->Controller->Acl->setReturnValue('check', true);

        $this->Controller->Auth->initialize($this->Controller);

        $this->Controller->Auth->userModel = 'AuthUser';
        $this->Controller->Auth->authorize = 'actions';
        $this->Controller->Auth->actionPath = 'Root/';

        $this->Controller->Acl->expectAt(0, 'check', [
            $user, 'Root/AuthTest/add',
        ]);

        $this->Controller->Auth->startup($this->Controller);
        $this->assertTrue($this->Controller->Auth->isAuthorized());
    }

    /**
     * Tests that deny always takes precedence over allow.
     */
    public function testAllowDenyAll()
    {
        $this->Controller->Auth->initialize($this->Controller);

        $this->Controller->Auth->allow('*');
        $this->Controller->Auth->deny('add', 'camelcase');

        $this->Controller->params['action'] = 'delete';
        $this->assertTrue($this->Controller->Auth->startup($this->Controller));

        $this->Controller->params['action'] = 'add';
        $this->assertFalse($this->Controller->Auth->startup($this->Controller));

        $this->Controller->params['action'] = 'Add';
        $this->assertFalse($this->Controller->Auth->startup($this->Controller));

        $this->Controller->params['action'] = 'camelCase';
        $this->assertFalse($this->Controller->Auth->startup($this->Controller));

        $this->Controller->Auth->allow('*');
        $this->Controller->Auth->deny(['add', 'camelcase']);

        $this->Controller->params['action'] = 'camelCase';
        $this->assertFalse($this->Controller->Auth->startup($this->Controller));
    }

    /**
     * test the action() method.
     */
    public function testActionMethod()
    {
        $this->Controller->params['controller'] = 'auth_test';
        $this->Controller->params['action'] = 'add';

        $this->Controller->Auth->initialize($this->Controller);
        $this->Controller->Auth->actionPath = 'ROOT/';

        $result = $this->Controller->Auth->action();
        $this->assertEqual($result, 'ROOT/AuthTest/add');

        $result = $this->Controller->Auth->action(':controller');
        $this->assertEqual($result, 'ROOT/AuthTest');

        $result = $this->Controller->Auth->action(':controller');
        $this->assertEqual($result, 'ROOT/AuthTest');

        $this->Controller->params['plugin'] = 'test_plugin';
        $this->Controller->params['controller'] = 'auth_test';
        $this->Controller->params['action'] = 'add';
        $this->Controller->Auth->initialize($this->Controller);
        $result = $this->Controller->Auth->action();
        $this->assertEqual($result, 'ROOT/TestPlugin/AuthTest/add');
    }

    /**
     * test that deny() converts camel case inputs to lowercase.
     */
    public function testDenyWithCamelCaseMethods()
    {
        $this->Controller->Auth->initialize($this->Controller);
        $this->Controller->Auth->allow('*');
        $this->Controller->Auth->deny('add', 'camelCase');

        $url = '/auth_test/camelCase';
        $this->Controller->params = Router::parse($url);
        $this->Controller->params['url']['url'] = Router::normalize($url);

        $this->assertFalse($this->Controller->Auth->startup($this->Controller));
    }

    /**
     * test that allow() and allowedActions work with camelCase method names.
     */
    public function testAllowedActionsWithCamelCaseMethods()
    {
        $url = '/auth_test/camelCase';
        $this->Controller->params = Router::parse($url);
        $this->Controller->params['url']['url'] = Router::normalize($url);
        $this->Controller->Auth->initialize($this->Controller);
        $this->Controller->Auth->loginAction = ['controller' => 'AuthTest', 'action' => 'login'];
        $this->Controller->Auth->userModel = 'AuthUser';
        $this->Controller->Auth->allow('*');
        $result = $this->Controller->Auth->startup($this->Controller);
        $this->assertTrue($result, 'startup() should return true, as action is allowed. %s');

        $url = '/auth_test/camelCase';
        $this->Controller->params = Router::parse($url);
        $this->Controller->params['url']['url'] = Router::normalize($url);
        $this->Controller->Auth->initialize($this->Controller);
        $this->Controller->Auth->loginAction = ['controller' => 'AuthTest', 'action' => 'login'];
        $this->Controller->Auth->userModel = 'AuthUser';
        $this->Controller->Auth->allowedActions = ['delete', 'camelCase', 'add'];
        $result = $this->Controller->Auth->startup($this->Controller);
        $this->assertTrue($result, 'startup() should return true, as action is allowed. %s');

        $this->Controller->Auth->allowedActions = ['delete', 'add'];
        $result = $this->Controller->Auth->startup($this->Controller);
        $this->assertFalse($result, 'startup() should return false, as action is not allowed. %s');

        $url = '/auth_test/delete';
        $this->Controller->params = Router::parse($url);
        $this->Controller->params['url']['url'] = Router::normalize($url);
        $this->Controller->Auth->initialize($this->Controller);
        $this->Controller->Auth->loginAction = ['controller' => 'AuthTest', 'action' => 'login'];
        $this->Controller->Auth->userModel = 'AuthUser';

        $this->Controller->Auth->allow(['delete', 'add']);
        $result = $this->Controller->Auth->startup($this->Controller);
        $this->assertTrue($result, 'startup() should return true, as action is allowed. %s');
    }

    public function testAllowedActionsSetWithAllowMethod()
    {
        $url = '/auth_test/action_name';
        $this->Controller->params = Router::parse($url);
        $this->Controller->params['url']['url'] = Router::normalize($url);
        $this->Controller->Auth->initialize($this->Controller);
        $this->Controller->Auth->allow('action_name', 'anotherAction');
        $this->assertEqual($this->Controller->Auth->allowedActions, ['action_name', 'anotheraction']);
    }

    /**
     * testLoginRedirect method.
     */
    public function testLoginRedirect()
    {
        if (isset($_SERVER['HTTP_REFERER'])) {
            $backup = $_SERVER['HTTP_REFERER'];
        } else {
            $backup = null;
        }

        $_SERVER['HTTP_REFERER'] = false;

        $this->Controller->Session->write('Auth', [
            'AuthUser' => ['id' => '1', 'username' => 'nate'],
        ]);

        $this->Controller->params = Router::parse('users/login');
        $this->Controller->params['url']['url'] = 'users/login';
        $this->Controller->Auth->initialize($this->Controller);

        $this->Controller->Auth->userModel = 'AuthUser';
        $this->Controller->Auth->loginRedirect = [
            'controller' => 'pages', 'action' => 'display', 'welcome',
        ];
        $this->Controller->Auth->startup($this->Controller);
        $expected = Router::normalize($this->Controller->Auth->loginRedirect);
        $this->assertEqual($expected, $this->Controller->Auth->redirect());

        $this->Controller->Session->delete('Auth');

        //empty referer no session
        $_SERVER['HTTP_REFERER'] = false;
        $_ENV['HTTP_REFERER'] = false;
        putenv('HTTP_REFERER=');
        $url = '/posts/view/1';

        $this->Controller->Session->write('Auth', [
            'AuthUser' => ['id' => '1', 'username' => 'nate'], ]
        );
        $this->Controller->testUrl = null;
        $this->Controller->params = Router::parse($url);
        array_push($this->Controller->methods, 'view', 'edit', 'index');

        $this->Controller->Auth->initialize($this->Controller);
        $this->Controller->Auth->authorize = 'controller';
        $this->Controller->params['testControllerAuth'] = true;

        $this->Controller->Auth->loginAction = [
            'controller' => 'AuthTest', 'action' => 'login',
        ];
        $this->Controller->Auth->userModel = 'AuthUser';
        $this->Controller->Auth->startup($this->Controller);
        $expected = Router::normalize('/');
        $this->assertEqual($expected, $this->Controller->testUrl);

        $this->Controller->Session->delete('Auth');
        $_SERVER['HTTP_REFERER'] = Router::url('/admin/', true);

        $this->Controller->Session->write('Auth', [
            'AuthUser' => ['id' => '1', 'username' => 'nate'], ]
        );
        $this->Controller->params['url']['url'] = 'auth_test/login';
        $this->Controller->Auth->initialize($this->Controller);
        $this->Controller->Auth->loginAction = 'auth_test/login';
        $this->Controller->Auth->userModel = 'AuthUser';
        $this->Controller->Auth->loginRedirect = false;
        $this->Controller->Auth->startup($this->Controller);
        $expected = Router::normalize('/admin');
        $this->assertEqual($expected, $this->Controller->Auth->redirect());

        //Ticket #4750
        //named params
        $this->Controller->Session->delete('Auth');
        $url = '/posts/index/year:2008/month:feb';
        $this->Controller->params = Router::parse($url);
        $this->Controller->params['url']['url'] = Router::normalize($url);
        $this->Controller->Auth->initialize($this->Controller);
        $this->Controller->Auth->loginAction = ['controller' => 'AuthTest', 'action' => 'login'];
        $this->Controller->Auth->userModel = 'AuthUser';
        $this->Controller->Auth->startup($this->Controller);
        $expected = Router::normalize('posts/index/year:2008/month:feb');
        $this->assertEqual($expected, $this->Controller->Session->read('Auth.redirect'));

        //passed args
        $this->Controller->Session->delete('Auth');
        $url = '/posts/view/1';
        $this->Controller->params = Router::parse($url);
        $this->Controller->params['url']['url'] = Router::normalize($url);
        $this->Controller->Auth->initialize($this->Controller);
        $this->Controller->Auth->loginAction = ['controller' => 'AuthTest', 'action' => 'login'];
        $this->Controller->Auth->userModel = 'AuthUser';
        $this->Controller->Auth->startup($this->Controller);
        $expected = Router::normalize('posts/view/1');
        $this->assertEqual($expected, $this->Controller->Session->read('Auth.redirect'));

        // QueryString parameters
        $_back = $_GET;
        $_GET = [
            'url' => '/posts/index/29',
            'print' => 'true',
            'refer' => 'menu',
        ];
        $this->Controller->Session->delete('Auth');
        $url = '/posts/index/29?print=true&refer=menu';
        $this->Controller->params = Dispatcher::parseParams($url);
        $this->Controller->Auth->initialize($this->Controller);
        $this->Controller->Auth->loginAction = ['controller' => 'AuthTest', 'action' => 'login'];
        $this->Controller->Auth->userModel = 'AuthUser';
        $this->Controller->Auth->startup($this->Controller);
        $expected = Router::normalize('posts/index/29?print=true&refer=menu');
        $this->assertEqual($expected, $this->Controller->Session->read('Auth.redirect'));

        $_GET = [
            'url' => '/posts/index/29',
            'print' => 'true',
            'refer' => 'menu',
            'ext' => 'html',
        ];
        $this->Controller->Session->delete('Auth');
        $url = '/posts/index/29?print=true&refer=menu';
        $this->Controller->params = Dispatcher::parseParams($url);
        $this->Controller->Auth->initialize($this->Controller);
        $this->Controller->Auth->loginAction = ['controller' => 'AuthTest', 'action' => 'login'];
        $this->Controller->Auth->userModel = 'AuthUser';
        $this->Controller->Auth->startup($this->Controller);
        $expected = Router::normalize('posts/index/29?print=true&refer=menu');
        $this->assertEqual($expected, $this->Controller->Session->read('Auth.redirect'));
        $_GET = $_back;

        //external authed action
        $_SERVER['HTTP_REFERER'] = 'http://webmail.example.com/view/message';
        $this->Controller->Session->delete('Auth');
        $url = '/posts/edit/1';
        $this->Controller->params = Router::parse($url);
        $this->Controller->params['url']['url'] = Router::normalize($url);
        $this->Controller->Auth->initialize($this->Controller);
        $this->Controller->Auth->loginAction = ['controller' => 'AuthTest', 'action' => 'login'];
        $this->Controller->Auth->userModel = 'AuthUser';
        $this->Controller->Auth->startup($this->Controller);
        $expected = Router::normalize('/posts/edit/1');
        $this->assertEqual($expected, $this->Controller->Session->read('Auth.redirect'));

        //external direct login link
        $_SERVER['HTTP_REFERER'] = 'http://webmail.example.com/view/message';
        $this->Controller->Session->delete('Auth');
        $url = '/AuthTest/login';
        $this->Controller->params = Router::parse($url);
        $this->Controller->params['url']['url'] = Router::normalize($url);
        $this->Controller->Auth->initialize($this->Controller);
        $this->Controller->Auth->loginAction = ['controller' => 'AuthTest', 'action' => 'login'];
        $this->Controller->Auth->userModel = 'AuthUser';
        $this->Controller->Auth->startup($this->Controller);
        $expected = Router::normalize('/');
        $this->assertEqual($expected, $this->Controller->Session->read('Auth.redirect'));

        $_SERVER['HTTP_REFERER'] = $backup;
        $this->Controller->Session->delete('Auth');
    }

    /**
     * Ensure that no redirect is performed when a 404 is reached
     * And the user doesn't have a session.
     */
    public function testNoRedirectOn404()
    {
        $this->Controller->Session->delete('Auth');
        $this->Controller->Auth->initialize($this->Controller);
        $this->Controller->params = Router::parse('auth_test/something_totally_wrong');
        $result = $this->Controller->Auth->startup($this->Controller);
        $this->assertTrue($result, 'Auth redirected a missing action %s');
    }

    /**
     * testEmptyUsernameOrPassword method.
     */
    public function testEmptyUsernameOrPassword()
    {
        $this->AuthUser = new AuthUser();
        $user['id'] = 1;
        $user['username'] = 'mariano';
        $user['password'] = Security::hash(Configure::read('Security.salt').'cake');
        $this->AuthUser->save($user, false);

        $authUser = $this->AuthUser->find();

        $this->Controller->data['AuthUser']['username'] = '';
        $this->Controller->data['AuthUser']['password'] = '';

        $this->Controller->params = Router::parse('auth_test/login');
        $this->Controller->params['url']['url'] = 'auth_test/login';
        $this->Controller->Auth->initialize($this->Controller);
        $this->Controller->Auth->loginAction = 'auth_test/login';
        $this->Controller->Auth->userModel = 'AuthUser';

        $this->Controller->Auth->startup($this->Controller);
        $user = $this->Controller->Auth->user();
        $this->assertTrue($this->Controller->Session->check('Message.auth'));
        $this->assertEqual($user, false);
        $this->Controller->Session->delete('Auth');
    }

    /**
     * testInjection method.
     */
    public function testInjection()
    {
        $this->AuthUser = new AuthUser();
        $this->AuthUser->id = 2;
        $this->AuthUser->saveField('password', Security::hash(Configure::read('Security.salt').'cake'));

        $this->Controller->data['AuthUser']['username'] = 'nate';
        $this->Controller->data['AuthUser']['password'] = 'cake';
        $this->Controller->params = Router::parse('auth_test/login');
        $this->Controller->params['url']['url'] = 'auth_test/login';
        $this->Controller->Auth->initialize($this->Controller);

        $this->Controller->Auth->loginAction = 'auth_test/login';
        $this->Controller->Auth->userModel = 'AuthUser';
        $this->Controller->Auth->startup($this->Controller);
        $this->assertTrue(is_array($this->Controller->Auth->user()));

        $this->Controller->Session->delete($this->Controller->Auth->sessionKey);

        $this->Controller->data['AuthUser']['username'] = 'nate';
        $this->Controller->data['AuthUser']['password'] = 'cake1';
        $this->Controller->params['url']['url'] = 'auth_test/login';
        $this->Controller->Auth->initialize($this->Controller);

        $this->Controller->Auth->loginAction = 'auth_test/login';
        $this->Controller->Auth->userModel = 'AuthUser';
        $this->Controller->Auth->startup($this->Controller);
        $this->assertTrue(is_null($this->Controller->Auth->user()));

        $this->Controller->Session->delete($this->Controller->Auth->sessionKey);

        $this->Controller->data['AuthUser']['username'] = '> n';
        $this->Controller->data['AuthUser']['password'] = 'cake';
        $this->Controller->Auth->initialize($this->Controller);

        $this->Controller->Auth->startup($this->Controller);
        $this->assertTrue(is_null($this->Controller->Auth->user()));

        unset($this->Controller->data['AuthUser']['password']);
        $this->Controller->data['AuthUser']['username'] = "1'1";
        $this->Controller->Auth->initialize($this->Controller);

        $this->Controller->Auth->startup($this->Controller);
        $this->assertTrue(is_null($this->Controller->Auth->user()));

        unset($this->Controller->data['AuthUser']['username']);
        $this->Controller->data['AuthUser']['password'] = "1'1";
        $this->Controller->Auth->initialize($this->Controller);

        $this->Controller->Auth->startup($this->Controller);
        $this->assertTrue(is_null($this->Controller->Auth->user()));
    }

    /**
     * test Hashing of passwords.
     */
    public function testHashPasswords()
    {
        $this->Controller->Auth->userModel = 'AuthUser';

        $data['AuthUser']['password'] = 'superSecret';
        $data['AuthUser']['username'] = 'superman@dailyplanet.com';
        $return = $this->Controller->Auth->hashPasswords($data);
        $expected = $data;
        $expected['AuthUser']['password'] = Security::hash($expected['AuthUser']['password'], null, true);
        $this->assertEqual($return, $expected);

        $data['Wrong']['password'] = 'superSecret';
        $data['Wrong']['username'] = 'superman@dailyplanet.com';
        $data['AuthUser']['password'] = 'IcantTellYou';
        $return = $this->Controller->Auth->hashPasswords($data);
        $expected = $data;
        $expected['AuthUser']['password'] = Security::hash($expected['AuthUser']['password'], null, true);
        $this->assertEqual($return, $expected);

        if (PHP5) {
            $xml = [
                'User' => [
                    'username' => 'batman@batcave.com',
                    'password' => 'bruceWayne',
                ],
            ];
            $data = new Xml($xml);
            $return = $this->Controller->Auth->hashPasswords($data);
            $expected = $data;
            $this->assertEqual($return, $expected);
        }
    }

    /**
     * testCustomRoute method.
     */
    public function testCustomRoute()
    {
        Router::reload();
        Router::connect(
            '/:lang/:controller/:action/*',
            ['lang' => null],
            ['lang' => '[a-z]{2,3}']
        );

        $url = '/en/users/login';
        $this->Controller->params = Router::parse($url);
        Router::setRequestInfo([$this->Controller->passedArgs, [
            'base' => null, 'here' => $url, 'webroot' => '/', 'passedArgs' => [],
            'argSeparator' => ':', 'namedArgs' => [],
        ]]);

        $this->AuthUser = new AuthUser();
        $user = [
            'id' => 1, 'username' => 'felix',
            'password' => Security::hash(Configure::read('Security.salt').'cake'
        ), ];
        $user = $this->AuthUser->save($user, false);

        $this->Controller->data['AuthUser'] = ['username' => 'felix', 'password' => 'cake'];
        $this->Controller->params['url']['url'] = substr($url, 1);
        $this->Controller->Auth->initialize($this->Controller);
        $this->Controller->Auth->loginAction = ['lang' => 'en', 'controller' => 'users', 'action' => 'login'];
        $this->Controller->Auth->userModel = 'AuthUser';

        $this->Controller->Auth->startup($this->Controller);
        $user = $this->Controller->Auth->user();
        $this->assertTrue((bool) $user);

        $this->Controller->Session->delete('Auth');
        Router::reload();
        Router::connect('/', ['controller' => 'people', 'action' => 'login']);
        $url = '/';
        $this->Controller->params = Router::parse($url);
        Router::setRequestInfo([$this->Controller->passedArgs, [
            'base' => null, 'here' => $url, 'webroot' => '/', 'passedArgs' => [],
            'argSeparator' => ':', 'namedArgs' => [],
        ]]);
        $this->Controller->data['AuthUser'] = ['username' => 'felix', 'password' => 'cake'];
        $this->Controller->params['url']['url'] = substr($url, 1);
        $this->Controller->Auth->initialize($this->Controller);
        $this->Controller->Auth->loginAction = ['controller' => 'people', 'action' => 'login'];
        $this->Controller->Auth->userModel = 'AuthUser';

        $this->Controller->Auth->startup($this->Controller);
        $user = $this->Controller->Auth->user();
        $this->assertTrue((bool) $user);
    }

    /**
     * testCustomField method.
     */
    public function testCustomField()
    {
        Router::reload();

        $this->AuthUserCustomField = new AuthUserCustomField();
        $user = [
            'id' => 1, 'email' => 'harking@example.com',
            'password' => Security::hash(Configure::read('Security.salt').'cake'
        ), ];
        $user = $this->AuthUserCustomField->save($user, false);

        Router::connect('/', ['controller' => 'people', 'action' => 'login']);
        $url = '/';
        $this->Controller->params = Router::parse($url);
        Router::setRequestInfo([$this->Controller->passedArgs, [
            'base' => null, 'here' => $url, 'webroot' => '/', 'passedArgs' => [],
            'argSeparator' => ':', 'namedArgs' => [],
        ]]);
        $this->Controller->data['AuthUserCustomField'] = ['email' => 'harking@example.com', 'password' => 'cake'];
        $this->Controller->params['url']['url'] = substr($url, 1);
        $this->Controller->Auth->initialize($this->Controller);
        $this->Controller->Auth->fields = ['username' => 'email', 'password' => 'password'];
        $this->Controller->Auth->loginAction = ['controller' => 'people', 'action' => 'login'];
        $this->Controller->Auth->userModel = 'AuthUserCustomField';

        $this->Controller->Auth->startup($this->Controller);
        $user = $this->Controller->Auth->user();
        $this->assertTrue((bool) $user);
    }

    /**
     * testAdminRoute method.
     */
    public function testAdminRoute()
    {
        $prefixes = Configure::read('Routing.prefixes');
        Configure::write('Routing.prefixes', ['admin']);
        Router::reload();

        $url = '/admin/auth_test/add';
        $this->Controller->params = Router::parse($url);
        $this->Controller->params['url']['url'] = ltrim($url, '/');
        Router::setRequestInfo([
            [
                'pass' => [], 'action' => 'add', 'plugin' => null,
                'controller' => 'auth_test', 'admin' => true,
                'url' => ['url' => $this->Controller->params['url']['url']],
            ],
            [
                'base' => null, 'here' => $url,
                'webroot' => '/', 'passedArgs' => [],
            ],
        ]);
        $this->Controller->Auth->initialize($this->Controller);

        $this->Controller->Auth->loginAction = [
            'admin' => true, 'controller' => 'auth_test', 'action' => 'login',
        ];
        $this->Controller->Auth->userModel = 'AuthUser';

        $this->Controller->Auth->startup($this->Controller);
        $this->assertEqual($this->Controller->testUrl, '/admin/auth_test/login');

        Configure::write('Routing.prefixes', $prefixes);
    }

    /**
     * testPluginModel method.
     */
    public function testPluginModel()
    {
        // Adding plugins
        Cache::delete('object_map', '_cake_core_');
        App::build([
            'plugins' => [TEST_CAKE_CORE_INCLUDE_PATH.'tests'.DS.'test_app'.DS.'plugins'.DS],
            'models' => [TEST_CAKE_CORE_INCLUDE_PATH.'tests'.DS.'test_app'.DS.'models'.DS],
        ], true);
        App::objects('plugin', null, false);

        $PluginModel = &ClassRegistry::init('TestPlugin.TestPluginAuthUser');
        $user['id'] = 1;
        $user['username'] = 'gwoo';
        $user['password'] = Security::hash(Configure::read('Security.salt').'cake');
        $PluginModel->save($user, false);

        $authUser = $PluginModel->find();

        $this->Controller->data['TestPluginAuthUser']['username'] = $authUser['TestPluginAuthUser']['username'];
        $this->Controller->data['TestPluginAuthUser']['password'] = 'cake';

        $this->Controller->params = Router::parse('auth_test/login');
        $this->Controller->params['url']['url'] = 'auth_test/login';

        $this->Controller->Auth->initialize($this->Controller);

        $this->Controller->Auth->loginAction = 'auth_test/login';
        $this->Controller->Auth->userModel = 'TestPlugin.TestPluginAuthUser';

        $this->Controller->Auth->startup($this->Controller);
        $user = $this->Controller->Auth->user();
        $expected = ['TestPluginAuthUser' => [
            'id' => 1, 'username' => 'gwoo', 'created' => '2007-03-17 01:16:23', 'updated' => date('Y-m-d H:i:s'),
        ]];
        $this->assertEqual($user, $expected);
        $sessionKey = $this->Controller->Auth->sessionKey;
        $this->assertEqual('Auth.TestPluginAuthUser', $sessionKey);

        $this->Controller->Auth->loginAction = null;
        $this->Controller->Auth->__setDefaults();
        $loginAction = $this->Controller->Auth->loginAction;
        $expected = [
            'controller' => 'test_plugin_auth_users',
            'action' => 'login',
            'plugin' => 'test_plugin',
        ];
        $this->assertEqual($loginAction, $expected);

        // Reverting changes
        Cache::delete('object_map', '_cake_core_');
        App::build();
        App::objects('plugin', null, false);
    }

    /**
     * testAjaxLogin method.
     */
    public function testAjaxLogin()
    {
        App::build(['views' => [TEST_CAKE_CORE_INCLUDE_PATH.'tests'.DS.'test_app'.DS.'views'.DS]]);
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';

        if (!class_exists('dispatcher')) {
            require CAKE.'dispatcher.php';
        }

        ob_start();
        $Dispatcher = new Dispatcher();
        $Dispatcher->dispatch('/ajax_auth/add', ['return' => 1]);
        $result = ob_get_clean();
        $this->assertEqual("Ajax!\nthis is the test element", str_replace("\r\n", "\n", $result));
        unset($_SERVER['HTTP_X_REQUESTED_WITH']);
    }

    /**
     * testLoginActionRedirect method.
     */
    public function testLoginActionRedirect()
    {
        $admin = Configure::read('Routing.admin');
        Configure::write('Routing.admin', 'admin');
        Router::reload();

        $url = '/admin/auth_test/login';
        $this->Controller->params = Router::parse($url);
        $this->Controller->params['url']['url'] = ltrim($url, '/');
        Router::setRequestInfo([
            [
                'pass' => [], 'action' => 'admin_login', 'plugin' => null, 'controller' => 'auth_test',
                'admin' => true, 'url' => ['url' => $this->Controller->params['url']['url']],
            ],
            [
                'base' => null, 'here' => $url,
                'webroot' => '/', 'passedArgs' => [],
            ],
        ]);

        $this->Controller->Auth->initialize($this->Controller);

        $this->Controller->Auth->loginAction = ['admin' => true, 'controller' => 'auth_test', 'action' => 'login'];
        $this->Controller->Auth->userModel = 'AuthUser';

        $this->Controller->Auth->startup($this->Controller);

        $this->assertNull($this->Controller->testUrl);

        Configure::write('Routing.admin', $admin);
    }

    /**
     * Tests that shutdown destroys the redirect session var.
     */
    public function testShutDown()
    {
        $this->Controller->Session->write('Auth.redirect', 'foo');
        $this->Controller->Auth->_loggedIn = true;
        $this->Controller->Auth->shutdown($this->Controller);
        $this->assertFalse($this->Controller->Session->read('Auth.redirect'));
    }

    /**
     * test the initialize callback and its interactions with Router::prefixes().
     */
    public function testInitializeAndRoutingPrefixes()
    {
        $restore = Configure::read('Routing');
        Configure::write('Routing.prefixes', ['admin', 'super_user']);
        Router::reload();
        $this->Controller->Auth->initialize($this->Controller);

        $this->assertTrue(isset($this->Controller->Auth->actionMap['delete']));
        $this->assertTrue(isset($this->Controller->Auth->actionMap['view']));
        $this->assertTrue(isset($this->Controller->Auth->actionMap['add']));
        $this->assertTrue(isset($this->Controller->Auth->actionMap['admin_view']));
        $this->assertTrue(isset($this->Controller->Auth->actionMap['super_user_delete']));

        Configure::write('Routing', $restore);
    }

    /**
     * test $settings in Controller::$components.
     */
    public function testComponentSettings()
    {
        $this->Controller = new AuthTestController();
        $this->Controller->components = [
            'Auth' => [
                'fields' => ['username' => 'email', 'password' => 'password'],
                'loginAction' => ['controller' => 'people', 'action' => 'login'],
                'userModel' => 'AuthUserCustomField',
                'sessionKey' => 'AltAuth.AuthUserCustomField',
            ],
            'Session',
        ];
        $this->Controller->Component->init($this->Controller);
        $this->Controller->Component->initialize($this->Controller);
        Router::reload();

        $this->AuthUserCustomField = new AuthUserCustomField();
        $user = [
            'id' => 1, 'email' => 'harking@example.com',
            'password' => Security::hash(Configure::read('Security.salt').'cake'
        ), ];
        $user = $this->AuthUserCustomField->save($user, false);

        Router::connect('/', ['controller' => 'people', 'action' => 'login']);
        $url = '/';
        $this->Controller->params = Router::parse($url);
        Router::setRequestInfo([$this->Controller->passedArgs, [
            'base' => null, 'here' => $url, 'webroot' => '/', 'passedArgs' => [],
            'argSeparator' => ':', 'namedArgs' => [],
        ]]);
        $this->Controller->data['AuthUserCustomField'] = ['email' => 'harking@example.com', 'password' => 'cake'];
        $this->Controller->params['url']['url'] = substr($url, 1);
        $this->Controller->Auth->startup($this->Controller);

        $user = $this->Controller->Auth->user();
        $this->assertTrue((bool) $user);

        $expected = [
            'fields' => ['username' => 'email', 'password' => 'password'],
            'loginAction' => ['controller' => 'people', 'action' => 'login'],
            'logoutRedirect' => ['controller' => 'people', 'action' => 'login'],
            'userModel' => 'AuthUserCustomField',
            'sessionKey' => 'AltAuth.AuthUserCustomField',
        ];
        $this->assertEqual($expected['fields'], $this->Controller->Auth->fields);
        $this->assertEqual($expected['loginAction'], $this->Controller->Auth->loginAction);
        $this->assertEqual($expected['logoutRedirect'], $this->Controller->Auth->logoutRedirect);
        $this->assertEqual($expected['userModel'], $this->Controller->Auth->userModel);
        $this->assertEqual($expected['sessionKey'], $this->Controller->Auth->sessionKey);
    }

    /**
     * test that logout deletes the session variables. and returns the correct url.
     */
    public function testLogout()
    {
        $this->Controller->Session->write('Auth.User.id', '1');
        $this->Controller->Session->write('Auth.redirect', '/users/login');
        $this->Controller->Auth->logoutRedirect = '/';
        $result = $this->Controller->Auth->logout();

        $this->assertEqual($result, '/');
        $this->assertNull($this->Controller->Session->read('Auth.AuthUser'));
        $this->assertNull($this->Controller->Session->read('Auth.redirect'));
    }
}
