<?php
/**
 * DbAclTest file.
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
 * @since         CakePHP(tm) v 1.2.0.4206
 *
 * @license       http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */
if (!defined('CAKEPHP_UNIT_TEST_EXECUTION')) {
    define('CAKEPHP_UNIT_TEST_EXECUTION', 1);
}
App::import('Component', 'Acl');
App::import('Core', 'db_acl');

/**
 * DB ACL wrapper test class.
 */
class DbAclNodeTestBase extends AclNode
{
    /**
     * useDbConfig property.
     *
     * @var string 'test_suite'
     */
    public $useDbConfig = 'test_suite';

    /**
     * cacheSources property.
     *
     * @var bool false
     */
    public $cacheSources = false;
}

/**
 * Aro Test Wrapper.
 */
class DbAroTest extends DbAclNodeTestBase
{
    /**
     * name property.
     *
     * @var string 'DbAroTest'
     */
    public $name = 'DbAroTest';

    /**
     * useTable property.
     *
     * @var string 'aros'
     */
    public $useTable = 'aros';

    /**
     * hasAndBelongsToMany property.
     *
     * @var array
     */
    public $hasAndBelongsToMany = ['DbAcoTest' => ['with' => 'DbPermissionTest']];
}

/**
 * Aco Test Wrapper.
 */
class DbAcoTest extends DbAclNodeTestBase
{
    /**
     * name property.
     *
     * @var string 'DbAcoTest'
     */
    public $name = 'DbAcoTest';

    /**
     * useTable property.
     *
     * @var string 'acos'
     */
    public $useTable = 'acos';

    /**
     * hasAndBelongsToMany property.
     *
     * @var array
     */
    public $hasAndBelongsToMany = ['DbAroTest' => ['with' => 'DbPermissionTest']];
}

/**
 * Permission Test Wrapper.
 */
class DbPermissionTest extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'DbPermissionTest'
     */
    public $name = 'DbPermissionTest';

    /**
     * useTable property.
     *
     * @var string 'aros_acos'
     */
    public $useTable = 'aros_acos';

    /**
     * cacheQueries property.
     *
     * @var bool false
     */
    public $cacheQueries = false;

    /**
     * belongsTo property.
     *
     * @var array
     */
    public $belongsTo = ['DbAroTest' => ['foreignKey' => 'aro_id'], 'DbAcoTest' => ['foreignKey' => 'aco_id']];
}

/**
 * DboActionTest class.
 */
class DbAcoActionTest extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'DbAcoActionTest'
     */
    public $name = 'DbAcoActionTest';

    /**
     * useTable property.
     *
     * @var string 'aco_actions'
     */
    public $useTable = 'aco_actions';

    /**
     * belongsTo property.
     *
     * @var array
     */
    public $belongsTo = ['DbAcoTest' => ['foreignKey' => 'aco_id']];
}

/**
 * DbAroUserTest class.
 */
class DbAroUserTest extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'AuthUser'
     */
    public $name = 'AuthUser';

    /**
     * useTable property.
     *
     * @var string 'auth_users'
     */
    public $useTable = 'auth_users';

    /**
     * bindNode method.
     *
     * @param mixed $ref
     */
    public function bindNode($ref = null)
    {
        if ('string' == Configure::read('DbAclbindMode')) {
            return 'ROOT/admins/Gandalf';
        } elseif ('array' == Configure::read('DbAclbindMode')) {
            return ['DbAroTest' => ['DbAroTest.model' => 'AuthUser', 'DbAroTest.foreign_key' => 2]];
        }
    }
}

/**
 * DbAclTest class.
 */
class DbAclTest extends DbAcl
{
    /**
     * construct method.
     */
    public function __construct()
    {
        $this->Aro = new DbAroTest();
        $this->Aro->Permission = new DbPermissionTest();
        $this->Aco = new DbAcoTest();
        $this->Aro->Permission = new DbPermissionTest();
    }
}

/**
 * AclNodeTest class.
 */
class AclNodeTest extends CakeTestCase
{
    /**
     * fixtures property.
     *
     * @var array
     */
    public $fixtures = ['core.aro', 'core.aco', 'core.aros_aco', 'core.aco_action', 'core.auth_user'];

    /**
     * setUp method.
     */
    public function setUp()
    {
        Configure::write('Acl.classname', 'DbAclTest');
        Configure::write('Acl.database', 'test_suite');
    }

