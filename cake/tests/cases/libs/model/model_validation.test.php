<?php

/* SVN FILE: $Id: model.test.php 8225 2009-07-08 03:25:30Z mark_story $ */

/**
 * ModelValidationTest file.
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
 * ModelValidationTest.
 */
class ModelValidationTest extends BaseModelTest
{
    /**
     * Tests validation parameter order in custom validation methods.
     */
    public function testValidationParams()
    {
        $TestModel = new ValidationTest1();
        $TestModel->validate['title'] = [
            'rule' => 'customValidatorWithParams',
            'required' => true,
        ];
        $TestModel->create(['title' => 'foo']);
        $TestModel->invalidFields();

        $expected = [
            'data' => [
                'title' => 'foo',
            ],
            'validator' => [
                'rule' => 'customValidatorWithParams',
                'on' => null,
                'last' => false,
                'allowEmpty' => false,
                'required' => true,
            ],
            'or' => true,
            'ignore_on_same' => 'id',
        ];
        $this->assertEqual($TestModel->validatorParams, $expected);

        $TestModel->validate['title'] = [
            'rule' => 'customValidatorWithMessage',
            'required' => true,
        ];
        $expected = [
            'title' => 'This field will *never* validate! Muhahaha!',
        ];

        $this->assertEqual($TestModel->invalidFields(), $expected);

        $TestModel->validate['title'] = [
            'rule' => ['customValidatorWithSixParams', 'one', 'two', null, 'four'],
            'required' => true,
        ];
        $TestModel->create(['title' => 'foo']);
        $TestModel->invalidFields();
        $expected = [
            'data' => [
                'title' => 'foo',
            ],
            'one' => 'one',
            'two' => 'two',
            'three' => null,
            'four' => 'four',
            'five' => [
                'rule' => [1 => 'one', 2 => 'two', 3 => null, 4 => 'four'],
                'on' => null,
                'last' => false,
                'allowEmpty' => false,
                'required' => true,
            ],
            'six' => 6,
        ];
        $this->assertEqual($TestModel->validatorParams, $expected);

        $TestModel->validate['title'] = [
            'rule' => ['customValidatorWithSixParams', 'one', ['two'], null, 'four', ['five' => 5]],
            'required' => true,
        ];
        $TestModel->create(['title' => 'foo']);
        $TestModel->invalidFields();
        $expected = [
            'data' => [
                'title' => 'foo',
            ],
            'one' => 'one',
            'two' => ['two'],
            'three' => null,
            'four' => 'four',
            'five' => ['five' => 5],
            'six' => [
                'rule' => [1 => 'one', 2 => ['two'], 3 => null, 4 => 'four', 5 => ['five' => 5]],
                'on' => null,
                'last' => false,
                'allowEmpty' => false,
                'required' => true,
            ],
        ];
        $this->assertEqual($TestModel->validatorParams, $expected);
    }

    /**
     * Tests validation parameter fieldList in invalidFields.
     */
    public function testInvalidFieldsWithFieldListParams()
    {
        $TestModel = new ValidationTest1();
        $TestModel->validate = $validate = [
            'title' => [
                'rule' => 'alphaNumeric',
                'required' => true,
            ],
            'name' => [
                'rule' => 'alphaNumeric',
                'required' => true,
        ], ];
        $TestModel->set(['title' => '$$', 'name' => '##']);
        $TestModel->invalidFields(['fieldList' => ['title']]);
        $expected = [
            'title' => 'This field cannot be left blank',
        ];
        $this->assertEqual($TestModel->validationErrors, $expected);
        $TestModel->validationErrors = [];

        $TestModel->invalidFields(['fieldList' => ['name']]);
        $expected = [
            'name' => 'This field cannot be left blank',
        ];
        $this->assertEqual($TestModel->validationErrors, $expected);
        $TestModel->validationErrors = [];

        $TestModel->invalidFields(['fieldList' => ['name', 'title']]);
        $expected = [
            'name' => 'This field cannot be left blank',
            'title' => 'This field cannot be left blank',
        ];
        $this->assertEqual($TestModel->validationErrors, $expected);
        $TestModel->validationErrors = [];

        $TestModel->whitelist = ['name'];
        $TestModel->invalidFields();
        $expected = ['name' => 'This field cannot be left blank'];
        $this->assertEqual($TestModel->validationErrors, $expected);

        $this->assertEqual($TestModel->validate, $validate);
    }

