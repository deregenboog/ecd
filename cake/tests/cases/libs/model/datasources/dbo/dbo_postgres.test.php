<?php
/**
 * DboPostgresTest file.
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
App::import('Core', ['Model', 'DataSource', 'DboSource', 'DboPostgres']);
App::import('Model', 'App');
require_once dirname(dirname(dirname(__FILE__))).DS.'models.php';

/**
 * DboPostgresTestDb class.
 */
class DboPostgresTestDb extends DboPostgres
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
 * PostgresTestModel class.
 */
class PostgresTestModel extends Model
{
    /**
     * name property.
     *
     * @var string 'PostgresTestModel'
     */
    public $name = 'PostgresTestModel';

    /**
     * useTable property.
     *
     * @var bool false
     */
    public $useTable = false;

    /**
     * belongsTo property.
     *
     * @var array
     */
    public $belongsTo = [
        'PostgresClientTestModel' => [
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
 * PostgresClientTestModel class.
 */
class PostgresClientTestModel extends Model
{
    /**
     * name property.
     *
     * @var string 'PostgresClientTestModel'
     */
    public $name = 'PostgresClientTestModel';

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
            'id' => ['type' => 'integer', 'null' => '', 'default' => '', 'length' => '8', 'key' => 'primary'],
            'name' => ['type' => 'string', 'null' => '', 'default' => '', 'length' => '255'],
            'email' => ['type' => 'string', 'null' => '1', 'default' => '', 'length' => '155'],
            'created' => ['type' => 'datetime', 'null' => '1', 'default' => '', 'length' => ''],
            'updated' => ['type' => 'datetime', 'null' => '1', 'default' => '', 'length' => null],
        ];
    }
}

/**
 * DboPostgresTest class.
 */
class DboPostgresTest extends CakeTestCase
{
    /**
     * Do not automatically load fixtures for each test, they will be loaded manually
     * using CakeTestCase::loadFixtures.
     *
     * @var bool
     */
    public $autoFixtures = false;

