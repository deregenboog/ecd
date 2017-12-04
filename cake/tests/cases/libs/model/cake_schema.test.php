<?php
/**
 * Test for Schema database management.
 *
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
 * @since         CakePHP(tm) v 1.2.0.5550
 *
 * @license       http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */
App::import('Model', 'CakeSchema', false);

/**
 * Test for Schema database management.
 */
class MyAppSchema extends CakeSchema
{
    /**
     * name property.
     *
     * @var string 'MyApp'
     */
    public $name = 'MyApp';

    /**
     * connection property.
     *
     * @var string 'test_suite'
     */
    public $connection = 'test_suite';

    /**
     * comments property.
     *
     * @var array
     */
    public $comments = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => 0, 'key' => 'primary'],
        'post_id' => ['type' => 'integer', 'null' => false, 'default' => 0],
        'user_id' => ['type' => 'integer', 'null' => false],
        'title' => ['type' => 'string', 'null' => false, 'length' => 100],
        'comment' => ['type' => 'text', 'null' => false, 'default' => null],
        'published' => ['type' => 'string', 'null' => true, 'default' => 'N', 'length' => 1],
        'created' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'updated' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'indexes' => ['PRIMARY' => ['column' => 'id', 'unique' => true]],
    ];

    /**
     * posts property.
     *
     * @var array
     */
    public $posts = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => 0, 'key' => 'primary'],
        'author_id' => ['type' => 'integer', 'null' => true, 'default' => ''],
        'title' => ['type' => 'string', 'null' => false, 'default' => 'Title'],
        'body' => ['type' => 'text', 'null' => true, 'default' => null],
        'summary' => ['type' => 'text', 'null' => true],
        'published' => ['type' => 'string', 'null' => true, 'default' => 'Y', 'length' => 1],
        'created' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'updated' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'indexes' => ['PRIMARY' => ['column' => 'id', 'unique' => true]],
    ];

    /**
     * setup method.
     *
     * @param mixed $version
     */
    public function setup($version)
    {
    }

    /**
     * teardown method.
     *
     * @param mixed $version
     */
    public function teardown($version)
    {
    }
}

/**
 * TestAppSchema class.
 */
class TestAppSchema extends CakeSchema
{
    /**
     * name property.
     *
     * @var string 'MyApp'
     */
    public $name = 'MyApp';

    /**
     * comments property.
     *
     * @var array
     */
    public $comments = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => 0, 'key' => 'primary'],
        'article_id' => ['type' => 'integer', 'null' => false],
        'user_id' => ['type' => 'integer', 'null' => false],
        'comment' => ['type' => 'text', 'null' => true, 'default' => null],
        'published' => ['type' => 'string', 'null' => true, 'default' => 'N', 'length' => 1],
        'created' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'updated' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'indexes' => ['PRIMARY' => ['column' => 'id', 'unique' => true]],
        'tableParameters' => [],
    ];

    /**
     * posts property.
     *
     * @var array
     */
    public $posts = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => 0, 'key' => 'primary'],
        'author_id' => ['type' => 'integer', 'null' => false],
        'title' => ['type' => 'string', 'null' => false],
        'body' => ['type' => 'text', 'null' => true, 'default' => null],
        'published' => ['type' => 'string', 'null' => true, 'default' => 'N', 'length' => 1],
        'created' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'updated' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'indexes' => ['PRIMARY' => ['column' => 'id', 'unique' => true]],
        'tableParameters' => [],
    ];

    /**
     * posts_tags property.
     *
     * @var array
     */
    public $posts_tags = [
        'post_id' => ['type' => 'integer', 'null' => false, 'key' => 'primary'],
        'tag_id' => ['type' => 'string', 'null' => false, 'key' => 'primary'],
        'indexes' => ['posts_tag' => ['column' => ['tag_id', 'post_id'], 'unique' => 1]],
        'tableParameters' => [],
    ];

    /**
     * tags property.
     *
     * @var array
     */
    public $tags = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => 0, 'key' => 'primary'],
        'tag' => ['type' => 'string', 'null' => false],
        'created' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'updated' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'indexes' => ['PRIMARY' => ['column' => 'id', 'unique' => true]],
        'tableParameters' => [],
    ];

    /**
     * datatypes property.
     *
     * @var array
     */
    public $datatypes = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => 0, 'key' => 'primary'],
        'float_field' => ['type' => 'float', 'null' => false, 'length' => '5,2', 'default' => ''],
        'bool' => ['type' => 'boolean', 'null' => false, 'default' => false],
        'indexes' => ['PRIMARY' => ['column' => 'id', 'unique' => true]],
        'tableParameters' => [],
    ];

    /**
     * setup method.
     *
     * @param mixed $version
     */
    public function setup($version)
    {
    }

    /**
     * teardown method.
     *
     * @param mixed $version
     */
    public function teardown($version)
    {
    }
}

