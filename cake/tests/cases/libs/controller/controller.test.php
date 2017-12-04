<?php
/**
 * ControllerTest file.
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * @see          http://cakephp.org CakePHP Project
 * @since         CakePHP(tm) v 1.2.0.5436
 *
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::import('Controller', 'Controller', false);
App::import('Component', 'Security');
App::import('Component', 'Cookie');

/*
 * AppController class
 *
 * @package       cake
 * @subpackage    cake.tests.cases.libs.controller
 */
if (!class_exists('AppController')) {
    /**
     * AppController class.
     */
    class AppController extends Controller
    {
        /**
         * helpers property.
         *
         * @var array
         */
        public $helpers = ['Html', 'Javascript'];
        /**
         * uses property.
         *
         * @var array
         */
        public $uses = ['ControllerPost'];
        /**
         * components property.
         *
         * @var array
         */
        public $components = ['Cookie'];
    }
} elseif (!defined('APP_CONTROLLER_EXISTS')) {
    define('APP_CONTROLLER_EXISTS', true);
}

/**
 * ControllerPost class.
 */
class ControllerPost extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'ControllerPost'
     */
    public $name = 'ControllerPost';

    /**
     * useTable property.
     *
     * @var string 'posts'
     */
    public $useTable = 'posts';

    /**
     * invalidFields property.
     *
     * @var array
     */
    public $invalidFields = ['name' => 'error_msg'];

    /**
     * lastQuery property.
     *
     * @var mixed null
     */
    public $lastQuery = null;

    /**
     * beforeFind method.
     *
     * @param mixed $query
     */
    public function beforeFind($query)
    {
        $this->lastQuery = $query;
    }

    /**
     * find method.
     *
     * @param mixed $type
     * @param array $options
     */
    public function find($type, $options = [])
    {
        if ('popular' == $type) {
            $conditions = [$this->name.'.'.$this->primaryKey.' > ' => '1'];
            $options = Set::merge($options, compact('conditions'));

            return parent::find('all', $options);
        }

        return parent::find($type, $options);
    }
}

/**
 * ControllerPostsController class.
 */
class ControllerCommentsController extends AppController
{
    /**
     * name property.
     *
     * @var string 'ControllerPost'
     */
    public $name = 'ControllerComments';
}

/**
 * ControllerComment class.
 */
class ControllerComment extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'ControllerComment'
     */
    public $name = 'Comment';

    /**
     * useTable property.
     *
     * @var string 'comments'
     */
    public $useTable = 'comments';

    /**
     * data property.
     *
     * @var array
     */
    public $data = ['name' => 'Some Name'];

    /**
     * alias property.
     *
     * @var string 'ControllerComment'
     */
    public $alias = 'ControllerComment';
}

/**
 * ControllerAlias class.
 */
class ControllerAlias extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'ControllerAlias'
     */
    public $name = 'ControllerAlias';

    /**
     * alias property.
     *
     * @var string 'ControllerSomeAlias'
     */
    public $alias = 'ControllerSomeAlias';

    /**
     * useTable property.
     *
     * @var string 'posts'
     */
    public $useTable = 'posts';
}

/**
 * ControllerPaginateModel class.
 */
class ControllerPaginateModel extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string
     */
    public $name = 'ControllerPaginateModel';

    /**
     * useTable property.
     *
     * @var string'
     */
    public $useTable = 'comments';

    /**
     * paginate method.
     */
    public function paginate($conditions, $fields, $order, $limit, $page, $recursive, $extra)
    {
        $this->extra = $extra;
    }

    /**
     * paginateCount.
     */
    public function paginateCount($conditions, $recursive, $extra)
    {
        $this->extraCount = $extra;
    }
}

/**
 * NameTest class.
 */
class NameTest extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'Name'
     */
    public $name = 'Name';

    /**
     * useTable property.
     *
     * @var string 'names'
     */
    public $useTable = 'comments';

    /**
     * alias property.
     *
     * @var string 'ControllerComment'
     */
    public $alias = 'Name';
}

/**
 * TestController class.
 */
class TestController extends AppController
{
    /**
     * name property.
     *
     * @var string 'Name'
     */
    public $name = 'TestController';

    /**
     * helpers property.
     *
     * @var array
     */
    public $helpers = ['Session', 'Xml'];

    /**
     * components property.
     *
     * @var array
     */
    public $components = ['Security'];

    /**
     * uses property.
     *
     * @var array
     */
    public $uses = ['ControllerComment', 'ControllerAlias'];

    /**
     * index method.
     *
     * @param mixed $testId
     * @param mixed $test2Id
     */
    public function index($testId, $test2Id)
    {
        $this->data['testId'] = $testId;
        $this->data['test2Id'] = $test2Id;
    }
}

/**
 * TestComponent class.
 */
class TestComponent extends Object
{
    /**
     * beforeRedirect method.
     */
    public function beforeRedirect()
    {
    }

    /**
     * initialize method.
     */
    public function initialize(&$controller)
    {
    }

    /**
     * startup method.
     */
    public function startup(&$controller)
    {
    }

    /**
     * shutdown method.
     */
    public function shutdown(&$controller)
    {
    }

    /**
     * beforeRender callback.
     */
    public function beforeRender(&$controller)
    {
        if ($this->viewclass) {
            $controller->view = $this->viewclass;
        }
    }
}

/**
 * AnotherTestController class.
 */
class AnotherTestController extends AppController
{
    /**
     * name property.
     *
     * @var string 'Name'
     */
    public $name = 'AnotherTest';
    /**
     * uses property.
     *
     * @var array
     */
    public $uses = null;
}

