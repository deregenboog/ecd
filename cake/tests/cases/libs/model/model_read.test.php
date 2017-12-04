<?php
/**
 * ModelReadTest file.
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
require_once dirname(__FILE__).DS.'model.test.php';
/**
 * ModelReadTest.
 */
class ModelReadTest extends BaseModelTest
{
    /**
     * testFetchingNonUniqueFKJoinTableRecords().
     *
     * Tests if the results are properly returned in the case there are non-unique FK's
     * in the join table but another fields value is different. For example:
     * something_id | something_else_id | doomed = 1
     * something_id | something_else_id | doomed = 0
     * Should return both records and not just one.
     */
    public function testFetchingNonUniqueFKJoinTableRecords()
    {
        $this->loadFixtures('Something', 'SomethingElse', 'JoinThing');
        $Something = new Something();

        $joinThingData = [
            'JoinThing' => [
                'something_id' => 1,
                'something_else_id' => 2,
                'doomed' => '0',
                'created' => '2007-03-18 10:39:23',
                'updated' => '2007-03-18 10:41:31',
            ],
        ];

        $Something->JoinThing->create($joinThingData);
        $Something->JoinThing->save();

        $result = $Something->JoinThing->find('all', ['conditions' => ['something_else_id' => 2]]);
        $this->assertEqual($result[0]['JoinThing']['doomed'], true);
        $this->assertEqual($result[1]['JoinThing']['doomed'], false);

        $result = $Something->find('first');
        $this->assertEqual(count($result['SomethingElse']), 2);

        $doomed = Set::extract('/JoinThing/doomed', $result['SomethingElse']);
        $this->assertTrue(in_array(true, $doomed));
        $this->assertTrue(in_array(false, $doomed));
    }

    /**
     * testGroupBy method.
     *
     * These tests will never pass with Postgres or Oracle as all fields in a select must be
     * part of an aggregate function or in the GROUP BY statement.
     */
    public function testGroupBy()
    {
        $db = ConnectionManager::getDataSource('test_suite');
        $isStrictGroupBy = in_array($db->config['driver'], ['postgres', 'oracle']);
        $message = '%s Postgres and Oracle have strict GROUP BY and are incompatible with this test.';

        if ($this->skipIf($isStrictGroupBy, $message)) {
            return;
        }

        $this->loadFixtures('Project', 'Product', 'Thread', 'Message', 'Bid');
        $Thread = new Thread();
        $Product = new Product();

        $result = $Thread->find('all', [
            'group' => 'Thread.project_id',
            'order' => 'Thread.id ASC',
        ]);

        $expected = [
            [
                'Thread' => [
                    'id' => 1,
                    'project_id' => 1,
                    'name' => 'Project 1, Thread 1',
                ],
                'Project' => [
                    'id' => 1,
                    'name' => 'Project 1',
                ],
                'Message' => [
                    [
                        'id' => 1,
                        'thread_id' => 1,
                        'name' => 'Thread 1, Message 1',
            ], ], ],
            [
                'Thread' => [
                    'id' => 3,
                    'project_id' => 2,
                    'name' => 'Project 2, Thread 1',
                ],
                'Project' => [
                    'id' => 2,
                    'name' => 'Project 2',
                ],
                'Message' => [
                    [
                        'id' => 3,
                        'thread_id' => 3,
                        'name' => 'Thread 3, Message 1',
        ], ], ], ];
        $this->assertEqual($result, $expected);

        $rows = $Thread->find('all', [
            'group' => 'Thread.project_id',
            'fields' => ['Thread.project_id', 'COUNT(*) AS total'],
        ]);
        $result = [];
        foreach ($rows as $row) {
            $result[$row['Thread']['project_id']] = $row[0]['total'];
        }
        $expected = [
            1 => 2,
            2 => 1,
        ];
        $this->assertEqual($result, $expected);

        $rows = $Thread->find('all', [
            'group' => 'Thread.project_id',
            'fields' => ['Thread.project_id', 'COUNT(*) AS total'],
            'order' => 'Thread.project_id',
        ]);
        $result = [];
        foreach ($rows as $row) {
            $result[$row['Thread']['project_id']] = $row[0]['total'];
        }
        $expected = [
            1 => 2,
            2 => 1,
        ];
        $this->assertEqual($result, $expected);

        $result = $Thread->find('all', [
            'conditions' => ['Thread.project_id' => 1],
            'group' => 'Thread.project_id',
        ]);
        $expected = [
            [
                'Thread' => [
                    'id' => 1,
                    'project_id' => 1,
                    'name' => 'Project 1, Thread 1',
                ],
                'Project' => [
                    'id' => 1,
                    'name' => 'Project 1',
                ],
                'Message' => [
                    [
                        'id' => 1,
                        'thread_id' => 1,
                        'name' => 'Thread 1, Message 1',
        ], ], ], ];
        $this->assertEqual($result, $expected);

        $result = $Thread->find('all', [
            'conditions' => ['Thread.project_id' => 1],
            'group' => 'Thread.project_id, Project.id',
        ]);
        $this->assertEqual($result, $expected);

        $result = $Thread->find('all', [
            'conditions' => ['Thread.project_id' => 1],
            'group' => 'project_id',
        ]);
        $this->assertEqual($result, $expected);

        $result = $Thread->find('all', [
            'conditions' => ['Thread.project_id' => 1],
            'group' => ['project_id'],
        ]);
        $this->assertEqual($result, $expected);

        $result = $Thread->find('all', [
            'conditions' => ['Thread.project_id' => 1],
            'group' => ['project_id', 'Project.id'],
        ]);
        $this->assertEqual($result, $expected);

        $result = $Thread->find('all', [
            'conditions' => ['Thread.project_id' => 1],
            'group' => ['Thread.project_id', 'Project.id'],
        ]);
        $this->assertEqual($result, $expected);

        $expected = [
            ['Product' => ['type' => 'Clothing'], ['price' => 32]],
            ['Product' => ['type' => 'Food'], ['price' => 9]],
            ['Product' => ['type' => 'Music'], ['price' => 4]],
            ['Product' => ['type' => 'Toy'], ['price' => 3]],
        ];
        $result = $Product->find('all', [
            'fields' => ['Product.type', 'MIN(Product.price) as price'],
            'group' => 'Product.type',
            'order' => 'Product.type ASC',
            ]);
        $this->assertEqual($result, $expected);

        $result = $Product->find('all', [
            'fields' => ['Product.type', 'MIN(Product.price) as price'],
            'group' => ['Product.type'],
            'order' => 'Product.type ASC', ]);
        $this->assertEqual($result, $expected);
    }

    /**
     * testOldQuery method.
     */
    public function testOldQuery()
    {
        $this->loadFixtures('Article');
        $Article = new Article();

        $query = 'SELECT title FROM ';
        $query .= $this->db->fullTableName('articles');
        $query .= ' WHERE '.$this->db->fullTableName('articles').'.id IN (1,2)';

        $results = $Article->query($query);
        $this->assertTrue(is_array($results));
        $this->assertEqual(count($results), 2);

        $query = 'SELECT title, body FROM ';
        $query .= $this->db->fullTableName('articles');
        $query .= ' WHERE '.$this->db->fullTableName('articles').'.id = 1';

        $results = $Article->query($query, false);
        $this->assertTrue(!isset($this->db->_queryCache[$query]));
        $this->assertTrue(is_array($results));

        $query = 'SELECT title, id FROM ';
        $query .= $this->db->fullTableName('articles');
        $query .= ' WHERE '.$this->db->fullTableName('articles');
        $query .= '.published = '.$this->db->value('Y');

        $results = $Article->query($query, true);
        $this->assertTrue(isset($this->db->_queryCache[$query]));
        $this->assertTrue(is_array($results));
    }

    /**
     * testPreparedQuery method.
     */
    public function testPreparedQuery()
    {
        $this->loadFixtures('Article');
        $Article = new Article();
        $this->db->_queryCache = [];

        $finalQuery = 'SELECT title, published FROM ';
        $finalQuery .= $this->db->fullTableName('articles');
        $finalQuery .= ' WHERE '.$this->db->fullTableName('articles');
        $finalQuery .= '.id = '.$this->db->value(1);
        $finalQuery .= ' AND '.$this->db->fullTableName('articles');
        $finalQuery .= '.published = '.$this->db->value('Y');

        $query = 'SELECT title, published FROM ';
        $query .= $this->db->fullTableName('articles');
        $query .= ' WHERE '.$this->db->fullTableName('articles');
        $query .= '.id = ? AND '.$this->db->fullTableName('articles').'.published = ?';

        $params = [1, 'Y'];
        $result = $Article->query($query, $params);
        $expected = [
            '0' => [
                $this->db->fullTableName('articles', false) => [
                    'title' => 'First Article', 'published' => 'Y', ],
        ], ];

        if (isset($result[0][0])) {
            $expected[0][0] = $expected[0][$this->db->fullTableName('articles', false)];
            unset($expected[0][$this->db->fullTableName('articles', false)]);
        }

        $this->assertEqual($result, $expected);
        $this->assertTrue(isset($this->db->_queryCache[$finalQuery]));

        $finalQuery = 'SELECT id, created FROM ';
        $finalQuery .= $this->db->fullTableName('articles');
        $finalQuery .= ' WHERE '.$this->db->fullTableName('articles');
        $finalQuery .= '.title = '.$this->db->value('First Article');

        $query = 'SELECT id, created FROM ';
        $query .= $this->db->fullTableName('articles');
        $query .= '  WHERE '.$this->db->fullTableName('articles').'.title = ?';

        $params = ['First Article'];
        $result = $Article->query($query, $params, false);
        $this->assertTrue(is_array($result));
        $this->assertTrue(
               isset($result[0][$this->db->fullTableName('articles', false)])
            || isset($result[0][0])
        );
        $this->assertFalse(isset($this->db->_queryCache[$finalQuery]));

        $query = 'SELECT title FROM ';
        $query .= $this->db->fullTableName('articles');
        $query .= ' WHERE '.$this->db->fullTableName('articles').'.title LIKE ?';

        $params = ['%First%'];
        $result = $Article->query($query, $params);
        $this->assertTrue(is_array($result));
        $this->assertTrue(
               isset($result[0][$this->db->fullTableName('articles', false)]['title'])
            || isset($result[0][0]['title'])
        );

        //related to ticket #5035
        $query = 'SELECT title FROM ';
        $query .= $this->db->fullTableName('articles').' WHERE title = ? AND published = ?';
        $params = ['First? Article', 'Y'];
        $Article->query($query, $params);

        $expected = 'SELECT title FROM ';
        $expected .= $this->db->fullTableName('articles');
        $expected .= " WHERE title = 'First? Article' AND published = 'Y'";
        $this->assertTrue(isset($this->db->_queryCache[$expected]));
    }

    /**
     * testParameterMismatch method.
     */
    public function testParameterMismatch()
    {
        $this->loadFixtures('Article');
        $Article = new Article();

        $query = 'SELECT * FROM '.$this->db->fullTableName('articles');
        $query .= ' WHERE '.$this->db->fullTableName('articles');
        $query .= '.published = ? AND '.$this->db->fullTableName('articles').'.user_id = ?';
        $params = ['Y'];
        $this->expectError();

        ob_start();
        $result = $Article->query($query, $params);
        ob_end_clean();
        $this->assertEqual($result, null);
    }

    /**
     * testVeryStrangeUseCase method.
     */
    public function testVeryStrangeUseCase()
    {
        $message = '%s skipping SELECT * FROM ? WHERE ? = ? AND ? = ?; prepared query.';
        $message .= ' MSSQL is incompatible with this test.';

        if ($this->skipIf('mssql' == $this->db->config['driver'], $message)) {
            return;
        }

        $this->loadFixtures('Article');
        $Article = new Article();

        $query = 'SELECT * FROM ? WHERE ? = ? AND ? = ?';
        $param = [
            $this->db->fullTableName('articles'),
            $this->db->fullTableName('articles').'.user_id', '3',
            $this->db->fullTableName('articles').'.published', 'Y',
        ];
        $this->expectError();

        ob_start();
        $result = $Article->query($query, $param);
        ob_end_clean();
    }

