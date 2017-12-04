<?php
/**
 * ScaffoldTest file.
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
 * @since         CakePHP(tm) v 1.2.0.5436
 *
 * @license       http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */
App::import('Core', 'Scaffold');

/**
 * ScaffoldMockController class.
 */
class ScaffoldMockController extends Controller
{
    /**
     * name property.
     *
     * @var string 'ScaffoldMock'
     */
    public $name = 'ScaffoldMock';

    /**
     * scaffold property.
     *
     * @var mixed
     */
    public $scaffold;
}

/**
 * ScaffoldMockControllerWithFields class.
 */
class ScaffoldMockControllerWithFields extends Controller
{
    /**
     * name property.
     *
     * @var string 'ScaffoldMock'
     */
    public $name = 'ScaffoldMock';

    /**
     * scaffold property.
     *
     * @var mixed
     */
    public $scaffold;

    /**
     * function _beforeScaffold.
     *
     * @param string method
     */
    public function _beforeScaffold($method)
    {
        $this->set('scaffoldFields', ['title']);

        return true;
    }
}

/**
 * TestScaffoldMock class.
 */
class TestScaffoldMock extends Scaffold
{
    /**
     * Overload __scaffold.
     *
     * @param unknown_type $params
     */
    public function __scaffold($params)
    {
        $this->_params = $params;
    }

    /**
     * Get Params from the Controller.
     *
     * @return unknown
     */
    public function getParams()
    {
        return $this->_params;
    }
}

/**
 * ScaffoldMock class.
 */
class ScaffoldMock extends CakeTestModel
{
    /**
     * useTable property.
     *
     * @var string 'posts'
     */
    public $useTable = 'articles';

    /**
     * belongsTo property.
     *
     * @var array
     */
    public $belongsTo = [
        'User' => [
            'className' => 'ScaffoldUser',
            'foreignKey' => 'user_id',
        ],
    ];

    /**
     * hasMany property.
     *
     * @var array
     */
    public $hasMany = [
        'Comment' => [
            'className' => 'ScaffoldComment',
            'foreignKey' => 'article_id',
        ],
    ];
    /**
     * hasAndBelongsToMany property.
     *
     * @var string
     */
    public $hasAndBelongsToMany = [
        'ScaffoldTag' => [
            'className' => 'ScaffoldTag',
            'foreignKey' => 'something_id',
            'associationForeignKey' => 'something_else_id',
            'joinTable' => 'join_things',
        ],
    ];
}

/**
 * ScaffoldUser class.
 */
class ScaffoldUser extends CakeTestModel
{
    /**
     * useTable property.
     *
     * @var string 'posts'
     */
    public $useTable = 'users';

    /**
     * hasMany property.
     *
     * @var array
     */
    public $hasMany = [
        'Article' => [
            'className' => 'ScaffoldMock',
            'foreignKey' => 'article_id',
        ],
    ];
}

/**
 * ScaffoldComment class.
 */
class ScaffoldComment extends CakeTestModel
{
    /**
     * useTable property.
     *
     * @var string 'posts'
     */
    public $useTable = 'comments';

    /**
     * belongsTo property.
     *
     * @var array
     */
    public $belongsTo = [
        'Article' => [
            'className' => 'ScaffoldMock',
            'foreignKey' => 'article_id',
        ],
    ];
}

/**
 * ScaffoldTag class.
 */
class ScaffoldTag extends CakeTestModel
{
    /**
     * useTable property.
     *
     * @var string 'posts'
     */
    public $useTable = 'tags';
}
/**
 * TestScaffoldView class.
 */
class TestScaffoldView extends ScaffoldView
{
    /**
     * testGetFilename method.
     *
     * @param mixed $action
     */
    public function testGetFilename($action)
    {
        return $this->_getViewFileName($action);
    }
}

/**
 * ScaffoldViewTest class.
 */
