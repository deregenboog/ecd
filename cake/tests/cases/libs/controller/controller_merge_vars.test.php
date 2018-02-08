<?php
/**
 * Controller Merge vars Test file.
 *
 * Isolated from the Controller and Component test as to not pollute their AppController class
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
 * @since         CakePHP(tm) v 1.2.3
 *
 * @license       http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */
if (!class_exists('AppController')) {
    /**
     * Test case AppController.
     */
    class AppController extends Controller
    {
        /**
         * components.
         *
         * @var array
         */
        public $components = ['MergeVar' => ['flag', 'otherFlag', 'redirect' => false]];
        /**
         * helpers.
         *
         * @var array
         */
        public $helpers = ['MergeVar' => ['format' => 'html', 'terse']];
    }
} elseif (!defined('APP_CONTROLLER_EXISTS')) {
    define('APP_CONTROLLER_EXISTS', true);
}

/**
 * MergeVar Component.
 */
class MergeVarComponent extends Object
{
}

/**
 * Additional controller for testing.
 */
class MergeVariablesController extends AppController
{
    /**
     * name.
     *
     * @var string
     */
    public $name = 'MergeVariables';

    /**
     * uses.
     *
     * @var arrays
     */
    public $uses = [];
}

/**
 * MergeVarPlugin App Controller.
 */
class MergeVarPluginAppController extends AppController
{
    /**
     * components.
     *
     * @var array
     */
    public $components = ['Auth' => ['setting' => 'val', 'otherVal']];

    /**
     * helpers.
     *
     * @var array
     */
    public $helpers = ['Javascript'];
}

/**
 * MergePostsController.
 */
class MergePostsController extends MergeVarPluginAppController
{
    /**
     * name.
     *
     * @var string
     */
    public $name = 'MergePosts';

    /**
     * uses.
     *
     * @var array
     */
    public $uses = [];
}

/**
 * Test Case for Controller Merging of Vars.
 */
class ControllerMergeVarsTestCase extends CakeTestCase
{
    /**
     * Skips the case if APP_CONTROLLER_EXISTS is defined.
     */
    public function skip()
    {
        $this->skipIf(defined('APP_CONTROLLER_EXISTS'), 'APP_CONTROLLER_EXISTS cannot run. %s');
    }

    /**
     * end test.
     */
    public function endTest()
    {
        ClassRegistry::flush();
    }

    /**
     * test that component settings are not duplicated when merging component settings.
     */
    public function testComponentParamMergingNoDuplication()
    {
        $Controller = new MergeVariablesController();
        $Controller->constructClasses();

        $expected = ['MergeVar' => ['flag', 'otherFlag', 'redirect' => false]];
        $this->assertEqual($Controller->components, $expected, 'Duplication of settings occured. %s');
    }

    /**
     * test component merges with redeclared components.
     */
    public function testComponentMergingWithRedeclarations()
    {
        $Controller = new MergeVariablesController();
        $Controller->components['MergeVar'] = ['remote', 'redirect' => true];
        $Controller->constructClasses();

        $expected = ['MergeVar' => ['flag', 'otherFlag', 'redirect' => true, 'remote']];
        $this->assertEqual($Controller->components, $expected, 'Merging of settings is wrong. %s');
    }

    /**
     * test merging of helpers array, ensure no duplication occurs.
     */
    public function testHelperSettingMergingNoDuplication()
    {
        $Controller = new MergeVariablesController();
        $Controller->constructClasses();

        $expected = ['MergeVar' => ['format' => 'html', 'terse']];
        $this->assertEqual($Controller->helpers, $expected, 'Duplication of settings occured. %s');
    }

    /**
     * Test that helpers declared in appcontroller come before those in the subclass
     * orderwise.
     */
    public function testHelperOrderPrecedence()
    {
        $Controller = new MergeVariablesController();
        $Controller->helpers = ['Custom', 'Foo' => ['something']];
        $Controller->constructClasses();

        $expected = [
            'MergeVar' => ['format' => 'html', 'terse'],
            'Custom' => null,
            'Foo' => ['something'],
        ];
        $this->assertIdentical($Controller->helpers, $expected, 'Order is incorrect. %s');
    }

    /**
     * test merging of vars with plugin.
     */
    public function testMergeVarsWithPlugin()
    {
        $Controller = new MergePostsController();
        $Controller->components = ['Email' => ['ports' => 'open']];
        $Controller->plugin = 'MergeVarPlugin';
        $Controller->constructClasses();

        $expected = [
            'MergeVar' => ['flag', 'otherFlag', 'redirect' => false],
            'Auth' => ['setting' => 'val', 'otherVal'],
            'Email' => ['ports' => 'open'],
        ];
        $this->assertEqual($Controller->components, $expected, 'Components are unexpected %s');

        $expected = [
            'MergeVar' => ['format' => 'html', 'terse'],
            'Javascript' => null,
        ];
        $this->assertEqual($Controller->helpers, $expected, 'Helpers are unexpected %s');

        $Controller = new MergePostsController();
        $Controller->components = [];
        $Controller->plugin = 'MergeVarPlugin';
        $Controller->constructClasses();

        $expected = [
            'MergeVar' => ['flag', 'otherFlag', 'redirect' => false],
            'Auth' => ['setting' => 'val', 'otherVal'],
        ];
        $this->assertEqual($Controller->components, $expected, 'Components are unexpected %s');
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
}
