<?php
/**
 * ViewTask Test file.
 *
 * Test Case for view generation shell task
 *
 * PHP versions 4 and 5
 *
 * CakePHP : Rapid Development Framework (http://cakephp.org)
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

require_once CAKE.'console'.DS.'libs'.DS.'tasks'.DS.'view.php';
require_once CAKE.'console'.DS.'libs'.DS.'tasks'.DS.'controller.php';
require_once CAKE.'console'.DS.'libs'.DS.'tasks'.DS.'template.php';
require_once CAKE.'console'.DS.'libs'.DS.'tasks'.DS.'project.php';

Mock::generatePartial(
    'ShellDispatcher', 'TestViewTaskMockShellDispatcher',
    ['getInput', 'stdout', 'stderr', '_stop', '_initEnvironment']
);
Mock::generatePartial(
    'ViewTask', 'MockViewTask',
    ['in', '_stop', 'err', 'out', 'createFile']
);

Mock::generate('ControllerTask', 'ViewTaskMockControllerTask');
Mock::generate('ProjectTask', 'ViewTaskMockProjectTask');

/**
 * Test View Task Comment Model.
 */
class ViewTaskComment extends Model
{
    /**
     * Model name.
     *
     * @var string
     */
    public $name = 'ViewTaskComment';

    /**
     * Table name.
     *
     * @var string
     */
    public $useTable = 'comments';

    /**
     * Belongs To Associations.
     *
     * @var array
     */
    public $belongsTo = [
        'Article' => [
            'className' => 'ViewTaskArticle',
            'foreignKey' => 'article_id',
        ],
    ];
}

/**
 * Test View Task Article Model.
 */
class ViewTaskArticle extends Model
{
    /**
     * Model name.
     *
     * @var string
     */
    public $name = 'ViewTaskArticle';

    /**
     * Table name.
     *
     * @var string
     */
    public $useTable = 'articles';
}

/**
 * Test View Task Comments Controller.
 */
class ViewTaskCommentsController extends Controller
{
    /**
     * Controller name.
     *
     * @var string
     */
    public $name = 'ViewTaskComments';

    /**
     * Testing public controller action.
     */
    public function index()
    {
    }

    /**
     * Testing public controller action.
     */
    public function add()
    {
    }
}

/**
 * Test View Task Articles Controller.
 */
class ViewTaskArticlesController extends Controller
{
    /**
     * Controller name.
     *
     * @var string
     */
    public $name = 'ViewTaskArticles';

    /**
     * Test public controller action.
     */
    public function index()
    {
    }

    /**
     * Test public controller action.
     */
    public function add()
    {
    }

    /**
     * Test admin prefixed controller action.
     */
    public function admin_index()
    {
    }

    /**
     * Test admin prefixed controller action.
     */
    public function admin_add()
    {
    }

    /**
     * Test admin prefixed controller action.
     */
    public function admin_view()
    {
    }

    /**
     * Test admin prefixed controller action.
     */
    public function admin_edit()
    {
    }

    /**
     * Test admin prefixed controller action.
     */
    public function admin_delete()
    {
    }
}

/**
 * ViewTaskTest class.
 */
class ViewTaskTest extends CakeTestCase
{
    /**
     * Fixtures.
     *
     * @var array
     */
    public $fixtures = ['core.article', 'core.comment', 'core.articles_tag', 'core.tag'];

    /**
     * startTest method.
     *
     * Ensure that the default theme is used
     */
    public function startTest()
    {
        $this->Dispatcher = new TestViewTaskMockShellDispatcher();
        $this->Dispatcher->shellPaths = App::path('shells');
        $this->Task = new MockViewTask($this->Dispatcher);
        $this->Task->name = 'ViewTask';
        $this->Task->Dispatch = &$this->Dispatcher;
        $this->Task->Template = new TemplateTask($this->Dispatcher);
        $this->Task->Controller = new ViewTaskMockControllerTask();
        $this->Task->Project = new ViewTaskMockProjectTask();
        $this->Task->DbConfig = new ViewTaskMockProjectTask();
        $this->Task->path = TMP;
        $this->Task->Template->params['theme'] = 'default';

        $this->_routing = Configure::read('Routing');
    }

    /**
     * endTest method.
     */
    public function endTest()
    {
        ClassRegistry::flush();
        Configure::write('Routing', $this->_routing);
    }