/**
 * SchmeaPost class.
 */
class SchemaPost extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'SchemaPost'
     */
    public $name = 'SchemaPost';

    /**
     * useTable property.
     *
     * @var string 'posts'
     */
    public $useTable = 'posts';

    /**
     * hasMany property.
     *
     * @var array
     */
    public $hasMany = ['SchemaComment'];

    /**
     * hasAndBelongsToMany property.
     *
     * @var array
     */
    public $hasAndBelongsToMany = ['SchemaTag'];
}

/**
 * SchemaComment class.
 */
class SchemaComment extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'SchemaComment'
     */
    public $name = 'SchemaComment';

    /**
     * useTable property.
     *
     * @var string 'comments'
     */
    public $useTable = 'comments';

    /**
     * belongsTo property.
     *
     * @var array
     */
    public $belongsTo = ['SchemaPost'];
}

/**
 * SchemaTag class.
 */
class SchemaTag extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'SchemaTag'
     */
    public $name = 'SchemaTag';

    /**
     * useTable property.
     *
     * @var string 'tags'
     */
    public $useTable = 'tags';

    /**
     * hasAndBelongsToMany property.
     *
     * @var array
     */
    public $hasAndBelongsToMany = ['SchemaPost'];
}

/**
 * SchemaDatatype class.
 */
class SchemaDatatype extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'SchemaDatatype'
     */
    public $name = 'SchemaDatatype';

    /**
     * useTable property.
     *
     * @var string 'datatypes'
     */
    public $useTable = 'datatypes';
}

/**
 * Testdescribe class.
 *
 * This class is defined purely to inherit the cacheSources variable otherwise
 * testSchemaCreatTable will fail if listSources has already been called and
 * its source cache populated - I.e. if the test is run within a group
 *
 * @uses          \CakeTestModel
 */
class Testdescribe extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'Testdescribe'
     */
    public $name = 'Testdescribe';
}

/**
 * SchemaCrossDatabase class.
 */
class SchemaCrossDatabase extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string 'SchemaCrossDatabase'
     */
    public $name = 'SchemaCrossDatabase';

    /**
     * useTable property.
     *
     * @var string 'posts'
     */
    public $useTable = 'cross_database';

    /**
     * useDbConfig property.
     *
     * @var string 'test2'
     */
    public $useDbConfig = 'test2';
}

/**
 * SchemaCrossDatabaseFixture class.
 */
class SchemaCrossDatabaseFixture extends CakeTestFixture
{
    /**
     * name property.
     *
     * @var string 'CrossDatabase'
     */
    public $name = 'CrossDatabase';

    /**
     * table property.
     */
    public $table = 'cross_database';

    /**
     * fields property.
     *
     * @var array
     */
    public $fields = [
        'id' => ['type' => 'integer', 'key' => 'primary'],
        'name' => 'string',
    ];

    /**
     * records property.
     *
     * @var array
     */
    public $records = [
        ['id' => 1, 'name' => 'First'],
        ['id' => 2, 'name' => 'Second'],
    ];
}

/**
 * SchemaPrefixAuthUser class.
 */
class SchemaPrefixAuthUser extends CakeTestModel
{
    /**
     * name property.
     *
     * @var string
     */
    public $name = 'SchemaPrefixAuthUser';
    /**
     * table prefix.
     *
     * @var string
     */
    public $tablePrefix = 'auth_';
    /**
     * useTable.
     *
     * @var string
     */
    public $useTable = 'users';
}

/**
 * CakeSchemaTest.
 */
