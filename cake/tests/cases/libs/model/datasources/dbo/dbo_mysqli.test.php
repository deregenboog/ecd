<?php
/**
 * DboMysqliTest file.
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
App::import('Core', ['Model', 'DataSource', 'DboSource', 'DboMysqli']);

/**
 * DboMysqliTestDb class.
 */
class DboMysqliTestDb extends DboMysqli
{
    /**
     * simulated property.
     *
     * @var array
     */
    public $simulated = [];

    /**
     * testing property.
     *
     * @var bool true
     */
    public $testing = true;

    /**
     * execute method.
     *
     * @param mixed $sql
     */
    public function _execute($sql)
    {
        if ($this->testing) {
            $this->simulated[] = $sql;

            return null;
        }

        return parent::_execute($sql);
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
 * MysqliTestModel class.
 */
class MysqliTestModel extends Model
{
    /**
     * name property.
     *
     * @var string 'MysqlTestModel'
     */
    public $name = 'MysqliTestModel';

    /**
     * useTable property.
     *
     * @var bool false
     */
    public $useTable = false;

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
 * DboMysqliTest class.
 */
class DboMysqliTest extends CakeTestCase
{
    public $fixtures = ['core.datatype'];
    /**
     * The Dbo instance to be tested.
     *
     * @var DboSource
     */
    public $Db = null;

    /**
     * Skip if cannot connect to mysqli.
     */
    public function skip()
    {
        $this->_initDb();
        $this->skipUnless('mysqli' == $this->db->config['driver'], '%s MySQLi connection not available');
    }

    /**
     * Sets up a Dbo class instance for testing.
     */
    public function setUp()
    {
        $this->model = new MysqliTestModel();
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
     * testIndexDetection method.
     */
    public function testIndexDetection()
    {
        $this->db->cacheSources = false;

        $name = $this->db->fullTableName('simple');
        $this->db->query('CREATE TABLE '.$name.' (id int(11) AUTO_INCREMENT, bool tinyint(1), small_int tinyint(2), primary key(id));');
        $expected = ['PRIMARY' => ['column' => 'id', 'unique' => 1]];
        $result = $this->db->index($name, false);
        $this->assertEqual($expected, $result);
        $this->db->query('DROP TABLE '.$name);

        $name = $this->db->fullTableName('with_a_key');
        $this->db->query('CREATE TABLE '.$name.' (id int(11) AUTO_INCREMENT, bool tinyint(1), small_int tinyint(2), primary key(id), KEY `pointless_bool` ( `bool` ));');
        $expected = [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'pointless_bool' => ['column' => 'bool', 'unique' => 0],
        ];
        $result = $this->db->index($name, false);
        $this->assertEqual($expected, $result);
        $this->db->query('DROP TABLE '.$name);

        $name = $this->db->fullTableName('with_two_keys');
        $this->db->query('CREATE TABLE '.$name.' (id int(11) AUTO_INCREMENT, bool tinyint(1), small_int tinyint(2), primary key(id), KEY `pointless_bool` ( `bool` ), KEY `pointless_small_int` ( `small_int` ));');
        $expected = [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'pointless_bool' => ['column' => 'bool', 'unique' => 0],
            'pointless_small_int' => ['column' => 'small_int', 'unique' => 0],
        ];
        $result = $this->db->index($name, false);
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
        $result = $this->db->index($name, false);
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
        $result = $this->db->index($name, false);
        $this->assertEqual($expected, $result);
        $this->db->query('DROP TABLE '.$name);
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
     * test transaction commands.
     */
    public function testTransactions()
    {
        $this->db->testing = false;
        $result = $this->db->begin($this->model);
        $this->assertTrue($result);

        $beginSqlCalls = Set::extract('/.[query=START TRANSACTION]', $this->db->_queriesLog);
        $this->assertEqual(1, count($beginSqlCalls));

        $result = $this->db->commit($this->model);
        $this->assertTrue($result);
    }

    /**
     * test that float values are correctly identified.
     */
    public function testFloatParsing()
    {
        $model = new Model(['ds' => 'test_suite', 'table' => 'datatypes', 'name' => 'Datatype']);
        $result = $this->db->describe($model);
        $this->assertEqual((string) $result['float_field']['length'], '5,2');
    }

    /**
     * test that tableParameters like collation, charset and engine are functioning.
     */
    public function testReadTableParameters()
    {
        $this->db->cacheSources = $this->db->testing = false;
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
}
