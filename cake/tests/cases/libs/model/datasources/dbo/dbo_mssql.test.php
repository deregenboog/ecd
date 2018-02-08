<?php
/**
 * DboMssqlTest file.
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
if (!defined('CAKEPHP_UNIT_TEST_EXECUTION')) {
    define('CAKEPHP_UNIT_TEST_EXECUTION', 1);
}
require_once LIBS.'model'.DS.'model.php';
require_once LIBS.'model'.DS.'datasources'.DS.'datasource.php';
require_once LIBS.'model'.DS.'datasources'.DS.'dbo_source.php';
require_once LIBS.'model'.DS.'datasources'.DS.'dbo'.DS.'dbo_mssql.php';

/**
 * DboMssqlTestDb class.
 */
class DboMssqlTestDb extends DboMssql
{
    /**
     * simulated property.
     *
     * @var array
     */
    public $simulated = [];

    /**
     * simalate property.
     *
     * @var array
     */
    public $simulate = true;
    /**
     * fetchAllResultsStack.
     *
     * @var array
     */
    public $fetchAllResultsStack = [];

    /**
     * execute method.
     *
     * @param mixed $sql
     */
    public function _execute($sql)
    {
        if ($this->simulate) {
            $this->simulated[] = $sql;

            return null;
        } else {
            return parent::_execute($sql);
        }
    }

    /**
     * fetchAll method.
     *
     * @param mixed $sql
     */
    public function _matchRecords(&$model, $conditions = null)
    {
        return $this->conditions(['id' => [1, 2]]);
    }

    /**
     * fetchAll method.
     *
     * @param mixed $sql
     */
    public function fetchAll($sql, $cache = true, $modelName = null)
    {
        $result = parent::fetchAll($sql, $cache, $modelName);
        if (!empty($this->fetchAllResultsStack)) {
            return array_pop($this->fetchAllResultsStack);
        }

        return $result;
    }

    /**
     * getLastQuery method.
     */
    public function getLastQuery()
    {
        return $this->simulated[count($this->simulated) - 1];
    }

    /**
     * getPrimaryKey method.
     *
     * @param mixed $model
     */
    public function getPrimaryKey($model)
    {
        return parent::_getPrimaryKey($model);
    }

    /**
     * clearFieldMappings method.
     */
    public function clearFieldMappings()
    {
        $this->__fieldMappings = [];
    }
}

/**
 * MssqlTestModel class.
 */
class MssqlTestModel extends Model
{
    /**
     * name property.
     *
     * @var string 'MssqlTestModel'
     */
    public $name = 'MssqlTestModel';

    /**
     * useTable property.
     *
     * @var bool false
     */
    public $useTable = false;

    /**
     * _schema property.
     *
     * @var array
     */
    public $_schema = [
        'id' => ['type' => 'integer', 'null' => '', 'default' => '', 'length' => '8', 'key' => 'primary'],
        'client_id' => ['type' => 'integer', 'null' => '', 'default' => '0', 'length' => '11'],
        'name' => ['type' => 'string', 'null' => '', 'default' => '', 'length' => '255'],
        'login' => ['type' => 'string', 'null' => '', 'default' => '', 'length' => '255'],
        'passwd' => ['type' => 'string', 'null' => '1', 'default' => '', 'length' => '255'],
        'addr_1' => ['type' => 'string', 'null' => '1', 'default' => '', 'length' => '255'],
        'addr_2' => ['type' => 'string', 'null' => '1', 'default' => '', 'length' => '25'],
        'zip_code' => ['type' => 'string', 'null' => '1', 'default' => '', 'length' => '155'],
        'city' => ['type' => 'string', 'null' => '1', 'default' => '', 'length' => '155'],
        'country' => ['type' => 'string', 'null' => '1', 'default' => '', 'length' => '155'],
        'phone' => ['type' => 'string', 'null' => '1', 'default' => '', 'length' => '155'],
        'fax' => ['type' => 'string', 'null' => '1', 'default' => '', 'length' => '155'],
        'url' => ['type' => 'string', 'null' => '1', 'default' => '', 'length' => '255'],
        'email' => ['type' => 'string', 'null' => '1', 'default' => '', 'length' => '155'],
        'comments' => ['type' => 'text', 'null' => '1', 'default' => '', 'length' => ''],
        'last_login' => ['type' => 'datetime', 'null' => '1', 'default' => '', 'length' => ''],
        'created' => ['type' => 'date', 'null' => '1', 'default' => '', 'length' => ''],
        'updated' => ['type' => 'datetime', 'null' => '1', 'default' => '', 'length' => null],
    ];