/**
 * ControllerTest class.
 */
class ControllerTest extends CakeTestCase
{
    /**
     * fixtures property.
     *
     * @var array
     */
    public $fixtures = ['core.post', 'core.comment', 'core.name'];

    /**
     * endTest.
     */
    public function endTest()
    {
        App::build();
    }

    /**
     * testLoadModel method.
     */
    public function testLoadModel()
    {
        $Controller = new Controller();

        $this->assertFalse(isset($Controller->ControllerPost));

        $result = $Controller->loadModel('ControllerPost');
        $this->assertTrue($result);
        $this->assertTrue(is_a($Controller->ControllerPost, 'ControllerPost'));
        $this->assertTrue(in_array('ControllerPost', $Controller->modelNames));

        ClassRegistry::flush();
        unset($Controller);
    }

    /**
     * testConstructClasses method.
     */
    public function testConstructClasses()
    {
        $Controller = new Controller();
        $Controller->modelClass = 'ControllerPost';
        $Controller->passedArgs[] = '1';
        $Controller->constructClasses();
        $this->assertEqual($Controller->ControllerPost->id, 1);

        unset($Controller);

        $Controller = new Controller();
        $Controller->uses = ['ControllerPost', 'ControllerComment'];
        $Controller->passedArgs[] = '1';
        $Controller->constructClasses();
        $this->assertTrue(is_a($Controller->ControllerPost, 'ControllerPost'));
        $this->assertTrue(is_a($Controller->ControllerComment, 'ControllerComment'));

        $this->assertEqual($Controller->ControllerComment->name, 'Comment');

        unset($Controller);

        App::build(['plugins' => [TEST_CAKE_CORE_INCLUDE_PATH.'tests'.DS.'test_app'.DS.'plugins'.DS]]);

        $Controller = new Controller();
        $Controller->uses = ['TestPlugin.TestPluginPost'];
        $Controller->constructClasses();

        $this->assertEqual($Controller->modelClass, 'TestPluginPost');
        $this->assertTrue(isset($Controller->TestPluginPost));
        $this->assertTrue(is_a($Controller->TestPluginPost, 'TestPluginPost'));

        unset($Controller);
    }

    /**
     * testAliasName method.
     */
    public function testAliasName()
    {
        $Controller = new Controller();
        $Controller->uses = ['NameTest'];
        $Controller->constructClasses();

        $this->assertEqual($Controller->NameTest->name, 'Name');
        $this->assertEqual($Controller->NameTest->alias, 'Name');

        unset($Controller);
    }

    /**
     * testPersistent method.
     */
    public function testPersistent()
    {
        Configure::write('Cache.disable', false);
        $Controller = new Controller();
        $Controller->modelClass = 'ControllerPost';
        $Controller->persistModel = true;
        $Controller->constructClasses();
        $this->assertTrue(file_exists(CACHE.'persistent'.DS.'controllerpost.php'));
        $this->assertTrue(is_a($Controller->ControllerPost, 'ControllerPost'));
        @unlink(CACHE.'persistent'.DS.'controllerpost.php');
        @unlink(CACHE.'persistent'.DS.'controllerpostregistry.php');

        unset($Controller);
        Configure::write('Cache.disable', true);
    }

