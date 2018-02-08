<?php
/**
 * ModelDeleteTest file.
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
 * ModelDeleteTest.
 */
class ModelDeleteTest extends BaseModelTest
{
    /**
     * testDeleteHabtmReferenceWithConditions method.
     */
    public function testDeleteHabtmReferenceWithConditions()
    {
        $this->loadFixtures('Portfolio', 'Item', 'ItemsPortfolio');

        $Portfolio = new Portfolio();
        $Portfolio->hasAndBelongsToMany['Item']['conditions'] = ['ItemsPortfolio.item_id >' => 1];

        $result = $Portfolio->find('first', [
            'conditions' => ['Portfolio.id' => 1],
        ]);
        $expected = [
            [
                'id' => 3,
                'syfile_id' => 3,
                'published' => 0,
                'name' => 'Item 3',
                'ItemsPortfolio' => [
                    'id' => 3,
                    'item_id' => 3,
                    'portfolio_id' => 1,
            ], ],
            [
                'id' => 4,
                'syfile_id' => 4,
                'published' => 0,
                'name' => 'Item 4',
                'ItemsPortfolio' => [
                    'id' => 4,
                    'item_id' => 4,
                    'portfolio_id' => 1,
            ], ],
            [
                'id' => 5,
                'syfile_id' => 5,
                'published' => 0,
                'name' => 'Item 5',
                'ItemsPortfolio' => [
                    'id' => 5,
                    'item_id' => 5,
                    'portfolio_id' => 1,
        ], ], ];
        $this->assertEqual($result['Item'], $expected);

        $result = $Portfolio->ItemsPortfolio->find('all', [
            'conditions' => ['ItemsPortfolio.portfolio_id' => 1],
        ]);
        $expected = [
            [
                'ItemsPortfolio' => [
                    'id' => 1,
                    'item_id' => 1,
                    'portfolio_id' => 1,
            ], ],
            [
                'ItemsPortfolio' => [
                    'id' => 3,
                    'item_id' => 3,
                    'portfolio_id' => 1,
            ], ],
            [
                'ItemsPortfolio' => [
                    'id' => 4,
                    'item_id' => 4,
                    'portfolio_id' => 1,
            ], ],
            [
                'ItemsPortfolio' => [
                    'id' => 5,
                    'item_id' => 5,
                    'portfolio_id' => 1,
        ], ], ];
        $this->assertEqual($result, $expected);

        $Portfolio->delete(1);

        $result = $Portfolio->find('first', [
            'conditions' => ['Portfolio.id' => 1],
        ]);
        $this->assertFalse($result);

        $result = $Portfolio->ItemsPortfolio->find('all', [
            'conditions' => ['ItemsPortfolio.portfolio_id' => 1],
        ]);
        $this->assertFalse($result);
    }

    /**
     * testDeleteArticleBLinks method.
     */
    public function testDeleteArticleBLinks()
    {
        $this->loadFixtures('Article', 'ArticlesTag', 'Tag');
        $TestModel = new ArticleB();

        $result = $TestModel->ArticlesTag->find('all');
        $expected = [
            ['ArticlesTag' => ['article_id' => '1', 'tag_id' => '1']],
            ['ArticlesTag' => ['article_id' => '1', 'tag_id' => '2']],
            ['ArticlesTag' => ['article_id' => '2', 'tag_id' => '1']],
            ['ArticlesTag' => ['article_id' => '2', 'tag_id' => '3']],
            ];
        $this->assertEqual($result, $expected);

        $TestModel->delete(1);
        $result = $TestModel->ArticlesTag->find('all');

        $expected = [
            ['ArticlesTag' => ['article_id' => '2', 'tag_id' => '1']],
            ['ArticlesTag' => ['article_id' => '2', 'tag_id' => '3']],
        ];
        $this->assertEqual($result, $expected);
    }