    /**
     * testRecursiveUnbind method.
     */
    public function testRecursiveUnbind()
    {
        $this->loadFixtures('Apple', 'Sample');
        $TestModel = new Apple();
        $TestModel->recursive = 2;

        $result = $TestModel->find('all');
        $expected = [
            [
                'Apple' => [
                    'id' => 1,
                    'apple_id' => 2,
                    'color' => 'Red 1',
                    'name' => 'Red Apple 1',
                    'created' => '2006-11-22 10:38:58',
                    'date' => '1951-01-04',
                    'modified' => '2006-12-01 13:31:26',
                    'mytime' => '22:57:17',
                ],
                'Parent' => [
                    'id' => 2,
                    'apple_id' => 1,
                    'color' => 'Bright Red 1',
                    'name' => 'Bright Red Apple',
                    'created' => '2006-11-22 10:43:13',
                    'date' => '2014-01-01',
                    'modified' => '2006-11-30 18:38:10',
                    'mytime' => '22:57:17',
                    'Parent' => [
                        'id' => 1,
                        'apple_id' => 2,
                        'color' => 'Red 1',
                        'name' => 'Red Apple 1',
                        'created' => '2006-11-22 10:38:58',
                        'date' => '1951-01-04',
                        'modified' => '2006-12-01 13:31:26',
                        'mytime' => '22:57:17',
                    ],
                    'Sample' => [
                        'id' => 2,
                        'apple_id' => 2,
                        'name' => 'sample2',
                    ],
                    'Child' => [
                        [
                            'id' => 1,
                            'apple_id' => 2,
                            'color' => 'Red 1',
                            'name' => 'Red Apple 1',
                            'created' => '2006-11-22 10:38:58',
                            'date' => '1951-01-04',
                            'modified' => '2006-12-01 13:31:26',
                            'mytime' => '22:57:17',
                        ],
                        [
                            'id' => 3,
                            'apple_id' => 2,
                            'color' => 'blue green',
                            'name' => 'green blue',
                            'created' => '2006-12-25 05:13:36',
                            'date' => '2006-12-25',
                            'modified' => '2006-12-25 05:23:24',
                            'mytime' => '22:57:17',
                        ],
                        [
                            'id' => 4,
                            'apple_id' => 2,
                            'color' => 'Blue Green',
                            'name' => 'Test Name',
                            'created' => '2006-12-25 05:23:36',
                            'date' => '2006-12-25',
                            'modified' => '2006-12-25 05:23:36',
                            'mytime' => '22:57:17',
                    ], ], ],
                    'Sample' => [
                        'id' => '',
                        'apple_id' => '',
                        'name' => '',
                    ],
                    'Child' => [
                        [
                            'id' => 2,
                            'apple_id' => 1,
                            'color' => 'Bright Red 1',
                            'name' => 'Bright Red Apple',
                            'created' => '2006-11-22 10:43:13',
                            'date' => '2014-01-01',
                            'modified' => '2006-11-30 18:38:10',
                            'mytime' => '22:57:17',
                            'Parent' => [
                                'id' => 1,
                                'apple_id' => 2,
                                'color' => 'Red 1',
                                'name' => 'Red Apple 1',
                                'created' => '2006-11-22 10:38:58',
                                'date' => '1951-01-04',
                                'modified' => '2006-12-01 13:31:26',
                                'mytime' => '22:57:17',
                            ],
                            'Sample' => [
                                'id' => 2,
                                'apple_id' => 2,
                                'name' => 'sample2',
                            ],
                            'Child' => [
                                [
                                    'id' => 1,
                                    'apple_id' => 2,
                                    'color' => 'Red 1',
                                    'name' => 'Red Apple 1',
                                    'created' => '2006-11-22 10:38:58',
                                    'date' => '1951-01-04',
                                    'modified' => '2006-12-01 13:31:26',
                                    'mytime' => '22:57:17',
                                ],
                                [
                                    'id' => 3,
                                    'apple_id' => 2,
                                    'color' => 'blue green',
                                    'name' => 'green blue',
                                    'created' => '2006-12-25 05:13:36',
                                    'date' => '2006-12-25',
                                    'modified' => '2006-12-25 05:23:24',
                                    'mytime' => '22:57:17',
                                ],
                                [
                                    'id' => 4,
                                    'apple_id' => 2,
                                    'color' => 'Blue Green',
                                    'name' => 'Test Name',
                                    'created' => '2006-12-25 05:23:36',
                                    'date' => '2006-12-25',
                                    'modified' => '2006-12-25 05:23:36',
                                    'mytime' => '22:57:17',
            ], ], ], ], ],
            [
                'Apple' => [
                    'id' => 2,
                    'apple_id' => 1,
                    'color' => 'Bright Red 1',
                    'name' => 'Bright Red Apple',
                    'created' => '2006-11-22 10:43:13',
                    'date' => '2014-01-01',
                    'modified' => '2006-11-30 18:38:10',
                    'mytime' => '22:57:17',
                ],
                'Parent' => [
                        'id' => 1,
                        'apple_id' => 2,
                        'color' => 'Red 1',
                        'name' => 'Red Apple 1',
                        'created' => '2006-11-22 10:38:58',
                        'date' => '1951-01-04',
                        'modified' => '2006-12-01 13:31:26',
                        'mytime' => '22:57:17',
                        'Parent' => [
                            'id' => 2,
                            'apple_id' => 1,
                            'color' => 'Bright Red 1',
                            'name' => 'Bright Red Apple',
                            'created' => '2006-11-22 10:43:13',
                            'date' => '2014-01-01',
                            'modified' => '2006-11-30 18:38:10',
                            'mytime' => '22:57:17',
                        ],
                        'Sample' => [],
                        'Child' => [
                            [
                                'id' => 2,
                                'apple_id' => 1,
                                'color' => 'Bright Red 1',
                                'name' => 'Bright Red Apple',
                                'created' => '2006-11-22 10:43:13',
                                'date' => '2014-01-01',
                                'modified' => '2006-11-30 18:38:10',
                                'mytime' => '22:57:17',
                    ], ], ],
                    'Sample' => [
                        'id' => 2,
                        'apple_id' => 2,
                        'name' => 'sample2',
                        'Apple' => [
                            'id' => 2,
                            'apple_id' => 1,
                            'color' => 'Bright Red 1',
                            'name' => 'Bright Red Apple',
                            'created' => '2006-11-22 10:43:13',
                            'date' => '2014-01-01',
                            'modified' => '2006-11-30 18:38:10',
                            'mytime' => '22:57:17',
                    ], ],
                    'Child' => [
                        [
                            'id' => 1,
                            'apple_id' => 2,
                            'color' => 'Red 1',
                            'name' => 'Red Apple 1',
                            'created' => '2006-11-22 10:38:58',
                            'date' => '1951-01-04',
                            'modified' => '2006-12-01 13:31:26',
                            'mytime' => '22:57:17',
                            'Parent' => [
                                'id' => 2,
                                'apple_id' => 1,
                                'color' => 'Bright Red 1',
                                'name' => 'Bright Red Apple',
                                'created' => '2006-11-22 10:43:13',
                                'date' => '2014-01-01',
                                'modified' => '2006-11-30 18:38:10',
                                'mytime' => '22:57:17',
                            ],
                            'Sample' => [],
                            'Child' => [
                                [
                                    'id' => 2,
                                    'apple_id' => 1,
                                    'color' => 'Bright Red 1',
                                    'name' => 'Bright Red Apple',
                                    'created' => '2006-11-22 10:43:13',
                                    'date' => '2014-01-01',
                                    'modified' => '2006-11-30 18:38:10',
                                    'mytime' => '22:57:17',
                        ], ], ],
                        [
                            'id' => 3,
                            'apple_id' => 2,
                            'color' => 'blue green',
                            'name' => 'green blue',
                            'created' => '2006-12-25 05:13:36',
                            'date' => '2006-12-25',
                            'modified' => '2006-12-25 05:23:24',
                            'mytime' => '22:57:17',
                            'Parent' => [
                                'id' => 2,
                                'apple_id' => 1,
                                'color' => 'Bright Red 1',
                                'name' => 'Bright Red Apple',
                                'created' => '2006-11-22 10:43:13',
                                'date' => '2014-01-01',
                                'modified' => '2006-11-30 18:38:10',
                                'mytime' => '22:57:17',
                            ],
                            'Sample' => [
                                'id' => 1,
                                'apple_id' => 3,
                                'name' => 'sample1',
                        ], ],
                        [
                            'id' => 4,
                            'apple_id' => 2,
                            'color' => 'Blue Green',
                            'name' => 'Test Name',
                            'created' => '2006-12-25 05:23:36',
                            'date' => '2006-12-25',
                            'modified' => '2006-12-25 05:23:36',
                            'mytime' => '22:57:17',
                            'Parent' => [
                                'id' => 2,
                                'apple_id' => 1,
                                'color' => 'Bright Red 1',
                                'name' => 'Bright Red Apple',
                                'created' => '2006-11-22 10:43:13',
                                'date' => '2014-01-01',
                                'modified' => '2006-11-30 18:38:10',
                                'mytime' => '22:57:17',
                            ],
                            'Sample' => [
                                'id' => 3,
                                'apple_id' => 4,
                                'name' => 'sample3',
                            ],
                            'Child' => [
                                [
                                    'id' => 6,
                                    'apple_id' => 4,
                                    'color' => 'My new appleOrange',
                                    'name' => 'My new apple',
                                    'created' => '2006-12-25 05:29:39',
                                    'date' => '2006-12-25',
                                    'modified' => '2006-12-25 05:29:39',
                                    'mytime' => '22:57:17',
            ], ], ], ], ],
            [
                'Apple' => [
                    'id' => 3,
                    'apple_id' => 2,
                    'color' => 'blue green',
                    'name' => 'green blue',
                    'created' => '2006-12-25 05:13:36',
                    'date' => '2006-12-25',
                    'modified' => '2006-12-25 05:23:24',
                    'mytime' => '22:57:17',
                ],
                'Parent' => [
                    'id' => 2,
                    'apple_id' => 1,
                    'color' => 'Bright Red 1',
                    'name' => 'Bright Red Apple',
                    'created' => '2006-11-22 10:43:13',
                    'date' => '2014-01-01',
                    'modified' => '2006-11-30 18:38:10',
                    'mytime' => '22:57:17',
                    'Parent' => [
                        'id' => 1,
                        'apple_id' => 2,
                        'color' => 'Red 1',
                        'name' => 'Red Apple 1',
                        'created' => '2006-11-22 10:38:58',
                        'date' => '1951-01-04',
                        'modified' => '2006-12-01 13:31:26',
                        'mytime' => '22:57:17',
                    ],
                    'Sample' => [
                        'id' => 2,
                        'apple_id' => 2,
                        'name' => 'sample2',
                    ],
                    'Child' => [
                        [
                            'id' => 1,
                            'apple_id' => 2,
                            'color' => 'Red 1',
                            'name' => 'Red Apple 1',
                            'created' => '2006-11-22 10:38:58',
                            'date' => '1951-01-04',
                            'modified' => '2006-12-01 13:31:26',
                            'mytime' => '22:57:17',
                        ],
                        [
                            'id' => 3,
                            'apple_id' => 2,
                            'color' => 'blue green',
                            'name' => 'green blue',
                            'created' => '2006-12-25 05:13:36',
                            'date' => '2006-12-25',
                            'modified' => '2006-12-25 05:23:24',
                            'mytime' => '22:57:17',
                        ],
                        [
                            'id' => 4,
                            'apple_id' => 2,
                            'color' => 'Blue Green',
                            'name' => 'Test Name',
                            'created' => '2006-12-25 05:23:36',
                            'date' => '2006-12-25',
                            'modified' => '2006-12-25 05:23:36',
                            'mytime' => '22:57:17',
                ], ], ],
                'Sample' => [
                    'id' => 1,
                    'apple_id' => 3,
                    'name' => 'sample1',
                    'Apple' => [
                        'id' => 3,
                        'apple_id' => 2,
                        'color' => 'blue green',
                        'name' => 'green blue',
                        'created' => '2006-12-25 05:13:36',
                        'date' => '2006-12-25',
                        'modified' => '2006-12-25 05:23:24',
                        'mytime' => '22:57:17',
                ], ],
                'Child' => [],
            ],
            [
                'Apple' => [
                    'id' => 4,
                    'apple_id' => 2,
                    'color' => 'Blue Green',
                    'name' => 'Test Name',
                    'created' => '2006-12-25 05:23:36',
                    'date' => '2006-12-25',
                    'modified' => '2006-12-25 05:23:36',
                    'mytime' => '22:57:17',
                ],
                'Parent' => [
                    'id' => 2,
                    'apple_id' => 1,
                    'color' => 'Bright Red 1',
                    'name' => 'Bright Red Apple',
                    'created' => '2006-11-22 10:43:13',
                    'date' => '2014-01-01',
                    'modified' => '2006-11-30 18:38:10',
                    'mytime' => '22:57:17',
                    'Parent' => [
                        'id' => 1,
                        'apple_id' => 2,
                        'color' => 'Red 1',
                        'name' => 'Red Apple 1',
                        'created' => '2006-11-22 10:38:58',
                        'date' => '1951-01-04',
                        'modified' => '2006-12-01 13:31:26', 'mytime' => '22:57:17', ],
                        'Sample' => ['id' => 2, 'apple_id' => 2, 'name' => 'sample2'],
                        'Child' => [
                            [
                                'id' => 1,
                                'apple_id' => 2,
                                'color' => 'Red 1',
                                'name' => 'Red Apple 1',
                                'created' => '2006-11-22 10:38:58',
                                'date' => '1951-01-04',
                                'modified' => '2006-12-01 13:31:26',
                                'mytime' => '22:57:17',
                            ],
                            [
                                'id' => 3,
                                'apple_id' => 2,
                                'color' => 'blue green',
                                'name' => 'green blue',
                                'created' => '2006-12-25 05:13:36',
                                'date' => '2006-12-25',
                                'modified' => '2006-12-25 05:23:24',
                                'mytime' => '22:57:17',
                            ],
                            [
                                'id' => 4,
                                'apple_id' => 2,
                                'color' => 'Blue Green',
                                'name' => 'Test Name',
                                'created' => '2006-12-25 05:23:36',
                                'date' => '2006-12-25',
                                'modified' => '2006-12-25 05:23:36',
                                'mytime' => '22:57:17',
                ], ], ],
                'Sample' => [
                    'id' => 3,
                    'apple_id' => 4,
                    'name' => 'sample3',
                    'Apple' => [
                        'id' => 4,
                        'apple_id' => 2,
                        'color' => 'Blue Green',
                        'name' => 'Test Name',
                        'created' => '2006-12-25 05:23:36',
                        'date' => '2006-12-25',
                        'modified' => '2006-12-25 05:23:36',
                        'mytime' => '22:57:17',
                ], ],
                'Child' => [
                    [
                        'id' => 6,
                        'apple_id' => 4,
                        'color' => 'My new appleOrange',
                        'name' => 'My new apple',
                        'created' => '2006-12-25 05:29:39',
                        'date' => '2006-12-25',
                        'modified' => '2006-12-25 05:29:39',
                        'mytime' => '22:57:17',
                        'Parent' => [
                            'id' => 4,
                            'apple_id' => 2,
                            'color' => 'Blue Green',
                            'name' => 'Test Name',
                            'created' => '2006-12-25 05:23:36',
                            'date' => '2006-12-25',
                            'modified' => '2006-12-25 05:23:36',
                            'mytime' => '22:57:17',
                        ],
                        'Sample' => [],
                        'Child' => [
                            [
                                'id' => 7,
                                'apple_id' => 6,
                                'color' => 'Some wierd color',
                                'name' => 'Some odd color',
                                'created' => '2006-12-25 05:34:21',
                                'date' => '2006-12-25',
                                'modified' => '2006-12-25 05:34:21',
                                'mytime' => '22:57:17',
            ], ], ], ], ],
            [
                'Apple' => [
                    'id' => 5,
                    'apple_id' => 5,
                    'color' => 'Green',
                    'name' => 'Blue Green',
                    'created' => '2006-12-25 05:24:06',
                    'date' => '2006-12-25',
                    'modified' => '2006-12-25 05:29:16',
                    'mytime' => '22:57:17',
                ],
                'Parent' => [
                    'id' => 5,
                    'apple_id' => 5,
                    'color' => 'Green',
                    'name' => 'Blue Green',
                    'created' => '2006-12-25 05:24:06',
                    'date' => '2006-12-25',
                    'modified' => '2006-12-25 05:29:16',
                    'mytime' => '22:57:17',
                    'Parent' => [
                        'id' => 5,
                        'apple_id' => 5,
                        'color' => 'Green',
                        'name' => 'Blue Green',
                        'created' => '2006-12-25 05:24:06',
                        'date' => '2006-12-25',
                        'modified' => '2006-12-25 05:29:16',
                        'mytime' => '22:57:17',
                    ],
                    'Sample' => [
                        'id' => 4,
                        'apple_id' => 5,
                        'name' => 'sample4',
                    ],
                    'Child' => [
                        [
                            'id' => 5,
                            'apple_id' => 5,
                            'color' => 'Green',
                            'name' => 'Blue Green',
                            'created' => '2006-12-25 05:24:06',
                            'date' => '2006-12-25',
                            'modified' => '2006-12-25 05:29:16',
                            'mytime' => '22:57:17',
                ], ], ],
                'Sample' => [
                    'id' => 4,
                    'apple_id' => 5,
                    'name' => 'sample4',
                    'Apple' => [
                        'id' => 5,
                        'apple_id' => 5,
                        'color' => 'Green',
                        'name' => 'Blue Green',
                        'created' => '2006-12-25 05:24:06',
                        'date' => '2006-12-25',
                        'modified' => '2006-12-25 05:29:16',
                        'mytime' => '22:57:17',
                    ], ],
                    'Child' => [
                        [
                            'id' => 5,
                            'apple_id' => 5,
                            'color' => 'Green',
                            'name' => 'Blue Green',
                            'created' => '2006-12-25 05:24:06',
                            'date' => '2006-12-25',
                            'modified' => '2006-12-25 05:29:16',
                            'mytime' => '22:57:17',
                            'Parent' => [
                                'id' => 5,
                                'apple_id' => 5,
                                'color' => 'Green',
                                'name' => 'Blue Green',
                                'created' => '2006-12-25 05:24:06',
                                'date' => '2006-12-25',
                                'modified' => '2006-12-25 05:29:16',
                                'mytime' => '22:57:17',
                            ],
                            'Sample' => [
                                'id' => 4,
                                'apple_id' => 5,
                                'name' => 'sample4',
                            ],
                            'Child' => [
                                [
                                    'id' => 5,
                                    'apple_id' => 5,
                                    'color' => 'Green',
                                    'name' => 'Blue Green',
                                    'created' => '2006-12-25 05:24:06',
                                    'date' => '2006-12-25',
                                    'modified' => '2006-12-25 05:29:16',
                                    'mytime' => '22:57:17',
            ], ], ], ], ],
            [
                'Apple' => [
                    'id' => 6,
                    'apple_id' => 4,
                    'color' => 'My new appleOrange',
                    'name' => 'My new apple',
                    'created' => '2006-12-25 05:29:39',
                    'date' => '2006-12-25',
                    'modified' => '2006-12-25 05:29:39',
                    'mytime' => '22:57:17',
                ],
                'Parent' => [
                    'id' => 4,
                    'apple_id' => 2,
                    'color' => 'Blue Green',
                    'name' => 'Test Name',
                    'created' => '2006-12-25 05:23:36',
                    'date' => '2006-12-25',
                    'modified' => '2006-12-25 05:23:36',
                    'mytime' => '22:57:17',
                    'Parent' => [
                        'id' => 2,
                        'apple_id' => 1,
                        'color' => 'Bright Red 1',
                        'name' => 'Bright Red Apple',
                        'created' => '2006-11-22 10:43:13',
                        'date' => '2014-01-01',
                        'modified' => '2006-11-30 18:38:10',
                        'mytime' => '22:57:17',
                    ],
                    'Sample' => [
                        'id' => 3,
                        'apple_id' => 4,
                        'name' => 'sample3',
                    ],
                    'Child' => [
                        [
                            'id' => 6,
                            'apple_id' => 4,
                            'color' => 'My new appleOrange',
                            'name' => 'My new apple',
                            'created' => '2006-12-25 05:29:39',
                            'date' => '2006-12-25',
                            'modified' => '2006-12-25 05:29:39',
                            'mytime' => '22:57:17',
                ], ], ],
                'Sample' => [
                    'id' => '',
                    'apple_id' => '',
                    'name' => '',
                ],
                'Child' => [
                    [
                        'id' => 7,
                        'apple_id' => 6,
                        'color' => 'Some wierd color',
                        'name' => 'Some odd color',
                        'created' => '2006-12-25 05:34:21',
                        'date' => '2006-12-25',
                        'modified' => '2006-12-25 05:34:21',
                        'mytime' => '22:57:17',
                        'Parent' => [
                            'id' => 6,
                            'apple_id' => 4,
                            'color' => 'My new appleOrange',
                            'name' => 'My new apple',
                            'created' => '2006-12-25 05:29:39',
                            'date' => '2006-12-25',
                            'modified' => '2006-12-25 05:29:39',
                            'mytime' => '22:57:17',
                        ],
                        'Sample' => [],
            ], ], ],
            [
                'Apple' => [
                    'id' => 7,
                    'apple_id' => 6,
                    'color' => 'Some wierd color',
                    'name' => 'Some odd color',
                    'created' => '2006-12-25 05:34:21',
                    'date' => '2006-12-25',
                    'modified' => '2006-12-25 05:34:21',
                    'mytime' => '22:57:17',
                ],
                'Parent' => [
                    'id' => 6,
                    'apple_id' => 4,
                    'color' => 'My new appleOrange',
                    'name' => 'My new apple',
                    'created' => '2006-12-25 05:29:39',
                    'date' => '2006-12-25',
                    'modified' => '2006-12-25 05:29:39',
                    'mytime' => '22:57:17',
                    'Parent' => [
                        'id' => 4,
                        'apple_id' => 2,
                        'color' => 'Blue Green',
                        'name' => 'Test Name',
                        'created' => '2006-12-25 05:23:36',
                        'date' => '2006-12-25',
                        'modified' => '2006-12-25 05:23:36',
                        'mytime' => '22:57:17',
                    ],
                    'Sample' => [],
                    'Child' => [
                        [
                            'id' => 7,
                            'apple_id' => 6,
                            'color' => 'Some wierd color',
                            'name' => 'Some odd color',
                            'created' => '2006-12-25 05:34:21',
                            'date' => '2006-12-25',
                            'modified' => '2006-12-25 05:34:21',
                            'mytime' => '22:57:17',
                ], ], ],
                'Sample' => [
                    'id' => '',
                    'apple_id' => '',
                    'name' => '',
                ],
                'Child' => [], ], ];
        $this->assertEqual($result, $expected);

        $result = $TestModel->Parent->unbindModel(['hasOne' => ['Sample']]);
        $this->assertTrue($result);

        $result = $TestModel->find('all');
        $expected = [
            [
                'Apple' => [
                    'id' => 1,
                    'apple_id' => 2,
                    'color' => 'Red 1',
                    'name' => 'Red Apple 1',
                    'created' => '2006-11-22 10:38:58',
                    'date' => '1951-01-04',
                    'modified' => '2006-12-01 13:31:26',
                    'mytime' => '22:57:17', ],
                    'Parent' => [
                        'id' => 2,
                        'apple_id' => 1,
                        'color' => 'Bright Red 1',
                        'name' => 'Bright Red Apple',
                        'created' => '2006-11-22 10:43:13',
                        'date' => '2014-01-01',
                        'modified' => '2006-11-30 18:38:10',
                        'mytime' => '22:57:17',
                        'Parent' => [
                            'id' => 1,
                            'apple_id' => 2,
                            'color' => 'Red 1',
                            'name' => 'Red Apple 1',
                            'created' => '2006-11-22 10:38:58',
                            'date' => '1951-01-04',
                            'modified' => '2006-12-01 13:31:26',
                            'mytime' => '22:57:17',
                        ],
                        'Child' => [
                            [
                                'id' => 1,
                                'apple_id' => 2,
                                'color' => 'Red 1',
                                'name' => 'Red Apple 1',
                                'created' => '2006-11-22 10:38:58',
                                'date' => '1951-01-04',
                                'modified' => '2006-12-01 13:31:26',
                                'mytime' => '22:57:17',
                            ],
                            [
                                'id' => 3,
                                'apple_id' => 2,
                                'color' => 'blue green',
                                'name' => 'green blue',
                                'created' => '2006-12-25 05:13:36',
                                'date' => '2006-12-25',
                                'modified' => '2006-12-25 05:23:24',
                                'mytime' => '22:57:17',
                            ],
                            [
                                'id' => 4,
                                'apple_id' => 2,
                                'color' => 'Blue Green',
                                'name' => 'Test Name',
                                'created' => '2006-12-25 05:23:36',
                                'date' => '2006-12-25',
                                'modified' => '2006-12-25 05:23:36',
                                'mytime' => '22:57:17',
                    ], ], ],
                    'Sample' => [
                        'id' => '',
                        'apple_id' => '',
                        'name' => '',
                    ],
                    'Child' => [
                        [
                            'id' => 2,
                            'apple_id' => 1,
                            'color' => 'Bright Red 1',
                            'name' => 'Bright Red Apple',
                            'created' => '2006-11-22 10:43:13',
                            'date' => '2014-01-01',
                            'modified' => '2006-11-30 18:38:10',
                            'mytime' => '22:57:17',
                            'Parent' => [
                                'id' => 1,
                                'apple_id' => 2,
                                'color' => 'Red 1',
                                'name' => 'Red Apple 1',
                                'created' => '2006-11-22 10:38:58',
                                'date' => '1951-01-04',
                                'modified' => '2006-12-01 13:31:26',
                                'mytime' => '22:57:17',
                            ],
                            'Sample' => [
                                'id' => 2,
                                'apple_id' => 2,
                                'name' => 'sample2',
                            ],
                            'Child' => [
                                [
                                    'id' => 1,
                                    'apple_id' => 2,
                                    'color' => 'Red 1',
                                    'name' => 'Red Apple 1',
                                    'created' => '2006-11-22 10:38:58',
                                    'date' => '1951-01-04',
                                    'modified' => '2006-12-01 13:31:26',
                                    'mytime' => '22:57:17',
                                ],
                                [
                                    'id' => 3,
                                    'apple_id' => 2,
                                    'color' => 'blue green',
                                    'name' => 'green blue',
                                    'created' => '2006-12-25 05:13:36',
                                    'date' => '2006-12-25',
                                    'modified' => '2006-12-25 05:23:24',
                                    'mytime' => '22:57:17',
                                ],
                                [
                                    'id' => 4,
                                    'apple_id' => 2,
                                    'color' => 'Blue Green',
                                    'name' => 'Test Name',
                                    'created' => '2006-12-25 05:23:36',
                                    'date' => '2006-12-25',
                                    'modified' => '2006-12-25 05:23:36',
                                    'mytime' => '22:57:17',
            ], ], ], ], ],
            [
                'Apple' => [
                    'id' => 2,
                    'apple_id' => 1,
                    'color' => 'Bright Red 1',
                    'name' => 'Bright Red Apple',
                    'created' => '2006-11-22 10:43:13',
                    'date' => '2014-01-01',
                    'modified' => '2006-11-30 18:38:10',
                    'mytime' => '22:57:17',
                ],
                'Parent' => [
                    'id' => 1,
                    'apple_id' => 2,
                    'color' => 'Red 1',
                    'name' => 'Red Apple 1',
                    'created' => '2006-11-22 10:38:58',
                    'date' => '1951-01-04',
                    'modified' => '2006-12-01 13:31:26',
                    'mytime' => '22:57:17',
                    'Parent' => [
                        'id' => 2,
                        'apple_id' => 1,
                        'color' => 'Bright Red 1',
                        'name' => 'Bright Red Apple',
                        'created' => '2006-11-22 10:43:13',
                        'date' => '2014-01-01',
                        'modified' => '2006-11-30 18:38:10',
                        'mytime' => '22:57:17',
                    ],
                    'Child' => [
                        [
                            'id' => 2,
                            'apple_id' => 1,
                            'color' => 'Bright Red 1',
                            'name' => 'Bright Red Apple',
                            'created' => '2006-11-22 10:43:13',
                            'date' => '2014-01-01',
                            'modified' => '2006-11-30 18:38:10',
                            'mytime' => '22:57:17',
                ], ], ],
                'Sample' => [
                    'id' => 2,
                    'apple_id' => 2,
                    'name' => 'sample2',
                    'Apple' => [
                        'id' => 2,
                        'apple_id' => 1,
                        'color' => 'Bright Red 1',
                        'name' => 'Bright Red Apple',
                        'created' => '2006-11-22 10:43:13',
                        'date' => '2014-01-01',
                        'modified' => '2006-11-30 18:38:10',
                        'mytime' => '22:57:17',
                ], ],
                'Child' => [
                    [
                        'id' => 1,
                        'apple_id' => 2,
                        'color' => 'Red 1',
                        'name' => 'Red Apple 1',
                        'created' => '2006-11-22 10:38:58',
                        'date' => '1951-01-04',
                        'modified' => '2006-12-01 13:31:26',
                        'mytime' => '22:57:17',
                        'Parent' => [
                            'id' => 2,
                            'apple_id' => 1,
                            'color' => 'Bright Red 1',
                            'name' => 'Bright Red Apple',
                            'created' => '2006-11-22 10:43:13',
                            'date' => '2014-01-01',
                            'modified' => '2006-11-30 18:38:10',
                            'mytime' => '22:57:17',
                        ],
                        'Sample' => [],
                        'Child' => [
                            [
                                'id' => 2,
                                'apple_id' => 1,
                                'color' => 'Bright Red 1',
                                'name' => 'Bright Red Apple',
                                'created' => '2006-11-22 10:43:13',
                                'date' => '2014-01-01', 'modified' => '2006-11-30 18:38:10',
                                'mytime' => '22:57:17',
                    ], ], ],
                    [
                        'id' => 3,
                        'apple_id' => 2,
                        'color' => 'blue green',
                        'name' => 'green blue',
                        'created' => '2006-12-25 05:13:36',
                        'date' => '2006-12-25',
                        'modified' => '2006-12-25 05:23:24',
                        'mytime' => '22:57:17',
                        'Parent' => [
                            'id' => 2,
                            'apple_id' => 1,
                            'color' => 'Bright Red 1',
                            'name' => 'Bright Red Apple',
                            'created' => '2006-11-22 10:43:13',
                            'date' => '2014-01-01',
                            'modified' => '2006-11-30 18:38:10',
                            'mytime' => '22:57:17',
                        ],
                        'Sample' => [
                            'id' => 1,
                            'apple_id' => 3,
                            'name' => 'sample1',
                    ], ],
                    [
                        'id' => 4,
                        'apple_id' => 2,
                        'color' => 'Blue Green',
                        'name' => 'Test Name',
                        'created' => '2006-12-25 05:23:36',
                        'date' => '2006-12-25',
                        'modified' => '2006-12-25 05:23:36',
                        'mytime' => '22:57:17',
                        'Parent' => [
                            'id' => 2,
                            'apple_id' => 1,
                            'color' => 'Bright Red 1',
                            'name' => 'Bright Red Apple',
                            'created' => '2006-11-22 10:43:13',
                            'date' => '2014-01-01',
                            'modified' => '2006-11-30 18:38:10',
                            'mytime' => '22:57:17',
                        ],
                        'Sample' => [
                            'id' => 3,
                            'apple_id' => 4,
                            'name' => 'sample3',
                        ],
                        'Child' => [
                            [
                                'id' => 6,
                                'apple_id' => 4,
                                'color' => 'My new appleOrange',
                                'name' => 'My new apple',
                                'created' => '2006-12-25 05:29:39',
                                'date' => '2006-12-25',
                                'modified' => '2006-12-25 05:29:39',
                                'mytime' => '22:57:17',
            ], ], ], ], ],
            [
                'Apple' => [
                    'id' => 3,
                    'apple_id' => 2,
                    'color' => 'blue green',
                    'name' => 'green blue',
                    'created' => '2006-12-25 05:13:36',
                    'date' => '2006-12-25',
                    'modified' => '2006-12-25 05:23:24',
                    'mytime' => '22:57:17',
                ],
                'Parent' => [
                    'id' => 2,
                    'apple_id' => 1,
                    'color' => 'Bright Red 1',
                    'name' => 'Bright Red Apple',
                    'created' => '2006-11-22 10:43:13',
                    'date' => '2014-01-01',
                    'modified' => '2006-11-30 18:38:10',
                    'mytime' => '22:57:17',
                    'Parent' => [
                        'id' => 1,
                        'apple_id' => 2,
                        'color' => 'Red 1',
                        'name' => 'Red Apple 1',
                        'created' => '2006-11-22 10:38:58',
                        'date' => '1951-01-04',
                        'modified' => '2006-12-01 13:31:26',
                        'mytime' => '22:57:17',
                    ],
                    'Child' => [
                        [
                            'id' => 1,
                            'apple_id' => 2,
                            'color' => 'Red 1',
                            'name' => 'Red Apple 1',
                            'created' => '2006-11-22 10:38:58',
                            'date' => '1951-01-04',
                            'modified' => '2006-12-01 13:31:26',
                            'mytime' => '22:57:17',
                        ],
                        [
                            'id' => 3,
                            'apple_id' => 2,
                            'color' => 'blue green',
                            'name' => 'green blue',
                            'created' => '2006-12-25 05:13:36',
                            'date' => '2006-12-25',
                            'modified' => '2006-12-25 05:23:24',
                            'mytime' => '22:57:17',
                        ],
                        [
                            'id' => 4,
                            'apple_id' => 2,
                            'color' => 'Blue Green',
                            'name' => 'Test Name',
                            'created' => '2006-12-25 05:23:36',
                            'date' => '2006-12-25',
                            'modified' => '2006-12-25 05:23:36',
                            'mytime' => '22:57:17',
                ], ], ],
                'Sample' => [
                    'id' => 1,
                    'apple_id' => 3,
                    'name' => 'sample1',
                    'Apple' => [
                        'id' => 3,
                        'apple_id' => 2,
                        'color' => 'blue green',
                        'name' => 'green blue',
                        'created' => '2006-12-25 05:13:36',
                        'date' => '2006-12-25',
                        'modified' => '2006-12-25 05:23:24',
                        'mytime' => '22:57:17',
                ], ],
                'Child' => [],
            ],
            [
                'Apple' => [
                    'id' => 4,
                    'apple_id' => 2,
                    'color' => 'Blue Green',
                    'name' => 'Test Name',
                    'created' => '2006-12-25 05:23:36',
                    'date' => '2006-12-25',
                    'modified' => '2006-12-25 05:23:36',
                    'mytime' => '22:57:17',
                ],
                'Parent' => [
                    'id' => 2,
                    'apple_id' => 1,
                    'color' => 'Bright Red 1',
                    'name' => 'Bright Red Apple',
                    'created' => '2006-11-22 10:43:13',
                    'date' => '2014-01-01',
                    'modified' => '2006-11-30 18:38:10',
                    'mytime' => '22:57:17',
                    'Parent' => [
                        'id' => 1,
                        'apple_id' => 2,
                        'color' => 'Red 1',
                        'name' => 'Red Apple 1',
                        'created' => '2006-11-22 10:38:58',
                        'date' => '1951-01-04',
                        'modified' => '2006-12-01 13:31:26',
                        'mytime' => '22:57:17',
                    ],
                    'Child' => [
                        [
                            'id' => 1,
                            'apple_id' => 2,
                            'color' => 'Red 1',
                            'name' => 'Red Apple 1',
                            'created' => '2006-11-22 10:38:58',
                            'date' => '1951-01-04',
                            'modified' => '2006-12-01 13:31:26',
                            'mytime' => '22:57:17',
                        ],
                        [
                            'id' => 3,
                            'apple_id' => 2,
                            'color' => 'blue green',
                            'name' => 'green blue',
                            'created' => '2006-12-25 05:13:36',
                            'date' => '2006-12-25',
                            'modified' => '2006-12-25 05:23:24',
                            'mytime' => '22:57:17',
                        ],
                        [
                            'id' => 4,
                            'apple_id' => 2,
                            'color' => 'Blue Green',
                            'name' => 'Test Name',
                            'created' => '2006-12-25 05:23:36',
                            'date' => '2006-12-25',
                            'modified' => '2006-12-25 05:23:36',
                            'mytime' => '22:57:17',
                ], ], ],
                'Sample' => [
                    'id' => 3,
                    'apple_id' => 4,
                    'name' => 'sample3',
                    'Apple' => [
                        'id' => 4,
                        'apple_id' => 2,
                        'color' => 'Blue Green',
                        'name' => 'Test Name',
                        'created' => '2006-12-25 05:23:36',
                        'date' => '2006-12-25',
                        'modified' => '2006-12-25 05:23:36',
                        'mytime' => '22:57:17',
                ], ],
                'Child' => [
                    [
                        'id' => 6,
                        'apple_id' => 4,
                        'color' => 'My new appleOrange',
                        'name' => 'My new apple',
                        'created' => '2006-12-25 05:29:39',
                        'date' => '2006-12-25',
                        'modified' => '2006-12-25 05:29:39',
                        'mytime' => '22:57:17',
                        'Parent' => [
                            'id' => 4,
                            'apple_id' => 2,
                            'color' => 'Blue Green',
                            'name' => 'Test Name',
                            'created' => '2006-12-25 05:23:36',
                            'date' => '2006-12-25',
                            'modified' => '2006-12-25 05:23:36',
                            'mytime' => '22:57:17',
                        ],
                        'Sample' => [],
                            'Child' => [
                                [
                                    'id' => 7,
                                    'apple_id' => 6,
                                    'color' => 'Some wierd color',
                                    'name' => 'Some odd color',
                                    'created' => '2006-12-25 05:34:21',
                                    'date' => '2006-12-25',
                                    'modified' => '2006-12-25 05:34:21',
                                    'mytime' => '22:57:17',
            ], ], ], ], ],
            [
                'Apple' => [
                    'id' => 5,
                    'apple_id' => 5,
                    'color' => 'Green',
                    'name' => 'Blue Green',
                    'created' => '2006-12-25 05:24:06',
                    'date' => '2006-12-25',
                    'modified' => '2006-12-25 05:29:16',
                    'mytime' => '22:57:17',
                ],
                'Parent' => [
                    'id' => 5,
                    'apple_id' => 5,
                    'color' => 'Green',
                    'name' => 'Blue Green',
                    'created' => '2006-12-25 05:24:06',
                    'date' => '2006-12-25',
                    'modified' => '2006-12-25 05:29:16',
                    'mytime' => '22:57:17',
                    'Parent' => [
                        'id' => 5,
                        'apple_id' => 5,
                        'color' => 'Green',
                        'name' => 'Blue Green',
                        'created' => '2006-12-25 05:24:06',
                        'date' => '2006-12-25',
                        'modified' => '2006-12-25 05:29:16',
                        'mytime' => '22:57:17',
                    ],
                    'Child' => [
                        [
                            'id' => 5,
                            'apple_id' => 5,
                            'color' => 'Green',
                            'name' => 'Blue Green',
                            'created' => '2006-12-25 05:24:06',
                            'date' => '2006-12-25',
                            'modified' => '2006-12-25 05:29:16',
                            'mytime' => '22:57:17',
                ], ], ],
                'Sample' => [
                    'id' => 4,
                    'apple_id' => 5,
                    'name' => 'sample4',
                    'Apple' => [
                        'id' => 5,
                        'apple_id' => 5,
                        'color' => 'Green',
                        'name' => 'Blue Green',
                        'created' => '2006-12-25 05:24:06',
                        'date' => '2006-12-25',
                        'modified' => '2006-12-25 05:29:16',
                        'mytime' => '22:57:17',
                ], ],
                'Child' => [
                    [
                        'id' => 5,
                        'apple_id' => 5,
                        'color' => 'Green',
                        'name' => 'Blue Green',
                        'created' => '2006-12-25 05:24:06',
                        'date' => '2006-12-25',
                        'modified' => '2006-12-25 05:29:16',
                        'mytime' => '22:57:17',
                        'Parent' => [
                            'id' => 5,
                            'apple_id' => 5,
                            'color' => 'Green',
                            'name' => 'Blue Green',
                            'created' => '2006-12-25 05:24:06',
                            'date' => '2006-12-25',
                            'modified' => '2006-12-25 05:29:16',
                            'mytime' => '22:57:17',
                        ],
                        'Sample' => [
                            'id' => 4,
                            'apple_id' => 5,
                            'name' => 'sample4',
                        ],
                        'Child' => [
                            [
                                'id' => 5,
                                'apple_id' => 5,
                                'color' => 'Green',
                                'name' => 'Blue Green',
                                'created' => '2006-12-25 05:24:06',
                                'date' => '2006-12-25',
                                'modified' => '2006-12-25 05:29:16',
                                'mytime' => '22:57:17',
            ], ], ], ], ],
            [
                'Apple' => [
                    'id' => 6,
                    'apple_id' => 4,
                    'color' => 'My new appleOrange',
                    'name' => 'My new apple',
                    'created' => '2006-12-25 05:29:39',
                    'date' => '2006-12-25',
                    'modified' => '2006-12-25 05:29:39',
                    'mytime' => '22:57:17',
                ],
                'Parent' => [
                    'id' => 4,
                    'apple_id' => 2,
                    'color' => 'Blue Green',
                    'name' => 'Test Name',
                    'created' => '2006-12-25 05:23:36',
                    'date' => '2006-12-25',
                    'modified' => '2006-12-25 05:23:36',
                    'mytime' => '22:57:17',
                    'Parent' => [
                        'id' => 2,
                        'apple_id' => 1,
                        'color' => 'Bright Red 1',
                        'name' => 'Bright Red Apple',
                        'created' => '2006-11-22 10:43:13',
                        'date' => '2014-01-01',
                        'modified' => '2006-11-30 18:38:10',
                        'mytime' => '22:57:17',
                    ],
                    'Child' => [
                        [
                            'id' => 6,
                            'apple_id' => 4,
                            'color' => 'My new appleOrange',
                            'name' => 'My new apple',
                            'created' => '2006-12-25 05:29:39',
                            'date' => '2006-12-25',
                            'modified' => '2006-12-25 05:29:39',
                            'mytime' => '22:57:17',
                ], ], ],
                'Sample' => [
                    'id' => '',
                    'apple_id' => '',
                    'name' => '',
                ],
                'Child' => [
                    [
                        'id' => 7,
                        'apple_id' => 6,
                        'color' => 'Some wierd color',
                        'name' => 'Some odd color',
                        'created' => '2006-12-25 05:34:21',
                        'date' => '2006-12-25',
                        'modified' => '2006-12-25 05:34:21',
                        'mytime' => '22:57:17',
                        'Parent' => [
                            'id' => 6,
                            'apple_id' => 4,
                            'color' => 'My new appleOrange',
                            'name' => 'My new apple',
                            'created' => '2006-12-25 05:29:39',
                            'date' => '2006-12-25',
                            'modified' => '2006-12-25 05:29:39',
                            'mytime' => '22:57:17',
                        ],
                        'Sample' => [],
            ], ], ],
            [
                'Apple' => [
                    'id' => 7,
                    'apple_id' => 6,
                    'color' => 'Some wierd color',
                    'name' => 'Some odd color',
                    'created' => '2006-12-25 05:34:21',
                    'date' => '2006-12-25',
                    'modified' => '2006-12-25 05:34:21',
                    'mytime' => '22:57:17',
                ],
                'Parent' => [
                    'id' => 6,
                    'apple_id' => 4,
                    'color' => 'My new appleOrange',
                    'name' => 'My new apple',
                    'created' => '2006-12-25 05:29:39',
                    'date' => '2006-12-25',
                    'modified' => '2006-12-25 05:29:39',
                    'mytime' => '22:57:17',
                    'Parent' => [
                        'id' => 4,
                        'apple_id' => 2,
                        'color' => 'Blue Green',
                        'name' => 'Test Name',
                        'created' => '2006-12-25 05:23:36',
                        'date' => '2006-12-25',
                        'modified' => '2006-12-25 05:23:36',
                        'mytime' => '22:57:17',
                    ],
                    'Child' => [
                        [
                            'id' => 7,
                            'apple_id' => 6,
                            'color' => 'Some wierd color',
                            'name' => 'Some odd color',
                            'created' => '2006-12-25 05:34:21',
                            'date' => '2006-12-25',
                            'modified' => '2006-12-25 05:34:21',
                            'mytime' => '22:57:17',
                ], ], ],
                'Sample' => [
                    'id' => '',
                    'apple_id' => '',
                    'name' => '',
                ],
                'Child' => [],
        ], ];

        $this->assertEqual($result, $expected);

        $result = $TestModel->Parent->unbindModel(['hasOne' => ['Sample']]);
        $this->assertTrue($result);

        $result = $TestModel->unbindModel(['hasMany' => ['Child']]);
        $this->assertTrue($result);

        $result = $TestModel->find('all');
        $expected = [
            [
                'Apple' => [
                    'id' => 1,
                    'apple_id' => 2,
                    'color' => 'Red 1',
                    'name' => 'Red Apple 1',
                    'created' => '2006-11-22 10:38:58',
                    'date' => '1951-01-04',
                    'modified' => '2006-12-01 13:31:26',
                    'mytime' => '22:57:17',
                ],
                'Parent' => [
                    'id' => 2,
                    'apple_id' => 1,
                    'color' => 'Bright Red 1',
                    'name' => 'Bright Red Apple',
                    'created' => '2006-11-22 10:43:13',
                    'date' => '2014-01-01',
                    'modified' => '2006-11-30 18:38:10',
                    'mytime' => '22:57:17',
                    'Parent' => [
                        'id' => 1,
                        'apple_id' => 2,
                        'color' => 'Red 1',
                        'name' => 'Red Apple 1',
                        'created' => '2006-11-22 10:38:58',
                        'date' => '1951-01-04',
                        'modified' => '2006-12-01 13:31:26',
                        'mytime' => '22:57:17',
                    ],
                    'Child' => [
                        [
                            'id' => 1,
                            'apple_id' => 2,
                            'color' => 'Red 1',
                            'name' => 'Red Apple 1',
                            'created' => '2006-11-22 10:38:58',
                            'date' => '1951-01-04',
                            'modified' => '2006-12-01 13:31:26',
                            'mytime' => '22:57:17',
                        ],
                        [
                            'id' => 3,
                            'apple_id' => 2,
                            'color' => 'blue green',
                            'name' => 'green blue',
                            'created' => '2006-12-25 05:13:36',
                            'date' => '2006-12-25',
                            'modified' => '2006-12-25 05:23:24',
                            'mytime' => '22:57:17',
                        ],
                        [
                            'id' => 4,
                            'apple_id' => 2,
                            'color' => 'Blue Green',
                            'name' => 'Test Name',
                            'created' => '2006-12-25 05:23:36',
                            'date' => '2006-12-25',
                            'modified' => '2006-12-25 05:23:36',
                            'mytime' => '22:57:17',
                ], ], ],
                'Sample' => [
                    'id' => '',
                    'apple_id' => '',
                    'name' => '',
            ], ],
            [
                'Apple' => [
                    'id' => 2,
                    'apple_id' => 1,
                    'color' => 'Bright Red 1',
                    'name' => 'Bright Red Apple',
                    'created' => '2006-11-22 10:43:13',
                    'date' => '2014-01-01',
                    'modified' => '2006-11-30 18:38:10',
                    'mytime' => '22:57:17',
                ],
                'Parent' => [
                    'id' => 1,
                    'apple_id' => 2,
                    'color' => 'Red 1',
                    'name' => 'Red Apple 1',
                    'created' => '2006-11-22 10:38:58',
                    'date' => '1951-01-04',
                    'modified' => '2006-12-01 13:31:26',
                    'mytime' => '22:57:17',
                    'Parent' => [
                        'id' => 2,
                        'apple_id' => 1,
                        'color' => 'Bright Red 1',
                        'name' => 'Bright Red Apple',
                        'created' => '2006-11-22 10:43:13',
                        'date' => '2014-01-01',
                        'modified' => '2006-11-30 18:38:10',
                        'mytime' => '22:57:17',
                    ],
                    'Child' => [
                        [
                            'id' => 2,
                            'apple_id' => 1,
                            'color' => 'Bright Red 1',
                            'name' => 'Bright Red Apple',
                            'created' => '2006-11-22 10:43:13',
                            'date' => '2014-01-01',
                            'modified' => '2006-11-30 18:38:10',
                            'mytime' => '22:57:17',
                ], ], ],
                'Sample' => [
                    'id' => 2,
                    'apple_id' => 2,
                    'name' => 'sample2',
                    'Apple' => [
                        'id' => 2,
                        'apple_id' => 1,
                        'color' => 'Bright Red 1',
                        'name' => 'Bright Red Apple',
                        'created' => '2006-11-22 10:43:13',
                        'date' => '2014-01-01',
                        'modified' => '2006-11-30 18:38:10',
                        'mytime' => '22:57:17',
            ], ], ],
            [
                'Apple' => [
                'id' => 3,
                'apple_id' => 2,
                'color' => 'blue green',
                'name' => 'green blue',
                'created' => '2006-12-25 05:13:36',
                'date' => '2006-12-25',
                'modified' => '2006-12-25 05:23:24',
                'mytime' => '22:57:17',
            ],
            'Parent' => [
                'id' => 2,
                'apple_id' => 1,
                'color' => 'Bright Red 1',
                'name' => 'Bright Red Apple',
                'created' => '2006-11-22 10:43:13',
                'date' => '2014-01-01',
                'modified' => '2006-11-30 18:38:10',
                'mytime' => '22:57:17',
                'Parent' => [
                    'id' => 1,
                    'apple_id' => 2,
                    'color' => 'Red 1',
                    'name' => 'Red Apple 1',
                    'created' => '2006-11-22 10:38:58',
                    'date' => '1951-01-04',
                    'modified' => '2006-12-01 13:31:26',
                    'mytime' => '22:57:17',
                ],
                'Child' => [
                    [
                        'id' => 1,
                        'apple_id' => 2,
                        'color' => 'Red 1',
                        'name' => 'Red Apple 1',
                        'created' => '2006-11-22 10:38:58',
                        'date' => '1951-01-04',
                        'modified' => '2006-12-01 13:31:26',
                        'mytime' => '22:57:17',
                    ],
                    [
                        'id' => 3,
                        'apple_id' => 2,
                        'color' => 'blue green',
                        'name' => 'green blue',
                        'created' => '2006-12-25 05:13:36',
                        'date' => '2006-12-25',
                        'modified' => '2006-12-25 05:23:24',
                        'mytime' => '22:57:17',
                    ],
                    [
                        'id' => 4,
                        'apple_id' => 2,
                        'color' => 'Blue Green',
                        'name' => 'Test Name',
                        'created' => '2006-12-25 05:23:36',
                        'date' => '2006-12-25',
                        'modified' => '2006-12-25 05:23:36',
                        'mytime' => '22:57:17',
            ], ], ],
            'Sample' => [
                'id' => 1,
                'apple_id' => 3,
                'name' => 'sample1',
                'Apple' => [
                    'id' => 3,
                    'apple_id' => 2,
                    'color' => 'blue green',
                    'name' => 'green blue',
                    'created' => '2006-12-25 05:13:36',
                    'date' => '2006-12-25',
                    'modified' => '2006-12-25 05:23:24',
                    'mytime' => '22:57:17',
        ], ], ],
        [
            'Apple' => [
                'id' => 4,
                'apple_id' => 2,
                'color' => 'Blue Green',
                'name' => 'Test Name',
                'created' => '2006-12-25 05:23:36',
                'date' => '2006-12-25',
                'modified' => '2006-12-25 05:23:36',
                'mytime' => '22:57:17',
            ],
            'Parent' => [
                'id' => 2,
                'apple_id' => 1,
                'color' => 'Bright Red 1',
                'name' => 'Bright Red Apple',
                'created' => '2006-11-22 10:43:13',
                'date' => '2014-01-01',
                'modified' => '2006-11-30 18:38:10',
                'mytime' => '22:57:17',
                'Parent' => [
                    'id' => 1,
                    'apple_id' => 2,
                    'color' => 'Red 1',
                    'name' => 'Red Apple 1',
                    'created' => '2006-11-22 10:38:58',
                    'date' => '1951-01-04',
                    'modified' => '2006-12-01 13:31:26',
                    'mytime' => '22:57:17',
                ],
                'Child' => [
                    [
                        'id' => 1,
                        'apple_id' => 2,
                        'color' => 'Red 1',
                        'name' => 'Red Apple 1',
                        'created' => '2006-11-22 10:38:58',
                        'date' => '1951-01-04',
                        'modified' => '2006-12-01 13:31:26',
                        'mytime' => '22:57:17',
                    ],
                    [
                        'id' => 3,
                        'apple_id' => 2,
                        'color' => 'blue green',
                        'name' => 'green blue',
                        'created' => '2006-12-25 05:13:36',
                        'date' => '2006-12-25',
                        'modified' => '2006-12-25 05:23:24',
                        'mytime' => '22:57:17',
                    ],
                    [
                        'id' => 4,
                        'apple_id' => 2,
                        'color' => 'Blue Green',
                        'name' => 'Test Name',
                        'created' => '2006-12-25 05:23:36',
                        'date' => '2006-12-25',
                        'modified' => '2006-12-25 05:23:36',
                        'mytime' => '22:57:17',
            ], ], ],
            'Sample' => [
                'id' => 3,
                'apple_id' => 4,
                'name' => 'sample3',
                'Apple' => [
                    'id' => 4,
                    'apple_id' => 2,
                    'color' => 'Blue Green',
                    'name' => 'Test Name',
                    'created' => '2006-12-25 05:23:36',
                    'date' => '2006-12-25',
                    'modified' => '2006-12-25 05:23:36',
                    'mytime' => '22:57:17',
        ], ], ],
        [
            'Apple' => [
                'id' => 5,
                'apple_id' => 5,
                'color' => 'Green',
                'name' => 'Blue Green',
                'created' => '2006-12-25 05:24:06',
                'date' => '2006-12-25',
                'modified' => '2006-12-25 05:29:16',
                'mytime' => '22:57:17',
            ],
            'Parent' => [
                'id' => 5,
                'apple_id' => 5,
                'color' => 'Green',
                'name' => 'Blue Green',
                'created' => '2006-12-25 05:24:06',
                'date' => '2006-12-25',
                'modified' => '2006-12-25 05:29:16',
                'mytime' => '22:57:17',
                'Parent' => [
                    'id' => 5,
                    'apple_id' => 5,
                    'color' => 'Green',
                    'name' => 'Blue Green',
                    'created' => '2006-12-25 05:24:06',
                    'date' => '2006-12-25',
                    'modified' => '2006-12-25 05:29:16',
                    'mytime' => '22:57:17',
                ],
                'Child' => [
                    [
                        'id' => 5,
                        'apple_id' => 5,
                        'color' => 'Green',
                        'name' => 'Blue Green',
                        'created' => '2006-12-25 05:24:06',
                        'date' => '2006-12-25',
                        'modified' => '2006-12-25 05:29:16',
                        'mytime' => '22:57:17',
            ], ], ],
            'Sample' => [
                'id' => 4,
                'apple_id' => 5,
                'name' => 'sample4',
                'Apple' => [
                    'id' => 5,
                    'apple_id' => 5,
                    'color' => 'Green',
                    'name' => 'Blue Green',
                    'created' => '2006-12-25 05:24:06',
                    'date' => '2006-12-25',
                    'modified' => '2006-12-25 05:29:16',
                    'mytime' => '22:57:17',
        ], ], ],
        [
            'Apple' => [
                'id' => 6,
                'apple_id' => 4,
                'color' => 'My new appleOrange',
                'name' => 'My new apple',
                'created' => '2006-12-25 05:29:39',
                'date' => '2006-12-25',
                'modified' => '2006-12-25 05:29:39',
                'mytime' => '22:57:17',
            ],
            'Parent' => [
                'id' => 4,
                'apple_id' => 2,
                'color' => 'Blue Green',
                'name' => 'Test Name',
                'created' => '2006-12-25 05:23:36',
                'date' => '2006-12-25',
                'modified' => '2006-12-25 05:23:36',
                'mytime' => '22:57:17',
                'Parent' => [
                    'id' => 2,
                    'apple_id' => 1,
                    'color' => 'Bright Red 1',
                    'name' => 'Bright Red Apple',
                    'created' => '2006-11-22 10:43:13',
                    'date' => '2014-01-01',
                    'modified' => '2006-11-30 18:38:10',
                    'mytime' => '22:57:17',
                ],
                'Child' => [
                    [
                        'id' => 6,
                        'apple_id' => 4,
                        'color' => 'My new appleOrange',
                        'name' => 'My new apple',
                        'created' => '2006-12-25 05:29:39',
                        'date' => '2006-12-25',
                        'modified' => '2006-12-25 05:29:39',
                        'mytime' => '22:57:17',
            ], ], ],
            'Sample' => [
                'id' => '',
                'apple_id' => '',
                'name' => '',
        ], ],
        [
            'Apple' => [
                'id' => 7,
                'apple_id' => 6,
                'color' => 'Some wierd color',
                'name' => 'Some odd color',
                'created' => '2006-12-25 05:34:21',
                'date' => '2006-12-25',
                'modified' => '2006-12-25 05:34:21',
                'mytime' => '22:57:17',
            ],
            'Parent' => [
                'id' => 6,
                'apple_id' => 4,
                'color' => 'My new appleOrange',
                'name' => 'My new apple',
                'created' => '2006-12-25 05:29:39',
                'date' => '2006-12-25',
                'modified' => '2006-12-25 05:29:39',
                'mytime' => '22:57:17',
                'Parent' => [
                    'id' => 4,
                    'apple_id' => 2,
                    'color' => 'Blue Green',
                    'name' => 'Test Name',
                    'created' => '2006-12-25 05:23:36',
                    'date' => '2006-12-25',
                    'modified' => '2006-12-25 05:23:36',
                    'mytime' => '22:57:17',
                ],
                'Child' => [
                    [
                        'id' => 7,
                        'apple_id' => 6,
                        'color' => 'Some wierd color',
                        'name' => 'Some odd color',
                        'created' => '2006-12-25 05:34:21',
                        'date' => '2006-12-25',
                        'modified' => '2006-12-25 05:34:21',
                        'mytime' => '22:57:17',
            ], ], ],
            'Sample' => [
                'id' => '',
                'apple_id' => '',
                'name' => '',
        ], ], ];

        $this->assertEqual($result, $expected);

        $result = $TestModel->unbindModel(['hasMany' => ['Child']]);
        $this->assertTrue($result);

        $result = $TestModel->Sample->unbindModel(['belongsTo' => ['Apple']]);
        $this->assertTrue($result);

        $result = $TestModel->find('all');
        $expected = [
            [
                'Apple' => [
                    'id' => 1,
                    'apple_id' => 2,
                    'color' => 'Red 1',
                    'name' => 'Red Apple 1',
                    'created' => '2006-11-22 10:38:58',
                    'date' => '1951-01-04',
                    'modified' => '2006-12-01 13:31:26',
                    'mytime' => '22:57:17',
                ],
                'Parent' => [
                    'id' => 2,
                    'apple_id' => 1,
                    'color' => 'Bright Red 1',
                    'name' => 'Bright Red Apple',
                    'created' => '2006-11-22 10:43:13',
                    'date' => '2014-01-01',
                    'modified' => '2006-11-30 18:38:10',
                    'mytime' => '22:57:17',
                    'Parent' => [
                        'id' => 1,
                        'apple_id' => 2,
                        'color' => 'Red 1',
                        'name' => 'Red Apple 1',
                        'created' => '2006-11-22 10:38:58',
                        'date' => '1951-01-04',
                        'modified' => '2006-12-01 13:31:26',
                        'mytime' => '22:57:17',
                    ],
                    'Sample' => [
                        'id' => 2,
                        'apple_id' => 2,
                        'name' => 'sample2',
                    ],
                    'Child' => [
                        [
                            'id' => 1,
                            'apple_id' => 2,
                            'color' => 'Red 1',
                            'name' => 'Red Apple 1',
                            'created' => '2006-11-22 10:38:58',
                            'date' => '1951-01-04',
                            'modified' => '2006-12-01 13:31:26',
                            'mytime' => '22:57:17',
                        ],
                        [
                            'id' => 3,
                            'apple_id' => 2,
                            'color' => 'blue green',
                            'name' => 'green blue',
                            'created' => '2006-12-25 05:13:36',
                            'date' => '2006-12-25',
                            'modified' => '2006-12-25 05:23:24',
                            'mytime' => '22:57:17',
                        ],
                        [
                            'id' => 4,
                            'apple_id' => 2,
                            'color' => 'Blue Green',
                            'name' => 'Test Name',
                            'created' => '2006-12-25 05:23:36',
                            'date' => '2006-12-25',
                            'modified' => '2006-12-25 05:23:36',
                            'mytime' => '22:57:17',
                ], ], ],
                'Sample' => [
                    'id' => '',
                    'apple_id' => '',
                    'name' => '',
            ], ],
            [
                'Apple' => [
                    'id' => 2,
                    'apple_id' => 1,
                    'color' => 'Bright Red 1',
                    'name' => 'Bright Red Apple',
                    'created' => '2006-11-22 10:43:13',
                    'date' => '2014-01-01',
                    'modified' => '2006-11-30 18:38:10',
                    'mytime' => '22:57:17',
                ],
                'Parent' => [
                    'id' => 1,
                    'apple_id' => 2,
                    'color' => 'Red 1',
                    'name' => 'Red Apple 1',
                    'created' => '2006-11-22 10:38:58',
                    'date' => '1951-01-04',
                    'modified' => '2006-12-01 13:31:26',
                    'mytime' => '22:57:17',
                    'Parent' => [
                        'id' => 2,
                        'apple_id' => 1,
                        'color' => 'Bright Red 1',
                        'name' => 'Bright Red Apple',
                        'created' => '2006-11-22 10:43:13',
                        'date' => '2014-01-01',
                        'modified' => '2006-11-30 18:38:10',
                        'mytime' => '22:57:17',
                    ],
                    'Sample' => [],
                    'Child' => [
                        [
                            'id' => 2,
                            'apple_id' => 1,
                            'color' => 'Bright Red 1',
                            'name' => 'Bright Red Apple',
                            'created' => '2006-11-22 10:43:13',
                            'date' => '2014-01-01',
                            'modified' => '2006-11-30 18:38:10',
                            'mytime' => '22:57:17',
                ], ], ],
                'Sample' => [
                    'id' => 2,
                    'apple_id' => 2,
                    'name' => 'sample2',
            ], ],
            [
                'Apple' => [
                    'id' => 3,
                    'apple_id' => 2,
                    'color' => 'blue green',
                    'name' => 'green blue',
                    'created' => '2006-12-25 05:13:36',
                    'date' => '2006-12-25',
                    'modified' => '2006-12-25 05:23:24',
                    'mytime' => '22:57:17',
                ],
                'Parent' => [
                    'id' => 2,
                    'apple_id' => 1,
                    'color' => 'Bright Red 1',
                    'name' => 'Bright Red Apple',
                    'created' => '2006-11-22 10:43:13',
                    'date' => '2014-01-01',
                    'modified' => '2006-11-30 18:38:10',
                    'mytime' => '22:57:17',
                    'Parent' => [
                        'id' => 1,
                        'apple_id' => 2,
                        'color' => 'Red 1',
                        'name' => 'Red Apple 1',
                        'created' => '2006-11-22 10:38:58',
                        'date' => '1951-01-04',
                        'modified' => '2006-12-01 13:31:26',
                        'mytime' => '22:57:17',
                    ],
                    'Sample' => [
                        'id' => 2,
                        'apple_id' => 2,
                        'name' => 'sample2',
                    ],
                    'Child' => [
                        [
                            'id' => 1,
                            'apple_id' => 2,
                            'color' => 'Red 1',
                            'name' => 'Red Apple 1',
                            'created' => '2006-11-22 10:38:58',
                            'date' => '1951-01-04',
                            'modified' => '2006-12-01 13:31:26',
                            'mytime' => '22:57:17',
                        ],
                        [
                            'id' => 3,
                            'apple_id' => 2,
                            'color' => 'blue green',
                            'name' => 'green blue',
                            'created' => '2006-12-25 05:13:36',
                            'date' => '2006-12-25',
                            'modified' => '2006-12-25 05:23:24',
                            'mytime' => '22:57:17',
                        ],
                        [
                            'id' => 4,
                            'apple_id' => 2,
                            'color' => 'Blue Green',
                            'name' => 'Test Name',
                            'created' => '2006-12-25 05:23:36',
                            'date' => '2006-12-25',
                            'modified' => '2006-12-25 05:23:36',
                            'mytime' => '22:57:17',
                ], ], ],
                'Sample' => [
                    'id' => 1,
                    'apple_id' => 3,
                    'name' => 'sample1',
            ], ],
            [
                'Apple' => [
                    'id' => 4,
                    'apple_id' => 2,
                    'color' => 'Blue Green',
                    'name' => 'Test Name',
                    'created' => '2006-12-25 05:23:36',
                    'date' => '2006-12-25',
                    'modified' => '2006-12-25 05:23:36',
                    'mytime' => '22:57:17',
                ],
                'Parent' => [
                    'id' => 2,
                    'apple_id' => 1,
                    'color' => 'Bright Red 1',
                    'name' => 'Bright Red Apple',
                    'created' => '2006-11-22 10:43:13',
                    'date' => '2014-01-01',
                    'modified' => '2006-11-30 18:38:10',
                    'mytime' => '22:57:17',
                    'Parent' => [
                        'id' => 1,
                        'apple_id' => 2,
                        'color' => 'Red 1',
                        'name' => 'Red Apple 1',
                        'created' => '2006-11-22 10:38:58',
                        'date' => '1951-01-04',
                        'modified' => '2006-12-01 13:31:26',
                        'mytime' => '22:57:17',
                    ],
                    'Sample' => [
                        'id' => 2,
                        'apple_id' => 2,
                        'name' => 'sample2',
                    ],
                    'Child' => [
                        [
                            'id' => 1,
                            'apple_id' => 2,
                            'color' => 'Red 1',
                            'name' => 'Red Apple 1',
                            'created' => '2006-11-22 10:38:58',
                            'date' => '1951-01-04',
                            'modified' => '2006-12-01 13:31:26',
                            'mytime' => '22:57:17',
                        ],
                        [
                            'id' => 3,
                            'apple_id' => 2,
                            'color' => 'blue green',
                            'name' => 'green blue',
                            'created' => '2006-12-25 05:13:36',
                            'date' => '2006-12-25',
                            'modified' => '2006-12-25 05:23:24',
                            'mytime' => '22:57:17',
                        ],
                        [
                            'id' => 4,
                            'apple_id' => 2,
                            'color' => 'Blue Green',
                            'name' => 'Test Name',
                            'created' => '2006-12-25 05:23:36',
                            'date' => '2006-12-25',
                            'modified' => '2006-12-25 05:23:36',
                            'mytime' => '22:57:17',
                ], ], ],
                'Sample' => [
                    'id' => 3,
                    'apple_id' => 4,
                    'name' => 'sample3',
            ], ],
            [
                'Apple' => [
                    'id' => 5,
                    'apple_id' => 5,
                    'color' => 'Green',
                    'name' => 'Blue Green',
                    'created' => '2006-12-25 05:24:06',
                    'date' => '2006-12-25',
                    'modified' => '2006-12-25 05:29:16',
                    'mytime' => '22:57:17',
                ],
                'Parent' => [
                    'id' => 5,
                    'apple_id' => 5,
                    'color' => 'Green',
                    'name' => 'Blue Green',
                    'created' => '2006-12-25 05:24:06',
                    'date' => '2006-12-25',
                    'modified' => '2006-12-25 05:29:16',
                    'mytime' => '22:57:17',
                    'Parent' => [
                        'id' => 5,
                        'apple_id' => 5,
                        'color' => 'Green',
                        'name' => 'Blue Green',
                        'created' => '2006-12-25 05:24:06',
                        'date' => '2006-12-25',
                        'modified' => '2006-12-25 05:29:16',
                        'mytime' => '22:57:17',
                    ],
                    'Sample' => [
                        'id' => 4,
                        'apple_id' => 5,
                        'name' => 'sample4',
                    ],
                    'Child' => [
                        [
                            'id' => 5,
                            'apple_id' => 5,
                            'color' => 'Green',
                            'name' => 'Blue Green',
                            'created' => '2006-12-25 05:24:06',
                            'date' => '2006-12-25',
                            'modified' => '2006-12-25 05:29:16',
                            'mytime' => '22:57:17',
                ], ], ],
                'Sample' => [
                    'id' => 4,
                    'apple_id' => 5,
                    'name' => 'sample4',
            ], ],
            [
                'Apple' => [
                    'id' => 6,
                    'apple_id' => 4,
                    'color' => 'My new appleOrange',
                    'name' => 'My new apple',
                    'created' => '2006-12-25 05:29:39',
                    'date' => '2006-12-25',
                    'modified' => '2006-12-25 05:29:39',
                    'mytime' => '22:57:17',
                ],
                'Parent' => [
                    'id' => 4,
                    'apple_id' => 2,
                    'color' => 'Blue Green',
                    'name' => 'Test Name',
                    'created' => '2006-12-25 05:23:36',
                    'date' => '2006-12-25',
                    'modified' => '2006-12-25 05:23:36',
                    'mytime' => '22:57:17',
                    'Parent' => [
                        'id' => 2,
                        'apple_id' => 1,
                        'color' => 'Bright Red 1',
                        'name' => 'Bright Red Apple',
                        'created' => '2006-11-22 10:43:13',
                        'date' => '2014-01-01',
                        'modified' => '2006-11-30 18:38:10',
                        'mytime' => '22:57:17',
                    ],
                    'Sample' => [
                        'id' => 3,
                        'apple_id' => 4,
                        'name' => 'sample3',
                    ],
                    'Child' => [
                        [
                            'id' => 6,
                            'apple_id' => 4,
                            'color' => 'My new appleOrange',
                            'name' => 'My new apple',
                            'created' => '2006-12-25 05:29:39',
                            'date' => '2006-12-25',
                            'modified' => '2006-12-25 05:29:39',
                            'mytime' => '22:57:17',
                ], ], ],
                'Sample' => [
                    'id' => '',
                    'apple_id' => '',
                    'name' => '',
            ], ],
            [
                'Apple' => [
                    'id' => 7,
                    'apple_id' => 6,
                    'color' => 'Some wierd color',
                    'name' => 'Some odd color',
                    'created' => '2006-12-25 05:34:21',
                    'date' => '2006-12-25',
                    'modified' => '2006-12-25 05:34:21',
                    'mytime' => '22:57:17',
                ],
                'Parent' => [
                    'id' => 6,
                    'apple_id' => 4,
                    'color' => 'My new appleOrange',
                    'name' => 'My new apple',
                    'created' => '2006-12-25 05:29:39',
                    'date' => '2006-12-25',
                    'modified' => '2006-12-25 05:29:39',
                    'mytime' => '22:57:17',
                    'Parent' => [
                        'id' => 4,
                        'apple_id' => 2,
                        'color' => 'Blue Green',
                        'name' => 'Test Name',
                        'created' => '2006-12-25 05:23:36',
                        'date' => '2006-12-25',
                        'modified' => '2006-12-25 05:23:36',
                        'mytime' => '22:57:17',
                    ],
                    'Sample' => [],
                    'Child' => [
                        [
                            'id' => 7,
                            'apple_id' => 6,
                            'color' => 'Some wierd color',
                            'name' => 'Some odd color',
                            'created' => '2006-12-25 05:34:21',
                            'date' => '2006-12-25',
                            'modified' => '2006-12-25 05:34:21',
                            'mytime' => '22:57:17',
                ], ], ],
                'Sample' => [
                    'id' => '',
                    'apple_id' => '',
                    'name' => '',
        ], ], ];
        $this->assertEqual($result, $expected);

        $result = $TestModel->Parent->unbindModel(['belongsTo' => ['Parent']]);
        $this->assertTrue($result);

        $result = $TestModel->unbindModel(['hasMany' => ['Child']]);
        $this->assertTrue($result);

        $result = $TestModel->find('all');
        $expected = [
            [
                'Apple' => [
                    'id' => 1,
                    'apple_id' => 2,
                    'color' => 'Red 1',
                    'name' => 'Red Apple 1',
                    'created' => '2006-11-22 10:38:58',
                    'date' => '1951-01-04',
                    'modified' => '2006-12-01 13:31:26',
                    'mytime' => '22:57:17',
                ],
                'Parent' => [
                    'id' => 2,
                    'apple_id' => 1,
                    'color' => 'Bright Red 1',
                    'name' => 'Bright Red Apple',
                    'created' => '2006-11-22 10:43:13',
                    'date' => '2014-01-01',
                    'modified' => '2006-11-30 18:38:10',
                    'mytime' => '22:57:17',
                    'Sample' => [
                        'id' => 2,
                        'apple_id' => 2,
                        'name' => 'sample2',
                    ],
                    'Child' => [
                        [
                            'id' => 1,
                            'apple_id' => 2,
                            'color' => 'Red 1',
                            'name' => 'Red Apple 1',
                            'created' => '2006-11-22 10:38:58',
                            'date' => '1951-01-04',
                            'modified' => '2006-12-01 13:31:26',
                            'mytime' => '22:57:17',
                        ],
                        [
                            'id' => 3,
                            'apple_id' => 2,
                            'color' => 'blue green',
                            'name' => 'green blue',
                            'created' => '2006-12-25 05:13:36',
                            'date' => '2006-12-25',
                            'modified' => '2006-12-25 05:23:24',
                            'mytime' => '22:57:17',
                        ],
                        [
                            'id' => 4,
                            'apple_id' => 2,
                            'color' => 'Blue Green',
                            'name' => 'Test Name',
                            'created' => '2006-12-25 05:23:36',
                            'date' => '2006-12-25',
                            'modified' => '2006-12-25 05:23:36',
                            'mytime' => '22:57:17',
                ], ], ],
                'Sample' => [
                    'id' => '',
                    'apple_id' => '',
                    'name' => '',
            ], ],
            [
                'Apple' => [
                    'id' => 2,
                    'apple_id' => 1,
                    'color' => 'Bright Red 1',
                    'name' => 'Bright Red Apple',
                    'created' => '2006-11-22 10:43:13',
                    'date' => '2014-01-01',
                    'modified' => '2006-11-30 18:38:10',
                    'mytime' => '22:57:17',
                ],
                'Parent' => [
                    'id' => 1,
                    'apple_id' => 2,
                    'color' => 'Red 1',
                    'name' => 'Red Apple 1',
                    'created' => '2006-11-22 10:38:58',
                    'date' => '1951-01-04',
                    'modified' => '2006-12-01 13:31:26',
                    'mytime' => '22:57:17',
                    'Sample' => [],
                        'Child' => [
                            [
                                'id' => 2,
                                'apple_id' => 1,
                                'color' => 'Bright Red 1',
                                'name' => 'Bright Red Apple',
                                'created' => '2006-11-22 10:43:13',
                                'date' => '2014-01-01',
                                'modified' => '2006-11-30 18:38:10',
                                'mytime' => '22:57:17',
                ], ], ],
                'Sample' => [
                    'id' => 2,
                    'apple_id' => 2,
                    'name' => 'sample2',
                    'Apple' => [
                        'id' => 2,
                        'apple_id' => 1,
                        'color' => 'Bright Red 1',
                        'name' => 'Bright Red Apple',
                        'created' => '2006-11-22 10:43:13',
                        'date' => '2014-01-01',
                        'modified' => '2006-11-30 18:38:10',
                        'mytime' => '22:57:17',
            ], ], ],
            [
                'Apple' => [
                    'id' => 3,
                    'apple_id' => 2,
                    'color' => 'blue green',
                    'name' => 'green blue',
                    'created' => '2006-12-25 05:13:36',
                    'date' => '2006-12-25',
                    'modified' => '2006-12-25 05:23:24',
                    'mytime' => '22:57:17',
                ],
                'Parent' => [
                    'id' => 2,
                    'apple_id' => 1,
                    'color' => 'Bright Red 1',
                    'name' => 'Bright Red Apple',
                    'created' => '2006-11-22 10:43:13',
                    'date' => '2014-01-01',
                    'modified' => '2006-11-30 18:38:10',
                    'mytime' => '22:57:17',
                    'Sample' => [
                        'id' => 2,
                        'apple_id' => 2,
                        'name' => 'sample2',
                    ],
                    'Child' => [
                        [
                            'id' => 1,
                            'apple_id' => 2,
                            'color' => 'Red 1',
                            'name' => 'Red Apple 1',
                            'created' => '2006-11-22 10:38:58',
                            'date' => '1951-01-04',
                            'modified' => '2006-12-01 13:31:26',
                            'mytime' => '22:57:17',
                        ],
                        [
                            'id' => 3,
                            'apple_id' => 2,
                            'color' => 'blue green',
                            'name' => 'green blue',
                            'created' => '2006-12-25 05:13:36',
                            'date' => '2006-12-25',
                            'modified' => '2006-12-25 05:23:24',
                            'mytime' => '22:57:17',
                        ],
                        [
                            'id' => 4,
                            'apple_id' => 2,
                            'color' => 'Blue Green',
                            'name' => 'Test Name',
                            'created' => '2006-12-25 05:23:36',
                            'date' => '2006-12-25',
                            'modified' => '2006-12-25 05:23:36',
                            'mytime' => '22:57:17',
                ], ], ],
                'Sample' => [
                    'id' => 1,
                    'apple_id' => 3,
                    'name' => 'sample1',
                    'Apple' => [
                        'id' => 3,
                        'apple_id' => 2,
                        'color' => 'blue green',
                        'name' => 'green blue',
                        'created' => '2006-12-25 05:13:36',
                        'date' => '2006-12-25',
                        'modified' => '2006-12-25 05:23:24',
                        'mytime' => '22:57:17',
            ], ], ],
            [
                'Apple' => [
                    'id' => 4,
                    'apple_id' => 2,
                    'color' => 'Blue Green',
                    'name' => 'Test Name',
                    'created' => '2006-12-25 05:23:36',
                    'date' => '2006-12-25',
                    'modified' => '2006-12-25 05:23:36',
                    'mytime' => '22:57:17',
                ],
                'Parent' => [
                    'id' => 2,
                    'apple_id' => 1,
                    'color' => 'Bright Red 1',
                    'name' => 'Bright Red Apple',
                    'created' => '2006-11-22 10:43:13',
                    'date' => '2014-01-01',
                    'modified' => '2006-11-30 18:38:10',
                    'mytime' => '22:57:17',
                    'Sample' => [
                        'id' => 2,
                        'apple_id' => 2,
                        'name' => 'sample2',
                    ],
                    'Child' => [
                        [
                            'id' => 1,
                            'apple_id' => 2,
                            'color' => 'Red 1',
                            'name' => 'Red Apple 1',
                            'created' => '2006-11-22 10:38:58',
                            'date' => '1951-01-04',
                            'modified' => '2006-12-01 13:31:26',
                            'mytime' => '22:57:17',
                        ],
                        [
                            'id' => 3,
                            'apple_id' => 2,
                            'color' => 'blue green',
                            'name' => 'green blue',
                            'created' => '2006-12-25 05:13:36',
                            'date' => '2006-12-25',
                            'modified' => '2006-12-25 05:23:24',
                            'mytime' => '22:57:17',
                        ],
                        [
                            'id' => 4,
                            'apple_id' => 2,
                            'color' => 'Blue Green',
                            'name' => 'Test Name',
                            'created' => '2006-12-25 05:23:36',
                            'date' => '2006-12-25',
                            'modified' => '2006-12-25 05:23:36',
                            'mytime' => '22:57:17',
                ], ], ],
                'Sample' => [
                    'id' => 3,
                    'apple_id' => 4,
                    'name' => 'sample3',
                    'Apple' => [
                        'id' => 4,
                        'apple_id' => 2,
                        'color' => 'Blue Green',
                        'name' => 'Test Name',
                        'created' => '2006-12-25 05:23:36',
                        'date' => '2006-12-25',
                        'modified' => '2006-12-25 05:23:36',
                        'mytime' => '22:57:17',
            ], ], ],
            [
                'Apple' => [
                    'id' => 5,
                    'apple_id' => 5,
                    'color' => 'Green',
                    'name' => 'Blue Green',
                    'created' => '2006-12-25 05:24:06',
                    'date' => '2006-12-25',
                    'modified' => '2006-12-25 05:29:16',
                    'mytime' => '22:57:17',
                ],
                'Parent' => [
                    'id' => 5,
                    'apple_id' => 5,
                    'color' => 'Green',
                    'name' => 'Blue Green',
                    'created' => '2006-12-25 05:24:06',
                    'date' => '2006-12-25',
                    'modified' => '2006-12-25 05:29:16',
                    'mytime' => '22:57:17',
                    'Sample' => [
                        'id' => 4,
                        'apple_id' => 5,
                        'name' => 'sample4',
                    ],
                    'Child' => [
                        [
                            'id' => 5,
                            'apple_id' => 5,
                            'color' => 'Green',
                            'name' => 'Blue Green',
                            'created' => '2006-12-25 05:24:06',
                            'date' => '2006-12-25',
                            'modified' => '2006-12-25 05:29:16',
                            'mytime' => '22:57:17',
                ], ], ],
                'Sample' => [
                    'id' => 4,
                    'apple_id' => 5,
                    'name' => 'sample4',
                    'Apple' => [
                        'id' => 5,
                        'apple_id' => 5,
                        'color' => 'Green',
                        'name' => 'Blue Green',
                        'created' => '2006-12-25 05:24:06',
                        'date' => '2006-12-25',
                        'modified' => '2006-12-25 05:29:16',
                        'mytime' => '22:57:17',
            ], ], ],
            [
                'Apple' => [
                    'id' => 6,
                    'apple_id' => 4,
                    'color' => 'My new appleOrange',
                    'name' => 'My new apple',
                    'created' => '2006-12-25 05:29:39',
                    'date' => '2006-12-25',
                    'modified' => '2006-12-25 05:29:39',
                    'mytime' => '22:57:17', ],
                    'Parent' => [
                        'id' => 4,
                        'apple_id' => 2,
                        'color' => 'Blue Green',
                        'name' => 'Test Name',
                        'created' => '2006-12-25 05:23:36',
                        'date' => '2006-12-25',
                        'modified' => '2006-12-25 05:23:36',
                        'mytime' => '22:57:17',
                        'Sample' => [
                            'id' => 3,
                            'apple_id' => 4,
                            'name' => 'sample3',
                        ],
                        'Child' => [
                            [
                                'id' => 6,
                                'apple_id' => 4,
                                'color' => 'My new appleOrange',
                                'name' => 'My new apple',
                                'created' => '2006-12-25 05:29:39',
                                'date' => '2006-12-25',
                                'modified' => '2006-12-25 05:29:39',
                                'mytime' => '22:57:17',
                    ], ], ],
                    'Sample' => [
                        'id' => '',
                        'apple_id' => '',
                        'name' => '',
            ], ],
            [
                'Apple' => [
                    'id' => 7,
                    'apple_id' => 6,
                    'color' => 'Some wierd color',
                    'name' => 'Some odd color',
                    'created' => '2006-12-25 05:34:21',
                    'date' => '2006-12-25',
                    'modified' => '2006-12-25 05:34:21',
                    'mytime' => '22:57:17',
                ],
                'Parent' => [
                    'id' => 6,
                    'apple_id' => 4,
                    'color' => 'My new appleOrange',
                    'name' => 'My new apple',
                    'created' => '2006-12-25 05:29:39',
                    'date' => '2006-12-25',
                    'modified' => '2006-12-25 05:29:39',
                    'mytime' => '22:57:17',
                    'Sample' => [],
                    'Child' => [
                        [
                            'id' => 7,
                            'apple_id' => 6,
                            'color' => 'Some wierd color',
                            'name' => 'Some odd color',
                            'created' => '2006-12-25 05:34:21',
                            'date' => '2006-12-25', 'modified' => '2006-12-25 05:34:21',
                            'mytime' => '22:57:17',
                ], ], ],
                'Sample' => [
                    'id' => '',
                    'apple_id' => '',
                    'name' => '',
        ], ], ];
        $this->assertEqual($result, $expected);
    }