class ScaffoldViewTest extends CakeTestCase
{
    /**
     * fixtures property.
     *
     * @var array
     */
    public $fixtures = ['core.article', 'core.user', 'core.comment', 'core.join_thing', 'core.tag'];

    /**
     * startTest method.
     */
    public function startTest()
    {
        $this->Controller = new ScaffoldMockController();

        App::build([
            'views' => [TEST_CAKE_CORE_INCLUDE_PATH.'tests'.DS.'test_app'.DS.'views'.DS],
            'plugins' => [TEST_CAKE_CORE_INCLUDE_PATH.'tests'.DS.'test_app'.DS.'plugins'.DS],
        ]);
    }

    /**
     * endTest method.
     */
    public function endTest()
    {
        unset($this->Controller);

        App::build();
    }

    /**
     * testGetViewFilename method.
     */
    public function testGetViewFilename()
    {
        $_admin = Configure::read('Routing.prefixes');
        Configure::write('Routing.prefixes', ['admin']);

        $this->Controller->action = 'index';
        $ScaffoldView = new TestScaffoldView($this->Controller);
        $result = $ScaffoldView->testGetFilename('index');
        $expected = TEST_CAKE_CORE_INCLUDE_PATH.'libs'.DS.'view'.DS.'scaffolds'.DS.'index.ctp';
        $this->assertEqual($result, $expected);

        $result = $ScaffoldView->testGetFilename('edit');
        $expected = TEST_CAKE_CORE_INCLUDE_PATH.'libs'.DS.'view'.DS.'scaffolds'.DS.'edit.ctp';
        $this->assertEqual($result, $expected);

        $result = $ScaffoldView->testGetFilename('add');
        $expected = TEST_CAKE_CORE_INCLUDE_PATH.'libs'.DS.'view'.DS.'scaffolds'.DS.'edit.ctp';
        $this->assertEqual($result, $expected);

        $result = $ScaffoldView->testGetFilename('view');
        $expected = TEST_CAKE_CORE_INCLUDE_PATH.'libs'.DS.'view'.DS.'scaffolds'.DS.'view.ctp';
        $this->assertEqual($result, $expected);

        $result = $ScaffoldView->testGetFilename('admin_index');
        $expected = TEST_CAKE_CORE_INCLUDE_PATH.'libs'.DS.'view'.DS.'scaffolds'.DS.'index.ctp';
        $this->assertEqual($result, $expected);

        $result = $ScaffoldView->testGetFilename('admin_view');
        $expected = TEST_CAKE_CORE_INCLUDE_PATH.'libs'.DS.'view'.DS.'scaffolds'.DS.'view.ctp';
        $this->assertEqual($result, $expected);

        $result = $ScaffoldView->testGetFilename('admin_edit');
        $expected = TEST_CAKE_CORE_INCLUDE_PATH.'libs'.DS.'view'.DS.'scaffolds'.DS.'edit.ctp';
        $this->assertEqual($result, $expected);

        $result = $ScaffoldView->testGetFilename('admin_add');
        $expected = TEST_CAKE_CORE_INCLUDE_PATH.'libs'.DS.'view'.DS.'scaffolds'.DS.'edit.ctp';
        $this->assertEqual($result, $expected);

        $result = $ScaffoldView->testGetFilename('error');
        $expected = 'cake'.DS.'libs'.DS.'view'.DS.'errors'.DS.'scaffold_error.ctp';
        $this->assertEqual($result, $expected);

        $Controller = new ScaffoldMockController();
        $Controller->scaffold = 'admin';
        $Controller->viewPath = 'posts';
        $Controller->action = 'admin_edit';
        $ScaffoldView = new TestScaffoldView($Controller);
        $result = $ScaffoldView->testGetFilename('admin_edit');
        $expected = TEST_CAKE_CORE_INCLUDE_PATH.'tests'.DS.'test_app'.DS.'views'.DS.'posts'.DS.'scaffold.edit.ctp';
        $this->assertEqual($result, $expected);

        $result = $ScaffoldView->testGetFilename('edit');
        $expected = TEST_CAKE_CORE_INCLUDE_PATH.'tests'.DS.'test_app'.DS.'views'.DS.'posts'.DS.'scaffold.edit.ctp';
        $this->assertEqual($result, $expected);

        $Controller = new ScaffoldMockController();
        $Controller->scaffold = 'admin';
        $Controller->viewPath = 'tests';
        $Controller->plugin = 'test_plugin';
        $Controller->action = 'admin_add';
        $ScaffoldView = new TestScaffoldView($Controller);
        $result = $ScaffoldView->testGetFilename('admin_add');
        $expected = TEST_CAKE_CORE_INCLUDE_PATH.'tests'.DS.'test_app'.DS.'plugins'
            .DS.'test_plugin'.DS.'views'.DS.'tests'.DS.'scaffold.edit.ctp';
        $this->assertEqual($result, $expected);

        $result = $ScaffoldView->testGetFilename('add');
        $expected = TEST_CAKE_CORE_INCLUDE_PATH.'tests'.DS.'test_app'.DS.'plugins'
            .DS.'test_plugin'.DS.'views'.DS.'tests'.DS.'scaffold.edit.ctp';
        $this->assertEqual($result, $expected);

        Configure::write('Routing.prefixes', $_admin);
    }

