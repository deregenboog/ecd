<?php
/**
 * BakeShell Test Case.
 *
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
 * @see          http://cakephp.org CakePHP(tm) Project
 * @since         CakePHP(tm) v 1.3
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

require_once CAKE.'console'.DS.'libs'.DS.'bake.php';
require_once CAKE.'console'.DS.'libs'.DS.'tasks'.DS.'model.php';
require_once CAKE.'console'.DS.'libs'.DS.'tasks'.DS.'controller.php';
require_once CAKE.'console'.DS.'libs'.DS.'tasks'.DS.'db_config.php';

Mock::generatePartial(
    'ShellDispatcher', 'BakeShellMockShellDispatcher',
    ['getInput', 'stdout', 'stderr', '_stop', '_initEnvironment']
);
Mock::generatePartial(
    'BakeShell', 'MockBakeShell',
    ['in', 'hr', 'out', 'err', 'createFile', '_stop', '_checkUnitTest']
);

Mock::generate('DbConfigTask', 'BakeShellMockDbConfigTask');
Mock::generate('ModelTask', 'BakeShellMockModelTask');
Mock::generate('ControllerTask', 'BakeShellMockControllerTask');

if (!class_exists('UsersController')) {
    class UsersController extends Controller
    {
        public $name = 'Users';
    }
}

class BakeShellTestCase extends CakeTestCase
{
    /**
     * fixtures.
     *
     * @var array
     */
    public $fixtures = ['core.user'];

    /**
     * start test.
     */
    public function startTest()
    {
        $this->Dispatch = new BakeShellMockShellDispatcher();
        $this->Shell = new MockBakeShell();
        $this->Shell->Dispatch = &$this->Dispatch;
        $this->Shell->Dispatch->shellPaths = App::path('shells');
    }

    /**
     * endTest method.
     */
    public function endTest()
    {
        unset($this->Dispatch, $this->Shell);
    }

    /**
     * test bake all.
     */
    public function testAllWithModelName()
    {
        App::import('Model', 'User');
        $userExists = class_exists('User');
        if ($this->skipIf($userExists, 'User class exists, cannot test `bake all [param]`. %s')) {
            return;
        }
        $this->Shell->Model = new BakeShellMockModelTask();
        $this->Shell->Controller = new BakeShellMockControllerTask();
        $this->Shell->View = new BakeShellMockModelTask();
        $this->Shell->DbConfig = new BakeShellMockDbConfigTask();

        $this->Shell->DbConfig->expectOnce('getConfig');
        $this->Shell->DbConfig->setReturnValue('getConfig', 'test_suite');

        $this->Shell->Model->setReturnValue('bake', true);
        $this->Shell->Model->expectNever('getName');
        $this->Shell->Model->expectOnce('bake');

        $this->Shell->Controller->expectOnce('bake');
        $this->Shell->Controller->setReturnValue('bake', true);

        $this->Shell->View->expectOnce('execute');

        $this->Shell->expectAt(0, 'out', ['Bake All']);
        $this->Shell->expectAt(1, 'out', ['User Model was baked.']);
        $this->Shell->expectAt(2, 'out', ['User Controller was baked.']);
        $this->Shell->expectAt(3, 'out', ['User Views were baked.']);
        $this->Shell->expectAt(4, 'out', ['Bake All complete']);

        $this->Shell->params = [];
        $this->Shell->args = ['User'];
        $this->Shell->all();
    }
}