    /**
     * testPaginate method.
     */
    public function testPaginate()
    {
        $Controller = new Controller();
        $Controller->uses = ['ControllerPost', 'ControllerComment'];
        $Controller->passedArgs[] = '1';
        $Controller->params['url'] = [];
        $Controller->constructClasses();

        $results = Set::extract($Controller->paginate('ControllerPost'), '{n}.ControllerPost.id');
        $this->assertEqual($results, [1, 2, 3]);

        $results = Set::extract($Controller->paginate('ControllerComment'), '{n}.ControllerComment.id');
        $this->assertEqual($results, [1, 2, 3, 4, 5, 6]);

        $Controller->modelClass = null;

        $Controller->uses[0] = 'Plugin.ControllerPost';
        $results = Set::extract($Controller->paginate(), '{n}.ControllerPost.id');
        $this->assertEqual($results, [1, 2, 3]);

        $Controller->passedArgs = ['page' => '-1'];
        $results = Set::extract($Controller->paginate('ControllerPost'), '{n}.ControllerPost.id');
        $this->assertEqual($Controller->params['paging']['ControllerPost']['page'], 1);
        $this->assertEqual($results, [1, 2, 3]);

        $Controller->passedArgs = ['sort' => 'ControllerPost.id', 'direction' => 'asc'];
        $results = Set::extract($Controller->paginate('ControllerPost'), '{n}.ControllerPost.id');
        $this->assertEqual($Controller->params['paging']['ControllerPost']['page'], 1);
        $this->assertEqual($results, [1, 2, 3]);

        $Controller->passedArgs = ['sort' => 'ControllerPost.id', 'direction' => 'desc'];
        $results = Set::extract($Controller->paginate('ControllerPost'), '{n}.ControllerPost.id');
        $this->assertEqual($Controller->params['paging']['ControllerPost']['page'], 1);
        $this->assertEqual($results, [3, 2, 1]);

        $Controller->passedArgs = ['sort' => 'id', 'direction' => 'desc'];
        $results = Set::extract($Controller->paginate('ControllerPost'), '{n}.ControllerPost.id');
        $this->assertEqual($Controller->params['paging']['ControllerPost']['page'], 1);
        $this->assertEqual($results, [3, 2, 1]);

        $Controller->passedArgs = ['sort' => 'NotExisting.field', 'direction' => 'desc'];
        $results = Set::extract($Controller->paginate('ControllerPost'), '{n}.ControllerPost.id');
        $this->assertEqual($Controller->params['paging']['ControllerPost']['page'], 1, 'Invalid field in query %s');
        $this->assertEqual($results, [1, 2, 3]);

        $Controller->passedArgs = ['sort' => 'ControllerPost.author_id', 'direction' => 'allYourBase'];
        $results = Set::extract($Controller->paginate('ControllerPost'), '{n}.ControllerPost.id');
        $this->assertEqual($Controller->ControllerPost->lastQuery['order'][0], ['ControllerPost.author_id' => 'asc']);
        $this->assertEqual($results, [1, 3, 2]);

        $Controller->passedArgs = ['page' => '1 " onclick="alert(\'xss\');">'];
        $Controller->paginate = ['limit' => 1];
        $Controller->paginate('ControllerPost');
        $this->assertIdentical($Controller->params['paging']['ControllerPost']['page'], 1, 'XSS exploit opened %s');
        $this->assertIdentical($Controller->params['paging']['ControllerPost']['options']['page'], 1, 'XSS exploit opened %s');

        $Controller->passedArgs = [];
        $Controller->paginate = ['limit' => 0];
        $Controller->paginate('ControllerPost');
        $this->assertIdentical($Controller->params['paging']['ControllerPost']['page'], 1);
        $this->assertIdentical($Controller->params['paging']['ControllerPost']['pageCount'], 3);
        $this->assertIdentical($Controller->params['paging']['ControllerPost']['prevPage'], false);
        $this->assertIdentical($Controller->params['paging']['ControllerPost']['nextPage'], true);

        $Controller->passedArgs = [];
        $Controller->paginate = ['limit' => 'garbage!'];
        $Controller->paginate('ControllerPost');
        $this->assertIdentical($Controller->params['paging']['ControllerPost']['page'], 1);
        $this->assertIdentical($Controller->params['paging']['ControllerPost']['pageCount'], 3);
        $this->assertIdentical($Controller->params['paging']['ControllerPost']['prevPage'], false);
        $this->assertIdentical($Controller->params['paging']['ControllerPost']['nextPage'], true);

        $Controller->passedArgs = [];
        $Controller->paginate = ['limit' => '-1'];
        $Controller->paginate('ControllerPost');
        $this->assertIdentical($Controller->params['paging']['ControllerPost']['page'], 1);
        $this->assertIdentical($Controller->params['paging']['ControllerPost']['pageCount'], 3);
        $this->assertIdentical($Controller->params['paging']['ControllerPost']['prevPage'], false);
        $this->assertIdentical($Controller->params['paging']['ControllerPost']['nextPage'], true);
    }

    /**
     * testPaginateExtraParams method.
     */
    public function testPaginateExtraParams()
    {
        $Controller = new Controller();
        $Controller->uses = ['ControllerPost', 'ControllerComment'];
        $Controller->passedArgs[] = '1';
        $Controller->params['url'] = [];
        $Controller->constructClasses();

        $Controller->passedArgs = ['page' => '-1', 'contain' => ['ControllerComment']];
        $result = $Controller->paginate('ControllerPost');
        $this->assertEqual($Controller->params['paging']['ControllerPost']['page'], 1);
        $this->assertEqual(Set::extract($result, '{n}.ControllerPost.id'), [1, 2, 3]);
        $this->assertTrue(!isset($Controller->ControllerPost->lastQuery['contain']));

        $Controller->passedArgs = ['page' => '-1'];
        $Controller->paginate = ['ControllerPost' => ['contain' => ['ControllerComment']]];
        $result = $Controller->paginate('ControllerPost');
        $this->assertEqual($Controller->params['paging']['ControllerPost']['page'], 1);
        $this->assertEqual(Set::extract($result, '{n}.ControllerPost.id'), [1, 2, 3]);
        $this->assertTrue(isset($Controller->ControllerPost->lastQuery['contain']));

        $Controller->paginate = ['ControllerPost' => ['popular', 'fields' => ['id', 'title']]];
        $result = $Controller->paginate('ControllerPost');
        $this->assertEqual(Set::extract($result, '{n}.ControllerPost.id'), [2, 3]);
        $this->assertEqual($Controller->ControllerPost->lastQuery['conditions'], ['ControllerPost.id > ' => '1']);

        $Controller->passedArgs = ['limit' => 12];
        $Controller->paginate = ['limit' => 30];
        $result = $Controller->paginate('ControllerPost');
        $paging = $Controller->params['paging']['ControllerPost'];

        $this->assertEqual($Controller->ControllerPost->lastQuery['limit'], 12);
        $this->assertEqual($paging['options']['limit'], 12);

        $Controller = new Controller();
        $Controller->uses = ['ControllerPaginateModel'];
        $Controller->params['url'] = [];
        $Controller->constructClasses();
        $Controller->paginate = [
            'ControllerPaginateModel' => ['contain' => ['ControllerPaginateModel'], 'group' => 'Comment.author_id'],
        ];
        $result = $Controller->paginate('ControllerPaginateModel');
        $expected = ['contain' => ['ControllerPaginateModel'], 'group' => 'Comment.author_id'];
        $this->assertEqual($Controller->ControllerPaginateModel->extra, $expected);
        $this->assertEqual($Controller->ControllerPaginateModel->extraCount, $expected);

        $Controller->paginate = [
            'ControllerPaginateModel' => ['foo', 'contain' => ['ControllerPaginateModel'], 'group' => 'Comment.author_id'],
        ];
        $Controller->paginate('ControllerPaginateModel');
        $expected = ['contain' => ['ControllerPaginateModel'], 'group' => 'Comment.author_id', 'type' => 'foo'];
        $this->assertEqual($Controller->ControllerPaginateModel->extra, $expected);
        $this->assertEqual($Controller->ControllerPaginateModel->extraCount, $expected);
    }