    /**
     * Test that invalidFields() integrates well with save().  And that fieldList can be an empty type.
     */
    public function testInvalidFieldsWhitelist()
    {
        $TestModel = new ValidationTest1();
        $TestModel->validate = [
            'title' => [
                'rule' => 'alphaNumeric',
                'required' => true,
            ],
            'name' => [
                'rule' => 'alphaNumeric',
                'required' => true,
        ], ];

        $TestModel->whitelist = ['name'];
        $TestModel->save(['name' => '#$$#', 'title' => '$$$$']);

        $expected = ['name' => 'This field cannot be left blank'];
        $this->assertEqual($TestModel->validationErrors, $expected);
    }

    /**
     * testValidates method.
     */
    public function testValidates()
    {
        $TestModel = new TestValidate();

        $TestModel->validate = [
            'user_id' => 'numeric',
            'title' => ['allowEmpty' => false, 'rule' => 'notEmpty'],
            'body' => 'notEmpty',
        ];

        $data = ['TestValidate' => [
            'user_id' => '1',
            'title' => '',
            'body' => 'body',
        ]];
        $result = $TestModel->create($data);
        $this->assertTrue($result);
        $result = $TestModel->validates();
        $this->assertFalse($result);

        $data = ['TestValidate' => [
            'user_id' => '1',
            'title' => 'title',
            'body' => 'body',
        ]];
        $result = $TestModel->create($data) && $TestModel->validates();
        $this->assertTrue($result);

        $data = ['TestValidate' => [
            'user_id' => '1',
            'title' => '0',
            'body' => 'body',
        ]];
        $result = $TestModel->create($data);
        $this->assertTrue($result);
        $result = $TestModel->validates();
        $this->assertTrue($result);

        $data = ['TestValidate' => [
            'user_id' => '1',
            'title' => 0,
            'body' => 'body',
        ]];
        $result = $TestModel->create($data);
        $this->assertTrue($result);
        $result = $TestModel->validates();
        $this->assertTrue($result);

        $TestModel->validate['modified'] = ['allowEmpty' => true, 'rule' => 'date'];

        $data = ['TestValidate' => [
            'user_id' => '1',
            'title' => 0,
            'body' => 'body',
            'modified' => '',
        ]];
        $result = $TestModel->create($data);
        $this->assertTrue($result);
        $result = $TestModel->validates();
        $this->assertTrue($result);

        $data = ['TestValidate' => [
            'user_id' => '1',
            'title' => 0,
            'body' => 'body',
            'modified' => '2007-05-01',
        ]];
        $result = $TestModel->create($data);
        $this->assertTrue($result);
        $result = $TestModel->validates();
        $this->assertTrue($result);

        $data = ['TestValidate' => [
            'user_id' => '1',
            'title' => 0,
            'body' => 'body',
            'modified' => 'invalid-date-here',
        ]];
        $result = $TestModel->create($data);
        $this->assertTrue($result);
        $result = $TestModel->validates();
        $this->assertFalse($result);

        $data = ['TestValidate' => [
            'user_id' => '1',
            'title' => 0,
            'body' => 'body',
            'modified' => 0,
        ]];
        $result = $TestModel->create($data);
        $this->assertTrue($result);
        $result = $TestModel->validates();
        $this->assertFalse($result);

        $data = ['TestValidate' => [
            'user_id' => '1',
            'title' => 0,
            'body' => 'body',
            'modified' => '0',
        ]];
        $result = $TestModel->create($data);
        $this->assertTrue($result);
        $result = $TestModel->validates();
        $this->assertFalse($result);

        $TestModel->validate['modified'] = ['allowEmpty' => false, 'rule' => 'date'];

        $data = ['TestValidate' => ['modified' => null]];
        $result = $TestModel->create($data);
        $this->assertTrue($result);
        $result = $TestModel->validates();
        $this->assertFalse($result);

        $data = ['TestValidate' => ['modified' => false]];
        $result = $TestModel->create($data);
        $this->assertTrue($result);
        $result = $TestModel->validates();
        $this->assertFalse($result);

        $data = ['TestValidate' => ['modified' => '']];
        $result = $TestModel->create($data);
        $this->assertTrue($result);
        $result = $TestModel->validates();
        $this->assertFalse($result);

        $data = ['TestValidate' => [
            'modified' => '2007-05-01',
        ]];
        $result = $TestModel->create($data);
        $this->assertTrue($result);
        $result = $TestModel->validates();
        $this->assertTrue($result);

        $TestModel->validate['slug'] = ['allowEmpty' => false, 'rule' => ['maxLength', 45]];

        $data = ['TestValidate' => [
            'user_id' => '1',
            'title' => 0,
            'body' => 'body',
            'slug' => '',
        ]];
        $result = $TestModel->create($data);
        $this->assertTrue($result);
        $result = $TestModel->validates();
        $this->assertFalse($result);

        $data = ['TestValidate' => [
            'user_id' => '1',
            'title' => 0,
            'body' => 'body',
            'slug' => 'slug-right-here',
        ]];
        $result = $TestModel->create($data);
        $this->assertTrue($result);
        $result = $TestModel->validates();
        $this->assertTrue($result);

        $data = ['TestValidate' => [
            'user_id' => '1',
            'title' => 0,
            'body' => 'body',
            'slug' => 'abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz',
        ]];
        $result = $TestModel->create($data);
        $this->assertTrue($result);
        $result = $TestModel->validates();
        $this->assertFalse($result);

        $TestModel->validate = [
            'number' => [
                'rule' => 'validateNumber',
                'min' => 3,
                'max' => 5,
            ],
            'title' => [
                'allowEmpty' => false,
                'rule' => 'notEmpty',
        ], ];

        $data = ['TestValidate' => [
            'title' => 'title',
            'number' => '0',
        ]];
        $result = $TestModel->create($data);
        $this->assertTrue($result);
        $result = $TestModel->validates();
        $this->assertFalse($result);

        $data = ['TestValidate' => [
            'title' => 'title',
            'number' => 0,
        ]];
        $result = $TestModel->create($data);
        $this->assertTrue($result);
        $result = $TestModel->validates();
        $this->assertFalse($result);

        $data = ['TestValidate' => [
            'title' => 'title',
            'number' => '3',
        ]];
        $result = $TestModel->create($data);
        $this->assertTrue($result);
        $result = $TestModel->validates();
        $this->assertTrue($result);

        $data = ['TestValidate' => [
            'title' => 'title',
            'number' => 3,
        ]];
        $result = $TestModel->create($data);
        $this->assertTrue($result);
        $result = $TestModel->validates();
        $this->assertTrue($result);

        $TestModel->validate = [
            'number' => [
                'rule' => 'validateNumber',
                'min' => 5,
                'max' => 10,
            ],
            'title' => [
                'allowEmpty' => false,
                'rule' => 'notEmpty',
        ], ];

        $data = ['TestValidate' => [
            'title' => 'title',
            'number' => '3',
        ]];
        $result = $TestModel->create($data);
        $this->assertTrue($result);
        $result = $TestModel->validates();
        $this->assertFalse($result);

        $data = ['TestValidate' => [
            'title' => 'title',
            'number' => 3,
        ]];
        $result = $TestModel->create($data);
        $this->assertTrue($result);
        $result = $TestModel->validates();
        $this->assertFalse($result);

        $TestModel->validate = [
            'title' => [
                'allowEmpty' => false,
                'rule' => 'validateTitle',
        ], ];

        $data = ['TestValidate' => ['title' => '']];
        $result = $TestModel->create($data);
        $this->assertTrue($result);
        $result = $TestModel->validates();
        $this->assertFalse($result);

        $data = ['TestValidate' => ['title' => 'new title']];
        $result = $TestModel->create($data);
        $this->assertTrue($result);
        $result = $TestModel->validates();
        $this->assertFalse($result);

        $data = ['TestValidate' => ['title' => 'title-new']];
        $result = $TestModel->create($data);
        $this->assertTrue($result);
        $result = $TestModel->validates();
        $this->assertTrue($result);

        $TestModel->validate = ['title' => [
            'allowEmpty' => true,
            'rule' => 'validateTitle',
        ]];
        $data = ['TestValidate' => ['title' => '']];
        $result = $TestModel->create($data);
        $this->assertTrue($result);
        $result = $TestModel->validates();
        $this->assertTrue($result);

        $TestModel->validate = [
            'title' => [
                'length' => [
                    'allowEmpty' => true,
                    'rule' => ['maxLength', 10],
        ], ], ];
        $data = ['TestValidate' => ['title' => '']];
        $result = $TestModel->create($data);
        $this->assertTrue($result);
        $result = $TestModel->validates();
        $this->assertTrue($result);

        $TestModel->validate = [
            'title' => [
                'rule' => ['userDefined', 'Article', 'titleDuplicate'],
        ], ];
        $data = ['TestValidate' => ['title' => 'My Article Title']];
        $result = $TestModel->create($data);
        $this->assertTrue($result);
        $result = $TestModel->validates();
        $this->assertFalse($result);

        $data = ['TestValidate' => [
            'title' => 'My Article With a Different Title',
        ]];
        $result = $TestModel->create($data);
        $this->assertTrue($result);
        $result = $TestModel->validates();
        $this->assertTrue($result);

        $TestModel->validate = [
            'title' => [
                'tooShort' => ['rule' => ['minLength', 50]],
                'onlyLetters' => ['rule' => '/^[a-z]+$/i'],
            ],
        ];
        $data = ['TestValidate' => [
            'title' => 'I am a short string',
        ]];
        $TestModel->create($data);
        $result = $TestModel->validates();
        $this->assertFalse($result);
        $result = $TestModel->validationErrors;
        $expected = [
            'title' => 'onlyLetters',
        ];
        $this->assertEqual($result, $expected);

        $TestModel->validate = [
            'title' => [
                'tooShort' => [
                    'rule' => ['minLength', 50],
                    'last' => true,
                ],
                'onlyLetters' => ['rule' => '/^[a-z]+$/i'],
            ],
        ];
        $data = ['TestValidate' => [
            'title' => 'I am a short string',
        ]];
        $TestModel->create($data);
        $result = $TestModel->validates();
        $this->assertFalse($result);
        $result = $TestModel->validationErrors;
        $expected = [
            'title' => 'tooShort',
        ];
        $this->assertEqual($result, $expected);
    }

