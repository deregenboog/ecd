<?php
/**
 * AclShell Test file.
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

if (!defined('DISABLE_AUTO_DISPATCH')) {
    define('DISABLE_AUTO_DISPATCH', true);
}

if (!class_exists('ShellDispatcher')) {
    ob_start();
    $argv = false;
    require CAKE.'console'.DS.'cake.php';
    ob_end_clean();
}

if (!class_exists('AclShell')) {
    require CAKE.'console'.DS.'libs'.DS.'acl.php';
}

Mock::generatePartial(
    'ShellDispatcher', 'TestAclShellMockShellDispatcher',
    ['getInput', 'stdout', 'stderr', '_stop', '_initEnvironment', 'dispatch']
);
Mock::generatePartial(
    'AclShell', 'MockAclShell',
    ['in', 'out', 'hr', 'createFile', 'error', 'err']
);

Mock::generate('AclComponent', 'MockAclShellAclComponent');

/**
 * AclShellTest class.
 */
class AclShellTest extends CakeTestCase
{
    /**
     * Fixtures.
     *
     * @var array
     */
    public $fixtures = ['core.aco', 'core.aro', 'core.aros_aco'];

    /**
     * configure Configure for testcase.
     */
    public function startCase()
    {
        $this->_aclDb = Configure::read('Acl.database');
        $this->_aclClass = Configure::read('Acl.classname');

        Configure::write('Acl.database', 'test_suite');
        Configure::write('Acl.classname', 'DbAcl');
    }

    /**
     * restore Environment settings.
     */
    public function endCase()
    {
        Configure::write('Acl.database', $this->_aclDb);
        Configure::write('Acl.classname', $this->_aclClass);
    }

    /**
     * startTest method.
     */
    public function startTest()
    {
        $this->Dispatcher = new TestAclShellMockShellDispatcher();
        $this->Task = new MockAclShell($this->Dispatcher);
        $this->Task->Dispatch = &$this->Dispatcher;
        $this->Task->params['datasource'] = 'test_suite';
        $this->Task->Acl = new AclComponent();
        $controller = null;
        $this->Task->Acl->startup($controller);
    }

    /**
     * endTest method.
     */
    public function endTest()
    {
        ClassRegistry::flush();
    }

    /**
     * test that model.foreign_key output works when looking at acl rows.
     */
    public function testViewWithModelForeignKeyOutput()
    {
        $this->Task->command = 'view';
        $this->Task->startup();
        $data = [
            'parent_id' => null,
            'model' => 'MyModel',
            'foreign_key' => 2,
        ];
        $this->Task->Acl->Aro->create($data);
        $this->Task->Acl->Aro->save();
        $this->Task->args[0] = 'aro';

        $this->Task->expectAt(0, 'out', ['Aro tree:']);
        $this->Task->expectAt(1, 'out', [new PatternExpectation('/\[1\] ROOT/')]);
        $this->Task->expectAt(3, 'out', [new PatternExpectation('/\[3\] Gandalf/')]);
        $this->Task->expectAt(5, 'out', [new PatternExpectation('/\[5\] MyModel.2/')]);

        $this->Task->view();
    }

    /**
     * test view with an argument.
     */
    public function testViewWithArgument()
    {
        $this->Task->args = ['aro', 'admins'];
        $this->Task->expectAt(0, 'out', ['Aro tree:']);
        $this->Task->expectAt(1, 'out', ['  [2] admins']);
        $this->Task->expectAt(2, 'out', ['    [3] Gandalf']);
        $this->Task->expectAt(3, 'out', ['    [4] Elrond']);
        $this->Task->view();
    }

    /**
     * test the method that splits model.foreign key. and that it returns an array.
     */
    public function testParsingModelAndForeignKey()
    {
        $result = $this->Task->parseIdentifier('Model.foreignKey');
        $expected = ['model' => 'Model', 'foreign_key' => 'foreignKey'];

        $result = $this->Task->parseIdentifier('mySuperUser');
        $this->assertEqual($result, 'mySuperUser');

        $result = $this->Task->parseIdentifier('111234');
        $this->assertEqual($result, '111234');
    }

    /**
     * test creating aro/aco nodes.
     */
    public function testCreate()
    {
        $this->Task->args = ['aro', 'root', 'User.1'];
        $this->Task->expectAt(0, 'out', [new PatternExpectation('/created/'), '*']);
        $this->Task->create();

        $Aro = &ClassRegistry::init('Aro');
        $Aro->cacheQueries = false;
        $result = $Aro->read();
        $this->assertEqual($result['Aro']['model'], 'User');
        $this->assertEqual($result['Aro']['foreign_key'], 1);
        $this->assertEqual($result['Aro']['parent_id'], null);
        $id = $result['Aro']['id'];

        $this->Task->args = ['aro', 'User.1', 'User.3'];
        $this->Task->expectAt(1, 'out', [new PatternExpectation('/created/'), '*']);
        $this->Task->create();

        $Aro = &ClassRegistry::init('Aro');
        $result = $Aro->read();
        $this->assertEqual($result['Aro']['model'], 'User');
        $this->assertEqual($result['Aro']['foreign_key'], 3);
        $this->assertEqual($result['Aro']['parent_id'], $id);

        $this->Task->args = ['aro', 'root', 'somealias'];
        $this->Task->expectAt(2, 'out', [new PatternExpectation('/created/'), '*']);
        $this->Task->create();

        $Aro = &ClassRegistry::init('Aro');
        $result = $Aro->read();
        $this->assertEqual($result['Aro']['alias'], 'somealias');
        $this->assertEqual($result['Aro']['model'], null);
        $this->assertEqual($result['Aro']['foreign_key'], null);
        $this->assertEqual($result['Aro']['parent_id'], null);
    }