    /**
     * testPaginateFieldsDouble method.
     */
    public function testPaginateFieldsDouble()
    {
        $Controller = new Controller();
        $Controller->uses = ['ControllerPost'];
        $Controller->params['url'] = [];
        $Controller->constructClasses();

        $Controller->paginate = [
            'fields' => [
                'ControllerPost.id',
                'radians(180.0) as floatvalue',
            ],
            'order' => ['ControllerPost.created' => 'DESC'],
            'limit' => 1,
            'page' => 1,
            'recursive' => -1,
        ];
        $conditions = [];
        $result = $Controller->paginate('ControllerPost', $conditions);
        $expected = [
            [
                'ControllerPost' => [
                    'id' => 3,
                ],
                0 => [
                    'floatvalue' => '3.14159265358979',
                ],
            ],
        ];
        $this->assertEqual($result, $expected);
    }

    /**
     * testPaginatePassedArgs method.
     */
    public function testPaginatePassedArgs()
    {
        $Controller = new Controller();
        $Controller->uses = ['ControllerPost'];
        $Controller->passedArgs[] = ['1', '2', '3'];
        $Controller->params['url'] = [];
        $Controller->constructClasses();

        $Controller->paginate = [
            'fields' => [],
            'order' => '',
            'limit' => 5,
            'page' => 1,
            'recursive' => -1,
        ];
        $conditions = [];
        $Controller->paginate('ControllerPost', $conditions);

        $expected = [
            'fields' => [],
            'order' => '',
            'limit' => 5,
            'page' => 1,
            'recursive' => -1,
            'conditions' => [],
        ];
        $this->assertEqual($Controller->params['paging']['ControllerPost']['options'], $expected);
    }

    /**
     * Test that special paginate types are called and that the type param doesn't leak out into defaults or options.
     */
    public function testPaginateSpecialType()
    {
        $Controller = new Controller();
        $Controller->uses = ['ControllerPost', 'ControllerComment'];
        $Controller->passedArgs[] = '1';
        $Controller->params['url'] = [];
        $Controller->constructClasses();

        $Controller->paginate = ['ControllerPost' => ['popular', 'fields' => ['id', 'title']]];
        $result = $Controller->paginate('ControllerPost');

        $this->assertEqual(Set::extract($result, '{n}.ControllerPost.id'), [2, 3]);
        $this->assertEqual($Controller->ControllerPost->lastQuery['conditions'], ['ControllerPost.id > ' => '1']);
        $this->assertFalse(isset($Controller->params['paging']['ControllerPost']['defaults'][0]));
        $this->assertFalse(isset($Controller->params['paging']['ControllerPost']['options'][0]));
    }

    /**
     * testDefaultPaginateParams method.
     */
    public function testDefaultPaginateParams()
    {
        $Controller = new Controller();
        $Controller->modelClass = 'ControllerPost';
        $Controller->params['url'] = [];
        $Controller->paginate = ['order' => 'ControllerPost.id DESC'];
        $Controller->constructClasses();
        $results = Set::extract($Controller->paginate('ControllerPost'), '{n}.ControllerPost.id');
        $this->assertEqual($Controller->params['paging']['ControllerPost']['defaults']['order'], 'ControllerPost.id DESC');
        $this->assertEqual($Controller->params['paging']['ControllerPost']['options']['order'], 'ControllerPost.id DESC');
        $this->assertEqual($results, [3, 2, 1]);
    }

    /**
     * test paginate() and virtualField interactions.
     */
    public function testPaginateOrderVirtualField()
    {
        $Controller = new Controller();
        $Controller->uses = ['ControllerPost', 'ControllerComment'];
        $Controller->params['url'] = [];
        $Controller->constructClasses();
        $Controller->ControllerPost->virtualFields = [
            'offset_test' => 'ControllerPost.id + 1',
        ];

        $Controller->paginate = [
            'fields' => ['id', 'title', 'offset_test'],
            'order' => ['offset_test' => 'DESC'],
        ];
        $result = $Controller->paginate('ControllerPost');
        $this->assertEqual(Set::extract($result, '{n}.ControllerPost.offset_test'), [4, 3, 2]);

        $Controller->passedArgs = ['sort' => 'offset_test', 'direction' => 'asc'];
        $result = $Controller->paginate('ControllerPost');
        $this->assertEqual(Set::extract($result, '{n}.ControllerPost.offset_test'), [2, 3, 4]);
    }

    /**
     * test paginate() and virtualField overlapping with real fields.
     */
    public function testPaginateOrderVirtualFieldSharedWithRealField()
    {
        $Controller = new Controller();
        $Controller->uses = ['ControllerPost', 'ControllerComment'];
        $Controller->params['url'] = [];
        $Controller->constructClasses();
        $Controller->ControllerComment->virtualFields = [
            'title' => 'ControllerComment.comment',
        ];
        $Controller->ControllerComment->bindModel([
            'belongsTo' => [
                'ControllerPost' => [
                    'className' => 'ControllerPost',
                    'foreignKey' => 'article_id',
                ],
            ],
        ], false);

        $Controller->paginate = [
            'fields' => ['ControllerComment.id', 'title', 'ControllerPost.title'],
        ];
        $Controller->passedArgs = ['sort' => 'ControllerPost.title', 'dir' => 'asc'];
        $result = $Controller->paginate('ControllerComment');
        $this->assertEqual(Set::extract($result, '{n}.ControllerComment.id'), [1, 2, 3, 4, 5, 6]);
    }

