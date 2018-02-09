<?php
/**
 * TestTaskTest file.
 *
 * Test Case for test generation shell task
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
App::import('Shell', 'Shell', false);
App::import('Controller', 'Controller', false);
App::import('Model', 'Model', false);

if (!defined('DISABLE_AUTO_DISPATCH')) {
    define('DISABLE_AUTO_DISPATCH', true);
}

if (!class_exists('ShellDispatcher')) {
    ob_start();
    $argv = false;
    require CAKE.'console'.DS.'cake.php';
    ob_end_clean();
}

require_once CAKE.'console'.DS.'libs'.DS.'tasks'.DS.'test.php';
require_once CAKE.'console'.DS.'libs'.DS.'tasks'.DS.'template.php';

Mock::generatePartial(
    'ShellDispatcher', 'TestTestTaskMockShellDispatcher',
    ['getInput', 'stdout', 'stderr', '_stop', '_initEnvironment']
);
Mock::generatePartial(
    'TestTask', 'MockTestTask',
    ['in', '_stop', 'err', 'out', 'hr', 'createFile', 'isLoadableClass']
);

/**
 * Test Article model.
 */
class TestTaskArticle extends Model
{
    /**
     * Model name.
     *
     * @var string
     */
    public $name = 'TestTaskArticle';

    /**
     * Table name to use.
     *
     * @var string
     */
    public $useTable = 'articles';

    /**
     * HasMany Associations.
     *
     * @var array
     */
    public $hasMany = [
        'Comment' => [
            'className' => 'TestTask.TestTaskComment',
            'foreignKey' => 'article_id',
        ],
    ];

    /**
     * Has and Belongs To Many Associations.
     *
     * @var array
     */
    public $hasAndBelongsToMany = [
        'Tag' => [
            'className' => 'TestTaskTag',
            'joinTable' => 'articles_tags',
            'foreignKey' => 'article_id',
            'associationForeignKey' => 'tag_id',
        ],
    ];

    /**
     * Example public method.
     */
    public function doSomething()
    {
    }

    /**
     * Example Secondary public method.
     */
    public function doSomethingElse()
    {
    }

    /**
     * Example protected method.
     */
    public function _innerMethod()
    {
    }
}

/**
 * Tag Testing Model.
 */
class TestTaskTag extends Model
{
    /**
     * Model name.
     *
     * @var string
     */
    public $name = 'TestTaskTag';

    /**
     * Table name.
     *
     * @var string
     */
    public $useTable = 'tags';

    /**
     * Has and Belongs To Many Associations.
     *
     * @var array
     */
    public $hasAndBelongsToMany = [
        'Article' => [
            'className' => 'TestTaskArticle',
            'joinTable' => 'articles_tags',
            'foreignKey' => 'tag_id',
            'associationForeignKey' => 'article_id',
        ],
    ];
}

/**
 * Simulated plugin.
 */
class TestTaskAppModel extends Model
{
}

/**
 * Testing AppMode (TaskComment).
 */
class TestTaskComment extends TestTaskAppModel
{
    /**
     * Model name.
     *
     * @var string
     */
    public $name = 'TestTaskComment';

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
            'className' => 'TestTaskArticle',
            'foreignKey' => 'article_id',
        ],
    ];
}

/**
 * Test Task Comments Controller.
 */
class TestTaskCommentsController extends Controller
{
    /**
     * Controller Name.
     *
     * @var string
     */
    public $name = 'TestTaskComments';

    /**
     * Models to use.
     *
     * @var array
     */
    public $uses = ['TestTaskComment', 'TestTaskTag'];
}

/**
 * TestTaskTest class.
 */
class TestTaskTest extends CakeTestCase
{
    /**
     * Fixtures.
     *
     * @var string
     */
    public $fixtures = ['core.article', 'core.comment', 'core.articles_tag', 'core.tag'];

    /**
     * startTest method.
     */
    public function startTest()
    {
        $this->Dispatcher = new TestTestTaskMockShellDispatcher();
        $this->Dispatcher->shellPaths = App::path('shells');
        $this->Task = new MockTestTask($this->Dispatcher);
        $this->Task->name = 'TestTask';
        $this->Task->Dispatch = &$this->Dispatcher;
        $this->Task->Template = new TemplateTask($this->Dispatcher);
    }

