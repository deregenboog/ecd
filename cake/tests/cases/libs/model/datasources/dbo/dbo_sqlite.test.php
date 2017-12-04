<?php
/**
 * DboSqliteTest file.
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
 * @since         CakePHP(tm) v 1.2.0
 *
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::import('Core', ['Model', 'DataSource', 'DboSource', 'DboSqlite']);

/**
 * DboSqliteTestDb class.
 */
class DboSqliteTestDb extends DboSqlite
{
    /**
     * simulated property.
     *
     * @var array
     */
    public $simulated = [];

    /**
     * execute method.
     *
     * @param mixed $sql
     */
    public function _execute($sql)
    {
        $this->simulated[] = $sql;

        return null;
    }

    /**
     * getLastQuery method.
     */
    public function getLastQuery()
    {
        return $this->simulated[count($this->simulated) - 1];
    }
}

/**
 * DboSqliteTest class.
 */
class DboSqliteTest extends CakeTestCase
{
    /**
     * Do not automatically load fixtures for each test, they will be loaded manually using CakeTestCase::loadFixtures.
     *
     * @var bool
     */
    public $autoFixtures = false;

    /**
     * Fixtures.
     *
     * @var object
     */
    public $fixtures = ['core.user'];

    /**
     * Actual DB connection used in testing.
     *
     * @var DboSource
     */
    public $db = null;

    /**
     * Simulated DB connection used in testing.
     *
     * @var DboSource
     */
    public $db2 = null;

    /**
     * Skip if cannot connect to SQLite.
     */
    public function skip()
    {
        $this->_initDb();
        $this->skipUnless('sqlite' == $this->db->config['driver'], '%s SQLite connection not available');
    }

    /**
     * Set up test suite database connection.
     */
    public function startTest()
    {
        $this->_initDb();
    }

    /**
     * Sets up a Dbo class instance for testing.
     */
    public function setUp()
    {
        Configure::write('Cache.disable', true);
        $this->startTest();
        $this->db = &ConnectionManager::getDataSource('test_suite');
        $this->db2 = new DboSqliteTestDb($this->db->config, false);
    }

    /**
     * Sets up a Dbo class instance for testing.
     */
    public function tearDown()
    {
        Configure::write('Cache.disable', false);
        unset($this->db2);
    }

    /**
     * Tests that SELECT queries from DboSqlite::listSources() are not cached.
     */
    public function testTableListCacheDisabling()
    {
        $this->assertFalse(in_array('foo_test', $this->db->listSources()));

        $this->db->query('CREATE TABLE foo_test (test VARCHAR(255));');
        $this->assertTrue(in_array('foo_test', $this->db->listSources()));

        $this->db->query('DROP TABLE foo_test;');
        $this->assertFalse(in_array('foo_test', $this->db->listSources()));
    }

    /**
     * test Index introspection.
     */
    public function testIndex()
    {
        $name = $this->db->fullTableName('with_a_key');
        $this->db->query('CREATE TABLE '.$name.' ("id" int(11) PRIMARY KEY, "bool" int(1), "small_char" varchar(50), "description" varchar(40) );');
        $this->db->query('CREATE INDEX pointless_bool ON '.$name.'("bool")');
        $this->db->query('CREATE UNIQUE INDEX char_index ON '.$name.'("small_char")');
        $expected = [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'pointless_bool' => ['column' => 'bool', 'unique' => 0],
            'char_index' => ['column' => 'small_char', 'unique' => 1],
        ];
        $result = $this->db->index($name);
        $this->assertEqual($expected, $result);
        $this->db->query('DROP TABLE '.$name);

        $this->db->query('CREATE TABLE '.$name.' ("id" int(11) PRIMARY KEY, "bool" int(1), "small_char" varchar(50), "description" varchar(40) );');
        $this->db->query('CREATE UNIQUE INDEX multi_col ON '.$name.'("small_char", "bool")');
        $expected = [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'multi_col' => ['column' => ['small_char', 'bool'], 'unique' => 1],
        ];
        $result = $this->db->index($name);
        $this->assertEqual($expected, $result);
        $this->db->query('DROP TABLE '.$name);
    }