    /**
     * test the delete method with different node types.
     */
    public function testDelete()
    {
        $this->Task->args = ['aro', 'AuthUser.1'];
        $this->Task->expectAt(0, 'out', [new NoPatternExpectation('/not/'), true]);
        $this->Task->delete();

        $Aro = &ClassRegistry::init('Aro');
        $result = $Aro->read(null, 3);
        $this->assertFalse($result);
    }

    /**
     * test setParent method.
     */
    public function testSetParent()
    {
        $this->Task->args = ['aro', 'AuthUser.2', 'root'];
        $this->Task->setParent();

        $Aro = &ClassRegistry::init('Aro');
        $result = $Aro->read(null, 4);
        $this->assertEqual($result['Aro']['parent_id'], null);
    }

    /**
     * test grant.
     */
    public function testGrant()
    {
        $this->Task->args = ['AuthUser.2', 'ROOT/Controller1', 'create'];
        $this->Task->expectAt(0, 'out', [new PatternExpectation('/Permission granted/'), true]);
        $this->Task->grant();

        $node = $this->Task->Acl->Aro->read(null, 4);
        $this->assertFalse(empty($node['Aco'][0]));
        $this->assertEqual($node['Aco'][0]['Permission']['_create'], 1);
    }

    /**
     * test deny.
     */
    public function testDeny()
    {
        $this->Task->args = ['AuthUser.2', 'ROOT/Controller1', 'create'];
        $this->Task->expectAt(0, 'out', [new PatternExpectation('/Permission denied/'), true]);
        $this->Task->deny();

        $node = $this->Task->Acl->Aro->read(null, 4);
        $this->assertFalse(empty($node['Aco'][0]));
        $this->assertEqual($node['Aco'][0]['Permission']['_create'], -1);
    }

    /**
     * test checking allowed and denied perms.
     */
    public function testCheck()
    {
        $this->Task->args = ['AuthUser.2', 'ROOT/Controller1', '*'];
        $this->Task->expectAt(0, 'out', [new PatternExpectation('/not allowed/'), true]);
        $this->Task->check();

        $this->Task->args = ['AuthUser.2', 'ROOT/Controller1', 'create'];
        $this->Task->expectAt(1, 'out', [new PatternExpectation('/Permission granted/'), true]);
        $this->Task->grant();

        $this->Task->args = ['AuthUser.2', 'ROOT/Controller1', 'create'];
        $this->Task->expectAt(2, 'out', [new PatternExpectation('/is allowed/'), true]);
        $this->Task->check();

        $this->Task->args = ['AuthUser.2', 'ROOT/Controller1', '*'];
        $this->Task->expectAt(3, 'out', [new PatternExpectation('/not allowed/'), true]);
        $this->Task->check();
    }

    /**
     * test inherit and that it 0's the permission fields.
     */
    public function testInherit()
    {
        $this->Task->args = ['AuthUser.2', 'ROOT/Controller1', 'create'];
        $this->Task->expectAt(0, 'out', [new PatternExpectation('/Permission granted/'), true]);
        $this->Task->grant();

        $this->Task->args = ['AuthUser.2', 'ROOT/Controller1', 'all'];
        $this->Task->expectAt(1, 'out', [new PatternExpectation('/permission inherited/i'), true]);
        $this->Task->inherit();

        $node = $this->Task->Acl->Aro->read(null, 4);
        $this->assertFalse(empty($node['Aco'][0]));
        $this->assertEqual($node['Aco'][0]['Permission']['_create'], 0);
    }

    /**
     * test getting the path for an aro/aco.
     */
    public function testGetPath()
    {
        $this->Task->args = ['aro', 'AuthUser.2'];
        $this->Task->expectAt(1, 'out', ['[1] ROOT']);
        $this->Task->expectAt(2, 'out', ['  [2] admins']);
        $this->Task->expectAt(3, 'out', ['    [4] Elrond']);
        $this->Task->getPath();
    }

    /**
     * test that initdb makes the correct call.
     */
    public function testInitDb()
    {
        $this->Task->Dispatch->expectOnce('dispatch');
        $this->Task->initdb();

        $this->assertEqual($this->Task->Dispatch->args, ['schema', 'create', 'DbAcl']);
    }
}