    /**
     * testDeleteDependentWithConditions method.
     */
    public function testDeleteDependentWithConditions()
    {
        $this->loadFixtures('Cd', 'Book', 'OverallFavorite');

        $Cd = new Cd();
        $Book = new Book();
        $OverallFavorite = new OverallFavorite();

        $Cd->delete(1);

        $result = $OverallFavorite->find('all', [
            'fields' => ['model_type', 'model_id', 'priority'],
        ]);
        $expected = [
            [
                'OverallFavorite' => [
                    'model_type' => 'Book',
                    'model_id' => 1,
                    'priority' => 2,
        ], ], ];

        $this->assertTrue(is_array($result));
        $this->assertEqual($result, $expected);

        $Book->delete(1);

        $result = $OverallFavorite->find('all', [
            'fields' => ['model_type', 'model_id', 'priority'],
        ]);
        $expected = [];

        $this->assertTrue(is_array($result));
        $this->assertEqual($result, $expected);
    }

    /**
     * testDel method.
     */
    public function testDelete()
    {
        $this->loadFixtures('Article');
        $TestModel = new Article();

        $result = $TestModel->delete(2);
        $this->assertTrue($result);

        $result = $TestModel->read(null, 2);
        $this->assertFalse($result);

        $TestModel->recursive = -1;
        $result = $TestModel->find('all', [
            'fields' => ['id', 'title'],
        ]);
        $expected = [
            ['Article' => [
                'id' => 1,
                'title' => 'First Article',
            ]],
            ['Article' => [
                'id' => 3,
                'title' => 'Third Article',
        ]], ];
        $this->assertEqual($result, $expected);

        $result = $TestModel->delete(3);
        $this->assertTrue($result);

        $result = $TestModel->read(null, 3);
        $this->assertFalse($result);

        $TestModel->recursive = -1;
        $result = $TestModel->find('all', [
            'fields' => ['id', 'title'],
        ]);
        $expected = [
            ['Article' => [
                'id' => 1,
                'title' => 'First Article',
        ]], ];

        $this->assertEqual($result, $expected);

        // make sure deleting a non-existent record doesn't break save()
        // ticket #6293
        $this->loadFixtures('Uuid');
        $Uuid = new Uuid();
        $data = [
            'B607DAB9-88A2-46CF-B57C-842CA9E3B3B3',
            '52C8865C-10EE-4302-AE6C-6E7D8E12E2C8',
            '8208C7FE-E89C-47C5-B378-DED6C271F9B8', ];
        foreach ($data as $id) {
            $Uuid->save(['id' => $id]);
        }
        $Uuid->delete('52C8865C-10EE-4302-AE6C-6E7D8E12E2C8');
        $Uuid->delete('52C8865C-10EE-4302-AE6C-6E7D8E12E2C8');
        foreach ($data as $id) {
            $Uuid->save(['id' => $id]);
        }
        $result = $Uuid->find('all', [
            'conditions' => ['id' => $data],
            'fields' => ['id'],
            'order' => 'id', ]);
        $expected = [
            ['Uuid' => [
                'id' => '52C8865C-10EE-4302-AE6C-6E7D8E12E2C8', ]],
            ['Uuid' => [
                'id' => '8208C7FE-E89C-47C5-B378-DED6C271F9B8', ]],
            ['Uuid' => [
                'id' => 'B607DAB9-88A2-46CF-B57C-842CA9E3B3B3', ]], ];
        $this->assertEqual($result, $expected);
    }

    /**
     * test that delete() updates the correct records counterCache() records.
     */
    public function testDeleteUpdatingCounterCacheCorrectly()
    {
        $this->loadFixtures('CounterCacheUser', 'CounterCachePost');
        $User = new CounterCacheUser();

        $User->Post->delete(3);
        $result = $User->read(null, 301);
        $this->assertEqual($result['User']['post_count'], 0);

        $result = $User->read(null, 66);
        $this->assertEqual($result['User']['post_count'], 2);
    }