    /**
     * testSelfAssociationAfterFind method.
     */
    public function testSelfAssociationAfterFind()
    {
        $this->loadFixtures('Apple');
        $afterFindModel = new NodeAfterFind();
        $afterFindModel->recursive = 3;
        $afterFindData = $afterFindModel->find('all');

        $duplicateModel = new NodeAfterFind();
        $duplicateModel->recursive = 3;
        $duplicateModelData = $duplicateModel->find('all');

        $noAfterFindModel = new NodeNoAfterFind();
        $noAfterFindModel->recursive = 3;
        $noAfterFindData = $noAfterFindModel->find('all');

        $this->assertFalse($afterFindModel == $noAfterFindModel);
        // Limitation of PHP 4 and PHP 5 > 5.1.6 when comparing recursive objects
        if (PHP_VERSION === '5.1.6') {
            $this->assertFalse($afterFindModel != $duplicateModel);
        }
        $this->assertEqual($afterFindData, $noAfterFindData);
    }

    /**
     * testFindAllThreaded method.
     */
    public function testFindAllThreaded()
    {
        $this->loadFixtures('Category');
        $TestModel = new Category();

        $result = $TestModel->find('threaded');
        $expected = [
            [
                'Category' => [
                    'id' => '1',
                    'parent_id' => '0',
                    'name' => 'Category 1',
                    'created' => '2007-03-18 15:30:23',
                    'updated' => '2007-03-18 15:32:31',
                ],
                'children' => [
                    [
                        'Category' => [
                            'id' => '2',
                            'parent_id' => '1',
                            'name' => 'Category 1.1',
                            'created' => '2007-03-18 15:30:23',
                            'updated' => '2007-03-18 15:32:31',
                        ],
                        'children' => [
                            ['Category' => [
                                'id' => '7',
                                'parent_id' => '2',
                                'name' => 'Category 1.1.1',
                                'created' => '2007-03-18 15:30:23',
                                'updated' => '2007-03-18 15:32:31', ],
                                'children' => [], ],
                            ['Category' => [
                                'id' => '8',
                                'parent_id' => '2',
                                'name' => 'Category 1.1.2',
                                'created' => '2007-03-18 15:30:23',
                                'updated' => '2007-03-18 15:32:31', ],
                                'children' => [], ], ],
                    ],
                    [
                        'Category' => [
                            'id' => '3',
                            'parent_id' => '1',
                            'name' => 'Category 1.2',
                            'created' => '2007-03-18 15:30:23',
                            'updated' => '2007-03-18 15:32:31',
                        ],
                        'children' => [],
                    ],
                ],
            ],
            [
                'Category' => [
                    'id' => '4',
                    'parent_id' => '0',
                    'name' => 'Category 2',
                    'created' => '2007-03-18 15:30:23',
                    'updated' => '2007-03-18 15:32:31',
                ],
                'children' => [],
            ],
            [
                'Category' => [
                    'id' => '5',
                    'parent_id' => '0',
                    'name' => 'Category 3',
                    'created' => '2007-03-18 15:30:23',
                    'updated' => '2007-03-18 15:32:31',
                ],
                'children' => [
                    [
                        'Category' => [
                            'id' => '6',
                            'parent_id' => '5',
                            'name' => 'Category 3.1',
                            'created' => '2007-03-18 15:30:23',
                            'updated' => '2007-03-18 15:32:31',
                        ],
                        'children' => [],
                    ],
                ],
            ],
        ];
        $this->assertEqual($result, $expected);

        $result = $TestModel->find('threaded', [
            'conditions' => ['Category.name LIKE' => 'Category 1%'],
        ]);

        $expected = [
            [
                'Category' => [
                    'id' => '1',
                    'parent_id' => '0',
                    'name' => 'Category 1',
                    'created' => '2007-03-18 15:30:23',
                    'updated' => '2007-03-18 15:32:31',
                ],
                'children' => [
                    [
                        'Category' => [
                            'id' => '2',
                            'parent_id' => '1',
                            'name' => 'Category 1.1',
                            'created' => '2007-03-18 15:30:23',
                            'updated' => '2007-03-18 15:32:31',
                        ],
                        'children' => [
                            ['Category' => [
                                'id' => '7',
                                'parent_id' => '2',
                                'name' => 'Category 1.1.1',
                                'created' => '2007-03-18 15:30:23',
                                'updated' => '2007-03-18 15:32:31', ],
                                'children' => [], ],
                            ['Category' => [
                                'id' => '8',
                                'parent_id' => '2',
                                'name' => 'Category 1.1.2',
                                'created' => '2007-03-18 15:30:23',
                                'updated' => '2007-03-18 15:32:31', ],
                                'children' => [], ], ],
                    ],
                    [
                        'Category' => [
                            'id' => '3',
                            'parent_id' => '1',
                            'name' => 'Category 1.2',
                            'created' => '2007-03-18 15:30:23',
                            'updated' => '2007-03-18 15:32:31',
                        ],
                        'children' => [],
                    ],
                ],
            ],
        ];
        $this->assertEqual($result, $expected);

        $result = $TestModel->find('threaded', [
            'fields' => 'id, parent_id, name',
        ]);

        $expected = [
            [
                'Category' => [
                    'id' => '1',
                    'parent_id' => '0',
                    'name' => 'Category 1',
                ],
                'children' => [
                    [
                        'Category' => [
                            'id' => '2',
                            'parent_id' => '1',
                            'name' => 'Category 1.1',
                        ],
                        'children' => [
                            ['Category' => [
                                'id' => '7',
                                'parent_id' => '2',
                                'name' => 'Category 1.1.1', ],
                                'children' => [], ],
                            ['Category' => [
                                'id' => '8',
                                'parent_id' => '2',
                                'name' => 'Category 1.1.2', ],
                                'children' => [], ], ],
                    ],
                    [
                        'Category' => [
                            'id' => '3',
                            'parent_id' => '1',
                            'name' => 'Category 1.2',
                        ],
                        'children' => [],
                    ],
                ],
            ],
            [
                'Category' => [
                    'id' => '4',
                    'parent_id' => '0',
                    'name' => 'Category 2',
                ],
                'children' => [],
            ],
            [
                'Category' => [
                    'id' => '5',
                    'parent_id' => '0',
                    'name' => 'Category 3',
                ],
                'children' => [
                    [
                        'Category' => [
                            'id' => '6',
                            'parent_id' => '5',
                            'name' => 'Category 3.1',
                        ],
                        'children' => [],
                    ],
                ],
            ],
        ];
        $this->assertEqual($result, $expected);

        $result = $TestModel->find('threaded', ['order' => 'id DESC']);

        $expected = [
            [
                'Category' => [
                    'id' => 5,
                    'parent_id' => 0,
                    'name' => 'Category 3',
                    'created' => '2007-03-18 15:30:23',
                    'updated' => '2007-03-18 15:32:31',
                ],
                'children' => [
                    [
                        'Category' => [
                            'id' => 6,
                            'parent_id' => 5,
                            'name' => 'Category 3.1',
                            'created' => '2007-03-18 15:30:23',
                            'updated' => '2007-03-18 15:32:31',
                        ],
                        'children' => [],
                    ],
                ],
            ],
            [
                'Category' => [
                    'id' => 4,
                    'parent_id' => 0,
                    'name' => 'Category 2',
                    'created' => '2007-03-18 15:30:23',
                    'updated' => '2007-03-18 15:32:31',
                ],
                'children' => [],
            ],
            [
                'Category' => [
                    'id' => 1,
                    'parent_id' => 0,
                    'name' => 'Category 1',
                    'created' => '2007-03-18 15:30:23',
                    'updated' => '2007-03-18 15:32:31',
                ],
                'children' => [
                    [
                        'Category' => [
                            'id' => 3,
                            'parent_id' => 1,
                            'name' => 'Category 1.2',
                            'created' => '2007-03-18 15:30:23',
                            'updated' => '2007-03-18 15:32:31',
                        ],
                        'children' => [],
                    ],
                    [
                        'Category' => [
                            'id' => 2,
                            'parent_id' => 1,
                            'name' => 'Category 1.1',
                            'created' => '2007-03-18 15:30:23',
                            'updated' => '2007-03-18 15:32:31',
                        ],
                        'children' => [
                            ['Category' => [
                                'id' => '8',
                                'parent_id' => '2',
                                'name' => 'Category 1.1.2',
                                'created' => '2007-03-18 15:30:23',
                                'updated' => '2007-03-18 15:32:31', ],
                                'children' => [], ],
                            ['Category' => [
                                'id' => '7',
                                'parent_id' => '2',
                                'name' => 'Category 1.1.1',
                                'created' => '2007-03-18 15:30:23',
                                'updated' => '2007-03-18 15:32:31', ],
                                'children' => [], ], ],
                    ],
                ],
            ],
        ];
        $this->assertEqual($result, $expected);

        $result = $TestModel->find('threaded', [
            'conditions' => ['Category.name LIKE' => 'Category 3%'],
        ]);
        $expected = [
            [
                'Category' => [
                    'id' => '5',
                    'parent_id' => '0',
                    'name' => 'Category 3',
                    'created' => '2007-03-18 15:30:23',
                    'updated' => '2007-03-18 15:32:31',
                ],
                'children' => [
                    [
                        'Category' => [
                            'id' => '6',
                            'parent_id' => '5',
                            'name' => 'Category 3.1',
                            'created' => '2007-03-18 15:30:23',
                            'updated' => '2007-03-18 15:32:31',
                        ],
                        'children' => [],
                    ],
                ],
            ],
        ];
        $this->assertEqual($result, $expected);

        $result = $TestModel->find('threaded', [
            'conditions' => ['Category.name LIKE' => 'Category 1.1%'],
        ]);
        $expected = [
                ['Category' => [
                        'id' => '2',
                        'parent_id' => '1',
                        'name' => 'Category 1.1',
                        'created' => '2007-03-18 15:30:23',
                        'updated' => '2007-03-18 15:32:31', ],
                        'children' => [
                            ['Category' => [
                                'id' => '7',
                                'parent_id' => '2',
                                'name' => 'Category 1.1.1',
                                'created' => '2007-03-18 15:30:23',
                                'updated' => '2007-03-18 15:32:31', ],
                                'children' => [], ],
                            ['Category' => [
                                'id' => '8',
                                'parent_id' => '2',
                                'name' => 'Category 1.1.2',
                                'created' => '2007-03-18 15:30:23',
                                'updated' => '2007-03-18 15:32:31', ],
                                'children' => [], ], ], ], ];
        $this->assertEqual($result, $expected);

        $result = $TestModel->find('threaded', [
            'fields' => 'id, parent_id, name',
            'conditions' => ['Category.id !=' => 2],
        ]);
        $expected = [
            [
                'Category' => [
                    'id' => '1',
                    'parent_id' => '0',
                    'name' => 'Category 1',
                ],
                'children' => [
                    [
                        'Category' => [
                            'id' => '3',
                            'parent_id' => '1',
                            'name' => 'Category 1.2',
                        ],
                        'children' => [],
                    ],
                ],
            ],
            [
                'Category' => [
                    'id' => '4',
                    'parent_id' => '0',
                    'name' => 'Category 2',
                ],
                'children' => [],
            ],
            [
                'Category' => [
                    'id' => '5',
                    'parent_id' => '0',
                    'name' => 'Category 3',
                ],
                'children' => [
                    [
                        'Category' => [
                            'id' => '6',
                            'parent_id' => '5',
                            'name' => 'Category 3.1',
                        ],
                        'children' => [],
                    ],
                ],
            ],
        ];
        $this->assertEqual($result, $expected);

        $result = $TestModel->find('all', [
            'fields' => 'id, name, parent_id',
            'conditions' => ['Category.id !=' => 1],
        ]);
        $expected = [
            ['Category' => [
                'id' => '2',
                'name' => 'Category 1.1',
                'parent_id' => '1',
            ]],
            ['Category' => [
                'id' => '3',
                'name' => 'Category 1.2',
                'parent_id' => '1',
            ]],
            ['Category' => [
                'id' => '4',
                'name' => 'Category 2',
                'parent_id' => '0',
            ]],
            ['Category' => [
                'id' => '5',
                'name' => 'Category 3',
                'parent_id' => '0',
            ]],
            ['Category' => [
                'id' => '6',
                'name' => 'Category 3.1',
                'parent_id' => '5',
            ]],
            ['Category' => [
                'id' => '7',
                'name' => 'Category 1.1.1',
                'parent_id' => '2',
            ]],
            ['Category' => [
                'id' => '8',
                'name' => 'Category 1.1.2',
                'parent_id' => '2',
        ]], ];
        $this->assertEqual($result, $expected);

        $result = $TestModel->find('threaded', [
            'fields' => 'id, parent_id, name',
            'conditions' => ['Category.id !=' => 1],
        ]);
        $expected = [
            [
                'Category' => [
                    'id' => '2',
                    'parent_id' => '1',
                    'name' => 'Category 1.1',
                ],
                'children' => [
                    ['Category' => [
                        'id' => '7',
                        'parent_id' => '2',
                        'name' => 'Category 1.1.1', ],
                        'children' => [], ],
                    ['Category' => [
                        'id' => '8',
                        'parent_id' => '2',
                        'name' => 'Category 1.1.2', ],
                        'children' => [], ], ],
            ],
            [
                'Category' => [
                    'id' => '3',
                    'parent_id' => '1',
                    'name' => 'Category 1.2',
                ],
                'children' => [],
            ],
        ];
        $this->assertEqual($result, $expected);
    }

