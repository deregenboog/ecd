<?php
/**
 * DboMysqlTest file.
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
App::import('Core', ['Model', 'DataSource', 'DboSource', 'DboMysql']);
App::import('Core', ['AppModel', 'Model']);
require_once dirname(dirname(dirname(__FILE__))).DS.'models.php';

Mock::generatePartial('DboMysql', 'QueryMockDboMysql', ['query', 'execute']);

/**
 * MysqlTestModel class.
 */
class MysqlTestModel extends Model
{
    /**
     * name property.
     *
     * @var string 'MysqlTestModel'
     */
    public $name = 'MysqlTestModel';

    /**
     * useTable property.
     *
     * @var bool false
     */
    public $useTable = false;

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
     * schema method.
     */
    public function schema()
    {
        return [
            'id' => ['type' => 'integer', 'null' => '', 'default' => '', 'length' => '8'],
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
    }
}

/**
 * DboMysqlTest class.
 */
class DboMysqlTest extends CakeTestCase
{
    public $fixtures = ['core.binary_test', 'core.post', 'core.author'];
    /**
     * The Dbo instance to be tested.
     *
     * @var DboSource
     */
    public $Db = null;

    /**
     * Skip if cannot connect to mysql.
     */
    public function skip()
    {
        $this->_initDb();
        $this->skipUnless('mysql' == $this->db->config['driver'], '%s MySQL connection not available');
    }

    /**
     * Sets up a Dbo class instance for testing.
     */
    public function startTest()
    {
        $db = ConnectionManager::getDataSource('test_suite');
        $this->model = new MysqlTestModel();
    }

    /**
     * Sets up a Dbo class instance for testing.
     */
    public function tearDown()
    {
        unset($this->model);
        ClassRegistry::flush();
    }

    /**
     * startCase.
     */
    public function startCase()
    {
        $this->_debug = Configure::read('debug');
        Configure::write('debug', 1);
    }

    /**
     * endCase.
     */
    public function endCase()
    {
        Configure::write('debug', $this->_debug);
    }

    /**
     * Test Dbo value method.
     */
    public function testQuoting()
    {
        $result = $this->db->fields($this->model);
        $expected = [
            '`MysqlTestModel`.`id`',
            '`MysqlTestModel`.`client_id`',
            '`MysqlTestModel`.`name`',
            '`MysqlTestModel`.`login`',
            '`MysqlTestModel`.`passwd`',
            '`MysqlTestModel`.`addr_1`',
            '`MysqlTestModel`.`addr_2`',
            '`MysqlTestModel`.`zip_code`',
            '`MysqlTestModel`.`city`',
            '`MysqlTestModel`.`country`',
            '`MysqlTestModel`.`phone`',
            '`MysqlTestModel`.`fax`',
            '`MysqlTestModel`.`url`',
            '`MysqlTestModel`.`email`',
            '`MysqlTestModel`.`comments`',
            '`MysqlTestModel`.`last_login`',
            '`MysqlTestModel`.`created`',
            '`MysqlTestModel`.`updated`',
        ];
        $this->assertEqual($result, $expected);

        $expected = 1.2;
        $result = $this->db->value(1.2, 'float');
        $this->assertEqual($expected, $result);

        $expected = "'1,2'";
        $result = $this->db->value('1,2', 'float');
        $this->assertEqual($expected, $result);

        $expected = "'4713e29446'";
        $result = $this->db->value('4713e29446');

        $this->assertEqual($expected, $result);

        $expected = 'NULL';
        $result = $this->db->value('', 'integer');
        $this->assertEqual($expected, $result);

        $expected = 'NULL';
        $result = $this->db->value('', 'boolean');
        $this->assertEqual($expected, $result);

        $expected = 10010001;
        $result = $this->db->value(10010001);
        $this->assertEqual($expected, $result);

        $expected = "'00010010001'";
        $result = $this->db->value('00010010001');
        $this->assertEqual($expected, $result);
    }