    /**
     * testDeleteAll method.
     */
    public function testDeleteAll()
    {
        $this->loadFixtures('Article');
        $TestModel = new Article();

        $data = ['Article' => [
            'user_id' => 2,
            'id' => 4,
            'title' => 'Fourth Article',
            'published' => 'N',
        ]];
        $result = $TestModel->set($data) && $TestModel->save();
        $this->assertTrue($result);

        $data = ['Article' => [
            'user_id' => 2,
            'id' => 5,
            'title' => 'Fifth Article',
            'published' => 'Y',
        ]];
        $result = $TestModel->set($data) && $TestModel->save();
        $this->assertTrue($result);

        $data = ['Article' => [
            'user_id' => 1,
            'id' => 6,
            'title' => 'Sixth Article',
            'published' => 'N',
        ]];
        $result = $TestModel->set($data) && $TestModel->save();
        $this->assertTrue($result);

        $TestModel->recursive = -1;
        $result = $TestModel->find('all', [
            'fields' => ['id', 'user_id', 'title', 'published'],
        ]);

        $expected = [
            ['Article' => [
                'id' => 1,
                'user_id' => 1,
                'title' => 'First Article',
                'published' => 'Y',
            ]],
            ['Article' => [
                'id' => 2,
                'user_id' => 3,
                'title' => 'Second Article',
                'published' => 'Y',
            ]],
            ['Article' => [
                'id' => 3,
                'user_id' => 1,
                'title' => 'Third Article',
                'published' => 'Y', ]],
            ['Article' => [
                'id' => 4,
                'user_id' => 2,
                'title' => 'Fourth Article',
                'published' => 'N',
            ]],
            ['Article' => [
                'id' => 5,
                'user_id' => 2,
                'title' => 'Fifth Article',
                'published' => 'Y',
            ]],
            ['Article' => [
                'id' => 6,
                'user_id' => 1,
                'title' => 'Sixth Article',
                'published' => 'N',
        ]], ];

        $this->assertEqual($result, $expected);

        $result = $TestModel->deleteAll(['Article.published' => 'N']);
        $this->assertTrue($result);

        $TestModel->recursive = -1;
        $result = $TestModel->find('all', [
            'fields' => ['id', 'user_id', 'title', 'published'],
        ]);
        $expected = [
            ['Article' => [
                'id' => 1,
                'user_id' => 1,
                'title' => 'First Article',
                'published' => 'Y',
            ]],
            ['Article' => [
                'id' => 2,
                'user_id' => 3,
                'title' => 'Second Article',
                'published' => 'Y',
            ]],
            ['Article' => [
                'id' => 3,
                'user_id' => 1,
                'title' => 'Third Article',
                'published' => 'Y',
            ]],
            ['Article' => [
                'id' => 5,
                'user_id' => 2,
                'title' => 'Fifth Article',
                'published' => 'Y',
        ]], ];
        $this->assertEqual($result, $expected);

        $data = ['Article.user_id' => [2, 3]];
        $result = $TestModel->deleteAll($data, true, true);
        $this->assertTrue($result);

        $TestModel->recursive = -1;
        $result = $TestModel->find('all', [
            'fields' => ['id', 'user_id', 'title', 'published'],
        ]);
        $expected = [
            ['Article' => [
                'id' => 1,
                'user_id' => 1,
                'title' => 'First Article',
                'published' => 'Y',
            ]],
            ['Article' => [
                'id' => 3,
                'user_id' => 1,
                'title' => 'Third Article',
                'published' => 'Y',
        ]], ];
        $this->assertEqual($result, $expected);

        $result = $TestModel->deleteAll(['Article.user_id' => 999]);
        $this->assertTrue($result, 'deleteAll returned false when all no records matched conditions. %s');

        $this->expectError();
        ob_start();
        $result = $TestModel->deleteAll(['Article.non_existent_field' => 999]);
        ob_get_clean();
        $this->assertFalse($result, 'deleteAll returned true when find query generated sql error. %s');
    }