    /**
     * Fixtures.
     *
     * @var object
     */
    public $fixtures = ['core.user', 'core.binary_test', 'core.comment', 'core.article',
        'core.tag', 'core.articles_tag', 'core.attachment', 'core.person', 'core.post', 'core.author',
        'core.datatype',
    ];
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
     * Skip if cannot connect to postgres.
     */
    public function skip()
    {
        $this->_initDb();
        $this->skipUnless('postgres' == $this->db->config['driver'], '%s PostgreSQL connection not available');
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
        $this->db2 = new DboPostgresTestDb($this->db->config, false);
        $this->model = new PostgresTestModel();
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
     * Test field quoting method.
     */
    public function testFieldQuoting()
    {
        $fields = [
            '"PostgresTestModel"."id" AS "PostgresTestModel__id"',
            '"PostgresTestModel"."client_id" AS "PostgresTestModel__client_id"',
            '"PostgresTestModel"."name" AS "PostgresTestModel__name"',
            '"PostgresTestModel"."login" AS "PostgresTestModel__login"',
            '"PostgresTestModel"."passwd" AS "PostgresTestModel__passwd"',
            '"PostgresTestModel"."addr_1" AS "PostgresTestModel__addr_1"',
            '"PostgresTestModel"."addr_2" AS "PostgresTestModel__addr_2"',
            '"PostgresTestModel"."zip_code" AS "PostgresTestModel__zip_code"',
            '"PostgresTestModel"."city" AS "PostgresTestModel__city"',
            '"PostgresTestModel"."country" AS "PostgresTestModel__country"',
            '"PostgresTestModel"."phone" AS "PostgresTestModel__phone"',
            '"PostgresTestModel"."fax" AS "PostgresTestModel__fax"',
            '"PostgresTestModel"."url" AS "PostgresTestModel__url"',
            '"PostgresTestModel"."email" AS "PostgresTestModel__email"',
            '"PostgresTestModel"."comments" AS "PostgresTestModel__comments"',
            '"PostgresTestModel"."last_login" AS "PostgresTestModel__last_login"',
            '"PostgresTestModel"."created" AS "PostgresTestModel__created"',
            '"PostgresTestModel"."updated" AS "PostgresTestModel__updated"',
        ];

        $result = $this->db->fields($this->model);
        $expected = $fields;
        $this->assertEqual($result, $expected);

        $result = $this->db->fields($this->model, null, 'PostgresTestModel.*');
        $expected = $fields;
        $this->assertEqual($result, $expected);

        $result = $this->db->fields($this->model, null, ['*', 'AnotherModel.id', 'AnotherModel.name']);
        $expected = array_merge($fields, [
            '"AnotherModel"."id" AS "AnotherModel__id"',
            '"AnotherModel"."name" AS "AnotherModel__name"', ]);
        $this->assertEqual($result, $expected);

        $result = $this->db->fields($this->model, null, ['*', 'PostgresClientTestModel.*']);
        $expected = array_merge($fields, [
            '"PostgresClientTestModel"."id" AS "PostgresClientTestModel__id"',
            '"PostgresClientTestModel"."name" AS "PostgresClientTestModel__name"',
            '"PostgresClientTestModel"."email" AS "PostgresClientTestModel__email"',
            '"PostgresClientTestModel"."created" AS "PostgresClientTestModel__created"',
            '"PostgresClientTestModel"."updated" AS "PostgresClientTestModel__updated"', ]);
        $this->assertEqual($result, $expected);
    }

    /**
     * testColumnParsing method.
     */
    public function testColumnParsing()
    {
        $this->assertEqual($this->db2->column('text'), 'text');
        $this->assertEqual($this->db2->column('date'), 'date');
        $this->assertEqual($this->db2->column('boolean'), 'boolean');
        $this->assertEqual($this->db2->column('character varying'), 'string');
        $this->assertEqual($this->db2->column('time without time zone'), 'time');
        $this->assertEqual($this->db2->column('timestamp without time zone'), 'datetime');
    }

    /**
     * testValueQuoting method.
     */
    public function testValueQuoting()
    {
        $this->assertIdentical($this->db2->value(1.2, 'float'), "'1.200000'");
        $this->assertEqual($this->db2->value('1,2', 'float'), "'1,2'");

        $this->assertEqual($this->db2->value('0', 'integer'), "'0'");
        $this->assertEqual($this->db2->value('', 'integer'), 'NULL');
        $this->assertEqual($this->db2->value('', 'float'), 'NULL');
        $this->assertEqual($this->db2->value('', 'integer', false), 'DEFAULT');
        $this->assertEqual($this->db2->value('', 'float', false), 'DEFAULT');
        $this->assertEqual($this->db2->value('0.0', 'float'), "'0.0'");

        $this->assertEqual($this->db2->value('t', 'boolean'), 'TRUE');
        $this->assertEqual($this->db2->value('f', 'boolean'), 'FALSE');
        $this->assertEqual($this->db2->value(true), 'TRUE');
        $this->assertEqual($this->db2->value(false), 'FALSE');
        $this->assertEqual($this->db2->value('t'), "'t'");
        $this->assertEqual($this->db2->value('f'), "'f'");
        $this->assertEqual($this->db2->value('true', 'boolean'), 'TRUE');
        $this->assertEqual($this->db2->value('false', 'boolean'), 'FALSE');
        $this->assertEqual($this->db2->value('', 'boolean'), 'FALSE');
        $this->assertEqual($this->db2->value(0, 'boolean'), 'FALSE');
        $this->assertEqual($this->db2->value(1, 'boolean'), 'TRUE');
        $this->assertEqual($this->db2->value('1', 'boolean'), 'TRUE');
        $this->assertEqual($this->db2->value(null, 'boolean'), 'NULL');
        $this->assertEqual($this->db2->value([]), 'NULL');
    }

    /**
     * test that localized floats don't cause trouble.
     */
    public function testLocalizedFloats()
    {
        $restore = setlocale(LC_ALL, null);
        setlocale(LC_ALL, 'de_DE');

        $result = $this->db->value(3.141593, 'float');
        $this->assertEqual((string) $result, "'3.141593'");

        $result = $this->db->value(3.14);
        $this->assertEqual((string) $result, "'3.140000'");

        setlocale(LC_ALL, $restore);
    }

    /**
     * test that date and time columns do not generate errors with null and nullish values.
     */
    public function testDateAndTimeAsNull()
    {
        $this->assertEqual($this->db2->value(null, 'date'), 'NULL');
        $this->assertEqual($this->db2->value('', 'date'), 'NULL');

        $this->assertEqual($this->db2->value('', 'datetime'), 'NULL');
        $this->assertEqual($this->db2->value(null, 'datetime'), 'NULL');

        $this->assertEqual($this->db2->value('', 'timestamp'), 'NULL');
        $this->assertEqual($this->db2->value(null, 'timestamp'), 'NULL');

        $this->assertEqual($this->db2->value('', 'time'), 'NULL');
        $this->assertEqual($this->db2->value(null, 'time'), 'NULL');
    }

    /**
     * Tests that different Postgres boolean 'flavors' are properly returned as native PHP booleans.
     */
    public function testBooleanNormalization()
    {
        $this->assertTrue($this->db2->boolean('t'));
        $this->assertTrue($this->db2->boolean('true'));
        $this->assertTrue($this->db2->boolean('TRUE'));
        $this->assertTrue($this->db2->boolean(true));
        $this->assertTrue($this->db2->boolean(1));
        $this->assertTrue($this->db2->boolean(' '));

        $this->assertFalse($this->db2->boolean('f'));
        $this->assertFalse($this->db2->boolean('false'));
        $this->assertFalse($this->db2->boolean('FALSE'));
        $this->assertFalse($this->db2->boolean(false));
        $this->assertFalse($this->db2->boolean(0));
        $this->assertFalse($this->db2->boolean(''));
    }

    /**
     * test that default -> false in schemas works correctly.
     */
    public function testBooleanDefaultFalseInSchema()
    {
        $model = new Model(['name' => 'Datatype', 'table' => 'datatypes', 'ds' => 'test_suite']);
        $model->create();
        $this->assertIdentical(false, $model->data['Datatype']['bool']);
    }

    /**
     * testLastInsertIdMultipleInsert method.
     */
    public function testLastInsertIdMultipleInsert()
    {
        $db1 = ConnectionManager::getDataSource('test_suite');

        $table = $db1->fullTableName('users', false);
        $password = '5f4dcc3b5aa765d61d8327deb882cf99';
        $db1->execute(
            "INSERT INTO {$table} (\"user\", password) VALUES ('mariano', '{$password}')"
        );
        $this->assertEqual($db1->lastInsertId($table), 1);

        $db1->execute("INSERT INTO {$table} (\"user\", password) VALUES ('hoge', '{$password}')");
        $this->assertEqual($db1->lastInsertId($table), 2);
    }

    /**
     * Tests that table lists and descriptions are scoped to the proper Postgres schema.
     */
    public function testSchemaScoping()
    {
        $db1 = &ConnectionManager::getDataSource('test_suite');
        $db1->cacheSources = false;
        $db1->reconnect(['persistent' => false]);
        $db1->query('CREATE SCHEMA _scope_test');

        $db2 = &ConnectionManager::create(
            'test_suite_2',
            array_merge($db1->config, ['driver' => 'postgres', 'schema' => '_scope_test'])
        );
        $db2->cacheSources = false;

        $db2->query('DROP SCHEMA _scope_test');
    }

    /**
     * Tests that column types without default lengths in $columns do not have length values
     * applied when generating schemas.
     */
    public function testColumnUseLength()
    {
        $result = ['name' => 'foo', 'type' => 'string', 'length' => 100, 'default' => 'FOO'];
        $expected = '"foo" varchar(100) DEFAULT \'FOO\'';
        $this->assertEqual($this->db->buildColumn($result), $expected);

        $result = ['name' => 'foo', 'type' => 'text', 'length' => 100, 'default' => 'FOO'];
        $expected = '"foo" text DEFAULT \'FOO\'';
        $this->assertEqual($this->db->buildColumn($result), $expected);
    }

    /**
     * Tests that binary data is escaped/unescaped properly on reads and writes.
     */
    public function testBinaryDataIntegrity()
    {
        $data = '%PDF-1.3
		%ƒÂÚÂÎßÛ†–ƒ∆
		4 0 obj
		<< /Length 5 0 R /Filter /FlateDecode >>
		stream
		xµYMì€∆Ω„WÃ%)nï0¯îâ-«é]Q"πXµáÿ•Ip	-	P V,]Ú#c˚ˇ‰ut¥†∏Ti9 Ü=”›Ø_˜4>à∑‚Épcé¢Pxæ®2q\'
		1UªbUáˇ’+ö«√[ıµ⁄ão"R∑"HiGæä€(å≠≈^Ãøsm?YlƒÃõªﬁ‹âEÚB&‚Î◊7bÒ^¸m°÷˛?2±Øs“ﬁu#®U√ˇú÷g¥C;ä")n})JºIÔ3ËSnÑÎ¥≤ıD∆¢∂Msx1üèG˚±Œ™⁄>¶ySïufØ ˝¸?UπÃã√6ﬂÌÚC=øK?˝…s
		˛§¯ˇ:-˜ò7€ÓFæ∂∑Õ˛∆“V’>ılﬂëÅd«ÜQdI›ÎB%W¿ΩıÉn~hvêCS>«é˛(ØôK!€¡zB!√
		[œÜ"ûß ·iH¸[Ã€ºæ∑¯¡L,ÀÚAlS∫ˆ=∫Œ≤cÄr&ˆÈ:√ÿ£˚È«4ﬂ•À]vc›bÅôÿî=siXe4/¡p]ã]ôÆIœ™ Ωﬂà_ƒ‚G?«7	ùÿ ı¯K4ïIpV◊÷·\'éµóªÚæ>î
		;›sú!2ﬂ¬F•/f∑j£
		dw"IÊÜπ<ôÿˆ%IG1ytÛDﬂXg|Éòa§˜}C˛¿ÿe°G´Ú±jÍm~¿/∂hã<#-¥•ıùe87€t˜õ6w}´{æ
		m‹ê–	∆¡ 6⁄\
		rAÀBùZ3aË‚r$G·$ó0ÑüâUY4È™¡%C∑Ÿ2rc<Iõ-cï.
		[ŒöâFA†É‡+QglMÉîÉÄúÌ|¸»#x7¥«MgVÎ-GGÚ• I?Á‘”Lzw∞pHÅ¯◊nefqCî.nÕeè∆ÿÛy¡˙fb≤üŒHÜAëÕNq=´@	’cQdÖúAÉIqñŸ˘+2&∏  Àù.gÅ‚ƒœ3EPƒOi—‰:>ÍCäı
		=Õec=ëR˝”eñ=<V$ì˙+x+¢ïÒÕ<àeWå»–˚∫Õd§&£àf ]fPA´âtënöå∏◊ó„Ë@∆≠K´÷˘}a_CI˚©yòHg,ôSSVìBƒl4 L.ÈY…á,2∂íäÙ.$ó¸CäŸ*€óy
		π?G,_√·ÆÎç=^Vkvo±ó{§ƒ2»±¨Ïüo»ëD-ãé ﬁó¥cVÙ\'™G~\'p¢%* ã˚÷
		ªºnh˚ºO^∏…®[Ó“‚ÅfıÌ≥∫F!Eœ(π∑T6`¬tΩÆ0ì»rTÎ`»Ñ«
		]≈åp˝)=¿Ô0∆öVÂmˇˆ„ø~¯ÁÔ∏b*fc»‡Îı„Ú}∆tœs∂Y∫ÜaÆ˙X∏~<ÿ·Ùvé1‹p¿TD∆ÔîÄ“úhˆ*Ú€îe)K–p¨ÚJ3Ÿ∞ã>ÊuNê°“√Ü ‹Ê9iÙ0˙AAEÍ ˙`∂£\'ûce•åƒX›ŸÁ´1SK{qdá"tÏ[wQ#SµBe∞∑µó…ÌV`B"Ñ≥„!è_ÓÏ†-º*ºú¿Ë0ˆeê∂´ë+HFj…‡zvHÓN|ÔL÷ûñ3õÜ$z%sá…pÎóV38âs	Çoµ•ß3†<9B·¨û~¢3)ÂxóÿÁCÕòÆ∫Í=»ÿSπS;∆~±êÆTEp∑óÈ÷ÀuìDHÈ$ÉõæÜjÃ»§"≤ÃONM®RËíRr{õS	∏Ê™op±W;ÂUÔ P∫kÔˇﬂTæ∑óﬂË”ÆC©Ô[≥◊HÁ˚¨hê"ÆbF?ú%h˙ˇ4xèÕ(ó2ÙáíM])Ñd|=fë-cI0ñL¢kÖêk‰Rƒ«ıÄWñ8mO3∏&√æËX¯Hó—ì]yF2»–˜ádàà‡‹ÇÎ¿„≥7mªHAS∑¶.;Œx(1} _kd©.ﬁdç48M\'àáªCp^Krí<É‰XÓıïl!Ì$N<ı∞B»G]…∂Ó¯>˛ÔbõÒπÀ•:ôO<j∂™œ%âÏ—>@È$pÖu‹Ê´-QqV ?V≥JÆÍqÛX8(lπï@zgÖ}Fe<ˇ‡Sñ“ÿ˜ê?6‡L∫Oß~µ –?ËeäÚ®YîÕ=Ü=¢DÁu*GvBk;)L¬N«î:flö∂≠ÇΩq„Ñmí•˜Ë∂‚"û≥§:±≤i^ΩÑ!)WıyÅ§ô á„RÄ÷Òôc’≠—s™rı‚Pdêãh˘ßHVç5ﬁﬁÈF€çÌÛuçÖ/M=gëµ±ÿGû1coÔuñæ‘z®. õ∑7ÉÏÜÆ,°’H†ÍÉÌ∂7e	º® íˆ⁄◊øNWK”ÂYµ‚ñé;µ¶gV-ﬂ>µtË¥áßN2 ¯¶BaP-)eW.àôt^∏1›C∑Ö?L„&”5’4jvã–ªZ	÷+4% ´0l…»ú^°´© ûiπ∑é®óÜ±Òÿ‰ïˆÌ–dˆ◊Æ19rQ=Í|ı•rMæ¬;ò‰Y‰é9.”‹˝V«ã¯∏,+ë®j*¡·/';

        $model = new AppModel(['name' => 'BinaryTest', 'ds' => 'test_suite']);
        $model->save(compact('data'));

        $result = $model->find('first');
        $this->assertEqual($result['BinaryTest']['data'], $data);
    }

    /**
     * Tests the syntax of generated schema indexes.
     */
    public function testSchemaIndexSyntax()
    {
        $schema = new CakeSchema();
        $schema->tables = ['i18n' => [
            'id' => [
                'type' => 'integer', 'null' => false, 'default' => null,
                'length' => 10, 'key' => 'primary',
            ],
            'locale' => ['type' => 'string', 'null' => false, 'length' => 6, 'key' => 'index'],
            'model' => ['type' => 'string', 'null' => false, 'key' => 'index'],
            'foreign_key' => [
                'type' => 'integer', 'null' => false, 'length' => 10, 'key' => 'index',
            ],
            'field' => ['type' => 'string', 'null' => false, 'key' => 'index'],
            'content' => ['type' => 'text', 'null' => true, 'default' => null],
            'indexes' => [
                'PRIMARY' => ['column' => 'id', 'unique' => 1],
                'locale' => ['column' => 'locale', 'unique' => 0],
                'model' => ['column' => 'model', 'unique' => 0],
                'row_id' => ['column' => 'foreign_key', 'unique' => 0],
                'field' => ['column' => 'field', 'unique' => 0],
            ],
        ]];

        $result = $this->db->createSchema($schema);
        $this->assertNoPattern('/^CREATE INDEX(.+);,$/', $result);
    }

    /**
     * testCakeSchema method.
     *
     * Test that schema generated postgresql queries are valid. ref #5696
     * Check that the create statement for a schema generated table is the same as the original sql
     */
    public function testCakeSchema()
    {
        $db1 = &ConnectionManager::getDataSource('test_suite');
        $db1->cacheSources = false;
        $db1->reconnect(['persistent' => false]);
        $db1->query('CREATE TABLE '.$db1->fullTableName('datatype_tests').' (
			id serial NOT NULL,
			"varchar" character varying(40) NOT NULL,
			"full_length" character varying NOT NULL,
			"timestamp" timestamp without time zone,
			date date,
			CONSTRAINT test_suite_data_types_pkey PRIMARY KEY (id)
		)');
        $model = new Model(['name' => 'DatatypeTest', 'ds' => 'test_suite']);
        $schema = new CakeSchema(['connection' => 'test_suite']);
        $result = $schema->read([
            'connection' => 'test_suite',
        ]);
        $schema->tables = ['datatype_tests' => $result['tables']['missing']['datatype_tests']];
        $result = $db1->createSchema($schema, 'datatype_tests');

        $this->assertNoPattern('/timestamp DEFAULT/', $result);
        $this->assertPattern('/\"full_length\"\s*text\s.*,/', $result);
        $this->assertPattern('/timestamp\s*,/', $result);

        $db1->query('DROP TABLE '.$db1->fullTableName('datatype_tests'));

        $db1->query($result);
        $result2 = $schema->read([
            'connection' => 'test_suite',
            'models' => ['DatatypeTest'],
        ]);
        $schema->tables = ['datatype_tests' => $result2['tables']['missing']['datatype_tests']];
        $result2 = $db1->createSchema($schema, 'datatype_tests');
        $this->assertEqual($result, $result2);

        $db1->query('DROP TABLE '.$db1->fullTableName('datatype_tests'));
    }

    /**
     * Test index generation from table info.
     */
    public function testIndexGeneration()
    {
        $name = $this->db->fullTableName('index_test', false);
        $this->db->query('CREATE TABLE '.$name.' ("id" serial NOT NULL PRIMARY KEY, "bool" integer, "small_char" varchar(50), "description" varchar(40) )');
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
        $name = $this->db->fullTableName('index_test_2', false);
        $this->db->query('CREATE TABLE '.$name.' ("id" serial NOT NULL PRIMARY KEY, "bool" integer, "small_char" varchar(50), "description" varchar(40) )');
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
     * Test the alterSchema capabilities of postgres.
     */
    public function testAlterSchema()
    {
        $Old = new CakeSchema([
            'connection' => 'test_suite',
            'name' => 'AlterPosts',
            'alter_posts' => [
                'id' => ['type' => 'integer', 'key' => 'primary'],
                'author_id' => ['type' => 'integer', 'null' => false],
                'title' => ['type' => 'string', 'null' => true],
                'body' => ['type' => 'text'],
                'published' => ['type' => 'string', 'length' => 1, 'default' => 'N'],
                'created' => ['type' => 'datetime'],
                'updated' => ['type' => 'datetime'],
            ],
        ]);
        $this->db->query($this->db->createSchema($Old));

        $New = new CakeSchema([
            'connection' => 'test_suite',
            'name' => 'AlterPosts',
            'alter_posts' => [
                'id' => ['type' => 'integer', 'key' => 'primary'],
                'author_id' => ['type' => 'integer', 'null' => true],
                'title' => ['type' => 'string', 'null' => false, 'default' => 'my title'],
                'body' => ['type' => 'string', 'length' => 500],
                'status' => ['type' => 'integer', 'length' => 3, 'default' => 1],
                'created' => ['type' => 'datetime'],
                'updated' => ['type' => 'datetime'],
            ],
        ]);
        $this->db->query($this->db->alterSchema($New->compare($Old), 'alter_posts'));

        $model = new CakeTestModel(['table' => 'alter_posts', 'ds' => 'test_suite']);
        $result = $model->schema();
        $this->assertTrue(isset($result['status']));
        $this->assertFalse(isset($result['published']));
        $this->assertEqual($result['body']['type'], 'string');
        $this->assertEqual($result['status']['default'], 1);
        $this->assertEqual($result['author_id']['null'], true);
        $this->assertEqual($result['title']['null'], false);

        $this->db->query($this->db->dropSchema($New));

        $New = new CakeSchema([
            'connection' => 'test_suite',
            'name' => 'AlterPosts',
            'alter_posts' => [
                'id' => ['type' => 'string', 'length' => 36, 'key' => 'primary'],
                'author_id' => ['type' => 'integer', 'null' => false],
                'title' => ['type' => 'string', 'null' => true],
                'body' => ['type' => 'text'],
                'published' => ['type' => 'string', 'length' => 1, 'default' => 'N'],
                'created' => ['type' => 'datetime'],
                'updated' => ['type' => 'datetime'],
            ],
        ]);
        $result = $this->db->alterSchema($New->compare($Old), 'alter_posts');
        $this->assertNoPattern('/varchar\(36\) NOT NULL/i', $result);
    }

    /**
     * Test the alter index capabilities of postgres.
     */
    public function testAlterIndexes()
    {
        $this->db->cacheSources = false;

        $schema1 = new CakeSchema([
            'name' => 'AlterTest1',
            'connection' => 'test_suite',
            'altertest' => [
                'id' => ['type' => 'integer', 'null' => false, 'default' => 0],
                'name' => ['type' => 'string', 'null' => false, 'length' => 50],
                'group1' => ['type' => 'integer', 'null' => true],
                'group2' => ['type' => 'integer', 'null' => true],
            ],
        ]);
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
                    'PRIMARY' => ['column' => 'id', 'unique' => 1],
                ],
            ],
        ]);
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
                    'another_idx' => ['column' => ['group1', 'name'], 'unique' => 0], ],
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

    /*
     * Test it is possible to use virtual field with postgresql
     *
     * @access public
     * @return void
     */
    public function testVirtualFields()
    {
        $this->loadFixtures('Article', 'Comment');
        $Article = new Article();
        $Article->virtualFields = [
            'next_id' => 'Article.id + 1',
            'complex' => 'Article.title || Article.body',
            'functional' => 'COALESCE(User.user, Article.title)',
            'subquery' => 'SELECT count(*) FROM '.$Article->Comment->table,
        ];
        $result = $Article->find('first');
        $this->assertEqual($result['Article']['next_id'], 2);
        $this->assertEqual($result['Article']['complex'], $result['Article']['title'].$result['Article']['body']);
        $this->assertEqual($result['Article']['functional'], $result['Article']['title']);
        $this->assertEqual($result['Article']['subquery'], 6);
    }

    /**
     * Test that virtual fields work with SQL constants.
     */
    public function testVirtualFieldAsAConstant()
    {
        $this->loadFixtures('Article', 'Comment');
        $Article = &ClassRegistry::init('Article');
        $Article->virtualFields = [
            'empty' => 'NULL',
            'number' => 43,
            'truth' => 'TRUE',
        ];
        $result = $Article->find('first');
        $this->assertNull($result['Article']['empty']);
        $this->assertTrue($result['Article']['truth']);
        $this->assertIdentical('43', $result['Article']['number']);
    }

    /**
     * Tests additional order options for postgres.
     */
    public function testOrderAdditionalParams()
    {
        $result = $this->db->order(['title' => 'DESC NULLS FIRST', 'body' => 'DESC']);
        $expected = ' ORDER BY "title" DESC NULLS FIRST, "body" DESC';
        $this->assertEqual($result, $expected);
    }

    /**
     * Test it is possible to do a SELECT COUNT(DISTINCT Model.field) query in postgres and it gets correctly quoted.
     */
    public function testQuoteDistinctInFunction()
    {
        $this->loadFixtures('Article');
        $Article = new Article();
        $result = $this->db->fields($Article, null, ['COUNT(DISTINCT Article.id)']);
        $expected = ['COUNT(DISTINCT "Article"."id")'];
        $this->assertEqual($result, $expected);

        $result = $this->db->fields($Article, null, ['COUNT(DISTINCT id)']);
        $expected = ['COUNT(DISTINCT "id")'];
        $this->assertEqual($result, $expected);

        $result = $this->db->fields($Article, null, ['COUNT(DISTINCT FUNC(id))']);
        $expected = ['COUNT(DISTINCT FUNC("id"))'];
        $this->assertEqual($result, $expected);
    }

    /**
     * test that saveAll works even with conditions that lack a model name.
     */
    public function testUpdateAllWithNonQualifiedConditions()
    {
        $this->loadFixtures('Article');
        $Article = new Article();
        $result = $Article->updateAll(['title' => "'Awesome'"], ['title' => 'Third Article']);
        $this->assertTrue($result);

        $result = $Article->find('count', [
            'conditions' => ['Article.title' => 'Awesome'],
        ]);
        $this->assertEqual($result, 1, 'Article count is wrong or fixture has changed.');
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
        $this->assertFalse(strpos(';ALTER', $result), 'Too many semi colons');
    }
}