    /**
     * test that localized floats don't cause trouble.
     */
    public function testLocalizedFloats()
    {
        $restore = setlocale(LC_ALL, null);
        setlocale(LC_ALL, 'de_DE');

        $result = $this->db->value(3.141593, 'float');
        $this->assertEqual('3.141593', $result);

        $result = $this->db->value(3.141593);
        $this->assertEqual('3.141593', $result);

        $result = $this->db->value(3.141593);
        $this->assertEqual('3.141593', $result);

        $result = $this->db->value(1234567.11, 'float');
        $this->assertEqual('1234567.11', $result);

        $result = $this->db->value(123456.45464748, 'float');
        $this->assertEqual('123456.454647', $result);

        $result = $this->db->value(0.987654321, 'float');
        $this->assertEqual('0.987654321', (string) $result);

        $result = $this->db->value(2.2E-54, 'float');
        $this->assertEqual('2.2E-54', (string) $result);

        $result = $this->db->value(2.2E-54);
        $this->assertEqual('2.2E-54', (string) $result);

        setlocale(LC_ALL, $restore);
    }

    /**
     * test that scientific notations are working correctly.
     */
    public function testScientificNotation()
    {
        $result = $this->db->value(2.2E-54, 'float');
        $this->assertEqual('2.2E-54', (string) $result);

        $result = $this->db->value(2.2E-54, 'float');
        $this->assertEqual('2.2E-54', (string) $result);

        $result = $this->db->value(2.2E-54);
        $this->assertEqual('2.2E-54', (string) $result);
    }

    /**
     * testTinyintCasting method.
     */
    public function testTinyintCasting()
    {
        $this->db->cacheSources = false;
        $this->db->query('CREATE TABLE '.$this->db->fullTableName('tinyint').' (id int(11) AUTO_INCREMENT, bool tinyint(1), small_int tinyint(2), primary key(id));');

        $this->model = new CakeTestModel([
            'name' => 'Tinyint', 'table' => 'tinyint', 'ds' => 'test_suite',
        ]);

        $result = $this->model->schema();
        $this->assertEqual($result['bool']['type'], 'boolean');
        $this->assertEqual($result['small_int']['type'], 'integer');

        $this->assertTrue($this->model->save(['bool' => 5, 'small_int' => 5]));
        $result = $this->model->find('first');
        $this->assertIdentical($result['Tinyint']['bool'], '1');
        $this->assertIdentical($result['Tinyint']['small_int'], '5');
        $this->model->deleteAll(true);

        $this->assertTrue($this->model->save(['bool' => 0, 'small_int' => 100]));
        $result = $this->model->find('first');
        $this->assertIdentical($result['Tinyint']['bool'], '0');
        $this->assertIdentical($result['Tinyint']['small_int'], '100');
        $this->model->deleteAll(true);

        $this->assertTrue($this->model->save(['bool' => true, 'small_int' => 0]));
        $result = $this->model->find('first');
        $this->assertIdentical($result['Tinyint']['bool'], '1');
        $this->assertIdentical($result['Tinyint']['small_int'], '0');
        $this->model->deleteAll(true);

        $this->db->query('DROP TABLE '.$this->db->fullTableName('tinyint'));
    }

