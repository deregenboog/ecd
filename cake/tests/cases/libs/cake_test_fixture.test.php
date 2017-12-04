<?php
/**
 * CakeTestFixture file.
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
App::import('Datasource', 'DboSource', false);

/**
 * CakeTestFixtureTestFixture class.
 */
class CakeTestFixtureTestFixture extends CakeTestFixture
{
    /**
     * name Property.
     *
     * @var string
     */
    public $name = 'FixtureTest';

    /**
     * table property.
     *
     * @var string
     */
    public $table = 'fixture_tests';

    /**
     * Fields array.
     *
     * @var array
     */
    public $fields = [
        'id' => ['type' => 'integer',  'key' => 'primary'],
        'name' => ['type' => 'string', 'length' => '255'],
        'created' => ['type' => 'datetime'],
    ];

    /**
     * Records property.
     *
     * @var array
     */
    public $records = [
        ['name' => 'Gandalf', 'created' => '2009-04-28 19:20:00'],
        ['name' => 'Captain Picard', 'created' => '2009-04-28 19:20:00'],
        ['name' => 'Chewbacca', 'created' => '2009-04-28 19:20:00'],
    ];
}

/**
 * StringFieldsTestFixture class.
 */
class StringsTestFixture extends CakeTestFixture
{
    /**
     * name Property.
     *
     * @var string
     */
    public $name = 'Strings';

    /**
     * table property.
     *
     * @var string
     */
    public $table = 'strings';

    /**
     * Fields array.
     *
     * @var array
     */
    public $fields = [
        'id' => ['type' => 'integer',  'key' => 'primary'],
        'name' => ['type' => 'string', 'length' => '255'],
        'email' => ['type' => 'string', 'length' => '255'],
        'age' => ['type' => 'integer', 'default' => 10],
    ];

    /**
     * Records property.
     *
     * @var array
     */
    public $records = [
        ['name' => 'John Doe', 'email' => 'john.doe@email.com', 'age' => 20],
        ['email' => 'jane.doe@email.com', 'name' => 'Jane Doe', 'age' => 30],
        ['name' => 'Mark Doe', 'email' => 'mark.doe@email.com'],
    ];
}

/**
 * CakeTestFixtureImportFixture class.
 */
class CakeTestFixtureImportFixture extends CakeTestFixture
{
    /**
     * Name property.
     *
     * @var string
     */
    public $name = 'ImportFixture';

    /**
     * Import property.
     *
     * @var mixed
     */
    public $import = ['table' => 'fixture_tests', 'connection' => 'test_suite'];
}

/**
 * CakeTestFixtureDefaultImportFixture class.
 */
class CakeTestFixtureDefaultImportFixture extends CakeTestFixture
{
    /**
     * Name property.
     *
     * @var string
     */
    public $name = 'ImportFixture';
}

/**
 * FixtureImportTestModel class.
 */
class FixtureImportTestModel extends Model
{
    public $name = 'FixtureImport';
    public $useTable = 'fixture_tests';
    public $useDbConfig = 'test_suite';
}

class FixturePrefixTest extends Model
{
    public $name = 'FixturePrefix';
    public $useTable = '_tests';
    public $tablePrefix = 'fixture';
    public $useDbConfig = 'test_suite';
}

Mock::generate('DboSource', 'BaseFixtureMockDboSource');

class FixtureMockDboSource extends BaseFixtureMockDboSource
{
    public $insertMulti;

    public function value($string)
    {
        return is_string($string) ? '\''.$string.'\'' : $string;
    }

    public function insertMulti($table, $fields, $values)
    {
        $this->insertMulti = compact('table', 'fields', 'values');

        return true;
    }
}

/**
 * Test case for CakeTestFixture.
 */
class CakeTestFixtureTest extends CakeTestCase
{
    /**
     * setUp method.
     */
    public function setUp()
    {
        $this->criticDb = new FixtureMockDboSource();
        $this->criticDb->fullDebug = true;
    }

    /**
     * tearDown.
     */
    public function tearDown()
    {
        unset($this->criticDb);
    }

    /**
     * testInit.
     */
    public function testInit()
    {
        $Fixture = new CakeTestFixtureTestFixture();
        unset($Fixture->table);
        $Fixture->init();
        $this->assertEqual($Fixture->table, 'fixture_tests');
        $this->assertEqual($Fixture->primaryKey, 'id');

        $Fixture = new CakeTestFixtureTestFixture();
        $Fixture->primaryKey = 'my_random_key';
        $Fixture->init();
        $this->assertEqual($Fixture->primaryKey, 'my_random_key');
    }