    /**
     * Test getContent and parsing of Templates.
     */
    public function testGetContent()
    {
        $vars = [
            'modelClass' => 'TestViewModel',
            'schema' => [],
            'primaryKey' => 'id',
            'displayField' => 'name',
            'singularVar' => 'testViewModel',
            'pluralVar' => 'testViewModels',
            'singularHumanName' => 'Test View Model',
            'pluralHumanName' => 'Test View Models',
            'fields' => ['id', 'name', 'body'],
            'associations' => [],
        ];
        $result = $this->Task->getContent('view', $vars);

        $this->assertPattern('/Delete Test View Model/', $result);
        $this->assertPattern('/Edit Test View Model/', $result);
        $this->assertPattern('/List Test View Models/', $result);
        $this->assertPattern('/New Test View Model/', $result);

        $this->assertPattern('/testViewModel\[\'TestViewModel\'\]\[\'id\'\]/', $result);
        $this->assertPattern('/testViewModel\[\'TestViewModel\'\]\[\'name\'\]/', $result);
        $this->assertPattern('/testViewModel\[\'TestViewModel\'\]\[\'body\'\]/', $result);
    }

    /**
     * test getContent() using an admin_prefixed action.
     */
    public function testGetContentWithAdminAction()
    {
        $_back = Configure::read('Routing');
        Configure::write('Routing.prefixes', ['admin']);
        $vars = [
            'modelClass' => 'TestViewModel',
            'schema' => [],
            'primaryKey' => 'id',
            'displayField' => 'name',
            'singularVar' => 'testViewModel',
            'pluralVar' => 'testViewModels',
            'singularHumanName' => 'Test View Model',
            'pluralHumanName' => 'Test View Models',
            'fields' => ['id', 'name', 'body'],
            'associations' => [],
        ];
        $result = $this->Task->getContent('admin_view', $vars);

        $this->assertPattern('/Delete Test View Model/', $result);
        $this->assertPattern('/Edit Test View Model/', $result);
        $this->assertPattern('/List Test View Models/', $result);
        $this->assertPattern('/New Test View Model/', $result);

        $this->assertPattern('/testViewModel\[\'TestViewModel\'\]\[\'id\'\]/', $result);
        $this->assertPattern('/testViewModel\[\'TestViewModel\'\]\[\'name\'\]/', $result);
        $this->assertPattern('/testViewModel\[\'TestViewModel\'\]\[\'body\'\]/', $result);

        $result = $this->Task->getContent('admin_add', $vars);
        $this->assertPattern("/input\('name'\)/", $result);
        $this->assertPattern("/input\('body'\)/", $result);
        $this->assertPattern('/List Test View Models/', $result);

        Configure::write('Routing', $_back);
    }

    /**
     * test Bake method.
     */
    public function testBake()
    {
        $this->Task->controllerName = 'ViewTaskComments';
        $this->Task->controllerPath = 'view_task_comments';

        $this->Task->expectAt(0, 'createFile', [
            TMP.'view_task_comments'.DS.'view.ctp',
            new PatternExpectation('/View Task Articles/'),
        ]);
        $this->Task->bake('view', true);

        $this->Task->expectAt(1, 'createFile', [TMP.'view_task_comments'.DS.'edit.ctp', '*']);
        $this->Task->bake('edit', true);

        $this->Task->expectAt(2, 'createFile', [
            TMP.'view_task_comments'.DS.'index.ctp',
            new PatternExpectation('/\$viewTaskComment\[\'Article\'\]\[\'title\'\]/'),
        ]);
        $this->Task->bake('index', true);
    }

    /**
     * test that baking a view with no template doesn't make a file.
     */
    public function testBakeWithNoTemplate()
    {
        $this->Task->controllerName = 'ViewTaskComments';
        $this->Task->controllerPath = 'view_task_comments';

        $this->Task->expectNever('createFile');
        $this->Task->bake('delete', true);
    }

    /**
     * test bake() with a -plugin param.
     */
    public function testBakeWithPlugin()
    {
        $this->Task->controllerName = 'ViewTaskComments';
        $this->Task->controllerPath = 'view_task_comments';
        $this->Task->plugin = 'TestTest';

        $path = APP.'plugins'.DS.'test_test'.DS.'views'.DS.'view_task_comments'.DS.'view.ctp';
        $this->Task->expectAt(0, 'createFile', [$path, '*']);
        $this->Task->bake('view', true);
    }