    /**
     * testIndexDetection method.
     */
    public function testIndexDetection()
    {
        $this->db->cacheSources = false;

        $name = $this->db->fullTableName('simple');
        $this->db->query('CREATE TABLE '.$name.' (id int(11) AUTO_INCREMENT, bool tinyint(1), small_int tinyint(2), primary key(id));');
        $expected = ['PRIMARY' => ['column' => 'id', 'unique' => 1]];
        $result = $this->db->index('simple', false);
        $this->assertEqual($expected, $result);
        $this->db->query('DROP TABLE '.$name);

        $name = $this->db->fullTableName('with_a_key');
        $this->db->query('CREATE TABLE '.$name.' (id int(11) AUTO_INCREMENT, bool tinyint(1), small_int tinyint(2), primary key(id), KEY `pointless_bool` ( `bool` ));');
        $expected = [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'pointless_bool' => ['column' => 'bool', 'unique' => 0],
        ];
        $result = $this->db->index('with_a_key', false);
        $this->assertEqual($expected, $result);
        $this->db->query('DROP TABLE '.$name);

        $name = $this->db->fullTableName('with_two_keys');
        $this->db->query('CREATE TABLE '.$name.' (id int(11) AUTO_INCREMENT, bool tinyint(1), small_int tinyint(2), primary key(id), KEY `pointless_bool` ( `bool` ), KEY `pointless_small_int` ( `small_int` ));');
        $expected = [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'pointless_bool' => ['column' => 'bool', 'unique' => 0],
            'pointless_small_int' => ['column' => 'small_int', 'unique' => 0],
        ];
        $result = $this->db->index('with_two_keys', false);
        $this->assertEqual($expected, $result);
        $this->db->query('DROP TABLE '.$name);

        $name = $this->db->fullTableName('with_compound_keys');
        $this->db->query('CREATE TABLE '.$name.' (id int(11) AUTO_INCREMENT, bool tinyint(1), small_int tinyint(2), primary key(id), KEY `pointless_bool` ( `bool` ), KEY `pointless_small_int` ( `small_int` ), KEY `one_way` ( `bool`, `small_int` ));');
        $expected = [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'pointless_bool' => ['column' => 'bool', 'unique' => 0],
            'pointless_small_int' => ['column' => 'small_int', 'unique' => 0],
            'one_way' => ['column' => ['bool', 'small_int'], 'unique' => 0],
        ];
        $result = $this->db->index('with_compound_keys', false);
        $this->assertEqual($expected, $result);
        $this->db->query('DROP TABLE '.$name);

        $name = $this->db->fullTableName('with_multiple_compound_keys');
        $this->db->query('CREATE TABLE '.$name.' (id int(11) AUTO_INCREMENT, bool tinyint(1), small_int tinyint(2), primary key(id), KEY `pointless_bool` ( `bool` ), KEY `pointless_small_int` ( `small_int` ), KEY `one_way` ( `bool`, `small_int` ), KEY `other_way` ( `small_int`, `bool` ));');
        $expected = [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'pointless_bool' => ['column' => 'bool', 'unique' => 0],
            'pointless_small_int' => ['column' => 'small_int', 'unique' => 0],
            'one_way' => ['column' => ['bool', 'small_int'], 'unique' => 0],
            'other_way' => ['column' => ['small_int', 'bool'], 'unique' => 0],
        ];
        $result = $this->db->index('with_multiple_compound_keys', false);
        $this->assertEqual($expected, $result);
        $this->db->query('DROP TABLE '.$name);
    }

    /**
     * testBuildColumn method.
     */
    public function testBuildColumn()
    {
        $restore = $this->db->columns;
        $this->db->columns = ['varchar(255)' => 1];
        $data = [
            'name' => 'testName',
            'type' => 'varchar(255)',
            'default',
            'null' => true,
            'key',
            'comment' => 'test',
        ];
        $result = $this->db->buildColumn($data);
        $expected = '`testName`  DEFAULT NULL COMMENT \'test\'';
        $this->assertEqual($result, $expected);

        $data = [
            'name' => 'testName',
            'type' => 'varchar(255)',
            'default',
            'null' => true,
            'key',
            'charset' => 'utf8',
            'collate' => 'utf8_unicode_ci',
        ];
        $result = $this->db->buildColumn($data);
        $expected = '`testName`  CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL';
        $this->assertEqual($result, $expected);
        $this->db->columns = $restore;
    }

