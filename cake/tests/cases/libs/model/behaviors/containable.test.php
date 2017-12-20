<?php
/**
 * ContainableBehaviorTest file.
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
 * @since         CakePHP(tm) v 1.2.0.5669
 *
 * @license       http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */
App::import('Core', ['AppModel', 'Model']);
require_once dirname(dirname(__FILE__)).DS.'models.php';

/**
 * ContainableTest class.
 */
class ContainableBehaviorTest extends CakeTestCase
{
    /**
     * Fixtures associated with this test case.
     *
     * @var array
     */
    public $fixtures = [
        'core.article', 'core.article_featured', 'core.article_featureds_tags', 'core.articles_tag', 'core.attachment', 'core.category',
        'core.comment', 'core.featured', 'core.tag', 'core.user', 'core.join_a', 'core.join_b', 'core.join_c', 'core.join_a_c', 'core.join_a_b',
    ];

    /**
     * Method executed before each test.
     */
    public function startTest()
    {
        $this->User = &ClassRegistry::init('User');
        $this->Article = &ClassRegistry::init('Article');
        $this->Tag = &ClassRegistry::init('Tag');

        $this->User->bindModel([
            'hasMany' => ['Article', 'ArticleFeatured', 'Comment'],
        ], false);
        $this->User->ArticleFeatured->unbindModel(['belongsTo' => ['Category']], false);
        $this->User->ArticleFeatured->hasMany['Comment']['foreignKey'] = 'article_id';

        $this->Tag->bindModel([
            'hasAndBelongsToMany' => ['Article'],
        ], false);

        $this->User->Behaviors->attach('Containable');
        $this->Article->Behaviors->attach('Containable');
        $this->Tag->Behaviors->attach('Containable');
    }

    /**
     * Method executed after each test.
     */
    public function endTest()
    {
        unset($this->Article);
        unset($this->User);
        unset($this->Tag);

        ClassRegistry::flush();
    }

    /**
     * testContainments method.
     */
    public function testContainments()
    {
        $r = $this->__containments($this->Article, ['Comment' => ['conditions' => ['Comment.user_id' => 2]]]);
        $this->assertTrue(Set::matches('/Article/keep/Comment/conditions[Comment.user_id=2]', $r));

        $r = $this->__containments($this->User, [
            'ArticleFeatured' => [
                'Featured' => [
                    'id',
                    'Category' => 'name',
                ],
        ], ]);
        $this->assertEqual(Set::extract('/ArticleFeatured/keep/Featured/fields', $r), ['id']);

        $r = $this->__containments($this->Article, [
            'Comment' => [
                'User',
                'conditions' => ['Comment' => ['user_id' => 2]],
            ],
        ]);
        $this->assertTrue(Set::matches('/User', $r));
        $this->assertTrue(Set::matches('/Comment', $r));
        $this->assertTrue(Set::matches('/Article/keep/Comment/conditions/Comment[user_id=2]', $r));

        $r = $this->__containments($this->Article, ['Comment(comment, published)' => 'Attachment(attachment)', 'User(user)']);
        $this->assertTrue(Set::matches('/Comment', $r));
        $this->assertTrue(Set::matches('/User', $r));
        $this->assertTrue(Set::matches('/Article/keep/Comment', $r));
        $this->assertTrue(Set::matches('/Article/keep/User', $r));
        $this->assertEqual(Set::extract('/Article/keep/Comment/fields', $r), ['comment', 'published']);
        $this->assertEqual(Set::extract('/Article/keep/User/fields', $r), ['user']);
        $this->assertTrue(Set::matches('/Comment/keep/Attachment', $r));
        $this->assertEqual(Set::extract('/Comment/keep/Attachment/fields', $r), ['attachment']);

        $r = $this->__containments($this->Article, ['Comment' => ['limit' => 1]]);
        $this->assertEqual(array_keys($r), ['Comment', 'Article']);
        $this->assertEqual(array_shift(Set::extract('/Comment/keep', $r)), ['keep' => []]);
        $this->assertTrue(Set::matches('/Article/keep/Comment', $r));
        $this->assertEqual(array_shift(Set::extract('/Article/keep/Comment/.', $r)), ['limit' => 1]);

        $r = $this->__containments($this->Article, ['Comment.User']);
        $this->assertEqual(array_keys($r), ['User', 'Comment', 'Article']);
        $this->assertEqual(array_shift(Set::extract('/User/keep', $r)), ['keep' => []]);
        $this->assertEqual(array_shift(Set::extract('/Comment/keep', $r)), ['keep' => ['User' => []]]);
        $this->assertEqual(array_shift(Set::extract('/Article/keep', $r)), ['keep' => ['Comment' => []]]);

        $r = $this->__containments($this->Tag, ['Article' => ['User' => ['Comment' => [
            'Attachment' => ['conditions' => ['Attachment.id >' => 1]],
        ]]]]);
        $this->assertTrue(Set::matches('/Attachment', $r));
        $this->assertTrue(Set::matches('/Comment/keep/Attachment/conditions', $r));
        $this->assertEqual($r['Comment']['keep']['Attachment']['conditions'], ['Attachment.id >' => 1]);
        $this->assertTrue(Set::matches('/User/keep/Comment', $r));
        $this->assertTrue(Set::matches('/Article/keep/User', $r));
        $this->assertTrue(Set::matches('/Tag/keep/Article', $r));
    }

    /**
     * testInvalidContainments method.
     */
    public function testInvalidContainments()
    {
        $this->expectError();
        $r = $this->__containments($this->Article, ['Comment', 'InvalidBinding']);

        $this->Article->Behaviors->attach('Containable', ['notices' => false]);
        $r = $this->__containments($this->Article, ['Comment', 'InvalidBinding']);
    }

    /**
     * testBeforeFind method.
     */
    public function testBeforeFind()
    {
        $r = $this->Article->find('all', ['contain' => ['Comment']]);
        $this->assertFalse(Set::matches('/User', $r));
        $this->assertTrue(Set::matches('/Comment', $r));
        $this->assertFalse(Set::matches('/Comment/User', $r));

        $r = $this->Article->find('all', ['contain' => 'Comment.User']);
        $this->assertTrue(Set::matches('/Comment/User', $r));
        $this->assertFalse(Set::matches('/Comment/Article', $r));

        $r = $this->Article->find('all', ['contain' => ['Comment' => ['User', 'Article']]]);
        $this->assertTrue(Set::matches('/Comment/User', $r));
        $this->assertTrue(Set::matches('/Comment/Article', $r));

        $r = $this->Article->find('all', ['contain' => ['Comment' => ['conditions' => ['Comment.user_id' => 2]]]]);
        $this->assertFalse(Set::matches('/Comment[user_id!=2]', $r));
        $this->assertTrue(Set::matches('/Comment[user_id=2]', $r));

        $r = $this->Article->find('all', ['contain' => ['Comment.user_id = 2']]);
        $this->assertFalse(Set::matches('/Comment[user_id!=2]', $r));

        $r = $this->Article->find('all', ['contain' => 'Comment.id DESC']);
        $ids = $descIds = Set::extract('/Comment[1]/id', $r);
        rsort($descIds);
        $this->assertEqual($ids, $descIds);

        $r = $this->Article->find('all', ['contain' => 'Comment']);
        $this->assertTrue(Set::matches('/Comment[user_id!=2]', $r));

        $r = $this->Article->find('all', ['contain' => ['Comment' => ['fields' => 'comment']]]);
        $this->assertFalse(Set::matches('/Comment/created', $r));
        $this->assertTrue(Set::matches('/Comment/comment', $r));
        $this->assertFalse(Set::matches('/Comment/updated', $r));

        $r = $this->Article->find('all', ['contain' => ['Comment' => ['fields' => ['comment', 'updated']]]]);
        $this->assertFalse(Set::matches('/Comment/created', $r));
        $this->assertTrue(Set::matches('/Comment/comment', $r));
        $this->assertTrue(Set::matches('/Comment/updated', $r));

        $r = $this->Article->find('all', ['contain' => ['Comment' => ['comment', 'updated']]]);
        $this->assertFalse(Set::matches('/Comment/created', $r));
        $this->assertTrue(Set::matches('/Comment/comment', $r));
        $this->assertTrue(Set::matches('/Comment/updated', $r));

        $r = $this->Article->find('all', ['contain' => ['Comment(comment,updated)']]);
        $this->assertFalse(Set::matches('/Comment/created', $r));
        $this->assertTrue(Set::matches('/Comment/comment', $r));
        $this->assertTrue(Set::matches('/Comment/updated', $r));

        $r = $this->Article->find('all', ['contain' => 'Comment.created']);
        $this->assertTrue(Set::matches('/Comment/created', $r));
        $this->assertFalse(Set::matches('/Comment/comment', $r));

        $r = $this->Article->find('all', ['contain' => ['User.Article(title)', 'Comment(comment)']]);
        $this->assertFalse(Set::matches('/Comment/Article', $r));
        $this->assertFalse(Set::matches('/Comment/User', $r));
        $this->assertTrue(Set::matches('/Comment/comment', $r));
        $this->assertFalse(Set::matches('/Comment/created', $r));
        $this->assertTrue(Set::matches('/User/Article/title', $r));
        $this->assertFalse(Set::matches('/User/Article/created', $r));

        $r = $this->Article->find('all', ['contain' => []]);
        $this->assertFalse(Set::matches('/User', $r));
        $this->assertFalse(Set::matches('/Comment', $r));

        $this->expectError();
        $r = $this->Article->find('all', ['contain' => ['Comment' => 'NonExistingBinding']]);
    }

    /**
     * testContain method.
     */
    public function testContain()
    {
        $this->Article->contain('Comment.User');
        $r = $this->Article->find('all');
        $this->assertTrue(Set::matches('/Comment/User', $r));
        $this->assertFalse(Set::matches('/Comment/Article', $r));

        $r = $this->Article->find('all');
        $this->assertFalse(Set::matches('/Comment/User', $r));
    }

    /**
     * Test that mixing contain() and the contain find option.
     */
    public function testContainAndContainOption()
    {
        $this->Article->contain();
        $r = $this->Article->find('all', [
            'contain' => ['Comment'],
        ]);
        $this->assertTrue(isset($r[0]['Comment']), 'No comment returned');
    }

    /**
     * testFindEmbeddedNoBindings method.
     */
    public function testFindEmbeddedNoBindings()
    {
        $result = $this->Article->find('all', ['contain' => false]);
        $expected = [
            ['Article' => [
                'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
                'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
            ]],
            ['Article' => [
                'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
                'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31',
            ]],
            ['Article' => [
                'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
                'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31',
            ]],
        ];
        $this->assertEqual($result, $expected);
    }