    /**
     * test bake actions baking multiple actions.
     */
    public function testBakeActions()
    {
        $this->Task->controllerName = 'ViewTaskComments';
        $this->Task->controllerPath = 'view_task_comments';

        $this->Task->expectAt(0, 'createFile', [
            TMP.'view_task_comments'.DS.'view.ctp',
            new PatternExpectation('/View Task Comments/'),
        ]);
        $this->Task->expectAt(1, 'createFile', [
            TMP.'view_task_comments'.DS.'edit.ctp',
            new PatternExpectation('/Edit View Task Comment/'),
        ]);
        $this->Task->expectAt(2, 'createFile', [
            TMP.'view_task_comments'.DS.'index.ctp',
            new PatternExpectation('/ViewTaskComment/'),
        ]);

        $this->Task->bakeActions(['view', 'edit', 'index'], []);
    }

    /**
     * test baking a customAction (non crud).
     */
    public function testCustomAction()
    {
        $this->Task->controllerName = 'ViewTaskComments';
        $this->Task->controllerPath = 'view_task_comments';
        $this->Task->params['app'] = APP;

        $this->Task->setReturnValueAt(0, 'in', '');
        $this->Task->setReturnValueAt(1, 'in', 'my_action');
        $this->Task->setReturnValueAt(2, 'in', 'y');
        $this->Task->expectAt(0, 'createFile', [TMP.'view_task_comments'.DS.'my_action.ctp', '*']);

        $this->Task->customAction();
    }

    /**
     * Test all().
     */
    public function testExecuteIntoAll()
    {
        $this->Task->args[0] = 'all';

        $this->Task->Controller->setReturnValue('listAll', ['view_task_comments']);
        $this->Task->Controller->expectOnce('listAll');

        $this->Task->expectCallCount('createFile', 2);
        $this->Task->expectAt(0, 'createFile', [TMP.'view_task_comments'.DS.'index.ctp', '*']);
        $this->Task->expectAt(1, 'createFile', [TMP.'view_task_comments'.DS.'add.ctp', '*']);

        $this->Task->execute();
    }

    /**
     * Test all() with action parameter.
     */
    public function testExecuteIntoAllWithActionName()
    {
        $this->Task->args = ['all', 'index'];

        $this->Task->Controller->setReturnValue('listAll', ['view_task_comments']);
        $this->Task->Controller->expectOnce('listAll');

        $this->Task->expectCallCount('createFile', 1);
        $this->Task->expectAt(0, 'createFile', [TMP.'view_task_comments'.DS.'index.ctp', '*']);

        $this->Task->execute();
    }

    /**
     * test `cake bake view $controller view`.
     */
    public function testExecuteWithActionParam()
    {
        $this->Task->args[0] = 'ViewTaskComments';
        $this->Task->args[1] = 'view';

        $this->Task->expectCallCount('createFile', 1);
        $this->Task->expectAt(0, 'createFile', [TMP.'view_task_comments'.DS.'view.ctp', '*']);
        $this->Task->execute();
    }

    /**
     * test `cake bake view $controller`
     * Ensure that views are only baked for actions that exist in the controller.
     */
    public function testExecuteWithController()
    {
        $this->Task->args[0] = 'ViewTaskComments';

        $this->Task->expectCallCount('createFile', 2);
        $this->Task->expectAt(0, 'createFile', [TMP.'view_task_comments'.DS.'index.ctp', '*']);
        $this->Task->expectAt(1, 'createFile', [TMP.'view_task_comments'.DS.'add.ctp', '*']);

        $this->Task->execute();
    }

    /**
     * test that both plural and singular forms can be used for baking views.
     */
    public function testExecuteWithControllerVariations()
    {
        $this->Task->args = ['ViewTaskComments'];

        $this->Task->expectAt(0, 'createFile', [TMP.'view_task_comments'.DS.'index.ctp', '*']);
        $this->Task->expectAt(1, 'createFile', [TMP.'view_task_comments'.DS.'add.ctp', '*']);
        $this->Task->execute();

        $this->Task->args = ['ViewTaskComment'];

        $this->Task->expectAt(0, 'createFile', [TMP.'view_task_comments'.DS.'index.ctp', '*']);
        $this->Task->expectAt(1, 'createFile', [TMP.'view_task_comments'.DS.'add.ctp', '*']);
        $this->Task->execute();
    }