    /**
     * MySQL 4.x returns index data in a different format,
     * Using a mock ensure that MySQL 4.x output is properly parsed.
     */
    public function testIndexOnMySQL4Output()
    {
        $name = $this->db->fullTableName('simple');

        $mockDbo = new QueryMockDboMysql($this);
        $columnData = [
            ['0' => [
                'Table' => 'with_compound_keys',
                'Non_unique' => '0',
                'Key_name' => 'PRIMARY',
                'Seq_in_index' => '1',
                'Column_name' => 'id',
                'Collation' => 'A',
                'Cardinality' => '0',
                'Sub_part' => null,
                'Packed' => null,
                'Null' => '',
                'Index_type' => 'BTREE',
                'Comment' => '',
            ]],
            ['0' => [
                'Table' => 'with_compound_keys',
                'Non_unique' => '1',
                'Key_name' => 'pointless_bool',
                'Seq_in_index' => '1',
                'Column_name' => 'bool',
                'Collation' => 'A',
                'Cardinality' => null,
                'Sub_part' => null,
                'Packed' => null,
                'Null' => 'YES',
                'Index_type' => 'BTREE',
                'Comment' => '',
            ]],
            ['0' => [
                'Table' => 'with_compound_keys',
                'Non_unique' => '1',
                'Key_name' => 'pointless_small_int',
                'Seq_in_index' => '1',
                'Column_name' => 'small_int',
                'Collation' => 'A',
                'Cardinality' => null,
                'Sub_part' => null,
                'Packed' => null,
                'Null' => 'YES',
                'Index_type' => 'BTREE',
                'Comment' => '',
            ]],
            ['0' => [
                'Table' => 'with_compound_keys',
                'Non_unique' => '1',
                'Key_name' => 'one_way',
                'Seq_in_index' => '1',
                'Column_name' => 'bool',
                'Collation' => 'A',
                'Cardinality' => null,
                'Sub_part' => null,
                'Packed' => null,
                'Null' => 'YES',
                'Index_type' => 'BTREE',
                'Comment' => '',
            ]],
            ['0' => [
                'Table' => 'with_compound_keys',
                'Non_unique' => '1',
                'Key_name' => 'one_way',
                'Seq_in_index' => '2',
                'Column_name' => 'small_int',
                'Collation' => 'A',
                'Cardinality' => null,
                'Sub_part' => null,
                'Packed' => null,
                'Null' => 'YES',
                'Index_type' => 'BTREE',
                'Comment' => '',
            ]],
        ];
        $mockDbo->setReturnValue('query', $columnData, ['SHOW INDEX FROM '.$name]);

        $result = $mockDbo->index($name, false);
        $expected = [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'pointless_bool' => ['column' => 'bool', 'unique' => 0],
            'pointless_small_int' => ['column' => 'small_int', 'unique' => 0],
            'one_way' => ['column' => ['bool', 'small_int'], 'unique' => 0],
        ];
        $this->assertEqual($result, $expected);
    }

    /**
     * testColumn method.
     */
    public function testColumn()
    {
        $result = $this->db->column('varchar(50)');
        $expected = 'string';
        $this->assertEqual($result, $expected);

        $result = $this->db->column('text');
        $expected = 'text';
        $this->assertEqual($result, $expected);

        $result = $this->db->column('int(11)');
        $expected = 'integer';
        $this->assertEqual($result, $expected);

        $result = $this->db->column('int(11) unsigned');
        $expected = 'integer';
        $this->assertEqual($result, $expected);

        $result = $this->db->column('tinyint(1)');
        $expected = 'boolean';
        $this->assertEqual($result, $expected);

        $result = $this->db->column('boolean');
        $expected = 'boolean';
        $this->assertEqual($result, $expected);

        $result = $this->db->column('float');
        $expected = 'float';
        $this->assertEqual($result, $expected);

        $result = $this->db->column('float unsigned');
        $expected = 'float';
        $this->assertEqual($result, $expected);

        $result = $this->db->column('double unsigned');
        $expected = 'float';
        $this->assertEqual($result, $expected);

        $result = $this->db->column('decimal(14,7) unsigned');
        $expected = 'float';
        $this->assertEqual($result, $expected);
    }

