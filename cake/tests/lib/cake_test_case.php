<?php
/**
 * CakeTestCase file.
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
 * @since         CakePHP(tm) v 1.2.0.4667
 *
 * @license       http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */
if (!class_exists('dispatcher')) {
    require CAKE.'dispatcher.php';
}
require_once CAKE_TESTS_LIB.'cake_test_model.php';
require_once CAKE_TESTS_LIB.'cake_test_fixture.php';
App::import('Vendor', 'simpletest'.DS.'unit_tester');

/**
 * CakeTestDispatcher.
 */
class CakeTestDispatcher extends Dispatcher
{
    /**
     * controller property.
     *
     * @var Controller
     */
    public $controller;
    public $testCase;

    /**
     * testCase method.
     *
     * @param CakeTestCase $testCase
     */
    public function testCase(&$testCase)
    {
        $this->testCase = &$testCase;
    }

    /**
     * invoke method.
     *
     * @param Controller $controller
     * @param array      $params
     * @param bool       $missingAction
     *
     * @return Controller
     */
    public function _invoke(&$controller, $params, $missingAction = false)
    {
        $this->controller = &$controller;

        if (array_key_exists('layout', $params)) {
            $this->controller->layout = $params['layout'];
        }

        if (isset($this->testCase) && method_exists($this->testCase, 'startController')) {
            $this->testCase->startController($this->controller, $params);
        }

        $result = parent::_invoke($this->controller, $params, $missingAction);

        if (isset($this->testCase) && method_exists($this->testCase, 'endController')) {
            $this->testCase->endController($this->controller, $params);
        }

        return $result;
    }
}

/**
 * CakeTestCase class.
 */
class CakeTestCase extends UnitTestCase
{
    /**
     * Methods used internally.
     *
     * @var array
     */
    public $methods = ['start', 'end', 'startcase', 'endcase', 'starttest', 'endtest'];

    /**
     * By default, all fixtures attached to this class will be truncated and reloaded after each test.
     * Set this to false to handle manually.
     *
     * @var array
     */
    public $autoFixtures = true;

    /**
     * Set this to false to avoid tables to be dropped if they already exist.
     *
     * @var bool
     */
    public $dropTables = true;

    /**
     * Maps fixture class names to fixture identifiers as included in CakeTestCase::$fixtures.
     *
     * @var array
     */
    public $_fixtureClassMap = [];

    /**
     * truncated property.
     *
     * @var bool
     */
    public $__truncated = true;

    /**
     * savedGetData property.
     *
     * @var array
     */
    public $__savedGetData = [];

    /**
     * Called when a test case (group of methods) is about to start (to be overriden when needed.).
     *
     * @param string $method test method about to get executed
     */
    public function startCase()
    {
    }

    /**
     * Called when a test case (group of methods) has been executed (to be overriden when needed.).
     *
     * @param string $method test method about that was executed
     */
    public function endCase()
    {
    }

    /**
     * Called when a test case method is about to start (to be overriden when needed.).
     *
     * @param string $method test method about to get executed
     */
    public function startTest($method)
    {
    }

    /**
     * Called when a test case method has been executed (to be overriden when needed.).
     *
     * @param string $method test method about that was executed
     */
    public function endTest($method)
    {
    }

    /**
     * Overrides SimpleTestCase::assert to enable calling of skipIf() from within tests.
     *
     * @param Expectation $expectation
     * @param mixed       $compare
     * @param string      $message
     *
     * @return bool|null
     */
    public function assert(&$expectation, $compare, $message = '%s')
    {
        if ($this->_should_skip) {
            return;
        }

        return parent::assert($expectation, $compare, $message);
    }

    /**
     * Overrides SimpleTestCase::skipIf to provide a boolean return value.
     *
     * @param bool   $shouldSkip
     * @param string $message
     *
     * @return bool
     */
    public function skipIf($shouldSkip, $message = '%s')
    {
        parent::skipIf($shouldSkip, $message);

        return $shouldSkip;
    }