    /**
     * test getting the view file name for themed scaffolds.
     */
    public function testGetViewFileNameWithTheme()
    {
        $this->Controller->action = 'index';
        $this->Controller->viewPath = 'posts';
        $this->Controller->theme = 'test_theme';
        $ScaffoldView = new TestScaffoldView($this->Controller);

        $result = $ScaffoldView->testGetFilename('index');
        $expected = TEST_CAKE_CORE_INCLUDE_PATH.'tests'.DS.'test_app'.DS.'views'.DS
            .'themed'.DS.'test_theme'.DS.'posts'.DS.'scaffold.index.ctp';
        $this->assertEqual($result, $expected);
    }

    /**
     * test default index scaffold generation.
     */
    public function testIndexScaffold()
    {
        $this->Controller->action = 'index';
        $this->Controller->here = '/scaffold_mock';
        $this->Controller->webroot = '/';
        $params = [
            'plugin' => null,
            'pass' => [],
            'form' => [],
            'named' => [],
            'url' => ['url' => 'scaffold_mock'],
            'controller' => 'scaffold_mock',
            'action' => 'index',
        ];
        //set router.
        Router::reload();
        Router::setRequestInfo([$params, ['base' => '/', 'here' => '/scaffold_mock', 'webroot' => '/']]);
        $this->Controller->params = $params;
        $this->Controller->controller = 'scaffold_mock';
        $this->Controller->base = '/';
        $this->Controller->constructClasses();
        ob_start();
        new Scaffold($this->Controller, $params);
        $result = ob_get_clean();

        $this->assertPattern('#<h2>Scaffold Mock</h2>#', $result);
        $this->assertPattern('#<table cellpadding="0" cellspacing="0">#', $result);

        $this->assertPattern('#<a href="/scaffold_users/view/1">1</a>#', $result); //belongsTo links
        $this->assertPattern('#<li><a href="/scaffold_mock/add">New Scaffold Mock</a></li>#', $result);
        $this->assertPattern('#<li><a href="/scaffold_users">List Scaffold Users</a></li>#', $result);
        $this->assertPattern('#<li><a href="/scaffold_comments/add">New Comment</a></li>#', $result);
    }