    /**
     * Tests that cached table descriptions are saved under the sanitized key name.
     */
    public function testCacheKeyName()
    {
        Configure::write('Cache.disable', false);

        $dbName = 'db'.rand().'$(*%&).db';
        $this->assertFalse(file_exists(TMP.$dbName));

        $config = $this->db->config;
        $db = new DboSqlite(array_merge($this->db->config, ['database' => TMP.$dbName]));
        $this->assertTrue(file_exists(TMP.$dbName));

        $db->execute('CREATE TABLE test_list (id VARCHAR(255));');

        $db->cacheSources = true;
        $this->assertEqual($db->listSources(), ['test_list']);
        $db->cacheSources = false;

        $fileName = '_'.preg_replace('/[^A-Za-z0-9_\-+]/', '_', TMP.$dbName).'_list';

        $result = Cache::read($fileName, '_cake_model_');
        $this->assertEqual($result, ['test_list']);

        Cache::delete($fileName, '_cake_model_');
        Configure::write('Cache.disable', true);
    }

    /**
     * test building columns with SQLite.
     */
    public function testBuildColumn()
    {
        $data = [
            'name' => 'int_field',
            'type' => 'integer',
            'null' => false,
        ];
        $result = $this->db->buildColumn($data);
        $expected = '"int_field" integer(11) NOT NULL';
        $this->assertEqual($result, $expected);

        $data = [
            'name' => 'name',
            'type' => 'string',
            'length' => 20,
            'null' => false,
        ];
        $result = $this->db->buildColumn($data);
        $expected = '"name" varchar(20) NOT NULL';
        $this->assertEqual($result, $expected);

        $data = [
            'name' => 'testName',
            'type' => 'string',
            'length' => 20,
            'default' => null,
            'null' => true,
            'collate' => 'NOCASE',
        ];
        $result = $this->db->buildColumn($data);
        $expected = '"testName" varchar(20) DEFAULT NULL COLLATE NOCASE';
        $this->assertEqual($result, $expected);

        $data = [
            'name' => 'testName',
            'type' => 'string',
            'length' => 20,
            'default' => 'test-value',
            'null' => false,
        ];
        $result = $this->db->buildColumn($data);
        $expected = '"testName" varchar(20) DEFAULT \'test-value\' NOT NULL';
        $this->assertEqual($result, $expected);

        $data = [
            'name' => 'testName',
            'type' => 'integer',
            'length' => 10,
            'default' => 10,
            'null' => false,
        ];
        $result = $this->db->buildColumn($data);
        $expected = '"testName" integer(10) DEFAULT \'10\' NOT NULL';
        $this->assertEqual($result, $expected);

        $data = [
            'name' => 'testName',
            'type' => 'integer',
            'length' => 10,
            'default' => 10,
            'null' => false,
            'collate' => 'BADVALUE',
        ];
        $result = $this->db->buildColumn($data);
        $expected = '"testName" integer(10) DEFAULT \'10\' NOT NULL';
        $this->assertEqual($result, $expected);
    }

    /**
     * test describe() and normal results.
     */
    public function testDescribe()
    {
        $Model = new Model(['name' => 'User', 'ds' => 'test_suite', 'table' => 'users']);
        $result = $this->db->describe($Model);
        $expected = [
            'id' => [
                'type' => 'integer',
                'key' => 'primary',
                'null' => false,
                'default' => null,
                'length' => 11,
            ],
            'user' => [
                'type' => 'string',
                'length' => 255,
                'null' => false,
                'default' => null,
            ],
            'password' => [
                'type' => 'string',
                'length' => 255,
                'null' => false,
                'default' => null,
            ],
            'created' => [
                'type' => 'datetime',
                'null' => true,
                'default' => null,
                'length' => null,
            ],
            'updated' => [
                'type' => 'datetime',
                'null' => true,
                'default' => null,
                'length' => null,
            ],
        ];
        $this->assertEqual($result, $expected);
    }

    /**
     * test that describe does not corrupt UUID primary keys.
     */
    public function testDescribeWithUuidPrimaryKey()
    {
        $tableName = 'uuid_tests';
        $this->db->query("CREATE TABLE {$tableName} (id VARCHAR(36) PRIMARY KEY, name VARCHAR, created DATETIME, modified DATETIME)");
        $Model = new Model(['name' => 'UuidTest', 'ds' => 'test_suite', 'table' => 'uuid_tests']);
        $result = $this->db->describe($Model);
        $expected = [
            'type' => 'string',
            'length' => 36,
            'null' => false,
            'default' => null,
            'key' => 'primary',
        ];
        $this->assertEqual($result['id'], $expected);
        $this->db->query('DROP TABLE '.$tableName);
    }
}