    /**
     * test that init() correctly sets the fixture table when the connection or model have prefixes defined.
     */
    public function testInitDbPrefix()
    {
        $this->_initDb();
        $Source = new CakeTestFixtureTestFixture();
        $Source->create($this->db);
        $Source->insert($this->db);

        $Fixture = new CakeTestFixtureImportFixture();
        $expected = ['id', 'name', 'created'];
        $this->assertEqual(array_keys($Fixture->fields), $expected);

        $db = &ConnectionManager::getDataSource('test_suite');
        $config = $db->config;
        $config['prefix'] = 'fixture_test_suite_';
        ConnectionManager::create('fixture_test_suite', $config);

        $Fixture->fields = $Fixture->records = null;
        $Fixture->import = ['table' => 'fixture_tests', 'connection' => 'test_suite', 'records' => true];
        $Fixture->init();
        $this->assertEqual(count($Fixture->records), count($Source->records));

        $Fixture = new CakeTestFixtureImportFixture();
        $Fixture->fields = $Fixture->records = $Fixture->table = null;
        $Fixture->import = ['model' => 'FixtureImportTestModel', 'connection' => 'test_suite'];
        $Fixture->init();
        $this->assertEqual(array_keys($Fixture->fields), ['id', 'name', 'created']);
        $this->assertEqual($Fixture->table, 'fixture_tests');

        $keys = array_flip(ClassRegistry::keys());
        $this->assertFalse(array_key_exists('fixtureimporttestmodel', $keys));

        $Source->drop($this->db);
    }

    /**
     * test that fixtures don't duplicate the test db prefix.
     */
    public function testInitDbPrefixDuplication()
    {
        $this->_initDb();
        $backPrefix = $this->db->config['prefix'];
        $this->db->config['prefix'] = 'cake_fixture_test_';

        $Source = new CakeTestFixtureTestFixture();
        $Source->create($this->db);
        $Source->insert($this->db);

        $Fixture = new CakeTestFixtureImportFixture();
        $Fixture->fields = $Fixture->records = $Fixture->table = null;
        $Fixture->import = ['model' => 'FixtureImportTestModel', 'connection' => 'test_suite'];

        $Fixture->init();
        $this->assertEqual(array_keys($Fixture->fields), ['id', 'name', 'created']);
        $this->assertEqual($Fixture->table, 'fixture_tests');

        $Source->drop($this->db);
        $this->db->config['prefix'] = $backPrefix;
    }

    /**
     * test init with a model that has a tablePrefix declared.
     */
    public function testInitModelTablePrefix()
    {
        $this->_initDb();
        $hasPrefix = !empty($this->db->config['prefix']);
        if ($this->skipIf($hasPrefix, 'Cannot run this test, you have a database connection prefix.')) {
            return;
        }
        $Source = new CakeTestFixtureTestFixture();
        $Source->create($this->db);
        $Source->insert($this->db);

        $Fixture = new CakeTestFixtureImportFixture();
        unset($Fixture->table);
        $Fixture->fields = $Fixture->records = null;
        $Fixture->import = ['model' => 'FixturePrefixTest', 'connection' => 'test_suite', 'records' => false];
        $Fixture->init();
        $this->assertEqual($Fixture->table, 'fixture_tests');

        $keys = array_flip(ClassRegistry::keys());
        $this->assertFalse(array_key_exists('fixtureimporttestmodel', $keys));

        $Source->drop($this->db);
    }

    /**
     * testImport.
     */
    public function testImport()
    {
        $this->_initDb();

        $defaultDb = &ConnectionManager::getDataSource('default');
        $testSuiteDb = &ConnectionManager::getDataSource('test_suite');
        $defaultConfig = $defaultDb->config;
        $testSuiteConfig = $testSuiteDb->config;
        ConnectionManager::create('new_test_suite', array_merge($testSuiteConfig, ['prefix' => 'new_'.$testSuiteConfig['prefix']]));
        $newTestSuiteDb = &ConnectionManager::getDataSource('new_test_suite');

        $Source = new CakeTestFixtureTestFixture();
        $Source->create($newTestSuiteDb);
        $Source->insert($newTestSuiteDb);

        $defaultDb->config = $newTestSuiteDb->config;

        $Fixture = new CakeTestFixtureDefaultImportFixture();
        $Fixture->fields = $Fixture->records = null;
        $Fixture->import = ['model' => 'FixtureImportTestModel', 'connection' => 'new_test_suite'];
        $Fixture->init();
        $this->assertEqual(array_keys($Fixture->fields), ['id', 'name', 'created']);

        $defaultDb->config = $defaultConfig;

        $keys = array_flip(ClassRegistry::keys());
        $this->assertFalse(array_key_exists('fixtureimporttestmodel', $keys));

        $Source->drop($newTestSuiteDb);
    }