    /**
     * testRecursiveDel method.
     */
    public function testRecursiveDel()
    {
        $this->loadFixtures('Article', 'Comment', 'Attachment');
        $TestModel = new Article();

        $result = $TestModel->delete(2);
        $this->assertTrue($result);

        $TestModel->recursive = 2;
        $result = $TestModel->read(null, 2);
        $this->assertFalse($result);

        $result = $TestModel->Comment->read(null, 5);
        $this->assertFalse($result);

        $result = $TestModel->Comment->read(null, 6);
        $this->assertFalse($result);

        $result = $TestModel->Comment->Attachment->read(null, 1);
        $this->assertFalse($result);

        $result = $TestModel->find('count');
        $this->assertEqual($result, 2);

        $result = $TestModel->Comment->find('count');
        $this->assertEqual($result, 4);

        $result = $TestModel->Comment->Attachment->find('count');
        $this->assertEqual($result, 0);
    }

    /**
     * testDependentExclusiveDelete method.
     */
    public function testDependentExclusiveDelete()
    {
        $this->loadFixtures('Article', 'Comment');
        $TestModel = new Article10();

        $result = $TestModel->find('all');
        $this->assertEqual(count($result[0]['Comment']), 4);
        $this->assertEqual(count($result[1]['Comment']), 2);
        $this->assertEqual($TestModel->Comment->find('count'), 6);

        $TestModel->delete(1);
        $this->assertEqual($TestModel->Comment->find('count'), 2);
    }

    /**
     * testDeleteLinks method.
     */
    public function testDeleteLinks()
    {
        $this->loadFixtures('Article', 'ArticlesTag', 'Tag');
        $TestModel = new Article();

        $result = $TestModel->ArticlesTag->find('all');
        $expected = [
            ['ArticlesTag' => [
                'article_id' => '1',
                'tag_id' => '1',
            ]],
            ['ArticlesTag' => [
                'article_id' => '1',
                'tag_id' => '2',
            ]],
            ['ArticlesTag' => [
                'article_id' => '2',
                'tag_id' => '1',
            ]],
            ['ArticlesTag' => [
                'article_id' => '2',
                'tag_id' => '3',
        ]], ];
        $this->assertEqual($result, $expected);

        $TestModel->delete(1);
        $result = $TestModel->ArticlesTag->find('all');

        $expected = [
            ['ArticlesTag' => [
                'article_id' => '2',
                'tag_id' => '1',
            ]],
            ['ArticlesTag' => [
                'article_id' => '2',
                'tag_id' => '3',
        ]], ];
        $this->assertEqual($result, $expected);

        $result = $TestModel->deleteAll(['Article.user_id' => 999]);
        $this->assertTrue($result, 'deleteAll returned false when all no records matched conditions. %s');
    }