class CakeSchemaTest extends CakeTestCase
{
    /**
     * fixtures property.
     *
     * @var array
     */
    public $fixtures = [
        'core.post', 'core.tag', 'core.posts_tag', 'core.test_plugin_comment',
        'core.datatype', 'core.auth_user', 'core.author',
        'core.test_plugin_article', 'core.user', 'core.comment',
        'core.prefix_test',
    ];

    /**
     * setUp method.
     */
    public function startTest()
    {
        $this->Schema = new TestAppSchema();
    }

    /**
     * tearDown method.
     */
    public function tearDown()
    {
        @unlink(TMP.'tests'.DS.'schema.php');
        unset($this->Schema);
        ClassRegistry::flush();
    }

    /**
     * testSchemaName method.
     */
    public function testSchemaName()
    {
        $Schema = new CakeSchema();
        $this->assertEqual(strtolower($Schema->name), strtolower(APP_DIR));

        Configure::write('App.dir', 'Some.name.with.dots');
        $Schema = new CakeSchema();
        $this->assertEqual($Schema->name, 'SomeNameWithDots');

        Configure::write('App.dir', 'app');
    }

    /**
     * testSchemaRead method.
     */
    public function testSchemaRead()
    {
        $read = $this->Schema->read([
            'connection' => 'test_suite',
            'name' => 'TestApp',
            'models' => ['SchemaPost', 'SchemaComment', 'SchemaTag', 'SchemaDatatype'],
        ]);
        unset($read['tables']['missing']);

        $expected = ['comments', 'datatypes', 'posts', 'posts_tags', 'tags'];
        $this->assertEqual(array_keys($read['tables']), $expected);
        foreach ($read['tables'] as $table => $fields) {
            $this->assertEqual(array_keys($fields), array_keys($this->Schema->tables[$table]));
        }

        $this->assertEqual(
            $read['tables']['datatypes']['float_field'],
            $this->Schema->tables['datatypes']['float_field']
        );

        $SchemaPost = &ClassRegistry::init('SchemaPost');
        $SchemaPost->table = 'sts';
        $SchemaPost->tablePrefix = 'po';
        $read = $this->Schema->read([
            'connection' => 'test_suite',
            'name' => 'TestApp',
            'models' => ['SchemaPost'],
        ]);
        $this->assertFalse(isset($read['tables']['missing']['posts']), 'Posts table was not read from tablePrefix %s');

        $read = $this->Schema->read([
            'connection' => 'test_suite',
            'name' => 'TestApp',
            'models' => ['SchemaComment', 'SchemaTag', 'SchemaPost'],
        ]);
        $this->assertFalse(isset($read['tables']['missing']['posts_tags']), 'Join table marked as missing %s');
    }

    /**
     * test read() with tablePrefix properties.
     */
    public function testSchemaReadWithTablePrefix()
    {
        $model = new SchemaPrefixAuthUser();

        $Schema = new CakeSchema();
        $read = $Schema->read([
            'connection' => 'test_suite',
            'name' => 'TestApp',
            'models' => ['SchemaPrefixAuthUser'],
        ]);
        unset($read['tables']['missing']);
        $this->assertTrue(isset($read['tables']['auth_users']), 'auth_users key missing %s');
    }

    /**
     * test reading schema with config prefix.
     */
    public function testSchemaReadWithConfigPrefix()
    {
        $db = &ConnectionManager::getDataSource('test_suite');
        $config = $db->config;
        $config['prefix'] = 'schema_test_prefix_';
        ConnectionManager::create('schema_prefix', $config);
        $read = $this->Schema->read(['connection' => 'schema_prefix', 'models' => false]);
        $this->assertTrue(empty($read['tables']));

        $config['prefix'] = 'prefix_';
        ConnectionManager::create('schema_prefix2', $config);
        $read = $this->Schema->read([
            'connection' => 'schema_prefix2',
            'name' => 'TestApp',
            'models' => false, ]);
        $this->assertTrue(isset($read['tables']['prefix_tests']));
    }