    /**
     * test find('neighbors').
     */
    public function testFindNeighbors()
    {
        $this->loadFixtures('User', 'Article');
        $TestModel = new Article();

        $TestModel->id = 1;
        $result = $TestModel->find('neighbors', ['fields' => ['id']]);
        $expected = [
            'prev' => null,
            'next' => [
                'Article' => ['id' => 2],
        ], ];
        $this->assertEqual($result, $expected);

        $TestModel->id = 2;
        $result = $TestModel->find('neighbors', [
            'fields' => ['id'],
        ]);

        $expected = [
            'prev' => [
                'Article' => [
                    'id' => 1,
            ], ],
            'next' => [
                'Article' => [
                    'id' => 3,
        ], ], ];
        $this->assertEqual($result, $expected);

        $TestModel->id = 3;
        $result = $TestModel->find('neighbors', ['fields' => ['id']]);
        $expected = [
            'prev' => [
                'Article' => [
                    'id' => 2,
            ], ],
            'next' => null,
        ];
        $this->assertEqual($result, $expected);

        $TestModel->id = 1;
        $result = $TestModel->find('neighbors', ['recursive' => -1]);
        $expected = [
            'prev' => null,
            'next' => [
                'Article' => [
                    'id' => 2,
                    'user_id' => 3,
                    'title' => 'Second Article',
                    'body' => 'Second Article Body',
                    'published' => 'Y',
                    'created' => '2007-03-18 10:41:23',
                    'updated' => '2007-03-18 10:43:31',
                ],
            ],
        ];
        $this->assertEqual($result, $expected);

        $TestModel->id = 2;
        $result = $TestModel->find('neighbors', ['recursive' => -1]);
        $expected = [
            'prev' => [
                'Article' => [
                    'id' => 1,
                    'user_id' => 1,
                    'title' => 'First Article',
                    'body' => 'First Article Body',
                    'published' => 'Y',
                    'created' => '2007-03-18 10:39:23',
                    'updated' => '2007-03-18 10:41:31',
                ],
            ],
            'next' => [
                'Article' => [
                    'id' => 3,
                    'user_id' => 1,
                    'title' => 'Third Article',
                    'body' => 'Third Article Body',
                    'published' => 'Y',
                    'created' => '2007-03-18 10:43:23',
                    'updated' => '2007-03-18 10:45:31',
                ],
            ],
        ];
        $this->assertEqual($result, $expected);

        $TestModel->id = 3;
        $result = $TestModel->find('neighbors', ['recursive' => -1]);
        $expected = [
            'prev' => [
                'Article' => [
                    'id' => 2,
                    'user_id' => 3,
                    'title' => 'Second Article',
                    'body' => 'Second Article Body',
                    'published' => 'Y',
                    'created' => '2007-03-18 10:41:23',
                    'updated' => '2007-03-18 10:43:31',
                ],
            ],
            'next' => null,
        ];
        $this->assertEqual($result, $expected);

        $TestModel->recursive = 0;
        $TestModel->id = 1;
        $one = $TestModel->read();
        $TestModel->id = 2;
        $two = $TestModel->read();
        $TestModel->id = 3;
        $three = $TestModel->read();

        $TestModel->id = 1;
        $result = $TestModel->find('neighbors');
        $expected = ['prev' => null, 'next' => $two];
        $this->assertEqual($result, $expected);

        $TestModel->id = 2;
        $result = $TestModel->find('neighbors');
        $expected = ['prev' => $one, 'next' => $three];
        $this->assertEqual($result, $expected);

        $TestModel->id = 3;
        $result = $TestModel->find('neighbors');
        $expected = ['prev' => $two, 'next' => null];
        $this->assertEqual($result, $expected);

        $TestModel->recursive = 2;
        $TestModel->id = 1;
        $one = $TestModel->read();
        $TestModel->id = 2;
        $two = $TestModel->read();
        $TestModel->id = 3;
        $three = $TestModel->read();

        $TestModel->id = 1;
        $result = $TestModel->find('neighbors', ['recursive' => 2]);
        $expected = ['prev' => null, 'next' => $two];
        $this->assertEqual($result, $expected);

        $TestModel->id = 2;
        $result = $TestModel->find('neighbors', ['recursive' => 2]);
        $expected = ['prev' => $one, 'next' => $three];
        $this->assertEqual($result, $expected);

        $TestModel->id = 3;
        $result = $TestModel->find('neighbors', ['recursive' => 2]);
        $expected = ['prev' => $two, 'next' => null];
        $this->assertEqual($result, $expected);
    }