    /**
     * testFindFirstLevel method.
     */
    public function testFindFirstLevel()
    {
        $this->Article->contain('User');
        $result = $this->Article->find('all', ['recursive' => 1]);
        $expected = [
            [
                'Article' => [
                    'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
                    'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                ],
                'User' => [
                    'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31',
                ],
            ],
            [
                'Article' => [
                    'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
                    'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31',
                ],
                'User' => [
                    'id' => 3, 'user' => 'larry', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:20:23', 'updated' => '2007-03-17 01:22:31',
                ],
            ],
            [
                'Article' => [
                    'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
                    'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31',
                ],
                'User' => [
                    'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31',
                ],
            ],
        ];
        $this->assertEqual($result, $expected);

        $this->Article->contain('User', 'Comment');
        $result = $this->Article->find('all', ['recursive' => 1]);
        $expected = [
            [
                'Article' => [
                    'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
                    'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                ],
                'User' => [
                    'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31',
                ],
                'Comment' => [
                    [
                        'id' => 1, 'article_id' => 1, 'user_id' => 2, 'comment' => 'First Comment for First Article',
                        'published' => 'Y', 'created' => '2007-03-18 10:45:23', 'updated' => '2007-03-18 10:47:31',
                    ],
                    [
                        'id' => 2, 'article_id' => 1, 'user_id' => 4, 'comment' => 'Second Comment for First Article',
                        'published' => 'Y', 'created' => '2007-03-18 10:47:23', 'updated' => '2007-03-18 10:49:31',
                    ],
                    [
                        'id' => 3, 'article_id' => 1, 'user_id' => 1, 'comment' => 'Third Comment for First Article',
                        'published' => 'Y', 'created' => '2007-03-18 10:49:23', 'updated' => '2007-03-18 10:51:31',
                    ],
                    [
                        'id' => 4, 'article_id' => 1, 'user_id' => 1, 'comment' => 'Fourth Comment for First Article',
                        'published' => 'N', 'created' => '2007-03-18 10:51:23', 'updated' => '2007-03-18 10:53:31',
                    ],
                ],
            ],
            [
                'Article' => [
                    'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
                    'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31',
                ],
                'User' => [
                    'id' => 3, 'user' => 'larry', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:20:23', 'updated' => '2007-03-17 01:22:31',
                ],
                'Comment' => [
                    [
                        'id' => 5, 'article_id' => 2, 'user_id' => 1, 'comment' => 'First Comment for Second Article',
                        'published' => 'Y', 'created' => '2007-03-18 10:53:23', 'updated' => '2007-03-18 10:55:31',
                    ],
                    [
                        'id' => 6, 'article_id' => 2, 'user_id' => 2, 'comment' => 'Second Comment for Second Article',
                        'published' => 'Y', 'created' => '2007-03-18 10:55:23', 'updated' => '2007-03-18 10:57:31',
                    ],
                ],
            ],
            [
                'Article' => [
                    'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
                    'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31',
                ],
                'User' => [
                    'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31',
                ],
                'Comment' => [],
            ],
        ];
        $this->assertEqual($result, $expected);
    }

    /**
     * testFindEmbeddedFirstLevel method.
     */
    public function testFindEmbeddedFirstLevel()
    {
        $result = $this->Article->find('all', ['contain' => ['User']]);
        $expected = [
            [
                'Article' => [
                    'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
                    'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                ],
                'User' => [
                    'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31',
                ],
            ],
            [
                'Article' => [
                    'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
                    'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31',
                ],
                'User' => [
                    'id' => 3, 'user' => 'larry', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:20:23', 'updated' => '2007-03-17 01:22:31',
                ],
            ],
            [
                'Article' => [
                    'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
                    'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31',
                ],
                'User' => [
                    'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31',
                ],
            ],
        ];
        $this->assertEqual($result, $expected);

        $result = $this->Article->find('all', ['contain' => ['User', 'Comment']]);
        $expected = [
            [
                'Article' => [
                    'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
                    'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                ],
                'User' => [
                    'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31',
                ],
                'Comment' => [
                    [
                        'id' => 1, 'article_id' => 1, 'user_id' => 2, 'comment' => 'First Comment for First Article',
                        'published' => 'Y', 'created' => '2007-03-18 10:45:23', 'updated' => '2007-03-18 10:47:31',
                    ],
                    [
                        'id' => 2, 'article_id' => 1, 'user_id' => 4, 'comment' => 'Second Comment for First Article',
                        'published' => 'Y', 'created' => '2007-03-18 10:47:23', 'updated' => '2007-03-18 10:49:31',
                    ],
                    [
                        'id' => 3, 'article_id' => 1, 'user_id' => 1, 'comment' => 'Third Comment for First Article',
                        'published' => 'Y', 'created' => '2007-03-18 10:49:23', 'updated' => '2007-03-18 10:51:31',
                    ],
                    [
                        'id' => 4, 'article_id' => 1, 'user_id' => 1, 'comment' => 'Fourth Comment for First Article',
                        'published' => 'N', 'created' => '2007-03-18 10:51:23', 'updated' => '2007-03-18 10:53:31',
                    ],
                ],
            ],
            [
                'Article' => [
                    'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
                    'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31',
                ],
                'User' => [
                    'id' => 3, 'user' => 'larry', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:20:23', 'updated' => '2007-03-17 01:22:31',
                ],
                'Comment' => [
                    [
                        'id' => 5, 'article_id' => 2, 'user_id' => 1, 'comment' => 'First Comment for Second Article',
                        'published' => 'Y', 'created' => '2007-03-18 10:53:23', 'updated' => '2007-03-18 10:55:31',
                    ],
                    [
                        'id' => 6, 'article_id' => 2, 'user_id' => 2, 'comment' => 'Second Comment for Second Article',
                        'published' => 'Y', 'created' => '2007-03-18 10:55:23', 'updated' => '2007-03-18 10:57:31',
                    ],
                ],
            ],
            [
                'Article' => [
                    'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
                    'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31',
                ],
                'User' => [
                    'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31',
                ],
                'Comment' => [],
            ],
        ];
        $this->assertEqual($result, $expected);
    }

    /**
     * testFindSecondLevel method.
     */
    public function testFindSecondLevel()
    {
        $this->Article->contain(['Comment' => 'User']);
        $result = $this->Article->find('all', ['recursive' => 2]);
        $expected = [
            [
                'Article' => [
                    'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
                    'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                ],
                'Comment' => [
                    [
                        'id' => 1, 'article_id' => 1, 'user_id' => 2, 'comment' => 'First Comment for First Article',
                        'published' => 'Y', 'created' => '2007-03-18 10:45:23', 'updated' => '2007-03-18 10:47:31',
                        'User' => [
                            'id' => 2, 'user' => 'nate', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                            'created' => '2007-03-17 01:18:23', 'updated' => '2007-03-17 01:20:31',
                        ],
                    ],
                    [
                        'id' => 2, 'article_id' => 1, 'user_id' => 4, 'comment' => 'Second Comment for First Article',
                        'published' => 'Y', 'created' => '2007-03-18 10:47:23', 'updated' => '2007-03-18 10:49:31',
                        'User' => [
                            'id' => 4, 'user' => 'garrett', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                            'created' => '2007-03-17 01:22:23', 'updated' => '2007-03-17 01:24:31',
                        ],
                    ],
                    [
                        'id' => 3, 'article_id' => 1, 'user_id' => 1, 'comment' => 'Third Comment for First Article',
                        'published' => 'Y', 'created' => '2007-03-18 10:49:23', 'updated' => '2007-03-18 10:51:31',
                        'User' => [
                            'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                            'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31',
                        ],
                    ],
                    [
                        'id' => 4, 'article_id' => 1, 'user_id' => 1, 'comment' => 'Fourth Comment for First Article',
                        'published' => 'N', 'created' => '2007-03-18 10:51:23', 'updated' => '2007-03-18 10:53:31',
                        'User' => [
                            'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                            'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31',
                        ],
                    ],
                ],
            ],
            [
                'Article' => [
                    'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
                    'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31',
                ],
                'Comment' => [
                    [
                        'id' => 5, 'article_id' => 2, 'user_id' => 1, 'comment' => 'First Comment for Second Article',
                        'published' => 'Y', 'created' => '2007-03-18 10:53:23', 'updated' => '2007-03-18 10:55:31',
                        'User' => [
                            'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                            'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31',
                        ],
                    ],
                    [
                        'id' => 6, 'article_id' => 2, 'user_id' => 2, 'comment' => 'Second Comment for Second Article',
                        'published' => 'Y', 'created' => '2007-03-18 10:55:23', 'updated' => '2007-03-18 10:57:31',
                        'User' => [
                            'id' => 2, 'user' => 'nate', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                            'created' => '2007-03-17 01:18:23', 'updated' => '2007-03-17 01:20:31',
                        ],
                    ],
                ],
            ],
            [
                'Article' => [
                    'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
                    'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31',
                ],
                'Comment' => [],
            ],
        ];
        $this->assertEqual($result, $expected);

        $this->Article->contain(['User' => 'ArticleFeatured']);
        $result = $this->Article->find('all', ['recursive' => 2]);
        $expected = [
            [
                'Article' => [
                    'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
                    'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                ],
                'User' => [
                    'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31',
                    'ArticleFeatured' => [
                        [
                            'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
                            'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                        ],
                        [
                            'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
                            'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31',
                        ],
                    ],
                ],
            ],
            [
                'Article' => [
                    'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
                    'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31',
                ],
                'User' => [
                    'id' => 3, 'user' => 'larry', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:20:23', 'updated' => '2007-03-17 01:22:31',
                    'ArticleFeatured' => [
                        [
                        'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
                        'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31',
                        ],
                    ],
                ],
            ],
            [
                'Article' => [
                    'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
                    'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31',
                ],
                'User' => [
                    'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31',
                    'ArticleFeatured' => [
                        [
                            'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
                            'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                        ],
                        [
                            'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
                            'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31',
                        ],
                    ],
                ],
            ],
        ];
        $this->assertEqual($result, $expected);

        $this->Article->contain(['User' => ['ArticleFeatured', 'Comment']]);
        $result = $this->Article->find('all', ['recursive' => 2]);
        $expected = [
            [
                'Article' => [
                    'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
                    'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                ],
                'User' => [
                    'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31',
                    'ArticleFeatured' => [
                        [
                            'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
                            'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                        ],
                        [
                            'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
                            'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31',
                        ],
                    ],
                    'Comment' => [
                        [
                            'id' => 3, 'article_id' => 1, 'user_id' => 1, 'comment' => 'Third Comment for First Article',
                            'published' => 'Y', 'created' => '2007-03-18 10:49:23', 'updated' => '2007-03-18 10:51:31',
                        ],
                        [
                            'id' => 4, 'article_id' => 1, 'user_id' => 1, 'comment' => 'Fourth Comment for First Article',
                            'published' => 'N', 'created' => '2007-03-18 10:51:23', 'updated' => '2007-03-18 10:53:31',
                        ],
                        [
                            'id' => 5, 'article_id' => 2, 'user_id' => 1, 'comment' => 'First Comment for Second Article',
                            'published' => 'Y', 'created' => '2007-03-18 10:53:23', 'updated' => '2007-03-18 10:55:31',
                        ],
                    ],
                ],
            ],
            [
                'Article' => [
                    'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
                    'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31',
                ],
                'User' => [
                    'id' => 3, 'user' => 'larry', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:20:23', 'updated' => '2007-03-17 01:22:31',
                    'ArticleFeatured' => [
                        [
                        'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
                        'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31',
                        ],
                    ],
                    'Comment' => [],
                ],
            ],
            [
                'Article' => [
                    'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
                    'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31',
                ],
                'User' => [
                    'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31',
                    'ArticleFeatured' => [
                        [
                            'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
                            'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                        ],
                        [
                            'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
                            'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31',
                        ],
                    ],
                    'Comment' => [
                        [
                            'id' => 3, 'article_id' => 1, 'user_id' => 1, 'comment' => 'Third Comment for First Article',
                            'published' => 'Y', 'created' => '2007-03-18 10:49:23', 'updated' => '2007-03-18 10:51:31',
                        ],
                        [
                            'id' => 4, 'article_id' => 1, 'user_id' => 1, 'comment' => 'Fourth Comment for First Article',
                            'published' => 'N', 'created' => '2007-03-18 10:51:23', 'updated' => '2007-03-18 10:53:31',
                        ],
                        [
                            'id' => 5, 'article_id' => 2, 'user_id' => 1, 'comment' => 'First Comment for Second Article',
                            'published' => 'Y', 'created' => '2007-03-18 10:53:23', 'updated' => '2007-03-18 10:55:31',
                        ],
                    ],
                ],
            ],
        ];
        $this->assertEqual($result, $expected);

        $this->Article->contain(['User' => ['ArticleFeatured']], 'Tag', ['Comment' => 'Attachment']);
        $result = $this->Article->find('all', ['recursive' => 2]);
        $expected = [
            [
                'Article' => [
                    'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
                    'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                ],
                'User' => [
                    'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31',
                    'ArticleFeatured' => [
                        [
                            'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
                            'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                        ],
                        [
                            'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
                            'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31',
                        ],
                    ],
                ],
                'Comment' => [
                    [
                        'id' => 1, 'article_id' => 1, 'user_id' => 2, 'comment' => 'First Comment for First Article',
                        'published' => 'Y', 'created' => '2007-03-18 10:45:23', 'updated' => '2007-03-18 10:47:31',
                        'Attachment' => [],
                    ],
                    [
                        'id' => 2, 'article_id' => 1, 'user_id' => 4, 'comment' => 'Second Comment for First Article',
                        'published' => 'Y', 'created' => '2007-03-18 10:47:23', 'updated' => '2007-03-18 10:49:31',
                        'Attachment' => [],
                    ],
                    [
                        'id' => 3, 'article_id' => 1, 'user_id' => 1, 'comment' => 'Third Comment for First Article',
                        'published' => 'Y', 'created' => '2007-03-18 10:49:23', 'updated' => '2007-03-18 10:51:31',
                        'Attachment' => [],
                    ],
                    [
                        'id' => 4, 'article_id' => 1, 'user_id' => 1, 'comment' => 'Fourth Comment for First Article',
                        'published' => 'N', 'created' => '2007-03-18 10:51:23', 'updated' => '2007-03-18 10:53:31',
                        'Attachment' => [],
                    ],
                ],
                'Tag' => [
                    ['id' => 1, 'tag' => 'tag1', 'created' => '2007-03-18 12:22:23', 'updated' => '2007-03-18 12:24:31'],
                    ['id' => 2, 'tag' => 'tag2', 'created' => '2007-03-18 12:24:23', 'updated' => '2007-03-18 12:26:31'],
                ],
            ],
            [
                'Article' => [
                    'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
                    'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31',
                ],
                'User' => [
                    'id' => 3, 'user' => 'larry', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:20:23', 'updated' => '2007-03-17 01:22:31',
                    'ArticleFeatured' => [
                        [
                        'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
                        'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31',
                        ],
                    ],
                ],
                'Comment' => [
                    [
                        'id' => 5, 'article_id' => 2, 'user_id' => 1, 'comment' => 'First Comment for Second Article',
                        'published' => 'Y', 'created' => '2007-03-18 10:53:23', 'updated' => '2007-03-18 10:55:31',
                        'Attachment' => [
                            'id' => 1, 'comment_id' => 5, 'attachment' => 'attachment.zip',
                            'created' => '2007-03-18 10:51:23', 'updated' => '2007-03-18 10:53:31',
                        ],
                    ],
                    [
                        'id' => 6, 'article_id' => 2, 'user_id' => 2, 'comment' => 'Second Comment for Second Article',
                        'published' => 'Y', 'created' => '2007-03-18 10:55:23', 'updated' => '2007-03-18 10:57:31',
                        'Attachment' => [],
                    ],
                ],
                'Tag' => [
                    ['id' => 1, 'tag' => 'tag1', 'created' => '2007-03-18 12:22:23', 'updated' => '2007-03-18 12:24:31'],
                    ['id' => 3, 'tag' => 'tag3', 'created' => '2007-03-18 12:26:23', 'updated' => '2007-03-18 12:28:31'],
                ],
            ],
            [
                'Article' => [
                    'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
                    'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31',
                ],
                'User' => [
                    'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31',
                    'ArticleFeatured' => [
                        [
                            'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
                            'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                        ],
                        [
                            'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
                            'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31',
                        ],
                    ],
                ],
                'Comment' => [],
                'Tag' => [],
            ],
        ];
        $this->assertEqual($result, $expected);
    }

    /**
     * testFindEmbeddedSecondLevel method.
     */
    public function testFindEmbeddedSecondLevel()
    {
        $result = $this->Article->find('all', ['contain' => ['Comment' => 'User']]);
        $expected = [
            [
                'Article' => [
                    'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
                    'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                ],
                'Comment' => [
                    [
                        'id' => 1, 'article_id' => 1, 'user_id' => 2, 'comment' => 'First Comment for First Article',
                        'published' => 'Y', 'created' => '2007-03-18 10:45:23', 'updated' => '2007-03-18 10:47:31',
                        'User' => [
                            'id' => 2, 'user' => 'nate', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                            'created' => '2007-03-17 01:18:23', 'updated' => '2007-03-17 01:20:31',
                        ],
                    ],
                    [
                        'id' => 2, 'article_id' => 1, 'user_id' => 4, 'comment' => 'Second Comment for First Article',
                        'published' => 'Y', 'created' => '2007-03-18 10:47:23', 'updated' => '2007-03-18 10:49:31',
                        'User' => [
                            'id' => 4, 'user' => 'garrett', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                            'created' => '2007-03-17 01:22:23', 'updated' => '2007-03-17 01:24:31',
                        ],
                    ],
                    [
                        'id' => 3, 'article_id' => 1, 'user_id' => 1, 'comment' => 'Third Comment for First Article',
                        'published' => 'Y', 'created' => '2007-03-18 10:49:23', 'updated' => '2007-03-18 10:51:31',
                        'User' => [
                            'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                            'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31',
                        ],
                    ],
                    [
                        'id' => 4, 'article_id' => 1, 'user_id' => 1, 'comment' => 'Fourth Comment for First Article',
                        'published' => 'N', 'created' => '2007-03-18 10:51:23', 'updated' => '2007-03-18 10:53:31',
                        'User' => [
                            'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                            'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31',
                        ],
                    ],
                ],
            ],
            [
                'Article' => [
                    'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
                    'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31',
                ],
                'Comment' => [
                    [
                        'id' => 5, 'article_id' => 2, 'user_id' => 1, 'comment' => 'First Comment for Second Article',
                        'published' => 'Y', 'created' => '2007-03-18 10:53:23', 'updated' => '2007-03-18 10:55:31',
                        'User' => [
                            'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                            'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31',
                        ],
                    ],
                    [
                        'id' => 6, 'article_id' => 2, 'user_id' => 2, 'comment' => 'Second Comment for Second Article',
                        'published' => 'Y', 'created' => '2007-03-18 10:55:23', 'updated' => '2007-03-18 10:57:31',
                        'User' => [
                            'id' => 2, 'user' => 'nate', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                            'created' => '2007-03-17 01:18:23', 'updated' => '2007-03-17 01:20:31',
                        ],
                    ],
                ],
            ],
            [
                'Article' => [
                    'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
                    'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31',
                ],
                'Comment' => [],
            ],
        ];
        $this->assertEqual($result, $expected);

        $result = $this->Article->find('all', ['contain' => ['User' => 'ArticleFeatured']]);
        $expected = [
            [
                'Article' => [
                    'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
                    'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                ],
                'User' => [
                    'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31',
                    'ArticleFeatured' => [
                        [
                            'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
                            'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                        ],
                        [
                            'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
                            'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31',
                        ],
                    ],
                ],
            ],
            [
                'Article' => [
                    'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
                    'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31',
                ],
                'User' => [
                    'id' => 3, 'user' => 'larry', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:20:23', 'updated' => '2007-03-17 01:22:31',
                    'ArticleFeatured' => [
                        [
                        'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
                        'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31',
                        ],
                    ],
                ],
            ],
            [
                'Article' => [
                    'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
                    'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31',
                ],
                'User' => [
                    'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31',
                    'ArticleFeatured' => [
                        [
                            'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
                            'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                        ],
                        [
                            'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
                            'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31',
                        ],
                    ],
                ],
            ],
        ];
        $this->assertEqual($result, $expected);

        $result = $this->Article->find('all', ['contain' => ['User' => ['ArticleFeatured', 'Comment']]]);
        $expected = [
            [
                'Article' => [
                    'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
                    'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                ],
                'User' => [
                    'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31',
                    'ArticleFeatured' => [
                        [
                            'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
                            'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                        ],
                        [
                            'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
                            'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31',
                        ],
                    ],
                    'Comment' => [
                        [
                            'id' => 3, 'article_id' => 1, 'user_id' => 1, 'comment' => 'Third Comment for First Article',
                            'published' => 'Y', 'created' => '2007-03-18 10:49:23', 'updated' => '2007-03-18 10:51:31',
                        ],
                        [
                            'id' => 4, 'article_id' => 1, 'user_id' => 1, 'comment' => 'Fourth Comment for First Article',
                            'published' => 'N', 'created' => '2007-03-18 10:51:23', 'updated' => '2007-03-18 10:53:31',
                        ],
                        [
                            'id' => 5, 'article_id' => 2, 'user_id' => 1, 'comment' => 'First Comment for Second Article',
                            'published' => 'Y', 'created' => '2007-03-18 10:53:23', 'updated' => '2007-03-18 10:55:31',
                        ],
                    ],
                ],
            ],
            [
                'Article' => [
                    'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
                    'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31',
                ],
                'User' => [
                    'id' => 3, 'user' => 'larry', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:20:23', 'updated' => '2007-03-17 01:22:31',
                    'ArticleFeatured' => [
                        [
                        'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
                        'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31',
                        ],
                    ],
                    'Comment' => [],
                ],
            ],
            [
                'Article' => [
                    'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
                    'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31',
                ],
                'User' => [
                    'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31',
                    'ArticleFeatured' => [
                        [
                            'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
                            'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                        ],
                        [
                            'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
                            'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31',
                        ],
                    ],
                    'Comment' => [
                        [
                            'id' => 3, 'article_id' => 1, 'user_id' => 1, 'comment' => 'Third Comment for First Article',
                            'published' => 'Y', 'created' => '2007-03-18 10:49:23', 'updated' => '2007-03-18 10:51:31',
                        ],
                        [
                            'id' => 4, 'article_id' => 1, 'user_id' => 1, 'comment' => 'Fourth Comment for First Article',
                            'published' => 'N', 'created' => '2007-03-18 10:51:23', 'updated' => '2007-03-18 10:53:31',
                        ],
                        [
                            'id' => 5, 'article_id' => 2, 'user_id' => 1, 'comment' => 'First Comment for Second Article',
                            'published' => 'Y', 'created' => '2007-03-18 10:53:23', 'updated' => '2007-03-18 10:55:31',
                        ],
                    ],
                ],
            ],
        ];
        $this->assertEqual($result, $expected);

        $result = $this->Article->find('all', ['contain' => ['User' => 'ArticleFeatured', 'Tag', 'Comment' => 'Attachment']]);
        $expected = [
            [
                'Article' => [
                    'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
                    'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                ],
                'User' => [
                    'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31',
                    'ArticleFeatured' => [
                        [
                            'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
                            'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                        ],
                        [
                            'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
                            'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31',
                        ],
                    ],
                ],
                'Comment' => [
                    [
                        'id' => 1, 'article_id' => 1, 'user_id' => 2, 'comment' => 'First Comment for First Article',
                        'published' => 'Y', 'created' => '2007-03-18 10:45:23', 'updated' => '2007-03-18 10:47:31',
                        'Attachment' => [],
                    ],
                    [
                        'id' => 2, 'article_id' => 1, 'user_id' => 4, 'comment' => 'Second Comment for First Article',
                        'published' => 'Y', 'created' => '2007-03-18 10:47:23', 'updated' => '2007-03-18 10:49:31',
                        'Attachment' => [],
                    ],
                    [
                        'id' => 3, 'article_id' => 1, 'user_id' => 1, 'comment' => 'Third Comment for First Article',
                        'published' => 'Y', 'created' => '2007-03-18 10:49:23', 'updated' => '2007-03-18 10:51:31',
                        'Attachment' => [],
                    ],
                    [
                        'id' => 4, 'article_id' => 1, 'user_id' => 1, 'comment' => 'Fourth Comment for First Article',
                        'published' => 'N', 'created' => '2007-03-18 10:51:23', 'updated' => '2007-03-18 10:53:31',
                        'Attachment' => [],
                    ],
                ],
                'Tag' => [
                    ['id' => 1, 'tag' => 'tag1', 'created' => '2007-03-18 12:22:23', 'updated' => '2007-03-18 12:24:31'],
                    ['id' => 2, 'tag' => 'tag2', 'created' => '2007-03-18 12:24:23', 'updated' => '2007-03-18 12:26:31'],
                ],
            ],
            [
                'Article' => [
                    'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
                    'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31',
                ],
                'User' => [
                    'id' => 3, 'user' => 'larry', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:20:23', 'updated' => '2007-03-17 01:22:31',
                    'ArticleFeatured' => [
                        [
                        'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
                        'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31',
                        ],
                    ],
                ],
                'Comment' => [
                    [
                        'id' => 5, 'article_id' => 2, 'user_id' => 1, 'comment' => 'First Comment for Second Article',
                        'published' => 'Y', 'created' => '2007-03-18 10:53:23', 'updated' => '2007-03-18 10:55:31',
                        'Attachment' => [
                            'id' => 1, 'comment_id' => 5, 'attachment' => 'attachment.zip',
                            'created' => '2007-03-18 10:51:23', 'updated' => '2007-03-18 10:53:31',
                        ],
                    ],
                    [
                        'id' => 6, 'article_id' => 2, 'user_id' => 2, 'comment' => 'Second Comment for Second Article',
                        'published' => 'Y', 'created' => '2007-03-18 10:55:23', 'updated' => '2007-03-18 10:57:31',
                        'Attachment' => [],
                    ],
                ],
                'Tag' => [
                    ['id' => 1, 'tag' => 'tag1', 'created' => '2007-03-18 12:22:23', 'updated' => '2007-03-18 12:24:31'],
                    ['id' => 3, 'tag' => 'tag3', 'created' => '2007-03-18 12:26:23', 'updated' => '2007-03-18 12:28:31'],
                ],
            ],
            [
                'Article' => [
                    'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
                    'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31',
                ],
                'User' => [
                    'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31',
                    'ArticleFeatured' => [
                        [
                            'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
                            'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                        ],
                        [
                            'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
                            'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31',
                        ],
                    ],
                ],
                'Comment' => [],
                'Tag' => [],
            ],
        ];
        $this->assertEqual($result, $expected);
    }

    /**
     * testFindThirdLevel method.
     */
    public function testFindThirdLevel()
    {
        $this->User->contain(['ArticleFeatured' => ['Featured' => 'Category']]);
        $result = $this->User->find('all', ['recursive' => 3]);
        $expected = [
            [
                'User' => [
                    'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31',
                ],
                'ArticleFeatured' => [
                    [
                        'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
                        'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                        'Featured' => [
                            'id' => 1, 'article_featured_id' => 1, 'category_id' => 1, 'published_date' => '2007-03-31 10:39:23',
                            'end_date' => '2007-05-15 10:39:23', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                            'Category' => [
                                'id' => 1, 'parent_id' => 0, 'name' => 'Category 1',
                                'created' => '2007-03-18 15:30:23', 'updated' => '2007-03-18 15:32:31',
                            ],
                        ],
                    ],
                    [
                        'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
                        'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31',
                        'Featured' => [],
                    ],
                ],
            ],
            [
                'User' => [
                    'id' => 2, 'user' => 'nate', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:18:23', 'updated' => '2007-03-17 01:20:31',
                ],
                'ArticleFeatured' => [],
            ],
            [
                'User' => [
                    'id' => 3, 'user' => 'larry', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:20:23', 'updated' => '2007-03-17 01:22:31',
                ],
                'ArticleFeatured' => [
                    [
                        'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
                        'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31',
                        'Featured' => [
                            'id' => 2, 'article_featured_id' => 2, 'category_id' => 1, 'published_date' => '2007-03-31 10:39:23',
                            'end_date' => '2007-05-15 10:39:23', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                            'Category' => [
                                'id' => 1, 'parent_id' => 0, 'name' => 'Category 1',
                                'created' => '2007-03-18 15:30:23', 'updated' => '2007-03-18 15:32:31',
                            ],
                        ],
                    ],
                ],
            ],
            [
                'User' => [
                    'id' => 4, 'user' => 'garrett', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:22:23', 'updated' => '2007-03-17 01:24:31',
                ],
                'ArticleFeatured' => [],
            ],
        ];
        $this->assertEqual($result, $expected);

        $this->User->contain(['ArticleFeatured' => ['Featured' => 'Category', 'Comment' => ['Article', 'Attachment']]]);
        $result = $this->User->find('all', ['recursive' => 3]);
        $expected = [
            [
                'User' => [
                    'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31',
                ],
                'ArticleFeatured' => [
                    [
                        'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
                        'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                        'Featured' => [
                            'id' => 1, 'article_featured_id' => 1, 'category_id' => 1, 'published_date' => '2007-03-31 10:39:23',
                            'end_date' => '2007-05-15 10:39:23', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                            'Category' => [
                                'id' => 1, 'parent_id' => 0, 'name' => 'Category 1',
                                'created' => '2007-03-18 15:30:23', 'updated' => '2007-03-18 15:32:31',
                            ],
                        ],
                        'Comment' => [
                            [
                                'id' => 1, 'article_id' => 1, 'user_id' => 2, 'comment' => 'First Comment for First Article',
                                'published' => 'Y', 'created' => '2007-03-18 10:45:23', 'updated' => '2007-03-18 10:47:31',
                                'Article' => [
                                    'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
                                    'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                                ],
                                'Attachment' => [],
                            ],
                            [
                                'id' => 2, 'article_id' => 1, 'user_id' => 4, 'comment' => 'Second Comment for First Article',
                                'published' => 'Y', 'created' => '2007-03-18 10:47:23', 'updated' => '2007-03-18 10:49:31',
                                'Article' => [
                                    'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
                                    'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                                ],
                                'Attachment' => [],
                            ],
                            [
                                'id' => 3, 'article_id' => 1, 'user_id' => 1, 'comment' => 'Third Comment for First Article',
                                'published' => 'Y', 'created' => '2007-03-18 10:49:23', 'updated' => '2007-03-18 10:51:31',
                                'Article' => [
                                    'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
                                    'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                                ],
                                'Attachment' => [],
                            ],
                            [
                                'id' => 4, 'article_id' => 1, 'user_id' => 1, 'comment' => 'Fourth Comment for First Article',
                                'published' => 'N', 'created' => '2007-03-18 10:51:23', 'updated' => '2007-03-18 10:53:31',
                                'Article' => [
                                    'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
                                    'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                                ],
                                'Attachment' => [],
                            ],
                        ],
                    ],
                    [
                        'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
                        'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31',
                        'Featured' => [],
                        'Comment' => [],
                    ],
                ],
            ],
            [
                'User' => [
                    'id' => 2, 'user' => 'nate', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:18:23', 'updated' => '2007-03-17 01:20:31',
                ],
                'ArticleFeatured' => [],
            ],
            [
                'User' => [
                    'id' => 3, 'user' => 'larry', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:20:23', 'updated' => '2007-03-17 01:22:31',
                ],
                'ArticleFeatured' => [
                    [
                        'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
                        'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31',
                        'Featured' => [
                            'id' => 2, 'article_featured_id' => 2, 'category_id' => 1, 'published_date' => '2007-03-31 10:39:23',
                            'end_date' => '2007-05-15 10:39:23', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                            'Category' => [
                                'id' => 1, 'parent_id' => 0, 'name' => 'Category 1',
                                'created' => '2007-03-18 15:30:23', 'updated' => '2007-03-18 15:32:31',
                            ],
                        ],
                        'Comment' => [
                            [
                                'id' => 5, 'article_id' => 2, 'user_id' => 1, 'comment' => 'First Comment for Second Article',
                                'published' => 'Y', 'created' => '2007-03-18 10:53:23', 'updated' => '2007-03-18 10:55:31',
                                'Article' => [
                                    'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
                                    'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31',
                                ],
                                'Attachment' => [
                                    'id' => 1, 'comment_id' => 5, 'attachment' => 'attachment.zip',
                                    'created' => '2007-03-18 10:51:23', 'updated' => '2007-03-18 10:53:31',
                                ],
                            ],
                            [
                                'id' => 6, 'article_id' => 2, 'user_id' => 2, 'comment' => 'Second Comment for Second Article',
                                'published' => 'Y', 'created' => '2007-03-18 10:55:23', 'updated' => '2007-03-18 10:57:31',
                                'Article' => [
                                    'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
                                    'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31',
                                ],
                                'Attachment' => [],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'User' => [
                    'id' => 4, 'user' => 'garrett', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:22:23', 'updated' => '2007-03-17 01:24:31',
                ],
                'ArticleFeatured' => [],
            ],
        ];
        $this->assertEqual($result, $expected);

        $this->User->contain(['ArticleFeatured' => ['Featured' => 'Category', 'Comment' => 'Attachment'], 'Article']);
        $result = $this->User->find('all', ['recursive' => 3]);
        $expected = [
            [
                'User' => [
                    'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31',
                ],
                'Article' => [
                    [
                        'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
                        'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                    ],
                    [
                        'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
                        'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31',
                    ],
                ],
                'ArticleFeatured' => [
                    [
                        'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
                        'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                        'Featured' => [
                            'id' => 1, 'article_featured_id' => 1, 'category_id' => 1, 'published_date' => '2007-03-31 10:39:23',
                            'end_date' => '2007-05-15 10:39:23', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                            'Category' => [
                                'id' => 1, 'parent_id' => 0, 'name' => 'Category 1',
                                'created' => '2007-03-18 15:30:23', 'updated' => '2007-03-18 15:32:31',
                            ],
                        ],
                        'Comment' => [
                            [
                                'id' => 1, 'article_id' => 1, 'user_id' => 2, 'comment' => 'First Comment for First Article',
                                'published' => 'Y', 'created' => '2007-03-18 10:45:23', 'updated' => '2007-03-18 10:47:31',
                                'Attachment' => [],
                            ],
                            [
                                'id' => 2, 'article_id' => 1, 'user_id' => 4, 'comment' => 'Second Comment for First Article',
                                'published' => 'Y', 'created' => '2007-03-18 10:47:23', 'updated' => '2007-03-18 10:49:31',
                                'Attachment' => [],
                            ],
                            [
                                'id' => 3, 'article_id' => 1, 'user_id' => 1, 'comment' => 'Third Comment for First Article',
                                'published' => 'Y', 'created' => '2007-03-18 10:49:23', 'updated' => '2007-03-18 10:51:31',
                                'Attachment' => [],
                            ],
                            [
                                'id' => 4, 'article_id' => 1, 'user_id' => 1, 'comment' => 'Fourth Comment for First Article',
                                'published' => 'N', 'created' => '2007-03-18 10:51:23', 'updated' => '2007-03-18 10:53:31',
                                'Attachment' => [],
                            ],
                        ],
                    ],
                    [
                        'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
                        'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31',
                        'Featured' => [],
                        'Comment' => [],
                    ],
                ],
            ],
            [
                'User' => [
                    'id' => 2, 'user' => 'nate', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:18:23', 'updated' => '2007-03-17 01:20:31',
                ],
                'Article' => [],
                'ArticleFeatured' => [],
            ],
            [
                'User' => [
                    'id' => 3, 'user' => 'larry', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:20:23', 'updated' => '2007-03-17 01:22:31',
                ],
                'Article' => [
                    [
                        'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
                        'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31',
                    ],
                ],
                'ArticleFeatured' => [
                    [
                        'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
                        'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31',
                        'Featured' => [
                            'id' => 2, 'article_featured_id' => 2, 'category_id' => 1, 'published_date' => '2007-03-31 10:39:23',
                            'end_date' => '2007-05-15 10:39:23', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                            'Category' => [
                                'id' => 1, 'parent_id' => 0, 'name' => 'Category 1',
                                'created' => '2007-03-18 15:30:23', 'updated' => '2007-03-18 15:32:31',
                            ],
                        ],
                        'Comment' => [
                            [
                                'id' => 5, 'article_id' => 2, 'user_id' => 1, 'comment' => 'First Comment for Second Article',
                                'published' => 'Y', 'created' => '2007-03-18 10:53:23', 'updated' => '2007-03-18 10:55:31',
                                'Attachment' => [
                                    'id' => 1, 'comment_id' => 5, 'attachment' => 'attachment.zip',
                                    'created' => '2007-03-18 10:51:23', 'updated' => '2007-03-18 10:53:31',
                                ],
                            ],
                            [
                                'id' => 6, 'article_id' => 2, 'user_id' => 2, 'comment' => 'Second Comment for Second Article',
                                'published' => 'Y', 'created' => '2007-03-18 10:55:23', 'updated' => '2007-03-18 10:57:31',
                                'Attachment' => [],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'User' => [
                    'id' => 4, 'user' => 'garrett', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:22:23', 'updated' => '2007-03-17 01:24:31',
                ],
                'Article' => [],
                'ArticleFeatured' => [],
            ],
        ];
        $this->assertEqual($result, $expected);
    }

    /**
     * testFindEmbeddedThirdLevel method.
     */
    public function testFindEmbeddedThirdLevel()
    {
        $result = $this->User->find('all', ['contain' => ['ArticleFeatured' => ['Featured' => 'Category']]]);
        $expected = [
            [
                'User' => [
                    'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31',
                ],
                'ArticleFeatured' => [
                    [
                        'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
                        'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                        'Featured' => [
                            'id' => 1, 'article_featured_id' => 1, 'category_id' => 1, 'published_date' => '2007-03-31 10:39:23',
                            'end_date' => '2007-05-15 10:39:23', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                            'Category' => [
                                'id' => 1, 'parent_id' => 0, 'name' => 'Category 1',
                                'created' => '2007-03-18 15:30:23', 'updated' => '2007-03-18 15:32:31',
                            ],
                        ],
                    ],
                    [
                        'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
                        'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31',
                        'Featured' => [],
                    ],
                ],
            ],
            [
                'User' => [
                    'id' => 2, 'user' => 'nate', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:18:23', 'updated' => '2007-03-17 01:20:31',
                ],
                'ArticleFeatured' => [],
            ],
            [
                'User' => [
                    'id' => 3, 'user' => 'larry', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:20:23', 'updated' => '2007-03-17 01:22:31',
                ],
                'ArticleFeatured' => [
                    [
                        'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
                        'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31',
                        'Featured' => [
                            'id' => 2, 'article_featured_id' => 2, 'category_id' => 1, 'published_date' => '2007-03-31 10:39:23',
                            'end_date' => '2007-05-15 10:39:23', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                            'Category' => [
                                'id' => 1, 'parent_id' => 0, 'name' => 'Category 1',
                                'created' => '2007-03-18 15:30:23', 'updated' => '2007-03-18 15:32:31',
                            ],
                        ],
                    ],
                ],
            ],
            [
                'User' => [
                    'id' => 4, 'user' => 'garrett', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:22:23', 'updated' => '2007-03-17 01:24:31',
                ],
                'ArticleFeatured' => [],
            ],
        ];
        $this->assertEqual($result, $expected);

        $result = $this->User->find('all', ['contain' => ['ArticleFeatured' => ['Featured' => 'Category', 'Comment' => ['Article', 'Attachment']]]]);
        $expected = [
            [
                'User' => [
                    'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31',
                ],
                'ArticleFeatured' => [
                    [
                        'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
                        'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                        'Featured' => [
                            'id' => 1, 'article_featured_id' => 1, 'category_id' => 1, 'published_date' => '2007-03-31 10:39:23',
                            'end_date' => '2007-05-15 10:39:23', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                            'Category' => [
                                'id' => 1, 'parent_id' => 0, 'name' => 'Category 1',
                                'created' => '2007-03-18 15:30:23', 'updated' => '2007-03-18 15:32:31',
                            ],
                        ],
                        'Comment' => [
                            [
                                'id' => 1, 'article_id' => 1, 'user_id' => 2, 'comment' => 'First Comment for First Article',
                                'published' => 'Y', 'created' => '2007-03-18 10:45:23', 'updated' => '2007-03-18 10:47:31',
                                'Article' => [
                                    'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
                                    'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                                ],
                                'Attachment' => [],
                            ],
                            [
                                'id' => 2, 'article_id' => 1, 'user_id' => 4, 'comment' => 'Second Comment for First Article',
                                'published' => 'Y', 'created' => '2007-03-18 10:47:23', 'updated' => '2007-03-18 10:49:31',
                                'Article' => [
                                    'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
                                    'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                                ],
                                'Attachment' => [],
                            ],
                            [
                                'id' => 3, 'article_id' => 1, 'user_id' => 1, 'comment' => 'Third Comment for First Article',
                                'published' => 'Y', 'created' => '2007-03-18 10:49:23', 'updated' => '2007-03-18 10:51:31',
                                'Article' => [
                                    'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
                                    'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                                ],
                                'Attachment' => [],
                            ],
                            [
                                'id' => 4, 'article_id' => 1, 'user_id' => 1, 'comment' => 'Fourth Comment for First Article',
                                'published' => 'N', 'created' => '2007-03-18 10:51:23', 'updated' => '2007-03-18 10:53:31',
                                'Article' => [
                                    'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
                                    'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                                ],
                                'Attachment' => [],
                            ],
                        ],
                    ],
                    [
                        'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
                        'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31',
                        'Featured' => [],
                        'Comment' => [],
                    ],
                ],
            ],
            [
                'User' => [
                    'id' => 2, 'user' => 'nate', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:18:23', 'updated' => '2007-03-17 01:20:31',
                ],
                'ArticleFeatured' => [],
            ],
            [
                'User' => [
                    'id' => 3, 'user' => 'larry', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:20:23', 'updated' => '2007-03-17 01:22:31',
                ],
                'ArticleFeatured' => [
                    [
                        'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
                        'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31',
                        'Featured' => [
                            'id' => 2, 'article_featured_id' => 2, 'category_id' => 1, 'published_date' => '2007-03-31 10:39:23',
                            'end_date' => '2007-05-15 10:39:23', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                            'Category' => [
                                'id' => 1, 'parent_id' => 0, 'name' => 'Category 1',
                                'created' => '2007-03-18 15:30:23', 'updated' => '2007-03-18 15:32:31',
                            ],
                        ],
                        'Comment' => [
                            [
                                'id' => 5, 'article_id' => 2, 'user_id' => 1, 'comment' => 'First Comment for Second Article',
                                'published' => 'Y', 'created' => '2007-03-18 10:53:23', 'updated' => '2007-03-18 10:55:31',
                                'Article' => [
                                    'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
                                    'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31',
                                ],
                                'Attachment' => [
                                    'id' => 1, 'comment_id' => 5, 'attachment' => 'attachment.zip',
                                    'created' => '2007-03-18 10:51:23', 'updated' => '2007-03-18 10:53:31',
                                ],
                            ],
                            [
                                'id' => 6, 'article_id' => 2, 'user_id' => 2, 'comment' => 'Second Comment for Second Article',
                                'published' => 'Y', 'created' => '2007-03-18 10:55:23', 'updated' => '2007-03-18 10:57:31',
                                'Article' => [
                                    'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
                                    'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31',
                                ],
                                'Attachment' => [],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'User' => [
                    'id' => 4, 'user' => 'garrett', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:22:23', 'updated' => '2007-03-17 01:24:31',
                ],
                'ArticleFeatured' => [],
            ],
        ];
        $this->assertEqual($result, $expected);

        $result = $this->User->find('all', ['contain' => ['ArticleFeatured' => ['Featured' => 'Category', 'Comment' => 'Attachment'], 'Article']]);
        $expected = [
            [
                'User' => [
                    'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31',
                ],
                'Article' => [
                    [
                        'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
                        'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                    ],
                    [
                        'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
                        'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31',
                    ],
                ],
                'ArticleFeatured' => [
                    [
                        'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
                        'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                        'Featured' => [
                            'id' => 1, 'article_featured_id' => 1, 'category_id' => 1, 'published_date' => '2007-03-31 10:39:23',
                            'end_date' => '2007-05-15 10:39:23', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                            'Category' => [
                                'id' => 1, 'parent_id' => 0, 'name' => 'Category 1',
                                'created' => '2007-03-18 15:30:23', 'updated' => '2007-03-18 15:32:31',
                            ],
                        ],
                        'Comment' => [
                            [
                                'id' => 1, 'article_id' => 1, 'user_id' => 2, 'comment' => 'First Comment for First Article',
                                'published' => 'Y', 'created' => '2007-03-18 10:45:23', 'updated' => '2007-03-18 10:47:31',
                                'Attachment' => [],
                            ],
                            [
                                'id' => 2, 'article_id' => 1, 'user_id' => 4, 'comment' => 'Second Comment for First Article',
                                'published' => 'Y', 'created' => '2007-03-18 10:47:23', 'updated' => '2007-03-18 10:49:31',
                                'Attachment' => [],
                            ],
                            [
                                'id' => 3, 'article_id' => 1, 'user_id' => 1, 'comment' => 'Third Comment for First Article',
                                'published' => 'Y', 'created' => '2007-03-18 10:49:23', 'updated' => '2007-03-18 10:51:31',
                                'Attachment' => [],
                            ],
                            [
                                'id' => 4, 'article_id' => 1, 'user_id' => 1, 'comment' => 'Fourth Comment for First Article',
                                'published' => 'N', 'created' => '2007-03-18 10:51:23', 'updated' => '2007-03-18 10:53:31',
                                'Attachment' => [],
                            ],
                        ],
                    ],
                    [
                        'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
                        'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31',
                        'Featured' => [],
                        'Comment' => [],
                    ],
                ],
            ],
            [
                'User' => [
                    'id' => 2, 'user' => 'nate', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:18:23', 'updated' => '2007-03-17 01:20:31',
                ],
                'Article' => [],
                'ArticleFeatured' => [],
            ],
            [
                'User' => [
                    'id' => 3, 'user' => 'larry', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:20:23', 'updated' => '2007-03-17 01:22:31',
                ],
                'Article' => [
                    [
                        'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
                        'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31',
                    ],
                ],
                'ArticleFeatured' => [
                    [
                        'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
                        'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31',
                        'Featured' => [
                            'id' => 2, 'article_featured_id' => 2, 'category_id' => 1, 'published_date' => '2007-03-31 10:39:23',
                            'end_date' => '2007-05-15 10:39:23', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                            'Category' => [
                                'id' => 1, 'parent_id' => 0, 'name' => 'Category 1',
                                'created' => '2007-03-18 15:30:23', 'updated' => '2007-03-18 15:32:31',
                            ],
                        ],
                        'Comment' => [
                            [
                                'id' => 5, 'article_id' => 2, 'user_id' => 1, 'comment' => 'First Comment for Second Article',
                                'published' => 'Y', 'created' => '2007-03-18 10:53:23', 'updated' => '2007-03-18 10:55:31',
                                'Attachment' => [
                                    'id' => 1, 'comment_id' => 5, 'attachment' => 'attachment.zip',
                                    'created' => '2007-03-18 10:51:23', 'updated' => '2007-03-18 10:53:31',
                                ],
                            ],
                            [
                                'id' => 6, 'article_id' => 2, 'user_id' => 2, 'comment' => 'Second Comment for Second Article',
                                'published' => 'Y', 'created' => '2007-03-18 10:55:23', 'updated' => '2007-03-18 10:57:31',
                                'Attachment' => [],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'User' => [
                    'id' => 4, 'user' => 'garrett', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:22:23', 'updated' => '2007-03-17 01:24:31',
                ],
                'Article' => [],
                'ArticleFeatured' => [],
            ],
        ];
        $this->assertEqual($result, $expected);
    }

    /**
     * testSettingsThirdLevel method.
     */
    public function testSettingsThirdLevel()
    {
        $result = $this->User->find('all', ['contain' => ['ArticleFeatured' => ['Featured' => ['Category' => ['id', 'name']]]]]);
        $expected = [
            [
                'User' => [
                    'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31',
                ],
                'ArticleFeatured' => [
                    [
                        'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
                        'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                        'Featured' => [
                            'id' => 1, 'article_featured_id' => 1, 'category_id' => 1, 'published_date' => '2007-03-31 10:39:23',
                            'end_date' => '2007-05-15 10:39:23', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                            'Category' => [
                                'id' => 1, 'name' => 'Category 1',
                            ],
                        ],
                    ],
                    [
                        'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
                        'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31',
                        'Featured' => [],
                    ],
                ],
            ],
            [
                'User' => [
                    'id' => 2, 'user' => 'nate', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:18:23', 'updated' => '2007-03-17 01:20:31',
                ],
                'ArticleFeatured' => [],
            ],
            [
                'User' => [
                    'id' => 3, 'user' => 'larry', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:20:23', 'updated' => '2007-03-17 01:22:31',
                ],
                'ArticleFeatured' => [
                    [
                        'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
                        'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31',
                        'Featured' => [
                            'id' => 2, 'article_featured_id' => 2, 'category_id' => 1, 'published_date' => '2007-03-31 10:39:23',
                            'end_date' => '2007-05-15 10:39:23', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                            'Category' => [
                                'id' => 1, 'name' => 'Category 1',
                            ],
                        ],
                    ],
                ],
            ],
            [
                'User' => [
                    'id' => 4, 'user' => 'garrett', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:22:23', 'updated' => '2007-03-17 01:24:31',
                ],
                'ArticleFeatured' => [],
            ],
        ];
        $this->assertEqual($result, $expected);

        $r = $this->User->find('all', ['contain' => [
            'ArticleFeatured' => [
                'id', 'title',
                'Featured' => [
                    'id', 'category_id',
                    'Category' => ['id', 'name'],
                ],
            ],
        ]]);

        $this->assertTrue(Set::matches('/User[id=1]', $r));
        $this->assertFalse(Set::matches('/Article', $r) || Set::matches('/Comment', $r));
        $this->assertTrue(Set::matches('/ArticleFeatured', $r));
        $this->assertFalse(Set::matches('/ArticleFeatured/User', $r) || Set::matches('/ArticleFeatured/Comment', $r) || Set::matches('/ArticleFeatured/Tag', $r));
        $this->assertTrue(Set::matches('/ArticleFeatured/Featured', $r));
        $this->assertFalse(Set::matches('/ArticleFeatured/Featured/ArticleFeatured', $r));
        $this->assertTrue(Set::matches('/ArticleFeatured/Featured/Category', $r));
        $this->assertTrue(Set::matches('/ArticleFeatured/Featured[id=1]', $r));
        $this->assertTrue(Set::matches('/ArticleFeatured/Featured[id=1]/Category[id=1]', $r));
        $this->assertTrue(Set::matches('/ArticleFeatured/Featured[id=1]/Category[name=Category 1]', $r));

        $r = $this->User->find('all', ['contain' => [
            'ArticleFeatured' => [
                'title',
                'Featured' => [
                    'id',
                    'Category' => 'name',
                ],
            ],
        ]]);

        $this->assertTrue(Set::matches('/User[id=1]', $r));
        $this->assertFalse(Set::matches('/Article', $r) || Set::matches('/Comment', $r));
        $this->assertTrue(Set::matches('/ArticleFeatured', $r));
        $this->assertFalse(Set::matches('/ArticleFeatured/User', $r) || Set::matches('/ArticleFeatured/Comment', $r) || Set::matches('/ArticleFeatured/Tag', $r));
        $this->assertTrue(Set::matches('/ArticleFeatured/Featured', $r));
        $this->assertFalse(Set::matches('/ArticleFeatured/Featured/ArticleFeatured', $r));
        $this->assertTrue(Set::matches('/ArticleFeatured/Featured/Category', $r));
        $this->assertTrue(Set::matches('/ArticleFeatured/Featured[id=1]', $r));
        $this->assertTrue(Set::matches('/ArticleFeatured/Featured[id=1]/Category[name=Category 1]', $r));

        $result = $this->User->find('all', ['contain' => [
            'ArticleFeatured' => [
                'title',
                'Featured' => [
                    'category_id',
                    'Category' => 'name',
                ],
            ],
        ]]);
        $expected = [
            [
                'User' => [
                    'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31',
                ],
                'ArticleFeatured' => [
                    [
                        'title' => 'First Article', 'id' => 1, 'user_id' => 1,
                        'Featured' => [
                            'category_id' => 1, 'id' => 1,
                            'Category' => [
                                'name' => 'Category 1',
                            ],
                        ],
                    ],
                    [
                        'title' => 'Third Article', 'id' => 3, 'user_id' => 1,
                        'Featured' => [],
                    ],
                ],
            ],
            [
                'User' => [
                    'id' => 2, 'user' => 'nate', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:18:23', 'updated' => '2007-03-17 01:20:31',
                ],
                'ArticleFeatured' => [],
            ],
            [
                'User' => [
                    'id' => 3, 'user' => 'larry', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:20:23', 'updated' => '2007-03-17 01:22:31',
                ],
                'ArticleFeatured' => [
                    [
                        'title' => 'Second Article', 'id' => 2, 'user_id' => 3,
                        'Featured' => [
                            'category_id' => 1, 'id' => 2,
                            'Category' => [
                                'name' => 'Category 1',
                            ],
                        ],
                    ],
                ],
            ],
            [
                'User' => [
                    'id' => 4, 'user' => 'garrett', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:22:23', 'updated' => '2007-03-17 01:24:31',
                ],
                'ArticleFeatured' => [],
            ],
        ];
        $this->assertEqual($result, $expected);

        $orders = [
            'title DESC', 'title DESC, published DESC',
            ['title' => 'DESC'], ['title' => 'DESC', 'published' => 'DESC'],
        ];
        foreach ($orders as $order) {
            $result = $this->User->find('all', ['contain' => [
                'ArticleFeatured' => [
                    'title', 'order' => $order,
                    'Featured' => [
                        'category_id',
                        'Category' => 'name',
                    ],
                ],
            ]]);
            $expected = [
                [
                    'User' => [
                        'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                        'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31',
                    ],
                    'ArticleFeatured' => [
                        [
                            'title' => 'Third Article', 'id' => 3, 'user_id' => 1,
                            'Featured' => [],
                        ],
                        [
                            'title' => 'First Article', 'id' => 1, 'user_id' => 1,
                            'Featured' => [
                                'category_id' => 1, 'id' => 1,
                                'Category' => [
                                    'name' => 'Category 1',
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'User' => [
                        'id' => 2, 'user' => 'nate', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                        'created' => '2007-03-17 01:18:23', 'updated' => '2007-03-17 01:20:31',
                    ],
                    'ArticleFeatured' => [],
                ],
                [
                    'User' => [
                        'id' => 3, 'user' => 'larry', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                        'created' => '2007-03-17 01:20:23', 'updated' => '2007-03-17 01:22:31',
                    ],
                    'ArticleFeatured' => [
                        [
                            'title' => 'Second Article', 'id' => 2, 'user_id' => 3,
                            'Featured' => [
                                'category_id' => 1, 'id' => 2,
                                'Category' => [
                                    'name' => 'Category 1',
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'User' => [
                        'id' => 4, 'user' => 'garrett', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                        'created' => '2007-03-17 01:22:23', 'updated' => '2007-03-17 01:24:31',
                    ],
                    'ArticleFeatured' => [],
                ],
            ];
            $this->assertEqual($result, $expected);
        }
    }

    /**
     * testFindThirdLevelNonReset method.
     */
    public function testFindThirdLevelNonReset()
    {
        $this->User->contain(false, ['ArticleFeatured' => ['Featured' => 'Category']]);
        $result = $this->User->find('all', ['recursive' => 3]);
        $expected = [
            [
                'User' => [
                    'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31',
                ],
                'ArticleFeatured' => [
                    [
                        'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
                        'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                        'Featured' => [
                            'id' => 1, 'article_featured_id' => 1, 'category_id' => 1, 'published_date' => '2007-03-31 10:39:23',
                            'end_date' => '2007-05-15 10:39:23', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                            'Category' => [
                                'id' => 1, 'parent_id' => 0, 'name' => 'Category 1',
                                'created' => '2007-03-18 15:30:23', 'updated' => '2007-03-18 15:32:31',
                            ],
                        ],
                    ],
                    [
                        'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
                        'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31',
                        'Featured' => [],
                    ],
                ],
            ],
            [
                'User' => [
                    'id' => 2, 'user' => 'nate', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:18:23', 'updated' => '2007-03-17 01:20:31',
                ],
                'ArticleFeatured' => [],
            ],
            [
                'User' => [
                    'id' => 3, 'user' => 'larry', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:20:23', 'updated' => '2007-03-17 01:22:31',
                ],
                'ArticleFeatured' => [
                    [
                        'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
                        'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31',
                        'Featured' => [
                            'id' => 2, 'article_featured_id' => 2, 'category_id' => 1, 'published_date' => '2007-03-31 10:39:23',
                            'end_date' => '2007-05-15 10:39:23', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                            'Category' => [
                                'id' => 1, 'parent_id' => 0, 'name' => 'Category 1',
                                'created' => '2007-03-18 15:30:23', 'updated' => '2007-03-18 15:32:31',
                            ],
                        ],
                    ],
                ],
            ],
            [
                'User' => [
                    'id' => 4, 'user' => 'garrett', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:22:23', 'updated' => '2007-03-17 01:24:31',
                ],
                'ArticleFeatured' => [],
            ],
        ];
        $this->assertEqual($result, $expected);

        $this->User->resetBindings();

        $this->User->contain(false, ['ArticleFeatured' => ['Featured' => 'Category', 'Comment' => ['Article', 'Attachment']]]);
        $result = $this->User->find('all', ['recursive' => 3]);
        $expected = [
            [
                'User' => [
                    'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31',
                ],
                'ArticleFeatured' => [
                    [
                        'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
                        'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                        'Featured' => [
                            'id' => 1, 'article_featured_id' => 1, 'category_id' => 1, 'published_date' => '2007-03-31 10:39:23',
                            'end_date' => '2007-05-15 10:39:23', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                            'Category' => [
                                'id' => 1, 'parent_id' => 0, 'name' => 'Category 1',
                                'created' => '2007-03-18 15:30:23', 'updated' => '2007-03-18 15:32:31',
                            ],
                        ],
                        'Comment' => [
                            [
                                'id' => 1, 'article_id' => 1, 'user_id' => 2, 'comment' => 'First Comment for First Article',
                                'published' => 'Y', 'created' => '2007-03-18 10:45:23', 'updated' => '2007-03-18 10:47:31',
                                'Article' => [
                                    'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
                                    'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                                ],
                                'Attachment' => [],
                            ],
                            [
                                'id' => 2, 'article_id' => 1, 'user_id' => 4, 'comment' => 'Second Comment for First Article',
                                'published' => 'Y', 'created' => '2007-03-18 10:47:23', 'updated' => '2007-03-18 10:49:31',
                                'Article' => [
                                    'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
                                    'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                                ],
                                'Attachment' => [],
                            ],
                            [
                                'id' => 3, 'article_id' => 1, 'user_id' => 1, 'comment' => 'Third Comment for First Article',
                                'published' => 'Y', 'created' => '2007-03-18 10:49:23', 'updated' => '2007-03-18 10:51:31',
                                'Article' => [
                                    'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
                                    'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                                ],
                                'Attachment' => [],
                            ],
                            [
                                'id' => 4, 'article_id' => 1, 'user_id' => 1, 'comment' => 'Fourth Comment for First Article',
                                'published' => 'N', 'created' => '2007-03-18 10:51:23', 'updated' => '2007-03-18 10:53:31',
                                'Article' => [
                                    'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
                                    'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                                ],
                                'Attachment' => [],
                            ],
                        ],
                    ],
                    [
                        'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
                        'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31',
                        'Featured' => [],
                        'Comment' => [],
                    ],
                ],
            ],
            [
                'User' => [
                    'id' => 2, 'user' => 'nate', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:18:23', 'updated' => '2007-03-17 01:20:31',
                ],
                'ArticleFeatured' => [],
            ],
            [
                'User' => [
                    'id' => 3, 'user' => 'larry', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:20:23', 'updated' => '2007-03-17 01:22:31',
                ],
                'ArticleFeatured' => [
                    [
                        'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
                        'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31',
                        'Featured' => [
                            'id' => 2, 'article_featured_id' => 2, 'category_id' => 1, 'published_date' => '2007-03-31 10:39:23',
                            'end_date' => '2007-05-15 10:39:23', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                            'Category' => [
                                'id' => 1, 'parent_id' => 0, 'name' => 'Category 1',
                                'created' => '2007-03-18 15:30:23', 'updated' => '2007-03-18 15:32:31',
                            ],
                        ],
                        'Comment' => [
                            [
                                'id' => 5, 'article_id' => 2, 'user_id' => 1, 'comment' => 'First Comment for Second Article',
                                'published' => 'Y', 'created' => '2007-03-18 10:53:23', 'updated' => '2007-03-18 10:55:31',
                                'Article' => [
                                    'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
                                    'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31',
                                ],
                                'Attachment' => [
                                    'id' => 1, 'comment_id' => 5, 'attachment' => 'attachment.zip',
                                    'created' => '2007-03-18 10:51:23', 'updated' => '2007-03-18 10:53:31',
                                ],
                            ],
                            [
                                'id' => 6, 'article_id' => 2, 'user_id' => 2, 'comment' => 'Second Comment for Second Article',
                                'published' => 'Y', 'created' => '2007-03-18 10:55:23', 'updated' => '2007-03-18 10:57:31',
                                'Article' => [
                                    'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
                                    'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31',
                                ],
                                'Attachment' => [],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'User' => [
                    'id' => 4, 'user' => 'garrett', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:22:23', 'updated' => '2007-03-17 01:24:31',
                ],
                'ArticleFeatured' => [],
            ],
        ];
        $this->assertEqual($result, $expected);

        $this->User->resetBindings();

        $this->User->contain(false, ['ArticleFeatured' => ['Featured' => 'Category', 'Comment' => 'Attachment'], 'Article']);
        $result = $this->User->find('all', ['recursive' => 3]);
        $expected = [
            [
                'User' => [
                    'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31',
                ],
                'Article' => [
                    [
                        'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
                        'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                    ],
                    [
                        'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
                        'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31',
                    ],
                ],
                'ArticleFeatured' => [
                    [
                        'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
                        'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                        'Featured' => [
                            'id' => 1, 'article_featured_id' => 1, 'category_id' => 1, 'published_date' => '2007-03-31 10:39:23',
                            'end_date' => '2007-05-15 10:39:23', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                            'Category' => [
                                'id' => 1, 'parent_id' => 0, 'name' => 'Category 1',
                                'created' => '2007-03-18 15:30:23', 'updated' => '2007-03-18 15:32:31',
                            ],
                        ],
                        'Comment' => [
                            [
                                'id' => 1, 'article_id' => 1, 'user_id' => 2, 'comment' => 'First Comment for First Article',
                                'published' => 'Y', 'created' => '2007-03-18 10:45:23', 'updated' => '2007-03-18 10:47:31',
                                'Attachment' => [],
                            ],
                            [
                                'id' => 2, 'article_id' => 1, 'user_id' => 4, 'comment' => 'Second Comment for First Article',
                                'published' => 'Y', 'created' => '2007-03-18 10:47:23', 'updated' => '2007-03-18 10:49:31',
                                'Attachment' => [],
                            ],
                            [
                                'id' => 3, 'article_id' => 1, 'user_id' => 1, 'comment' => 'Third Comment for First Article',
                                'published' => 'Y', 'created' => '2007-03-18 10:49:23', 'updated' => '2007-03-18 10:51:31',
                                'Attachment' => [],
                            ],
                            [
                                'id' => 4, 'article_id' => 1, 'user_id' => 1, 'comment' => 'Fourth Comment for First Article',
                                'published' => 'N', 'created' => '2007-03-18 10:51:23', 'updated' => '2007-03-18 10:53:31',
                                'Attachment' => [],
                            ],
                        ],
                    ],
                    [
                        'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
                        'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31',
                        'Featured' => [],
                        'Comment' => [],
                    ],
                ],
            ],
            [
                'User' => [
                    'id' => 2, 'user' => 'nate', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:18:23', 'updated' => '2007-03-17 01:20:31',
                ],
                'Article' => [],
                'ArticleFeatured' => [],
            ],
            [
                'User' => [
                    'id' => 3, 'user' => 'larry', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:20:23', 'updated' => '2007-03-17 01:22:31',
                ],
                'Article' => [
                    [
                        'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
                        'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31',
                    ],
                ],
                'ArticleFeatured' => [
                    [
                        'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
                        'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31',
                        'Featured' => [
                            'id' => 2, 'article_featured_id' => 2, 'category_id' => 1, 'published_date' => '2007-03-31 10:39:23',
                            'end_date' => '2007-05-15 10:39:23', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                            'Category' => [
                                'id' => 1, 'parent_id' => 0, 'name' => 'Category 1',
                                'created' => '2007-03-18 15:30:23', 'updated' => '2007-03-18 15:32:31',
                            ],
                        ],
                        'Comment' => [
                            [
                                'id' => 5, 'article_id' => 2, 'user_id' => 1, 'comment' => 'First Comment for Second Article',
                                'published' => 'Y', 'created' => '2007-03-18 10:53:23', 'updated' => '2007-03-18 10:55:31',
                                'Attachment' => [
                                    'id' => 1, 'comment_id' => 5, 'attachment' => 'attachment.zip',
                                    'created' => '2007-03-18 10:51:23', 'updated' => '2007-03-18 10:53:31',
                                ],
                            ],
                            [
                                'id' => 6, 'article_id' => 2, 'user_id' => 2, 'comment' => 'Second Comment for Second Article',
                                'published' => 'Y', 'created' => '2007-03-18 10:55:23', 'updated' => '2007-03-18 10:57:31',
                                'Attachment' => [],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'User' => [
                    'id' => 4, 'user' => 'garrett', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:22:23', 'updated' => '2007-03-17 01:24:31',
                ],
                'Article' => [],
                'ArticleFeatured' => [],
            ],
        ];
        $this->assertEqual($result, $expected);
    }

    /**
     * testFindEmbeddedThirdLevelNonReset method.
     */
    public function testFindEmbeddedThirdLevelNonReset()
    {
        $result = $this->User->find('all', ['reset' => false, 'contain' => ['ArticleFeatured' => ['Featured' => 'Category']]]);
        $expected = [
            [
                'User' => [
                    'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31',
                ],
                'ArticleFeatured' => [
                    [
                        'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
                        'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                        'Featured' => [
                            'id' => 1, 'article_featured_id' => 1, 'category_id' => 1, 'published_date' => '2007-03-31 10:39:23',
                            'end_date' => '2007-05-15 10:39:23', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                            'Category' => [
                                'id' => 1, 'parent_id' => 0, 'name' => 'Category 1',
                                'created' => '2007-03-18 15:30:23', 'updated' => '2007-03-18 15:32:31',
                            ],
                        ],
                    ],
                    [
                        'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
                        'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31',
                        'Featured' => [],
                    ],
                ],
            ],
            [
                'User' => [
                    'id' => 2, 'user' => 'nate', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:18:23', 'updated' => '2007-03-17 01:20:31',
                ],
                'ArticleFeatured' => [],
            ],
            [
                'User' => [
                    'id' => 3, 'user' => 'larry', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:20:23', 'updated' => '2007-03-17 01:22:31',
                ],
                'ArticleFeatured' => [
                    [
                        'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
                        'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31',
                        'Featured' => [
                            'id' => 2, 'article_featured_id' => 2, 'category_id' => 1, 'published_date' => '2007-03-31 10:39:23',
                            'end_date' => '2007-05-15 10:39:23', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                            'Category' => [
                                'id' => 1, 'parent_id' => 0, 'name' => 'Category 1',
                                'created' => '2007-03-18 15:30:23', 'updated' => '2007-03-18 15:32:31',
                            ],
                        ],
                    ],
                ],
            ],
            [
                'User' => [
                    'id' => 4, 'user' => 'garrett', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:22:23', 'updated' => '2007-03-17 01:24:31',
                ],
                'ArticleFeatured' => [],
            ],
        ];
        $this->assertEqual($result, $expected);

        $this->__assertBindings($this->User, ['hasMany' => ['ArticleFeatured']]);
        $this->__assertBindings($this->User->ArticleFeatured, ['hasOne' => ['Featured']]);
        $this->__assertBindings($this->User->ArticleFeatured->Featured, ['belongsTo' => ['Category']]);

        $this->User->resetBindings();

        $this->__assertBindings($this->User, ['hasMany' => ['Article', 'ArticleFeatured', 'Comment']]);
        $this->__assertBindings($this->User->ArticleFeatured, ['belongsTo' => ['User'], 'hasOne' => ['Featured'], 'hasMany' => ['Comment'], 'hasAndBelongsToMany' => ['Tag']]);
        $this->__assertBindings($this->User->ArticleFeatured->Featured, ['belongsTo' => ['ArticleFeatured', 'Category']]);
        $this->__assertBindings($this->User->ArticleFeatured->Comment, ['belongsTo' => ['Article', 'User'], 'hasOne' => ['Attachment']]);

        $result = $this->User->find('all', ['reset' => false, 'contain' => ['ArticleFeatured' => ['Featured' => 'Category', 'Comment' => ['Article', 'Attachment']]]]);
        $expected = [
            [
                'User' => [
                    'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31',
                ],
                'ArticleFeatured' => [
                    [
                        'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
                        'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                        'Featured' => [
                            'id' => 1, 'article_featured_id' => 1, 'category_id' => 1, 'published_date' => '2007-03-31 10:39:23',
                            'end_date' => '2007-05-15 10:39:23', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                            'Category' => [
                                'id' => 1, 'parent_id' => 0, 'name' => 'Category 1',
                                'created' => '2007-03-18 15:30:23', 'updated' => '2007-03-18 15:32:31',
                            ],
                        ],
                        'Comment' => [
                            [
                                'id' => 1, 'article_id' => 1, 'user_id' => 2, 'comment' => 'First Comment for First Article',
                                'published' => 'Y', 'created' => '2007-03-18 10:45:23', 'updated' => '2007-03-18 10:47:31',
                                'Article' => [
                                    'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
                                    'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                                ],
                                'Attachment' => [],
                            ],
                            [
                                'id' => 2, 'article_id' => 1, 'user_id' => 4, 'comment' => 'Second Comment for First Article',
                                'published' => 'Y', 'created' => '2007-03-18 10:47:23', 'updated' => '2007-03-18 10:49:31',
                                'Article' => [
                                    'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
                                    'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                                ],
                                'Attachment' => [],
                            ],
                            [
                                'id' => 3, 'article_id' => 1, 'user_id' => 1, 'comment' => 'Third Comment for First Article',
                                'published' => 'Y', 'created' => '2007-03-18 10:49:23', 'updated' => '2007-03-18 10:51:31',
                                'Article' => [
                                    'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
                                    'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                                ],
                                'Attachment' => [],
                            ],
                            [
                                'id' => 4, 'article_id' => 1, 'user_id' => 1, 'comment' => 'Fourth Comment for First Article',
                                'published' => 'N', 'created' => '2007-03-18 10:51:23', 'updated' => '2007-03-18 10:53:31',
                                'Article' => [
                                    'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
                                    'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                                ],
                                'Attachment' => [],
                            ],
                        ],
                    ],
                    [
                        'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
                        'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31',
                        'Featured' => [],
                        'Comment' => [],
                    ],
                ],
            ],
            [
                'User' => [
                    'id' => 2, 'user' => 'nate', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:18:23', 'updated' => '2007-03-17 01:20:31',
                ],
                'ArticleFeatured' => [],
            ],
            [
                'User' => [
                    'id' => 3, 'user' => 'larry', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:20:23', 'updated' => '2007-03-17 01:22:31',
                ],
                'ArticleFeatured' => [
                    [
                        'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
                        'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31',
                        'Featured' => [
                            'id' => 2, 'article_featured_id' => 2, 'category_id' => 1, 'published_date' => '2007-03-31 10:39:23',
                            'end_date' => '2007-05-15 10:39:23', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                            'Category' => [
                                'id' => 1, 'parent_id' => 0, 'name' => 'Category 1',
                                'created' => '2007-03-18 15:30:23', 'updated' => '2007-03-18 15:32:31',
                            ],
                        ],
                        'Comment' => [
                            [
                                'id' => 5, 'article_id' => 2, 'user_id' => 1, 'comment' => 'First Comment for Second Article',
                                'published' => 'Y', 'created' => '2007-03-18 10:53:23', 'updated' => '2007-03-18 10:55:31',
                                'Article' => [
                                    'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
                                    'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31',
                                ],
                                'Attachment' => [
                                    'id' => 1, 'comment_id' => 5, 'attachment' => 'attachment.zip',
                                    'created' => '2007-03-18 10:51:23', 'updated' => '2007-03-18 10:53:31',
                                ],
                            ],
                            [
                                'id' => 6, 'article_id' => 2, 'user_id' => 2, 'comment' => 'Second Comment for Second Article',
                                'published' => 'Y', 'created' => '2007-03-18 10:55:23', 'updated' => '2007-03-18 10:57:31',
                                'Article' => [
                                    'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
                                    'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31',
                                ],
                                'Attachment' => [],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'User' => [
                    'id' => 4, 'user' => 'garrett', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:22:23', 'updated' => '2007-03-17 01:24:31',
                ],
                'ArticleFeatured' => [],
            ],
        ];
        $this->assertEqual($result, $expected);

        $this->__assertBindings($this->User, ['hasMany' => ['ArticleFeatured']]);
        $this->__assertBindings($this->User->ArticleFeatured, ['hasOne' => ['Featured'], 'hasMany' => ['Comment']]);
        $this->__assertBindings($this->User->ArticleFeatured->Featured, ['belongsTo' => ['Category']]);
        $this->__assertBindings($this->User->ArticleFeatured->Comment, ['belongsTo' => ['Article'], 'hasOne' => ['Attachment']]);

        $this->User->resetBindings();
        $this->__assertBindings($this->User, ['hasMany' => ['Article', 'ArticleFeatured', 'Comment']]);
        $this->__assertBindings($this->User->ArticleFeatured, ['belongsTo' => ['User'], 'hasOne' => ['Featured'], 'hasMany' => ['Comment'], 'hasAndBelongsToMany' => ['Tag']]);
        $this->__assertBindings($this->User->ArticleFeatured->Featured, ['belongsTo' => ['ArticleFeatured', 'Category']]);
        $this->__assertBindings($this->User->ArticleFeatured->Comment, ['belongsTo' => ['Article', 'User'], 'hasOne' => ['Attachment']]);

        $result = $this->User->find('all', ['contain' => ['ArticleFeatured' => ['Featured' => 'Category', 'Comment' => ['Article', 'Attachment']], false]]);
        $expected = [
            [
                'User' => [
                    'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31',
                ],
                'ArticleFeatured' => [
                    [
                        'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
                        'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                        'Featured' => [
                            'id' => 1, 'article_featured_id' => 1, 'category_id' => 1, 'published_date' => '2007-03-31 10:39:23',
                            'end_date' => '2007-05-15 10:39:23', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                            'Category' => [
                                'id' => 1, 'parent_id' => 0, 'name' => 'Category 1',
                                'created' => '2007-03-18 15:30:23', 'updated' => '2007-03-18 15:32:31',
                            ],
                        ],
                        'Comment' => [
                            [
                                'id' => 1, 'article_id' => 1, 'user_id' => 2, 'comment' => 'First Comment for First Article',
                                'published' => 'Y', 'created' => '2007-03-18 10:45:23', 'updated' => '2007-03-18 10:47:31',
                                'Article' => [
                                    'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
                                    'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                                ],
                                'Attachment' => [],
                            ],
                            [
                                'id' => 2, 'article_id' => 1, 'user_id' => 4, 'comment' => 'Second Comment for First Article',
                                'published' => 'Y', 'created' => '2007-03-18 10:47:23', 'updated' => '2007-03-18 10:49:31',
                                'Article' => [
                                    'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
                                    'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                                ],
                                'Attachment' => [],
                            ],
                            [
                                'id' => 3, 'article_id' => 1, 'user_id' => 1, 'comment' => 'Third Comment for First Article',
                                'published' => 'Y', 'created' => '2007-03-18 10:49:23', 'updated' => '2007-03-18 10:51:31',
                                'Article' => [
                                    'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
                                    'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                                ],
                                'Attachment' => [],
                            ],
                            [
                                'id' => 4, 'article_id' => 1, 'user_id' => 1, 'comment' => 'Fourth Comment for First Article',
                                'published' => 'N', 'created' => '2007-03-18 10:51:23', 'updated' => '2007-03-18 10:53:31',
                                'Article' => [
                                    'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
                                    'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                                ],
                                'Attachment' => [],
                            ],
                        ],
                    ],
                    [
                        'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
                        'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31',
                        'Featured' => [],
                        'Comment' => [],
                    ],
                ],
            ],
            [
                'User' => [
                    'id' => 2, 'user' => 'nate', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:18:23', 'updated' => '2007-03-17 01:20:31',
                ],
                'ArticleFeatured' => [],
            ],
            [
                'User' => [
                    'id' => 3, 'user' => 'larry', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:20:23', 'updated' => '2007-03-17 01:22:31',
                ],
                'ArticleFeatured' => [
                    [
                        'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
                        'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31',
                        'Featured' => [
                            'id' => 2, 'article_featured_id' => 2, 'category_id' => 1, 'published_date' => '2007-03-31 10:39:23',
                            'end_date' => '2007-05-15 10:39:23', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                            'Category' => [
                                'id' => 1, 'parent_id' => 0, 'name' => 'Category 1',
                                'created' => '2007-03-18 15:30:23', 'updated' => '2007-03-18 15:32:31',
                            ],
                        ],
                        'Comment' => [
                            [
                                'id' => 5, 'article_id' => 2, 'user_id' => 1, 'comment' => 'First Comment for Second Article',
                                'published' => 'Y', 'created' => '2007-03-18 10:53:23', 'updated' => '2007-03-18 10:55:31',
                                'Article' => [
                                    'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
                                    'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31',
                                ],
                                'Attachment' => [
                                    'id' => 1, 'comment_id' => 5, 'attachment' => 'attachment.zip',
                                    'created' => '2007-03-18 10:51:23', 'updated' => '2007-03-18 10:53:31',
                                ],
                            ],
                            [
                                'id' => 6, 'article_id' => 2, 'user_id' => 2, 'comment' => 'Second Comment for Second Article',
                                'published' => 'Y', 'created' => '2007-03-18 10:55:23', 'updated' => '2007-03-18 10:57:31',
                                'Article' => [
                                    'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
                                    'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31',
                                ],
                                'Attachment' => [],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'User' => [
                    'id' => 4, 'user' => 'garrett', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:22:23', 'updated' => '2007-03-17 01:24:31',
                ],
                'ArticleFeatured' => [],
            ],
        ];
        $this->assertEqual($result, $expected);

        $this->__assertBindings($this->User, ['hasMany' => ['ArticleFeatured']]);
        $this->__assertBindings($this->User->ArticleFeatured, ['hasOne' => ['Featured'], 'hasMany' => ['Comment']]);
        $this->__assertBindings($this->User->ArticleFeatured->Featured, ['belongsTo' => ['Category']]);
        $this->__assertBindings($this->User->ArticleFeatured->Comment, ['belongsTo' => ['Article'], 'hasOne' => ['Attachment']]);

        $this->User->resetBindings();
        $this->__assertBindings($this->User, ['hasMany' => ['Article', 'ArticleFeatured', 'Comment']]);
        $this->__assertBindings($this->User->ArticleFeatured, ['belongsTo' => ['User'], 'hasOne' => ['Featured'], 'hasMany' => ['Comment'], 'hasAndBelongsToMany' => ['Tag']]);
        $this->__assertBindings($this->User->ArticleFeatured->Featured, ['belongsTo' => ['ArticleFeatured', 'Category']]);
        $this->__assertBindings($this->User->ArticleFeatured->Comment, ['belongsTo' => ['Article', 'User'], 'hasOne' => ['Attachment']]);

        $result = $this->User->find('all', ['reset' => false, 'contain' => ['ArticleFeatured' => ['Featured' => 'Category', 'Comment' => 'Attachment'], 'Article']]);
        $expected = [
            [
                'User' => [
                    'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31',
                ],
                'Article' => [
                    [
                        'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
                        'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                    ],
                    [
                        'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
                        'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31',
                    ],
                ],
                'ArticleFeatured' => [
                    [
                        'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
                        'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                        'Featured' => [
                            'id' => 1, 'article_featured_id' => 1, 'category_id' => 1, 'published_date' => '2007-03-31 10:39:23',
                            'end_date' => '2007-05-15 10:39:23', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                            'Category' => [
                                'id' => 1, 'parent_id' => 0, 'name' => 'Category 1',
                                'created' => '2007-03-18 15:30:23', 'updated' => '2007-03-18 15:32:31',
                            ],
                        ],
                        'Comment' => [
                            [
                                'id' => 1, 'article_id' => 1, 'user_id' => 2, 'comment' => 'First Comment for First Article',
                                'published' => 'Y', 'created' => '2007-03-18 10:45:23', 'updated' => '2007-03-18 10:47:31',
                                'Attachment' => [],
                            ],
                            [
                                'id' => 2, 'article_id' => 1, 'user_id' => 4, 'comment' => 'Second Comment for First Article',
                                'published' => 'Y', 'created' => '2007-03-18 10:47:23', 'updated' => '2007-03-18 10:49:31',
                                'Attachment' => [],
                            ],
                            [
                                'id' => 3, 'article_id' => 1, 'user_id' => 1, 'comment' => 'Third Comment for First Article',
                                'published' => 'Y', 'created' => '2007-03-18 10:49:23', 'updated' => '2007-03-18 10:51:31',
                                'Attachment' => [],
                            ],
                            [
                                'id' => 4, 'article_id' => 1, 'user_id' => 1, 'comment' => 'Fourth Comment for First Article',
                                'published' => 'N', 'created' => '2007-03-18 10:51:23', 'updated' => '2007-03-18 10:53:31',
                                'Attachment' => [],
                            ],
                        ],
                    ],
                    [
                        'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
                        'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31',
                        'Featured' => [],
                        'Comment' => [],
                    ],
                ],
            ],
            [
                'User' => [
                    'id' => 2, 'user' => 'nate', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:18:23', 'updated' => '2007-03-17 01:20:31',
                ],
                'Article' => [],
                'ArticleFeatured' => [],
            ],
            [
                'User' => [
                    'id' => 3, 'user' => 'larry', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:20:23', 'updated' => '2007-03-17 01:22:31',
                ],
                'Article' => [
                    [
                        'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
                        'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31',
                    ],
                ],
                'ArticleFeatured' => [
                    [
                        'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
                        'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31',
                        'Featured' => [
                            'id' => 2, 'article_featured_id' => 2, 'category_id' => 1, 'published_date' => '2007-03-31 10:39:23',
                            'end_date' => '2007-05-15 10:39:23', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
                            'Category' => [
                                'id' => 1, 'parent_id' => 0, 'name' => 'Category 1',
                                'created' => '2007-03-18 15:30:23', 'updated' => '2007-03-18 15:32:31',
                            ],
                        ],
                        'Comment' => [
                            [
                                'id' => 5, 'article_id' => 2, 'user_id' => 1, 'comment' => 'First Comment for Second Article',
                                'published' => 'Y', 'created' => '2007-03-18 10:53:23', 'updated' => '2007-03-18 10:55:31',
                                'Attachment' => [
                                    'id' => 1, 'comment_id' => 5, 'attachment' => 'attachment.zip',
                                    'created' => '2007-03-18 10:51:23', 'updated' => '2007-03-18 10:53:31',
                                ],
                            ],
                            [
                                'id' => 6, 'article_id' => 2, 'user_id' => 2, 'comment' => 'Second Comment for Second Article',
                                'published' => 'Y', 'created' => '2007-03-18 10:55:23', 'updated' => '2007-03-18 10:57:31',
                                'Attachment' => [],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'User' => [
                    'id' => 4, 'user' => 'garrett', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                    'created' => '2007-03-17 01:22:23', 'updated' => '2007-03-17 01:24:31',
                ],
                'Article' => [],
                'ArticleFeatured' => [],
            ],
        ];
        $this->assertEqual($result, $expected);

        $this->__assertBindings($this->User, ['hasMany' => ['Article', 'ArticleFeatured']]);
        $this->__assertBindings($this->User->Article);
        $this->__assertBindings($this->User->ArticleFeatured, ['hasOne' => ['Featured'], 'hasMany' => ['Comment']]);
        $this->__assertBindings($this->User->ArticleFeatured->Featured, ['belongsTo' => ['Category']]);
        $this->__assertBindings($this->User->ArticleFeatured->Comment, ['hasOne' => ['Attachment']]);

        $this->User->resetBindings();
        $this->__assertBindings($this->User, ['hasMany' => ['Article', 'ArticleFeatured', 'Comment']]);
        $this->__assertBindings($this->User->Article, ['belongsTo' => ['User'], 'hasMany' => ['Comment'], 'hasAndBelongsToMany' => ['Tag']]);
        $this->__assertBindings($this->User->ArticleFeatured, ['belongsTo' => ['User'], 'hasOne' => ['Featured'], 'hasMany' => ['Comment'], 'hasAndBelongsToMany' => ['Tag']]);
        $this->__assertBindings($this->User->ArticleFeatured->Featured, ['belongsTo' => ['ArticleFeatured', 'Category']]);
        $this->__assertBindings($this->User->ArticleFeatured->Comment, ['belongsTo' => ['Article', 'User'], 'hasOne' => ['Attachment']]);
    }

    /**
     * testEmbeddedFindFields method.
     */
    public function testEmbeddedFindFields()
    {
        $result = $this->Article->find('all', [
            'contain' => ['User(user)'],
            'fields' => ['title'],
        ]);
        $expected = [
            ['Article' => ['title' => 'First Article'], 'User' => ['user' => 'mariano', 'id' => 1]],
            ['Article' => ['title' => 'Second Article'], 'User' => ['user' => 'larry', 'id' => 3]],
            ['Article' => ['title' => 'Third Article'], 'User' => ['user' => 'mariano', 'id' => 1]],
        ];
        $this->assertEqual($result, $expected);

        $result = $this->Article->find('all', [
            'contain' => ['User(id, user)'],
            'fields' => ['title'],
        ]);
        $expected = [
            ['Article' => ['title' => 'First Article'], 'User' => ['user' => 'mariano', 'id' => 1]],
            ['Article' => ['title' => 'Second Article'], 'User' => ['user' => 'larry', 'id' => 3]],
            ['Article' => ['title' => 'Third Article'], 'User' => ['user' => 'mariano', 'id' => 1]],
        ];
        $this->assertEqual($result, $expected);

        $result = $this->Article->find('all', [
            'contain' => [
                'Comment(comment, published)' => 'Attachment(attachment)', 'User(user)',
            ],
            'fields' => ['title'],
        ]);
        if (!empty($result)) {
            foreach ($result as $i => $article) {
                foreach ($article['Comment'] as $j => $comment) {
                    $result[$i]['Comment'][$j] = array_diff_key($comment, ['id' => true]);
                }
            }
        }
        $expected = [
            [
                'Article' => ['title' => 'First Article', 'id' => 1],
                'User' => ['user' => 'mariano', 'id' => 1],
                'Comment' => [
                    ['comment' => 'First Comment for First Article', 'published' => 'Y', 'article_id' => 1, 'Attachment' => []],
                    ['comment' => 'Second Comment for First Article', 'published' => 'Y', 'article_id' => 1, 'Attachment' => []],
                    ['comment' => 'Third Comment for First Article', 'published' => 'Y', 'article_id' => 1, 'Attachment' => []],
                    ['comment' => 'Fourth Comment for First Article', 'published' => 'N', 'article_id' => 1, 'Attachment' => []],
                ],
            ],
            [
                'Article' => ['title' => 'Second Article', 'id' => 2],
                'User' => ['user' => 'larry', 'id' => 3],
                'Comment' => [
                    ['comment' => 'First Comment for Second Article', 'published' => 'Y', 'article_id' => 2, 'Attachment' => [
                        'attachment' => 'attachment.zip', 'id' => 1,
                    ]],
                    ['comment' => 'Second Comment for Second Article', 'published' => 'Y', 'article_id' => 2, 'Attachment' => []],
                ],
            ],
            [
                'Article' => ['title' => 'Third Article', 'id' => 3],
                'User' => ['user' => 'mariano', 'id' => 1],
                'Comment' => [],
            ],
        ];
        $this->assertEqual($result, $expected);
    }

    /**
     * test that hasOne and belongsTo fields act the same in a contain array.
     */
    public function testHasOneFieldsInContain()
    {
        $this->Article->unbindModel([
            'hasMany' => ['Comment'],
        ], true);
        unset($this->Article->Comment);
        $this->Article->bindModel([
            'hasOne' => ['Comment'],
        ]);

        $result = $this->Article->find('all', [
            'fields' => ['title', 'body'],
            'contain' => [
                'Comment' => [
                    'fields' => ['comment'],
                ],
                'User' => [
                    'fields' => ['user'],
                ],
            ],
        ]);
        $this->assertTrue(isset($result[0]['Article']['title']), 'title missing %s');
        $this->assertTrue(isset($result[0]['Article']['body']), 'body missing %s');
        $this->assertTrue(isset($result[0]['Comment']['comment']), 'comment missing %s');
        $this->assertTrue(isset($result[0]['User']['user']), 'body missing %s');
        $this->assertFalse(isset($result[0]['Comment']['published']), 'published found %s');
        $this->assertFalse(isset($result[0]['User']['password']), 'password found %s');
    }

    /**
     * testFindConditionalBinding method.
     */
    public function testFindConditionalBinding()
    {
        $this->Article->contain([
            'User(user)',
            'Tag' => [
                'fields' => ['tag', 'created'],
                'conditions' => ['created >=' => '2007-03-18 12:24'],
            ],
        ]);
        $result = $this->Article->find('all', ['fields' => ['title']]);
        $expected = [
            [
                'Article' => ['id' => 1, 'title' => 'First Article'],
                'User' => ['id' => 1, 'user' => 'mariano'],
                'Tag' => [['tag' => 'tag2', 'created' => '2007-03-18 12:24:23']],
            ],
            [
                'Article' => ['id' => 2, 'title' => 'Second Article'],
                'User' => ['id' => 3, 'user' => 'larry'],
                'Tag' => [['tag' => 'tag3', 'created' => '2007-03-18 12:26:23']],
            ],
            [
                'Article' => ['id' => 3, 'title' => 'Third Article'],
                'User' => ['id' => 1, 'user' => 'mariano'],
                'Tag' => [],
            ],
        ];
        $this->assertEqual($result, $expected);

        $this->Article->contain(['User(id,user)', 'Tag' => ['fields' => ['tag', 'created']]]);
        $result = $this->Article->find('all', ['fields' => ['title']]);
        $expected = [
            [
                'Article' => ['id' => 1, 'title' => 'First Article'],
                'User' => ['id' => 1, 'user' => 'mariano'],
                'Tag' => [
                    ['tag' => 'tag1', 'created' => '2007-03-18 12:22:23'],
                    ['tag' => 'tag2', 'created' => '2007-03-18 12:24:23'],
                ],
            ],
            [
                'Article' => ['id' => 2, 'title' => 'Second Article'],
                'User' => ['id' => 3, 'user' => 'larry'],
                'Tag' => [
                    ['tag' => 'tag1', 'created' => '2007-03-18 12:22:23'],
                    ['tag' => 'tag3', 'created' => '2007-03-18 12:26:23'],
                ],
            ],
            [
                'Article' => ['id' => 3, 'title' => 'Third Article'],
                'User' => ['id' => 1, 'user' => 'mariano'],
                'Tag' => [],
            ],
        ];
        $this->assertEqual($result, $expected);

        $result = $this->Article->find('all', [
            'fields' => ['title'],
            'contain' => ['User(id,user)', 'Tag' => ['fields' => ['tag', 'created']]],
        ]);
        $expected = [
            [
                'Article' => ['id' => 1, 'title' => 'First Article'],
                'User' => ['id' => 1, 'user' => 'mariano'],
                'Tag' => [
                    ['tag' => 'tag1', 'created' => '2007-03-18 12:22:23'],
                    ['tag' => 'tag2', 'created' => '2007-03-18 12:24:23'],
                ],
            ],
            [
                'Article' => ['id' => 2, 'title' => 'Second Article'],
                'User' => ['id' => 3, 'user' => 'larry'],
                'Tag' => [
                    ['tag' => 'tag1', 'created' => '2007-03-18 12:22:23'],
                    ['tag' => 'tag3', 'created' => '2007-03-18 12:26:23'],
                ],
            ],
            [
                'Article' => ['id' => 3, 'title' => 'Third Article'],
                'User' => ['id' => 1, 'user' => 'mariano'],
                'Tag' => [],
            ],
        ];
        $this->assertEqual($result, $expected);

        $this->Article->contain([
            'User(id,user)',
            'Tag' => [
                'fields' => ['tag', 'created'],
                'conditions' => ['created >=' => '2007-03-18 12:24'],
            ],
        ]);
        $result = $this->Article->find('all', ['fields' => ['title']]);
        $expected = [
            [
                'Article' => ['id' => 1, 'title' => 'First Article'],
                'User' => ['id' => 1, 'user' => 'mariano'],
                'Tag' => [['tag' => 'tag2', 'created' => '2007-03-18 12:24:23']],
            ],
            [
                'Article' => ['id' => 2, 'title' => 'Second Article'],
                'User' => ['id' => 3, 'user' => 'larry'],
                'Tag' => [['tag' => 'tag3', 'created' => '2007-03-18 12:26:23']],
            ],
            [
                'Article' => ['id' => 3, 'title' => 'Third Article'],
                'User' => ['id' => 1, 'user' => 'mariano'],
                'Tag' => [],
            ],
        ];
        $this->assertEqual($result, $expected);

        $this->assertTrue(empty($this->User->Article->hasAndBelongsToMany['Tag']['conditions']));

        $result = $this->User->find('all', ['contain' => [
            'Article.Tag' => ['conditions' => ['created >=' => '2007-03-18 12:24']],
        ]]);

        $this->assertTrue(Set::matches('/User[id=1]', $result));
        $this->assertFalse(Set::matches('/Article[id=1]/Tag[id=1]', $result));
        $this->assertTrue(Set::matches('/Article[id=1]/Tag[id=2]', $result));
        $this->assertTrue(empty($this->User->Article->hasAndBelongsToMany['Tag']['conditions']));

        $this->assertTrue(empty($this->User->Article->hasAndBelongsToMany['Tag']['order']));

        $result = $this->User->find('all', ['contain' => [
            'Article.Tag' => ['order' => 'created DESC'],
        ]]);

        $this->assertTrue(Set::matches('/User[id=1]', $result));
        $this->assertTrue(Set::matches('/Article[id=1]/Tag[id=1]', $result));
        $this->assertTrue(Set::matches('/Article[id=1]/Tag[id=2]', $result));
        $this->assertTrue(empty($this->User->Article->hasAndBelongsToMany['Tag']['order']));
    }

    /**
     * testOtherFinds method.
     */
    public function testOtherFinds()
    {
        $result = $this->Article->find('count');
        $expected = 3;
        $this->assertEqual($result, $expected);

        $result = $this->Article->find('count', ['conditions' => ['Article.id >' => '1']]);
        $expected = 2;
        $this->assertEqual($result, $expected);

        $result = $this->Article->find('count', ['contain' => []]);
        $expected = 3;
        $this->assertEqual($result, $expected);

        $this->Article->contain(['User(id,user)', 'Tag' => ['fields' => ['tag', 'created'], 'conditions' => ['created >=' => '2007-03-18 12:24']]]);
        $result = $this->Article->find('first', ['fields' => ['title']]);
        $expected = [
            'Article' => ['id' => 1, 'title' => 'First Article'],
            'User' => ['id' => 1, 'user' => 'mariano'],
            'Tag' => [['tag' => 'tag2', 'created' => '2007-03-18 12:24:23']],
        ];
        $this->assertEqual($result, $expected);

        $this->Article->contain(['User(id,user)', 'Tag' => ['fields' => ['tag', 'created']]]);
        $result = $this->Article->find('first', ['fields' => ['title']]);
        $expected = [
            'Article' => ['id' => 1, 'title' => 'First Article'],
            'User' => ['id' => 1, 'user' => 'mariano'],
            'Tag' => [
                ['tag' => 'tag1', 'created' => '2007-03-18 12:22:23'],
                ['tag' => 'tag2', 'created' => '2007-03-18 12:24:23'],
            ],
        ];
        $this->assertEqual($result, $expected);

        $result = $this->Article->find('first', [
            'fields' => ['title'],
            'order' => 'Article.id DESC',
            'contain' => ['User(id,user)', 'Tag' => ['fields' => ['tag', 'created']]],
        ]);
        $expected = [
            'Article' => ['id' => 3, 'title' => 'Third Article'],
            'User' => ['id' => 1, 'user' => 'mariano'],
            'Tag' => [],
        ];
        $this->assertEqual($result, $expected);

        $result = $this->Article->find('list', [
            'contain' => ['User(id,user)'],
            'fields' => ['Article.id', 'Article.title'],
        ]);
        $expected = [
            1 => 'First Article',
            2 => 'Second Article',
            3 => 'Third Article',
        ];
        $this->assertEqual($result, $expected);
    }

    /**
     * testPaginate method.
     */
    public function testPaginate()
    {
        $Controller = new Controller();
        $Controller->uses = ['Article'];
        $Controller->passedArgs[] = '1';
        $Controller->params['url'] = [];
        $Controller->constructClasses();

        $Controller->paginate = ['Article' => ['fields' => ['title'], 'contain' => ['User(user)']]];
        $result = $Controller->paginate('Article');
        $expected = [
            ['Article' => ['title' => 'First Article'], 'User' => ['user' => 'mariano', 'id' => 1]],
            ['Article' => ['title' => 'Second Article'], 'User' => ['user' => 'larry', 'id' => 3]],
            ['Article' => ['title' => 'Third Article'], 'User' => ['user' => 'mariano', 'id' => 1]],
        ];
        $this->assertEqual($result, $expected);

        $r = $Controller->Article->find('all');
        $this->assertTrue(Set::matches('/Article[id=1]', $r));
        $this->assertTrue(Set::matches('/User[id=1]', $r));
        $this->assertTrue(Set::matches('/Tag[id=1]', $r));

        $Controller->paginate = ['Article' => ['contain' => ['Comment(comment)' => 'User(user)'], 'fields' => ['title']]];
        $result = $Controller->paginate('Article');
        $expected = [
            [
                'Article' => ['title' => 'First Article', 'id' => 1],
                'Comment' => [
                    [
                        'comment' => 'First Comment for First Article',
                        'user_id' => 2,
                        'article_id' => 1,
                        'User' => ['user' => 'nate'],
                    ],
                    [
                        'comment' => 'Second Comment for First Article',
                        'user_id' => 4,
                        'article_id' => 1,
                        'User' => ['user' => 'garrett'],
                    ],
                    [
                        'comment' => 'Third Comment for First Article',
                        'user_id' => 1,
                        'article_id' => 1,
                        'User' => ['user' => 'mariano'],
                    ],
                    [
                        'comment' => 'Fourth Comment for First Article',
                        'user_id' => 1,
                        'article_id' => 1,
                        'User' => ['user' => 'mariano'],
                    ],
                ],
            ],
            [
                'Article' => ['title' => 'Second Article', 'id' => 2],
                'Comment' => [
                    [
                        'comment' => 'First Comment for Second Article',
                        'user_id' => 1,
                        'article_id' => 2,
                        'User' => ['user' => 'mariano'],
                    ],
                    [
                        'comment' => 'Second Comment for Second Article',
                        'user_id' => 2,
                        'article_id' => 2,
                        'User' => ['user' => 'nate'],
                    ],
                ],
            ],
            [
                'Article' => ['title' => 'Third Article', 'id' => 3],
                'Comment' => [],
            ],
        ];
        $this->assertEqual($result, $expected);

        $r = $Controller->Article->find('all');
        $this->assertTrue(Set::matches('/Article[id=1]', $r));
        $this->assertTrue(Set::matches('/User[id=1]', $r));
        $this->assertTrue(Set::matches('/Tag[id=1]', $r));

        $Controller->Article->unbindModel(['hasMany' => ['Comment'], 'belongsTo' => ['User'], 'hasAndBelongsToMany' => ['Tag']], false);
        $Controller->Article->bindModel(['hasMany' => ['Comment'], 'belongsTo' => ['User']], false);

        $Controller->paginate = ['Article' => ['contain' => ['Comment(comment)', 'User(user)'], 'fields' => ['title']]];
        $r = $Controller->paginate('Article');
        $this->assertTrue(Set::matches('/Article[id=1]', $r));
        $this->assertTrue(Set::matches('/User[id=1]', $r));
        $this->assertTrue(Set::matches('/Comment[article_id=1]', $r));
        $this->assertFalse(Set::matches('/Comment[id=1]', $r));

        $r = $this->Article->find('all');
        $this->assertTrue(Set::matches('/Article[id=1]', $r));
        $this->assertTrue(Set::matches('/User[id=1]', $r));
        $this->assertTrue(Set::matches('/Comment[article_id=1]', $r));
        $this->assertTrue(Set::matches('/Comment[id=1]', $r));
    }

    /**
     * testOriginalAssociations method.
     */
    public function testOriginalAssociations()
    {
        $this->Article->Comment->Behaviors->attach('Containable');

        $options = [
            'conditions' => [
                'Comment.published' => 'Y',
            ],
            'contain' => 'User',
            'recursive' => 1,
        ];

        $firstResult = $this->Article->Comment->find('all', $options);

        $dummyResult = $this->Article->Comment->find('all', [
            'conditions' => [
                'User.user' => 'mariano',
            ],
            'fields' => ['User.password'],
            'contain' => ['User.password'],
        ]);

        $result = $this->Article->Comment->find('all', $options);
        $this->assertEqual($result, $firstResult);

        $this->Article->unbindModel(['hasMany' => ['Comment'], 'belongsTo' => ['User'], 'hasAndBelongsToMany' => ['Tag']], false);
        $this->Article->bindModel(['hasMany' => ['Comment'], 'belongsTo' => ['User']], false);

        $r = $this->Article->find('all', ['contain' => ['Comment(comment)', 'User(user)'], 'fields' => ['title']]);
        $this->assertTrue(Set::matches('/Article[id=1]', $r));
        $this->assertTrue(Set::matches('/User[id=1]', $r));
        $this->assertTrue(Set::matches('/Comment[article_id=1]', $r));
        $this->assertFalse(Set::matches('/Comment[id=1]', $r));

        $r = $this->Article->find('all');
        $this->assertTrue(Set::matches('/Article[id=1]', $r));
        $this->assertTrue(Set::matches('/User[id=1]', $r));
        $this->assertTrue(Set::matches('/Comment[article_id=1]', $r));
        $this->assertTrue(Set::matches('/Comment[id=1]', $r));

        $this->Article->bindModel(['hasAndBelongsToMany' => ['Tag']], false);

        $this->Article->contain(false, ['User(id,user)', 'Comment' => ['fields' => ['comment'], 'conditions' => ['created >=' => '2007-03-18 10:49']]]);
        $result = $this->Article->find('all', ['fields' => ['title'], 'limit' => 1, 'page' => 1, 'order' => 'Article.id ASC']);
        $expected = [[
            'Article' => ['id' => 1, 'title' => 'First Article'],
            'User' => ['id' => 1, 'user' => 'mariano'],
            'Comment' => [
                ['comment' => 'Third Comment for First Article', 'article_id' => 1],
                ['comment' => 'Fourth Comment for First Article', 'article_id' => 1],
            ],
        ]];
        $this->assertEqual($result, $expected);

        $result = $this->Article->find('all', ['fields' => ['title', 'User.id', 'User.user'], 'limit' => 1, 'page' => 2, 'order' => 'Article.id ASC']);
        $expected = [[
            'Article' => ['id' => 2, 'title' => 'Second Article'],
            'User' => ['id' => 3, 'user' => 'larry'],
            'Comment' => [
                ['comment' => 'First Comment for Second Article', 'article_id' => 2],
                ['comment' => 'Second Comment for Second Article', 'article_id' => 2],
            ],
        ]];
        $this->assertEqual($result, $expected);

        $result = $this->Article->find('all', ['fields' => ['title', 'User.id', 'User.user'], 'limit' => 1, 'page' => 3, 'order' => 'Article.id ASC']);
        $expected = [[
            'Article' => ['id' => 3, 'title' => 'Third Article'],
            'User' => ['id' => 1, 'user' => 'mariano'],
            'Comment' => [],
        ]];
        $this->assertEqual($result, $expected);

        $this->Article->contain(false, ['User' => ['fields' => 'user'], 'Comment']);
        $result = $this->Article->find('all');
        $this->assertTrue(Set::matches('/Article[id=1]', $result));
        $this->assertTrue(Set::matches('/User[user=mariano]', $result));
        $this->assertTrue(Set::matches('/Comment[article_id=1]', $result));
        $this->Article->resetBindings();

        $this->Article->contain(false, ['User' => ['fields' => ['user']], 'Comment']);
        $result = $this->Article->find('all');
        $this->assertTrue(Set::matches('/Article[id=1]', $result));
        $this->assertTrue(Set::matches('/User[user=mariano]', $result));
        $this->assertTrue(Set::matches('/Comment[article_id=1]', $result));
        $this->Article->resetBindings();
    }

    /**
     * testResetAddedAssociation method.
     */
    public function testResetAddedAssociation()
    {
        $this->assertTrue(empty($this->Article->hasMany['ArticlesTag']));

        $this->Article->bindModel([
            'hasMany' => ['ArticlesTag'],
        ]);
        $this->assertTrue(!empty($this->Article->hasMany['ArticlesTag']));

        $result = $this->Article->find('first', [
            'conditions' => ['Article.id' => 1],
            'contain' => ['ArticlesTag'],
        ]);
        $expected = ['Article', 'ArticlesTag'];
        $this->assertTrue(!empty($result));
        $this->assertEqual('First Article', $result['Article']['title']);
        $this->assertTrue(!empty($result['ArticlesTag']));
        $this->assertEqual($expected, array_keys($result));

        $this->assertTrue(empty($this->Article->hasMany['ArticlesTag']));

        $this->JoinA = &ClassRegistry::init('JoinA');
        $this->JoinB = &ClassRegistry::init('JoinB');
        $this->JoinC = &ClassRegistry::init('JoinC');

        $this->JoinA->Behaviors->attach('Containable');
        $this->JoinB->Behaviors->attach('Containable');
        $this->JoinC->Behaviors->attach('Containable');

        $this->JoinA->JoinB->find('all', ['contain' => ['JoinA']]);
        $this->JoinA->bindModel(['hasOne' => ['JoinAsJoinC' => ['joinTable' => 'as_cs']]], false);
        $result = $this->JoinA->hasOne;
        $this->JoinA->find('all');
        $resultAfter = $this->JoinA->hasOne;
        $this->assertEqual($result, $resultAfter);
    }

    /**
     * testResetAssociation method.
     */
    public function testResetAssociation()
    {
        $this->Article->Behaviors->attach('Containable');
        $this->Article->Comment->Behaviors->attach('Containable');
        $this->Article->User->Behaviors->attach('Containable');

        $initialOptions = [
            'conditions' => [
                'Comment.published' => 'Y',
            ],
            'contain' => 'User',
            'recursive' => 1,
        ];

        $initialModels = $this->Article->Comment->find('all', $initialOptions);

        $findOptions = [
            'conditions' => [
                'User.user' => 'mariano',
            ],
            'fields' => ['User.password'],
            'contain' => ['User.password'],
        ];
        $result = $this->Article->Comment->find('all', $findOptions);
        $result = $this->Article->Comment->find('all', $initialOptions);
        $this->assertEqual($result, $initialModels);
    }

    /**
     * testResetDeeperHasOneAssociations method.
     */
    public function testResetDeeperHasOneAssociations()
    {
        $this->Article->User->unbindModel([
            'hasMany' => ['ArticleFeatured', 'Comment'],
        ], false);
        $userHasOne = ['hasOne' => ['ArticleFeatured', 'Comment']];

        $this->Article->User->bindModel($userHasOne, false);
        $expected = $this->Article->User->hasOne;
        $this->Article->find('all');
        $this->assertEqual($expected, $this->Article->User->hasOne);

        $this->Article->User->bindModel($userHasOne, false);
        $expected = $this->Article->User->hasOne;
        $this->Article->find('all', [
            'contain' => [
                'User' => ['ArticleFeatured', 'Comment'],
            ],
        ]);
        $this->assertEqual($expected, $this->Article->User->hasOne);

        $this->Article->User->bindModel($userHasOne, false);
        $expected = $this->Article->User->hasOne;
        $this->Article->find('all', [
            'contain' => [
                'User' => [
                    'ArticleFeatured',
                    'Comment' => ['fields' => ['created']],
                ],
            ],
        ]);
        $this->assertEqual($expected, $this->Article->User->hasOne);

        $this->Article->User->bindModel($userHasOne, false);
        $expected = $this->Article->User->hasOne;
        $this->Article->find('all', [
            'contain' => [
                'User' => [
                    'Comment' => ['fields' => ['created']],
                ],
            ],
        ]);
        $this->assertEqual($expected, $this->Article->User->hasOne);

        $this->Article->User->bindModel($userHasOne, false);
        $expected = $this->Article->User->hasOne;
        $this->Article->find('all', [
            'contain' => [
                'User.ArticleFeatured' => [
                    'conditions' => ['ArticleFeatured.published' => 'Y'],
                ],
                'User.Comment',
            ],
        ]);
        $this->assertEqual($expected, $this->Article->User->hasOne);
    }

    /**
     * testResetMultipleHabtmAssociations method.
     */
    public function testResetMultipleHabtmAssociations()
    {
        $articleHabtm = [
            'hasAndBelongsToMany' => [
                'Tag' => [
                    'className' => 'Tag',
                    'joinTable' => 'articles_tags',
                    'foreignKey' => 'article_id',
                    'associationForeignKey' => 'tag_id',
                ],
                'ShortTag' => [
                    'className' => 'Tag',
                    'joinTable' => 'articles_tags',
                    'foreignKey' => 'article_id',
                    'associationForeignKey' => 'tag_id',
                    // LENGHT function mysql-only, using LIKE does almost the same
                    'conditions' => 'ShortTag.tag LIKE "???"',
                ],
            ],
        ];

        $this->Article->resetBindings();
        $this->Article->bindModel($articleHabtm, false);
        $expected = $this->Article->hasAndBelongsToMany;
        $this->Article->find('all');
        $this->assertEqual($expected, $this->Article->hasAndBelongsToMany);

        $this->Article->resetBindings();
        $this->Article->bindModel($articleHabtm, false);
        $expected = $this->Article->hasAndBelongsToMany;
        $this->Article->find('all', ['contain' => 'Tag.tag']);
        $this->assertEqual($expected, $this->Article->hasAndBelongsToMany);

        $this->Article->resetBindings();
        $this->Article->bindModel($articleHabtm, false);
        $expected = $this->Article->hasAndBelongsToMany;
        $this->Article->find('all', ['contain' => 'Tag']);
        $this->assertEqual($expected, $this->Article->hasAndBelongsToMany);

        $this->Article->resetBindings();
        $this->Article->bindModel($articleHabtm, false);
        $expected = $this->Article->hasAndBelongsToMany;
        $this->Article->find('all', ['contain' => ['Tag' => ['fields' => [null]]]]);
        $this->assertEqual($expected, $this->Article->hasAndBelongsToMany);

        $this->Article->resetBindings();
        $this->Article->bindModel($articleHabtm, false);
        $expected = $this->Article->hasAndBelongsToMany;
        $this->Article->find('all', ['contain' => ['Tag' => ['fields' => ['Tag.tag']]]]);
        $this->assertEqual($expected, $this->Article->hasAndBelongsToMany);

        $this->Article->resetBindings();
        $this->Article->bindModel($articleHabtm, false);
        $expected = $this->Article->hasAndBelongsToMany;
        $this->Article->find('all', ['contain' => ['Tag' => ['fields' => ['Tag.tag', 'Tag.created']]]]);
        $this->assertEqual($expected, $this->Article->hasAndBelongsToMany);

        $this->Article->resetBindings();
        $this->Article->bindModel($articleHabtm, false);
        $expected = $this->Article->hasAndBelongsToMany;
        $this->Article->find('all', ['contain' => 'ShortTag.tag']);
        $this->assertEqual($expected, $this->Article->hasAndBelongsToMany);

        $this->Article->resetBindings();
        $this->Article->bindModel($articleHabtm, false);
        $expected = $this->Article->hasAndBelongsToMany;
        $this->Article->find('all', ['contain' => 'ShortTag']);
        $this->assertEqual($expected, $this->Article->hasAndBelongsToMany);

        $this->Article->resetBindings();
        $this->Article->bindModel($articleHabtm, false);
        $expected = $this->Article->hasAndBelongsToMany;
        $this->Article->find('all', ['contain' => ['ShortTag' => ['fields' => [null]]]]);
        $this->assertEqual($expected, $this->Article->hasAndBelongsToMany);

        $this->Article->resetBindings();
        $this->Article->bindModel($articleHabtm, false);
        $expected = $this->Article->hasAndBelongsToMany;
        $this->Article->find('all', ['contain' => ['ShortTag' => ['fields' => ['ShortTag.tag']]]]);
        $this->assertEqual($expected, $this->Article->hasAndBelongsToMany);

        $this->Article->resetBindings();
        $this->Article->bindModel($articleHabtm, false);
        $expected = $this->Article->hasAndBelongsToMany;
        $this->Article->find('all', ['contain' => ['ShortTag' => ['fields' => ['ShortTag.tag', 'ShortTag.created']]]]);
        $this->assertEqual($expected, $this->Article->hasAndBelongsToMany);
    }

    /**
     * test that bindModel and unbindModel work with find() calls in between.
     */
    public function testBindMultipleTimesWithFind()
    {
        $binding = [
            'hasOne' => [
                'ArticlesTag' => [
                    'foreignKey' => false,
                    'type' => 'INNER',
                    'conditions' => [
                        'ArticlesTag.article_id = Article.id',
                    ],
                ],
                'Tag' => [
                    'type' => 'INNER',
                    'foreignKey' => false,
                    'conditions' => [
                        'ArticlesTag.tag_id = Tag.id',
                    ],
                ],
            ],
        ];
        $this->Article->unbindModel(['hasAndBelongsToMany' => ['Tag']]);
        $this->Article->bindModel($binding);
        $result = $this->Article->find('all', ['limit' => 1, 'contain' => ['ArticlesTag', 'Tag']]);

        $this->Article->unbindModel(['hasAndBelongsToMany' => ['Tag']]);
        $this->Article->bindModel($binding);
        $result = $this->Article->find('all', ['limit' => 1, 'contain' => ['ArticlesTag', 'Tag']]);

        $associated = $this->Article->getAssociated();
        $this->assertEqual('hasAndBelongsToMany', $associated['Tag']);
        $this->assertFalse(isset($associated['ArticleTag']));
    }

    /**
     * test that autoFields doesn't splice in fields from other databases.
     */
    public function testAutoFieldsWithMultipleDatabases()
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

        $db = &ConnectionManager::getDataSource('test2');
        $this->_fixtures[$this->_fixtureClassMap['User']]->create($db);
        $this->_fixtures[$this->_fixtureClassMap['User']]->insert($db);

        $this->Article->User->setDataSource('test2');

        $result = $this->Article->find('all', [
            'fields' => ['Article.title'],
            'contain' => ['User'],
        ]);
        $this->assertTrue(isset($result[0]['Article']));
        $this->assertTrue(isset($result[0]['User']));

        $this->_fixtures[$this->_fixtureClassMap['User']]->drop($db);
    }

    /**
     * test that autoFields doesn't splice in columns that aren't part of the join.
     */
    public function testAutoFieldsWithRecursiveNegativeOne()
    {
        $this->Article->recursive = -1;
        $result = $this->Article->field('title', ['Article.title' => 'First Article']);
        $this->assertNoErrors();
        $this->assertEqual($result, 'First Article', 'Field is wrong');
    }

    /**
     * test that find(all) doesn't return incorrect values when mixed with containable.
     */
    public function testFindAllReturn()
    {
        $result = $this->Article->find('all', [
            'conditions' => ['Article.id' => 999999999],
        ]);
        $this->assertEqual($result, [], 'Should be empty.');
    }

    /**
     * containments method.
     *
     * @param mixed $Model
     * @param array $contain
     */
    public function __containments(&$Model, $contain = [])
    {
        if (!is_array($Model)) {
            $result = $Model->containments($contain);

            return $this->__containments($result['models']);
        } else {
            $result = $Model;
            foreach ($result as $i => $containment) {
                $result[$i] = array_diff_key($containment, ['instance' => true]);
            }
        }

        return $result;
    }

    /**
     * assertBindings method.
     *
     * @param mixed $Model
     * @param array $expected
     */
    public function __assertBindings(&$Model, $expected = [])
    {
        $expected = array_merge(['belongsTo' => [], 'hasOne' => [], 'hasMany' => [], 'hasAndBelongsToMany' => []], $expected);

        foreach ($expected as $binding => $expect) {
            $this->assertEqual(array_keys($Model->$binding), $expect);
        }
    }

    /**
     * bindings method.
     *
     * @param mixed $Model
     * @param array $extra
     * @param bool  $output
     */
    public function __bindings(&$Model, $extra = [], $output = true)
    {
        $relationTypes = ['belongsTo', 'hasOne', 'hasMany', 'hasAndBelongsToMany'];

        $debug = '[';
        $lines = [];
        foreach ($relationTypes as $binding) {
            if (!empty($Model->$binding)) {
                $models = array_keys($Model->$binding);
                foreach ($models as $linkedModel) {
                    $line = $linkedModel;
                    if (!empty($extra) && !empty($Model->{$binding}[$linkedModel])) {
                        $extraData = [];
                        foreach (array_intersect_key($Model->{$binding}[$linkedModel], array_flip($extra)) as $key => $value) {
                            $extraData[] = $key.': '.(is_array($value) ? '('.implode(', ', $value).')' : $value);
                        }
                        $line .= ' {'.implode(' - ', $extraData).'}';
                    }
                    $lines[] = $line;
                }
            }
        }
        $debug .= implode(' | ', $lines);
        $debug .= ']';
        $debug = '<strong>'.$Model->alias.'</strong>: '.$debug.'<br />';

        if ($output) {
            echo $debug;
        }

        return $debug;
    }
}