    /**
     * testNode method.
     */
    public function testNode()
    {
        $Aco = new DbAcoTest();
        $result = Set::extract($Aco->node('Controller1'), '{n}.DbAcoTest.id');
        $expected = [2, 1];
        $this->assertEqual($result, $expected);

        $result = Set::extract($Aco->node('Controller1/action1'), '{n}.DbAcoTest.id');
        $expected = [3, 2, 1];
        $this->assertEqual($result, $expected);

        $result = Set::extract($Aco->node('Controller2/action1'), '{n}.DbAcoTest.id');
        $expected = [7, 6, 1];
        $this->assertEqual($result, $expected);

        $result = Set::extract($Aco->node('Controller1/action2'), '{n}.DbAcoTest.id');
        $expected = [5, 2, 1];
        $this->assertEqual($result, $expected);

        $result = Set::extract($Aco->node('Controller1/action1/record1'), '{n}.DbAcoTest.id');
        $expected = [4, 3, 2, 1];
        $this->assertEqual($result, $expected);

        $result = Set::extract($Aco->node('Controller2/action1/record1'), '{n}.DbAcoTest.id');
        $expected = [8, 7, 6, 1];
        $this->assertEqual($result, $expected);

        $result = Set::extract($Aco->node('Controller2/action3'), '{n}.DbAcoTest.id');
        $this->assertFalse($result);

        $result = Set::extract($Aco->node('Controller2/action3/record5'), '{n}.DbAcoTest.id');
        $this->assertFalse($result);

        $result = $Aco->node('');
        $this->assertEqual($result, null);
    }

    /**
     * test that node() doesn't dig deeper than it should.
     */
    public function testNodeWithDuplicatePathSegments()
    {
        $Aco = new DbAcoTest();
        $nodes = $Aco->node('ROOT/Users');
        $this->assertEqual($nodes[0]['DbAcoTest']['parent_id'], 1, 'Parent id does not point at ROOT. %s');
    }

    /**
     * testNodeArrayFind method.
     */
    public function testNodeArrayFind()
    {
        $Aro = new DbAroTest();
        Configure::write('DbAclbindMode', 'string');
        $result = Set::extract($Aro->node(['DbAroUserTest' => ['id' => '1', 'foreign_key' => '1']]), '{n}.DbAroTest.id');
        $expected = [3, 2, 1];
        $this->assertEqual($result, $expected);

        Configure::write('DbAclbindMode', 'array');
        $result = Set::extract($Aro->node(['DbAroUserTest' => ['id' => 4, 'foreign_key' => 2]]), '{n}.DbAroTest.id');
        $expected = [4];
        $this->assertEqual($result, $expected);
    }

    /**
     * testNodeObjectFind method.
     */
    public function testNodeObjectFind()
    {
        $Aro = new DbAroTest();
        $Model = new DbAroUserTest();
        $Model->id = 1;
        $result = Set::extract($Aro->node($Model), '{n}.DbAroTest.id');
        $expected = [3, 2, 1];
        $this->assertEqual($result, $expected);

        $Model->id = 2;
        $result = Set::extract($Aro->node($Model), '{n}.DbAroTest.id');
        $expected = [4, 2, 1];
        $this->assertEqual($result, $expected);
    }

    /**
     * testNodeAliasParenting method.
     */
    public function testNodeAliasParenting()
    {
        $Aco = new DbAcoTest();
        $db = &ConnectionManager::getDataSource('test_suite');
        $db->truncate($Aco);
        $db->_queriesLog = [];

        $Aco->create(['model' => null, 'foreign_key' => null, 'parent_id' => null, 'alias' => 'Application']);
        $Aco->save();

        $Aco->create(['model' => null, 'foreign_key' => null, 'parent_id' => $Aco->id, 'alias' => 'Pages']);
        $Aco->save();

        $result = $Aco->find('all');
        $expected = [
            ['DbAcoTest' => ['id' => '1', 'parent_id' => null, 'model' => null, 'foreign_key' => null, 'alias' => 'Application', 'lft' => '1', 'rght' => '4'], 'DbAroTest' => []],
            ['DbAcoTest' => ['id' => '2', 'parent_id' => '1', 'model' => null, 'foreign_key' => null, 'alias' => 'Pages', 'lft' => '2', 'rght' => '3'], 'DbAroTest' => []],
        ];
        $this->assertEqual($result, $expected);
    }
}