    /**
     * testFindCombinedRelations method.
     */
    public function testFindCombinedRelations()
    {
        $this->loadFixtures('Apple', 'Sample');
        $TestModel = new Apple();

        $result = $TestModel->find('all');

        $expected = [
            [
                'Apple' => [
                    'id' => '1',
                    'apple_id' => '2',
                    'color' => 'Red 1',
                    'name' => 'Red Apple 1',
                    'created' => '2006-11-22 10:38:58',
                    'date' => '1951-01-04',
                    'modified' => '2006-12-01 13:31:26',
                    'mytime' => '22:57:17',
                ],
                'Parent' => [
                    'id' => '2',
                    'apple_id' => '1',
                    'color' => 'Bright Red 1',
                    'name' => 'Bright Red Apple',
                    'created' => '2006-11-22 10:43:13',
                    'date' => '2014-01-01',
                    'modified' => '2006-11-30 18:38:10',
                    'mytime' => '22:57:17',
                ],
                'Sample' => [
                    'id' => null,
                    'apple_id' => null,
                    'name' => null,
                ],
                'Child' => [
                    [
                        'id' => '2',
                        'apple_id' => '1',
                        'color' => 'Bright Red 1',
                        'name' => 'Bright Red Apple',
                        'created' => '2006-11-22 10:43:13',
                        'date' => '2014-01-01',
                        'modified' => '2006-11-30 18:38:10',
                        'mytime' => '22:57:17',
            ], ], ],
            [
                'Apple' => [
                    'id' => '2',
                    'apple_id' => '1',
                    'color' => 'Bright Red 1',
                    'name' => 'Bright Red Apple',
                    'created' => '2006-11-22 10:43:13',
                    'date' => '2014-01-01',
                    'modified' => '2006-11-30 18:38:10',
                    'mytime' => '22:57:17',
                ],
                'Parent' => [
                    'id' => '1',
                    'apple_id' => '2',
                    'color' => 'Red 1',
                    'name' => 'Red Apple 1',
                    'created' => '2006-11-22 10:38:58',
                    'date' => '1951-01-04',
                    'modified' => '2006-12-01 13:31:26',
                    'mytime' => '22:57:17',
                ],
                'Sample' => [
                    'id' => '2',
                    'apple_id' => '2',
                    'name' => 'sample2',
                ],
                'Child' => [
                    [
                        'id' => '1',
                        'apple_id' => '2',
                        'color' => 'Red 1',
                        'name' => 'Red Apple 1',
                        'created' => '2006-11-22 10:38:58',
                        'date' => '1951-01-04',
                        'modified' => '2006-12-01 13:31:26',
                        'mytime' => '22:57:17',
                    ],
                    [
                        'id' => '3',
                        'apple_id' => '2',
                        'color' => 'blue green',
                        'name' => 'green blue',
                        'created' => '2006-12-25 05:13:36',
                        'date' => '2006-12-25',
                        'modified' => '2006-12-25 05:23:24',
                        'mytime' => '22:57:17',
                    ],
                    [
                        'id' => '4',
                        'apple_id' => '2',
                        'color' => 'Blue Green',
                        'name' => 'Test Name',
                        'created' => '2006-12-25 05:23:36',
                        'date' => '2006-12-25',
                        'modified' => '2006-12-25 05:23:36',
                        'mytime' => '22:57:17',
            ], ], ],
            [
                'Apple' => [
                    'id' => '3',
                    'apple_id' => '2',
                    'color' => 'blue green',
                    'name' => 'green blue',
                    'created' => '2006-12-25 05:13:36',
                    'date' => '2006-12-25',
                    'modified' => '2006-12-25 05:23:24',
                    'mytime' => '22:57:17',
                ],
                'Parent' => [
                    'id' => '2',
                    'apple_id' => '1',
                    'color' => 'Bright Red 1',
                    'name' => 'Bright Red Apple',
                    'created' => '2006-11-22 10:43:13',
                    'date' => '2014-01-01',
                    'modified' => '2006-11-30 18:38:10',
                    'mytime' => '22:57:17',
                ],
                'Sample' => [
                    'id' => '1',
                    'apple_id' => '3',
                    'name' => 'sample1',
                ],
                'Child' => [],
            ],
            [
                'Apple' => [
                    'id' => '4',
                    'apple_id' => '2',
                    'color' => 'Blue Green',
                    'name' => 'Test Name',
                    'created' => '2006-12-25 05:23:36',
                    'date' => '2006-12-25',
                    'modified' => '2006-12-25 05:23:36',
                    'mytime' => '22:57:17',
                ],
                'Parent' => [
                    'id' => '2',
                    'apple_id' => '1',
                    'color' => 'Bright Red 1',
                    'name' => 'Bright Red Apple',
                    'created' => '2006-11-22 10:43:13',
                    'date' => '2014-01-01',
                    'modified' => '2006-11-30 18:38:10',
                    'mytime' => '22:57:17',
                ],
                'Sample' => [
                    'id' => '3',
                    'apple_id' => '4',
                    'name' => 'sample3',
                ],
                'Child' => [
                    [
                        'id' => '6',
                        'apple_id' => '4',
                        'color' => 'My new appleOrange',
                        'name' => 'My new apple',
                        'created' => '2006-12-25 05:29:39',
                        'date' => '2006-12-25',
                        'modified' => '2006-12-25 05:29:39',
                        'mytime' => '22:57:17',
            ], ], ],
            [
                'Apple' => [
                    'id' => '5',
                    'apple_id' => '5',
                    'color' => 'Green',
                    'name' => 'Blue Green',
                    'created' => '2006-12-25 05:24:06',
                    'date' => '2006-12-25',
                    'modified' => '2006-12-25 05:29:16',
                    'mytime' => '22:57:17',
                ],
                'Parent' => [
                    'id' => '5',
                    'apple_id' => '5',
                    'color' => 'Green',
                    'name' => 'Blue Green',
                    'created' => '2006-12-25 05:24:06',
                    'date' => '2006-12-25',
                    'modified' => '2006-12-25 05:29:16',
                    'mytime' => '22:57:17',
                ],
                'Sample' => [
                    'id' => '4',
                    'apple_id' => '5',
                    'name' => 'sample4',
                ],
                'Child' => [
                    [
                        'id' => '5',
                        'apple_id' => '5',
                        'color' => 'Green',
                        'name' => 'Blue Green',
                        'created' => '2006-12-25 05:24:06',
                        'date' => '2006-12-25',
                        'modified' => '2006-12-25 05:29:16',
                        'mytime' => '22:57:17',
            ], ], ],
            [
                'Apple' => [
                    'id' => '6',
                    'apple_id' => '4',
                    'color' => 'My new appleOrange',
                    'name' => 'My new apple',
                    'created' => '2006-12-25 05:29:39',
                    'date' => '2006-12-25',
                    'modified' => '2006-12-25 05:29:39',
                    'mytime' => '22:57:17',
                ],
                'Parent' => [
                    'id' => '4',
                    'apple_id' => '2',
                    'color' => 'Blue Green',
                    'name' => 'Test Name',
                    'created' => '2006-12-25 05:23:36',
                    'date' => '2006-12-25',
                    'modified' => '2006-12-25 05:23:36',
                    'mytime' => '22:57:17',
                ],
                'Sample' => [
                    'id' => null,
                    'apple_id' => null,
                    'name' => null,
                ],
                'Child' => [
                    [
                        'id' => '7',
                        'apple_id' => '6',
                        'color' => 'Some wierd color',
                        'name' => 'Some odd color',
                        'created' => '2006-12-25 05:34:21',
                        'date' => '2006-12-25',
                        'modified' => '2006-12-25 05:34:21',
                        'mytime' => '22:57:17',
            ], ], ],
            [
                'Apple' => [
                    'id' => '7',
                    'apple_id' => '6',
                    'color' => 'Some wierd color',
                    'name' => 'Some odd color',
                    'created' => '2006-12-25 05:34:21',
                    'date' => '2006-12-25',
                    'modified' => '2006-12-25 05:34:21',
                    'mytime' => '22:57:17',
                ],
                'Parent' => [
                    'id' => '6',
                    'apple_id' => '4',
                    'color' => 'My new appleOrange',
                    'name' => 'My new apple',
                    'created' => '2006-12-25 05:29:39',
                    'date' => '2006-12-25',
                    'modified' => '2006-12-25 05:29:39',
                    'mytime' => '22:57:17',
                ],
                'Sample' => [
                    'id' => null,
                    'apple_id' => null,
                    'name' => null,
                ],
                'Child' => [],
        ], ];
        $this->assertEqual($result, $expected);
    }

    /**
     * testSaveEmpty method.
     */
    public function testSaveEmpty()
    {
        $this->loadFixtures('Thread');
        $TestModel = new Thread();
        $data = [];
        $expected = $TestModel->save($data);
        $this->assertFalse($expected);
    }

    /**
     * testFindAllWithConditionInChildQuery.
     *
     * @todo external conditions like this are going to need to be revisited at some point
     */
    public function testFindAllWithConditionInChildQuery()
    {
        $this->loadFixtures('Basket', 'FilmFile');

        $TestModel = new Basket();
        $recursive = 3;
        $result = $TestModel->find('all', compact('conditions', 'recursive'));

        $expected = [
            [
                'Basket' => [
                    'id' => 1,
                    'type' => 'nonfile',
                    'name' => 'basket1',
                    'object_id' => 1,
                    'user_id' => 1,
                ],
                'FilmFile' => [
                    'id' => '',
                    'name' => '',
                ],
            ],
            [
                'Basket' => [
                    'id' => 2,
                    'type' => 'file',
                    'name' => 'basket2',
                    'object_id' => 2,
                    'user_id' => 1,
                ],
                'FilmFile' => [
                    'id' => 2,
                    'name' => 'two',
                ],
            ],
        ];
        $this->assertEqual($result, $expected);
    }

    /**
     * testFindAllWithConditionsHavingMixedDataTypes method.
     */
    public function testFindAllWithConditionsHavingMixedDataTypes()
    {
        $this->loadFixtures('Article');
        $TestModel = new Article();
        $expected = [
            [
                'Article' => [
                    'id' => 1,
                    'user_id' => 1,
                    'title' => 'First Article',
                    'body' => 'First Article Body',
                    'published' => 'Y',
                    'created' => '2007-03-18 10:39:23',
                    'updated' => '2007-03-18 10:41:31',
                ],
            ],
            [
                'Article' => [
                    'id' => 2,
                    'user_id' => 3,
                    'title' => 'Second Article',
                    'body' => 'Second Article Body',
                    'published' => 'Y',
                    'created' => '2007-03-18 10:41:23',
                    'updated' => '2007-03-18 10:43:31',
                ],
            ],
        ];
        $conditions = ['id' => ['1', 2]];
        $recursive = -1;
        $order = 'Article.id ASC';
        $result = $TestModel->find('all', compact('conditions', 'recursive', 'order'));
        $this->assertEqual($result, $expected);

        if ($this->skipIf('postgres' == $this->db->config['driver'], 'The rest of testFindAllWithConditionsHavingMixedDataTypes test is not compatible with Postgres')) {
            return;
        }
        $conditions = ['id' => ['1', 2, '3.0']];
        $order = 'Article.id ASC';
        $result = $TestModel->find('all', compact('recursive', 'conditions', 'order'));
        $expected = [
            [
                'Article' => [
                    'id' => 1,
                    'user_id' => 1,
                    'title' => 'First Article',
                    'body' => 'First Article Body',
                    'published' => 'Y',
                    'created' => '2007-03-18 10:39:23',
                    'updated' => '2007-03-18 10:41:31',
                ],
            ],
            [
                'Article' => [
                    'id' => 2,
                    'user_id' => 3,
                    'title' => 'Second Article',
                    'body' => 'Second Article Body',
                    'published' => 'Y',
                    'created' => '2007-03-18 10:41:23',
                    'updated' => '2007-03-18 10:43:31',
                ],
            ],
            [
                'Article' => [
                    'id' => 3,
                    'user_id' => 1,
                    'title' => 'Third Article',
                    'body' => 'Third Article Body',
                    'published' => 'Y',
                    'created' => '2007-03-18 10:43:23',
                    'updated' => '2007-03-18 10:45:31',
                ],
            ],
        ];
        $this->assertEqual($result, $expected);
    }

    /**
     * testBindUnbind method.
     */
    public function testBindUnbind()
    {
        $this->loadFixtures('User', 'Comment', 'FeatureSet');
        $TestModel = new User();

        $result = $TestModel->hasMany;
        $expected = [];
        $this->assertEqual($result, $expected);

        $result = $TestModel->bindModel(['hasMany' => ['Comment']]);
        $this->assertTrue($result);

        $result = $TestModel->find('all', [
            'fields' => 'User.id, User.user',
        ]);
        $expected = [
            [
                'User' => [
                    'id' => '1',
                    'user' => 'mariano',
                ],
                'Comment' => [
                    [
                        'id' => '3',
                        'article_id' => '1',
                        'user_id' => '1',
                        'comment' => 'Third Comment for First Article',
                        'published' => 'Y',
                        'created' => '2007-03-18 10:49:23',
                        'updated' => '2007-03-18 10:51:31',
                    ],
                    [
                        'id' => '4',
                        'article_id' => '1',
                        'user_id' => '1',
                        'comment' => 'Fourth Comment for First Article',
                        'published' => 'N',
                        'created' => '2007-03-18 10:51:23',
                        'updated' => '2007-03-18 10:53:31',
                    ],
                    [
                        'id' => '5',
                        'article_id' => '2',
                        'user_id' => '1',
                        'comment' => 'First Comment for Second Article',
                        'published' => 'Y',
                        'created' => '2007-03-18 10:53:23',
                        'updated' => '2007-03-18 10:55:31',
            ], ], ],
            [
                'User' => [
                    'id' => '2',
                    'user' => 'nate',
                ],
                'Comment' => [
                    [
                        'id' => '1',
                        'article_id' => '1',
                        'user_id' => '2',
                        'comment' => 'First Comment for First Article',
                        'published' => 'Y',
                        'created' => '2007-03-18 10:45:23',
                        'updated' => '2007-03-18 10:47:31',
                    ],
                    [
                        'id' => '6',
                        'article_id' => '2',
                        'user_id' => '2',
                        'comment' => 'Second Comment for Second Article',
                        'published' => 'Y',
                        'created' => '2007-03-18 10:55:23',
                        'updated' => '2007-03-18 10:57:31',
            ], ], ],
            [
                'User' => [
                    'id' => '3',
                    'user' => 'larry',
                ],
                'Comment' => [],
            ],
            [
                'User' => [
                    'id' => '4',
                    'user' => 'garrett',
                ],
                'Comment' => [
                    [
                        'id' => '2',
                        'article_id' => '1',
                        'user_id' => '4',
                        'comment' => 'Second Comment for First Article',
                        'published' => 'Y',
                        'created' => '2007-03-18 10:47:23',
                        'updated' => '2007-03-18 10:49:31',
        ], ], ], ];

        $this->assertEqual($result, $expected);

        $TestModel->resetAssociations();
        $result = $TestModel->hasMany;
        $this->assertEqual($result, []);

        $result = $TestModel->bindModel(['hasMany' => ['Comment']], false);
        $this->assertTrue($result);

        $result = $TestModel->find('all', [
            'fields' => 'User.id, User.user',
        ]);

        $expected = [
            [
                'User' => [
                    'id' => '1',
                    'user' => 'mariano',
                ],
                'Comment' => [
                    [
                        'id' => '3',
                        'article_id' => '1',
                        'user_id' => '1',
                        'comment' => 'Third Comment for First Article',
                        'published' => 'Y',
                        'created' => '2007-03-18 10:49:23',
                        'updated' => '2007-03-18 10:51:31',
                    ],
                    [
                        'id' => '4',
                        'article_id' => '1',
                        'user_id' => '1',
                        'comment' => 'Fourth Comment for First Article',
                        'published' => 'N',
                        'created' => '2007-03-18 10:51:23',
                        'updated' => '2007-03-18 10:53:31',
                    ],
                    [
                        'id' => '5',
                        'article_id' => '2',
                        'user_id' => '1',
                        'comment' => 'First Comment for Second Article',
                        'published' => 'Y',
                        'created' => '2007-03-18 10:53:23',
                        'updated' => '2007-03-18 10:55:31',
            ], ], ],
            [
                'User' => [
                    'id' => '2',
                    'user' => 'nate',
                ],
                'Comment' => [
                    [
                        'id' => '1',
                        'article_id' => '1',
                        'user_id' => '2',
                        'comment' => 'First Comment for First Article',
                        'published' => 'Y',
                        'created' => '2007-03-18 10:45:23',
                        'updated' => '2007-03-18 10:47:31',
                    ],
                    [
                        'id' => '6',
                        'article_id' => '2',
                        'user_id' => '2',
                        'comment' => 'Second Comment for Second Article',
                        'published' => 'Y',
                        'created' => '2007-03-18 10:55:23',
                        'updated' => '2007-03-18 10:57:31',
            ], ], ],
            [
                'User' => [
                    'id' => '3',
                    'user' => 'larry',
                ],
                'Comment' => [],
            ],
            [
                'User' => [
                    'id' => '4',
                    'user' => 'garrett',
                ],
                'Comment' => [
                    [
                        'id' => '2',
                        'article_id' => '1',
                        'user_id' => '4',
                        'comment' => 'Second Comment for First Article',
                        'published' => 'Y',
                        'created' => '2007-03-18 10:47:23',
                        'updated' => '2007-03-18 10:49:31',
        ], ], ], ];

        $this->assertEqual($result, $expected);

        $result = $TestModel->hasMany;
        $expected = [
            'Comment' => [
                'className' => 'Comment',
                'foreignKey' => 'user_id',
                'conditions' => null,
                'fields' => null,
                'order' => null,
                'limit' => null,
                'offset' => null,
                'dependent' => null,
                'exclusive' => null,
                'finderQuery' => null,
                'counterQuery' => null,
        ], ];
        $this->assertEqual($result, $expected);

        $result = $TestModel->unbindModel(['hasMany' => ['Comment']]);
        $this->assertTrue($result);

        $result = $TestModel->hasMany;
        $expected = [];
        $this->assertEqual($result, $expected);

        $result = $TestModel->find('all', [
            'fields' => 'User.id, User.user',
        ]);
        $expected = [
            ['User' => ['id' => '1', 'user' => 'mariano']],
            ['User' => ['id' => '2', 'user' => 'nate']],
            ['User' => ['id' => '3', 'user' => 'larry']],
            ['User' => ['id' => '4', 'user' => 'garrett']], ];
        $this->assertEqual($result, $expected);

        $result = $TestModel->find('all', [
            'fields' => 'User.id, User.user',
        ]);
        $expected = [
            [
                'User' => [
                    'id' => '1',
                    'user' => 'mariano',
                ],
                'Comment' => [
                    [
                        'id' => '3',
                        'article_id' => '1',
                        'user_id' => '1',
                        'comment' => 'Third Comment for First Article',
                        'published' => 'Y',
                        'created' => '2007-03-18 10:49:23',
                        'updated' => '2007-03-18 10:51:31',
                    ],
                    [
                        'id' => '4',
                        'article_id' => '1',
                        'user_id' => '1',
                        'comment' => 'Fourth Comment for First Article',
                        'published' => 'N',
                        'created' => '2007-03-18 10:51:23',
                        'updated' => '2007-03-18 10:53:31',
                    ],
                    [
                        'id' => '5',
                        'article_id' => '2',
                        'user_id' => '1',
                        'comment' => 'First Comment for Second Article',
                        'published' => 'Y',
                        'created' => '2007-03-18 10:53:23',
                        'updated' => '2007-03-18 10:55:31',
            ], ], ],
            [
                'User' => [
                    'id' => '2',
                    'user' => 'nate',
                ],
                'Comment' => [
                    [
                        'id' => '1',
                        'article_id' => '1',
                        'user_id' => '2',
                        'comment' => 'First Comment for First Article',
                        'published' => 'Y',
                        'created' => '2007-03-18 10:45:23',
                        'updated' => '2007-03-18 10:47:31',
                    ],
                    [
                        'id' => '6',
                        'article_id' => '2',
                        'user_id' => '2',
                        'comment' => 'Second Comment for Second Article',
                        'published' => 'Y',
                        'created' => '2007-03-18 10:55:23',
                        'updated' => '2007-03-18 10:57:31',
            ], ], ],
            [
                'User' => [
                    'id' => '3',
                    'user' => 'larry',
                ],
                'Comment' => [],
            ],
            [
                'User' => [
                    'id' => '4',
                    'user' => 'garrett',
                ],
                'Comment' => [
                    [
                        'id' => '2',
                        'article_id' => '1',
                        'user_id' => '4',
                        'comment' => 'Second Comment for First Article',
                        'published' => 'Y',
                        'created' => '2007-03-18 10:47:23',
                        'updated' => '2007-03-18 10:49:31',
        ], ], ], ];
        $this->assertEqual($result, $expected);

        $result = $TestModel->unbindModel(['hasMany' => ['Comment']], false);
        $this->assertTrue($result);

        $result = $TestModel->find('all', ['fields' => 'User.id, User.user']);
        $expected = [
            ['User' => ['id' => '1', 'user' => 'mariano']],
            ['User' => ['id' => '2', 'user' => 'nate']],
            ['User' => ['id' => '3', 'user' => 'larry']],
            ['User' => ['id' => '4', 'user' => 'garrett']], ];
        $this->assertEqual($result, $expected);

        $result = $TestModel->hasMany;
        $expected = [];
        $this->assertEqual($result, $expected);

        $result = $TestModel->bindModel(['hasMany' => [
            'Comment' => ['className' => 'Comment', 'conditions' => 'Comment.published = \'Y\''],
        ]]);
        $this->assertTrue($result);

        $result = $TestModel->find('all', ['fields' => 'User.id, User.user']);
        $expected = [
            [
                'User' => [
                    'id' => '1',
                    'user' => 'mariano',
                ],
                'Comment' => [
                    [
                        'id' => '3',
                        'article_id' => '1',
                        'user_id' => '1',
                        'comment' => 'Third Comment for First Article',
                        'published' => 'Y',
                        'created' => '2007-03-18 10:49:23',
                        'updated' => '2007-03-18 10:51:31',
                    ],
                    [
                        'id' => '5',
                        'article_id' => '2',
                        'user_id' => '1',
                        'comment' => 'First Comment for Second Article',
                        'published' => 'Y',
                        'created' => '2007-03-18 10:53:23',
                        'updated' => '2007-03-18 10:55:31',
            ], ], ],
            [
                'User' => [
                    'id' => '2',
                    'user' => 'nate',
                ],
                'Comment' => [
                    [
                        'id' => '1',
                        'article_id' => '1',
                        'user_id' => '2',
                        'comment' => 'First Comment for First Article',
                        'published' => 'Y',
                        'created' => '2007-03-18 10:45:23',
                        'updated' => '2007-03-18 10:47:31',
                    ],
                    [
                        'id' => '6',
                        'article_id' => '2',
                        'user_id' => '2',
                        'comment' => 'Second Comment for Second Article',
                        'published' => 'Y',
                        'created' => '2007-03-18 10:55:23',
                        'updated' => '2007-03-18 10:57:31',
            ], ], ],
            [
                'User' => [
                    'id' => '3',
                    'user' => 'larry',
                ],
                'Comment' => [],
            ],
            [
                'User' => [
                    'id' => '4',
                    'user' => 'garrett',
                ],
                'Comment' => [
                    [
                        'id' => '2',
                        'article_id' => '1',
                        'user_id' => '4',
                        'comment' => 'Second Comment for First Article',
                        'published' => 'Y',
                        'created' => '2007-03-18 10:47:23',
                        'updated' => '2007-03-18 10:49:31',
        ], ], ], ];

        $this->assertEqual($result, $expected);

        $TestModel2 = new DeviceType();

        $expected = [
            'className' => 'FeatureSet',
            'foreignKey' => 'feature_set_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'counterCache' => '',
        ];
        $this->assertEqual($TestModel2->belongsTo['FeatureSet'], $expected);

        $TestModel2->bindModel([
            'belongsTo' => [
                'FeatureSet' => [
                    'className' => 'FeatureSet',
                    'conditions' => ['active' => true],
                ],
            ],
        ]);
        $expected['conditions'] = ['active' => true];
        $this->assertEqual($TestModel2->belongsTo['FeatureSet'], $expected);

        $TestModel2->bindModel([
            'belongsTo' => [
                'FeatureSet' => [
                    'className' => 'FeatureSet',
                    'foreignKey' => false,
                    'conditions' => ['Feature.name' => 'DeviceType.name'],
                ],
            ],
        ]);
        $expected['conditions'] = ['Feature.name' => 'DeviceType.name'];
        $expected['foreignKey'] = false;
        $this->assertEqual($TestModel2->belongsTo['FeatureSet'], $expected);

        $TestModel2->bindModel([
            'hasMany' => [
                'NewFeatureSet' => [
                    'className' => 'FeatureSet',
                    'conditions' => ['active' => true],
                ],
            ],
        ]);

        $expected = [
            'className' => 'FeatureSet',
            'conditions' => ['active' => true],
            'foreignKey' => 'device_type_id',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'dependent' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => '',
        ];
        $this->assertEqual($TestModel2->hasMany['NewFeatureSet'], $expected);
        $this->assertTrue(is_object($TestModel2->NewFeatureSet));
    }