    /**
     * endTest method.
     */
    public function endTest()
    {
        ClassRegistry::flush();
        App::build();
    }

    /**
     * Test that file path generation doesn't continuously append paths.
     */
    public function testFilePathGeneration()
    {
        $file = TESTS.'cases'.DS.'models'.DS.'my_class.test.php';

        $this->Task->Dispatch->expectNever('stderr');
        $this->Task->Dispatch->expectNever('_stop');

        $this->Task->setReturnValue('in', 'y');
        $this->Task->expectAt(0, 'createFile', [$file, '*']);
        $this->Task->bake('Model', 'MyClass');

        $this->Task->expectAt(1, 'createFile', [$file, '*']);
        $this->Task->bake('Model', 'MyClass');

        $file = TESTS.'cases'.DS.'controllers'.DS.'comments_controller.test.php';
        $this->Task->expectAt(2, 'createFile', [$file, '*']);
        $this->Task->bake('Controller', 'Comments');
    }

    /**
     * Test that method introspection pulls all relevant non parent class
     * methods into the test case.
     */
    public function testMethodIntrospection()
    {
        $result = $this->Task->getTestableMethods('TestTaskArticle');
        $expected = ['dosomething', 'dosomethingelse'];
        $this->assertEqual(array_map('strtolower', $result), $expected);
    }

    /**
     * test that the generation of fixtures works correctly.
     */
    public function testFixtureArrayGenerationFromModel()
    {
        $subject = ClassRegistry::init('TestTaskArticle');
        $result = $this->Task->generateFixtureList($subject);
        $expected = ['plugin.test_task.test_task_comment', 'app.articles_tags',
            'app.test_task_article', 'app.test_task_tag', ];

        $this->assertEqual(sort($result), sort($expected));
    }

    /**
     * test that the generation of fixtures works correctly.
     */
    public function testFixtureArrayGenerationFromController()
    {
        $subject = new TestTaskCommentsController();
        $result = $this->Task->generateFixtureList($subject);
        $expected = ['plugin.test_task.test_task_comment', 'app.articles_tags',
            'app.test_task_article', 'app.test_task_tag', ];

        $this->assertEqual(sort($result), sort($expected));
    }

    /**
     * test user interaction to get object type.
     */
    public function testGetObjectType()
    {
        $this->Task->expectOnce('_stop');
        $this->Task->setReturnValueAt(0, 'in', 'q');
        $this->Task->getObjectType();

        $this->Task->setReturnValueAt(1, 'in', 2);
        $result = $this->Task->getObjectType();
        $this->assertEqual($result, $this->Task->classTypes[1]);
    }

    /**
     * creating test subjects should clear the registry so the registry is always fresh.
     */
    public function testRegistryClearWhenBuildingTestObjects()
    {
        ClassRegistry::flush();
        $model = ClassRegistry::init('TestTaskComment');
        $model->bindModel([
            'belongsTo' => [
                'Random' => [
                    'className' => 'TestTaskArticle',
                    'foreignKey' => 'article_id',
                ],
            ],
        ]);
        $keys = ClassRegistry::keys();
        $this->assertTrue(in_array('random', $keys));
        $object = &$this->Task->buildTestSubject('Model', 'TestTaskComment');

        $keys = ClassRegistry::keys();
        $this->assertFalse(in_array('random', $keys));
    }

    /**
     * test that getClassName returns the user choice as a classname.
     */
    public function testGetClassName()
    {
        $objects = App::objects('model');
        $skip = $this->skipIf(empty($objects), 'No models in app, this test will fail. %s');
        if ($skip) {
            return;
        }
        $this->Task->setReturnValueAt(0, 'in', 'MyCustomClass');
        $result = $this->Task->getClassName('Model');
        $this->assertEqual($result, 'MyCustomClass');

        $this->Task->setReturnValueAt(1, 'in', 1);
        $result = $this->Task->getClassName('Model');
        $options = App::objects('model');
        $this->assertEqual($result, $options[0]);
    }

    /**
     * Test the user interaction for defining additional fixtures.
     */
    public function testGetUserFixtures()
    {
        $this->Task->setReturnValueAt(0, 'in', 'y');
        $this->Task->setReturnValueAt(1, 'in', 'app.pizza, app.topping, app.side_dish');
        $result = $this->Task->getUserFixtures();
        $expected = ['app.pizza', 'app.topping', 'app.side_dish'];
        $this->assertEqual($result, $expected);
    }