    /**
     * test default view scaffold generation.
     */
    public function testViewScaffold()
    {
        $this->Controller->action = 'view';
        $this->Controller->here = '/scaffold_mock';
        $this->Controller->webroot = '/';
        $params = [
            'plugin' => null,
            'pass' => [1],
            'form' => [],
            'named' => [],
            'url' => ['url' => 'scaffold_mock'],
            'controller' => 'scaffold_mock',
            'action' => 'view',
        ];
        //set router.
        Router::reload();
        Router::setRequestInfo([$params, ['base' => '/', 'here' => '/scaffold_mock', 'webroot' => '/']]);
        $this->Controller->params = $params;
        $this->Controller->controller = 'scaffold_mock';
        $this->Controller->base = '/';
        $this->Controller->constructClasses();

        ob_start();
        new Scaffold($this->Controller, $params);
        $result = ob_get_clean();

        $this->assertPattern('/<h2>View Scaffold Mock<\/h2>/', $result);
        $this->assertPattern('/<dl>/', $result);
        //TODO: add specific tests for fields.
        $this->assertPattern('/<a href="\/scaffold_users\/view\/1">1<\/a>/', $result); //belongsTo links
        $this->assertPattern('/<li><a href="\/scaffold_mock\/edit\/1">Edit Scaffold Mock<\/a>\s<\/li>/', $result);
        $this->assertPattern('/<li><a href="\/scaffold_mock\/delete\/1"[^>]*>Delete Scaffold Mock<\/a>\s*<\/li>/', $result);
        //check related table
        $this->assertPattern('/<div class="related">\s*<h3>Related Scaffold Comments<\/h3>\s*<table cellpadding="0" cellspacing="0">/', $result);
        $this->assertPattern('/<li><a href="\/scaffold_comments\/add">New Comment<\/a><\/li>/', $result);
        $this->assertNoPattern('/<th>JoinThing<\/th>/', $result);
    }

    /**
     * test default view scaffold generation.
     */
    public function testEditScaffold()
    {
        $this->Controller->action = 'edit';
        $this->Controller->here = '/scaffold_mock';
        $this->Controller->webroot = '/';
        $params = [
            'plugin' => null,
            'pass' => [1],
            'form' => [],
            'named' => [],
            'url' => ['url' => 'scaffold_mock'],
            'controller' => 'scaffold_mock',
            'action' => 'edit',
        ];
        //set router.
        Router::reload();
        Router::setRequestInfo([$params, ['base' => '/', 'here' => '/scaffold_mock', 'webroot' => '/']]);
        $this->Controller->params = $params;
        $this->Controller->controller = 'scaffold_mock';
        $this->Controller->base = '/';
        $this->Controller->constructClasses();
        ob_start();
        new Scaffold($this->Controller, $params);
        $result = ob_get_clean();

        $this->assertPattern('/<form action="\/scaffold_mock\/edit\/1" id="ScaffoldMockEditForm" method="post"/', $result);
        $this->assertPattern('/<legend>Edit Scaffold Mock<\/legend>/', $result);

        $this->assertPattern('/input type="hidden" name="data\[ScaffoldMock\]\[id\]" value="1" id="ScaffoldMockId"/', $result);
        $this->assertPattern('/select name="data\[ScaffoldMock\]\[user_id\]" id="ScaffoldMockUserId"/', $result);
        $this->assertPattern('/input name="data\[ScaffoldMock\]\[title\]" type="text" maxlength="255" value="First Article" id="ScaffoldMockTitle"/', $result);
        $this->assertPattern('/input name="data\[ScaffoldMock\]\[published\]" type="text" maxlength="1" value="Y" id="ScaffoldMockPublished"/', $result);
        $this->assertPattern('/textarea name="data\[ScaffoldMock\]\[body\]" cols="30" rows="6" id="ScaffoldMockBody"/', $result);
        $this->assertPattern('/<li><a href="\/scaffold_mock\/delete\/1"[^>]*>Delete<\/a>\s*<\/li>/', $result);
    }