    /**
     * Callback issued when a controller's action is about to be invoked through testAction().
     *
     * @param Controller $controller controller that's about to be invoked
     * @param array      $params     additional parameters as sent by testAction()
     */
    public function startController(&$controller, $params = [])
    {
        if (isset($params['fixturize']) && ((is_array($params['fixturize']) && !empty($params['fixturize'])) || true === $params['fixturize'])) {
            if (!isset($this->db)) {
                $this->_initDb();
            }

            if (false === $controller->uses) {
                $list = [$controller->modelClass];
            } else {
                $list = is_array($controller->uses) ? $controller->uses : [$controller->uses];
            }

            $models = [];
            ClassRegistry::config(['ds' => $params['connection']]);

            foreach ($list as $name) {
                if ((is_array($params['fixturize']) && in_array($name, $params['fixturize'])) || true === $params['fixturize']) {
                    if (class_exists($name) || App::import('Model', $name)) {
                        $object = &ClassRegistry::init($name);
                        //switch back to specified datasource.
                        $object->setDataSource($params['connection']);
                        $db = &ConnectionManager::getDataSource($object->useDbConfig);
                        $db->cacheSources = false;

                        $models[$object->alias] = [
                            'table' => $object->table,
                            'model' => $object->alias,
                            'key' => strtolower($name),
                        ];
                    }
                }
            }
            ClassRegistry::config(['ds' => 'test_suite']);

            if (!empty($models) && isset($this->db)) {
                $this->_actionFixtures = [];

                foreach ($models as $model) {
                    $fixture = new CakeTestFixture($this->db);

                    $fixture->name = $model['model'].'Test';
                    $fixture->table = $model['table'];
                    $fixture->import = ['model' => $model['model'], 'records' => true];
                    $fixture->init();

                    $fixture->create($this->db);
                    $fixture->insert($this->db);
                    $this->_actionFixtures[] = &$fixture;
                }

                foreach ($models as $model) {
                    $object = &ClassRegistry::getObject($model['key']);
                    if (false !== $object) {
                        $object->setDataSource('test_suite');
                        $object->cacheSources = false;
                    }
                }
            }
        }
    }

    /**
     * Callback issued when a controller's action has been invoked through testAction().
     *
     * @param Controller $controller controller that has been invoked
     * @param array      $params     additional parameters as sent by testAction()
     */
    public function endController(&$controller, $params = [])
    {
        if (isset($this->db) && isset($this->_actionFixtures) && !empty($this->_actionFixtures) && $this->dropTables) {
            foreach ($this->_actionFixtures as $fixture) {
                $fixture->drop($this->db);
            }
        }
    }

    /**
     * Executes a Cake URL, and can get (depending on the $params['return'] value):.
     *
     * Params:
     * - 'return' has several possible values:
     *   1. 'result': Whatever the action returns (and also specifies $this->params['requested'] for controller)
     *   2. 'view': The rendered view, without the layout
     *   3. 'contents': The rendered view, within the layout.
     *   4. 'vars': the view vars
     *
     * - 'fixturize' - Set to true if you want to copy model data from 'connection' to the test_suite connection
     * - 'data' - The data you want to insert into $this->data in the controller.
     * - 'connection' - Which connection to use in conjunction with fixturize (defaults to 'default')
     * - 'method' - What type of HTTP method to simulate (defaults to post)
     *
     * @param string $url    Cake URL to execute (e.g: /articles/view/455)
     * @param mixed  $params Parameters (see above), or simply a string of what to return
     *
     * @return mixed Whatever is returned depending of requested result
     */
    public function testAction($url, $params = [])
    {
        $default = [
            'return' => 'result',
            'fixturize' => false,
            'data' => [],
            'method' => 'post',
            'connection' => 'default',
        ];

        if (is_string($params)) {
            $params = ['return' => $params];
        }
        $params = array_merge($default, $params);

        $toSave = [
            'case' => null,
            'group' => null,
            'app' => null,
            'output' => null,
            'show' => null,
            'plugin' => null,
        ];
        $this->__savedGetData = (empty($this->__savedGetData))
                ? array_intersect_key($_GET, $toSave)
                : $this->__savedGetData;

        $data = (!empty($params['data'])) ? $params['data'] : [];

        if ('get' == strtolower($params['method'])) {
            $_GET = array_merge($this->__savedGetData, $data);
            $_POST = [];
        } else {
            $_POST = ['data' => $data];
            $_GET = $this->__savedGetData;
        }

        $return = $params['return'];
        $params = array_diff_key($params, ['data' => null, 'method' => null, 'return' => null]);

        $dispatcher = new CakeTestDispatcher();
        $dispatcher->testCase($this);

        if ('result' != $return) {
            if ('contents' != $return) {
                $params['layout'] = false;
            }

            ob_start();
            @$dispatcher->dispatch($url, $params);
            $result = ob_get_clean();

            if ('vars' == $return) {
                $view = &ClassRegistry::getObject('view');
                $viewVars = $view->getVars();

                $result = [];

                foreach ($viewVars as $var) {
                    $result[$var] = $view->getVar($var);
                }

                if (!empty($view->pageTitle)) {
                    $result = array_merge($result, ['title' => $view->pageTitle]);
                }
            }
        } else {
            $params['return'] = 1;
            $params['bare'] = 1;
            $params['requested'] = 1;

            $result = @$dispatcher->dispatch($url, $params);
        }

        if (isset($this->_actionFixtures)) {
            unset($this->_actionFixtures);
        }
        ClassRegistry::flush();

        return $result;
    }