    /**
     * test that resolving classnames works.
     */
    public function testGetRealClassname()
    {
        $result = $this->Task->getRealClassname('Model', 'Post');
        $this->assertEqual($result, 'Post');

        $result = $this->Task->getRealClassname('Controller', 'Posts');
        $this->assertEqual($result, 'PostsController');

        $result = $this->Task->getRealClassname('Helper', 'Form');
        $this->assertEqual($result, 'FormHelper');

        $result = $this->Task->getRealClassname('Behavior', 'Containable');
        $this->assertEqual($result, 'ContainableBehavior');

        $result = $this->Task->getRealClassname('Component', 'Auth');
        $this->assertEqual($result, 'AuthComponent');
    }

    /**
     * test baking files.  The conditionally run tests are known to fail in PHP4
     * as PHP4 classnames are all lower case, breaking the plugin path inflection.
     */
    public function testBakeModelTest()
    {
        $this->Task->setReturnValue('createFile', true);
        $this->Task->setReturnValue('isLoadableClass', true);

        $result = $this->Task->bake('Model', 'TestTaskArticle');

        $this->assertPattern('/App::import\(\'Model\', \'TestTaskArticle\'\)/', $result);
        $this->assertPattern('/class TestTaskArticleTestCase extends CakeTestCase/', $result);

        $this->assertPattern('/function startTest\(\)/', $result);
        $this->assertPattern("/\\\$this->TestTaskArticle \=\& ClassRegistry::init\('TestTaskArticle'\)/", $result);

        $this->assertPattern('/function endTest\(\)/', $result);
        $this->assertPattern('/unset\(\$this->TestTaskArticle\)/', $result);

        $this->assertPattern('/function testDoSomething\(\)/i', $result);
        $this->assertPattern('/function testDoSomethingElse\(\)/i', $result);

        $this->assertPattern("/'app\.test_task_article'/", $result);
        if (PHP5) {
            $this->assertPattern("/'plugin\.test_task\.test_task_comment'/", $result);
        }
        $this->assertPattern("/'app\.test_task_tag'/", $result);
        $this->assertPattern("/'app\.articles_tag'/", $result);
    }

    /**
     * test baking controller test files, ensure that the stub class is generated.
     * Conditional assertion is known to fail on PHP4 as classnames are all lower case
     * causing issues with inflection of path name from classname.
     */
    public function testBakeControllerTest()
    {
        $this->Task->setReturnValue('createFile', true);
        $this->Task->setReturnValue('isLoadableClass', true);

        $result = $this->Task->bake('Controller', 'TestTaskComments');

        $this->assertPattern('/App::import\(\'Controller\', \'TestTaskComments\'\)/', $result);
        $this->assertPattern('/class TestTaskCommentsControllerTestCase extends CakeTestCase/', $result);

        $this->assertPattern('/class TestTestTaskCommentsController extends TestTaskCommentsController/', $result);
        $this->assertPattern('/var \$autoRender = false/', $result);
        $this->assertPattern('/function redirect\(\$url, \$status = null, \$exit = true\)/', $result);

        $this->assertPattern('/function startTest\(\)/', $result);
        $this->assertPattern("/\\\$this->TestTaskComments \=\& new TestTestTaskCommentsController\(\)/", $result);
        $this->assertPattern("/\\\$this->TestTaskComments->constructClasses\(\)/", $result);

        $this->assertPattern('/function endTest\(\)/', $result);
        $this->assertPattern('/unset\(\$this->TestTaskComments\)/', $result);

        $this->assertPattern("/'app\.test_task_article'/", $result);
        if (PHP5) {
            $this->assertPattern("/'plugin\.test_task\.test_task_comment'/", $result);
        }
        $this->assertPattern("/'app\.test_task_tag'/", $result);
        $this->assertPattern("/'app\.articles_tag'/", $result);
    }

    /**
     * test Constructor generation ensure that constructClasses is called for controllers.
     */
    public function testGenerateConstructor()
    {
        $result = $this->Task->generateConstructor('controller', 'PostsController');
        $expected = "new TestPostsController();\n\t\t\$this->Posts->constructClasses();\n";
        $this->assertEqual($result, $expected);

        $result = $this->Task->generateConstructor('model', 'Post');
        $expected = "ClassRegistry::init('Post');\n";
        $this->assertEqual($result, $expected);

        $result = $this->Task->generateConstructor('helper', 'FormHelper');
        $expected = "new FormHelper();\n";
        $this->assertEqual($result, $expected);
    }