    /**
     * Test Admin Index Scaffolding.
     */
    public function testAdminIndexScaffold()
    {
        $_backAdmin = Configure::read('Routing.prefixes');

        Configure::write('Routing.prefixes', ['admin']);
        $params = [
            'plugin' => null,
            'pass' => [],
            'form' => [],
            'named' => [],
            'prefix' => 'admin',
            'url' => ['url' => 'admin/scaffold_mock'],
            'controller' => 'scaffold_mock',
            'action' => 'admin_index',
            'admin' => 1,
        ];
        //reset, and set router.
        Router::reload();
        Router::setRequestInfo([$params, ['base' => '/', 'here' => '/admin/scaffold_mock', 'webroot' => '/']]);
        $this->Controller->params = $params;
        $this->Controller->controller = 'scaffold_mock';
        $this->Controller->base = '/';
        $this->Controller->action = 'admin_index';
        $this->Controller->here = '/tests/admin/scaffold_mock';
        $this->Controller->webroot = '/';
        $this->Controller->scaffold = 'admin';
        $this->Controller->constructClasses();

        ob_start();
        $Scaffold = new Scaffold($this->Controller, $params);
        $result = ob_get_clean();

        $this->assertPattern('/<h2>Scaffold Mock<\/h2>/', $result);
        $this->assertPattern('/<table cellpadding="0" cellspacing="0">/', $result);
        //TODO: add testing for table generation
        $this->assertPattern('/<li><a href="\/admin\/scaffold_mock\/add">New Scaffold Mock<\/a><\/li>/', $result);

        Configure::write('Routing.prefixes', $_backAdmin);
    }

    /**
     * Test Admin Index Scaffolding.
     */
    public function testAdminEditScaffold()
    {
        $_backAdmin = Configure::read('Routing.prefixes');

        Configure::write('Routing.prefixes', ['admin']);
        $params = [
            'plugin' => null,
            'pass' => [],
            'form' => [],
            'named' => [],
            'prefix' => 'admin',
            'url' => ['url' => 'admin/scaffold_mock/edit'],
            'controller' => 'scaffold_mock',
            'action' => 'admin_edit',
            'admin' => 1,
        ];
        //reset, and set router.
        Router::reload();
        Router::setRequestInfo([$params, ['base' => '/', 'here' => '/admin/scaffold_mock/edit', 'webroot' => '/']]);
        $this->Controller->params = $params;
        $this->Controller->controller = 'scaffold_mock';
        $this->Controller->base = '/';
        $this->Controller->action = 'admin_index';
        $this->Controller->here = '/tests/admin/scaffold_mock';
        $this->Controller->webroot = '/';
        $this->Controller->scaffold = 'admin';
        $this->Controller->constructClasses();

        ob_start();
        $Scaffold = new Scaffold($this->Controller, $params);
        $result = ob_get_clean();

        $this->assertPattern('#admin/scaffold_mock/edit/1#', $result);
        $this->assertPattern('#Scaffold Mock#', $result);

        Configure::write('Routing.prefixes', $_backAdmin);
    }

    /**
     * Test Admin Index Scaffolding.
     */
    public function testMultiplePrefixScaffold()
    {
        $_backAdmin = Configure::read('Routing.prefixes');

        Configure::write('Routing.prefixes', ['admin', 'member']);
        $params = [
            'plugin' => null,
            'pass' => [],
            'form' => [],
            'named' => [],
            'prefix' => 'member',
            'url' => ['url' => 'member/scaffold_mock'],
            'controller' => 'scaffold_mock',
            'action' => 'member_index',
            'member' => 1,
        ];
        //reset, and set router.
        Router::reload();
        Router::setRequestInfo([$params, ['base' => '/', 'here' => '/member/scaffold_mock', 'webroot' => '/']]);
        $this->Controller->params = $params;
        $this->Controller->controller = 'scaffold_mock';
        $this->Controller->base = '/';
        $this->Controller->action = 'member_index';
        $this->Controller->here = '/tests/member/scaffold_mock';
        $this->Controller->webroot = '/';
        $this->Controller->scaffold = 'member';
        $this->Controller->constructClasses();

        ob_start();
        $Scaffold = new Scaffold($this->Controller, $params);
        $result = ob_get_clean();

        $this->assertPattern('/<h2>Scaffold Mock<\/h2>/', $result);
        $this->assertPattern('/<table cellpadding="0" cellspacing="0">/', $result);
        //TODO: add testing for table generation
        $this->assertPattern('/<li><a href="\/member\/scaffold_mock\/add">New Scaffold Mock<\/a><\/li>/', $result);

        Configure::write('Routing.prefixes', $_backAdmin);
    }
}