    /**
     * test `cake bake view $controller -admin`
     * Which only bakes admin methods, not non-admin methods.
     */
    public function testExecuteWithControllerAndAdminFlag()
    {
        $_back = Configure::read('Routing');
        Configure::write('Routing.prefixes', ['admin']);
        $this->Task->args[0] = 'ViewTaskArticles';
        $this->Task->params['admin'] = 1;
        $this->Task->Project->setReturnValue('getPrefix', 'admin_');

        $this->Task->expectCallCount('createFile', 4);
        $this->Task->expectAt(0, 'createFile', [TMP.'view_task_articles'.DS.'admin_index.ctp', '*']);
        $this->Task->expectAt(1, 'createFile', [TMP.'view_task_articles'.DS.'admin_add.ctp', '*']);
        $this->Task->expectAt(2, 'createFile', [TMP.'view_task_articles'.DS.'admin_view.ctp', '*']);
        $this->Task->expectAt(3, 'createFile', [TMP.'view_task_articles'.DS.'admin_edit.ctp', '*']);

        $this->Task->execute();
        Configure::write('Routing', $_back);
    }

    /**
     * test execute into interactive.
     */
    public function testExecuteInteractive()
    {
        $this->Task->connection = 'test_suite';
        $this->Task->args = [];
        $this->Task->params = [];

        $this->Task->Controller->setReturnValue('getName', 'ViewTaskComments');
        $this->Task->setReturnValue('in', 'y');
        $this->Task->setReturnValueAt(0, 'in', 'y');
        $this->Task->setReturnValueAt(1, 'in', 'y');
        $this->Task->setReturnValueAt(2, 'in', 'n');

        $this->Task->expectCallCount('createFile', 4);
        $this->Task->expectAt(0, 'createFile', [
            TMP.'view_task_comments'.DS.'index.ctp',
            new PatternExpectation('/ViewTaskComment/'),
        ]);
        $this->Task->expectAt(1, 'createFile', [
            TMP.'view_task_comments'.DS.'view.ctp',
            new PatternExpectation('/ViewTaskComment/'),
        ]);
        $this->Task->expectAt(2, 'createFile', [
            TMP.'view_task_comments'.DS.'add.ctp',
            new PatternExpectation('/Add View Task Comment/'),
        ]);
        $this->Task->expectAt(3, 'createFile', [
            TMP.'view_task_comments'.DS.'edit.ctp',
            new PatternExpectation('/Edit View Task Comment/'),
        ]);

        $this->Task->execute();
    }

    /**
     * test `cake bake view posts index list`.
     */
    public function testExecuteWithAlternateTemplates()
    {
        $this->Task->connection = 'test_suite';
        $this->Task->args = ['ViewTaskComments', 'index', 'list'];
        $this->Task->params = [];

        $this->Task->expectCallCount('createFile', 1);
        $this->Task->expectAt(0, 'createFile', [
            TMP.'view_task_comments'.DS.'list.ctp',
            new PatternExpectation('/ViewTaskComment/'),
        ]);
        $this->Task->execute();
    }

    /**
     * test execute into interactive() with admin methods.
     */
    public function testExecuteInteractiveWithAdmin()
    {
        Configure::write('Routing.prefixes', ['admin']);
        $this->Task->connection = 'test_suite';
        $this->Task->args = [];

        $this->Task->Controller->setReturnValue('getName', 'ViewTaskComments');
        $this->Task->Project->setReturnValue('getPrefix', 'admin_');
        $this->Task->setReturnValueAt(0, 'in', 'y');
        $this->Task->setReturnValueAt(1, 'in', 'n');
        $this->Task->setReturnValueAt(2, 'in', 'y');

        $this->Task->expectCallCount('createFile', 4);
        $this->Task->expectAt(0, 'createFile', [
            TMP.'view_task_comments'.DS.'admin_index.ctp',
            new PatternExpectation('/ViewTaskComment/'),
        ]);
        $this->Task->expectAt(1, 'createFile', [
            TMP.'view_task_comments'.DS.'admin_view.ctp',
            new PatternExpectation('/ViewTaskComment/'),
        ]);
        $this->Task->expectAt(2, 'createFile', [
            TMP.'view_task_comments'.DS.'admin_add.ctp',
            new PatternExpectation('/Add View Task Comment/'),
        ]);
        $this->Task->expectAt(3, 'createFile', [
            TMP.'view_task_comments'.DS.'admin_edit.ctp',
            new PatternExpectation('/Edit View Task Comment/'),
        ]);

        $this->Task->execute();
    }

    /**
     * test getting templates, make sure noTemplateActions works.
     */
    public function testGetTemplate()
    {
        $result = $this->Task->getTemplate('delete');
        $this->assertFalse($result);

        $result = $this->Task->getTemplate('add');
        $this->assertEqual($result, 'form');

        Configure::write('Routing.prefixes', ['admin']);

        $result = $this->Task->getTemplate('admin_add');
        $this->assertEqual($result, 'form');
    }
}