    /**
     * belongsTo property.
     *
     * @var array
     */
    public $belongsTo = [
        'MssqlClientTestModel' => [
            'foreignKey' => 'client_id',
        ],
    ];

    /**
     * find method.
     *
     * @param mixed $conditions
     * @param mixed $fields
     * @param mixed $order
     * @param mixed $recursive
     */
    public function find($conditions = null, $fields = null, $order = null, $recursive = null)
    {
        return $conditions;
    }

    /**
     * findAll method.
     *
     * @param mixed $conditions
     * @param mixed $fields
     * @param mixed $order
     * @param mixed $recursive
     */
    public function findAll($conditions = null, $fields = null, $order = null, $recursive = null)
    {
        return $conditions;
    }

    /**
     * setSchema method.
     *
     * @param array $schema
     */
    public function setSchema($schema)
    {
        $this->_schema = $schema;
    }
}

/**
 * MssqlClientTestModel class.
 */
class MssqlClientTestModel extends Model
{
    /**
     * name property.
     *
     * @var string 'MssqlAssociatedTestModel'
     */
    public $name = 'MssqlClientTestModel';
    /**
     * useTable property.
     *
     * @var bool false
     */
    public $useTable = false;
    /**
     * _schema property.
     *
     * @var array
     */
    public $_schema = [
        'id' => ['type' => 'integer', 'null' => '', 'default' => '', 'length' => '8', 'key' => 'primary'],
        'name' => ['type' => 'string', 'null' => '', 'default' => '', 'length' => '255'],
        'email' => ['type' => 'string', 'null' => '1', 'default' => '', 'length' => '155'],
        'created' => ['type' => 'datetime', 'null' => '1', 'default' => '', 'length' => ''],
        'updated' => ['type' => 'datetime', 'null' => '1', 'default' => '', 'length' => null],
    ];
}
/**
 * DboMssqlTest class.
 */
class DboMssqlTest extends CakeTestCase
{
    /**
     * The Dbo instance to be tested.
     *
     * @var DboSource
     */
    public $db = null;

    /**
     * autoFixtures property.
     *
     * @var bool false
     */
    public $autoFixtures = false;
    /**
     * fixtures property.
     *
     * @var array
     */
    public $fixtures = ['core.category'];

    /**
     * Skip if cannot connect to mssql.
     */
    public function skip()
    {
        $this->_initDb();
        $this->skipUnless('mssql' == $this->db->config['driver'], '%s SQL Server connection not available');
    }

    /**
     * Make sure all fixtures tables are being created.
     */
    public function start()
    {
        $this->db->simulate = false;
        parent::start();
        $this->db->simulate = true;
    }

    /**
     * Make sure all fixtures tables are being dropped.
     */
    public function end()
    {
        $this->db->simulate = false;
        parent::end();
        $this->db->simulate = true;
    }

    /**
     * Sets up a Dbo class instance for testing.
     */
    public function setUp()
    {
        $db = ConnectionManager::getDataSource('test_suite');
        $this->db = new DboMssqlTestDb($db->config);
        $this->model = new MssqlTestModel();
    }

    /**
     * tearDown method.
     */
    public function tearDown()
    {
        unset($this->model);
    }

    /**
     * testQuoting method.
     */
    public function testQuoting()
    {
        $expected = '1.2';
        $result = $this->db->value(1.2, 'float');
        $this->assertIdentical($expected, $result);

        $expected = "'1,2'";
        $result = $this->db->value('1,2', 'float');
        $this->assertIdentical($expected, $result);

        $expected = 'NULL';
        $result = $this->db->value('', 'integer');
        $this->assertIdentical($expected, $result);

        $expected = 'NULL';
        $result = $this->db->value('', 'float');
        $this->assertIdentical($expected, $result);

        $expected = 'NULL';
        $result = $this->db->value('', 'binary');
        $this->assertIdentical($expected, $result);
    }