    /**
     * test reading schema from plugins.
     */
    public function testSchemaReadWithPlugins()
    {
        App::objects('model', null, false);
        App::build([
            'plugins' => [TEST_CAKE_CORE_INCLUDE_PATH.'tests'.DS.'test_app'.DS.'plugins'.DS],
        ]);

        $Schema = new CakeSchema();
        $Schema->plugin = 'TestPlugin';
        $read = $Schema->read([
            'connection' => 'test_suite',
            'name' => 'TestApp',
            'models' => true,
        ]);
        unset($read['tables']['missing']);
        $this->assertTrue(isset($read['tables']['auth_users']));
        $this->assertTrue(isset($read['tables']['authors']));
        $this->assertTrue(isset($read['tables']['test_plugin_comments']));
        $this->assertTrue(isset($read['tables']['posts']));
        $this->assertEqual(count($read['tables']), 4);

        App::build();
    }

    /**
     * test reading schema with tables from another database.
     */
    public function testSchemaReadWithCrossDatabase()
    {
        $config = new DATABASE_CONFIG();
        $skip = $this->skipIf(
            !isset($config->test) || !isset($config->test2),
             '%s Primary and secondary test databases not configured, skipping cross-database '
            .'join tests.'
            .' To run these tests, you must define $test and $test2 in your database configuration.'
        );
        if ($skip) {
            return;
        }

        $db2 = &ConnectionManager::getDataSource('test2');
        $fixture = new SchemaCrossDatabaseFixture();
        $fixture->create($db2);
        $fixture->insert($db2);

        $read = $this->Schema->read([
            'connection' => 'test_suite',
            'name' => 'TestApp',
            'models' => ['SchemaCrossDatabase', 'SchemaPost'],
        ]);
        $this->assertTrue(isset($read['tables']['posts']));
        $this->assertFalse(isset($read['tables']['cross_database']), 'Cross database should not appear');
        $this->assertFalse(isset($read['tables']['missing']['cross_database']), 'Cross database should not appear');

        $read = $this->Schema->read([
            'connection' => 'test2',
            'name' => 'TestApp',
            'models' => ['SchemaCrossDatabase', 'SchemaPost'],
        ]);
        $this->assertFalse(isset($read['tables']['posts']), 'Posts should not appear');
        $this->assertFalse(isset($read['tables']['posts']), 'Posts should not appear');
        $this->assertTrue(isset($read['tables']['cross_database']));

        $fixture->drop($db2);
    }

    /**
     * test that tables are generated correctly.
     */
    public function testGenerateTable()
    {
        $fields = [
            'id' => ['type' => 'integer', 'null' => false, 'default' => 0, 'key' => 'primary'],
            'author_id' => ['type' => 'integer', 'null' => false],
            'title' => ['type' => 'string', 'null' => false],
            'body' => ['type' => 'text', 'null' => true, 'default' => null],
            'published' => ['type' => 'string', 'null' => true, 'default' => 'N', 'length' => 1],
            'created' => ['type' => 'datetime', 'null' => true, 'default' => null],
            'updated' => ['type' => 'datetime', 'null' => true, 'default' => null],
            'indexes' => ['PRIMARY' => ['column' => 'id', 'unique' => true]],
        ];
        $result = $this->Schema->generateTable('posts', $fields);
        $this->assertPattern('/var \$posts/', $result);

        eval(substr($result, 4));
        $this->assertEqual($posts, $fields);
    }

    /**
     * testSchemaWrite method.
     */
    public function testSchemaWrite()
    {
        $write = $this->Schema->write(['name' => 'MyOtherApp', 'tables' => $this->Schema->tables, 'path' => TMP.'tests']);
        $file = file_get_contents(TMP.'tests'.DS.'schema.php');
        $this->assertEqual($write, $file);

        require_once TMP.'tests'.DS.'schema.php';
        $OtherSchema = new MyOtherAppSchema();
        $this->assertEqual($this->Schema->tables, $OtherSchema->tables);
    }