/**
 * Scaffold Test class.
 */
class ScaffoldTest extends CakeTestCase
{
    /**
     * Controller property.
     *
     * @var SecurityTestController
     */
    public $Controller;

    /**
     * fixtures property.
     *
     * @var array
     */
    public $fixtures = ['core.article', 'core.user', 'core.comment', 'core.join_thing', 'core.tag'];

    /**
     * startTest method.
     */
    public function startTest()
    {
        $this->Controller = new ScaffoldMockController();
    }

    /**
     * endTest method.
     */
    public function endTest()
    {
        unset($this->Controller);
    }

    /**
     * Test the correct Generation of Scaffold Params.
     * This ensures that the correct action and view will be generated.
     */
    public function testScaffoldParams()
    {
        $this->Controller->action = 'admin_edit';
        $this->Controller->here = '/admin/scaffold_mock/edit';
        $this->Controller->webroot = '/';
        $params = [
            'plugin' => null,
            'pass' => [],
            'form' => [],
            'named' => [],
            'url' => ['url' => 'admin/scaffold_mock/edit'],
            'controller' => 'scaffold_mock',
            'action' => 'admin_edit',
            'admin' => true,
        ];
        //set router.
        Router::setRequestInfo([$params, ['base' => '/', 'here' => 'admin/scaffold_mock', 'webroot' => '/']]);

        $this->Controller->params = $params;
        $this->Controller->controller = 'scaffold_mock';
        $this->Controller->base = '/';
        $this->Controller->constructClasses();
        $Scaffold = new TestScaffoldMock($this->Controller, $params);
        $result = $Scaffold->getParams();
        $this->assertEqual($result['action'], 'admin_edit');
    }

    /**
     * test that the proper names and variable values are set by Scaffold.
     */
    public function testScaffoldVariableSetting()
    {
        $this->Controller->action = 'admin_edit';
        $this->Controller->here = '/admin/scaffold_mock/edit';
        $this->Controller->webroot = '/';
        $params = [
            'plugin' => null,
            'pass' => [],
            'form' => [],
            'named' => [],
            'url' => ['url' => 'admin/scaffold_mock/edit'],
            'controller' => 'scaffold_mock',
            'action' => 'admin_edit',
            'admin' => true,
        ];
        //set router.
        Router::setRequestInfo([$params, ['base' => '/', 'here' => 'admin/scaffold_mock', 'webroot' => '/']]);

        $this->Controller->params = $params;
        $this->Controller->controller = 'scaffold_mock';
        $this->Controller->base = '/';
        $this->Controller->constructClasses();
        $Scaffold = new TestScaffoldMock($this->Controller, $params);
        $result = $Scaffold->controller->viewVars;

        $this->assertEqual($result['title_for_layout'], 'Scaffold :: Admin Edit :: Scaffold Mock');
        $this->assertEqual($result['singularHumanName'], 'Scaffold Mock');
        $this->assertEqual($result['pluralHumanName'], 'Scaffold Mock');
        $this->assertEqual($result['modelClass'], 'ScaffoldMock');
        $this->assertEqual($result['primaryKey'], 'id');
        $this->assertEqual($result['displayField'], 'title');
        $this->assertEqual($result['singularVar'], 'scaffoldMock');
        $this->assertEqual($result['pluralVar'], 'scaffoldMock');
        $this->assertEqual($result['scaffoldFields'], ['id', 'user_id', 'title', 'body', 'published', 'created', 'updated']);
    }

    /**
     * test that Scaffold overrides the view property even if its set to 'Theme'.
     */
    public function testScaffoldChangingViewProperty()
    {
        $this->Controller->action = 'edit';
        $this->Controller->theme = 'test_theme';
        $this->Controller->view = 'Theme';
        $this->Controller->constructClasses();
        $Scaffold = new TestScaffoldMock($this->Controller, []);

        $this->assertEqual($this->Controller->view, 'Scaffold');
    }