    /**
     * testBindMultipleTimes method.
     */
    public function testBindMultipleTimes()
    {
        $this->loadFixtures('User', 'Comment', 'Article');
        $TestModel = new User();

        $result = $TestModel->hasMany;
        $expected = [];
        $this->assertEqual($result, $expected);

        $result = $TestModel->bindModel([
            'hasMany' => [
                'Items' => ['className' => 'Comment'],
        ], ]);
        $this->assertTrue($result);

        $result = $TestModel->find('all', [
            'fields' => 'User.id, User.user',
        ]);
        $expected = [
            [
                'User' => [
                    'id' => '1',
                    'user' => 'mariano',
                ],
                'Items' => [
                    [
                        'id' => '3',
                        'article_id' => '1',
                        'user_id' => '1',
                        'comment' => 'Third Comment for First Article',
                        'published' => 'Y',
                        'created' => '2007-03-18 10:49:23',
                        'updated' => '2007-03-18 10:51:31',
                    ],
                    [
                        'id' => '4',
                        'article_id' => '1',
                        'user_id' => '1',
                        'comment' => 'Fourth Comment for First Article',
                        'published' => 'N',
                        'created' => '2007-03-18 10:51:23',
                        'updated' => '2007-03-18 10:53:31',
                    ],
                    [
                        'id' => '5',
                        'article_id' => '2',
                        'user_id' => '1',
                        'comment' => 'First Comment for Second Article',
                        'published' => 'Y',
                        'created' => '2007-03-18 10:53:23',
                        'updated' => '2007-03-18 10:55:31',
            ], ], ],
            [
                'User' => [
                    'id' => '2',
                    'user' => 'nate',
                ],
                'Items' => [
                    [
                        'id' => '1',
                        'article_id' => '1',
                        'user_id' => '2',
                        'comment' => 'First Comment for First Article',
                        'published' => 'Y',
                        'created' => '2007-03-18 10:45:23',
                        'updated' => '2007-03-18 10:47:31',
                    ],
                    [
                        'id' => '6',
                        'article_id' => '2',
                        'user_id' => '2',
                        'comment' => 'Second Comment for Second Article',
                        'published' => 'Y',
                        'created' => '2007-03-18 10:55:23',
                        'updated' => '2007-03-18 10:57:31',
            ], ], ],
            [
                'User' => [
                    'id' => '3',
                    'user' => 'larry',
                ],
                'Items' => [],
            ],
            [
                'User' => [
                    'id' => '4', 'user' => 'garrett', ],
                    'Items' => [
                        [
                            'id' => '2',
                            'article_id' => '1',
                            'user_id' => '4',
                            'comment' => 'Second Comment for First Article',
                            'published' => 'Y',
                            'created' => '2007-03-18 10:47:23',
                            'updated' => '2007-03-18 10:49:31',
        ], ], ], ];
        $this->assertEqual($result, $expected);

        $result = $TestModel->bindModel([
            'hasMany' => [
                'Items' => ['className' => 'Article'],
        ], ]);
        $this->assertTrue($result);

        $result = $TestModel->find('all', [
            'fields' => 'User.id, User.user',
        ]);
        $expected = [
            [
                'User' => [
                    'id' => '1',
                    'user' => 'mariano',
                ],
                'Items' => [
                    [
                        'id' => 1,
                        'user_id' => 1,
                        'title' => 'First Article',
                        'body' => 'First Article Body',
                        'published' => 'Y',
                        'created' => '2007-03-18 10:39:23',
                        'updated' => '2007-03-18 10:41:31',
                    ],
                    [
                        'id' => 3,
                        'user_id' => 1,
                        'title' => 'Third Article',
                        'body' => 'Third Article Body',
                        'published' => 'Y',
                        'created' => '2007-03-18 10:43:23',
                        'updated' => '2007-03-18 10:45:31',
            ], ], ],
            [
                'User' => [
                    'id' => '2',
                    'user' => 'nate',
                ],
                'Items' => [],
            ],
            [
                'User' => [
                    'id' => '3',
                    'user' => 'larry',
                ],
                'Items' => [
                    [
                        'id' => 2,
                        'user_id' => 3,
                        'title' => 'Second Article',
                        'body' => 'Second Article Body',
                        'published' => 'Y',
                        'created' => '2007-03-18 10:41:23',
                        'updated' => '2007-03-18 10:43:31',
            ], ], ],
            [
                'User' => [
                    'id' => '4',
                    'user' => 'garrett',
                ],
                'Items' => [],
        ], ];
        $this->assertEqual($result, $expected);
    }

    /**
     * test that multiple reset = true calls to bindModel() result in the original associations.
     */
    public function testBindModelMultipleTimesResetCorrectly()
    {
        $this->loadFixtures('User', 'Comment', 'Article');
        $TestModel = new User();

        $TestModel->bindModel(['hasMany' => ['Comment']]);
        $TestModel->bindModel(['hasMany' => ['Comment']]);
        $TestModel->resetAssociations();

        $this->assertFalse(isset($TestModel->hasMany['Comment']), 'Association left behind');
    }

    /**
     * testBindMultipleTimes method with different reset settings.
     */
    public function testBindMultipleTimesWithDifferentResetSettings()
    {
        $this->loadFixtures('User', 'Comment', 'Article');
        $TestModel = new User();

        $result = $TestModel->hasMany;
        $expected = [];
        $this->assertEqual($result, $expected);

        $result = $TestModel->bindModel([
            'hasMany' => ['Comment'],
        ]);
        $this->assertTrue($result);
        $result = $TestModel->bindModel(
            ['hasMany' => ['Article']],
            false
        );
        $this->assertTrue($result);

        $result = array_keys($TestModel->hasMany);
        $expected = ['Comment', 'Article'];
        $this->assertEqual($result, $expected);

        $TestModel->resetAssociations();

        $result = array_keys($TestModel->hasMany);
        $expected = ['Article'];
        $this->assertEqual($result, $expected);
    }

    /**
     * test that bindModel behaves with Custom primary Key associations.
     */
    public function testBindWithCustomPrimaryKey()
    {
        $this->loadFixtures('Story', 'StoriesTag', 'Tag');
        $Model = &ClassRegistry::init('StoriesTag');
        $Model->bindModel([
            'belongsTo' => [
                'Tag' => [
                    'className' => 'Tag',
                    'foreignKey' => 'story',
        ], ], ]);

        $result = $Model->find('all');
        $this->assertFalse(empty($result));
    }

    /**
     * test that calling unbindModel() with reset == true multiple times
     * leaves associations in the correct state.
     */
    public function testUnbindMultipleTimesResetCorrectly()
    {
        $this->loadFixtures('User', 'Comment', 'Article');
        $TestModel = new Article10();

        $TestModel->unbindModel(['hasMany' => ['Comment']]);
        $TestModel->unbindModel(['hasMany' => ['Comment']]);
        $TestModel->resetAssociations();

        $this->assertTrue(isset($TestModel->hasMany['Comment']), 'Association permanently removed');
    }

    /**
     * testBindMultipleTimes method with different reset settings.
     */
    public function testUnBindMultipleTimesWithDifferentResetSettings()
    {
        $this->loadFixtures('User', 'Comment', 'Article');
        $TestModel = new Comment();

        $result = array_keys($TestModel->belongsTo);
        $expected = ['Article', 'User'];
        $this->assertEqual($result, $expected);

        $result = $TestModel->unbindModel([
            'belongsTo' => ['User'],
        ]);
        $this->assertTrue($result);
        $result = $TestModel->unbindModel(
            ['belongsTo' => ['Article']],
            false
        );
        $this->assertTrue($result);

        $result = array_keys($TestModel->belongsTo);
        $expected = [];
        $this->assertEqual($result, $expected);

        $TestModel->resetAssociations();

        $result = array_keys($TestModel->belongsTo);
        $expected = ['User'];
        $this->assertEqual($result, $expected);
    }

    /**
     * testAssociationAfterFind method.
     */
    public function testAssociationAfterFind()
    {
        $this->loadFixtures('Post', 'Author', 'Comment');
        $TestModel = new Post();
        $result = $TestModel->find('all');
        $expected = [
            [
                'Post' => [
                    'id' => '1',
                    'author_id' => '1',
                    'title' => 'First Post',
                    'body' => 'First Post Body',
                    'published' => 'Y',
                    'created' => '2007-03-18 10:39:23',
                    'updated' => '2007-03-18 10:41:31',
                ],
                'Author' => [
                    'id' => '1',
                    'user' => 'mariano',
                    'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:16:23',
                    'updated' => '2007-03-17 01:18:31',
                    'test' => 'working',
            ], ],
            [
                'Post' => [
                    'id' => '2',
                    'author_id' => '3',
                    'title' => 'Second Post',
                    'body' => 'Second Post Body',
                    'published' => 'Y',
                    'created' => '2007-03-18 10:41:23',
                    'updated' => '2007-03-18 10:43:31',
                ],
                'Author' => [
                    'id' => '3',
                    'user' => 'larry',
                    'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:20:23',
                    'updated' => '2007-03-17 01:22:31',
                    'test' => 'working',
            ], ],
            [
                'Post' => [
                    'id' => '3',
                    'author_id' => '1',
                    'title' => 'Third Post',
                    'body' => 'Third Post Body',
                    'published' => 'Y',
                    'created' => '2007-03-18 10:43:23',
                    'updated' => '2007-03-18 10:45:31',
                ],
                'Author' => [
                    'id' => '1',
                    'user' => 'mariano',
                    'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:16:23',
                    'updated' => '2007-03-17 01:18:31',
                    'test' => 'working',
        ], ], ];
        $this->assertEqual($result, $expected);
        unset($TestModel);

        $Author = new Author();
        $Author->Post->bindModel([
            'hasMany' => [
                'Comment' => [
                    'className' => 'ModifiedComment',
                    'foreignKey' => 'article_id',
                ],
        ], ]);
        $result = $Author->find('all', [
            'conditions' => ['Author.id' => 1],
            'recursive' => 2,
        ]);
        $expected = [
            'id' => 1,
            'article_id' => 1,
            'user_id' => 2,
            'comment' => 'First Comment for First Article',
            'published' => 'Y',
            'created' => '2007-03-18 10:45:23',
            'updated' => '2007-03-18 10:47:31',
            'callback' => 'Fire',
        ];
        $this->assertEqual($result[0]['Post'][0]['Comment'][0], $expected);
    }

    /**
     * Tests that callbacks can be properly disabled.
     */
    public function testCallbackDisabling()
    {
        $this->loadFixtures('Author');
        $TestModel = new ModifiedAuthor();

        $result = Set::extract($TestModel->find('all'), '/Author/user');
        $expected = ['mariano (CakePHP)', 'nate (CakePHP)', 'larry (CakePHP)', 'garrett (CakePHP)'];
        $this->assertEqual($result, $expected);

        $result = Set::extract($TestModel->find('all', ['callbacks' => 'after']), '/Author/user');
        $expected = ['mariano (CakePHP)', 'nate (CakePHP)', 'larry (CakePHP)', 'garrett (CakePHP)'];
        $this->assertEqual($result, $expected);

        $result = Set::extract($TestModel->find('all', ['callbacks' => 'before']), '/Author/user');
        $expected = ['mariano', 'nate', 'larry', 'garrett'];
        $this->assertEqual($result, $expected);

        $result = Set::extract($TestModel->find('all', ['callbacks' => false]), '/Author/user');
        $expected = ['mariano', 'nate', 'larry', 'garrett'];
        $this->assertEqual($result, $expected);
    }

    /**
     * testAssociationAfterFindCallbacksDisabled method.
     */
    public function testAssociationAfterFindCalbacksDisabled()
    {
        $this->loadFixtures('Post', 'Author', 'Comment');
        $TestModel = new Post();
        $result = $TestModel->find('all', ['callbacks' => false]);
        $expected = [
            [
                'Post' => [
                    'id' => '1',
                    'author_id' => '1',
                    'title' => 'First Post',
                    'body' => 'First Post Body',
                    'published' => 'Y',
                    'created' => '2007-03-18 10:39:23',
                    'updated' => '2007-03-18 10:41:31',
                ],
                'Author' => [
                    'id' => '1',
                    'user' => 'mariano',
                    'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:16:23',
                    'updated' => '2007-03-17 01:18:31',
            ], ],
            [
                'Post' => [
                    'id' => '2',
                    'author_id' => '3',
                    'title' => 'Second Post',
                    'body' => 'Second Post Body',
                    'published' => 'Y',
                    'created' => '2007-03-18 10:41:23',
                    'updated' => '2007-03-18 10:43:31',
                ],
                'Author' => [
                    'id' => '3',
                    'user' => 'larry',
                    'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:20:23',
                    'updated' => '2007-03-17 01:22:31',
            ], ],
            [
                'Post' => [
                    'id' => '3',
                    'author_id' => '1',
                    'title' => 'Third Post',
                    'body' => 'Third Post Body',
                    'published' => 'Y',
                    'created' => '2007-03-18 10:43:23',
                    'updated' => '2007-03-18 10:45:31',
                ],
                'Author' => [
                    'id' => '1',
                    'user' => 'mariano',
                    'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:16:23',
                    'updated' => '2007-03-17 01:18:31',
        ], ], ];
        $this->assertEqual($expected, $result);
        unset($TestModel);

        $Author = new Author();
        $Author->Post->bindModel([
            'hasMany' => [
                'Comment' => [
                    'className' => 'ModifiedComment',
                    'foreignKey' => 'article_id',
                ],
        ], ]);
        $result = $Author->find('all', [
            'conditions' => ['Author.id' => 1],
            'recursive' => 2,
            'callbacks' => false,
        ]);
        $expected = [
            'id' => 1,
            'article_id' => 1,
            'user_id' => 2,
            'comment' => 'First Comment for First Article',
            'published' => 'Y',
            'created' => '2007-03-18 10:45:23',
            'updated' => '2007-03-18 10:47:31',
        ];
        $this->assertEqual($result[0]['Post'][0]['Comment'][0], $expected);
    }

    /**
     * Tests that the database configuration assigned to the model can be changed using
     * (before|after)Find callbacks.
     */
    public function testCallbackSourceChange()
    {
        $this->loadFixtures('Post');
        $TestModel = new Post();
        $this->assertEqual(3, count($TestModel->find('all')));

        $this->expectError(new PatternExpectation('/Non-existent data source foo/i'));
        $this->assertFalse($TestModel->find('all', ['connection' => 'foo']));
    }

    /**
     * testMultipleBelongsToWithSameClass method.
     */
    public function testMultipleBelongsToWithSameClass()
    {
        $this->loadFixtures(
            'DeviceType',
            'DeviceTypeCategory',
            'FeatureSet',
            'ExteriorTypeCategory',
            'Document',
            'Device',
            'DocumentDirectory'
        );

        $DeviceType = new DeviceType();

        $DeviceType->recursive = 2;
        $result = $DeviceType->read(null, 1);

        $expected = [
            'DeviceType' => [
                'id' => 1,
                'device_type_category_id' => 1,
                'feature_set_id' => 1,
                'exterior_type_category_id' => 1,
                'image_id' => 1,
                'extra1_id' => 1,
                'extra2_id' => 1,
                'name' => 'DeviceType 1',
                'order' => 0,
            ],
            'Image' => [
                'id' => 1,
                'document_directory_id' => 1,
                'name' => 'Document 1',
                'DocumentDirectory' => [
                    'id' => 1,
                    'name' => 'DocumentDirectory 1',
            ], ],
            'Extra1' => [
                'id' => 1,
                'document_directory_id' => 1,
                'name' => 'Document 1',
                'DocumentDirectory' => [
                    'id' => 1,
                    'name' => 'DocumentDirectory 1',
            ], ],
            'Extra2' => [
                'id' => 1,
                'document_directory_id' => 1,
                'name' => 'Document 1',
                'DocumentDirectory' => [
                    'id' => 1,
                    'name' => 'DocumentDirectory 1',
            ], ],
            'DeviceTypeCategory' => [
                'id' => 1,
                'name' => 'DeviceTypeCategory 1',
            ],
            'FeatureSet' => [
                'id' => 1,
                'name' => 'FeatureSet 1',
            ],
            'ExteriorTypeCategory' => [
                'id' => 1,
                'image_id' => 1,
                'name' => 'ExteriorTypeCategory 1',
                'Image' => [
                    'id' => 1,
                    'device_type_id' => 1,
                    'name' => 'Device 1',
                    'typ' => 1,
            ], ],
            'Device' => [
                [
                    'id' => 1,
                    'device_type_id' => 1,
                    'name' => 'Device 1',
                    'typ' => 1,
                ],
                [
                    'id' => 2,
                    'device_type_id' => 1,
                    'name' => 'Device 2',
                    'typ' => 1,
                ],
                [
                    'id' => 3,
                    'device_type_id' => 1,
                    'name' => 'Device 3',
                    'typ' => 2,
        ], ], ];

        $this->assertEqual($result, $expected);
    }

    /**
     * testHabtmRecursiveBelongsTo method.
     */
    public function testHabtmRecursiveBelongsTo()
    {
        $this->loadFixtures('Portfolio', 'Item', 'ItemsPortfolio', 'Syfile', 'Image');
        $Portfolio = new Portfolio();

        $result = $Portfolio->find(['id' => 2], null, null, 3);
        $expected = [
            'Portfolio' => [
                'id' => 2,
                'seller_id' => 1,
                'name' => 'Portfolio 2',
            ],
            'Item' => [
                [
                    'id' => 2,
                    'syfile_id' => 2,
                    'published' => 0,
                    'name' => 'Item 2',
                    'ItemsPortfolio' => [
                        'id' => 2,
                        'item_id' => 2,
                        'portfolio_id' => 2,
                    ],
                    'Syfile' => [
                        'id' => 2,
                        'image_id' => 2,
                        'name' => 'Syfile 2',
                        'item_count' => null,
                        'Image' => [
                            'id' => 2,
                            'name' => 'Image 2',
                        ],
                ], ],
                [
                    'id' => 6,
                    'syfile_id' => 6,
                    'published' => 0,
                    'name' => 'Item 6',
                    'ItemsPortfolio' => [
                        'id' => 6,
                        'item_id' => 6,
                        'portfolio_id' => 2,
                    ],
                    'Syfile' => [
                        'id' => 6,
                        'image_id' => null,
                        'name' => 'Syfile 6',
                        'item_count' => null,
                        'Image' => [],
        ], ], ], ];

        $this->assertEqual($result, $expected);
    }

    /**
     * testHabtmFinderQuery method.
     */
    public function testHabtmFinderQuery()
    {
        $this->loadFixtures('Article', 'Tag', 'ArticlesTag');
        $Article = new Article();

        $sql = $this->db->buildStatement(
            [
                'fields' => $this->db->fields($Article->Tag, null, [
                    'Tag.id', 'Tag.tag', 'ArticlesTag.article_id', 'ArticlesTag.tag_id',
                ]),
                'table' => $this->db->fullTableName('tags'),
                'alias' => 'Tag',
                'limit' => null,
                'offset' => null,
                'group' => null,
                'joins' => [[
                    'alias' => 'ArticlesTag',
                    'table' => 'articles_tags',
                    'conditions' => [
                        ['ArticlesTag.article_id' => '{$__cakeID__$}'],
                        ['ArticlesTag.tag_id' => $this->db->identifier('Tag.id')],
                    ],
                ]],
                'conditions' => [],
                'order' => null,
            ],
            $Article
        );

        $Article->hasAndBelongsToMany['Tag']['finderQuery'] = $sql;
        $result = $Article->find('first');
        $expected = [
            [
                'id' => '1',
                'tag' => 'tag1',
            ],
            [
                'id' => '2',
                'tag' => 'tag2',
        ], ];

        $this->assertEqual($result['Tag'], $expected);
    }

    /**
     * testHabtmLimitOptimization method.
     */
    public function testHabtmLimitOptimization()
    {
        $this->loadFixtures('Article', 'User', 'Comment', 'Tag', 'ArticlesTag');
        $TestModel = new Article();

        $TestModel->hasAndBelongsToMany['Tag']['limit'] = 2;
        $result = $TestModel->read(null, 2);
        $expected = [
            'Article' => [
                'id' => '2',
                'user_id' => '3',
                'title' => 'Second Article',
                'body' => 'Second Article Body',
                'published' => 'Y',
                'created' => '2007-03-18 10:41:23',
                'updated' => '2007-03-18 10:43:31',
            ],
            'User' => [
                'id' => '3',
                'user' => 'larry',
                'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                'created' => '2007-03-17 01:20:23',
                'updated' => '2007-03-17 01:22:31',
            ],
            'Comment' => [
                [
                    'id' => '5',
                    'article_id' => '2',
                    'user_id' => '1',
                    'comment' => 'First Comment for Second Article',
                    'published' => 'Y',
                    'created' => '2007-03-18 10:53:23',
                    'updated' => '2007-03-18 10:55:31',
                ],
                [
                    'id' => '6',
                    'article_id' => '2',
                    'user_id' => '2',
                    'comment' => 'Second Comment for Second Article',
                    'published' => 'Y',
                    'created' => '2007-03-18 10:55:23',
                    'updated' => '2007-03-18 10:57:31',
            ], ],
            'Tag' => [
                [
                    'id' => '1',
                    'tag' => 'tag1',
                    'created' => '2007-03-18 12:22:23',
                    'updated' => '2007-03-18 12:24:31',
                ],
                [
                    'id' => '3',
                    'tag' => 'tag3',
                    'created' => '2007-03-18 12:26:23',
                    'updated' => '2007-03-18 12:28:31',
        ], ], ];

        $this->assertEqual($result, $expected);

        $TestModel->hasAndBelongsToMany['Tag']['limit'] = 1;
        $result = $TestModel->read(null, 2);
        unset($expected['Tag'][1]);

        $this->assertEqual($result, $expected);
    }

    /**
     * testHasManyLimitOptimization method.
     */
    public function testHasManyLimitOptimization()
    {
        $this->loadFixtures('Project', 'Thread', 'Message', 'Bid');
        $Project = new Project();
        $Project->recursive = 3;

        $result = $Project->find('all');
        $expected = [
            [
                'Project' => [
                    'id' => 1,
                    'name' => 'Project 1',
                ],
                'Thread' => [
                    [
                        'id' => 1,
                        'project_id' => 1,
                        'name' => 'Project 1, Thread 1',
                        'Project' => [
                            'id' => 1,
                            'name' => 'Project 1',
                            'Thread' => [
                                [
                                    'id' => 1,
                                    'project_id' => 1,
                                    'name' => 'Project 1, Thread 1',
                                ],
                                [
                                    'id' => 2,
                                    'project_id' => 1,
                                    'name' => 'Project 1, Thread 2',
                        ], ], ],
                        'Message' => [
                            [
                                'id' => 1,
                                'thread_id' => 1,
                                'name' => 'Thread 1, Message 1',
                                'Bid' => [
                                    'id' => 1,
                                    'message_id' => 1,
                                    'name' => 'Bid 1.1',
                    ], ], ], ],
                    [
                        'id' => 2,
                        'project_id' => 1,
                        'name' => 'Project 1, Thread 2',
                        'Project' => [
                            'id' => 1,
                            'name' => 'Project 1',
                            'Thread' => [
                                [
                                    'id' => 1,
                                    'project_id' => 1,
                                    'name' => 'Project 1, Thread 1',
                                ],
                                [
                                    'id' => 2,
                                    'project_id' => 1,
                                    'name' => 'Project 1, Thread 2',
                        ], ], ],
                        'Message' => [
                            [
                                'id' => 2,
                                'thread_id' => 2,
                                'name' => 'Thread 2, Message 1',
                                'Bid' => [
                                    'id' => 4,
                                    'message_id' => 2,
                                    'name' => 'Bid 2.1',
            ], ], ], ], ], ],
            [
                'Project' => [
                    'id' => 2,
                    'name' => 'Project 2',
                ],
                'Thread' => [
                    [
                        'id' => 3,
                        'project_id' => 2,
                        'name' => 'Project 2, Thread 1',
                        'Project' => [
                            'id' => 2,
                            'name' => 'Project 2',
                            'Thread' => [
                                [
                                    'id' => 3,
                                    'project_id' => 2,
                                    'name' => 'Project 2, Thread 1',
                        ], ], ],
                        'Message' => [
                            [
                                'id' => 3,
                                'thread_id' => 3,
                                'name' => 'Thread 3, Message 1',
                                'Bid' => [
                                    'id' => 3,
                                    'message_id' => 3,
                                    'name' => 'Bid 3.1',
            ], ], ], ], ], ],
            [
                'Project' => [
                    'id' => 3,
                    'name' => 'Project 3',
                ],
                'Thread' => [],
        ], ];

        $this->assertEqual($result, $expected);
    }

    /**
     * testFindAllRecursiveSelfJoin method.
     */
    public function testFindAllRecursiveSelfJoin()
    {
        $this->loadFixtures('Home', 'AnotherArticle', 'Advertisement');
        $TestModel = new Home();
        $TestModel->recursive = 2;

        $result = $TestModel->find('all');
        $expected = [
            [
                'Home' => [
                    'id' => '1',
                    'another_article_id' => '1',
                    'advertisement_id' => '1',
                    'title' => 'First Home',
                    'created' => '2007-03-18 10:39:23',
                    'updated' => '2007-03-18 10:41:31',
                ],
                'AnotherArticle' => [
                    'id' => '1',
                    'title' => 'First Article',
                    'created' => '2007-03-18 10:39:23',
                    'updated' => '2007-03-18 10:41:31',
                    'Home' => [
                        [
                            'id' => '1',
                            'another_article_id' => '1',
                            'advertisement_id' => '1',
                            'title' => 'First Home',
                            'created' => '2007-03-18 10:39:23',
                            'updated' => '2007-03-18 10:41:31',
                ], ], ],
                'Advertisement' => [
                    'id' => '1',
                    'title' => 'First Ad',
                    'created' => '2007-03-18 10:39:23',
                    'updated' => '2007-03-18 10:41:31',
                    'Home' => [
                        [
                            'id' => '1',
                            'another_article_id' => '1',
                            'advertisement_id' => '1',
                            'title' => 'First Home',
                            'created' => '2007-03-18 10:39:23',
                            'updated' => '2007-03-18 10:41:31',
                        ],
                        [
                            'id' => '2',
                            'another_article_id' => '3',
                            'advertisement_id' => '1',
                            'title' => 'Second Home',
                            'created' => '2007-03-18 10:41:23',
                            'updated' => '2007-03-18 10:43:31',
            ], ], ], ],
            [
                'Home' => [
                    'id' => '2',
                    'another_article_id' => '3',
                    'advertisement_id' => '1',
                    'title' => 'Second Home',
                    'created' => '2007-03-18 10:41:23',
                    'updated' => '2007-03-18 10:43:31',
                ],
                'AnotherArticle' => [
                    'id' => '3',
                    'title' => 'Third Article',
                    'created' => '2007-03-18 10:43:23',
                    'updated' => '2007-03-18 10:45:31',
                    'Home' => [
                        [
                            'id' => '2',
                            'another_article_id' => '3',
                            'advertisement_id' => '1',
                            'title' => 'Second Home',
                            'created' => '2007-03-18 10:41:23',
                            'updated' => '2007-03-18 10:43:31',
                ], ], ],
                'Advertisement' => [
                    'id' => '1',
                    'title' => 'First Ad',
                    'created' => '2007-03-18 10:39:23',
                    'updated' => '2007-03-18 10:41:31',
                    'Home' => [
                        [
                            'id' => '1',
                            'another_article_id' => '1',
                            'advertisement_id' => '1',
                            'title' => 'First Home',
                            'created' => '2007-03-18 10:39:23',
                            'updated' => '2007-03-18 10:41:31',
                        ],
                        [
                            'id' => '2',
                            'another_article_id' => '3',
                            'advertisement_id' => '1',
                            'title' => 'Second Home',
                            'created' => '2007-03-18 10:41:23',
                            'updated' => '2007-03-18 10:43:31',
        ], ], ], ], ];

        $this->assertEqual($result, $expected);
    }

    /**
     * testFindAllRecursiveWithHabtm method.
     */
    public function testFindAllRecursiveWithHabtm()
    {
        $this->loadFixtures(
            'MyCategoriesMyUsers',
            'MyCategoriesMyProducts',
            'MyCategory',
            'MyUser',
            'MyProduct'
        );

        $MyUser = new MyUser();
        $MyUser->recursive = 2;

        $result = $MyUser->find('all');
        $expected = [
            [
                'MyUser' => ['id' => '1', 'firstname' => 'userA'],
                'MyCategory' => [
                    [
                        'id' => '1',
                        'name' => 'A',
                        'MyProduct' => [
                            [
                                'id' => '1',
                                'name' => 'book',
                    ], ], ],
                    [
                        'id' => '3',
                        'name' => 'C',
                        'MyProduct' => [
                            [
                                'id' => '2',
                                'name' => 'computer',
            ], ], ], ], ],
            [
                'MyUser' => [
                    'id' => '2',
                    'firstname' => 'userB',
                ],
                'MyCategory' => [
                    [
                        'id' => '1',
                        'name' => 'A',
                        'MyProduct' => [
                            [
                                'id' => '1',
                                'name' => 'book',
                    ], ], ],
                    [
                        'id' => '2',
                        'name' => 'B',
                        'MyProduct' => [
                            [
                                'id' => '1',
                                'name' => 'book',
                            ],
                            [
                                'id' => '2',
                                'name' => 'computer',
        ], ], ], ], ], ];

        $this->assertIdentical($result, $expected);
    }