    /**
     * test that validates() checks all the 'with' associations as well for validation
     * as this can cause partial/wrong data insertion.
     */
    public function testValidatesWithAssociations()
    {
        $data = [
            'Something' => [
                'id' => 5,
                'title' => 'Extra Fields',
                'body' => 'Extra Fields Body',
                'published' => '1',
            ],
            'SomethingElse' => [
                ['something_else_id' => 1, 'doomed' => ''],
            ],
        ];

        $Something = new Something();
        $JoinThing = &$Something->JoinThing;

        $JoinThing->validate = ['doomed' => ['rule' => 'notEmpty']];

        $expectedError = ['doomed' => 'This field cannot be left blank'];

        $Something->create();
        $result = $Something->save($data);
        $this->assertFalse($result, 'Save occured even when with models failed. %s');
        $this->assertEqual($JoinThing->validationErrors, $expectedError);
        $count = $Something->find('count', ['conditions' => ['Something.id' => $data['Something']['id']]]);
        $this->assertIdentical($count, 0);

        $data = [
            'Something' => [
                'id' => 5,
                'title' => 'Extra Fields',
                'body' => 'Extra Fields Body',
                'published' => '1',
            ],
            'SomethingElse' => [
                ['something_else_id' => 1, 'doomed' => 1],
                ['something_else_id' => 1, 'doomed' => ''],
            ],
        ];
        $Something->create();
        $result = $Something->save($data);
        $this->assertFalse($result, 'Save occured even when with models failed. %s');

        $joinRecords = $JoinThing->find('count', [
            'conditions' => ['JoinThing.something_id' => $data['Something']['id']],
        ]);
        $this->assertEqual($joinRecords, 0, 'Records were saved on the join table. %s');
    }