    /**
     * Announces the start of a test.
     *
     * @param string $method test method just started
     */
    public function before($method)
    {
        parent::before($method);

        if (isset($this->fixtures) && (!is_array($this->fixtures) || empty($this->fixtures))) {
            unset($this->fixtures);
        }

        // Set up DB connection
        if (isset($this->fixtures) && 'start' == strtolower($method)) {
            $this->_initDb();
            $this->_loadFixtures();
        }

        // Create records
        if (isset($this->_fixtures) && isset($this->db) && !in_array(strtolower($method), ['start', 'end']) && $this->__truncated && true == $this->autoFixtures) {
            foreach ($this->_fixtures as $fixture) {
                $inserts = $fixture->insert($this->db);
            }
        }

        if (!in_array(strtolower($method), $this->methods)) {
            $this->startTest($method);
        }
    }

    /**
     * Runs as first test to create tables.
     */
    public function start()
    {
        if (isset($this->_fixtures) && isset($this->db)) {
            Configure::write('Cache.disable', true);
            $cacheSources = $this->db->cacheSources;
            $this->db->cacheSources = false;
            $sources = $this->db->listSources();
            $this->db->cacheSources = $cacheSources;

            if (!$this->dropTables) {
                return;
            }
            foreach ($this->_fixtures as $fixture) {
                $table = $this->db->config['prefix'].$fixture->table;
                if (in_array($table, $sources)) {
                    $fixture->drop($this->db);
                    $fixture->create($this->db);
                } elseif (!in_array($table, $sources)) {
                    $fixture->create($this->db);
                }
            }
        }
    }

    /**
     * Runs as last test to drop tables.
     */
    public function end()
    {
        if (isset($this->_fixtures) && isset($this->db)) {
            if ($this->dropTables) {
                foreach (array_reverse($this->_fixtures) as $fixture) {
                    $fixture->drop($this->db);
                }
            }
            $this->db->sources(true);
            Configure::write('Cache.disable', false);
        }

        if (class_exists('ClassRegistry')) {
            ClassRegistry::flush();
        }
    }

    /**
     * Announces the end of a test.
     *
     * @param string $method test method just finished
     */
    public function after($method)
    {
        $isTestMethod = !in_array(strtolower($method), ['start', 'end']);

        if (isset($this->_fixtures) && isset($this->db) && $isTestMethod) {
            foreach (array_reverse($this->_fixtures) as $fixture) {
                $fixture->truncate($this->db);
            }
            $this->__truncated = true;
        } else {
            $this->__truncated = false;
        }

        if (!in_array(strtolower($method), $this->methods)) {
            $this->endTest($method);
        }
        $this->_should_skip = false;

        parent::after($method);
    }

    /**
     * Gets a list of test names. Normally that will be all internal methods that start with the
     * name "test". This method should be overridden if you want a different rule.
     *
     * @return array list of test names
     */
    public function getTests()
    {
        return array_merge(
            ['start', 'startCase'],
            array_diff(parent::getTests(), ['testAction', 'testaction']),
            ['endCase', 'end']
        );
    }

    /**
     * Chooses which fixtures to load for a given test.
     *
     * @param string $fixture Each parameter is a model name that corresponds to a
     *                        fixture, i.e. 'Post', 'Author', etc.
     *
     * @see CakeTestCase::$autoFixtures
     */
    public function loadFixtures()
    {
        $args = func_get_args();
        foreach ($args as $class) {
            if (isset($this->_fixtureClassMap[$class])) {
                $fixture = $this->_fixtures[$this->_fixtureClassMap[$class]];

                $fixture->truncate($this->db);
                $fixture->insert($this->db);
            } else {
                trigger_error(sprintf(__('Referenced fixture class %s not found', true), $class), E_USER_WARNING);
            }
        }
    }