    /**
     * testReadFakeThread method.
     */
    public function testReadFakeThread()
    {
        $this->loadFixtures('CategoryThread');
        $TestModel = new CategoryThread();

        $fullDebug = $this->db->fullDebug;
        $this->db->fullDebug = true;
        $TestModel->recursive = 6;
        $TestModel->id = 7;
        $result = $TestModel->read();
        $expected = [
            'CategoryThread' => [
                'id' => 7,
                'parent_id' => 6,
                'name' => 'Category 2.1',
                'created' => '2007-03-18 15:30:23',
                'updated' => '2007-03-18 15:32:31',
            ],
            'ParentCategory' => [
                'id' => 6,
                'parent_id' => 5,
                'name' => 'Category 2',
                'created' => '2007-03-18 15:30:23',
                'updated' => '2007-03-18 15:32:31',
                'ParentCategory' => [
                    'id' => 5,
                    'parent_id' => 4,
                    'name' => 'Category 1.1.1.1',
                    'created' => '2007-03-18 15:30:23',
                    'updated' => '2007-03-18 15:32:31',
                    'ParentCategory' => [
                        'id' => 4,
                        'parent_id' => 3,
                        'name' => 'Category 1.1.2',
                        'created' => '2007-03-18 15:30:23',
                        'updated' => '2007-03-18 15:32:31',
                        'ParentCategory' => [
                            'id' => 3,
                            'parent_id' => 2,
                            'name' => 'Category 1.1.1',
                            'created' => '2007-03-18 15:30:23',
                            'updated' => '2007-03-18 15:32:31',
                            'ParentCategory' => [
                                'id' => 2,
                                'parent_id' => 1,
                                'name' => 'Category 1.1',
                                'created' => '2007-03-18 15:30:23',
                                'updated' => '2007-03-18 15:32:31',
                                'ParentCategory' => [
                                    'id' => 1,
                                    'parent_id' => 0,
                                    'name' => 'Category 1',
                                    'created' => '2007-03-18 15:30:23',
                                    'updated' => '2007-03-18 15:32:31',
        ], ], ], ], ], ], ];

        $this->db->fullDebug = $fullDebug;
        $this->assertEqual($result, $expected);
    }

    /**
     * testFindFakeThread method.
     */
    public function testFindFakeThread()
    {
        $this->loadFixtures('CategoryThread');
        $TestModel = new CategoryThread();

        $fullDebug = $this->db->fullDebug;
        $this->db->fullDebug = true;
        $TestModel->recursive = 6;
        $result = $TestModel->find(['CategoryThread.id' => 7]);

        $expected = [
            'CategoryThread' => [
                'id' => 7,
                'parent_id' => 6,
                'name' => 'Category 2.1',
                'created' => '2007-03-18 15:30:23',
                'updated' => '2007-03-18 15:32:31',
            ],
            'ParentCategory' => [
                'id' => 6,
                'parent_id' => 5,
                'name' => 'Category 2',
                'created' => '2007-03-18 15:30:23',
                'updated' => '2007-03-18 15:32:31',
                'ParentCategory' => [
                    'id' => 5,
                    'parent_id' => 4,
                    'name' => 'Category 1.1.1.1',
                    'created' => '2007-03-18 15:30:23',
                    'updated' => '2007-03-18 15:32:31',
                    'ParentCategory' => [
                        'id' => 4,
                        'parent_id' => 3,
                        'name' => 'Category 1.1.2',
                        'created' => '2007-03-18 15:30:23',
                        'updated' => '2007-03-18 15:32:31',
                        'ParentCategory' => [
                            'id' => 3,
                            'parent_id' => 2,
                            'name' => 'Category 1.1.1',
                            'created' => '2007-03-18 15:30:23',
                            'updated' => '2007-03-18 15:32:31',
                            'ParentCategory' => [
                                'id' => 2,
                                'parent_id' => 1,
                                'name' => 'Category 1.1',
                                'created' => '2007-03-18 15:30:23',
                                'updated' => '2007-03-18 15:32:31',
                                'ParentCategory' => [
                                    'id' => 1,
                                    'parent_id' => 0,
                                    'name' => 'Category 1',
                                    'created' => '2007-03-18 15:30:23',
                                    'updated' => '2007-03-18 15:32:31',
        ], ], ], ], ], ], ];

        $this->db->fullDebug = $fullDebug;
        $this->assertEqual($result, $expected);
    }

    /**
     * testFindAllFakeThread method.
     */
    public function testFindAllFakeThread()
    {
        $this->loadFixtures('CategoryThread');
        $TestModel = new CategoryThread();

        $fullDebug = $this->db->fullDebug;
        $this->db->fullDebug = true;
        $TestModel->recursive = 6;
        $result = $TestModel->find('all', null, null, 'CategoryThread.id ASC');
        $expected = [
            [
                'CategoryThread' => [
                'id' => 1,
                'parent_id' => 0,
                'name' => 'Category 1',
                'created' => '2007-03-18 15:30:23',
                'updated' => '2007-03-18 15:32:31',
                ],
                'ParentCategory' => [
                    'id' => null,
                    'parent_id' => null,
                    'name' => null,
                    'created' => null,
                    'updated' => null,
                    'ParentCategory' => [],
            ], ],
            [
                'CategoryThread' => [
                    'id' => 2,
                    'parent_id' => 1,
                    'name' => 'Category 1.1',
                    'created' => '2007-03-18 15:30:23',
                    'updated' => '2007-03-18 15:32:31',
                ],
                'ParentCategory' => [
                    'id' => 1,
                    'parent_id' => 0,
                    'name' => 'Category 1',
                    'created' => '2007-03-18 15:30:23',
                    'updated' => '2007-03-18 15:32:31',
                    'ParentCategory' => [],
                ], ],
            [
                'CategoryThread' => [
                    'id' => 3,
                    'parent_id' => 2,
                    'name' => 'Category 1.1.1',
                    'created' => '2007-03-18 15:30:23',
                    'updated' => '2007-03-18 15:32:31',
                ],
                'ParentCategory' => [
                    'id' => 2,
                    'parent_id' => 1,
                    'name' => 'Category 1.1',
                    'created' => '2007-03-18 15:30:23',
                    'updated' => '2007-03-18 15:32:31',
                    'ParentCategory' => [
                        'id' => 1,
                        'parent_id' => 0,
                        'name' => 'Category 1',
                        'created' => '2007-03-18 15:30:23',
                        'updated' => '2007-03-18 15:32:31',
                        'ParentCategory' => [],
            ], ], ],
            [
                'CategoryThread' => [
                    'id' => 4,
                    'parent_id' => 3,
                    'name' => 'Category 1.1.2',
                    'created' => '2007-03-18 15:30:23',
                    'updated' => '2007-03-18 15:32:31',
                ],
                'ParentCategory' => [
                    'id' => 3,
                    'parent_id' => 2,
                    'name' => 'Category 1.1.1',
                    'created' => '2007-03-18 15:30:23',
                    'updated' => '2007-03-18 15:32:31',
                    'ParentCategory' => [
                        'id' => 2,
                        'parent_id' => 1,
                        'name' => 'Category 1.1',
                        'created' => '2007-03-18 15:30:23',
                        'updated' => '2007-03-18 15:32:31',
                        'ParentCategory' => [
                            'id' => 1,
                            'parent_id' => 0,
                            'name' => 'Category 1',
                            'created' => '2007-03-18 15:30:23',
                            'updated' => '2007-03-18 15:32:31',
                            'ParentCategory' => [],
            ], ], ], ],
            [
                'CategoryThread' => [
                    'id' => 5,
                    'parent_id' => 4,
                    'name' => 'Category 1.1.1.1',
                    'created' => '2007-03-18 15:30:23',
                    'updated' => '2007-03-18 15:32:31',
                ],
                'ParentCategory' => [
                    'id' => 4,
                    'parent_id' => 3,
                    'name' => 'Category 1.1.2',
                    'created' => '2007-03-18 15:30:23',
                    'updated' => '2007-03-18 15:32:31',
                    'ParentCategory' => [
                        'id' => 3,
                        'parent_id' => 2,
                        'name' => 'Category 1.1.1',
                        'created' => '2007-03-18 15:30:23',
                        'updated' => '2007-03-18 15:32:31',
                        'ParentCategory' => [
                            'id' => 2,
                            'parent_id' => 1,
                            'name' => 'Category 1.1',
                            'created' => '2007-03-18 15:30:23',
                            'updated' => '2007-03-18 15:32:31',
                            'ParentCategory' => [
                                'id' => 1,
                                'parent_id' => 0,
                                'name' => 'Category 1',
                                'created' => '2007-03-18 15:30:23',
                                'updated' => '2007-03-18 15:32:31',
                                'ParentCategory' => [],
            ], ], ], ], ],
            [
                'CategoryThread' => [
                    'id' => 6,
                    'parent_id' => 5,
                    'name' => 'Category 2',
                    'created' => '2007-03-18 15:30:23',
                    'updated' => '2007-03-18 15:32:31',
                ],
                'ParentCategory' => [
                    'id' => 5,
                    'parent_id' => 4,
                    'name' => 'Category 1.1.1.1',
                    'created' => '2007-03-18 15:30:23',
                    'updated' => '2007-03-18 15:32:31',
                    'ParentCategory' => [
                        'id' => 4,
                        'parent_id' => 3,
                        'name' => 'Category 1.1.2',
                        'created' => '2007-03-18 15:30:23',
                        'updated' => '2007-03-18 15:32:31',
                        'ParentCategory' => [
                            'id' => 3,
                            'parent_id' => 2,
                            'name' => 'Category 1.1.1',
                            'created' => '2007-03-18 15:30:23',
                            'updated' => '2007-03-18 15:32:31',
                            'ParentCategory' => [
                                'id' => 2,
                                'parent_id' => 1,
                                'name' => 'Category 1.1',
                                'created' => '2007-03-18 15:30:23',
                                'updated' => '2007-03-18 15:32:31',
                                'ParentCategory' => [
                                    'id' => 1,
                                    'parent_id' => 0,
                                    'name' => 'Category 1',
                                    'created' => '2007-03-18 15:30:23',
                                    'updated' => '2007-03-18 15:32:31',
                                    'ParentCategory' => [],
            ], ], ], ], ], ],
            [
                'CategoryThread' => [
                    'id' => 7,
                    'parent_id' => 6,
                    'name' => 'Category 2.1',
                    'created' => '2007-03-18 15:30:23',
                    'updated' => '2007-03-18 15:32:31',
                ],
                'ParentCategory' => [
                    'id' => 6,
                    'parent_id' => 5,
                    'name' => 'Category 2',
                    'created' => '2007-03-18 15:30:23',
                    'updated' => '2007-03-18 15:32:31',
                    'ParentCategory' => [
                        'id' => 5,
                        'parent_id' => 4,
                        'name' => 'Category 1.1.1.1',
                        'created' => '2007-03-18 15:30:23',
                        'updated' => '2007-03-18 15:32:31',
                        'ParentCategory' => [
                            'id' => 4,
                            'parent_id' => 3,
                            'name' => 'Category 1.1.2',
                            'created' => '2007-03-18 15:30:23',
                            'updated' => '2007-03-18 15:32:31',
                            'ParentCategory' => [
                                'id' => 3,
                                'parent_id' => 2,
                                'name' => 'Category 1.1.1',
                                'created' => '2007-03-18 15:30:23',
                                'updated' => '2007-03-18 15:32:31',
                            'ParentCategory' => [
                                'id' => 2,
                                'parent_id' => 1,
                                'name' => 'Category 1.1',
                                'created' => '2007-03-18 15:30:23',
                                'updated' => '2007-03-18 15:32:31',
                                'ParentCategory' => [
                                    'id' => 1,
                                    'parent_id' => 0,
                                    'name' => 'Category 1',
                                    'created' => '2007-03-18 15:30:23',
                                    'updated' => '2007-03-18 15:32:31',
        ], ], ], ], ], ], ], ];

        $this->db->fullDebug = $fullDebug;
        $this->assertEqual($result, $expected);
    }

    /**
     * testConditionalNumerics method.
     */
    public function testConditionalNumerics()
    {
        $this->loadFixtures('NumericArticle');
        $NumericArticle = new NumericArticle();
        $data = ['title' => '12345abcde'];
        $result = $NumericArticle->find($data);
        $this->assertTrue(!empty($result));

        $data = ['title' => '12345'];
        $result = $NumericArticle->find($data);
        $this->assertTrue(empty($result));
    }

    /**
     * test find('all') method.
     */
    public function testFindAll()
    {
        $this->loadFixtures('User');
        $TestModel = new User();
        $TestModel->cacheQueries = false;

        $result = $TestModel->find('all');
        $expected = [
            [
                'User' => [
                    'id' => '1',
                    'user' => 'mariano',
                    'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:16:23',
                    'updated' => '2007-03-17 01:18:31',
            ], ],
            [
                'User' => [
                    'id' => '2',
                    'user' => 'nate',
                    'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:18:23',
                    'updated' => '2007-03-17 01:20:31',
            ], ],
            [
                'User' => [
                    'id' => '3',
                    'user' => 'larry',
                    'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:20:23',
                    'updated' => '2007-03-17 01:22:31',
            ], ],
            [
                'User' => [
                    'id' => '4',
                    'user' => 'garrett',
                    'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:22:23',
                    'updated' => '2007-03-17 01:24:31',
        ], ], ];
        $this->assertEqual($result, $expected);

        $result = $TestModel->find('all', ['conditions' => 'User.id > 2']);
        $expected = [
            [
                'User' => [
                    'id' => '3',
                    'user' => 'larry',
                    'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:20:23',
                    'updated' => '2007-03-17 01:22:31',
            ], ],
            [
                'User' => [
                    'id' => '4',
                    'user' => 'garrett',
                    'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:22:23',
                    'updated' => '2007-03-17 01:24:31',
        ], ], ];
        $this->assertEqual($result, $expected);

        $result = $TestModel->find('all', [
            'conditions' => ['User.id !=' => '0', 'User.user LIKE' => '%arr%'],
        ]);
        $expected = [
            [
                'User' => [
                    'id' => '3',
                    'user' => 'larry',
                    'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:20:23',
                    'updated' => '2007-03-17 01:22:31',
            ], ],
            [
                'User' => [
                    'id' => '4',
                    'user' => 'garrett',
                    'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:22:23',
                    'updated' => '2007-03-17 01:24:31',
        ], ], ];
        $this->assertEqual($result, $expected);

        $result = $TestModel->find('all', ['conditions' => ['User.id' => '0']]);
        $expected = [];
        $this->assertEqual($result, $expected);

        $result = $TestModel->find('all', [
            'conditions' => ['or' => ['User.id' => '0', 'User.user LIKE' => '%a%'],
        ], ]);

        $expected = [
            [
                'User' => [
                    'id' => '1',
                    'user' => 'mariano',
                    'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:16:23',
                    'updated' => '2007-03-17 01:18:31',
            ], ],
            [
                'User' => [
                    'id' => '2',
                    'user' => 'nate',
                    'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:18:23',
                    'updated' => '2007-03-17 01:20:31',
            ], ],
            [
                'User' => [
                    'id' => '3',
                    'user' => 'larry',
                    'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:20:23',
                    'updated' => '2007-03-17 01:22:31',
            ], ],
            [
                'User' => [
                    'id' => '4',
                    'user' => 'garrett',
                    'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:22:23',
                    'updated' => '2007-03-17 01:24:31',
        ], ], ];
        $this->assertEqual($result, $expected);

        $result = $TestModel->find('all', ['fields' => 'User.id, User.user']);
        $expected = [
                ['User' => ['id' => '1', 'user' => 'mariano']],
                ['User' => ['id' => '2', 'user' => 'nate']],
                ['User' => ['id' => '3', 'user' => 'larry']],
                ['User' => ['id' => '4', 'user' => 'garrett']], ];
        $this->assertEqual($result, $expected);

        $result = $TestModel->find('all', ['fields' => 'User.user', 'order' => 'User.user ASC']);
        $expected = [
                ['User' => ['user' => 'garrett']],
                ['User' => ['user' => 'larry']],
                ['User' => ['user' => 'mariano']],
                ['User' => ['user' => 'nate']], ];
        $this->assertEqual($result, $expected);

        $result = $TestModel->find('all', ['fields' => 'User.user', 'order' => 'User.user DESC']);
        $expected = [
                ['User' => ['user' => 'nate']],
                ['User' => ['user' => 'mariano']],
                ['User' => ['user' => 'larry']],
                ['User' => ['user' => 'garrett']], ];
        $this->assertEqual($result, $expected);

        $result = $TestModel->find('all', ['limit' => 3, 'page' => 1]);

        $expected = [
            [
                'User' => [
                    'id' => '1',
                    'user' => 'mariano',
                    'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:16:23',
                    'updated' => '2007-03-17 01:18:31',
            ], ],
            [
                'User' => [
                    'id' => '2',
                    'user' => 'nate',
                    'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:18:23',
                    'updated' => '2007-03-17 01:20:31',
            ], ],
            [
                'User' => [
                    'id' => '3',
                    'user' => 'larry',
                    'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:20:23',
                    'updated' => '2007-03-17 01:22:31',
        ], ], ];
        $this->assertEqual($result, $expected);

        $ids = [4 => 1, 5 => 3];
        $result = $TestModel->find('all', [
            'conditions' => ['User.id' => $ids],
            'order' => 'User.id',
        ]);
        $expected = [
            [
                'User' => [
                    'id' => '1',
                    'user' => 'mariano',
                    'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:16:23',
                    'updated' => '2007-03-17 01:18:31',
            ], ],
            [
                'User' => [
                    'id' => '3',
                    'user' => 'larry',
                    'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:20:23',
                    'updated' => '2007-03-17 01:22:31',
        ], ], ];
        $this->assertEqual($result, $expected);

        // These tests are expected to fail on SQL Server since the LIMIT/OFFSET
        // hack can't handle small record counts.
        if ('mssql' != $this->db->config['driver']) {
            $result = $TestModel->find('all', ['limit' => 3, 'page' => 2]);
            $expected = [
                [
                    'User' => [
                        'id' => '4',
                        'user' => 'garrett',
                        'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                        'created' => '2007-03-17 01:22:23',
                        'updated' => '2007-03-17 01:24:31',
            ], ], ];
            $this->assertEqual($result, $expected);

            $result = $TestModel->find('all', ['limit' => 3, 'page' => 3]);
            $expected = [];
            $this->assertEqual($result, $expected);
        }
    }

    /**
     * test find('list') method.
     */
    public function testGenerateFindList()
    {
        $this->loadFixtures('Article', 'Apple', 'Post', 'Author', 'User');

        $TestModel = new Article();
        $TestModel->displayField = 'title';

        $result = $TestModel->find('list', [
            'order' => 'Article.title ASC',
        ]);

        $expected = [
            1 => 'First Article',
            2 => 'Second Article',
            3 => 'Third Article',
        ];
        $this->assertEqual($result, $expected);

        $db = &ConnectionManager::getDataSource('test_suite');
        if ('mysql' == $db->config['driver']) {
            $result = $TestModel->find('list', [
                'order' => ['FIELD(Article.id, 3, 2) ASC', 'Article.title ASC'],
            ]);
            $expected = [
                1 => 'First Article',
                3 => 'Third Article',
                2 => 'Second Article',
            ];
            $this->assertEqual($result, $expected);
        }

        $result = Set::combine(
            $TestModel->find('all', [
                'order' => 'Article.title ASC',
                'fields' => ['id', 'title'],
            ]),
            '{n}.Article.id', '{n}.Article.title'
        );
        $expected = [
            1 => 'First Article',
            2 => 'Second Article',
            3 => 'Third Article',
        ];
        $this->assertEqual($result, $expected);

        $result = Set::combine(
            $TestModel->find('all', [
                'order' => 'Article.title ASC',
            ]),
            '{n}.Article.id', '{n}.Article'
        );
        $expected = [
            1 => [
                'id' => 1,
                'user_id' => 1,
                'title' => 'First Article',
                'body' => 'First Article Body',
                'published' => 'Y',
                'created' => '2007-03-18 10:39:23',
                'updated' => '2007-03-18 10:41:31',
            ],
            2 => [
                'id' => 2,
                'user_id' => 3,
                'title' => 'Second Article',
                'body' => 'Second Article Body',
                'published' => 'Y',
                'created' => '2007-03-18 10:41:23',
                'updated' => '2007-03-18 10:43:31',
            ],
            3 => [
                'id' => 3,
                'user_id' => 1,
                'title' => 'Third Article',
                'body' => 'Third Article Body',
                'published' => 'Y',
                'created' => '2007-03-18 10:43:23',
                'updated' => '2007-03-18 10:45:31',
        ], ];

        $this->assertEqual($result, $expected);

        $result = Set::combine(
            $TestModel->find('all', [
                'order' => 'Article.title ASC',
            ]),
            '{n}.Article.id', '{n}.Article', '{n}.Article.user_id'
        );
        $expected = [
            1 => [
                1 => [
                    'id' => 1,
                    'user_id' => 1,
                    'title' => 'First Article',
                    'body' => 'First Article Body',
                    'published' => 'Y',
                    'created' => '2007-03-18 10:39:23',
                    'updated' => '2007-03-18 10:41:31',
                ],
                3 => [
                    'id' => 3,
                    'user_id' => 1,
                    'title' => 'Third Article',
                    'body' => 'Third Article Body',
                    'published' => 'Y',
                    'created' => '2007-03-18 10:43:23',
                    'updated' => '2007-03-18 10:45:31',
                ], ],
            3 => [
                2 => [
                    'id' => 2,
                    'user_id' => 3,
                    'title' => 'Second Article',
                    'body' => 'Second Article Body',
                    'published' => 'Y',
                    'created' => '2007-03-18 10:41:23',
                    'updated' => '2007-03-18 10:43:31',
        ], ], ];

        $this->assertEqual($result, $expected);

        $result = Set::combine(
            $TestModel->find('all', [
                'order' => 'Article.title ASC',
                'fields' => ['id', 'title', 'user_id'],
            ]),
            '{n}.Article.id', '{n}.Article.title', '{n}.Article.user_id'
        );

        $expected = [
            1 => [
                1 => 'First Article',
                3 => 'Third Article',
            ],
            3 => [
                2 => 'Second Article',
        ], ];
        $this->assertEqual($result, $expected);

        $TestModel = new Apple();
        $expected = [
            1 => 'Red Apple 1',
            2 => 'Bright Red Apple',
            3 => 'green blue',
            4 => 'Test Name',
            5 => 'Blue Green',
            6 => 'My new apple',
            7 => 'Some odd color',
        ];

        $this->assertEqual($TestModel->find('list'), $expected);
        $this->assertEqual($TestModel->Parent->find('list'), $expected);

        $TestModel = new Post();
        $result = $TestModel->find('list', [
            'fields' => 'Post.title',
        ]);
        $expected = [
            1 => 'First Post',
            2 => 'Second Post',
            3 => 'Third Post',
        ];
        $this->assertEqual($result, $expected);

        $result = $TestModel->find('list', [
            'fields' => 'title',
        ]);
        $expected = [
            1 => 'First Post',
            2 => 'Second Post',
            3 => 'Third Post',
        ];
        $this->assertEqual($result, $expected);

        $result = $TestModel->find('list', [
            'fields' => ['title', 'id'],
        ]);
        $expected = [
            'First Post' => '1',
            'Second Post' => '2',
            'Third Post' => '3',
        ];
        $this->assertEqual($result, $expected);

        $result = $TestModel->find('list', [
            'fields' => ['title', 'id', 'created'],
        ]);
        $expected = [
            '2007-03-18 10:39:23' => [
                'First Post' => '1',
            ],
            '2007-03-18 10:41:23' => [
                'Second Post' => '2',
            ],
            '2007-03-18 10:43:23' => [
                'Third Post' => '3',
            ],
        ];
        $this->assertEqual($result, $expected);

        $result = $TestModel->find('list', [
            'fields' => ['Post.body'],
        ]);
        $expected = [
            1 => 'First Post Body',
            2 => 'Second Post Body',
            3 => 'Third Post Body',
        ];
        $this->assertEqual($result, $expected);

        $result = $TestModel->find('list', [
            'fields' => ['Post.title', 'Post.body'],
        ]);
        $expected = [
            'First Post' => 'First Post Body',
            'Second Post' => 'Second Post Body',
            'Third Post' => 'Third Post Body',
        ];
        $this->assertEqual($result, $expected);

        $result = $TestModel->find('list', [
            'fields' => ['Post.id', 'Post.title', 'Author.user'],
            'recursive' => 1,
        ]);
        $expected = [
            'mariano' => [
                1 => 'First Post',
                3 => 'Third Post',
            ],
            'larry' => [
                2 => 'Second Post',
        ], ];
        $this->assertEqual($result, $expected);

        $TestModel = new User();
        $result = $TestModel->find('list', [
            'fields' => ['User.user', 'User.password'],
        ]);
        $expected = [
            'mariano' => '5f4dcc3b5aa765d61d8327deb882cf99',
            'nate' => '5f4dcc3b5aa765d61d8327deb882cf99',
            'larry' => '5f4dcc3b5aa765d61d8327deb882cf99',
            'garrett' => '5f4dcc3b5aa765d61d8327deb882cf99',
        ];
        $this->assertEqual($result, $expected);

        $TestModel = new ModifiedAuthor();
        $result = $TestModel->find('list', [
            'fields' => ['Author.id', 'Author.user'],
        ]);
        $expected = [
            1 => 'mariano (CakePHP)',
            2 => 'nate (CakePHP)',
            3 => 'larry (CakePHP)',
            4 => 'garrett (CakePHP)',
        ];
        $this->assertEqual($result, $expected);

        $TestModel = new Article();
        $TestModel->displayField = 'title';
        $result = $TestModel->find('list', [
            'conditions' => ['User.user' => 'mariano'],
            'recursive' => 0,
        ]);
        $expected = [
            1 => 'First Article',
            3 => 'Third Article',
        ];
        $this->assertEqual($result, $expected);
    }

    /**
     * testFindField method.
     */
    public function testFindField()
    {
        $this->loadFixtures('User');
        $TestModel = new User();

        $TestModel->id = 1;
        $result = $TestModel->field('user');
        $this->assertEqual($result, 'mariano');

        $result = $TestModel->field('User.user');
        $this->assertEqual($result, 'mariano');

        $TestModel->id = false;
        $result = $TestModel->field('user', [
            'user' => 'mariano',
        ]);
        $this->assertEqual($result, 'mariano');

        $result = $TestModel->field('COUNT(*) AS count', true);
        $this->assertEqual($result, 4);

        $result = $TestModel->field('COUNT(*)', true);
        $this->assertEqual($result, 4);
    }

    /**
     * testFindUnique method.
     */
    public function testFindUnique()
    {
        $this->loadFixtures('User');
        $TestModel = new User();

        $this->assertFalse($TestModel->isUnique([
            'user' => 'nate',
        ]));
        $TestModel->id = 2;
        $this->assertTrue($TestModel->isUnique([
            'user' => 'nate',
        ]));
        $this->assertFalse($TestModel->isUnique([
            'user' => 'nate',
            'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
        ]));
    }

    /**
     * test find('count') method.
     */
    public function testFindCount()
    {
        $this->loadFixtures('User', 'Project');

        $TestModel = new User();
        $result = $TestModel->find('count');
        $this->assertEqual($result, 4);

        $fullDebug = $this->db->fullDebug;
        $this->db->fullDebug = true;
        $TestModel->order = 'User.id';
        $this->db->_queriesLog = [];
        $result = $TestModel->find('count');
        $this->assertEqual($result, 4);

        $this->assertTrue(isset($this->db->_queriesLog[0]['query']));
        $this->assertNoPattern('/ORDER\s+BY/', $this->db->_queriesLog[0]['query']);
    }