    /**
     * testSchemaComparison method.
     */
    public function testSchemaComparison()
    {
        $New = new MyAppSchema();
        $compare = $New->compare($this->Schema);
        $expected = [
            'comments' => [
                'add' => [
                    'post_id' => ['type' => 'integer', 'null' => false, 'default' => 0, 'after' => 'id'],
                    'title' => ['type' => 'string', 'null' => false, 'length' => 100, 'after' => 'user_id'],
                ],
                'drop' => [
                    'article_id' => ['type' => 'integer', 'null' => false],
                    'tableParameters' => [],
                ],
                'change' => [
                    'comment' => ['type' => 'text', 'null' => false, 'default' => null],
                ],
            ],
            'posts' => [
                'add' => [
                    'summary' => ['type' => 'text', 'null' => 1, 'after' => 'body'],
                ],
                'drop' => [
                    'tableParameters' => [],
                ],
                'change' => [
                    'author_id' => ['type' => 'integer', 'null' => true, 'default' => ''],
                    'title' => ['type' => 'string', 'null' => false, 'default' => 'Title'],
                    'published' => ['type' => 'string', 'null' => true, 'default' => 'Y', 'length' => '1'],
                ],
            ],
        ];
        $this->assertEqual($expected, $compare);

        $tables = [
            'missing' => [
                'categories' => [
                    'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
                    'created' => ['type' => 'datetime', 'null' => false, 'default' => null],
                    'modified' => ['type' => 'datetime', 'null' => false, 'default' => null],
                    'name' => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 100],
                    'indexes' => ['PRIMARY' => ['column' => 'id', 'unique' => 1]],
                    'tableParameters' => ['charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM'],
                ],
            ],
            'ratings' => [
                'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
                'foreign_key' => ['type' => 'integer', 'null' => false, 'default' => null],
                'model' => ['type' => 'varchar', 'null' => false, 'default' => null],
                'value' => ['type' => 'float', 'null' => false, 'length' => '5,2', 'default' => null],
                'created' => ['type' => 'datetime', 'null' => false, 'default' => null],
                'modified' => ['type' => 'datetime', 'null' => false, 'default' => null],
                'indexes' => ['PRIMARY' => ['column' => 'id', 'unique' => 1]],
                'tableParameters' => ['charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM'],
            ],
        ];
        $compare = $New->compare($this->Schema, $tables);
        $expected = [
            'ratings' => [
                'add' => [
                    'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
                    'foreign_key' => ['type' => 'integer', 'null' => false, 'default' => null, 'after' => 'id'],
                    'model' => ['type' => 'varchar', 'null' => false, 'default' => null, 'after' => 'foreign_key'],
                    'value' => ['type' => 'float', 'null' => false, 'length' => '5,2', 'default' => null, 'after' => 'model'],
                    'created' => ['type' => 'datetime', 'null' => false, 'default' => null, 'after' => 'value'],
                    'modified' => ['type' => 'datetime', 'null' => false, 'default' => null, 'after' => 'created'],
                    'indexes' => ['PRIMARY' => ['column' => 'id', 'unique' => 1]],
                    'tableParameters' => ['charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM'],
                ],
            ],
        ];
        $this->assertEqual($expected, $compare);
    }

    /**
     * test comparing '' and null and making sure they are different.
     */
    public function testCompareEmptyStringAndNull()
    {
        $One = new CakeSchema([
            'posts' => [
                'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
                'name' => ['type' => 'string', 'null' => false, 'default' => ''],
            ],
        ]);
        $Two = new CakeSchema([
            'posts' => [
                'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
                'name' => ['type' => 'string', 'null' => false, 'default' => null],
            ],
        ]);
        $compare = $One->compare($Two);
        $expected = [
            'posts' => [
                'change' => [
                    'name' => ['type' => 'string', 'null' => false, 'default' => null],
                ],
            ],
        ];
        $this->assertEqual($expected, $compare);
    }

    /**
     * Test comparing tableParameters and indexes.
     */
    public function testTableParametersAndIndexComparison()
    {
        $old = [
            'posts' => [
                'id' => ['type' => 'integer', 'null' => false, 'default' => 0, 'key' => 'primary'],
                'author_id' => ['type' => 'integer', 'null' => false],
                'title' => ['type' => 'string', 'null' => false],
                'indexes' => [
                    'PRIMARY' => ['column' => 'id', 'unique' => true],
                ],
                'tableParameters' => [
                    'charset' => 'latin1',
                    'collate' => 'latin1_general_ci',
                ],
            ],
            'comments' => [
                'id' => ['type' => 'integer', 'null' => false, 'default' => 0, 'key' => 'primary'],
                'post_id' => ['type' => 'integer', 'null' => false, 'default' => 0],
                'comment' => ['type' => 'text'],
                'indexes' => [
                    'PRIMARY' => ['column' => 'id', 'unique' => true],
                    'post_id' => ['column' => 'post_id'],
                ],
                'tableParameters' => [
                    'engine' => 'InnoDB',
                    'charset' => 'latin1',
                    'collate' => 'latin1_general_ci',
                ],
            ],
        ];
        $new = [
            'posts' => [
                'id' => ['type' => 'integer', 'null' => false, 'default' => 0, 'key' => 'primary'],
                'author_id' => ['type' => 'integer', 'null' => false],
                'title' => ['type' => 'string', 'null' => false],
                'indexes' => [
                    'PRIMARY' => ['column' => 'id', 'unique' => true],
                    'author_id' => ['column' => 'author_id'],
                ],
                'tableParameters' => [
                    'charset' => 'utf8',
                    'collate' => 'utf8_general_ci',
                    'engine' => 'MyISAM',
                ],
            ],
            'comments' => [
                'id' => ['type' => 'integer', 'null' => false, 'default' => 0, 'key' => 'primary'],
                'post_id' => ['type' => 'integer', 'null' => false, 'default' => 0],
                'comment' => ['type' => 'text'],
                'indexes' => [
                    'PRIMARY' => ['column' => 'id', 'unique' => true],
                ],
                'tableParameters' => [
                    'charset' => 'utf8',
                    'collate' => 'utf8_general_ci',
                ],
            ],
        ];
        $compare = $this->Schema->compare($old, $new);
        $expected = [
            'posts' => [
                'add' => [
                    'indexes' => ['author_id' => ['column' => 'author_id']],
                ],
                'change' => [
                    'tableParameters' => [
                        'charset' => 'utf8',
                        'collate' => 'utf8_general_ci',
                        'engine' => 'MyISAM',
                    ],
                ],
            ],
            'comments' => [
                'drop' => [
                    'indexes' => ['post_id' => ['column' => 'post_id']],
                ],
                'change' => [
                    'tableParameters' => [
                        'charset' => 'utf8',
                        'collate' => 'utf8_general_ci',
                    ],
                ],
            ],
        ];
        $this->assertEqual($compare, $expected);
    }

    /**
     * testSchemaLoading method.
     */
    public function testSchemaLoading()
    {
        $Other = &$this->Schema->load(['name' => 'MyOtherApp', 'path' => TMP.'tests']);
        $this->assertEqual($Other->name, 'MyOtherApp');
        $this->assertEqual($Other->tables, $this->Schema->tables);
    }

    /**
     * test loading schema files inside of plugins.
     */
    public function testSchemaLoadingFromPlugin()
    {
        App::build([
            'plugins' => [TEST_CAKE_CORE_INCLUDE_PATH.'tests'.DS.'test_app'.DS.'plugins'.DS],
        ]);
        $Other = &$this->Schema->load(['name' => 'TestPluginApp', 'plugin' => 'TestPlugin']);
        $this->assertEqual($Other->name, 'TestPluginApp');
        $this->assertEqual(array_keys($Other->tables), ['acos']);

        App::build();
    }

    /**
     * testSchemaCreateTable method.
     */
    public function testSchemaCreateTable()
    {
        $db = &ConnectionManager::getDataSource('test_suite');
        $db->cacheSources = false;

        $Schema = new CakeSchema([
            'connection' => 'test_suite',
            'testdescribes' => [
                'id' => ['type' => 'integer', 'key' => 'primary'],
                'int_null' => ['type' => 'integer', 'null' => true],
                'int_not_null' => ['type' => 'integer', 'null' => false],
            ],
        ]);
        $sql = $db->createSchema($Schema);

        $col = $Schema->tables['testdescribes']['int_null'];
        $col['name'] = 'int_null';
        $column = $this->db->buildColumn($col);
        $this->assertPattern('/'.preg_quote($column, '/').'/', $sql);

        $col = $Schema->tables['testdescribes']['int_not_null'];
        $col['name'] = 'int_not_null';
        $column = $this->db->buildColumn($col);
        $this->assertPattern('/'.preg_quote($column, '/').'/', $sql);
    }
}