    /**
     * Takes an array $expected and generates a regex from it to match the provided $string.
     * Samples for $expected:.
     *
     * Checks for an input tag with a name attribute (contains any non-empty value) and an id
     * attribute that contains 'my-input':
     * 	array('input' => array('name', 'id' => 'my-input'))
     *
     * Checks for two p elements with some text in them:
     * 	array(
     * 		array('p' => true),
     * 		'textA',
     * 		'/p',
     * 		array('p' => true),
     * 		'textB',
     * 		'/p'
     *	)
     *
     * You can also specify a pattern expression as part of the attribute values, or the tag
     * being defined, if you prepend the value with preg: and enclose it with slashes, like so:
     *	array(
     *  	array('input' => array('name', 'id' => 'preg:/FieldName\d+/')),
     *  	'preg:/My\s+field/'
     *	)
     *
     * Important: This function is very forgiving about whitespace and also accepts any
     * permutation of attribute order. It will also allow whitespaces between specified tags.
     *
     * @param string $string   An HTML/XHTML/XML string
     * @param array  $expected An array, see above
     * @param string $message  SimpleTest failure output string
     *
     * @return bool
     */
    public function assertTags($string, $expected, $fullDebug = false)
    {
        $regex = [];
        $normalized = [];
        foreach ((array) $expected as $key => $val) {
            if (!is_numeric($key)) {
                $normalized[] = [$key => $val];
            } else {
                $normalized[] = $val;
            }
        }
        $i = 0;
        foreach ($normalized as $tags) {
            if (!is_array($tags)) {
                $tags = (string) $tags;
            }
            ++$i;
            if (is_string($tags) && '<' == $tags[0]) {
                $tags = [substr($tags, 1) => []];
            } elseif (is_string($tags)) {
                $tagsTrimmed = preg_replace('/\s+/m', '', $tags);

                if (preg_match('/^\*?\//', $tags, $match) && '//' !== $tagsTrimmed) {
                    $prefix = [null, null];

                    if ('*/' == $match[0]) {
                        $prefix = ['Anything, ', '.*?'];
                    }
                    $regex[] = [
                        sprintf('%sClose %s tag', $prefix[0], substr($tags, strlen($match[0]))),
                        sprintf('%s<[\s]*\/[\s]*%s[\s]*>[\n\r]*', $prefix[1], substr($tags, strlen($match[0]))),
                        $i,
                    ];
                    continue;
                }
                if (!empty($tags) && preg_match('/^preg\:\/(.+)\/$/i', $tags, $matches)) {
                    $tags = $matches[1];
                    $type = 'Regex matches';
                } else {
                    $tags = preg_quote($tags, '/');
                    $type = 'Text equals';
                }
                $regex[] = [
                    sprintf('%s "%s"', $type, $tags),
                    $tags,
                    $i,
                ];
                continue;
            }
            foreach ($tags as $tag => $attributes) {
                $regex[] = [
                    sprintf('Open %s tag', $tag),
                    sprintf('[\s]*<%s', preg_quote($tag, '/')),
                    $i,
                ];
                if (true === $attributes) {
                    $attributes = [];
                }
                $attrs = [];
                $explanations = [];
                $i = 1;
                foreach ($attributes as $attr => $val) {
                    if (is_numeric($attr) && preg_match('/^preg\:\/(.+)\/$/i', $val, $matches)) {
                        $attrs[] = $matches[1];
                        $explanations[] = sprintf('Regex "%s" matches', $matches[1]);
                        continue;
                    } else {
                        $quotes = '["\']';
                        if (is_numeric($attr)) {
                            $attr = $val;
                            $val = '.+?';
                            $explanations[] = sprintf('Attribute "%s" present', $attr);
                        } elseif (!empty($val) && preg_match('/^preg\:\/(.+)\/$/i', $val, $matches)) {
                            $quotes = '["\']?';
                            $val = $matches[1];
                            $explanations[] = sprintf('Attribute "%s" matches "%s"', $attr, $val);
                        } else {
                            $explanations[] = sprintf('Attribute "%s" == "%s"', $attr, $val);
                            $val = preg_quote($val, '/');
                        }
                        $attrs[] = '[\s]+'.preg_quote($attr, '/').'='.$quotes.$val.$quotes;
                    }
                    ++$i;
                }
                if ($attrs) {
                    $permutations = $this->__array_permute($attrs);

                    $permutationTokens = [];
                    foreach ($permutations as $permutation) {
                        $permutationTokens[] = implode('', $permutation);
                    }
                    $regex[] = [
                        sprintf('%s', implode(', ', $explanations)),
                        $permutationTokens,
                        $i,
                    ];
                }
                $regex[] = [
                    sprintf('End %s tag', $tag),
                    '[\s]*\/?[\s]*>[\n\r]*',
                    $i,
                ];
            }
        }
        foreach ($regex as $i => $assertation) {
            list($description, $expressions, $itemNum) = $assertation;
            $matches = false;
            foreach ((array) $expressions as $expression) {
                if (preg_match(sprintf('/^%s/s', $expression), $string, $match)) {
                    $matches = true;
                    $string = substr($string, strlen($match[0]));
                    break;
                }
            }
            if (!$matches) {
                $this->assert(new TrueExpectation(), false, sprintf('Item #%d / regex #%d failed: %s', $itemNum, $i, $description));
                if ($fullDebug) {
                    debug($string, true);
                    debug($regex, true);
                }

                return false;
            }
        }

        return $this->assert(new TrueExpectation(), true, '%s');
    }