    /**
     * testFields method.
     */
    public function testFields()
    {
        $fields = [
            '[MssqlTestModel].[id] AS [MssqlTestModel__0]',
            '[MssqlTestModel].[client_id] AS [MssqlTestModel__1]',
            '[MssqlTestModel].[name] AS [MssqlTestModel__2]',
            '[MssqlTestModel].[login] AS [MssqlTestModel__3]',
            '[MssqlTestModel].[passwd] AS [MssqlTestModel__4]',
            '[MssqlTestModel].[addr_1] AS [MssqlTestModel__5]',
            '[MssqlTestModel].[addr_2] AS [MssqlTestModel__6]',
            '[MssqlTestModel].[zip_code] AS [MssqlTestModel__7]',
            '[MssqlTestModel].[city] AS [MssqlTestModel__8]',
            '[MssqlTestModel].[country] AS [MssqlTestModel__9]',
            '[MssqlTestModel].[phone] AS [MssqlTestModel__10]',
            '[MssqlTestModel].[fax] AS [MssqlTestModel__11]',
            '[MssqlTestModel].[url] AS [MssqlTestModel__12]',
            '[MssqlTestModel].[email] AS [MssqlTestModel__13]',
            '[MssqlTestModel].[comments] AS [MssqlTestModel__14]',
            'CONVERT(VARCHAR(20), [MssqlTestModel].[last_login], 20) AS [MssqlTestModel__15]',
            '[MssqlTestModel].[created] AS [MssqlTestModel__16]',
            'CONVERT(VARCHAR(20), [MssqlTestModel].[updated], 20) AS [MssqlTestModel__17]',
        ];

        $result = $this->db->fields($this->model);
        $expected = $fields;
        $this->assertEqual($result, $expected);

        $this->db->clearFieldMappings();
        $result = $this->db->fields($this->model, null, 'MssqlTestModel.*');
        $expected = $fields;
        $this->assertEqual($result, $expected);

        $this->db->clearFieldMappings();
        $result = $this->db->fields($this->model, null, ['*', 'AnotherModel.id', 'AnotherModel.name']);
        $expected = array_merge($fields, [
            '[AnotherModel].[id] AS [AnotherModel__18]',
            '[AnotherModel].[name] AS [AnotherModel__19]', ]);
        $this->assertEqual($result, $expected);

        $this->db->clearFieldMappings();
        $result = $this->db->fields($this->model, null, ['*', 'MssqlClientTestModel.*']);
        $expected = array_merge($fields, [
            '[MssqlClientTestModel].[id] AS [MssqlClientTestModel__18]',
            '[MssqlClientTestModel].[name] AS [MssqlClientTestModel__19]',
            '[MssqlClientTestModel].[email] AS [MssqlClientTestModel__20]',
            'CONVERT(VARCHAR(20), [MssqlClientTestModel].[created], 20) AS [MssqlClientTestModel__21]',
            'CONVERT(VARCHAR(20), [MssqlClientTestModel].[updated], 20) AS [MssqlClientTestModel__22]', ]);
        $this->assertEqual($result, $expected);
    }

    /**
     * testDistinctFields method.
     */
    public function testDistinctFields()
    {
        $result = $this->db->fields($this->model, null, ['DISTINCT Car.country_code']);
        $expected = ['DISTINCT [Car].[country_code] AS [Car__0]'];
        $this->assertEqual($result, $expected);

        $result = $this->db->fields($this->model, null, 'DISTINCT Car.country_code');
        $expected = ['DISTINCT [Car].[country_code] AS [Car__1]'];
        $this->assertEqual($result, $expected);
    }