    /**
     * Test that mock class generation works for the appropriate classes.
     */
    public function testMockClassGeneration()
    {
        $result = $this->Task->hasMockClass('controller');
        $this->assertTrue($result);
    }

    /**
     * test bake() with a -plugin param.
     */
    public function testBakeWithPlugin()
    {
        $this->Task->plugin = 'TestTest';

        $path = APP.'plugins'.DS.'test_test'.DS.'tests'.DS.'cases'.DS.'helpers'.DS.'form.test.php';
        $this->Task->expectAt(0, 'createFile', [$path, '*']);
        $this->Task->bake('Helper', 'Form');
    }

    /**
     * test interactive with plugins lists from the plugin.
     */
    public function testInteractiveWithPlugin()
    {
        $testApp = TEST_CAKE_CORE_INCLUDE_PATH.'tests'.DS.'test_app'.DS.'plugins'.DS;
        App::build([
            'plugins' => [$testApp],
        ], true);

        $this->Task->plugin = 'TestPlugin';
        $path = $testApp.'test_plugin'.DS.'tests'.DS.'cases'.DS.'helpers'.DS.'other_helper.test.php';
        $this->Task->setReturnValueAt(0, 'in', 5); //helper
        $this->Task->setReturnValueAt(1, 'in', 1); //OtherHelper
        $this->Task->expectAt(0, 'createFile', [$path, '*']);
        $this->Task->expectAt(9, 'out', ['1. OtherHelper']);
        $this->Task->execute();
    }

    /**
     * Test filename generation for each type + plugins.
     */
    public function testTestCaseFileName()
    {
        $this->Task->path = '/my/path/tests/';

        $result = $this->Task->testCaseFileName('Model', 'Post');
        $expected = $this->Task->path.'cases'.DS.'models'.DS.'post.test.php';
        $this->assertEqual($result, $expected);

        $result = $this->Task->testCaseFileName('Helper', 'Form');
        $expected = $this->Task->path.'cases'.DS.'helpers'.DS.'form.test.php';
        $this->assertEqual($result, $expected);

        $result = $this->Task->testCaseFileName('Controller', 'Posts');
        $expected = $this->Task->path.'cases'.DS.'controllers'.DS.'posts_controller.test.php';
        $this->assertEqual($result, $expected);

        $result = $this->Task->testCaseFileName('Behavior', 'Containable');
        $expected = $this->Task->path.'cases'.DS.'behaviors'.DS.'containable.test.php';
        $this->assertEqual($result, $expected);

        $result = $this->Task->testCaseFileName('Component', 'Auth');
        $expected = $this->Task->path.'cases'.DS.'components'.DS.'auth.test.php';
        $this->assertEqual($result, $expected);

        $this->Task->plugin = 'TestTest';
        $result = $this->Task->testCaseFileName('Model', 'Post');
        $expected = APP.'plugins'.DS.'test_test'.DS.'tests'.DS.'cases'.DS.'models'.DS.'post.test.php';
        $this->assertEqual($result, $expected);
    }

    /**
     * test execute with a type defined.
     */
    public function testExecuteWithOneArg()
    {
        $this->Task->args[0] = 'Model';
        $this->Task->setReturnValueAt(0, 'in', 'TestTaskTag');
        $this->Task->setReturnValue('isLoadableClass', true);
        $this->Task->expectAt(0, 'createFile', ['*', new PatternExpectation('/class TestTaskTagTestCase extends CakeTestCase/')]);
        $this->Task->execute();
    }

    /**
     * test execute with type and class name defined.
     */
    public function testExecuteWithTwoArgs()
    {
        $this->Task->args = ['Model', 'TestTaskTag'];
        $this->Task->setReturnValueAt(0, 'in', 'TestTaskTag');
        $this->Task->setReturnValue('isLoadableClass', true);
        $this->Task->expectAt(0, 'createFile', ['*', new PatternExpectation('/class TestTaskTagTestCase extends CakeTestCase/')]);
        $this->Task->execute();
    }
}