    /**
     * Initialize DB connection.
     */
    public function _initDb()
    {
        $testDbAvailable = in_array('test', array_keys(ConnectionManager::enumConnectionObjects()));

        $_prefix = null;

        if ($testDbAvailable) {
            // Try for test DB
            restore_error_handler();
            @$db = &ConnectionManager::getDataSource('test');
            set_error_handler('simpleTestErrorHandler');
            $testDbAvailable = $db->isConnected();
        }

        // Try for default DB
        if (!$testDbAvailable) {
            $db = &ConnectionManager::getDataSource('default');
            $_prefix = $db->config['prefix'];
            $db->config['prefix'] = 'test_suite_';
        }

        ConnectionManager::create('test_suite', $db->config);
        $db->config['prefix'] = $_prefix;

        // Get db connection
        $this->db = &ConnectionManager::getDataSource('test_suite');
        $this->db->cacheSources = false;

        ClassRegistry::config(['ds' => 'test_suite']);
    }

    /**
     * Load fixtures specified in var $fixtures.
     */
    public function _loadFixtures()
    {
        if (!isset($this->fixtures) || empty($this->fixtures)) {
            return;
        }

        if (!is_array($this->fixtures)) {
            $this->fixtures = array_map('trim', explode(',', $this->fixtures));
        }

        $this->_fixtures = [];

        foreach ($this->fixtures as $index => $fixture) {
            $fixtureFile = null;

            if (0 === strpos($fixture, 'core.')) {
                $fixture = substr($fixture, strlen('core.'));
                foreach (App::core('cake') as $key => $path) {
                    $fixturePaths[] = $path.'tests'.DS.'fixtures';
                }
            } elseif (0 === strpos($fixture, 'app.')) {
                $fixture = substr($fixture, strlen('app.'));
                $fixturePaths = [
                    TESTS.'fixtures',
                    VENDORS.'tests'.DS.'fixtures',
                ];
            } elseif (0 === strpos($fixture, 'plugin.')) {
                $parts = explode('.', $fixture, 3);
                $pluginName = $parts[1];
                $fixture = $parts[2];
                $fixturePaths = [
                    App::pluginPath($pluginName).'tests'.DS.'fixtures',
                    TESTS.'fixtures',
                    VENDORS.'tests'.DS.'fixtures',
                ];
            } else {
                $fixturePaths = [
                    TESTS.'fixtures',
                    VENDORS.'tests'.DS.'fixtures',
                    TEST_CAKE_CORE_INCLUDE_PATH.DS.'cake'.DS.'tests'.DS.'fixtures',
                ];
            }

            foreach ($fixturePaths as $path) {
                if (is_readable($path.DS.$fixture.'_fixture.php')) {
                    $fixtureFile = $path.DS.$fixture.'_fixture.php';
                    break;
                }
            }

            if (isset($fixtureFile)) {
                require_once $fixtureFile;
                $fixtureClass = Inflector::camelize($fixture).'Fixture';
                $this->_fixtures[$this->fixtures[$index]] = new $fixtureClass($this->db);
                $this->_fixtureClassMap[Inflector::camelize($fixture)] = $this->fixtures[$index];
            }
        }

        if (empty($this->_fixtures)) {
            unset($this->_fixtures);
        }
    }

    /**
     * Generates all permutation of an array $items and returns them in a new array.
     *
     * @param array $items An array of items
     *
     * @return array
     */
    public function __array_permute($items, $perms = [])
    {
        static $permuted;
        if (empty($perms)) {
            $permuted = [];
        }

        if (empty($items)) {
            $permuted[] = $perms;
        } else {
            $numItems = count($items) - 1;
            for ($i = $numItems; $i >= 0; --$i) {
                $newItems = $items;
                $newPerms = $perms;
                list($tmp) = array_splice($newItems, $i, 1);
                array_unshift($newPerms, $tmp);
                $this->__array_permute($newItems, $newPerms);
            }

            return $permuted;
        }
    }
}
