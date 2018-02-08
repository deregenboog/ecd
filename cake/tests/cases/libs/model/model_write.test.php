<?php

/* SVN FILE: $Id: model.test.php 8225 2009-07-08 03:25:30Z mark_story $ */

/**
 * ModelWriteTest file.
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
 * ModelWriteTest.
 */
class ModelWriteTest extends BaseModelTest
{
    /**
     * testInsertAnotherHabtmRecordWithSameForeignKey method.
     */
    public function testInsertAnotherHabtmRecordWithSameForeignKey()
    {
        $this->loadFixtures('JoinA', 'JoinB', 'JoinAB');
        $TestModel = new JoinA();

        $result = $TestModel->JoinAsJoinB->findById(1);
        $expected = [
            'JoinAsJoinB' => [
                'id' => 1,
                'join_a_id' => 1,
                'join_b_id' => 2,
                'other' => 'Data for Join A 1 Join B 2',
                'created' => '2008-01-03 10:56:33',
                'updated' => '2008-01-03 10:56:33',
        ], ];
        $this->assertEqual($result, $expected);

        $TestModel->JoinAsJoinB->create();
        $result = $TestModel->JoinAsJoinB->save([
            'join_a_id' => 1,
            'join_b_id' => 1,
            'other' => 'Data for Join A 1 Join B 1',
            'created' => '2008-01-03 10:56:44',
            'updated' => '2008-01-03 10:56:44',
        ]);
        $this->assertTrue($result);
        $lastInsertId = $TestModel->JoinAsJoinB->getLastInsertID();
        $this->assertTrue(null != $lastInsertId);

        $result = $TestModel->JoinAsJoinB->findById(1);
        $expected = [
            'JoinAsJoinB' => [
                'id' => 1,
                'join_a_id' => 1,
                'join_b_id' => 2,
                'other' => 'Data for Join A 1 Join B 2',
                'created' => '2008-01-03 10:56:33',
                'updated' => '2008-01-03 10:56:33',
        ], ];
        $this->assertEqual($result, $expected);

        $updatedValue = 'UPDATED Data for Join A 1 Join B 2';
        $TestModel->JoinAsJoinB->id = 1;
        $result = $TestModel->JoinAsJoinB->saveField('other', $updatedValue, false);
        $this->assertTrue($result);

        $result = $TestModel->JoinAsJoinB->findById(1);
        $this->assertEqual($result['JoinAsJoinB']['other'], $updatedValue);
    }

    /**
     * testSaveDateAsFirstEntry method.
     */
    public function testSaveDateAsFirstEntry()
    {
        $this->loadFixtures('Article');

        $Article = new Article();

        $data = [
            'Article' => [
                'created' => [
                    'day' => '1',
                    'month' => '1',
                    'year' => '2008',
                ],
                'title' => 'Test Title',
                'user_id' => 1,
        ], ];
        $Article->create();
        $this->assertTrue($Article->save($data));

        $testResult = $Article->find(['Article.title' => 'Test Title']);

        $this->assertEqual($testResult['Article']['title'], $data['Article']['title']);
        $this->assertEqual($testResult['Article']['created'], '2008-01-01 00:00:00');
    }

    /**
     * testUnderscoreFieldSave method.
     */
    public function testUnderscoreFieldSave()
    {
        $this->loadFixtures('UnderscoreField');
        $UnderscoreField = new UnderscoreField();

        $currentCount = $UnderscoreField->find('count');
        $this->assertEqual($currentCount, 3);
        $data = ['UnderscoreField' => [
            'user_id' => '1',
            'my_model_has_a_field' => 'Content here',
            'body' => 'Body',
            'published' => 'Y',
            'another_field' => 4,
        ]];
        $ret = $UnderscoreField->save($data);
        $this->assertTrue($ret);

        $currentCount = $UnderscoreField->find('count');
        $this->assertEqual($currentCount, 4);
    }

    /**
     * testAutoSaveUuid method.
     */
    public function testAutoSaveUuid()
    {
        // SQLite does not support non-integer primary keys
        $this->skipIf('sqlite' == $this->db->config['driver']);

        $this->loadFixtures('Uuid');
        $TestModel = new Uuid();

        $TestModel->save(['title' => 'Test record']);
        $result = $TestModel->findByTitle('Test record');
        $this->assertEqual(
            array_keys($result['Uuid']),
            ['id', 'title', 'count', 'created', 'updated']
        );
        $this->assertEqual(strlen($result['Uuid']['id']), 36);
    }

    /**
     * Ensure that if the id key is null but present the save doesn't fail (with an
     * x sql error: "Column id specified twice").
     */
    public function testSaveUuidNull()
    {
        // SQLite does not support non-integer primary keys
        $this->skipIf('sqlite' == $this->db->config['driver']);

        $this->loadFixtures('Uuid');
        $TestModel = new Uuid();

        $TestModel->save(['title' => 'Test record', 'id' => null]);
        $result = $TestModel->findByTitle('Test record');
        $this->assertEqual(
            array_keys($result['Uuid']),
            ['id', 'title', 'count', 'created', 'updated']
        );
        $this->assertEqual(strlen($result['Uuid']['id']), 36);
    }

    /**
     * testZeroDefaultFieldValue method.
     */
    public function testZeroDefaultFieldValue()
    {
        $this->skipIf(
            'sqlite' == $this->db->config['driver'],
            '%s SQLite uses loose typing, this operation is unsupported'
        );
        $this->loadFixtures('DataTest');
        $TestModel = new DataTest();

        $TestModel->create([]);
        $TestModel->save();
        $result = $TestModel->findById($TestModel->id);
        $this->assertIdentical($result['DataTest']['count'], '0');
        $this->assertIdentical($result['DataTest']['float'], '0');
    }