    /**
     * testDeleteDependent method.
     */
    public function testDeleteDependent()
    {
        $this->loadFixtures('Bidding', 'BiddingMessage');
        $Bidding = new Bidding();
        $result = $Bidding->find('all');
        $expected = [
            [
                'Bidding' => ['id' => 1, 'bid' => 'One', 'name' => 'Bid 1'],
                'BiddingMessage' => ['bidding' => 'One', 'name' => 'Message 1'],
            ],
            [
                'Bidding' => ['id' => 2, 'bid' => 'Two', 'name' => 'Bid 2'],
                'BiddingMessage' => ['bidding' => 'Two', 'name' => 'Message 2'],
            ],
            [
                'Bidding' => ['id' => 3, 'bid' => 'Three', 'name' => 'Bid 3'],
                'BiddingMessage' => ['bidding' => 'Three', 'name' => 'Message 3'],
            ],
            [
                'Bidding' => ['id' => 4, 'bid' => 'Five', 'name' => 'Bid 5'],
                'BiddingMessage' => ['bidding' => '', 'name' => ''],
            ],
        ];
        $this->assertEqual($result, $expected);

        $Bidding->delete(4, true);
        $result = $Bidding->find('all');
        $expected = [
            [
                'Bidding' => ['id' => 1, 'bid' => 'One', 'name' => 'Bid 1'],
                'BiddingMessage' => ['bidding' => 'One', 'name' => 'Message 1'],
            ],
            [
                'Bidding' => ['id' => 2, 'bid' => 'Two', 'name' => 'Bid 2'],
                'BiddingMessage' => ['bidding' => 'Two', 'name' => 'Message 2'],
            ],
            [
                'Bidding' => ['id' => 3, 'bid' => 'Three', 'name' => 'Bid 3'],
                'BiddingMessage' => ['bidding' => 'Three', 'name' => 'Message 3'],
            ],
        ];
        $this->assertEqual($result, $expected);

        $Bidding->delete(2, true);
        $result = $Bidding->find('all');
        $expected = [
            [
                'Bidding' => ['id' => 1, 'bid' => 'One', 'name' => 'Bid 1'],
                'BiddingMessage' => ['bidding' => 'One', 'name' => 'Message 1'],
            ],
            [
                'Bidding' => ['id' => 3, 'bid' => 'Three', 'name' => 'Bid 3'],
                'BiddingMessage' => ['bidding' => 'Three', 'name' => 'Message 3'],
            ],
        ];
        $this->assertEqual($result, $expected);

        $result = $Bidding->BiddingMessage->find('all', ['order' => ['BiddingMessage.name' => 'ASC']]);
        $expected = [
            [
                'BiddingMessage' => ['bidding' => 'One', 'name' => 'Message 1'],
                'Bidding' => ['id' => 1, 'bid' => 'One', 'name' => 'Bid 1'],
            ],
            [
                'BiddingMessage' => ['bidding' => 'Three', 'name' => 'Message 3'],
                'Bidding' => ['id' => 3, 'bid' => 'Three', 'name' => 'Bid 3'],
            ],
            [
                'BiddingMessage' => ['bidding' => 'Four', 'name' => 'Message 4'],
                'Bidding' => ['id' => '', 'bid' => '', 'name' => ''],
            ],
        ];
        $this->assertEqual($result, $expected);
    }

    /**
     * test deleteLinks with Multiple habtm associations.
     */
    public function testDeleteLinksWithMultipleHabtmAssociations()
    {
        $this->loadFixtures('JoinA', 'JoinB', 'JoinC', 'JoinAB', 'JoinAC');
        $JoinA = new JoinA();

        //create two new join records to expose the issue.
        $JoinA->JoinAsJoinC->create([
            'join_a_id' => 1,
            'join_c_id' => 2,
        ]);
        $JoinA->JoinAsJoinC->save();
        $JoinA->JoinAsJoinB->create([
            'join_a_id' => 1,
            'join_b_id' => 2,
        ]);
        $JoinA->JoinAsJoinB->save();

        $result = $JoinA->delete(1);
        $this->assertTrue($result, 'Delete failed %s');

        $joinedBs = $JoinA->JoinAsJoinB->find('count', [
            'conditions' => ['JoinAsJoinB.join_a_id' => 1],
        ]);
        $this->assertEqual($joinedBs, 0, 'JoinA/JoinB link records left over. %s');

        $joinedBs = $JoinA->JoinAsJoinC->find('count', [
            'conditions' => ['JoinAsJoinC.join_a_id' => 1],
        ]);
        $this->assertEqual($joinedBs, 0, 'JoinA/JoinC link records left over. %s');
    }

    /**
     * testHabtmDeleteLinksWhenNoPrimaryKeyInJoinTable method.
     */
    public function testHabtmDeleteLinksWhenNoPrimaryKeyInJoinTable()
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