    /**
     * test that importing with records works.  Make sure to try with postgres as its
     * handling of aliases is a workaround at best.
     */
    public function testImportWithRecords()
    {
        $this->_initDb();

        $defaultDb = &ConnectionManager::getDataSource('default');
        $testSuiteDb = &ConnectionManager::getDataSource('test_suite');
        $defaultConfig = $defaultDb->config;
        $testSuiteConfig = $testSuiteDb->config;
        ConnectionManager::create('new_test_suite', array_merge($testSuiteConfig, ['prefix' => 'new_'.$testSuiteConfig['prefix']]));
        $newTestSuiteDb = &ConnectionManager::getDataSource('new_test_suite');

        $Source = new CakeTestFixtureTestFixture();
        $Source->create($newTestSuiteDb);
        $Source->insert($newTestSuiteDb);

        $defaultDb->config = $newTestSuiteDb->config;

        $Fixture = new CakeTestFixtureDefaultImportFixture();
        $Fixture->fields = $Fixture->records = null;
        $Fixture->import = [
            'model' => 'FixtureImportTestModel', 'connection' => 'new_test_suite', 'records' => true,
        ];
        $Fixture->init();
        $this->assertEqual(array_keys($Fixture->fields), ['id', 'name', 'created']);
        $this->assertFalse(empty($Fixture->records[0]), 'No records loaded on importing fixture.');
        $this->assertTrue(isset($Fixture->records[0]['name']), 'No name loaded for first record');

        $defaultDb->config = $defaultConfig;

        $Source->drop($newTestSuiteDb);
    }

    /**
     * test create method.
     */
    public function testCreate()
    {
        $Fixture = new CakeTestFixtureTestFixture();
        $this->criticDb->expectAtLeastOnce('execute');
        $this->criticDb->expectAtLeastOnce('createSchema');
        $return = $Fixture->create($this->criticDb);
        $this->assertTrue($this->criticDb->fullDebug);
        $this->assertTrue($return);

        unset($Fixture->fields);
        $return = $Fixture->create($this->criticDb);
        $this->assertFalse($return);
    }

    /**
     * test the insert method.
     */
    public function testInsert()
    {
        $Fixture = new CakeTestFixtureTestFixture();

        $this->criticDb->insertMulti = [];
        $return = $Fixture->insert($this->criticDb);
        $this->assertTrue(!empty($this->criticDb->insertMulti));
        $this->assertTrue($this->criticDb->fullDebug);
        $this->assertTrue($return);
        $this->assertEqual('fixture_tests', $this->criticDb->insertMulti['table']);
        $this->assertEqual(['name', 'created'], $this->criticDb->insertMulti['fields']);
        $expected = [
            '(\'Gandalf\', \'2009-04-28 19:20:00\')',
            '(\'Captain Picard\', \'2009-04-28 19:20:00\')',
            '(\'Chewbacca\', \'2009-04-28 19:20:00\')',
        ];
        $this->assertEqual($expected, $this->criticDb->insertMulti['values']);
    }

    /**
     * test the insert method.
     */
    public function testInsertStrings()
    {
        $Fixture = new StringsTestFixture();

        $this->criticDb->insertMulti = [];
        $return = $Fixture->insert($this->criticDb);
        $this->assertTrue(!empty($this->criticDb->insertMulti));
        $this->assertTrue($this->criticDb->fullDebug);
        $this->assertTrue($return);
        $this->assertEqual('strings', $this->criticDb->insertMulti['table']);
        $this->assertEqual(['name', 'email', 'age'], $this->criticDb->insertMulti['fields']);
        $expected = [
            '(\'John Doe\', \'john.doe@email.com\', 20)',
            '(\'Jane Doe\', \'jane.doe@email.com\', 30)',
            '(\'Mark Doe\', \'mark.doe@email.com\', NULL)',
        ];
        $this->assertEqual($expected, $this->criticDb->insertMulti['values']);
    }

    /**
     * Test the drop method.
     */
    public function testDrop()
    {
        $Fixture = new CakeTestFixtureTestFixture();
        $this->criticDb->setReturnValueAt(0, 'execute', true);
        $this->criticDb->expectAtLeastOnce('execute');
        $this->criticDb->expectAtLeastOnce('dropSchema');

        $return = $Fixture->drop($this->criticDb);
        $this->assertTrue($this->criticDb->fullDebug);
        $this->assertTrue($return);

        $this->criticDb->setReturnValueAt(1, 'execute', false);
        $return = $Fixture->drop($this->criticDb);
        $this->assertFalse($return);

        unset($Fixture->fields);
        $return = $Fixture->drop($this->criticDb);
        $this->assertFalse($return);
    }

    /**
     * Test the truncate method.
     */
    public function testTruncate()
    {
        $Fixture = new CakeTestFixtureTestFixture();
        $this->criticDb->expectAtLeastOnce('truncate');
        $Fixture->truncate($this->criticDb);
        $this->assertTrue($this->criticDb->fullDebug);
    }
}