    /**
     * testAlterSchemaIndexes method.
     */
    public function testAlterSchemaIndexes()
    {
        App::import('Model', 'CakeSchema');
        $this->db->cacheSources = false;

        $schema1 = new CakeSchema([
            'name' => 'AlterTest1',
            'connection' => 'test_suite',
            'altertest' => [
                'id' => ['type' => 'integer', 'null' => false, 'default' => 0],
                'name' => ['type' => 'string', 'null' => false, 'length' => 50],
                'group1' => ['type' => 'integer', 'null' => true],
                'group2' => ['type' => 'integer', 'null' => true],
        ], ]);
        $this->db->query($this->db->createSchema($schema1));

        $schema2 = new CakeSchema([
            'name' => 'AlterTest2',
            'connection' => 'test_suite',
            'altertest' => [
                'id' => ['type' => 'integer', 'null' => false, 'default' => 0],
                'name' => ['type' => 'string', 'null' => false, 'length' => 50],
                'group1' => ['type' => 'integer', 'null' => true],
                'group2' => ['type' => 'integer', 'null' => true],
                'indexes' => [
                    'name_idx' => ['column' => 'name', 'unique' => 0],
                    'group_idx' => ['column' => 'group1', 'unique' => 0],
                    'compound_idx' => ['column' => ['group1', 'group2'], 'unique' => 0],
                    'PRIMARY' => ['column' => 'id', 'unique' => 1], ],
        ], ]);
        $this->db->query($this->db->alterSchema($schema2->compare($schema1)));

        $indexes = $this->db->index('altertest');
        $this->assertEqual($schema2->tables['altertest']['indexes'], $indexes);

        // Change three indexes, delete one and add another one
        $schema3 = new CakeSchema([
            'name' => 'AlterTest3',
            'connection' => 'test_suite',
            'altertest' => [
                'id' => ['type' => 'integer', 'null' => false, 'default' => 0],
                'name' => ['type' => 'string', 'null' => false, 'length' => 50],
                'group1' => ['type' => 'integer', 'null' => true],
                'group2' => ['type' => 'integer', 'null' => true],
                'indexes' => [
                    'name_idx' => ['column' => 'name', 'unique' => 1],
                    'group_idx' => ['column' => 'group2', 'unique' => 0],
                    'compound_idx' => ['column' => ['group2', 'group1'], 'unique' => 0],
                    'id_name_idx' => ['column' => ['id', 'name'], 'unique' => 0], ],
        ], ]);

        $this->db->query($this->db->alterSchema($schema3->compare($schema2)));

        $indexes = $this->db->index('altertest');
        $this->assertEqual($schema3->tables['altertest']['indexes'], $indexes);

        // Compare us to ourself.
        $this->assertEqual($schema3->compare($schema3), []);

        // Drop the indexes
        $this->db->query($this->db->alterSchema($schema1->compare($schema3)));

        $indexes = $this->db->index('altertest');
        $this->assertEqual([], $indexes);

        $this->db->query($this->db->dropSchema($schema1));
    }