    /**
     * testFlash method.
     */
    public function testFlash()
    {
        $Controller = new Controller();
        $Controller->flash('this should work', '/flash');
        $result = $Controller->output;

        $expected = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>this should work</title>
		<style><!--
		P { text-align:center; font:bold 1.1em sans-serif }
		A { color:#444; text-decoration:none }
		A:HOVER { text-decoration: underline; color:#44E }
		--></style>
		</head>
		<body>
		<p><a href="/flash">this should work</a></p>
		</body>
		</html>';
        $result = str_replace(["\t", "\r\n", "\n"], '', $result);
        $expected = str_replace(["\t", "\r\n", "\n"], '', $expected);
        $this->assertEqual($result, $expected);

        App::build(['views' => [TEST_CAKE_CORE_INCLUDE_PATH.'tests'.DS.'test_app'.DS.'views'.DS]]);
        $Controller = new Controller();
        $Controller->flash('this should work', '/flash', 1, 'ajax2');
        $result = $Controller->output;
        $this->assertPattern('/Ajax!/', $result);
        App::build();
    }

    /**
     * testControllerSet method.
     */
    public function testControllerSet()
    {
        $Controller = new Controller();
        $Controller->set('variable_with_underscores', null);
        $this->assertTrue(array_key_exists('variable_with_underscores', $Controller->viewVars));

        $Controller->viewVars = [];
        $viewVars = ['ModelName' => ['id' => 1, 'name' => 'value']];
        $Controller->set($viewVars);
        $this->assertTrue(array_key_exists('ModelName', $Controller->viewVars));

        $Controller->viewVars = [];
        $Controller->set('variable_with_underscores', 'value');
        $this->assertTrue(array_key_exists('variable_with_underscores', $Controller->viewVars));

        $Controller->viewVars = [];
        $viewVars = ['ModelName' => 'name'];
        $Controller->set($viewVars);
        $this->assertTrue(array_key_exists('ModelName', $Controller->viewVars));

        $Controller->set('title', 'someTitle');
        $this->assertIdentical($Controller->viewVars['title'], 'someTitle');
        $this->assertTrue(empty($Controller->pageTitle));

        $Controller->viewVars = [];
        $expected = ['ModelName' => 'name', 'ModelName2' => 'name2'];
        $Controller->set(['ModelName', 'ModelName2'], ['name', 'name2']);
        $this->assertIdentical($Controller->viewVars, $expected);

        $Controller->viewVars = [];
        $Controller->set([3 => 'three', 4 => 'four']);
        $Controller->set([1 => 'one', 2 => 'two']);
        $expected = [3 => 'three', 4 => 'four', 1 => 'one', 2 => 'two'];
        $this->assertEqual($Controller->viewVars, $expected);
    }

    /**
     * testRender method.
     */
    public function testRender()
    {
        App::build([
            'views' => [TEST_CAKE_CORE_INCLUDE_PATH.'tests'.DS.'test_app'.DS.'views'.DS],
        ], true);

        $Controller = new Controller();
        $Controller->viewPath = 'posts';

        $result = $Controller->render('index');
        $this->assertPattern('/posts index/', $result);

        $result = $Controller->render('/elements/test_element');
        $this->assertPattern('/this is the test element/', $result);

        $Controller = new TestController();
        $Controller->constructClasses();
        $Controller->ControllerComment->validationErrors = ['title' => 'tooShort'];
        $expected = $Controller->ControllerComment->validationErrors;

        ClassRegistry::flush();
        $Controller->viewPath = 'posts';
        $result = $Controller->render('index');
        $View = ClassRegistry::getObject('view');
        $this->assertTrue(isset($View->validationErrors['ControllerComment']));
        $this->assertEqual($expected, $View->validationErrors['ControllerComment']);

        $Controller->ControllerComment->validationErrors = [];
        ClassRegistry::flush();
        App::build();
    }

    /**
     * test that a component beforeRender can change the controller view class.
     */
    public function testComponentBeforeRenderChangingViewClass()
    {
        $core = App::core('views');
        App::build([
            'views' => [
                TEST_CAKE_CORE_INCLUDE_PATH.'tests'.DS.'test_app'.DS.'views'.DS,
                $core[0],
            ],
        ], true);
        $Controller = new Controller();
        $Controller->uses = [];
        $Controller->components = ['Test'];
        $Controller->constructClasses();
        $Controller->Test->viewclass = 'Theme';
        $Controller->viewPath = 'posts';
        $Controller->theme = 'test_theme';
        $result = $Controller->render('index');
        $this->assertPattern('/default test_theme layout/', $result);
        App::build();
    }

    /**
     * testToBeInheritedGuardmethods method.
     */
    public function testToBeInheritedGuardmethods()
    {
        $Controller = new Controller();
        $this->assertTrue($Controller->_beforeScaffold(''));
        $this->assertTrue($Controller->_afterScaffoldSave(''));
        $this->assertTrue($Controller->_afterScaffoldSaveError(''));
        $this->assertFalse($Controller->_scaffoldError(''));
    }

    /**
     * testRedirect method.
     */
    public function testRedirect()
    {
        $codes = [
            100 => 'Continue',
            101 => 'Switching Protocols',
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authoritative Information',
            204 => 'No Content',
            205 => 'Reset Content',
            206 => 'Partial Content',
            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Found',
            303 => 'See Other',
            304 => 'Not Modified',
            305 => 'Use Proxy',
            307 => 'Temporary Redirect',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            407 => 'Proxy Authentication Required',
            408 => 'Request Time-out',
            409 => 'Conflict',
            410 => 'Gone',
            411 => 'Length Required',
            412 => 'Precondition Failed',
            413 => 'Request Entity Too Large',
            414 => 'Request-URI Too Large',
            415 => 'Unsupported Media Type',
            416 => 'Requested range not satisfiable',
            417 => 'Expectation Failed',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Time-out',
        ];

        Mock::generatePartial('Controller', 'MockController', ['header']);
        Mock::generate('TestComponent', 'MockTestComponent');
        Mock::generate('TestComponent', 'MockTestBComponent');

        App::import('Helper', 'Cache');

        foreach ($codes as $code => $msg) {
            $MockController = new MockController();
            $MockController->Component = new Component();
            $MockController->Component->init($MockController);
            $MockController->expectAt(0, 'header', ["HTTP/1.1 {$code} {$msg}"]);
            $MockController->expectAt(1, 'header', ['Location: http://cakephp.org']);
            $MockController->expectCallCount('header', 2);
            $MockController->redirect('http://cakephp.org', (int) $code, false);
            $this->assertFalse($MockController->autoRender);
        }
        foreach ($codes as $code => $msg) {
            $MockController = new MockController();
            $MockController->Component = new Component();
            $MockController->Component->init($MockController);
            $MockController->expectAt(0, 'header', ["HTTP/1.1 {$code} {$msg}"]);
            $MockController->expectAt(1, 'header', ['Location: http://cakephp.org']);
            $MockController->expectCallCount('header', 2);
            $MockController->redirect('http://cakephp.org', $msg, false);
            $this->assertFalse($MockController->autoRender);
        }

        $MockController = new MockController();
        $MockController->Component = new Component();
        $MockController->Component->init($MockController);
        $MockController->expectAt(0, 'header', ['Location: http://www.example.org/users/login']);
        $MockController->expectCallCount('header', 1);
        $MockController->redirect('http://www.example.org/users/login', null, false);

        $MockController = new MockController();
        $MockController->Component = new Component();
        $MockController->Component->init($MockController);
        $MockController->expectAt(0, 'header', ['HTTP/1.1 301 Moved Permanently']);
        $MockController->expectAt(1, 'header', ['Location: http://www.example.org/users/login']);
        $MockController->expectCallCount('header', 2);
        $MockController->redirect('http://www.example.org/users/login', 301, false);

        $MockController = new MockController();
        $MockController->components = ['MockTest'];
        $MockController->Component = new Component();
        $MockController->Component->init($MockController);
        $MockController->MockTest->setReturnValue('beforeRedirect', null);
        $MockController->expectAt(0, 'header', ['HTTP/1.1 301 Moved Permanently']);
        $MockController->expectAt(1, 'header', ['Location: http://cakephp.org']);
        $MockController->expectCallCount('header', 2);
        $MockController->redirect('http://cakephp.org', 301, false);

        $MockController = new MockController();
        $MockController->components = ['MockTest'];
        $MockController->Component = new Component();
        $MockController->Component->init($MockController);
        $MockController->MockTest->setReturnValue('beforeRedirect', 'http://book.cakephp.org');
        $MockController->expectAt(0, 'header', ['HTTP/1.1 301 Moved Permanently']);
        $MockController->expectAt(1, 'header', ['Location: http://book.cakephp.org']);
        $MockController->expectCallCount('header', 2);
        $MockController->redirect('http://cakephp.org', 301, false);

        $MockController = new MockController();
        $MockController->components = ['MockTest'];
        $MockController->Component = new Component();
        $MockController->Component->init($MockController);
        $MockController->MockTest->setReturnValue('beforeRedirect', false);
        $MockController->expectNever('header');
        $MockController->redirect('http://cakephp.org', 301, false);

        $MockController = new MockController();
        $MockController->components = ['MockTest', 'MockTestB'];
        $MockController->Component = new Component();
        $MockController->Component->init($MockController);
        $MockController->MockTest->setReturnValue('beforeRedirect', 'http://book.cakephp.org');
        $MockController->MockTestB->setReturnValue('beforeRedirect', 'http://bakery.cakephp.org');
        $MockController->expectAt(0, 'header', ['HTTP/1.1 301 Moved Permanently']);
        $MockController->expectAt(1, 'header', ['Location: http://bakery.cakephp.org']);
        $MockController->expectCallCount('header', 2);
        $MockController->redirect('http://cakephp.org', 301, false);
    }

    /**
     * testMergeVars method.
     */
    public function testMergeVars()
    {
        if ($this->skipIf(defined('APP_CONTROLLER_EXISTS'), '%s Need a non-existent AppController')) {
            return;
        }

        $TestController = new TestController();
        $TestController->constructClasses();

        $testVars = get_class_vars('TestController');
        $appVars = get_class_vars('AppController');

        $components = is_array($appVars['components'])
                        ? array_merge($appVars['components'], $testVars['components'])
                        : $testVars['components'];
        if (!in_array('Session', $components)) {
            $components[] = 'Session';
        }
        $helpers = is_array($appVars['helpers'])
                    ? array_merge($appVars['helpers'], $testVars['helpers'])
                    : $testVars['helpers'];
        $uses = is_array($appVars['uses'])
                    ? array_merge($appVars['uses'], $testVars['uses'])
                    : $testVars['uses'];

        $this->assertEqual(count(array_diff_assoc(Set::normalize($TestController->helpers), Set::normalize($helpers))), 0);
        $this->assertEqual(count(array_diff($TestController->uses, $uses)), 0);
        $this->assertEqual(count(array_diff_assoc(Set::normalize($TestController->components), Set::normalize($components))), 0);

        $expected = ['ControllerComment', 'ControllerAlias', 'ControllerPost'];
        $this->assertEqual($expected, $TestController->uses, '$uses was merged incorrectly, AppController models should be last.');

        $TestController = new AnotherTestController();
        $TestController->constructClasses();

        $appVars = get_class_vars('AppController');
        $testVars = get_class_vars('AnotherTestController');

        $this->assertTrue(in_array('ControllerPost', $appVars['uses']));
        $this->assertNull($testVars['uses']);

        $this->assertFalse(isset($TestController->ControllerPost));

        $TestController = new ControllerCommentsController();
        $TestController->constructClasses();

        $appVars = get_class_vars('AppController');
        $testVars = get_class_vars('ControllerCommentsController');

        $this->assertTrue(in_array('ControllerPost', $appVars['uses']));
        $this->assertEqual(['ControllerPost'], $testVars['uses']);

        $this->assertTrue(isset($TestController->ControllerPost));
        $this->assertTrue(isset($TestController->ControllerComment));
    }

    /**
     * test that options from child classes replace those in the parent classes.
     */
    public function testChildComponentOptionsSupercedeParents()
    {
        if ($this->skipIf(defined('APP_CONTROLLER_EXISTS'), '%s Need a non-existent AppController')) {
            return;
        }
        $TestController = new TestController();
        $expected = ['foo'];
        $TestController->components = ['Cookie' => $expected];
        $TestController->constructClasses();
        $this->assertEqual($TestController->components['Cookie'], $expected);
    }

    /**
     * Ensure that __mergeVars is not being greedy and merging with
     * AppController when you make an instance of Controller.
     */
    public function testMergeVarsNotGreedy()
    {
        $Controller = new Controller();
        $Controller->components = [];
        $Controller->uses = [];
        $Controller->constructClasses();

        $this->assertFalse(isset($Controller->Session));
    }

    /**
     * testReferer method.
     */
    public function testReferer()
    {
        $Controller = new Controller();
        $_SERVER['HTTP_REFERER'] = 'http://cakephp.org';
        $result = $Controller->referer(null, false);
        $expected = 'http://cakephp.org';
        $this->assertIdentical($result, $expected);

        $_SERVER['HTTP_REFERER'] = '';
        $result = $Controller->referer('http://cakephp.org', false);
        $expected = 'http://cakephp.org';
        $this->assertIdentical($result, $expected);

        $_SERVER['HTTP_REFERER'] = '';
        $referer = [
            'controller' => 'pages',
            'action' => 'display',
            'home',
        ];
        $result = $Controller->referer($referer, false);
        $expected = 'http://'.env('HTTP_HOST').'/pages/display/home';
        $this->assertIdentical($result, $expected);

        $_SERVER['HTTP_REFERER'] = '';
        $result = $Controller->referer(null, false);
        $expected = '/';
        $this->assertIdentical($result, $expected);

        $_SERVER['HTTP_REFERER'] = FULL_BASE_URL.$Controller->webroot.'/some/path';
        $result = $Controller->referer(null, false);
        $expected = '/some/path';
        $this->assertIdentical($result, $expected);

        $Controller->webroot .= '/';
        $_SERVER['HTTP_REFERER'] = FULL_BASE_URL.$Controller->webroot.'/some/path';
        $result = $Controller->referer(null, false);
        $expected = '/some/path';
        $this->assertIdentical($result, $expected);

        $_SERVER['HTTP_REFERER'] = FULL_BASE_URL.$Controller->webroot.'some/path';
        $result = $Controller->referer(null, false);
        $expected = '/some/path';
        $this->assertIdentical($result, $expected);

        $Controller->webroot = '/recipe/';

        $_SERVER['HTTP_REFERER'] = FULL_BASE_URL.$Controller->webroot.'recipes/add';
        $result = $Controller->referer();
        $expected = '/recipes/add';
        $this->assertIdentical($result, $expected);
    }

    /**
     * testSetAction method.
     */
    public function testSetAction()
    {
        $TestController = new TestController();
        $TestController->setAction('index', 1, 2);
        $expected = ['testId' => 1, 'test2Id' => 2];
        $this->assertidentical($TestController->data, $expected);
    }

    /**
     * testUnimplementedIsAuthorized method.
     */
    public function testUnimplementedIsAuthorized()
    {
        $TestController = new TestController();
        $TestController->isAuthorized();
        $this->assertError();
    }

    /**
     * testValidateErrors method.
     */
    public function testValidateErrors()
    {
        $TestController = new TestController();
        $TestController->constructClasses();
        $this->assertFalse($TestController->validateErrors());
        $this->assertEqual($TestController->validate(), 0);

        $TestController->ControllerComment->invalidate('some_field', 'error_message');
        $TestController->ControllerComment->invalidate('some_field2', 'error_message2');
        $comment = new ControllerComment();
        $comment->set('someVar', 'data');
        $result = $TestController->validateErrors($comment);
        $expected = ['some_field' => 'error_message', 'some_field2' => 'error_message2'];
        $this->assertIdentical($result, $expected);
        $this->assertEqual($TestController->validate($comment), 2);
    }

    /**
     * test that validateErrors works with any old model.
     */
    public function testValidateErrorsOnArbitraryModels()
    {
        $TestController = new TestController();

        $Post = new ControllerPost();
        $Post->validate = ['title' => 'notEmpty'];
        $Post->set('title', '');
        $result = $TestController->validateErrors($Post);

        $expected = ['title' => 'This field cannot be left blank'];
        $this->assertEqual($result, $expected);
    }

    /**
     * testPostConditions method.
     */
    public function testPostConditions()
    {
        $Controller = new Controller();

        $data = [
            'Model1' => ['field1' => '23'],
            'Model2' => ['field2' => 'string'],
            'Model3' => ['field3' => '23'],
        ];
        $expected = [
            'Model1.field1' => '23',
            'Model2.field2' => 'string',
            'Model3.field3' => '23',
        ];
        $result = $Controller->postConditions($data);
        $this->assertIdentical($result, $expected);

        $data = [];
        $Controller->data = [
            'Model1' => ['field1' => '23'],
            'Model2' => ['field2' => 'string'],
            'Model3' => ['field3' => '23'],
        ];
        $expected = [
            'Model1.field1' => '23',
            'Model2.field2' => 'string',
            'Model3.field3' => '23',
        ];
        $result = $Controller->postConditions($data);
        $this->assertIdentical($result, $expected);

        $data = [];
        $Controller->data = [];
        $result = $Controller->postConditions($data);
        $this->assertNull($result);

        $data = [];
        $Controller->data = [
            'Model1' => ['field1' => '23'],
            'Model2' => ['field2' => 'string'],
            'Model3' => ['field3' => '23'],
        ];
        $ops = [
            'Model1.field1' => '>',
            'Model2.field2' => 'LIKE',
            'Model3.field3' => '<=',
        ];
        $expected = [
            'Model1.field1 >' => '23',
            'Model2.field2 LIKE' => '%string%',
            'Model3.field3 <=' => '23',
        ];
        $result = $Controller->postConditions($data, $ops);
        $this->assertIdentical($result, $expected);
    }

    /**
     * testRequestHandlerPrefers method.
     */
    public function testRequestHandlerPrefers()
    {
        Configure::write('debug', 2);
        $Controller = new Controller();
        $Controller->components = ['RequestHandler'];
        $Controller->modelClass = 'ControllerPost';
        $Controller->params['url']['ext'] = 'rss';
        $Controller->constructClasses();
        $Controller->Component->initialize($Controller);
        $Controller->beforeFilter();
        $Controller->Component->startup($Controller);

        $this->assertEqual($Controller->RequestHandler->prefers(), 'rss');
        unset($Controller);
    }

    /**
     * testControllerHttpCodes method.
     */
    public function testControllerHttpCodes()
    {
        $Controller = new Controller();
        $result = $Controller->httpCodes();
        $this->assertEqual(count($result), 39);

        $result = $Controller->httpCodes(100);
        $expected = [100 => 'Continue'];
        $this->assertEqual($result, $expected);

        $codes = [
            1337 => 'Undefined Unicorn',
            1729 => 'Hardy-Ramanujan Located',
        ];

        $result = $Controller->httpCodes($codes);
        $this->assertTrue($result);
        $this->assertEqual(count($Controller->httpCodes()), 41);

        $result = $Controller->httpCodes(1337);
        $expected = [1337 => 'Undefined Unicorn'];
        $this->assertEqual($result, $expected);

        $codes = [404 => 'Sorry Bro'];
        $result = $Controller->httpCodes($codes);
        $this->assertTrue($result);
        $this->assertEqual(count($Controller->httpCodes()), 41);

        $result = $Controller->httpCodes(404);
        $expected = [404 => 'Sorry Bro'];
        $this->assertEqual($result, $expected);
    }

    /**
     * Tests that the startup process calls the correct functions.
     */
    public function testStartupProcess()
    {
        Mock::generatePartial('AnotherTestController', 'MockedController', ['beforeFilter', 'afterFilter']);
        Mock::generate('TestComponent', 'MockTestComponent', ['startup', 'initialize']);
        $MockedController = new MockedController();
        $MockedController->components = ['MockTest'];
        $MockedController->Component = new Component();
        $MockedController->Component->init($MockedController);
        $MockedController->expectCallCount('beforeFilter', 1);
        $MockedController->MockTest->expectCallCount('initialize', 1);
        $MockedController->MockTest->expectCallCount('startup', 1);
        $MockedController->startupProcess();
    }

    /**
     * Tests that the shutdown process calls the correct functions.
     */
    public function testShutdownProcess()
    {
        Mock::generate('TestComponent', 'MockTestComponent', ['shutdown']);
        $MockedController = new MockedController();
        $MockedController->components = ['MockTest'];
        $MockedController->Component = new Component();
        $MockedController->Component->init($MockedController);
        $MockedController->expectCallCount('afterFilter', 1);
        $MockedController->MockTest->expectCallCount('shutdown', 1);
        $MockedController->shutdownProcess();
    }

    /**
     * Tests that the correct alias is selected.
     */
    public function testValidateSortAlias()
    {
        $Controller = new Controller();
        $Controller->uses = ['ControllerPost', 'ControllerComment'];
        $Controller->passedArgs[] = '1';
        $Controller->params['url'] = [];
        $Controller->constructClasses();
        $Controller->passedArgs = ['sort' => 'Derp.id', 'direction' => 'asc'];
        $results = Set::extract($Controller->paginate('ControllerPost'), '{n}.ControllerPost.id');
        $this->assertEqual($Controller->params['paging']['ControllerPost']['page'], 1);
        $this->assertEqual($results, [1, 2, 3]);
    }
}