    /**
     * Test that find('first') does not use the id set to the object.
     */
    public function testFindFirstNoIdUsed()
    {
        $this->loadFixtures('Project');

        $Project = new Project();
        $Project->id = 3;
        $result = $Project->find('first');

        $this->assertEqual($result['Project']['name'], 'Project 1', 'Wrong record retrieved');
    }

    /**
     * test find with COUNT(DISTINCT field).
     */
    public function testFindCountDistinct()
    {
        $skip = $this->skipIf(
            'sqlite' == $this->db->config['driver'],
            'SELECT COUNT(DISTINCT field) is not compatible with SQLite'
        );
        if ($skip) {
            return;
        }
        $this->loadFixtures('Project');
        $TestModel = new Project();
        $TestModel->create(['name' => 'project']) && $TestModel->save();
        $TestModel->create(['name' => 'project']) && $TestModel->save();
        $TestModel->create(['name' => 'project']) && $TestModel->save();

        $result = $TestModel->find('count', ['fields' => 'DISTINCT name']);
        $this->assertEqual($result, 4);
    }

    /**
     * Test find(count) with Db::expression.
     */
    public function testFindCountWithDbExpressions()
    {
        if ($this->skipIf('postgres' == $this->db->config['driver'], '%s testFindCountWithExpressions is not compatible with Postgres')) {
            return;
        }
        $this->loadFixtures('Project');
        $db = ConnectionManager::getDataSource('test_suite');
        $TestModel = new Project();

        $result = $TestModel->find('count', ['conditions' => [
            $db->expression('Project.name = \'Project 3\''),
        ]]);
        $this->assertEqual($result, 1);

        $result = $TestModel->find('count', ['conditions' => [
            'Project.name' => $db->expression('\'Project 3\''),
        ]]);
        $this->assertEqual($result, 1);
    }

    /**
     * testFindMagic method.
     */
    public function testFindMagic()
    {
        $this->loadFixtures('User');
        $TestModel = new User();

        $result = $TestModel->findByUser('mariano');
        $expected = [
            'User' => [
                'id' => '1',
                'user' => 'mariano',
                'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                'created' => '2007-03-17 01:16:23',
                'updated' => '2007-03-17 01:18:31',
        ], ];
        $this->assertEqual($result, $expected);

        $result = $TestModel->findByPassword('5f4dcc3b5aa765d61d8327deb882cf99');
        $expected = ['User' => [
            'id' => '1',
            'user' => 'mariano',
            'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
            'created' => '2007-03-17 01:16:23',
            'updated' => '2007-03-17 01:18:31',
        ]];
        $this->assertEqual($result, $expected);
    }

    /**
     * testRead method.
     */
    public function testRead()
    {
        $this->loadFixtures('User', 'Article');
        $TestModel = new User();

        $result = $TestModel->read();
        $this->assertFalse($result);

        $TestModel->id = 2;
        $result = $TestModel->read();
        $expected = [
            'User' => [
                'id' => '2',
                'user' => 'nate',
                'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                'created' => '2007-03-17 01:18:23',
                'updated' => '2007-03-17 01:20:31',
        ], ];
        $this->assertEqual($result, $expected);

        $result = $TestModel->read(null, 2);
        $expected = [
            'User' => [
                'id' => '2',
                'user' => 'nate',
                'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                'created' => '2007-03-17 01:18:23',
                'updated' => '2007-03-17 01:20:31',
        ], ];
        $this->assertEqual($result, $expected);

        $TestModel->id = 2;
        $result = $TestModel->read(['id', 'user']);
        $expected = ['User' => ['id' => '2', 'user' => 'nate']];
        $this->assertEqual($result, $expected);

        $result = $TestModel->read('id, user', 2);
        $expected = [
            'User' => [
                'id' => '2',
                'user' => 'nate',
        ], ];
        $this->assertEqual($result, $expected);

        $result = $TestModel->bindModel(['hasMany' => ['Article']]);
        $this->assertTrue($result);

        $TestModel->id = 1;
        $result = $TestModel->read('id, user');
        $expected = [
            'User' => [
                'id' => '1',
                'user' => 'mariano',
            ],
            'Article' => [
                [
                    'id' => '1',
                    'user_id' => '1',
                    'title' => 'First Article',
                    'body' => 'First Article Body',
                    'published' => 'Y',
                    'created' => '2007-03-18 10:39:23',
                    'updated' => '2007-03-18 10:41:31',
                ],
                [
                    'id' => '3',
                    'user_id' => '1',
                    'title' => 'Third Article',
                    'body' => 'Third Article Body',
                    'published' => 'Y',
                    'created' => '2007-03-18 10:43:23',
                    'updated' => '2007-03-18 10:45:31',
        ], ], ];
        $this->assertEqual($result, $expected);
    }

    /**
     * testRecursiveRead method.
     */
    public function testRecursiveRead()
    {
        $this->loadFixtures(
            'User',
            'Article',
            'Comment',
            'Tag',
            'ArticlesTag',
            'Featured',
            'ArticleFeatured'
        );
        $TestModel = new User();

        $result = $TestModel->bindModel(['hasMany' => ['Article']], false);
        $this->assertTrue($result);

        $TestModel->recursive = 0;
        $result = $TestModel->read('id, user', 1);
        $expected = [
            'User' => ['id' => '1', 'user' => 'mariano'],
        ];
        $this->assertEqual($result, $expected);

        $TestModel->recursive = 1;
        $result = $TestModel->read('id, user', 1);
        $expected = [
            'User' => [
                'id' => '1',
                'user' => 'mariano',
            ],
            'Article' => [
                [
                    'id' => '1',
                    'user_id' => '1',
                    'title' => 'First Article',
                    'body' => 'First Article Body',
                    'published' => 'Y',
                    'created' => '2007-03-18 10:39:23',
                    'updated' => '2007-03-18 10:41:31',
                ],
                [
                    'id' => '3',
                    'user_id' => '1',
                    'title' => 'Third Article',
                    'body' => 'Third Article Body',
                    'published' => 'Y',
                    'created' => '2007-03-18 10:43:23',
                    'updated' => '2007-03-18 10:45:31',
        ], ], ];
        $this->assertEqual($result, $expected);

        $TestModel->recursive = 2;
        $result = $TestModel->read('id, user', 3);
        $expected = [
            'User' => [
                'id' => '3',
                'user' => 'larry',
            ],
            'Article' => [
                [
                    'id' => '2',
                    'user_id' => '3',
                    'title' => 'Second Article',
                    'body' => 'Second Article Body',
                    'published' => 'Y',
                    'created' => '2007-03-18 10:41:23',
                    'updated' => '2007-03-18 10:43:31',
                    'User' => [
                        'id' => '3',
                        'user' => 'larry',
                        'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                        'created' => '2007-03-17 01:20:23',
                        'updated' => '2007-03-17 01:22:31',
                    ],
                    'Comment' => [
                        [
                            'id' => '5',
                            'article_id' => '2',
                            'user_id' => '1',
                            'comment' => 'First Comment for Second Article',
                            'published' => 'Y',
                            'created' => '2007-03-18 10:53:23',
                            'updated' => '2007-03-18 10:55:31',
                        ],
                        [
                            'id' => '6',
                            'article_id' => '2',
                            'user_id' => '2',
                            'comment' => 'Second Comment for Second Article',
                            'published' => 'Y',
                            'created' => '2007-03-18 10:55:23',
                            'updated' => '2007-03-18 10:57:31',
                    ], ],
                    'Tag' => [
                        [
                            'id' => '1',
                            'tag' => 'tag1',
                            'created' => '2007-03-18 12:22:23',
                            'updated' => '2007-03-18 12:24:31',
                        ],
                        [
                            'id' => '3',
                            'tag' => 'tag3',
                            'created' => '2007-03-18 12:26:23',
                            'updated' => '2007-03-18 12:28:31',
        ], ], ], ], ];
        $this->assertEqual($result, $expected);
    }

    public function testRecursiveFindAll()
    {
        $this->db->truncate(new Featured());

        $this->loadFixtures(
            'User',
            'Article',
            'Comment',
            'Tag',
            'ArticlesTag',
            'Attachment',
            'ArticleFeatured',
            'Featured',
            'Category'
        );
        $TestModel = new Article();

        $result = $TestModel->find('all', ['conditions' => ['Article.user_id' => 1]]);
        $expected = [
            [
                'Article' => [
                    'id' => '1',
                    'user_id' => '1',
                    'title' => 'First Article',
                    'body' => 'First Article Body',
                    'published' => 'Y',
                    'created' => '2007-03-18 10:39:23',
                    'updated' => '2007-03-18 10:41:31',
                ],
                'User' => [
                    'id' => '1',
                    'user' => 'mariano',
                    'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:16:23',
                    'updated' => '2007-03-17 01:18:31',
                ],
                'Comment' => [
                    [
                        'id' => '1',
                        'article_id' => '1',
                        'user_id' => '2',
                        'comment' => 'First Comment for First Article',
                        'published' => 'Y',
                        'created' => '2007-03-18 10:45:23',
                        'updated' => '2007-03-18 10:47:31',
                    ],
                    [
                        'id' => '2',
                        'article_id' => '1',
                        'user_id' => '4',
                        'comment' => 'Second Comment for First Article',
                        'published' => 'Y',
                        'created' => '2007-03-18 10:47:23',
                        'updated' => '2007-03-18 10:49:31',
                    ],
                    [
                        'id' => '3',
                        'article_id' => '1',
                        'user_id' => '1',
                        'comment' => 'Third Comment for First Article',
                        'published' => 'Y',
                        'created' => '2007-03-18 10:49:23',
                        'updated' => '2007-03-18 10:51:31',
                    ],
                    [
                        'id' => '4',
                        'article_id' => '1',
                        'user_id' => '1',
                        'comment' => 'Fourth Comment for First Article',
                        'published' => 'N',
                        'created' => '2007-03-18 10:51:23',
                        'updated' => '2007-03-18 10:53:31',
                    ],
                ],
                'Tag' => [
                    [
                        'id' => '1',
                        'tag' => 'tag1',
                        'created' => '2007-03-18 12:22:23',
                        'updated' => '2007-03-18 12:24:31',
                    ],
                    [
                        'id' => '2',
                        'tag' => 'tag2',
                        'created' => '2007-03-18 12:24:23',
                        'updated' => '2007-03-18 12:26:31',
            ], ], ],
            [
                'Article' => [
                    'id' => '3',
                    'user_id' => '1',
                    'title' => 'Third Article',
                    'body' => 'Third Article Body',
                    'published' => 'Y',
                    'created' => '2007-03-18 10:43:23',
                    'updated' => '2007-03-18 10:45:31',
                ],
                'User' => [
                    'id' => '1',
                    'user' => 'mariano',
                    'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:16:23',
                    'updated' => '2007-03-17 01:18:31',
                ],
                'Comment' => [],
                'Tag' => [],
            ],
        ];
        $this->assertEqual($result, $expected);

        $result = $TestModel->find('all', [
            'conditions' => ['Article.user_id' => 3],
            'limit' => 1,
            'recursive' => 2,
        ]);

        $expected = [
            [
                'Article' => [
                    'id' => '2',
                    'user_id' => '3',
                    'title' => 'Second Article',
                    'body' => 'Second Article Body',
                    'published' => 'Y',
                    'created' => '2007-03-18 10:41:23',
                    'updated' => '2007-03-18 10:43:31',
                ],
                'User' => [
                    'id' => '3',
                    'user' => 'larry',
                    'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:20:23',
                    'updated' => '2007-03-17 01:22:31',
                ],
                'Comment' => [
                    [
                        'id' => '5',
                        'article_id' => '2',
                        'user_id' => '1',
                        'comment' => 'First Comment for Second Article',
                        'published' => 'Y',
                        'created' => '2007-03-18 10:53:23',
                        'updated' => '2007-03-18 10:55:31',
                        'Article' => [
                            'id' => '2',
                            'user_id' => '3',
                            'title' => 'Second Article',
                            'body' => 'Second Article Body',
                            'published' => 'Y',
                            'created' => '2007-03-18 10:41:23',
                            'updated' => '2007-03-18 10:43:31',
                        ],
                        'User' => [
                            'id' => '1',
                            'user' => 'mariano',
                            'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                            'created' => '2007-03-17 01:16:23',
                            'updated' => '2007-03-17 01:18:31',
                        ],
                        'Attachment' => [
                            'id' => '1',
                            'comment_id' => 5,
                            'attachment' => 'attachment.zip',
                            'created' => '2007-03-18 10:51:23',
                            'updated' => '2007-03-18 10:53:31',
                        ],
                    ],
                    [
                        'id' => '6',
                        'article_id' => '2',
                        'user_id' => '2',
                        'comment' => 'Second Comment for Second Article',
                        'published' => 'Y',
                        'created' => '2007-03-18 10:55:23',
                        'updated' => '2007-03-18 10:57:31',
                        'Article' => [
                            'id' => '2',
                            'user_id' => '3',
                            'title' => 'Second Article',
                            'body' => 'Second Article Body',
                            'published' => 'Y',
                            'created' => '2007-03-18 10:41:23',
                            'updated' => '2007-03-18 10:43:31',
                        ],
                        'User' => [
                            'id' => '2',
                            'user' => 'nate',
                            'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                            'created' => '2007-03-17 01:18:23',
                            'updated' => '2007-03-17 01:20:31',
                        ],
                        'Attachment' => false,
                    ],
                ],
                'Tag' => [
                    [
                        'id' => '1',
                        'tag' => 'tag1',
                        'created' => '2007-03-18 12:22:23',
                        'updated' => '2007-03-18 12:24:31',
                    ],
                    [
                        'id' => '3',
                        'tag' => 'tag3',
                        'created' => '2007-03-18 12:26:23',
                        'updated' => '2007-03-18 12:28:31',
        ], ], ], ];

        $this->assertEqual($result, $expected);

        $Featured = new Featured();

        $Featured->recursive = 2;
        $Featured->bindModel([
            'belongsTo' => [
                'ArticleFeatured' => [
                    'conditions' => "ArticleFeatured.published = 'Y'",
                    'fields' => 'id, title, user_id, published',
                ],
            ],
        ]);

        $Featured->ArticleFeatured->unbindModel([
            'hasMany' => ['Attachment', 'Comment'],
            'hasAndBelongsToMany' => ['Tag'], ]
        );

        $orderBy = 'ArticleFeatured.id ASC';
        $result = $Featured->find('all', [
            'order' => $orderBy, 'limit' => 3,
        ]);

        $expected = [
            [
                'Featured' => [
                    'id' => '1',
                    'article_featured_id' => '1',
                    'category_id' => '1',
                    'published_date' => '2007-03-31 10:39:23',
                    'end_date' => '2007-05-15 10:39:23',
                    'created' => '2007-03-18 10:39:23',
                    'updated' => '2007-03-18 10:41:31',
                ],
                'ArticleFeatured' => [
                    'id' => '1',
                    'title' => 'First Article',
                    'user_id' => '1',
                    'published' => 'Y',
                    'User' => [
                        'id' => '1',
                        'user' => 'mariano',
                        'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                        'created' => '2007-03-17 01:16:23',
                        'updated' => '2007-03-17 01:18:31',
                    ],
                    'Category' => [],
                    'Featured' => [
                        'id' => '1',
                        'article_featured_id' => '1',
                        'category_id' => '1',
                        'published_date' => '2007-03-31 10:39:23',
                        'end_date' => '2007-05-15 10:39:23',
                        'created' => '2007-03-18 10:39:23',
                        'updated' => '2007-03-18 10:41:31',
                ], ],
                'Category' => [
                    'id' => '1',
                    'parent_id' => '0',
                    'name' => 'Category 1',
                    'created' => '2007-03-18 15:30:23',
                    'updated' => '2007-03-18 15:32:31',
                ], ],
            [
                'Featured' => [
                    'id' => '2',
                    'article_featured_id' => '2',
                    'category_id' => '1',
                    'published_date' => '2007-03-31 10:39:23',
                    'end_date' => '2007-05-15 10:39:23',
                    'created' => '2007-03-18 10:39:23',
                    'updated' => '2007-03-18 10:41:31',
                ],
                'ArticleFeatured' => [
                    'id' => '2',
                    'title' => 'Second Article',
                    'user_id' => '3',
                    'published' => 'Y',
                    'User' => [
                        'id' => '3',
                        'user' => 'larry',
                        'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                        'created' => '2007-03-17 01:20:23',
                        'updated' => '2007-03-17 01:22:31',
                    ],
                    'Category' => [],
                    'Featured' => [
                        'id' => '2',
                        'article_featured_id' => '2',
                        'category_id' => '1',
                        'published_date' => '2007-03-31 10:39:23',
                        'end_date' => '2007-05-15 10:39:23',
                        'created' => '2007-03-18 10:39:23',
                        'updated' => '2007-03-18 10:41:31',
                ], ],
                'Category' => [
                    'id' => '1',
                    'parent_id' => '0',
                    'name' => 'Category 1',
                    'created' => '2007-03-18 15:30:23',
                    'updated' => '2007-03-18 15:32:31',
        ], ], ];
        $this->assertEqual($result, $expected);
    }

    /**
     * testRecursiveFindAllWithLimit method.
     */
    public function testRecursiveFindAllWithLimit()
    {
        $this->loadFixtures('Article', 'User', 'Tag', 'ArticlesTag', 'Comment', 'Attachment');
        $TestModel = new Article();

        $TestModel->hasMany['Comment']['limit'] = 2;

        $result = $TestModel->find('all', [
            'conditions' => ['Article.user_id' => 1],
        ]);
        $expected = [
            [
                'Article' => [
                    'id' => '1',
                    'user_id' => '1',
                    'title' => 'First Article',
                    'body' => 'First Article Body',
                    'published' => 'Y',
                    'created' => '2007-03-18 10:39:23',
                    'updated' => '2007-03-18 10:41:31',
                ],
                'User' => [
                    'id' => '1',
                    'user' => 'mariano',
                    'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:16:23',
                    'updated' => '2007-03-17 01:18:31',
                ],
                'Comment' => [
                    [
                        'id' => '1',
                        'article_id' => '1',
                        'user_id' => '2',
                        'comment' => 'First Comment for First Article',
                        'published' => 'Y',
                        'created' => '2007-03-18 10:45:23',
                        'updated' => '2007-03-18 10:47:31',
                    ],
                    [
                        'id' => '2',
                        'article_id' => '1',
                        'user_id' => '4',
                        'comment' => 'Second Comment for First Article',
                        'published' => 'Y',
                        'created' => '2007-03-18 10:47:23',
                        'updated' => '2007-03-18 10:49:31',
                    ],
                ],
                'Tag' => [
                    [
                        'id' => '1',
                        'tag' => 'tag1',
                        'created' => '2007-03-18 12:22:23',
                        'updated' => '2007-03-18 12:24:31',
                    ],
                    [
                        'id' => '2',
                        'tag' => 'tag2',
                        'created' => '2007-03-18 12:24:23',
                        'updated' => '2007-03-18 12:26:31',
            ], ], ],
            [
                'Article' => [
                    'id' => '3',
                    'user_id' => '1',
                    'title' => 'Third Article',
                    'body' => 'Third Article Body',
                    'published' => 'Y',
                    'created' => '2007-03-18 10:43:23',
                    'updated' => '2007-03-18 10:45:31',
                ],
                'User' => [
                    'id' => '1',
                    'user' => 'mariano',
                    'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:16:23',
                    'updated' => '2007-03-17 01:18:31',
                ],
                'Comment' => [],
                'Tag' => [],
            ],
        ];
        $this->assertEqual($result, $expected);

        $TestModel->hasMany['Comment']['limit'] = 1;

        $result = $TestModel->find('all', [
            'conditions' => ['Article.user_id' => 3],
            'limit' => 1,
            'recursive' => 2,
        ]);
        $expected = [
            [
                'Article' => [
                    'id' => '2',
                    'user_id' => '3',
                    'title' => 'Second Article',
                    'body' => 'Second Article Body',
                    'published' => 'Y',
                    'created' => '2007-03-18 10:41:23',
                    'updated' => '2007-03-18 10:43:31',
                ],
                'User' => [
                    'id' => '3',
                    'user' => 'larry',
                    'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:20:23',
                    'updated' => '2007-03-17 01:22:31',
                ],
                'Comment' => [
                    [
                        'id' => '5',
                        'article_id' => '2',
                        'user_id' => '1',
                        'comment' => 'First Comment for Second Article',
                        'published' => 'Y',
                        'created' => '2007-03-18 10:53:23',
                        'updated' => '2007-03-18 10:55:31',
                        'Article' => [
                            'id' => '2',
                            'user_id' => '3',
                            'title' => 'Second Article',
                            'body' => 'Second Article Body',
                            'published' => 'Y',
                            'created' => '2007-03-18 10:41:23',
                            'updated' => '2007-03-18 10:43:31',
                        ],
                        'User' => [
                            'id' => '1',
                            'user' => 'mariano',
                            'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                            'created' => '2007-03-17 01:16:23',
                            'updated' => '2007-03-17 01:18:31',
                        ],
                        'Attachment' => [
                            'id' => '1',
                            'comment_id' => 5,
                            'attachment' => 'attachment.zip',
                            'created' => '2007-03-18 10:51:23',
                            'updated' => '2007-03-18 10:53:31',
                        ],
                    ],
                ],
                'Tag' => [
                    [
                        'id' => '1',
                        'tag' => 'tag1',
                        'created' => '2007-03-18 12:22:23',
                        'updated' => '2007-03-18 12:24:31',
                    ],
                    [
                        'id' => '3',
                        'tag' => 'tag3',
                        'created' => '2007-03-18 12:26:23',
                        'updated' => '2007-03-18 12:28:31',
                    ],
                ],
            ],
        ];
        $this->assertEqual($result, $expected);
    }

    /**
     * Testing availability of $this->findQueryType in Model callbacks.
     */
    public function testFindQueryTypeInCallbacks()
    {
        $this->loadFixtures('Comment');
        $Comment = new AgainModifiedComment();
        $comments = $Comment->find('all');
        $this->assertEqual($comments[0]['Comment']['querytype'], 'all');
        $comments = $Comment->find('first');
        $this->assertEqual($comments['Comment']['querytype'], 'first');
    }

    /**
     * testVirtualFields().
     *
     * Test correct fetching of virtual fields
     * currently is not possible to do Relation.virtualField
     */
    public function testVirtualFields()
    {
        $this->loadFixtures('Post', 'Author');
        $Post = &ClassRegistry::init('Post');
        $Post->virtualFields = ['two' => '1 + 1'];
        $result = $Post->find('first');
        $this->assertEqual($result['Post']['two'], 2);

        $Post->Author->virtualFields = ['false' => '1 = 2'];
        $result = $Post->find('first');
        $this->assertEqual($result['Post']['two'], 2);
        $this->assertEqual($result['Author']['false'], false);

        $result = $Post->find('first', ['fields' => ['author_id']]);
        $this->assertFalse(isset($result['Post']['two']));
        $this->assertFalse(isset($result['Author']['false']));

        $result = $Post->find('first', ['fields' => ['author_id', 'two']]);
        $this->assertEqual($result['Post']['two'], 2);
        $this->assertFalse(isset($result['Author']['false']));

        $result = $Post->find('first', ['fields' => ['two']]);
        $this->assertEqual($result['Post']['two'], 2);

        $Post->id = 1;
        $result = $Post->field('two');
        $this->assertEqual($result, 2);

        $result = $Post->find('first', [
            'conditions' => ['two' => 2],
            'limit' => 1,
        ]);
        $this->assertEqual($result['Post']['two'], 2);

        $result = $Post->find('first', [
            'conditions' => ['two <' => 3],
            'limit' => 1,
        ]);
        $this->assertEqual($result['Post']['two'], 2);

        $result = $Post->find('first', [
            'conditions' => ['NOT' => ['two >' => 3]],
            'limit' => 1,
        ]);
        $this->assertEqual($result['Post']['two'], 2);

        $dbo = &$Post->getDataSource();
        $Post->virtualFields = ['other_field' => 'Post.id + 1'];
        $result = $Post->find('first', [
            'conditions' => ['other_field' => 3],
            'limit' => 1,
        ]);
        $this->assertEqual($result['Post']['id'], 2);

        $Post->virtualFields = ['other_field' => 'Post.id + 1'];
        $result = $Post->find('all', [
            'fields' => [$dbo->calculate($Post, 'max', ['other_field'])],
        ]);
        $this->assertEqual($result[0][0]['other_field'], 4);

        ClassRegistry::flush();
        $Writing = &ClassRegistry::init(['class' => 'Post', 'alias' => 'Writing'], 'Model');
        $Writing->virtualFields = ['two' => '1 + 1'];
        $result = $Writing->find('first');
        $this->assertEqual($result['Writing']['two'], 2);

        $Post->create();
        $Post->virtualFields = ['other_field' => 'COUNT(Post.id) + 1'];
        $result = $Post->field('other_field');
        $this->assertEqual($result, 4);

        if ($this->skipIf('postgres' == $this->db->config['driver'], 'The rest of virtualFieds test is not compatible with Postgres')) {
            return;
        }
        ClassRegistry::flush();
        $Post = &ClassRegistry::init('Post');

        $Post->create();
        $Post->virtualFields = [
            'year' => 'YEAR(Post.created)',
            'unique_test_field' => 'COUNT(Post.id)',
        ];

        $expectation = [
            'Post' => [
                'year' => 2007,
                'unique_test_field' => 3,
            ],
        ];

        $result = $Post->find('first', [
            'fields' => array_keys($Post->virtualFields),
            'group' => ['year'],
        ]);

        $this->assertEqual($result, $expectation);

        $Author = &ClassRegistry::init('Author');
        $Author->virtualFields = [
            'full_name' => 'CONCAT(Author.user, " ", Author.id)',
        ];

        $result = $Author->find('first', [
            'conditions' => ['Author.user' => 'mariano'],
            'fields' => ['Author.password', 'Author.full_name'],
            'recursive' => -1,
        ]);
        $this->assertTrue(isset($result['Author']['full_name']));

        $result = $Author->find('first', [
            'conditions' => ['Author.user' => 'mariano'],
            'fields' => ['Author.full_name', 'Author.password'],
            'recursive' => -1,
        ]);
        $this->assertTrue(isset($result['Author']['full_name']));
    }

    /**
     * Test that virtual fields work with SQL constants.
     */
    public function testVirtualFieldAsAConstant()
    {
        $this->loadFixtures('Post', 'Author');
        $Post = &ClassRegistry::init('Post');
        $Post->virtualFields = ['empty' => 'NULL'];
        $result = $Post->find('first');
        $this->assertNull($result['Post']['empty']);
    }

    /**
     * test that virtual fields work when they don't contain functions.
     */
    public function testVirtualFieldAsAString()
    {
        $this->loadFixtures('Post', 'Author');
        $Post = new Post();
        $Post->virtualFields = [
            'writer' => 'Author.user',
        ];
        $result = $Post->find('first');
        $this->assertTrue(isset($result['Post']['writer']), 'virtual field not fetched %s');
    }

    /**
     * test that isVirtualField will accept both aliased and non aliased fieldnames.
     */
    public function testIsVirtualField()
    {
        $this->loadFixtures('Post');
        $Post = &ClassRegistry::init('Post');
        $Post->virtualFields = ['other_field' => 'COUNT(Post.id) + 1'];

        $this->assertTrue($Post->isVirtualField('other_field'));
        $this->assertTrue($Post->isVirtualField('Post.other_field'));
        $this->assertFalse($Post->isVirtualField('Comment.other_field'), 'Other models should not match.');
        $this->assertFalse($Post->isVirtualField('id'));
        $this->assertFalse($Post->isVirtualField('Post.id'));
        $this->assertFalse($Post->isVirtualField([]));
    }

    /**
     * test that getting virtual fields works with and without model alias attached.
     */
    public function testGetVirtualField()
    {
        $this->loadFixtures('Post');
        $Post = &ClassRegistry::init('Post');
        $Post->virtualFields = ['other_field' => 'COUNT(Post.id) + 1'];

        $this->assertEqual($Post->getVirtualField('other_field'), $Post->virtualFields['other_field']);
        $this->assertEqual($Post->getVirtualField('Post.other_field'), $Post->virtualFields['other_field']);
    }
}