    /**
     * test saving and retrieval of blobs.
     */
    public function testBlobSaving()
    {
        $this->db->cacheSources = false;
        $data = "GIF87ab 
		 Ò   4A¿¿¿ˇˇˇ   ,    b 
		  ¢îè©ÀÌ#¥⁄ã≥ﬁ:¯Ü‚Héá¶jV∂ÓúÎL≥çÀóËıÎ…>ï ≈ vFE%ÒâLFI<†µw˝±≈£7˘ç^H“≤«>ÉÃ¢*∑Ç nÖA•Ù|ﬂêèj£:=ÿ6óUàµ5'∂®àA¬ñ∆ˆGE(gt’≈àÚyÁó«7	‚VìöÇ√˙Ç™
		k”:;kÀAõ{*¡€Î˚˚[  ;;";

        $model = new AppModel(['name' => 'BinaryTest', 'ds' => 'test_suite']);
        $model->save(compact('data'));

        $result = $model->find('first');
        $this->assertEqual($result['BinaryTest']['data'], $data);
    }

    /**
     * test altering the table settings with schema.
     */
    public function testAlteringTableParameters()
    {
        App::import('Model', 'CakeSchema');
        $this->db->cacheSources = false;

        $schema1 = new CakeSchema([
            'name' => 'AlterTest1',
            'connection' => 'test_suite',
            'altertest' => [
                'id' => ['type' => 'integer', 'null' => false, 'default' => 0],
                'name' => ['type' => 'string', 'null' => false, 'length' => 50],
                'tableParameters' => [
                    'charset' => 'latin1',
                    'collate' => 'latin1_general_ci',
                    'engine' => 'MyISAM',
                ],
            ],
        ]);
        $this->db->query($this->db->createSchema($schema1));
        $schema2 = new CakeSchema([
            'name' => 'AlterTest1',
            'connection' => 'test_suite',
            'altertest' => [
                'id' => ['type' => 'integer', 'null' => false, 'default' => 0],
                'name' => ['type' => 'string', 'null' => false, 'length' => 50],
                'tableParameters' => [
                    'charset' => 'utf8',
                    'collate' => 'utf8_general_ci',
                    'engine' => 'InnoDB',
                ],
            ],
        ]);
        $result = $this->db->alterSchema($schema2->compare($schema1));
        $this->assertPattern('/DEFAULT CHARSET=utf8/', $result);
        $this->assertPattern('/ENGINE=InnoDB/', $result);
        $this->assertPattern('/COLLATE=utf8_general_ci/', $result);

        $this->db->query($result);
        $result = $this->db->listDetailedSources('altertest');
        $this->assertEqual($result['Collation'], 'utf8_general_ci');
        $this->assertEqual($result['Engine'], 'InnoDB');
        $this->assertEqual($result['charset'], 'utf8');

        $this->db->query($this->db->dropSchema($schema1));
    }

    /**
     * test alterSchema on two tables.
     */
    public function testAlteringTwoTables()
    {
        $schema1 = new CakeSchema([
            'name' => 'AlterTest1',
            'connection' => 'test_suite',
            'altertest' => [
                'id' => ['type' => 'integer', 'null' => false, 'default' => 0],
                'name' => ['type' => 'string', 'null' => false, 'length' => 50],
            ],
            'other_table' => [
                'id' => ['type' => 'integer', 'null' => false, 'default' => 0],
                'name' => ['type' => 'string', 'null' => false, 'length' => 50],
            ],
        ]);
        $schema2 = new CakeSchema([
            'name' => 'AlterTest1',
            'connection' => 'test_suite',
            'altertest' => [
                'id' => ['type' => 'integer', 'null' => false, 'default' => 0],
                'field_two' => ['type' => 'string', 'null' => false, 'length' => 50],
            ],
            'other_table' => [
                'id' => ['type' => 'integer', 'null' => false, 'default' => 0],
                'field_two' => ['type' => 'string', 'null' => false, 'length' => 50],
            ],
        ]);
        $result = $this->db->alterSchema($schema2->compare($schema1));
        $this->assertEqual(2, substr_count($result, 'field_two'), 'Too many fields');
    }

    /**
     * testReadTableParameters method.
     */
    public function testReadTableParameters()
    {
        $this->db->cacheSources = false;
        $this->db->query('CREATE TABLE '.$this->db->fullTableName('tinyint').' (id int(11) AUTO_INCREMENT, bool tinyint(1), small_int tinyint(2), primary key(id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');
        $result = $this->db->readTableParameters('tinyint');
        $expected = [
            'charset' => 'utf8',
            'collate' => 'utf8_unicode_ci',
            'engine' => 'InnoDB', ];
        $this->assertEqual($result, $expected);

        $this->db->query('DROP TABLE '.$this->db->fullTableName('tinyint'));
        $this->db->query('CREATE TABLE '.$this->db->fullTableName('tinyint').' (id int(11) AUTO_INCREMENT, bool tinyint(1), small_int tinyint(2), primary key(id)) ENGINE=MyISAM DEFAULT CHARSET=cp1250 COLLATE=cp1250_general_ci;');
        $result = $this->db->readTableParameters('tinyint');
        $expected = [
            'charset' => 'cp1250',
            'collate' => 'cp1250_general_ci',
            'engine' => 'MyISAM', ];
        $this->assertEqual($result, $expected);
        $this->db->query('DROP TABLE '.$this->db->fullTableName('tinyint'));
    }

    /**
     * testBuildTableParameters method.
     */
    public function testBuildTableParameters()
    {
        $this->db->cacheSources = false;
        $data = [
            'charset' => 'utf8',
            'collate' => 'utf8_unicode_ci',
            'engine' => 'InnoDB', ];
        $result = $this->db->buildTableParameters($data);
        $expected = [
            'DEFAULT CHARSET=utf8',
            'COLLATE=utf8_unicode_ci',
            'ENGINE=InnoDB', ];
        $this->assertEqual($result, $expected);
    }

    /**
     * testBuildTableParameters method.
     */
    public function testGetCharsetName()
    {
        $this->db->cacheSources = false;
        $result = $this->db->getCharsetName('utf8_unicode_ci');
        $this->assertEqual($result, 'utf8');
        $result = $this->db->getCharsetName('cp1250_general_ci');
        $this->assertEqual($result, 'cp1250');
    }

    /**
     * test that changing the virtualFieldSeparator allows for __ fields.
     */
    public function testVirtualFieldSeparators()
    {
        $model = new CakeTestModel(['table' => 'binary_tests', 'ds' => 'test_suite', 'name' => 'BinaryTest']);
        $model->virtualFields = [
            'other__field' => 'SUM(id)',
        ];

        $this->db->virtualFieldSeparator = '_$_';
        $result = $this->db->fields($model, null, ['data', 'other__field']);
        $expected = ['`BinaryTest`.`data`', '(SUM(id)) AS  `BinaryTest_$_other__field`'];
        $this->assertEqual($result, $expected);
    }

    /**
     * test that a describe() gets additional fieldParameters.
     */
    public function testDescribeGettingFieldParameters()
    {
        $schema = new CakeSchema([
            'connection' => 'test_suite',
            'testdescribes' => [
                'id' => ['type' => 'integer', 'key' => 'primary'],
                'stringy' => [
                    'type' => 'string',
                    'null' => true,
                    'charset' => 'cp1250',
                    'collate' => 'cp1250_general_ci',
                ],
                'other_col' => [
                    'type' => 'string',
                    'null' => false,
                    'charset' => 'latin1',
                    'comment' => 'Test Comment',
                ],
            ],
        ]);
        $this->db->execute($this->db->createSchema($schema));

        $model = new CakeTestModel(['table' => 'testdescribes', 'name' => 'Testdescribes']);
        $result = $this->db->describe($model);
        $this->assertEqual($result['stringy']['collate'], 'cp1250_general_ci');
        $this->assertEqual($result['stringy']['charset'], 'cp1250');
        $this->assertEqual($result['other_col']['comment'], 'Test Comment');

        $this->db->execute($this->db->dropSchema($schema));
    }

    /**
     * test that simple delete conditions don't create joins using a mock.
     */
    public function testSimpleDeleteConditionsNoJoins()
    {
        $model = new Post();
        $mockDbo = new QueryMockDboMysql($this);
        $mockDbo->expectAt(0, 'execute', [new PatternExpectation('/AS\s+`Post`\s+WHERE\s+`Post/')]);
        $mockDbo->setReturnValue('execute', true);

        $mockDbo->delete($model, ['Post.id' => 1]);
    }

    /**
     * test deleting with joins, a MySQL specific feature.
     */
    public function testDeleteWithJoins()
    {
        $model = new Post();
        $mockDbo = new QueryMockDboMysql($this);
        $mockDbo->expectAt(0, 'execute', [new PatternExpectation('/LEFT JOIN `authors`/')]);
        $mockDbo->setReturnValue('execute', true);

        $mockDbo->delete($model, ['Author.id' => 1]);
    }

    /**
     * test joins on delete with multiple conditions.
     */
    public function testDeleteWithJoinsAndMultipleConditions()
    {
        $model = new Post();
        $mockDbo = new QueryMockDboMysql($this);
        $mockDbo->expectAt(0, 'execute', [new PatternExpectation('/LEFT JOIN `authors`/')]);
        $mockDbo->expectAt(1, 'execute', [new PatternExpectation('/LEFT JOIN `authors`/')]);
        $mockDbo->setReturnValue('execute', true);

        $mockDbo->delete($model, ['Author.id' => 1, 'Post.id' => 2]);
        $mockDbo->delete($model, ['Post.id' => 2, 'Author.id' => 1]);
    }
}