        $ThePaper = new ThePaper();
        $ThePaper->id = 2;
        $ThePaper->save(['Monkey' => [2, 3]]);

        $result = $ThePaper->findById(2);
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

        $ThePaper->delete(1);
        $result = $ThePaper->findById(2);
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
     * test that beforeDelete returning false can abort deletion.
     */
    public function testBeforeDeleteDeleteAbortion()
    {
        $this->loadFixtures('Post');
        $Model = new CallbackPostTestModel();
        $Model->beforeDeleteReturn = false;

        $result = $Model->delete(1);
        $this->assertFalse($result);

        $exists = $Model->findById(1);
        $this->assertTrue(is_array($exists));
    }

    /**
     * test for a habtm deletion error that occurs in postgres but should not.
     * And should not occur in any dbo.
     */
    public function testDeleteHabtmPostgresFailure()
    {
        $this->loadFixtures('Article', 'Tag', 'ArticlesTag');

        $Article = &ClassRegistry::init('Article');
        $Article->hasAndBelongsToMany['Tag']['unique'] = true;

        $Tag = &ClassRegistry::init('Tag');
        $Tag->bindModel(['hasAndBelongsToMany' => [
            'Article' => [
                'className' => 'Article',
                'unique' => true,
            ],
        ]], true);

        // Article 1 should have Tag.1 and Tag.2
        $before = $Article->find('all', [
            'conditions' => ['Article.id' => 1],
        ]);
        $this->assertEqual(count($before[0]['Tag']), 2, 'Tag count for Article.id = 1 is incorrect, should be 2 %s');

        // From now on, Tag #1 is only associated with Post #1
        $submitted_data = [
            'Tag' => ['id' => 1, 'tag' => 'tag1'],
            'Article' => [
                'Article' => [1],
            ],
        ];
        $Tag->save($submitted_data);

        // One more submission (The other way around) to make sure the reverse save looks good.
        $submitted_data = [
            'Article' => ['id' => 2, 'title' => 'second article'],
            'Tag' => [
                'Tag' => [2, 3],
            ],
        ];
        // ERROR:
        // Postgresql: DELETE FROM "articles_tags" WHERE tag_id IN ('1', '3')
        // MySQL: DELETE `ArticlesTag` FROM `articles_tags` AS `ArticlesTag` WHERE `ArticlesTag`.`article_id` = 2 AND `ArticlesTag`.`tag_id` IN (1, 3)
        $Article->save($submitted_data);

        // Want to make sure Article #1 has Tag #1 and Tag #2 still.
        $after = $Article->find('all', [
            'conditions' => ['Article.id' => 1],
        ]);

        // Removing Article #2 from Tag #1 is all that should have happened.
        $this->assertEqual(count($before[0]['Tag']), count($after[0]['Tag']));
    }

    /**
     * test that deleting records inside the beforeDelete doesn't truncate the table.
     */
    public function testBeforeDeleteWipingTable()
    {
        $this->loadFixtures('Comment');

        $Comment = new BeforeDeleteComment();
        // Delete 3 records.
        $Comment->delete(4);
        $result = $Comment->find('count');

        $this->assertTrue($result > 1, 'Comments are all gone.');
        $Comment->create([
            'article_id' => 1,
            'user_id' => 2,
            'comment' => 'new record',
            'published' => 'Y',
        ]);
        $Comment->save();

        $Comment->delete(5);
        $result = $Comment->find('count');

        $this->assertTrue($result > 1, 'Comments are all gone.');
    }

    /**
     * test that deleting the same record from the beforeDelete and the delete doesn't truncate the table.
     */
    public function testBeforeDeleteWipingTableWithDuplicateDelete()
    {
        $this->loadFixtures('Comment');

        $Comment = new BeforeDeleteComment();
        $Comment->delete(1);

        $result = $Comment->find('count');
        $this->assertTrue($result > 1, 'Comments are all gone.');
    }
}