    /**
     * testDistinctWithLimit method.
     */
    public function testDistinctWithLimit()
    {
        $this->db->read($this->model, [
            'fields' => ['DISTINCT MssqlTestModel.city', 'MssqlTestModel.country'],
            'limit' => 5,
        ]);
        $result = $this->db->getLastQuery();
        $this->assertPattern('/^SELECT DISTINCT TOP 5/', $result);
    }

    /**
     * testDescribe method.
     */
    public function testDescribe()
    {
        $MssqlTableDescription = [
            0 => [
                0 => [
                    'Default' => '((0))',
                    'Field' => 'count',
                    'Key' => 0,
                    'Length' => '4',
                    'Null' => 'NO',
                    'Type' => 'integer',
                ],
            ],
        ];
        $this->db->fetchAllResultsStack = [$MssqlTableDescription];
        $dummyModel = $this->model;
        $result = $this->db->describe($dummyModel);
        $expected = [
            'count' => [
                'type' => 'integer',
                'null' => false,
                'default' => '0',
                'length' => 4,
            ],
        ];
        $this->assertEqual($result, $expected);
    }

    /**
     * testBuildColumn.
     *
     * @return unknown_type
     */
    public function testBuildColumn()
    {
        $column = ['name' => 'id', 'type' => 'integer', 'null' => '', 'default' => '', 'length' => '8', 'key' => 'primary'];
        $result = $this->db->buildColumn($column);
        $expected = '[id] int IDENTITY (1, 1) NOT NULL';
        $this->assertEqual($result, $expected);

        $column = ['name' => 'client_id', 'type' => 'integer', 'null' => '', 'default' => '0', 'length' => '11'];
        $result = $this->db->buildColumn($column);
        $expected = '[client_id] int DEFAULT 0 NOT NULL';
        $this->assertEqual($result, $expected);

        $column = ['name' => 'client_id', 'type' => 'integer', 'null' => true];
        $result = $this->db->buildColumn($column);
        $expected = '[client_id] int NULL';
        $this->assertEqual($result, $expected);

        // 'name' => 'type' format for columns
        $column = ['type' => 'integer', 'name' => 'client_id'];
        $result = $this->db->buildColumn($column);
        $expected = '[client_id] int NULL';
        $this->assertEqual($result, $expected);

        $column = ['type' => 'string', 'name' => 'name'];
        $result = $this->db->buildColumn($column);
        $expected = '[name] varchar(255) NULL';
        $this->assertEqual($result, $expected);

        $column = ['name' => 'name', 'type' => 'string', 'null' => '', 'default' => '', 'length' => '255'];
        $result = $this->db->buildColumn($column);
        $expected = '[name] varchar(255) DEFAULT \'\' NOT NULL';
        $this->assertEqual($result, $expected);

        $column = ['name' => 'name', 'type' => 'string', 'null' => false, 'length' => '255'];
        $result = $this->db->buildColumn($column);
        $expected = '[name] varchar(255) NOT NULL';
        $this->assertEqual($result, $expected);

        $column = ['name' => 'name', 'type' => 'string', 'null' => false, 'default' => null, 'length' => '255'];
        $result = $this->db->buildColumn($column);
        $expected = '[name] varchar(255) NOT NULL';
        $this->assertEqual($result, $expected);

        $column = ['name' => 'name', 'type' => 'string', 'null' => true, 'default' => null, 'length' => '255'];
        $result = $this->db->buildColumn($column);
        $expected = '[name] varchar(255) NULL';
        $this->assertEqual($result, $expected);

        $column = ['name' => 'name', 'type' => 'string', 'null' => true, 'default' => '', 'length' => '255'];
        $result = $this->db->buildColumn($column);
        $expected = '[name] varchar(255) DEFAULT \'\'';
        $this->assertEqual($result, $expected);
    }