    /**
     * testNonNumericHabtmJoinKey method.
     */
    public function testNonNumericHabtmJoinKey()
    {
        $this->loadFixtures('Post', 'Tag', 'PostsTag');
        $Post = new Post();
        $Post->bindModel([
            'hasAndBelongsToMany' => ['Tag'],
        ]);
        $Post->Tag->primaryKey = 'tag';

        $result = $Post->find('all');
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
                    'id' => null,
                    'user' => null,
                    'password' => null,
                    'created' => null,
                    'updated' => null,
                    'test' => 'working',
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
                    'id' => null,
                    'user' => null,
                    'password' => null,
                    'created' => null,
                    'updated' => null,
                    'test' => 'working',
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
            ], ], ],
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
                    'id' => null,
                    'user' => null,
                    'password' => null,
                    'created' => null,
                    'updated' => null,
                    'test' => 'working',
                ],
                'Tag' => [],
        ], ];
        $this->assertEqual($result, $expected);
    }

    /**
     * Tests validation parameter order in custom validation methods.
     */
    public function testAllowSimulatedFields()
    {
        $TestModel = new ValidationTest1();

        $TestModel->create([
            'title' => 'foo',
            'bar' => 'baz',
        ]);
        $expected = [
            'ValidationTest1' => [
                'title' => 'foo',
                'bar' => 'baz',
        ], ];
        $this->assertEqual($TestModel->data, $expected);
    }

    /**
     * test that Caches are getting cleared on save().
     * ensure that both inflections of controller names are getting cleared
     * as url for controller could be either overallFavorites/index or overall_favorites/index.
     */
    public function testCacheClearOnSave()
    {
        $_back = [
            'check' => Configure::read('Cache.check'),
            'disable' => Configure::read('Cache.disable'),
        ];
        Configure::write('Cache.check', true);
        Configure::write('Cache.disable', false);

        $this->loadFixtures('OverallFavorite');
        $OverallFavorite = new OverallFavorite();

        touch(CACHE.'views'.DS.'some_dir_overallfavorites_index.php');
        touch(CACHE.'views'.DS.'some_dir_overall_favorites_index.php');

        $data = [
            'OverallFavorite' => [
                'id' => 22,
                'model_type' => '8-track',
                'model_id' => '3',
                'priority' => '1',
            ],
        ];
        $OverallFavorite->create($data);
        $OverallFavorite->save();

        $this->assertFalse(file_exists(CACHE.'views'.DS.'some_dir_overallfavorites_index.php'));
        $this->assertFalse(file_exists(CACHE.'views'.DS.'some_dir_overall_favorites_index.php'));

        Configure::write('Cache.check', $_back['check']);
        Configure::write('Cache.disable', $_back['disable']);
    }

    /**
     * testSaveWithCounterCache method.
     */
    public function testSaveWithCounterCache()
    {
        $this->loadFixtures('Syfile', 'Item');
        $TestModel = new Syfile();
        $TestModel2 = new Item();

        $result = $TestModel->findById(1);
        $this->assertIdentical($result['Syfile']['item_count'], null);

        $TestModel2->save([
            'name' => 'Item 7',
            'syfile_id' => 1,
            'published' => false,
        ]);

        $result = $TestModel->findById(1);
        $this->assertIdentical($result['Syfile']['item_count'], '2');

        $TestModel2->delete(1);
        $result = $TestModel->findById(1);
        $this->assertIdentical($result['Syfile']['item_count'], '1');

        $TestModel2->id = 2;
        $TestModel2->saveField('syfile_id', 1);

        $result = $TestModel->findById(1);
        $this->assertIdentical($result['Syfile']['item_count'], '2');

        $result = $TestModel->findById(2);
        $this->assertIdentical($result['Syfile']['item_count'], '0');
    }

    /**
     * Tests that counter caches are updated when records are added.
     */
    public function testCounterCacheIncrease()
    {
        $this->loadFixtures('CounterCacheUser', 'CounterCachePost');
        $User = new CounterCacheUser();
        $Post = new CounterCachePost();
        $data = ['Post' => [
            'id' => 22,
            'title' => 'New Post',
            'user_id' => 66,
        ]];

        $Post->save($data);
        $user = $User->find('first', [
            'conditions' => ['id' => 66],
            'recursive' => -1,
        ]);

        $result = $user[$User->alias]['post_count'];
        $expected = 3;
        $this->assertEqual($result, $expected);
    }

    /**
     * Tests that counter caches are updated when records are deleted.
     */
    public function testCounterCacheDecrease()
    {
        $this->loadFixtures('CounterCacheUser', 'CounterCachePost');
        $User = new CounterCacheUser();
        $Post = new CounterCachePost();

        $Post->delete(2);
        $user = $User->find('first', [
            'conditions' => ['id' => 66],
            'recursive' => -1,
        ]);

        $result = $user[$User->alias]['post_count'];
        $expected = 1;
        $this->assertEqual($result, $expected);
    }

    /**
     * Tests that counter caches are updated when foreign keys of counted records change.
     */
    public function testCounterCacheUpdated()
    {
        $this->loadFixtures('CounterCacheUser', 'CounterCachePost');
        $User = new CounterCacheUser();
        $Post = new CounterCachePost();

        $data = $Post->find('first', [
            'conditions' => ['id' => 1],
            'recursive' => -1,
        ]);
        $data[$Post->alias]['user_id'] = 301;
        $Post->save($data);

        $users = $User->find('all', ['order' => 'User.id']);
        $this->assertEqual($users[0]['User']['post_count'], 1);
        $this->assertEqual($users[1]['User']['post_count'], 2);
    }

    /**
     * Test counter cache with models that use a non-standard (i.e. not using 'id')
     * as their primary key.
     */
    public function testCounterCacheWithNonstandardPrimaryKey()
    {
        $this->loadFixtures(
            'CounterCacheUserNonstandardPrimaryKey',
            'CounterCachePostNonstandardPrimaryKey'
        );

        $User = new CounterCacheUserNonstandardPrimaryKey();
        $Post = new CounterCachePostNonstandardPrimaryKey();

        $data = $Post->find('first', [
            'conditions' => ['pid' => 1],
            'recursive' => -1,
        ]);
        $data[$Post->alias]['uid'] = 301;
        $Post->save($data);

        $users = $User->find('all', ['order' => 'User.uid']);
        $this->assertEqual($users[0]['User']['post_count'], 1);
        $this->assertEqual($users[1]['User']['post_count'], 2);
    }

    /**
     * test Counter Cache With Self Joining table.
     */
    public function testCounterCacheWithSelfJoin()
    {
        $skip = $this->skipIf(
            ('sqlite' == $this->db->config['driver']),
            'SQLite 2.x does not support ALTER TABLE ADD COLUMN'
        );
        if ($skip) {
            return;
        }

        $this->loadFixtures('CategoryThread');
        $this->db->query('ALTER TABLE '.$this->db->fullTableName('category_threads').' ADD COLUMN child_count INTEGER');
        $Category = new CategoryThread();
        $result = $Category->updateAll(['CategoryThread.name' => "'updated'"], ['CategoryThread.parent_id' => 5]);
        $this->assertTrue($result);

        $Category = new CategoryThread();
        $Category->belongsTo['ParentCategory']['counterCache'] = 'child_count';
        $Category->updateCounterCache(['parent_id' => 5]);
        $result = Set::extract($Category->find('all', ['conditions' => ['CategoryThread.id' => 5]]), '{n}.CategoryThread.child_count');
        $expected = array_fill(0, 1, 1);
        $this->assertEqual($result, $expected);
    }

    /**
     * testSaveWithCounterCacheScope method.
     */
    public function testSaveWithCounterCacheScope()
    {
        $this->loadFixtures('Syfile', 'Item');
        $TestModel = new Syfile();
        $TestModel2 = new Item();
        $TestModel2->belongsTo['Syfile']['counterCache'] = true;
        $TestModel2->belongsTo['Syfile']['counterScope'] = ['published' => true];

        $result = $TestModel->findById(1);
        $this->assertIdentical($result['Syfile']['item_count'], null);

        $TestModel2->save([
            'name' => 'Item 7',
            'syfile_id' => 1,
            'published' => true,
        ]);

        $result = $TestModel->findById(1);
        $this->assertIdentical($result['Syfile']['item_count'], '1');

        $TestModel2->id = 1;
        $TestModel2->saveField('published', true);
        $result = $TestModel->findById(1);
        $this->assertIdentical($result['Syfile']['item_count'], '2');

        $TestModel2->save([
            'id' => 1,
            'syfile_id' => 1,
            'published' => false,
        ]);

        $result = $TestModel->findById(1);
        $this->assertIdentical($result['Syfile']['item_count'], '1');
    }

    /**
     * test that beforeValidate returning false can abort saves.
     */
    public function testBeforeValidateSaveAbortion()
    {
        $Model = new CallbackPostTestModel();
        $Model->beforeValidateReturn = false;

        $data = [
            'title' => 'new article',
            'body' => 'this is some text.',
        ];
        $Model->create();
        $result = $Model->save($data);
        $this->assertFalse($result);
    }

    /**
     * test that beforeSave returning false can abort saves.
     */
    public function testBeforeSaveSaveAbortion()
    {
        $Model = new CallbackPostTestModel();
        $Model->beforeSaveReturn = false;

        $data = [
            'title' => 'new article',
            'body' => 'this is some text.',
        ];
        $Model->create();
        $result = $Model->save($data);
        $this->assertFalse($result);
    }

    /**
     * testSaveField method.
     */
    public function testSaveField()
    {
        $this->loadFixtures('Article');
        $TestModel = new Article();

        $TestModel->id = 1;
        $result = $TestModel->saveField('title', 'New First Article');
        $this->assertTrue($result);

        $TestModel->recursive = -1;
        $result = $TestModel->read(['id', 'user_id', 'title', 'body'], 1);
        $expected = ['Article' => [
            'id' => '1',
            'user_id' => '1',
            'title' => 'New First Article',
            'body' => 'First Article Body',
        ]];
        $this->assertEqual($result, $expected);

        $TestModel->id = 1;
        $result = $TestModel->saveField('title', '');
        $this->assertTrue($result);

        $TestModel->recursive = -1;
        $result = $TestModel->read(['id', 'user_id', 'title', 'body'], 1);
        $expected = ['Article' => [
            'id' => '1',
            'user_id' => '1',
            'title' => '',
            'body' => 'First Article Body',
        ]];
        $result['Article']['title'] = trim($result['Article']['title']);
        $this->assertEqual($result, $expected);

        $TestModel->id = 1;
        $TestModel->set('body', 'Messed up data');
        $this->assertTrue($TestModel->saveField('title', 'First Article'));
        $result = $TestModel->read(['id', 'user_id', 'title', 'body'], 1);
        $expected = ['Article' => [
            'id' => '1',
            'user_id' => '1',
            'title' => 'First Article',
            'body' => 'First Article Body',
        ]];
        $this->assertEqual($result, $expected);

        $TestModel->recursive = -1;
        $result = $TestModel->read(['id', 'user_id', 'title', 'body'], 1);

        $TestModel->id = 1;
        $result = $TestModel->saveField('title', '', true);
        $this->assertFalse($result);

        $this->loadFixtures('Node', 'Dependency');
        $Node = new Node();
        $Node->set('id', 1);
        $result = $Node->read();
        $this->assertEqual(Set::extract('/ParentNode/name', $result), ['Second']);

        $Node->saveField('state', 10);
        $result = $Node->read();
        $this->assertEqual(Set::extract('/ParentNode/name', $result), ['Second']);
    }

    /**
     * testSaveWithCreate method.
     */
    public function testSaveWithCreate()
    {
        $this->loadFixtures(
            'User',
            'Article',
            'User',
            'Comment',
            'Tag',
            'ArticlesTag',
            'Attachment'
        );
        $TestModel = new User();

        $data = ['User' => [
            'user' => 'user',
            'password' => '',
        ]];
        $result = $TestModel->save($data);
        $this->assertFalse($result);
        $this->assertTrue(!empty($TestModel->validationErrors));

        $TestModel = new Article();

        $data = ['Article' => [
            'user_id' => '',
            'title' => '',
            'body' => '',
        ]];
        $result = $TestModel->create($data) && $TestModel->save();
        $this->assertFalse($result);
        $this->assertTrue(!empty($TestModel->validationErrors));

        $data = ['Article' => [
            'id' => 1,
            'user_id' => '1',
            'title' => 'New First Article',
            'body' => '',
        ]];
        $result = $TestModel->create($data) && $TestModel->save();
        $this->assertFalse($result);

        $data = ['Article' => [
            'id' => 1,
            'title' => 'New First Article',
        ]];
        $result = $TestModel->create() && $TestModel->save($data, false);
        $this->assertTrue($result);

        $TestModel->recursive = -1;
        $result = $TestModel->read(['id', 'user_id', 'title', 'body', 'published'], 1);
        $expected = ['Article' => [
            'id' => '1',
            'user_id' => '1',
            'title' => 'New First Article',
            'body' => 'First Article Body',
            'published' => 'N',
        ]];
        $this->assertEqual($result, $expected);

        $data = ['Article' => [
            'id' => 1,
            'user_id' => '2',
            'title' => 'First Article',
            'body' => 'New First Article Body',
            'published' => 'Y',
        ]];
        $result = $TestModel->create() && $TestModel->save($data, true, ['id', 'title', 'published']);
        $this->assertTrue($result);

        $TestModel->recursive = -1;
        $result = $TestModel->read(['id', 'user_id', 'title', 'body', 'published'], 1);
        $expected = ['Article' => [
            'id' => '1',
            'user_id' => '1',
            'title' => 'First Article',
            'body' => 'First Article Body',
            'published' => 'Y',
        ]];
        $this->assertEqual($result, $expected);

        $data = [
            'Article' => [
                'user_id' => '2',
                'title' => 'New Article',
                'body' => 'New Article Body',
                'created' => '2007-03-18 14:55:23',
                'updated' => '2007-03-18 14:57:31',
            ],
            'Tag' => ['Tag' => [1, 3]],
        ];
        $TestModel->create();
        $result = $TestModel->create() && $TestModel->save($data);
        $this->assertTrue($result);

        $TestModel->recursive = 2;
        $result = $TestModel->read(null, 4);
        $expected = [
            'Article' => [
                'id' => '4',
                'user_id' => '2',
                'title' => 'New Article',
                'body' => 'New Article Body',
                'published' => 'N',
                'created' => '2007-03-18 14:55:23',
                'updated' => '2007-03-18 14:57:31',
            ],
            'User' => [
                'id' => '2',
                'user' => 'nate',
                'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                'created' => '2007-03-17 01:18:23',
                'updated' => '2007-03-17 01:20:31',
            ],
            'Comment' => [],
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

        $data = ['Comment' => [
            'article_id' => '4',
            'user_id' => '1',
            'comment' => 'Comment New Article',
            'published' => 'Y',
            'created' => '2007-03-18 14:57:23',
            'updated' => '2007-03-18 14:59:31',
        ]];
        $result = $TestModel->Comment->create() && $TestModel->Comment->save($data);
        $this->assertTrue($result);

        $data = ['Attachment' => [
            'comment_id' => '7',
            'attachment' => 'newattachment.zip',
            'created' => '2007-03-18 15:02:23',
            'updated' => '2007-03-18 15:04:31',
        ]];
        $result = $TestModel->Comment->Attachment->save($data);
        $this->assertTrue($result);

        $TestModel->recursive = 2;
        $result = $TestModel->read(null, 4);
        $expected = [
            'Article' => [
                'id' => '4',
                'user_id' => '2',
                'title' => 'New Article',
                'body' => 'New Article Body',
                'published' => 'N',
                'created' => '2007-03-18 14:55:23',
                'updated' => '2007-03-18 14:57:31',
            ],
            'User' => [
                'id' => '2',
                'user' => 'nate',
                'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                'created' => '2007-03-17 01:18:23',
                'updated' => '2007-03-17 01:20:31',
            ],
            'Comment' => [
                [
                    'id' => '7',
                    'article_id' => '4',
                    'user_id' => '1',
                    'comment' => 'Comment New Article',
                    'published' => 'Y',
                    'created' => '2007-03-18 14:57:23',
                    'updated' => '2007-03-18 14:59:31',
                    'Article' => [
                        'id' => '4',
                        'user_id' => '2',
                        'title' => 'New Article',
                        'body' => 'New Article Body',
                        'published' => 'N',
                        'created' => '2007-03-18 14:55:23',
                        'updated' => '2007-03-18 14:57:31',
                    ],
                    'User' => [
                        'id' => '1',
                        'user' => 'mariano',
                        'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                        'created' => '2007-03-17 01:16:23',
                        'updated' => '2007-03-17 01:18:31',
                    ],
                    'Attachment' => [
                        'id' => '2',
                        'comment_id' => '7',
                        'attachment' => 'newattachment.zip',
                        'created' => '2007-03-18 15:02:23',
                        'updated' => '2007-03-18 15:04:31',
            ], ], ],
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
    }

    /**
     * test that a null Id doesn't cause errors.
     */
    public function testSaveWithNullId()
    {
        $this->loadFixtures('User');
        $User = new User();
        $User->read(null, 1);
        $User->data['User']['id'] = null;
        $this->assertTrue($User->save(['password' => 'test']));
        $this->assertTrue($User->id > 0);

        $result = $User->read(null, 2);
        $User->data['User']['id'] = null;
        $this->assertTrue($User->save(['password' => 'test']));
        $this->assertTrue($User->id > 0);

        $User->data['User'] = ['password' => 'something'];
        $this->assertTrue($User->save());
        $result = $User->read();
        $this->assertEqual($User->data['User']['password'], 'something');
    }

    /**
     * testSaveWithSet method.
     */
    public function testSaveWithSet()
    {
        $this->loadFixtures('Article');
        $TestModel = new Article();

        // Create record we will be updating later

        $data = ['Article' => [
            'user_id' => '1',
            'title' => 'Fourth Article',
            'body' => 'Fourth Article Body',
            'published' => 'Y',
        ]];
        $result = $TestModel->create() && $TestModel->save($data);
        $this->assertTrue($result);

        // Check record we created

        $TestModel->recursive = -1;
        $result = $TestModel->read(['id', 'user_id', 'title', 'body', 'published'], 4);
        $expected = ['Article' => [
            'id' => '4',
            'user_id' => '1',
            'title' => 'Fourth Article',
            'body' => 'Fourth Article Body',
            'published' => 'Y',
        ]];
        $this->assertEqual($result, $expected);

        // Create new record just to overlap Model->id on previously created record

        $data = ['Article' => [
            'user_id' => '4',
            'title' => 'Fifth Article',
            'body' => 'Fifth Article Body',
            'published' => 'Y',
        ]];
        $result = $TestModel->create() && $TestModel->save($data);
        $this->assertTrue($result);

        $TestModel->recursive = -1;
        $result = $TestModel->read(['id', 'user_id', 'title', 'body', 'published'], 5);
        $expected = ['Article' => [
            'id' => '5',
            'user_id' => '4',
            'title' => 'Fifth Article',
            'body' => 'Fifth Article Body',
            'published' => 'Y',
        ]];
        $this->assertEqual($result, $expected);

        // Go back and edit the first article we created, starting by checking it's still there

        $TestModel->recursive = -1;
        $result = $TestModel->read(['id', 'user_id', 'title', 'body', 'published'], 4);
        $expected = ['Article' => [
            'id' => '4',
            'user_id' => '1',
            'title' => 'Fourth Article',
            'body' => 'Fourth Article Body',
            'published' => 'Y',
        ]];
        $this->assertEqual($result, $expected);

        // And now do the update with set()

        $data = ['Article' => [
            'id' => '4',
            'title' => 'Fourth Article - New Title',
            'published' => 'N',
        ]];
        $result = $TestModel->set($data) && $TestModel->save();
        $this->assertTrue($result);

        $TestModel->recursive = -1;
        $result = $TestModel->read(['id', 'user_id', 'title', 'body', 'published'], 4);
        $expected = ['Article' => [
            'id' => '4',
            'user_id' => '1',
            'title' => 'Fourth Article - New Title',
            'body' => 'Fourth Article Body',
            'published' => 'N',
        ]];
        $this->assertEqual($result, $expected);

        $TestModel->recursive = -1;
        $result = $TestModel->read(['id', 'user_id', 'title', 'body', 'published'], 5);
        $expected = ['Article' => [
            'id' => '5',
            'user_id' => '4',
            'title' => 'Fifth Article',
            'body' => 'Fifth Article Body',
            'published' => 'Y',
        ]];
        $this->assertEqual($result, $expected);

        $data = ['Article' => ['id' => '5', 'title' => 'Fifth Article - New Title 5']];
        $result = ($TestModel->set($data) && $TestModel->save());
        $this->assertTrue($result);

        $TestModel->recursive = -1;
        $result = $TestModel->read(['id', 'user_id', 'title', 'body', 'published'], 5);
        $expected = ['Article' => [
            'id' => '5',
            'user_id' => '4',
            'title' => 'Fifth Article - New Title 5',
            'body' => 'Fifth Article Body',
            'published' => 'Y',
        ]];
        $this->assertEqual($result, $expected);

        $TestModel->recursive = -1;
        $result = $TestModel->find('all', ['fields' => ['id', 'title']]);
        $expected = [
            ['Article' => ['id' => 1, 'title' => 'First Article']],
            ['Article' => ['id' => 2, 'title' => 'Second Article']],
            ['Article' => ['id' => 3, 'title' => 'Third Article']],
            ['Article' => ['id' => 4, 'title' => 'Fourth Article - New Title']],
            ['Article' => ['id' => 5, 'title' => 'Fifth Article - New Title 5']],
        ];
        $this->assertEqual($result, $expected);
    }

    /**
     * testSaveWithNonExistentFields method.
     */
    public function testSaveWithNonExistentFields()
    {
        $this->loadFixtures('Article');
        $TestModel = new Article();
        $TestModel->recursive = -1;

        $data = [
            'non_existent' => 'This field does not exist',
            'user_id' => '1',
            'title' => 'Fourth Article - New Title',
            'body' => 'Fourth Article Body',
            'published' => 'N',
        ];
        $result = $TestModel->create() && $TestModel->save($data);
        $this->assertTrue($result);

        $expected = ['Article' => [
            'id' => '4',
            'user_id' => '1',
            'title' => 'Fourth Article - New Title',
            'body' => 'Fourth Article Body',
            'published' => 'N',
        ]];
        $result = $TestModel->read(['id', 'user_id', 'title', 'body', 'published'], 4);
        $this->assertEqual($result, $expected);

        $data = [
            'user_id' => '1',
            'non_existent' => 'This field does not exist',
            'title' => 'Fiveth Article - New Title',
            'body' => 'Fiveth Article Body',
            'published' => 'N',
        ];
        $result = $TestModel->create() && $TestModel->save($data);
        $this->assertTrue($result);

        $expected = ['Article' => [
            'id' => '5',
            'user_id' => '1',
            'title' => 'Fiveth Article - New Title',
            'body' => 'Fiveth Article Body',
            'published' => 'N',
        ]];
        $result = $TestModel->read(['id', 'user_id', 'title', 'body', 'published'], 5);
        $this->assertEqual($result, $expected);
    }

    /**
     * testSaveFromXml method.
     */
    public function testSaveFromXml()
    {
        $this->loadFixtures('Article');
        App::import('Core', 'Xml');

        $Article = new Article();
        $Article->save(new Xml('<article title="test xml" user_id="5" />'));
        $this->assertTrue($Article->save(new Xml('<article title="test xml" user_id="5" />')));

        $results = $Article->find(['Article.title' => 'test xml']);
        $this->assertTrue($results);
    }

    /**
     * testSaveHabtm method.
     */
    public function testSaveHabtm()
    {
        $this->loadFixtures('Article', 'User', 'Comment', 'Tag', 'ArticlesTag');
        $TestModel = new Article();

        $result = $TestModel->findById(2);
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
                ],
            ],
        ];
        $this->assertEqual($result, $expected);

        $data = [
            'Article' => [
                'id' => '2',
                'title' => 'New Second Article',
            ],
            'Tag' => ['Tag' => [1, 2]],
        ];

        $this->assertTrue($TestModel->set($data));
        $this->assertTrue($TestModel->save());

        $TestModel->unbindModel(['belongsTo' => ['User'], 'hasMany' => ['Comment']]);
        $result = $TestModel->find(['Article.id' => 2], ['id', 'user_id', 'title', 'body']);
        $expected = [
            'Article' => [
                'id' => '2',
                'user_id' => '3',
                'title' => 'New Second Article',
                'body' => 'Second Article Body',
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
        ], ], ];
        $this->assertEqual($result, $expected);

        $data = ['Article' => ['id' => '2'], 'Tag' => ['Tag' => [2, 3]]];
        $result = $TestModel->set($data);
        $this->assertTrue($result);

        $result = $TestModel->save();
        $this->assertTrue($result);

        $TestModel->unbindModel([
            'belongsTo' => ['User'],
            'hasMany' => ['Comment'],
        ]);
        $result = $TestModel->find(['Article.id' => 2], ['id', 'user_id', 'title', 'body']);
        $expected = [
            'Article' => [
                'id' => '2',
                'user_id' => '3',
                'title' => 'New Second Article',
                'body' => 'Second Article Body',
            ],
            'Tag' => [
                [
                    'id' => '2',
                    'tag' => 'tag2',
                    'created' => '2007-03-18 12:24:23',
                    'updated' => '2007-03-18 12:26:31',
                ],
                [
                    'id' => '3',
                    'tag' => 'tag3',
                    'created' => '2007-03-18 12:26:23',
                    'updated' => '2007-03-18 12:28:31',
        ], ], ];
        $this->assertEqual($result, $expected);

        $data = ['Tag' => ['Tag' => [1, 2, 3]]];

        $result = $TestModel->set($data);
        $this->assertTrue($result);

        $result = $TestModel->save();
        $this->assertTrue($result);

        $TestModel->unbindModel([
            'belongsTo' => ['User'],
            'hasMany' => ['Comment'],
        ]);
        $result = $TestModel->find(['Article.id' => 2], ['id', 'user_id', 'title', 'body']);
        $expected = [
            'Article' => [
                'id' => '2',
                'user_id' => '3',
                'title' => 'New Second Article',
                'body' => 'Second Article Body',
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
                ],
                [
                    'id' => '3',
                    'tag' => 'tag3',
                    'created' => '2007-03-18 12:26:23',
                    'updated' => '2007-03-18 12:28:31',
        ], ], ];
        $this->assertEqual($result, $expected);

        $data = ['Tag' => ['Tag' => []]];
        $result = $TestModel->set($data);
        $this->assertTrue($result);

        $result = $TestModel->save();
        $this->assertTrue($result);

        $data = ['Tag' => ['Tag' => '']];
        $result = $TestModel->set($data);
        $this->assertTrue($result);

        $result = $TestModel->save();
        $this->assertTrue($result);

        $TestModel->unbindModel([
            'belongsTo' => ['User'],
            'hasMany' => ['Comment'],
        ]);
        $result = $TestModel->find(['Article.id' => 2], ['id', 'user_id', 'title', 'body']);
        $expected = [
            'Article' => [
                'id' => '2',
                'user_id' => '3',
                'title' => 'New Second Article',
                'body' => 'Second Article Body',
            ],
            'Tag' => [],
        ];
        $this->assertEqual($result, $expected);

        $data = ['Tag' => ['Tag' => [2, 3]]];
        $result = $TestModel->set($data);
        $this->assertTrue($result);

        $result = $TestModel->save();
        $this->assertTrue($result);

        $TestModel->unbindModel([
            'belongsTo' => ['User'],
            'hasMany' => ['Comment'],
        ]);
        $result = $TestModel->find(['Article.id' => 2], ['id', 'user_id', 'title', 'body']);
        $expected = [
            'Article' => [
                'id' => '2',
                'user_id' => '3',
                'title' => 'New Second Article',
                'body' => 'Second Article Body',
            ],
            'Tag' => [
                [
                    'id' => '2',
                    'tag' => 'tag2',
                    'created' => '2007-03-18 12:24:23',
                    'updated' => '2007-03-18 12:26:31',
                ],
                [
                    'id' => '3',
                    'tag' => 'tag3',
                    'created' => '2007-03-18 12:26:23',
                    'updated' => '2007-03-18 12:28:31',
        ], ], ];
        $this->assertEqual($result, $expected);

        $data = [
            'Tag' => [
                'Tag' => [1, 2],
            ],
            'Article' => [
                'id' => '2',
                'title' => 'New Second Article',
        ], ];
        $this->assertTrue($TestModel->set($data));
        $this->assertTrue($TestModel->save());

        $TestModel->unbindModel([
            'belongsTo' => ['User'],
            'hasMany' => ['Comment'],
        ]);
        $result = $TestModel->find(['Article.id' => 2], ['id', 'user_id', 'title', 'body']);
        $expected = [
            'Article' => [
                'id' => '2',
                'user_id' => '3',
                'title' => 'New Second Article',
                'body' => 'Second Article Body',
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
        ], ], ];
        $this->assertEqual($result, $expected);

        $data = [
            'Tag' => [
                'Tag' => [1, 2],
            ],
            'Article' => [
                'id' => '2',
                'title' => 'New Second Article Title',
        ], ];
        $result = $TestModel->set($data);
        $this->assertTrue($result);
        $this->assertTrue($TestModel->save());

        $TestModel->unbindModel([
            'belongsTo' => ['User'],
            'hasMany' => ['Comment'],
        ]);
        $result = $TestModel->find(['Article.id' => 2], ['id', 'user_id', 'title', 'body']);
        $expected = [
            'Article' => [
                'id' => '2',
                'user_id' => '3',
                'title' => 'New Second Article Title',
                'body' => 'Second Article Body',
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
                ],
            ],
        ];
        $this->assertEqual($result, $expected);

        $data = [
            'Tag' => [
                'Tag' => [2, 3],
            ],
            'Article' => [
                'id' => '2',
                'title' => 'Changed Second Article',
        ], ];
        $this->assertTrue($TestModel->set($data));
        $this->assertTrue($TestModel->save());

        $TestModel->unbindModel([
            'belongsTo' => ['User'],
            'hasMany' => ['Comment'],
        ]);
        $result = $TestModel->find(['Article.id' => 2], ['id', 'user_id', 'title', 'body']);
        $expected = [
            'Article' => [
                'id' => '2',
                'user_id' => '3',
                'title' => 'Changed Second Article',
                'body' => 'Second Article Body',
            ],
            'Tag' => [
                [
                    'id' => '2',
                    'tag' => 'tag2',
                    'created' => '2007-03-18 12:24:23',
                    'updated' => '2007-03-18 12:26:31',
                ],
                [
                    'id' => '3',
                    'tag' => 'tag3',
                    'created' => '2007-03-18 12:26:23',
                    'updated' => '2007-03-18 12:28:31',
                ],
            ],
        ];
        $this->assertEqual($result, $expected);

        $data = [
            'Tag' => [
                'Tag' => [1, 3],
            ],
            'Article' => ['id' => '2'],
        ];

        $result = $TestModel->set($data);
        $this->assertTrue($result);

        $result = $TestModel->save();
        $this->assertTrue($result);

        $TestModel->unbindModel([
            'belongsTo' => ['User'],
            'hasMany' => ['Comment'],
        ]);
        $result = $TestModel->find(['Article.id' => 2], ['id', 'user_id', 'title', 'body']);
        $expected = [
            'Article' => [
                'id' => '2',
                'user_id' => '3',
                'title' => 'Changed Second Article',
                'body' => 'Second Article Body',
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
        ], ], ];
        $this->assertEqual($result, $expected);

        $data = [
            'Article' => [
                'id' => 10,
                'user_id' => '2',
                'title' => 'New Article With Tags and fieldList',
                'body' => 'New Article Body with Tags and fieldList',
                'created' => '2007-03-18 14:55:23',
                'updated' => '2007-03-18 14:57:31',
            ],
            'Tag' => [
                'Tag' => [1, 2, 3],
        ], ];
        $result = $TestModel->create()
                && $TestModel->save($data, true, ['user_id', 'title', 'published']);
        $this->assertTrue($result);

        $TestModel->unbindModel(['belongsTo' => ['User'], 'hasMany' => ['Comment']]);
        $result = $TestModel->read();
        $expected = [
            'Article' => [
                'id' => 4,
                'user_id' => 2,
                'title' => 'New Article With Tags and fieldList',
                'body' => '',
                'published' => 'N',
                'created' => '',
                'updated' => '',
            ],
            'Tag' => [
                0 => [
                    'id' => 1,
                    'tag' => 'tag1',
                    'created' => '2007-03-18 12:22:23',
                    'updated' => '2007-03-18 12:24:31',
                ],
                1 => [
                    'id' => 2,
                    'tag' => 'tag2',
                    'created' => '2007-03-18 12:24:23',
                    'updated' => '2007-03-18 12:26:31',
                ],
                2 => [
                    'id' => 3,
                    'tag' => 'tag3',
                    'created' => '2007-03-18 12:26:23',
                    'updated' => '2007-03-18 12:28:31',
        ], ], ];
        $this->assertEqual($result, $expected);

        $this->loadFixtures('JoinA', 'JoinC', 'JoinAC', 'JoinB', 'JoinAB');
        $TestModel = new JoinA();
        $TestModel->hasBelongsToMany['JoinC']['unique'] = true;
        $data = [
            'JoinA' => [
                'id' => 1,
                'name' => 'Join A 1',
                'body' => 'Join A 1 Body',
            ],
            'JoinC' => [
                'JoinC' => [
                    ['join_c_id' => 2, 'other' => 'new record'],
                    ['join_c_id' => 3, 'other' => 'new record'],
                ],
            ],
        ];
        $TestModel->save($data);
        $result = $TestModel->read(null, 1);
        $expected = [4, 5];
        $this->assertEqual(Set::extract('/JoinC/JoinAsJoinC/id', $result), $expected);
        $expected = ['new record', 'new record'];
        $this->assertEqual(Set::extract('/JoinC/JoinAsJoinC/other', $result), $expected);
    }

    /**
     * testSaveHabtmCustomKeys method.
     */
    public function testSaveHabtmCustomKeys()
    {
        $this->loadFixtures('Story', 'StoriesTag', 'Tag');
        $Story = new Story();

        $data = [
            'Story' => ['story' => '1'],
            'Tag' => [
                'Tag' => [2, 3],
        ], ];
        $result = $Story->set($data);
        $this->assertTrue($result);

        $result = $Story->save();
        $this->assertTrue($result);

        $result = $Story->find('all', ['order' => ['Story.story']]);
        $expected = [
            [
                'Story' => [
                    'story' => 1,
                    'title' => 'First Story',
                ],
                'Tag' => [
                    [
                        'id' => 2,
                        'tag' => 'tag2',
                        'created' => '2007-03-18 12:24:23',
                        'updated' => '2007-03-18 12:26:31',
                    ],
                    [
                        'id' => 3,
                        'tag' => 'tag3',
                        'created' => '2007-03-18 12:26:23',
                        'updated' => '2007-03-18 12:28:31',
            ], ], ],
            [
                'Story' => [
                    'story' => 2,
                    'title' => 'Second Story',
                ],
                'Tag' => [],
        ], ];
        $this->assertEqual($result, $expected);
    }

    /**
     * test that saving habtm records respects conditions set in the 'conditions' key
     * for the association.
     */
    public function testHabtmSaveWithConditionsInAssociation()
    {
        $this->loadFixtures('JoinThing', 'Something', 'SomethingElse');
        $Something = new Something();
        $Something->unbindModel(['hasAndBelongsToMany' => ['SomethingElse']], false);

        $Something->bindModel([
            'hasAndBelongsToMany' => [
                'DoomedSomethingElse' => [
                    'className' => 'SomethingElse',
                    'joinTable' => 'join_things',
                    'conditions' => 'JoinThing.doomed = true',
                    'unique' => true,
                ],
                'NotDoomedSomethingElse' => [
                    'className' => 'SomethingElse',
                    'joinTable' => 'join_things',
                    'conditions' => ['JoinThing.doomed' => 0],
                    'unique' => true,
                ],
            ],
        ], false);
        $result = $Something->read(null, 1);
        $this->assertTrue(empty($result['NotDoomedSomethingElse']));
        $this->assertEqual(count($result['DoomedSomethingElse']), 1);

        $data = [
            'Something' => ['id' => 1],
            'NotDoomedSomethingElse' => [
                'NotDoomedSomethingElse' => [
                    ['something_else_id' => 2, 'doomed' => 0],
                    ['something_else_id' => 3, 'doomed' => 0],
                ],
            ],
        ];
        $Something->create($data);
        $result = $Something->save();
        $this->assertTrue($result);

        $result = $Something->read(null, 1);
        $this->assertEqual(count($result['NotDoomedSomethingElse']), 2);
        $this->assertEqual(count($result['DoomedSomethingElse']), 1);
    }

    /**
     * testHabtmSaveKeyResolution method.
     */
    public function testHabtmSaveKeyResolution()
    {
        $this->loadFixtures('Apple', 'Device', 'ThePaperMonkies');
        $ThePaper = new ThePaper();

        $ThePaper->id = 1;
        $ThePaper->save(['Monkey' => [2, 3]]);

        $result = $ThePaper->findById(1);
        $expected = [
            [
                'id' => '2',
                'device_type_id' => '1',
                'name' => 'Device 2',
                'typ' => '1',
            ],
            [
                'id' => '3',
                'device_type_id' => '1',
                'name' => 'Device 3',
                'typ' => '2',
        ], ];
        $this->assertEqual($result['Monkey'], $expected);

        $ThePaper->id = 2;
        $ThePaper->save(['Monkey' => [1, 2, 3]]);

        $result = $ThePaper->findById(2);
        $expected = [
            [
                'id' => '1',
                'device_type_id' => '1',
                'name' => 'Device 1',
                'typ' => '1',
            ],
            [
                'id' => '2',
                'device_type_id' => '1',
                'name' => 'Device 2',
                'typ' => '1',
            ],
            [
                'id' => '3',
                'device_type_id' => '1',
                'name' => 'Device 3',
                'typ' => '2',
        ], ];
        $this->assertEqual($result['Monkey'], $expected);

        $ThePaper->id = 2;
        $ThePaper->save(['Monkey' => [1, 3]]);

        $result = $ThePaper->findById(2);
        $expected = [
            [
                'id' => '1',
                'device_type_id' => '1',
                'name' => 'Device 1',
                'typ' => '1',
            ],
            [
                'id' => '3',
                'device_type_id' => '1',
                'name' => 'Device 3',
                'typ' => '2',
            ], ];
        $this->assertEqual($result['Monkey'], $expected);

        $result = $ThePaper->findById(1);
        $expected = [
            [
                'id' => '2',
                'device_type_id' => '1',
                'name' => 'Device 2',
                'typ' => '1',
            ],
            [
                'id' => '3',
                'device_type_id' => '1',
                'name' => 'Device 3',
                'typ' => '2',
        ], ];
        $this->assertEqual($result['Monkey'], $expected);
    }

    /**
     * testCreationOfEmptyRecord method.
     */
    public function testCreationOfEmptyRecord()
    {
        $this->loadFixtures('Author');
        $TestModel = new Author();
        $this->assertEqual($TestModel->find('count'), 4);

        $TestModel->deleteAll(true, false, false);
        $this->assertEqual($TestModel->find('count'), 0);

        $result = $TestModel->save();
        $this->assertTrue(isset($result['Author']['created']));
        $this->assertTrue(isset($result['Author']['updated']));
        $this->assertEqual($TestModel->find('count'), 1);
    }

    /**
     * testCreateWithPKFiltering method.
     */
    public function testCreateWithPKFiltering()
    {
        $TestModel = new Article();
        $data = [
            'id' => 5,
            'user_id' => 2,
            'title' => 'My article',
            'body' => 'Some text',
        ];

        $result = $TestModel->create($data);
        $expected = [
            'Article' => [
                'published' => 'N',
                'id' => 5,
                'user_id' => 2,
                'title' => 'My article',
                'body' => 'Some text',
        ], ];

        $this->assertEqual($result, $expected);
        $this->assertEqual($TestModel->id, 5);

        $result = $TestModel->create($data, true);
        $expected = [
            'Article' => [
                'published' => 'N',
                'id' => false,
                'user_id' => 2,
                'title' => 'My article',
                'body' => 'Some text',
        ], ];

        $this->assertEqual($result, $expected);
        $this->assertFalse($TestModel->id);

        $result = $TestModel->create(['Article' => $data], true);
        $expected = [
            'Article' => [
                'published' => 'N',
                'id' => false,
                'user_id' => 2,
                'title' => 'My article',
                'body' => 'Some text',
        ], ];

        $this->assertEqual($result, $expected);
        $this->assertFalse($TestModel->id);

        $data = [
            'id' => 6,
            'user_id' => 2,
            'title' => 'My article',
            'body' => 'Some text',
            'created' => '1970-01-01 00:00:00',
            'updated' => '1970-01-01 12:00:00',
            'modified' => '1970-01-01 12:00:00',
        ];

        $result = $TestModel->create($data);
        $expected = [
            'Article' => [
                'published' => 'N',
                'id' => 6,
                'user_id' => 2,
                'title' => 'My article',
                'body' => 'Some text',
                'created' => '1970-01-01 00:00:00',
                'updated' => '1970-01-01 12:00:00',
                'modified' => '1970-01-01 12:00:00',
        ], ];
        $this->assertEqual($result, $expected);
        $this->assertEqual($TestModel->id, 6);

        $result = $TestModel->create([
            'Article' => array_diff_key($data, [
                'created' => true,
                'updated' => true,
                'modified' => true,
        ]), ], true);
        $expected = [
            'Article' => [
                'published' => 'N',
                'id' => false,
                'user_id' => 2,
                'title' => 'My article',
                'body' => 'Some text',
        ], ];
        $this->assertEqual($result, $expected);
        $this->assertFalse($TestModel->id);
    }

    /**
     * testCreationWithMultipleData method.
     */
    public function testCreationWithMultipleData()
    {
        $this->loadFixtures('Article', 'Comment');
        $Article = new Article();
        $Comment = new Comment();

        $articles = $Article->find('all', [
            'fields' => ['id', 'title'],
            'recursive' => -1,
        ]);

        $comments = $Comment->find('all', [
            'fields' => ['id', 'article_id', 'user_id', 'comment', 'published'], 'recursive' => -1, ]);

        $this->assertEqual($articles, [
            ['Article' => [
                'id' => 1,
                'title' => 'First Article',
            ]],
            ['Article' => [
                'id' => 2,
                'title' => 'Second Article',
            ]],
            ['Article' => [
                'id' => 3,
                'title' => 'Third Article',
        ]], ]);

        $this->assertEqual($comments, [
            ['Comment' => [
                'id' => 1,
                'article_id' => 1,
                'user_id' => 2,
                'comment' => 'First Comment for First Article',
                'published' => 'Y',
            ]],
            ['Comment' => [
                'id' => 2,
                'article_id' => 1,
                'user_id' => 4,
                'comment' => 'Second Comment for First Article',
                'published' => 'Y',
            ]],
            ['Comment' => [
                'id' => 3,
                'article_id' => 1,
                'user_id' => 1,
                'comment' => 'Third Comment for First Article',
                'published' => 'Y',
            ]],
            ['Comment' => [
                'id' => 4,
                'article_id' => 1,
                'user_id' => 1,
                'comment' => 'Fourth Comment for First Article',
                'published' => 'N',
            ]],
            ['Comment' => [
                'id' => 5,
                'article_id' => 2,
                'user_id' => 1,
                'comment' => 'First Comment for Second Article',
                'published' => 'Y',
            ]],
            ['Comment' => [
                'id' => 6,
                'article_id' => 2,
                'user_id' => 2,
                'comment' => 'Second Comment for Second Article',
                'published' => 'Y',
        ]], ]);

        $data = [
            'Comment' => [
                'article_id' => 2,
                'user_id' => 4,
                'comment' => 'Brand New Comment',
                'published' => 'N',
            ],
            'Article' => [
                'id' => 2,
                'title' => 'Second Article Modified',
        ], ];

        $result = $Comment->create($data);

        $this->assertTrue($result);
        $result = $Comment->save();
        $this->assertTrue($result);

        $articles = $Article->find('all', [
            'fields' => ['id', 'title'],
            'recursive' => -1,
        ]);

        $comments = $Comment->find('all', [
            'fields' => ['id', 'article_id', 'user_id', 'comment', 'published'],
            'recursive' => -1,
        ]);

        $this->assertEqual($articles, [
            ['Article' => [
                'id' => 1,
                'title' => 'First Article',
            ]],
            ['Article' => [
                'id' => 2,
                'title' => 'Second Article',
            ]],
            ['Article' => [
                'id' => 3,
                'title' => 'Third Article',
        ]], ]);

        $this->assertEqual($comments, [
            ['Comment' => [
                'id' => 1,
                'article_id' => 1,
                'user_id' => 2,
                'comment' => 'First Comment for First Article',
                'published' => 'Y',
            ]],
            ['Comment' => [
                'id' => 2,
                'article_id' => 1,
                'user_id' => 4,
                'comment' => 'Second Comment for First Article',
                'published' => 'Y',
            ]],
            ['Comment' => [
                'id' => 3,
                'article_id' => 1,
                'user_id' => 1,
                'comment' => 'Third Comment for First Article',
                'published' => 'Y',
            ]],
            ['Comment' => [
                'id' => 4,
                'article_id' => 1,
                'user_id' => 1,
                'comment' => 'Fourth Comment for First Article',
                'published' => 'N',
            ]],
            ['Comment' => [
                'id' => 5,
                'article_id' => 2,
                'user_id' => 1,
                'comment' => 'First Comment for Second Article',
                'published' => 'Y',
            ]],
            ['Comment' => [
                'id' => 6,
                'article_id' => 2,
                'user_id' => 2, 'comment' => 'Second Comment for Second Article',
                'published' => 'Y',
            ]],
            ['Comment' => [
                'id' => 7,
                'article_id' => 2,
                'user_id' => 4,
                'comment' => 'Brand New Comment',
                'published' => 'N',
    ]], ]);
    }

    /**
     * testCreationWithMultipleDataSameModel method.
     */
    public function testCreationWithMultipleDataSameModel()
    {
        $this->loadFixtures('Article');
        $Article = new Article();
        $SecondaryArticle = new Article();

        $result = $Article->field('title', ['id' => 1]);
        $this->assertEqual($result, 'First Article');

        $data = [
            'Article' => [
                'user_id' => 2,
                'title' => 'Brand New Article',
                'body' => 'Brand New Article Body',
                'published' => 'Y',
            ],
            'SecondaryArticle' => [
                'id' => 1,
        ], ];

        $Article->create();
        $result = $Article->save($data);
        $this->assertTrue($result);

        $result = $Article->getInsertID();
        $this->assertTrue(!empty($result));

        $result = $Article->field('title', ['id' => 1]);
        $this->assertEqual($result, 'First Article');

        $articles = $Article->find('all', [
            'fields' => ['id', 'title'],
            'recursive' => -1,
        ]);

        $this->assertEqual($articles, [
            ['Article' => [
                'id' => 1,
                'title' => 'First Article',
            ]],
            ['Article' => [
                'id' => 2,
                'title' => 'Second Article',
            ]],
            ['Article' => [
                'id' => 3,
                'title' => 'Third Article',
            ]],
            ['Article' => [
                'id' => 4,
                'title' => 'Brand New Article',
        ]], ]);
    }

    /**
     * testCreationWithMultipleDataSameModelManualInstances method.
     */
    public function testCreationWithMultipleDataSameModelManualInstances()
    {
        $this->loadFixtures('PrimaryModel');
        $Primary = new PrimaryModel();
        $Secondary = new PrimaryModel();

        $result = $Primary->field('primary_name', ['id' => 1]);
        $this->assertEqual($result, 'Primary Name Existing');

        $data = [
            'PrimaryModel' => [
                'primary_name' => 'Primary Name New',
            ],
            'SecondaryModel' => [
                'id' => [1],
        ], ];

        $Primary->create();
        $result = $Primary->save($data);
        $this->assertTrue($result);

        $result = $Primary->field('primary_name', ['id' => 1]);
        $this->assertEqual($result, 'Primary Name Existing');

        $result = $Primary->getInsertID();
        $this->assertTrue(!empty($result));

        $result = $Primary->field('primary_name', ['id' => $result]);
        $this->assertEqual($result, 'Primary Name New');

        $result = $Primary->find('count');
        $this->assertEqual($result, 2);
    }

    /**
     * testRecordExists method.
     */
    public function testRecordExists()
    {
        $this->loadFixtures('User');
        $TestModel = new User();

        $this->assertFalse($TestModel->exists());
        $TestModel->read(null, 1);
        $this->assertTrue($TestModel->exists());
        $TestModel->create();
        $this->assertFalse($TestModel->exists());
        $TestModel->id = 4;
        $this->assertTrue($TestModel->exists());

        $TestModel = new TheVoid();
        $this->assertFalse($TestModel->exists());

        $TestModel->id = 5;
        $this->expectError();
        ob_start();
        $this->assertFalse($TestModel->exists());
        $output = ob_get_clean();
    }

    /**
     * testUpdateExisting method.
     */
    public function testUpdateExisting()
    {
        $this->loadFixtures('User', 'Article', 'Comment');
        $TestModel = new User();
        $TestModel->create();

        $TestModel->save([
            'User' => [
                'user' => 'some user',
                'password' => 'some password',
        ], ]);
        $this->assertTrue(is_int($TestModel->id) || (5 === intval($TestModel->id)));
        $id = $TestModel->id;

        $TestModel->save([
            'User' => [
                'user' => 'updated user',
        ], ]);
        $this->assertEqual($TestModel->id, $id);

        $result = $TestModel->findById($id);
        $this->assertEqual($result['User']['user'], 'updated user');
        $this->assertEqual($result['User']['password'], 'some password');

        $Article = new Article();
        $Comment = new Comment();
        $data = [
            'Comment' => [
                'id' => 1,
                'comment' => 'First Comment for First Article',
            ],
            'Article' => [
                'id' => 2,
                'title' => 'Second Article',
        ], ];

        $result = $Article->save($data);
        $this->assertTrue($result);

        $result = $Comment->save($data);
        $this->assertTrue($result);
    }

    /**
     * test updating records and saving blank values.
     */
    public function testUpdateSavingBlankValues()
    {
        $this->loadFixtures('Article');
        $Article = new Article();
        $Article->validate = [];
        $Article->create();
        $result = $Article->save([
            'id' => 1,
            'title' => '',
            'body' => '',
        ]);
        $this->assertTrue($result);
        $result = $Article->find('first', ['conditions' => ['Article.id' => 1]]);
        $this->assertEqual('', $result['Article']['title'], 'Title is not blank');
        $this->assertEqual('', $result['Article']['body'], 'Body is not blank');
    }

    /**
     * testUpdateMultiple method.
     */
    public function testUpdateMultiple()
    {
        $this->loadFixtures('Comment', 'Article', 'User', 'CategoryThread');
        $TestModel = new Comment();
        $result = Set::extract($TestModel->find('all'), '{n}.Comment.user_id');
        $expected = ['2', '4', '1', '1', '1', '2'];
        $this->assertEqual($result, $expected);

        $TestModel->updateAll(['Comment.user_id' => 5], ['Comment.user_id' => 2]);
        $result = Set::combine($TestModel->find('all'), '{n}.Comment.id', '{n}.Comment.user_id');
        $expected = [1 => 5, 2 => 4, 3 => 1, 4 => 1, 5 => 1, 6 => 5];
        $this->assertEqual($result, $expected);

        $result = $TestModel->updateAll(
            ['Comment.comment' => "'Updated today'"],
            ['Comment.user_id' => 5]
        );
        $this->assertTrue($result);
        $result = Set::extract(
            $TestModel->find('all', [
                'conditions' => [
                    'Comment.user_id' => 5,
            ], ]),
            '{n}.Comment.comment'
        );
        $expected = array_fill(0, 2, 'Updated today');
        $this->assertEqual($result, $expected);
    }

    /**
     * testHabtmUuidWithUuidId method.
     */
    public function testHabtmUuidWithUuidId()
    {
        $this->loadFixtures('Uuidportfolio', 'Uuiditem', 'UuiditemsUuidportfolio');
        $TestModel = new Uuidportfolio();

        $data = ['Uuidportfolio' => ['name' => 'Portfolio 3']];
        $data['Uuiditem']['Uuiditem'] = ['483798c8-c7cc-430e-8cf9-4fcc40cf8569'];
        $TestModel->create($data);
        $TestModel->save();
        $id = $TestModel->id;
        $result = $TestModel->read(null, $id);
        $this->assertEqual(1, count($result['Uuiditem']));
        $this->assertEqual(strlen($result['Uuiditem'][0]['UuiditemsUuidportfolio']['id']), 36);
    }

    /**
     * test HABTM saving when join table has no primary key and only 2 columns.
     */
    public function testHabtmSavingWithNoPrimaryKeyUuidJoinTable()
    {
        $this->loadFixtures('UuidTag', 'Fruit', 'FruitsUuidTag');
        $Fruit = new Fruit();
        $data = [
            'Fruit' => [
                'color' => 'Red',
                'shape' => 'Heart-shaped',
                'taste' => 'sweet',
                'name' => 'Strawberry',
            ],
            'UuidTag' => [
                'UuidTag' => [
                    '481fc6d0-b920-43e0-e50f-6d1740cf8569',
                ],
            ],
        ];
        $this->assertTrue($Fruit->save($data));
    }

    /**
     * test HABTM saving when join table has no primary key and only 2 columns, no with model is used.
     */
    public function testHabtmSavingWithNoPrimaryKeyUuidJoinTableNoWith()
    {
        $this->loadFixtures('UuidTag', 'Fruit', 'FruitsUuidTag');
        $Fruit = new FruitNoWith();
        $data = [
            'Fruit' => [
                'color' => 'Red',
                'shape' => 'Heart-shaped',
                'taste' => 'sweet',
                'name' => 'Strawberry',
            ],
            'UuidTag' => [
                'UuidTag' => [
                    '481fc6d0-b920-43e0-e50f-6d1740cf8569',
                ],
            ],
        ];
        $this->assertTrue($Fruit->save($data));
    }

    /**
     * testHabtmUuidWithNumericId method.
     */
    public function testHabtmUuidWithNumericId()
    {
        $this->loadFixtures('Uuidportfolio', 'Uuiditem', 'UuiditemsUuidportfolioNumericid');
        $TestModel = new Uuiditem();

        $data = ['Uuiditem' => ['name' => 'Item 7', 'published' => 0]];
        $data['Uuidportfolio']['Uuidportfolio'] = ['480af662-eb8c-47d3-886b-230540cf8569'];
        $TestModel->create($data);
        $TestModel->save();
        $id = $TestModel->id;
        $result = $TestModel->read(null, $id);
        $this->assertEqual(1, count($result['Uuidportfolio']));
    }

    /**
     * testSaveMultipleHabtm method.
     */
    public function testSaveMultipleHabtm()
    {
        $this->loadFixtures('JoinA', 'JoinB', 'JoinC', 'JoinAB', 'JoinAC');
        $TestModel = new JoinA();
        $result = $TestModel->findById(1);

        $expected = [
            'JoinA' => [
                'id' => 1,
                'name' => 'Join A 1',
                'body' => 'Join A 1 Body',
                'created' => '2008-01-03 10:54:23',
                'updated' => '2008-01-03 10:54:23',
            ],
            'JoinB' => [
                0 => [
                    'id' => 2,
                    'name' => 'Join B 2',
                    'created' => '2008-01-03 10:55:02',
                    'updated' => '2008-01-03 10:55:02',
                    'JoinAsJoinB' => [
                        'id' => 1,
                        'join_a_id' => 1,
                        'join_b_id' => 2,
                        'other' => 'Data for Join A 1 Join B 2',
                        'created' => '2008-01-03 10:56:33',
                        'updated' => '2008-01-03 10:56:33',
            ], ], ],
            'JoinC' => [
                0 => [
                    'id' => 2,
                    'name' => 'Join C 2',
                    'created' => '2008-01-03 10:56:12',
                    'updated' => '2008-01-03 10:56:12',
                    'JoinAsJoinC' => [
                        'id' => 1,
                        'join_a_id' => 1,
                        'join_c_id' => 2,
                        'other' => 'Data for Join A 1 Join C 2',
                        'created' => '2008-01-03 10:57:22',
                        'updated' => '2008-01-03 10:57:22',
        ], ], ], ];

        $this->assertEqual($result, $expected);

        $ts = date('Y-m-d H:i:s');
        $TestModel->id = 1;
        $data = [
            'JoinA' => [
                'id' => '1',
                'name' => 'New name for Join A 1',
                'updated' => $ts,
            ],
            'JoinB' => [
                [
                    'id' => 1,
                    'join_b_id' => 2,
                    'other' => 'New data for Join A 1 Join B 2',
                    'created' => $ts,
                    'updated' => $ts,
            ], ],
            'JoinC' => [
                [
                    'id' => 1,
                    'join_c_id' => 2,
                    'other' => 'New data for Join A 1 Join C 2',
                    'created' => $ts,
                    'updated' => $ts,
        ], ], ];

        $TestModel->set($data);
        $TestModel->save();

        $result = $TestModel->findById(1);
        $expected = [
            'JoinA' => [
                'id' => 1,
                'name' => 'New name for Join A 1',
                'body' => 'Join A 1 Body',
                'created' => '2008-01-03 10:54:23',
                'updated' => $ts,
            ],
            'JoinB' => [
                0 => [
                    'id' => 2,
                    'name' => 'Join B 2',
                    'created' => '2008-01-03 10:55:02',
                    'updated' => '2008-01-03 10:55:02',
                    'JoinAsJoinB' => [
                        'id' => 1,
                        'join_a_id' => 1,
                        'join_b_id' => 2,
                        'other' => 'New data for Join A 1 Join B 2',
                        'created' => $ts,
                        'updated' => $ts,
            ], ], ],
            'JoinC' => [
                0 => [
                    'id' => 2,
                    'name' => 'Join C 2',
                    'created' => '2008-01-03 10:56:12',
                    'updated' => '2008-01-03 10:56:12',
                    'JoinAsJoinC' => [
                        'id' => 1,
                        'join_a_id' => 1,
                        'join_c_id' => 2,
                        'other' => 'New data for Join A 1 Join C 2',
                        'created' => $ts,
                        'updated' => $ts,
        ], ], ], ];

        $this->assertEqual($result, $expected);
    }

    /**
     * testSaveAll method.
     */
    public function testSaveAll()
    {
        $this->loadFixtures('Post', 'Author', 'Comment', 'Attachment');
        $TestModel = new Post();

        $result = $TestModel->find('all');
        $this->assertEqual(count($result), 3);
        $this->assertFalse(isset($result[3]));
        $ts = date('Y-m-d H:i:s');

        $TestModel->saveAll([
            'Post' => [
                'title' => 'Post with Author',
                'body' => 'This post will be saved with an author',
            ],
            'Author' => [
                'user' => 'bob',
                'password' => '5f4dcc3b5aa765d61d8327deb882cf90',
        ], ]);

        $result = $TestModel->find('all');
        $expected = [
            'Post' => [
                'id' => '4',
                'author_id' => '5',
                'title' => 'Post with Author',
                'body' => 'This post will be saved with an author',
                'published' => 'N',
                'created' => $ts,
                'updated' => $ts,
            ],
            'Author' => [
                'id' => '5',
                'user' => 'bob',
                'password' => '5f4dcc3b5aa765d61d8327deb882cf90',
                'created' => $ts,
                'updated' => $ts,
                'test' => 'working',
        ], ];
        $this->assertEqual($result[3], $expected);
        $this->assertEqual(count($result), 4);

        $TestModel->deleteAll(true);
        $this->assertEqual($TestModel->find('all'), []);

        // SQLite seems to reset the PK counter when that happens, so we need this to make the tests pass
        $this->db->truncate($TestModel);

        $ts = date('Y-m-d H:i:s');
        $TestModel->saveAll([
            [
                'title' => 'Multi-record post 1',
                'body' => 'First multi-record post',
                'author_id' => 2,
            ],
            [
                'title' => 'Multi-record post 2',
                'body' => 'Second multi-record post',
                'author_id' => 2,
        ], ]);

        $result = $TestModel->find('all', [
            'recursive' => -1,
            'order' => 'Post.id ASC',
        ]);
        $expected = [
            [
                'Post' => [
                    'id' => '1',
                    'author_id' => '2',
                    'title' => 'Multi-record post 1',
                    'body' => 'First multi-record post',
                    'published' => 'N',
                    'created' => $ts,
                    'updated' => $ts,
            ], ],
            [
                'Post' => [
                    'id' => '2',
                    'author_id' => '2',
                    'title' => 'Multi-record post 2',
                    'body' => 'Second multi-record post',
                    'published' => 'N',
                    'created' => $ts,
                    'updated' => $ts,
        ], ], ];
        $this->assertEqual($result, $expected);

        $TestModel = new Comment();
        $ts = date('Y-m-d H:i:s');
        $result = $TestModel->saveAll([
            'Comment' => [
                'article_id' => 2,
                'user_id' => 2,
                'comment' => 'New comment with attachment',
                'published' => 'Y',
            ],
            'Attachment' => [
                'attachment' => 'some_file.tgz',
            ], ]);
        $this->assertTrue($result);

        $result = $TestModel->find('all');
        $expected = [
            'id' => '7',
            'article_id' => '2',
            'user_id' => '2',
            'comment' => 'New comment with attachment',
            'published' => 'Y',
            'created' => $ts,
            'updated' => $ts,
        ];
        $this->assertEqual($result[6]['Comment'], $expected);

        $expected = [
            'id' => '7',
            'article_id' => '2',
            'user_id' => '2',
            'comment' => 'New comment with attachment',
            'published' => 'Y',
            'created' => $ts,
            'updated' => $ts,
        ];
        $this->assertEqual($result[6]['Comment'], $expected);

        $expected = [
            'id' => '2',
            'comment_id' => '7',
            'attachment' => 'some_file.tgz',
            'created' => $ts,
            'updated' => $ts,
        ];
        $this->assertEqual($result[6]['Attachment'], $expected);
    }

    /**
     * Test SaveAll with Habtm relations.
     */
    public function testSaveAllHabtm()
    {
        $this->loadFixtures('Article', 'Tag', 'Comment', 'User');
        $data = [
            'Article' => [
                'user_id' => 1,
                'title' => 'Article Has and belongs to Many Tags',
            ],
            'Tag' => [
                'Tag' => [1, 2],
            ],
            'Comment' => [
                [
                    'comment' => 'Article comment',
                    'user_id' => 1,
        ], ], ];
        $Article = new Article();
        $result = $Article->saveAll($data);
        $this->assertTrue($result);

        $result = $Article->read();
        $this->assertEqual(count($result['Tag']), 2);
        $this->assertEqual($result['Tag'][0]['tag'], 'tag1');
        $this->assertEqual(count($result['Comment']), 1);
        $this->assertEqual(count($result['Comment'][0]['comment']), 1);
    }

    /**
     * Test SaveAll with Habtm relations and extra join table fields.
     */
    public function testSaveAllHabtmWithExtraJoinTableFields()
    {
        $this->loadFixtures('Something', 'SomethingElse', 'JoinThing');

        $data = [
            'Something' => [
                'id' => 4,
                'title' => 'Extra Fields',
                'body' => 'Extra Fields Body',
                'published' => '1',
            ],
            'SomethingElse' => [
                ['something_else_id' => 1, 'doomed' => '1'],
                ['something_else_id' => 2, 'doomed' => '0'],
                ['something_else_id' => 3, 'doomed' => '1'],
            ],
        ];

        $Something = new Something();
        $result = $Something->saveAll($data);
        $this->assertTrue($result);
        $result = $Something->read();

        $this->assertEqual(count($result['SomethingElse']), 3);
        $this->assertTrue(Set::matches('/Something[id=4]', $result));

        $this->assertTrue(Set::matches('/SomethingElse[id=1]', $result));
        $this->assertTrue(Set::matches('/SomethingElse[id=1]/JoinThing[something_else_id=1]', $result));
        $this->assertTrue(Set::matches('/SomethingElse[id=1]/JoinThing[doomed=1]', $result));

        $this->assertTrue(Set::matches('/SomethingElse[id=2]', $result));
        $this->assertTrue(Set::matches('/SomethingElse[id=2]/JoinThing[something_else_id=2]', $result));
        $this->assertTrue(Set::matches('/SomethingElse[id=2]/JoinThing[doomed=0]', $result));

        $this->assertTrue(Set::matches('/SomethingElse[id=3]', $result));
        $this->assertTrue(Set::matches('/SomethingElse[id=3]/JoinThing[something_else_id=3]', $result));
        $this->assertTrue(Set::matches('/SomethingElse[id=3]/JoinThing[doomed=1]', $result));
    }

    /**
     * testSaveAllHasOne method.
     */
    public function testSaveAllHasOne()
    {
        $model = new Comment();
        $model->deleteAll(true);
        $this->assertEqual($model->find('all'), []);

        $model->Attachment->deleteAll(true);
        $this->assertEqual($model->Attachment->find('all'), []);

        $this->assertTrue($model->saveAll([
            'Comment' => [
                'comment' => 'Comment with attachment',
                'article_id' => 1,
                'user_id' => 1,
            ],
            'Attachment' => [
                'attachment' => 'some_file.zip',
        ], ]));
        $result = $model->find('all', ['fields' => [
            'Comment.id', 'Comment.comment', 'Attachment.id',
            'Attachment.comment_id', 'Attachment.attachment',
        ]]);
        $expected = [[
            'Comment' => [
                'id' => '1',
                'comment' => 'Comment with attachment',
            ],
            'Attachment' => [
                'id' => '1',
                'comment_id' => '1',
                'attachment' => 'some_file.zip',
        ], ]];
        $this->assertEqual($result, $expected);

        $model->Attachment->bindModel(['belongsTo' => ['Comment']], false);
        $data = [
            'Comment' => [
                'comment' => 'Comment with attachment',
                'article_id' => 1,
                'user_id' => 1,
            ],
            'Attachment' => [
                'attachment' => 'some_file.zip',
        ], ];
        $this->assertTrue($model->saveAll($data, ['validate' => 'first']));
    }

    /**
     * testSaveAllBelongsTo method.
     */
    public function testSaveAllBelongsTo()
    {
        $model = new Comment();
        $model->deleteAll(true);
        $this->assertEqual($model->find('all'), []);

        $model->Article->deleteAll(true);
        $this->assertEqual($model->Article->find('all'), []);

        $this->assertTrue($model->saveAll([
            'Comment' => [
                'comment' => 'Article comment',
                'article_id' => 1,
                'user_id' => 1,
            ],
            'Article' => [
                'title' => 'Model Associations 101',
                'user_id' => 1,
        ], ]));
        $result = $model->find('all', ['fields' => [
            'Comment.id', 'Comment.comment', 'Comment.article_id', 'Article.id', 'Article.title',
        ]]);
        $expected = [[
            'Comment' => [
                'id' => '1',
                'article_id' => '1',
                'comment' => 'Article comment',
            ],
            'Article' => [
                'id' => '1',
                'title' => 'Model Associations 101',
        ], ]];
        $this->assertEqual($result, $expected);
    }

    /**
     * testSaveAllHasOneValidation method.
     */
    public function testSaveAllHasOneValidation()
    {
        $model = new Comment();
        $model->deleteAll(true);
        $this->assertEqual($model->find('all'), []);

        $model->Attachment->deleteAll(true);
        $this->assertEqual($model->Attachment->find('all'), []);

        $model->validate = ['comment' => 'notEmpty'];
        $model->Attachment->validate = ['attachment' => 'notEmpty'];
        $model->Attachment->bindModel(['belongsTo' => ['Comment']]);

        $this->assertFalse($model->saveAll(
            [
                'Comment' => [
                    'comment' => '',
                    'article_id' => 1,
                    'user_id' => 1,
                ],
                'Attachment' => ['attachment' => ''],
            ],
            ['validate' => 'first']
        ));
        $expected = [
            'Comment' => ['comment' => 'This field cannot be left blank'],
            'Attachment' => ['attachment' => 'This field cannot be left blank'],
        ];
        $this->assertEqual($model->validationErrors, $expected['Comment']);
        $this->assertEqual($model->Attachment->validationErrors, $expected['Attachment']);

        $this->assertFalse($model->saveAll(
            [
                'Comment' => ['comment' => '', 'article_id' => 1, 'user_id' => 1],
                'Attachment' => ['attachment' => ''],
            ],
            ['validate' => 'only']
        ));
        $this->assertEqual($model->validationErrors, $expected['Comment']);
        $this->assertEqual($model->Attachment->validationErrors, $expected['Attachment']);
    }

    /**
     * testSaveAllAtomic method.
     */
    public function testSaveAllAtomic()
    {
        $this->loadFixtures('Article', 'User');
        $TestModel = new Article();

        $result = $TestModel->saveAll([
            'Article' => [
                'title' => 'Post with Author',
                'body' => 'This post will be saved with an author',
                'user_id' => 2,
            ],
            'Comment' => [
                ['comment' => 'First new comment', 'user_id' => 2], ],
        ], ['atomic' => false]);

        $this->assertIdentical($result, ['Article' => true, 'Comment' => [true]]);

        $result = $TestModel->saveAll([
            [
                'id' => '1',
                'title' => 'Baleeted First Post',
                'body' => 'Baleeted!',
                'published' => 'N',
            ],
            [
                'id' => '2',
                'title' => 'Just update the title',
            ],
            [
                'title' => 'Creating a fourth post',
                'body' => 'Fourth post body',
                'user_id' => 2,
            ],
        ], ['atomic' => false]);
        $this->assertIdentical($result, [true, true, true]);

        $TestModel->validate = ['title' => 'notEmpty', 'author_id' => 'numeric'];
        $result = $TestModel->saveAll([
            [
                'id' => '1',
                'title' => 'Un-Baleeted First Post',
                'body' => 'Not Baleeted!',
                'published' => 'Y',
            ],
            [
                'id' => '2',
                'title' => '',
                'body' => 'Trying to get away with an empty title',
            ],
        ], ['validate' => true, 'atomic' => false]);

        $this->assertIdentical($result, [true, false]);

        $result = $TestModel->saveAll([
            'Article' => ['id' => 2],
            'Comment' => [
                [
                    'comment' => 'First new comment',
                    'published' => 'Y',
                    'user_id' => 1,
                ],
                [
                    'comment' => 'Second new comment',
                    'published' => 'Y',
                    'user_id' => 2,
            ], ],
        ], ['validate' => true, 'atomic' => false]);
        $this->assertIdentical($result, ['Article' => true, 'Comment' => [true, true]]);
    }

    /**
     * testSaveAllHasMany method.
     */
    public function testSaveAllHasMany()
    {
        $this->loadFixtures('Article', 'Comment');
        $TestModel = new Article();
        $TestModel->belongsTo = $TestModel->hasAndBelongsToMany = [];

        $result = $TestModel->saveAll([
            'Article' => ['id' => 2],
            'Comment' => [
                ['comment' => 'First new comment', 'published' => 'Y', 'user_id' => 1],
                ['comment' => 'Second new comment', 'published' => 'Y', 'user_id' => 2],
            ],
        ]);
        $this->assertTrue($result);

        $result = $TestModel->findById(2);
        $expected = [
            'First Comment for Second Article',
            'Second Comment for Second Article',
            'First new comment',
            'Second new comment',
        ];
        $this->assertEqual(Set::extract($result['Comment'], '{n}.comment'), $expected);

        $result = $TestModel->saveAll(
            [
                'Article' => ['id' => 2],
                'Comment' => [
                    [
                        'comment' => 'Third new comment',
                        'published' => 'Y',
                        'user_id' => 1,
            ], ], ],
            ['atomic' => false]
        );
        $this->assertTrue($result);

        $result = $TestModel->findById(2);
        $expected = [
            'First Comment for Second Article',
            'Second Comment for Second Article',
            'First new comment',
            'Second new comment',
            'Third new comment',
        ];
        $this->assertEqual(Set::extract($result['Comment'], '{n}.comment'), $expected);

        $TestModel->beforeSaveReturn = false;
        $result = $TestModel->saveAll(
            [
                'Article' => ['id' => 2],
                'Comment' => [
                    [
                        'comment' => 'Fourth new comment',
                        'published' => 'Y',
                        'user_id' => 1,
            ], ], ],
            ['atomic' => false]
        );
        $this->assertEqual($result, ['Article' => false]);

        $result = $TestModel->findById(2);
        $expected = [
            'First Comment for Second Article',
            'Second Comment for Second Article',
            'First new comment',
            'Second new comment',
            'Third new comment',
        ];
        $this->assertEqual(Set::extract($result['Comment'], '{n}.comment'), $expected);
    }

    /**
     * testSaveAllHasManyValidation method.
     */
    public function testSaveAllHasManyValidation()
    {
        $this->loadFixtures('Article', 'Comment');
        $TestModel = new Article();
        $TestModel->belongsTo = $TestModel->hasAndBelongsToMany = [];
        $TestModel->Comment->validate = ['comment' => 'notEmpty'];

        $result = $TestModel->saveAll([
            'Article' => ['id' => 2],
            'Comment' => [
                ['comment' => '', 'published' => 'Y', 'user_id' => 1],
            ],
        ], ['validate' => true]);
        $expected = ['Comment' => [false]];
        $this->assertEqual($result, $expected);

        $expected = ['Comment' => [
            ['comment' => 'This field cannot be left blank'],
        ]];
        $this->assertEqual($TestModel->validationErrors, $expected);
        $expected = [
            ['comment' => 'This field cannot be left blank'],
        ];
        $this->assertEqual($TestModel->Comment->validationErrors, $expected);

        $result = $TestModel->saveAll([
            'Article' => ['id' => 2],
            'Comment' => [
                [
                    'comment' => '',
                    'published' => 'Y',
                    'user_id' => 1,
            ], ],
        ], ['validate' => 'first']);
        $this->assertFalse($result);
    }

    /**
     * test saveAll with transactions and ensure there is no missing rollback.
     */
    public function testSaveAllManyRowsTransactionNoRollback()
    {
        $this->loadFixtures('Post');

        Mock::generate('DboSource', 'MockTransactionDboSource');
        $db = ConnectionManager::create('mock_transaction', [
            'datasource' => 'MockTransactionDbo',
        ]);
        $db->expectOnce('rollback');

        $Post = new Post();
        $Post->useDbConfig = 'mock_transaction';

        $Post->validate = [
            'title' => ['rule' => ['notEmpty']],
        ];

        $data = [
            ['author_id' => 1, 'title' => 'New Fourth Post'],
            ['author_id' => 1, 'title' => ''],
        ];
        $Post->saveAll($data, ['atomic' => true]);
    }

    /**
     * test saveAll with transactions and ensure there is no missing rollback.
     */
    public function testSaveAllAssociatedTransactionNoRollback()
    {
        $testDb = ConnectionManager::getDataSource('test_suite');

        Mock::generate('DboSource', 'MockTransactionAssociatedDboSource');
        $db = ConnectionManager::create('mock_transaction_assoc', [
            'datasource' => 'MockTransactionAssociatedDbo',
        ]);
        $db->columns = $testDb->columns;

        $db->expectOnce('rollback');

        $Post = new Post();
        $Post->useDbConfig = 'mock_transaction_assoc';
        $Post->Author->useDbConfig = 'mock_transaction_assoc';

        $Post->Author->validate = [
            'user' => ['rule' => ['notEmpty']],
        ];

        $data = [
            'Post' => [
                'title' => 'New post',
                'body' => 'Content',
                'published' => 'Y',
            ],
            'Author' => [
                'user' => '',
                'password' => 'sekret',
            ],
        ];
        $Post->saveAll($data);
    }

    /**
     * test saveAll with nested saveAll call.
     */
    public function testSaveAllNestedSaveAll()
    {
        $this->loadFixtures('Sample');
        $TransactionTestModel = new TransactionTestModel();

        $data = [
            ['apple_id' => 1, 'name' => 'sample5'],
        ];

        $this->assertTrue($TransactionTestModel->saveAll($data, ['atomic' => true]));
    }

    /**
     * testSaveAllTransaction method.
     */
    public function testSaveAllTransaction()
    {
        $this->loadFixtures('Post', 'Author', 'Comment', 'Attachment');
        $TestModel = new Post();

        $TestModel->validate = ['title' => 'notEmpty'];
        $data = [
            ['author_id' => 1, 'title' => 'New Fourth Post'],
            ['author_id' => 1, 'title' => 'New Fifth Post'],
            ['author_id' => 1, 'title' => ''],
        ];
        $ts = date('Y-m-d H:i:s');
        $this->assertFalse($TestModel->saveAll($data));

        $result = $TestModel->find('all', ['recursive' => -1]);
        $expected = [
            ['Post' => [
                'id' => '1',
                'author_id' => 1,
                'title' => 'First Post',
                'body' => 'First Post Body',
                'published' => 'Y',
                'created' => '2007-03-18 10:39:23',
                'updated' => '2007-03-18 10:41:31',
            ]],
            ['Post' => [
                'id' => '2',
                'author_id' => 3,
                'title' => 'Second Post',
                'body' => 'Second Post Body',
                'published' => 'Y',
                'created' => '2007-03-18 10:41:23',
                'updated' => '2007-03-18 10:43:31',
            ]],
            ['Post' => [
                'id' => '3',
                'author_id' => 1,
                'title' => 'Third Post',
                'body' => 'Third Post Body',
                'published' => 'Y',
                'created' => '2007-03-18 10:43:23',
                'updated' => '2007-03-18 10:45:31',
        ]], ];

        if (3 != count($result)) {
            // Database doesn't support transactions
            $expected[] = [
                'Post' => [
                    'id' => '4',
                    'author_id' => 1,
                    'title' => 'New Fourth Post',
                    'body' => null,
                    'published' => 'N',
                    'created' => $ts,
                    'updated' => $ts,
            ], ];

            $expected[] = [
                'Post' => [
                    'id' => '5',
                    'author_id' => 1,
                    'title' => 'New Fifth Post',
                    'body' => null,
                    'published' => 'N',
                    'created' => $ts,
                    'updated' => $ts,
            ], ];

            $this->assertEqual($result, $expected);
            // Skip the rest of the transactional tests
            return;
        }

        $this->assertEqual($result, $expected);

        $data = [
            ['author_id' => 1, 'title' => 'New Fourth Post'],
            ['author_id' => 1, 'title' => ''],
            ['author_id' => 1, 'title' => 'New Sixth Post'],
        ];
        $ts = date('Y-m-d H:i:s');
        $this->assertFalse($TestModel->saveAll($data));

        $result = $TestModel->find('all', ['recursive' => -1]);
        $expected = [
            ['Post' => [
                'id' => '1',
                'author_id' => 1,
                'title' => 'First Post',
                'body' => 'First Post Body',
                'published' => 'Y',
                'created' => '2007-03-18 10:39:23',
                'updated' => '2007-03-18 10:41:31',
            ]],
            ['Post' => [
                'id' => '2',
                'author_id' => 3,
                'title' => 'Second Post',
                'body' => 'Second Post Body',
                'published' => 'Y',
                'created' => '2007-03-18 10:41:23',
                'updated' => '2007-03-18 10:43:31',
            ]],
            ['Post' => [
                'id' => '3',
                'author_id' => 1,
                'title' => 'Third Post',
                'body' => 'Third Post Body',
                'published' => 'Y',
                'created' => '2007-03-18 10:43:23',
                'updated' => '2007-03-18 10:45:31',
        ]], ];

        if (3 != count($result)) {
            // Database doesn't support transactions
            $expected[] = [
                'Post' => [
                    'id' => '4',
                    'author_id' => 1,
                    'title' => 'New Fourth Post',
                    'body' => 'Third Post Body',
                    'published' => 'N',
                    'created' => $ts,
                    'updated' => $ts,
            ], ];

            $expected[] = [
                'Post' => [
                    'id' => '5',
                    'author_id' => 1,
                    'title' => 'Third Post',
                    'body' => 'Third Post Body',
                    'published' => 'N',
                    'created' => $ts,
                    'updated' => $ts,
            ], ];
        }
        $this->assertEqual($result, $expected);

        $TestModel->validate = ['title' => 'notEmpty'];
        $data = [
            ['author_id' => 1, 'title' => 'New Fourth Post'],
            ['author_id' => 1, 'title' => 'New Fifth Post'],
            ['author_id' => 1, 'title' => 'New Sixth Post'],
        ];
        $this->assertTrue($TestModel->saveAll($data));

        $result = $TestModel->find('all', [
            'recursive' => -1,
            'fields' => ['author_id', 'title', 'body', 'published'],
        ]);

        $expected = [
            ['Post' => [
                'author_id' => 1,
                'title' => 'First Post',
                'body' => 'First Post Body',
                'published' => 'Y',
            ]],
            ['Post' => [
                'author_id' => 3,
                'title' => 'Second Post',
                'body' => 'Second Post Body',
                'published' => 'Y',
            ]],
            ['Post' => [
                'author_id' => 1,
                'title' => 'Third Post',
                'body' => 'Third Post Body',
                'published' => 'Y',
            ]],
            ['Post' => [
                'author_id' => 1,
                'title' => 'New Fourth Post',
                'body' => '',
                'published' => 'N',
            ]],
            ['Post' => [
                'author_id' => 1,
                'title' => 'New Fifth Post',
                'body' => '',
                'published' => 'N',
            ]],
            ['Post' => [
                'author_id' => 1,
                'title' => 'New Sixth Post',
                'body' => '',
                'published' => 'N',
        ]], ];
        $this->assertEqual($result, $expected);
    }

    /**
     * testSaveAllValidation method.
     */
    public function testSaveAllValidation()
    {
        $this->loadFixtures('Post', 'Author', 'Comment', 'Attachment');
        $TestModel = new Post();

        $data = [
            [
                'id' => '1',
                'title' => 'Baleeted First Post',
                'body' => 'Baleeted!',
                'published' => 'N',
            ],
            [
                'id' => '2',
                'title' => 'Just update the title',
            ],
            [
                'title' => 'Creating a fourth post',
                'body' => 'Fourth post body',
                'author_id' => 2,
        ], ];

        $this->assertTrue($TestModel->saveAll($data));

        $result = $TestModel->find('all', ['recursive' => -1, 'order' => 'Post.id ASC']);
        $ts = date('Y-m-d H:i:s');
        $expected = [
            [
                'Post' => [
                    'id' => '1',
                    'author_id' => '1',
                    'title' => 'Baleeted First Post',
                    'body' => 'Baleeted!',
                    'published' => 'N',
                    'created' => '2007-03-18 10:39:23',
                    'updated' => $ts,
            ], ],
            [
                'Post' => [
                    'id' => '2',
                    'author_id' => '3',
                    'title' => 'Just update the title',
                    'body' => 'Second Post Body',
                    'published' => 'Y',
                    'created' => '2007-03-18 10:41:23', 'updated' => $ts,
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
            ], ],
            [
                'Post' => [
                    'id' => '4',
                    'author_id' => '2',
                    'title' => 'Creating a fourth post',
                    'body' => 'Fourth post body',
                    'published' => 'N',
                    'created' => $ts,
                    'updated' => $ts,
        ], ], ];
        $this->assertEqual($result, $expected);

        $TestModel->validate = ['title' => 'notEmpty', 'author_id' => 'numeric'];
        $data = [
            [
                'id' => '1',
                'title' => 'Un-Baleeted First Post',
                'body' => 'Not Baleeted!',
                'published' => 'Y',
            ],
            [
                'id' => '2',
                'title' => '',
                'body' => 'Trying to get away with an empty title',
        ], ];
        $result = $TestModel->saveAll($data);
        $this->assertEqual($result, false);

        $result = $TestModel->find('all', ['recursive' => -1, 'order' => 'Post.id ASC']);
        $errors = [1 => ['title' => 'This field cannot be left blank']];
        $transactionWorked = Set::matches('/Post[1][title=Baleeted First Post]', $result);
        if (!$transactionWorked) {
            $this->assertTrue(Set::matches('/Post[1][title=Un-Baleeted First Post]', $result));
            $this->assertTrue(Set::matches('/Post[2][title=Just update the title]', $result));
        }

        $this->assertEqual($TestModel->validationErrors, $errors);

        $TestModel->validate = ['title' => 'notEmpty', 'author_id' => 'numeric'];
        $data = [
            [
                'id' => '1',
                'title' => 'Un-Baleeted First Post',
                'body' => 'Not Baleeted!',
                'published' => 'Y',
            ],
            [
                'id' => '2',
                'title' => '',
                'body' => 'Trying to get away with an empty title',
        ], ];
        $result = $TestModel->saveAll($data, ['validate' => true, 'atomic' => false]);
        $this->assertEqual($result, [true, false]);
        $result = $TestModel->find('all', ['recursive' => -1, 'order' => 'Post.id ASC']);
        $errors = [1 => ['title' => 'This field cannot be left blank']];
        $newTs = date('Y-m-d H:i:s');
        $expected = [
            [
                'Post' => [
                    'id' => '1',
                    'author_id' => '1',
                    'title' => 'Un-Baleeted First Post',
                    'body' => 'Not Baleeted!',
                    'published' => 'Y',
                    'created' => '2007-03-18 10:39:23',
                    'updated' => $newTs,
            ], ],
            [
                'Post' => [
                    'id' => '2',
                    'author_id' => '3',
                    'title' => 'Just update the title',
                    'body' => 'Second Post Body',
                    'published' => 'Y',
                    'created' => '2007-03-18 10:41:23',
                    'updated' => $ts,
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
            ], ],
            [
                'Post' => [
                    'id' => '4',
                    'author_id' => '2',
                    'title' => 'Creating a fourth post',
                    'body' => 'Fourth post body',
                    'published' => 'N',
                    'created' => $ts,
                    'updated' => $ts,
        ], ], ];
        $this->assertEqual($result, $expected);
        $this->assertEqual($TestModel->validationErrors, $errors);

        $data = [
            [
                'id' => '1',
                'title' => 'Re-Baleeted First Post',
                'body' => 'Baleeted!',
                'published' => 'N',
            ],
            [
                'id' => '2',
                'title' => '',
                'body' => 'Trying to get away with an empty title',
        ], ];
        $this->assertFalse($TestModel->saveAll($data, ['validate' => 'first']));

        $result = $TestModel->find('all', ['recursive' => -1, 'order' => 'Post.id ASC']);
        $this->assertEqual($result, $expected);
        $this->assertEqual($TestModel->validationErrors, $errors);

        $data = [
            [
                'title' => 'First new post',
                'body' => 'Woohoo!',
                'published' => 'Y',
            ],
            [
                'title' => 'Empty body',
                'body' => '',
        ], ];

        $TestModel->validate['body'] = 'notEmpty';
    }

    /**
     * testSaveAllValidationOnly method.
     */
    public function testSaveAllValidationOnly()
    {
        $TestModel = new Comment();
        $TestModel->Attachment->validate = ['attachment' => 'notEmpty'];

        $data = [
            'Comment' => [
                'comment' => 'This is the comment',
            ],
            'Attachment' => [
                'attachment' => '',
            ],
        ];

        $result = $TestModel->saveAll($data, ['validate' => 'only']);
        $this->assertFalse($result);

        $TestModel = new Article();
        $TestModel->validate = ['title' => 'notEmpty'];
        $result = $TestModel->saveAll(
            [
                0 => ['title' => ''],
                1 => ['title' => 'title 1'],
                2 => ['title' => 'title 2'],
            ],
            ['validate' => 'only']
        );
        $this->assertFalse($result);
        $expected = [
            0 => ['title' => 'This field cannot be left blank'],
        ];
        $this->assertEqual($TestModel->validationErrors, $expected);

        $result = $TestModel->saveAll(
            [
                0 => ['title' => 'title 0'],
                1 => ['title' => ''],
                2 => ['title' => 'title 2'],
            ],
            ['validate' => 'only']
        );
        $this->assertFalse($result);
        $expected = [
            1 => ['title' => 'This field cannot be left blank'],
        ];
        $this->assertEqual($TestModel->validationErrors, $expected);
    }

    /**
     * testSaveAllValidateFirst method.
     */
    public function testSaveAllValidateFirst()
    {
        $model = new Article();
        $model->deleteAll(true);

        $model->Comment->validate = ['comment' => 'notEmpty'];
        $result = $model->saveAll([
            'Article' => [
                'title' => 'Post with Author',
                'body' => 'This post will be saved  author',
            ],
            'Comment' => [
                ['comment' => 'First new comment'],
                ['comment' => ''],
            ],
        ], ['validate' => 'first']);

        $this->assertFalse($result);

        $result = $model->find('all');
        $this->assertEqual($result, []);
        $expected = ['Comment' => [
            1 => ['comment' => 'This field cannot be left blank'],
        ]];

        $this->assertEqual($model->Comment->validationErrors, $expected['Comment']);

        $this->assertIdentical($model->Comment->find('count'), 0);

        $result = $model->saveAll(
            [
                'Article' => [
                    'title' => 'Post with Author',
                    'body' => 'This post will be saved with an author',
                    'user_id' => 2,
                ],
                'Comment' => [
                    [
                        'comment' => 'Only new comment',
                        'user_id' => 2,
            ], ], ],
            ['validate' => 'first']
        );

        $this->assertIdentical($result, true);

        $result = $model->Comment->find('all');
        $this->assertIdentical(count($result), 1);
        $result = Set::extract('/Comment/article_id', $result);
        $this->assertTrue(1 === $result[0] || '1' === $result[0]);

        $model->deleteAll(true);
        $data = [
            'Article' => [
                'title' => 'Post with Author saveAlled from comment',
                'body' => 'This post will be saved with an author',
                'user_id' => 2,
            ],
            'Comment' => [
                'comment' => 'Only new comment', 'user_id' => 2,
        ], ];

        $result = $model->Comment->saveAll($data, ['validate' => 'first']);
        $this->assertTrue($result);

        $result = $model->find('all');
        $this->assertEqual(
            $result[0]['Article']['title'],
            'Post with Author saveAlled from comment'
        );
        $this->assertEqual($result[0]['Comment'][0]['comment'], 'Only new comment');
    }

    /**
     * test saveAll()'s return is correct when using atomic = false and validate = first.
     */
    public function testSaveAllValidateFirstAtomicFalse()
    {
        $Something = new Something();
        $invalidData = [
            [
                'title' => 'foo',
                'body' => 'bar',
                'published' => 'baz',
            ],
            [
                'body' => 3,
                'published' => 'sd',
            ],
        ];
        $Something->create();
        $Something->validate = [
            'title' => [
                'rule' => 'alphaNumeric',
                'required' => true,
            ],
            'body' => [
                'rule' => 'alphaNumeric',
                'required' => true,
                'allowEmpty' => true,
            ],
        ];
        $result = $Something->saveAll($invalidData, [
            'atomic' => false,
            'validate' => 'first',
        ]);
        $expected = [true, false];
        $this->assertEqual($result, $expected);

        $Something = new Something();
        $validData = [
            [
                'title' => 'title value',
                'body' => 'body value',
                'published' => 'baz',
            ],
            [
                'title' => 'valid',
                'body' => 'this body',
                'published' => 'sd',
            ],
        ];
        $Something->create();
        $result = $Something->saveAll($validData, [
            'atomic' => false,
            'validate' => 'first',
        ]);
        $expected = [true, true];
        $this->assertEqual($result, $expected);
    }

    /**
     * testUpdateWithCalculation method.
     */
    public function testUpdateWithCalculation()
    {
        $this->loadFixtures('DataTest');
        $model = new DataTest();
        $model->deleteAll(true);
        $result = $model->saveAll([
            ['count' => 5, 'float' => 1.1],
            ['count' => 3, 'float' => 1.2],
            ['count' => 4, 'float' => 1.3],
            ['count' => 1, 'float' => 2.0],
        ]);
        $this->assertTrue($result);

        $result = Set::extract('/DataTest/count', $model->find('all', ['fields' => 'count']));
        $this->assertEqual($result, [5, 3, 4, 1]);

        $this->assertTrue($model->updateAll(['count' => 'count + 2']));
        $result = Set::extract('/DataTest/count', $model->find('all', ['fields' => 'count']));
        $this->assertEqual($result, [7, 5, 6, 3]);

        $this->assertTrue($model->updateAll(['DataTest.count' => 'DataTest.count - 1']));
        $result = Set::extract('/DataTest/count', $model->find('all', ['fields' => 'count']));
        $this->assertEqual($result, [6, 4, 5, 2]);
    }

    /**
     * testSaveAllHasManyValidationOnly method.
     */
    public function testSaveAllHasManyValidationOnly()
    {
        $this->loadFixtures('Article', 'Comment');
        $TestModel = new Article();
        $TestModel->belongsTo = $TestModel->hasAndBelongsToMany = [];
        $TestModel->Comment->validate = ['comment' => 'notEmpty'];

        $result = $TestModel->saveAll(
            [
                'Article' => ['id' => 2],
                'Comment' => [
                    [
                        'id' => 1,
                        'comment' => '',
                        'published' => 'Y',
                        'user_id' => 1, ],
                    [
                        'id' => 2,
                        'comment' => 'comment',
                        'published' => 'Y',
                        'user_id' => 1,
            ], ], ],
            ['validate' => 'only']
        );
        $this->assertFalse($result);

        $result = $TestModel->saveAll(
            [
                'Article' => ['id' => 2],
                'Comment' => [
                    [
                        'id' => 1,
                        'comment' => '',
                        'published' => 'Y',
                        'user_id' => 1,
                    ],
                    [
                        'id' => 2,
                        'comment' => 'comment',
                        'published' => 'Y',
                        'user_id' => 1,
                    ],
                    [
                        'id' => 3,
                        'comment' => '',
                        'published' => 'Y',
                        'user_id' => 1,
            ], ], ],
            [
                'validate' => 'only',
                'atomic' => false,
        ]);
        $expected = [
            'Article' => true,
            'Comment' => [false, true, false],
        ];
        $this->assertIdentical($result, $expected);

        $expected = ['Comment' => [
            0 => ['comment' => 'This field cannot be left blank'],
            2 => ['comment' => 'This field cannot be left blank'],
        ]];
        $this->assertEqual($TestModel->validationErrors, $expected);

        $expected = [
            0 => ['comment' => 'This field cannot be left blank'],
            2 => ['comment' => 'This field cannot be left blank'],
        ];
        $this->assertEqual($TestModel->Comment->validationErrors, $expected);
    }

    /**
     * TestFindAllWithoutForeignKey.
     *
     * @see http://code.cakephp.org/tickets/view/69
     */
    public function testFindAllForeignKey()
    {
        $this->loadFixtures('ProductUpdateAll', 'GroupUpdateAll');
        $ProductUpdateAll = new ProductUpdateAll();

        $conditions = ['Group.name' => 'group one'];

        $ProductUpdateAll->bindModel([
            'belongsTo' => [
                'Group' => ['className' => 'GroupUpdateAll'],
            ],
        ]);

        $ProductUpdateAll->belongsTo = [
            'Group' => ['className' => 'GroupUpdateAll', 'foreignKey' => 'group_id'],
        ];

        $results = $ProductUpdateAll->find('all', compact('conditions'));
        $this->assertTrue(!empty($results));

        $ProductUpdateAll->bindModel(['belongsTo' => ['Group']]);
        $ProductUpdateAll->belongsTo = [
            'Group' => [
                'className' => 'GroupUpdateAll',
                'foreignKey' => false,
                'conditions' => 'ProductUpdateAll.groupcode = Group.code',
            ], ];

        $resultsFkFalse = $ProductUpdateAll->find('all', compact('conditions'));
        $this->assertTrue(!empty($resultsFkFalse));
        $expected = [
            '0' => [
                'ProductUpdateAll' => [
                    'id' => 1,
                    'name' => 'product one',
                    'groupcode' => 120,
                    'group_id' => 1, ],
                'Group' => [
                    'id' => 1,
                    'name' => 'group one',
                    'code' => 120, ],
                ],
            '1' => [
                'ProductUpdateAll' => [
                    'id' => 2,
                    'name' => 'product two',
                    'groupcode' => 120,
                    'group_id' => 1, ],
                'Group' => [
                    'id' => 1,
                    'name' => 'group one',
                    'code' => 120, ],
                ],
            ];
        $this->assertEqual($results, $expected);
        $this->assertEqual($resultsFkFalse, $expected);
    }

    /**
     * test updateAll with empty values.
     */
    public function testUpdateAllEmptyValues()
    {
        $this->loadFixtures('Author', 'Post');
        $model = new Author();
        $result = $model->updateAll(['user' => '""']);
        $this->assertTrue($result);
    }

    /**
     * testUpdateAllWithJoins.
     *
     * @see http://code.cakephp.org/tickets/view/69
     */
    public function testUpdateAllWithJoins()
    {
        $this->skipIf(
            'postgres' == $this->db->config['driver'],
            '%s Currently, there is no way of doing joins in an update statement in postgresql'
        );
        $this->loadFixtures('ProductUpdateAll', 'GroupUpdateAll');
        $ProductUpdateAll = new ProductUpdateAll();

        $conditions = ['Group.name' => 'group one'];

        $ProductUpdateAll->bindModel(['belongsTo' => [
            'Group' => ['className' => 'GroupUpdateAll'], ]]
        );

        $ProductUpdateAll->updateAll(['name' => "'new product'"], $conditions);
        $results = $ProductUpdateAll->find('all', [
            'conditions' => ['ProductUpdateAll.name' => 'new product'],
        ]);
        $expected = [
            '0' => [
                'ProductUpdateAll' => [
                    'id' => 1,
                    'name' => 'new product',
                    'groupcode' => 120,
                    'group_id' => 1, ],
                'Group' => [
                    'id' => 1,
                    'name' => 'group one',
                    'code' => 120, ],
                ],
            '1' => [
                'ProductUpdateAll' => [
                    'id' => 2,
                    'name' => 'new product',
                    'groupcode' => 120,
                    'group_id' => 1, ],
                'Group' => [
                    'id' => 1,
                    'name' => 'group one',
                    'code' => 120, ], ], ];

        $this->assertEqual($results, $expected);
    }

    /**
     * testUpdateAllWithoutForeignKey.
     *
     * @see http://code.cakephp.org/tickets/view/69
     */
    public function testUpdateAllWithoutForeignKey()
    {
        $this->skipIf(
            'postgres' == $this->db->config['driver'],
            '%s Currently, there is no way of doing joins in an update statement in postgresql'
        );
        $this->loadFixtures('ProductUpdateAll', 'GroupUpdateAll');
        $ProductUpdateAll = new ProductUpdateAll();

        $conditions = ['Group.name' => 'group one'];

        $ProductUpdateAll->bindModel(['belongsTo' => [
            'Group' => ['className' => 'GroupUpdateAll'],
        ]]);

        $ProductUpdateAll->belongsTo = [
            'Group' => [
                'className' => 'GroupUpdateAll',
                'foreignKey' => false,
                'conditions' => 'ProductUpdateAll.groupcode = Group.code',
            ],
        ];

        $ProductUpdateAll->updateAll(['name' => "'new product'"], $conditions);
        $resultsFkFalse = $ProductUpdateAll->find('all', ['conditions' => ['ProductUpdateAll.name' => 'new product']]);
        $expected = [
            '0' => [
                'ProductUpdateAll' => [
                    'id' => 1,
                    'name' => 'new product',
                    'groupcode' => 120,
                    'group_id' => 1, ],
                'Group' => [
                    'id' => 1,
                    'name' => 'group one',
                    'code' => 120, ],
                ],
            '1' => [
                'ProductUpdateAll' => [
                    'id' => 2,
                    'name' => 'new product',
                    'groupcode' => 120,
                    'group_id' => 1, ],
                'Group' => [
                    'id' => 1,
                    'name' => 'group one',
                    'code' => 120, ], ], ];
        $this->assertEqual($resultsFkFalse, $expected);
    }

    /**
     * test that saveAll behaves like plain save() when suplied empty data.
     *
     * @see http://cakephp.lighthouseapp.com/projects/42648/tickets/277-test-saveall-with-validation-returns-incorrect-boolean-when-saving-empty-data
     */
    public function testSaveAllEmptyData()
    {
        $this->loadFixtures('Article', 'ProductUpdateAll');
        $model = new Article();
        $result = $model->saveAll([], ['validate' => 'first']);
        $this->assertTrue($result);

        $model = new ProductUpdateAll();
        $result = $model->saveAll([]);
        $this->assertFalse($result);
    }

    /**
     * test writing floats in german locale.
     */
    public function testWriteFloatAsGerman()
    {
        $restore = setlocale(LC_ALL, null);
        setlocale(LC_ALL, 'de_DE');

        $model = new DataTest();
        $result = $model->save([
            'count' => 1,
            'float' => 3.14593,
        ]);
        $this->assertTrue($result);
        setlocale(LC_ALL, $restore);
    }
}