    /**
     * test that saveAll and with models with validation interact well.
     */
    public function testValidatesWithModelsAndSaveAll()
    {
        $data = [
            'Something' => [
                'id' => 5,
                'title' => 'Extra Fields',
                'body' => 'Extra Fields Body',
                'published' => '1',
            ],
            'SomethingElse' => [
                ['something_else_id' => 1, 'doomed' => ''],
            ],
        ];
        $Something = new Something();
        $JoinThing = &$Something->JoinThing;

        $JoinThing->validate = ['doomed' => ['rule' => 'notEmpty']];
        $expectedError = ['doomed' => 'This field cannot be left blank'];

        $Something->create();
        $result = $Something->saveAll($data, ['validate' => 'only']);
        $this->assertFalse($result);
        $this->assertEqual($JoinThing->validationErrors, $expectedError);

        $Something->create();
        $result = $Something->saveAll($data, ['validate' => 'first']);
        $this->assertFalse($result);
        $this->assertEqual($JoinThing->validationErrors, $expectedError);

        $count = $Something->find('count', ['conditions' => ['Something.id' => $data['Something']['id']]]);
        $this->assertIdentical($count, 0);

        $joinRecords = $JoinThing->find('count', [
            'conditions' => ['JoinThing.something_id' => $data['Something']['id']],
        ]);
        $this->assertEqual($joinRecords, 0, 'Records were saved on the join table. %s');
    }