    /**
     * test that scaffold outputs flash messages when sessions are unset.
     */
    public function testScaffoldFlashMessages()
    {
        $this->Controller->action = 'edit';
        $this->Controller->here = '/scaffold_mock';
        $this->Controller->webroot = '/';
        $params = [
            'plugin' => null,
            'pass' => [1],
            'form' => [],
            'named' => [],
            'url' => ['url' => 'scaffold_mock'],
            'controller' => 'scaffold_mock',
            'action' => 'edit',
        ];
        //set router.
        Router::reload();
        Router::setRequestInfo([$params, ['base' => '/', 'here' => '/scaffold_mock', 'webroot' => '/']]);
        $this->Controller->params = $params;
        $this->Controller->controller = 'scaffold_mock';
        $this->Controller->base = '/';
        $this->Controller->data = [
            'ScaffoldMock' => [
                'id' => 1,
                'title' => 'New title',
                'body' => 'new body',
            ],
        ];
        $this->Controller->constructClasses();
        unset($this->Controller->Session);

        ob_start();
        new Scaffold($this->Controller, $params);
        $result = ob_get_clean();
        $this->assertPattern('/Scaffold Mock has been updated/', $result);
    }

    /**
     * test that habtm relationship keys get added to scaffoldFields.
     *
     * @see http://code.cakephp.org/tickets/view/48
     */
    public function testHabtmFieldAdditionWithScaffoldForm()
    {
        $this->Controller->action = 'edit';
        $this->Controller->here = '/scaffold_mock';
        $this->Controller->webroot = '/';
        $params = [
            'plugin' => null,
            'pass' => [1],
            'form' => [],
            'named' => [],
            'url' => ['url' => 'scaffold_mock'],
            'controller' => 'scaffold_mock',
            'action' => 'edit',
        ];
        //set router.
        Router::reload();
        Router::setRequestInfo([$params, ['base' => '/', 'here' => '/scaffold_mock', 'webroot' => '/']]);
        $this->Controller->params = $params;
        $this->Controller->controller = 'scaffold_mock';
        $this->Controller->base = '/';
        $this->Controller->constructClasses();
        ob_start();
        $Scaffold = new Scaffold($this->Controller, $params);
        $result = ob_get_clean();
        $this->assertPattern('/name="data\[ScaffoldTag\]\[ScaffoldTag\]"/', $result);

        $result = $Scaffold->controller->viewVars;
        $this->assertEqual($result['scaffoldFields'], ['id', 'user_id', 'title', 'body', 'published', 'created', 'updated', 'ScaffoldTag']);
    }

    /**
     * test that the proper names and variable values are set by Scaffold.
     */
    public function testEditScaffoldWithScaffoldFields()
    {
        $this->Controller = new ScaffoldMockControllerWithFields();
        $this->Controller->action = 'edit';
        $this->Controller->here = '/scaffold_mock';
        $this->Controller->webroot = '/';
        $params = [
            'plugin' => null,
            'pass' => [1],
            'form' => [],
            'named' => [],
            'url' => ['url' => 'scaffold_mock'],
            'controller' => 'scaffold_mock',
            'action' => 'edit',
        ];
        //set router.
        Router::reload();
        Router::setRequestInfo([$params, ['base' => '/', 'here' => '/scaffold_mock', 'webroot' => '/']]);
        $this->Controller->params = $params;
        $this->Controller->controller = 'scaffold_mock';
        $this->Controller->base = '/';
        $this->Controller->constructClasses();
        ob_start();
        new Scaffold($this->Controller, $params);
        $result = ob_get_clean();

        $this->assertNoPattern('/textarea name="data\[ScaffoldMock\]\[body\]" cols="30" rows="6" id="ScaffoldMockBody"/', $result);
    }
}