    /**
     * testBuildIndex method.
     */
    public function testBuildIndex()
    {
        $indexes = [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'client_id' => ['column' => 'client_id', 'unique' => 1],
        ];
        $result = $this->db->buildIndex($indexes, 'items');
        $expected = [
            'PRIMARY KEY ([id])',
            'ALTER TABLE items ADD CONSTRAINT client_id UNIQUE([client_id]);',
        ];
        $this->assertEqual($result, $expected);

        $indexes = ['client_id' => ['column' => 'client_id']];
        $result = $this->db->buildIndex($indexes, 'items');
        $this->assertEqual($result, []);

        $indexes = ['client_id' => ['column' => ['client_id', 'period_id'], 'unique' => 1]];
        $result = $this->db->buildIndex($indexes, 'items');
        $expected = ['ALTER TABLE items ADD CONSTRAINT client_id UNIQUE([client_id], [period_id]);'];
        $this->assertEqual($result, $expected);
    }

    /**
     * testUpdateAllSyntax method.
     */
    public function testUpdateAllSyntax()
    {
        $fields = ['MssqlTestModel.client_id' => '[MssqlTestModel].[client_id] + 1'];
        $conditions = ['MssqlTestModel.updated <' => date('2009-01-01 00:00:00')];
        $this->db->update($this->model, $fields, null, $conditions);

        $result = $this->db->getLastQuery();
        $this->assertNoPattern('/MssqlTestModel/', $result);
        $this->assertPattern('/^UPDATE \[mssql_test_models\]/', $result);
        $this->assertPattern('/SET \[client_id\] = \[client_id\] \+ 1/', $result);
    }

    /**
     * testGetPrimaryKey method.
     */
    public function testGetPrimaryKey()
    {
        // When param is a model
        $result = $this->db->getPrimaryKey($this->model);
        $this->assertEqual($result, 'id');

        $schema = $this->model->schema();
        unset($schema['id']['key']);
        $this->model->setSchema($schema);
        $result = $this->db->getPrimaryKey($this->model);
        $this->assertNull($result);

        // When param is a table name
        $this->db->simulate = false;
        $this->loadFixtures('Category');
        $result = $this->db->getPrimaryKey('categories');
        $this->assertEqual($result, 'id');
    }

    /**
     * testInsertMulti.
     */
    public function testInsertMulti()
    {
        $fields = ['id', 'name', 'login'];
        $values = ['(1, \'Larry\', \'PhpNut\')', '(2, \'Renan\', \'renan.saddam\')'];
        $this->db->simulated = [];
        $this->db->insertMulti($this->model, $fields, $values);
        $result = $this->db->simulated;
        $expected = [
            'SET IDENTITY_INSERT [mssql_test_models] ON',
            'INSERT INTO [mssql_test_models] ([id], [name], [login]) VALUES (1, \'Larry\', \'PhpNut\')',
            'INSERT INTO [mssql_test_models] ([id], [name], [login]) VALUES (2, \'Renan\', \'renan.saddam\')',
            'SET IDENTITY_INSERT [mssql_test_models] OFF',
        ];
        $this->assertEqual($result, $expected);

        $fields = ['name', 'login'];
        $values = ['(\'Larry\', \'PhpNut\')', '(\'Renan\', \'renan.saddam\')'];
        $this->db->simulated = [];
        $this->db->insertMulti($this->model, $fields, $values);
        $result = $this->db->simulated;
        $expected = [
            'INSERT INTO [mssql_test_models] ([name], [login]) VALUES (\'Larry\', \'PhpNut\')',
            'INSERT INTO [mssql_test_models] ([name], [login]) VALUES (\'Renan\', \'renan.saddam\')',
        ];
        $this->assertEqual($result, $expected);
    }

    /**
     * testLastError.
     */
    public function testLastError()
    {
        $debug = Configure::read('debug');
        Configure::write('debug', 0);

        $this->db->simulate = false;
        $query = 'SELECT [name] FROM [categories]';
        $this->assertTrue(false !== $this->db->execute($query));
        $this->assertNull($this->db->lastError());

        $query = 'SELECT [inexistent_field] FROM [categories]';
        $this->assertFalse($this->db->execute($query));
        $this->assertNotNull($this->db->lastError());

        $query = 'SELECT [name] FROM [categories]';
        $this->assertTrue(false !== $this->db->execute($query));
        $this->assertNull($this->db->lastError());

        Configure::write('debug', $debug);
    }
}