    /**
     * test that saveAll and with models at initial insert (no id has set yet)
     * with validation interact well.
     */
    public function testValidatesWithModelsAndSaveAllWithoutId()
    {
        $data = [
            'Article' => [
                'title' => 'Extra Fields',
                'body' => 'Extra Fields Body',
                'published' => '1',
            ],
            'Comment' => [
                ['word' => 'Hello'],
                ['word' => 'World'],
            ],
        ];
        $Article = new Article();
        $Comment = &$Article->Comment;

        $Comment->validate = ['article_id' => ['rule' => 'numeric']];

        $Article->create();
        $result = $Article->saveAll($data, ['validate' => 'only']);
        $this->assertTrue($result);

        $Article->create();
        $result = $Article->saveAll($data, ['validate' => 'first']);
        $this->assertTrue($result);
        $this->assertFalse(is_null($Article->id));

        $id = $Article->id;
        $count = $Article->find('count', ['conditions' => ['Article.id' => $id]]);
        $this->assertIdentical($count, 1);

        $count = $Comment->find('count', [
            'conditions' => ['Comment.article_id' => $id],
        ]);
        $this->assertEqual($count, count($data['Comment']));
    }

    /**
     * Test that missing validation methods trigger errors in development mode.
     * Helps to make developement easier.
     */
    public function testMissingValidationErrorTriggering()
    {
        $restore = Configure::read('debug');
        Configure::write('debug', 2);

        $TestModel = new ValidationTest1();
        $TestModel->create(['title' => 'foo']);
        $TestModel->validate = [
            'title' => [
                'rule' => ['thisOneBringsThePain'],
                'required' => true,
            ],
        ];
        $this->expectError(new PatternExpectation('/thisOneBringsThePain for title/i'));
        $TestModel->invalidFields(['fieldList' => ['title']]);

        Configure::write('debug', 0);
        $this->assertNoErrors();
        $TestModel->invalidFields(['fieldList' => ['title']]);
        Configure::write('debug', $restore);
    }

    /**
     * Test for 'on' => [create|update] in validation rules.
     */
    public function testStateValidation()
    {
        $Article = new Article();

        $data = [
            'Article' => [
                'title' => '',
                'body' => 'Extra Fields Body',
                'published' => '1',
            ],
        ];

        $Article->validate = [
            'title' => [
                'notempty' => [
                    'rule' => 'notEmpty',
                    'on' => 'create',
                ],
            ],
            'published' => [
                'notempty' => [
                    'rule' => 'notEmpty',
                ],
            ],
        ];

        $Article->create($data);
        $this->assertFalse($Article->validates());

        $Article->save(null, ['validate' => false]);
        $data['Article']['id'] = $Article->id;
        $Article->set($data);
        $this->assertTrue($Article->validates());

        $Article->data['Article']['published'] = null;
        $this->assertFalse($Article->validates());

        unset($data['Article']['id']);
        $Article->data['Article']['published'] = '1';
        $Article->validate = [
            'title' => [
                'notempty' => [
                    'rule' => 'notEmpty',
                    'on' => 'update',
                ],
            ],
            'published' => [
                'notempty' => [
                    'rule' => 'notEmpty',
                ],
            ],
        ];

        $Article->create($data);
        $this->assertTrue($Article->validates());

        $Article->save(null, ['validate' => false]);
        $data['Article']['id'] = $Article->id;
        $Article->set($data);
        $this->assertFalse($Article->validates());
    }
}
