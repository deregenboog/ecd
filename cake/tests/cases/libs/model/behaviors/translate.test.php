<?php
/**
 * TranslateBehaviorTest file.
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
 * TranslateBehaviorTest class.
 */
class TranslateBehaviorTest extends CakeTestCase
{
    /**
     * autoFixtures property.
     *
     * @var bool false
     */
    public $autoFixtures = false;

    /**
     * fixtures property.
     *
     * @var array
     */
    public $fixtures = [
        'core.translated_item', 'core.translate', 'core.translate_table',
        'core.translated_article', 'core.translate_article', 'core.user', 'core.comment', 'core.tag', 'core.articles_tag',
        'core.translate_with_prefix',
    ];

    /**
     * endTest method.
     */
    public function endTest()
    {
        ClassRegistry::flush();
    }

    /**
     * Test that count queries with conditions get the correct joins.
     */
    public function testCountWithConditions()
    {
        $this->loadFixtures('Translate', 'TranslatedItem');

        $Model = new TranslatedItem();
        $Model->locale = 'eng';
        $result = $Model->find('count', [
            'conditions' => [
                'I18n__content.locale' => 'eng',
            ],
        ]);
        $this->assertEqual(3, $result);
    }

    /**
     * testTranslateModel method.
     */
    public function testTranslateModel()
    {
        $TestModel = new Tag();
        $TestModel->translateTable = 'another_i18n';
        $TestModel->Behaviors->attach('Translate', ['title']);
        $translateModel = &$TestModel->Behaviors->Translate->translateModel($TestModel);
        $this->assertEqual($translateModel->name, 'I18nModel');
        $this->assertEqual($translateModel->useTable, 'another_i18n');

        $TestModel = new User();
        $TestModel->Behaviors->attach('Translate', ['title']);
        $translateModel = &$TestModel->Behaviors->Translate->translateModel($TestModel);
        $this->assertEqual($translateModel->name, 'I18nModel');
        $this->assertEqual($translateModel->useTable, 'i18n');

        $TestModel = new TranslatedArticle();
        $translateModel = &$TestModel->Behaviors->Translate->translateModel($TestModel);
        $this->assertEqual($translateModel->name, 'TranslateArticleModel');
        $this->assertEqual($translateModel->useTable, 'article_i18n');

        $TestModel = new TranslatedItem();
        $translateModel = &$TestModel->Behaviors->Translate->translateModel($TestModel);
        $this->assertEqual($translateModel->name, 'TranslateTestModel');
        $this->assertEqual($translateModel->useTable, 'i18n');
    }

    /**
     * testLocaleFalsePlain method.
     */
    public function testLocaleFalsePlain()
    {
        $this->loadFixtures('Translate', 'TranslatedItem');

        $TestModel = new TranslatedItem();
        $TestModel->locale = false;

        $result = $TestModel->read(null, 1);
        $expected = ['TranslatedItem' => [
            'id' => 1,
            'slug' => 'first_translated',
            'translated_article_id' => 1,
        ]];
        $this->assertEqual($expected, $result);

        $result = $TestModel->find('all', ['fields' => ['slug']]);
        $expected = [
            ['TranslatedItem' => ['slug' => 'first_translated']],
            ['TranslatedItem' => ['slug' => 'second_translated']],
            ['TranslatedItem' => ['slug' => 'third_translated']],
        ];
        $this->assertEqual($result, $expected);
    }

    /**
     * testLocaleFalseAssociations method.
     */
    public function testLocaleFalseAssociations()
    {
        $this->loadFixtures('Translate', 'TranslatedItem');

        $TestModel = new TranslatedItem();
        $TestModel->locale = false;
        $TestModel->unbindTranslation();
        $translations = ['title' => 'Title', 'content' => 'Content'];
        $TestModel->bindTranslation($translations, false);

        $result = $TestModel->read(null, 1);
        $expected = [
            'TranslatedItem' => ['id' => 1, 'slug' => 'first_translated', 'translated_article_id' => 1],
            'Title' => [
                ['id' => 1, 'locale' => 'eng', 'model' => 'TranslatedItem', 'foreign_key' => 1, 'field' => 'title', 'content' => 'Title #1'],
                ['id' => 3, 'locale' => 'deu', 'model' => 'TranslatedItem', 'foreign_key' => 1, 'field' => 'title', 'content' => 'Titel #1'],
                ['id' => 5, 'locale' => 'cze', 'model' => 'TranslatedItem', 'foreign_key' => 1, 'field' => 'title', 'content' => 'Titulek #1'],
            ],
            'Content' => [
                ['id' => 2, 'locale' => 'eng', 'model' => 'TranslatedItem', 'foreign_key' => 1, 'field' => 'content', 'content' => 'Content #1'],
                ['id' => 4, 'locale' => 'deu', 'model' => 'TranslatedItem', 'foreign_key' => 1, 'field' => 'content', 'content' => 'Inhalt #1'],
                ['id' => 6, 'locale' => 'cze', 'model' => 'TranslatedItem', 'foreign_key' => 1, 'field' => 'content', 'content' => 'Obsah #1'],
            ],
        ];
        $this->assertEqual($result, $expected);

        $TestModel->hasMany['Title']['fields'] = $TestModel->hasMany['Content']['fields'] = ['content'];
        $TestModel->hasMany['Title']['conditions']['locale'] = $TestModel->hasMany['Content']['conditions']['locale'] = 'eng';

        $result = $TestModel->find('all', ['fields' => ['TranslatedItem.slug']]);
        $expected = [
            [
                'TranslatedItem' => ['id' => 1, 'slug' => 'first_translated'],
                'Title' => [['foreign_key' => 1, 'content' => 'Title #1']],
                'Content' => [['foreign_key' => 1, 'content' => 'Content #1']],
            ],
            [
                'TranslatedItem' => ['id' => 2, 'slug' => 'second_translated'],
                'Title' => [['foreign_key' => 2, 'content' => 'Title #2']],
                'Content' => [['foreign_key' => 2, 'content' => 'Content #2']],
            ],
            [
                'TranslatedItem' => ['id' => 3, 'slug' => 'third_translated'],
                'Title' => [['foreign_key' => 3, 'content' => 'Title #3']],
                'Content' => [['foreign_key' => 3, 'content' => 'Content #3']],
            ],
        ];
        $this->assertEqual($result, $expected);
    }

    /**
     * testLocaleSingle method.
     */
    public function testLocaleSingle()
    {
        $this->loadFixtures('Translate', 'TranslatedItem');

        $TestModel = new TranslatedItem();
        $TestModel->locale = 'eng';
        $result = $TestModel->read(null, 1);
        $expected = [
            'TranslatedItem' => [
                'id' => 1,
                'slug' => 'first_translated',
                'locale' => 'eng',
                'title' => 'Title #1',
                'content' => 'Content #1',
                'translated_article_id' => 1,
            ],
        ];
        $this->assertEqual($result, $expected);

        $result = $TestModel->find('all');
        $expected = [
            [
                'TranslatedItem' => [
                    'id' => 1,
                    'slug' => 'first_translated',
                    'locale' => 'eng',
                    'title' => 'Title #1',
                    'content' => 'Content #1',
                    'translated_article_id' => 1,
                ],
            ],
            [
                'TranslatedItem' => [
                    'id' => 2,
                    'slug' => 'second_translated',
                    'locale' => 'eng',
                    'title' => 'Title #2',
                    'content' => 'Content #2',
                    'translated_article_id' => 1,
                ],
            ],
            [
                'TranslatedItem' => [
                    'id' => 3,
                    'slug' => 'third_translated',
                    'locale' => 'eng',
                    'title' => 'Title #3',
                    'content' => 'Content #3',
                    'translated_article_id' => 1,
                ],
            ],
        ];
        $this->assertEqual($result, $expected);
    }

    /**
     * testLocaleSingleWithConditions method.
     */
    public function testLocaleSingleWithConditions()
    {
        $this->loadFixtures('Translate', 'TranslatedItem');

        $TestModel = new TranslatedItem();
        $TestModel->locale = 'eng';
        $result = $TestModel->find('all', ['conditions' => ['slug' => 'first_translated']]);
        $expected = [
            [
                'TranslatedItem' => [
                    'id' => 1,
                    'slug' => 'first_translated',
                    'locale' => 'eng',
                    'title' => 'Title #1',
                    'content' => 'Content #1',
                    'translated_article_id' => 1,
                ],
            ],
        ];
        $this->assertEqual($result, $expected);

        $result = $TestModel->find('all', ['conditions' => "TranslatedItem.slug = 'first_translated'"]);
        $expected = [
            [
                'TranslatedItem' => [
                    'id' => 1,
                    'slug' => 'first_translated',
                    'locale' => 'eng',
                    'title' => 'Title #1',
                    'content' => 'Content #1',
                    'translated_article_id' => 1,
                ],
            ],
        ];
        $this->assertEqual($result, $expected);
    }

    /**
     * testLocaleSingleAssociations method.
     */
    public function testLocaleSingleAssociations()
    {
        $this->loadFixtures('Translate', 'TranslatedItem');

        $TestModel = new TranslatedItem();
        $TestModel->locale = 'eng';
        $TestModel->unbindTranslation();
        $translations = ['title' => 'Title', 'content' => 'Content'];
        $TestModel->bindTranslation($translations, false);

        $result = $TestModel->read(null, 1);
        $expected = [
            'TranslatedItem' => [
                'id' => 1,
                'slug' => 'first_translated',
                'locale' => 'eng',
                'title' => 'Title #1',
                'content' => 'Content #1',
                'translated_article_id' => 1,
            ],
            'Title' => [
                ['id' => 1, 'locale' => 'eng', 'model' => 'TranslatedItem', 'foreign_key' => 1, 'field' => 'title', 'content' => 'Title #1'],
                ['id' => 3, 'locale' => 'deu', 'model' => 'TranslatedItem', 'foreign_key' => 1, 'field' => 'title', 'content' => 'Titel #1'],
                ['id' => 5, 'locale' => 'cze', 'model' => 'TranslatedItem', 'foreign_key' => 1, 'field' => 'title', 'content' => 'Titulek #1'],
            ],
            'Content' => [
                ['id' => 2, 'locale' => 'eng', 'model' => 'TranslatedItem', 'foreign_key' => 1, 'field' => 'content', 'content' => 'Content #1'],
                ['id' => 4, 'locale' => 'deu', 'model' => 'TranslatedItem', 'foreign_key' => 1, 'field' => 'content', 'content' => 'Inhalt #1'],
                ['id' => 6, 'locale' => 'cze', 'model' => 'TranslatedItem', 'foreign_key' => 1, 'field' => 'content', 'content' => 'Obsah #1'],
            ],
        ];
        $this->assertEqual($result, $expected);

        $TestModel->hasMany['Title']['fields'] = $TestModel->hasMany['Content']['fields'] = ['content'];
        $TestModel->hasMany['Title']['conditions']['locale'] = $TestModel->hasMany['Content']['conditions']['locale'] = 'eng';

        $result = $TestModel->find('all', ['fields' => ['TranslatedItem.title']]);
        $expected = [
            [
                'TranslatedItem' => [
                    'id' => 1,
                    'locale' => 'eng',
                    'title' => 'Title #1',
                ],
                'Title' => [['foreign_key' => 1, 'content' => 'Title #1']],
                'Content' => [['foreign_key' => 1, 'content' => 'Content #1']],
            ],
            [
                'TranslatedItem' => [
                    'id' => 2,
                    'locale' => 'eng',
                    'title' => 'Title #2',
                ],
                'Title' => [['foreign_key' => 2, 'content' => 'Title #2']],
                'Content' => [['foreign_key' => 2, 'content' => 'Content #2']],
            ],
            [
                'TranslatedItem' => [
                    'id' => 3,
                    'locale' => 'eng',
                    'title' => 'Title #3',
                ],
                'Title' => [['foreign_key' => 3, 'content' => 'Title #3']],
                'Content' => [['foreign_key' => 3, 'content' => 'Content #3']],
            ],
        ];
        $this->assertEqual($result, $expected);
    }

    /**
     * testLocaleMultiple method.
     */
    public function testLocaleMultiple()
    {
        $this->loadFixtures('Translate', 'TranslatedItem');

        $TestModel = new TranslatedItem();
        $TestModel->locale = ['deu', 'eng', 'cze'];
        $delete = [
            ['locale' => 'deu'],
            ['foreign_key' => 1, 'field' => 'title', 'locale' => 'eng'],
            ['foreign_key' => 1, 'field' => 'content', 'locale' => 'cze'],
            ['foreign_key' => 2, 'field' => 'title', 'locale' => 'cze'],
            ['foreign_key' => 2, 'field' => 'content', 'locale' => 'eng'],
            ['foreign_key' => 3, 'field' => 'title'],
        ];
        $I18nModel = &ClassRegistry::getObject('TranslateTestModel');
        $I18nModel->deleteAll(['or' => $delete]);

        $result = $TestModel->read(null, 1);
        $expected = [
            'TranslatedItem' => [
                'id' => 1,
                'translated_article_id' => 1,
                'slug' => 'first_translated',
                'locale' => 'deu',
                'title' => 'Titulek #1',
                'content' => 'Content #1',
            ],
        ];
        $this->assertEqual($result, $expected);

        $result = $TestModel->find('all', ['fields' => ['slug', 'title', 'content']]);
        $expected = [
            [
                'TranslatedItem' => [
                    'slug' => 'first_translated',
                    'locale' => 'deu',
                    'content' => 'Content #1',
                    'title' => 'Titulek #1',
                ],
            ],
            [
                'TranslatedItem' => [
                    'slug' => 'second_translated',
                    'locale' => 'deu',
                    'title' => 'Title #2',
                    'content' => 'Obsah #2',
                ],
            ],
            [
                'TranslatedItem' => [
                    'slug' => 'third_translated',
                    'locale' => 'deu',
                    'title' => '',
                    'content' => 'Content #3',
                ],
            ],
        ];
        $this->assertEqual($result, $expected);
    }

    /**
     * testMissingTranslation method.
     */
    public function testMissingTranslation()
    {
        $this->loadFixtures('Translate', 'TranslatedItem');

        $TestModel = new TranslatedItem();
        $TestModel->locale = 'rus';
        $result = $TestModel->read(null, 1);
        $this->assertFalse($result);

        $TestModel->locale = ['rus'];
        $result = $TestModel->read(null, 1);
        $expected = [
            'TranslatedItem' => [
                'id' => 1,
                'slug' => 'first_translated',
                'locale' => 'rus',
                'title' => '',
                'content' => '',
                'translated_article_id' => 1,
            ],
        ];
        $this->assertEqual($result, $expected);
    }

    /**
     * testTranslatedFindList method.
     */
    public function testTranslatedFindList()
    {
        $this->loadFixtures('Translate', 'TranslatedItem');

        $TestModel = new TranslatedItem();
        $TestModel->locale = 'deu';
        $TestModel->displayField = 'title';
        $result = $TestModel->find('list', ['recursive' => 1]);
        $expected = [1 => 'Titel #1', 2 => 'Titel #2', 3 => 'Titel #3'];
        $this->assertEqual($result, $expected);

        // MSSQL trigger an error and stops the page even if the debug = 0
        if ('mssql' != $this->db->config['driver']) {
            $debug = Configure::read('debug');
            Configure::write('debug', 0);

            $result = $TestModel->find('list', ['recursive' => 1, 'callbacks' => false]);
            $this->assertEqual($result, []);

            $result = $TestModel->find('list', ['recursive' => 1, 'callbacks' => 'after']);
            $this->assertEqual($result, []);
            Configure::write('debug', $debug);
        }

        $result = $TestModel->find('list', ['recursive' => 1, 'callbacks' => 'before']);
        $expected = [1 => null, 2 => null, 3 => null];
        $this->assertEqual($result, $expected);
    }

    /**
     * testReadSelectedFields method.
     */
    public function testReadSelectedFields()
    {
        $this->loadFixtures('Translate', 'TranslatedItem');

        $TestModel = new TranslatedItem();
        $TestModel->locale = 'eng';
        $result = $TestModel->find('all', ['fields' => ['slug', 'TranslatedItem.content']]);
        $expected = [
            ['TranslatedItem' => ['slug' => 'first_translated', 'locale' => 'eng', 'content' => 'Content #1']],
            ['TranslatedItem' => ['slug' => 'second_translated', 'locale' => 'eng', 'content' => 'Content #2']],
            ['TranslatedItem' => ['slug' => 'third_translated', 'locale' => 'eng', 'content' => 'Content #3']],
        ];
        $this->assertEqual($result, $expected);

        $result = $TestModel->find('all', ['fields' => ['TranslatedItem.slug', 'content']]);
        $this->assertEqual($result, $expected);

        $TestModel->locale = ['eng', 'deu', 'cze'];
        $delete = [['locale' => 'deu'], ['field' => 'content', 'locale' => 'eng']];
        $I18nModel = &ClassRegistry::getObject('TranslateTestModel');
        $I18nModel->deleteAll(['or' => $delete]);

        $result = $TestModel->find('all', ['fields' => ['title', 'content']]);
        $expected = [
            ['TranslatedItem' => ['locale' => 'eng', 'title' => 'Title #1', 'content' => 'Obsah #1']],
            ['TranslatedItem' => ['locale' => 'eng', 'title' => 'Title #2', 'content' => 'Obsah #2']],
            ['TranslatedItem' => ['locale' => 'eng', 'title' => 'Title #3', 'content' => 'Obsah #3']],
        ];
        $this->assertEqual($result, $expected);
    }

    /**
     * testSaveCreate method.
     */
    public function testSaveCreate()
    {
        $this->loadFixtures('Translate', 'TranslatedItem');

        $TestModel = new TranslatedItem();
        $TestModel->locale = 'spa';
        $data = [
            'slug' => 'fourth_translated',
            'title' => 'Leyenda #4',
            'content' => 'Contenido #4',
            'translated_article_id' => null,
        ];
        $TestModel->create($data);
        $TestModel->save();
        $result = $TestModel->read();
        $expected = ['TranslatedItem' => array_merge($data, ['id' => $TestModel->id, 'locale' => 'spa'])];
        $this->assertEqual($result, $expected);
    }

    /**
     * testSaveUpdate method.
     */
    public function testSaveUpdate()
    {
        $this->loadFixtures('Translate', 'TranslatedItem');

        $TestModel = new TranslatedItem();
        $TestModel->locale = 'spa';
        $oldData = ['slug' => 'fourth_translated', 'title' => 'Leyenda #4', 'translated_article_id' => 1];
        $TestModel->create($oldData);
        $TestModel->save();
        $id = $TestModel->id;
        $newData = ['id' => $id, 'content' => 'Contenido #4'];
        $TestModel->create($newData);
        $TestModel->save();
        $result = $TestModel->read(null, $id);
        $expected = ['TranslatedItem' => array_merge($oldData, $newData, ['locale' => 'spa'])];
        $this->assertEqual($result, $expected);
    }

    /**
     * testMultipleCreate method.
     */
    public function testMultipleCreate()
    {
        $this->loadFixtures('Translate', 'TranslatedItem');

        $TestModel = new TranslatedItem();
        $TestModel->locale = 'deu';
        $data = [
            'slug' => 'new_translated',
            'title' => ['eng' => 'New title', 'spa' => 'Nuevo leyenda'],
            'content' => ['eng' => 'New content', 'spa' => 'Nuevo contenido'],
        ];
        $TestModel->create($data);
        $TestModel->save();

        $TestModel->unbindTranslation();
        $translations = ['title' => 'Title', 'content' => 'Content'];
        $TestModel->bindTranslation($translations, false);
        $TestModel->locale = ['eng', 'spa'];

        $result = $TestModel->read();
        $expected = [
            'TranslatedItem' => [
                'id' => 4,
                'slug' => 'new_translated',
                'locale' => 'eng',
                'title' => 'New title',
                'content' => 'New content',
                'translated_article_id' => null,
            ],
            'Title' => [
                ['id' => 21, 'locale' => 'eng', 'model' => 'TranslatedItem', 'foreign_key' => 4, 'field' => 'title', 'content' => 'New title'],
                ['id' => 22, 'locale' => 'spa', 'model' => 'TranslatedItem', 'foreign_key' => 4, 'field' => 'title', 'content' => 'Nuevo leyenda'],
            ],
            'Content' => [
                ['id' => 19, 'locale' => 'eng', 'model' => 'TranslatedItem', 'foreign_key' => 4, 'field' => 'content', 'content' => 'New content'],
                ['id' => 20, 'locale' => 'spa', 'model' => 'TranslatedItem', 'foreign_key' => 4, 'field' => 'content', 'content' => 'Nuevo contenido'],
            ],
        ];
        $this->assertEqual($result, $expected);
    }

    /**
     * testMultipleUpdate method.
     */
    public function testMultipleUpdate()
    {
        $this->loadFixtures('Translate', 'TranslatedItem');

        $TestModel = new TranslatedItem();
        $TestModel->locale = 'eng';
        $TestModel->validate['title'] = 'notEmpty';
        $data = ['TranslatedItem' => [
            'id' => 1,
            'title' => ['eng' => 'New Title #1', 'deu' => 'Neue Titel #1', 'cze' => 'Novy Titulek #1'],
            'content' => ['eng' => 'New Content #1', 'deu' => 'Neue Inhalt #1', 'cze' => 'Novy Obsah #1'],
        ]];
        $TestModel->create();
        $TestModel->save($data);

        $TestModel->unbindTranslation();
        $translations = ['title' => 'Title', 'content' => 'Content'];
        $TestModel->bindTranslation($translations, false);
        $result = $TestModel->read(null, 1);
        $expected = [
            'TranslatedItem' => [
                'id' => '1',
                'slug' => 'first_translated',
                'locale' => 'eng',
                'title' => 'New Title #1',
                'content' => 'New Content #1',
                'translated_article_id' => 1,
            ],
            'Title' => [
                ['id' => 1, 'locale' => 'eng', 'model' => 'TranslatedItem', 'foreign_key' => 1, 'field' => 'title', 'content' => 'New Title #1'],
                ['id' => 3, 'locale' => 'deu', 'model' => 'TranslatedItem', 'foreign_key' => 1, 'field' => 'title', 'content' => 'Neue Titel #1'],
                ['id' => 5, 'locale' => 'cze', 'model' => 'TranslatedItem', 'foreign_key' => 1, 'field' => 'title', 'content' => 'Novy Titulek #1'],
            ],
            'Content' => [
                ['id' => 2, 'locale' => 'eng', 'model' => 'TranslatedItem', 'foreign_key' => 1, 'field' => 'content', 'content' => 'New Content #1'],
                ['id' => 4, 'locale' => 'deu', 'model' => 'TranslatedItem', 'foreign_key' => 1, 'field' => 'content', 'content' => 'Neue Inhalt #1'],
                ['id' => 6, 'locale' => 'cze', 'model' => 'TranslatedItem', 'foreign_key' => 1, 'field' => 'content', 'content' => 'Novy Obsah #1'],
            ],
        ];
        $this->assertEqual($result, $expected);

        $TestModel->unbindTranslation($translations);
        $TestModel->bindTranslation(['title', 'content'], false);
    }

    /**
     * testMixedCreateUpdateWithArrayLocale method.
     */
    public function testMixedCreateUpdateWithArrayLocale()
    {
        $this->loadFixtures('Translate', 'TranslatedItem');

        $TestModel = new TranslatedItem();
        $TestModel->locale = ['cze', 'deu'];
        $data = ['TranslatedItem' => [
            'id' => 1,
            'title' => ['eng' => 'Updated Title #1', 'spa' => 'Nuevo leyenda #1'],
            'content' => 'Upraveny obsah #1',
        ]];
        $TestModel->create();
        $TestModel->save($data);

        $TestModel->unbindTranslation();
        $translations = ['title' => 'Title', 'content' => 'Content'];
        $TestModel->bindTranslation($translations, false);
        $result = $TestModel->read(null, 1);
        $expected = [
            'TranslatedItem' => [
                'id' => 1,
                'slug' => 'first_translated',
                'locale' => 'cze',
                'title' => 'Titulek #1',
                'content' => 'Upraveny obsah #1',
                'translated_article_id' => 1,
            ],
            'Title' => [
                ['id' => 1, 'locale' => 'eng', 'model' => 'TranslatedItem', 'foreign_key' => 1, 'field' => 'title', 'content' => 'Updated Title #1'],
                ['id' => 3, 'locale' => 'deu', 'model' => 'TranslatedItem', 'foreign_key' => 1, 'field' => 'title', 'content' => 'Titel #1'],
                ['id' => 5, 'locale' => 'cze', 'model' => 'TranslatedItem', 'foreign_key' => 1, 'field' => 'title', 'content' => 'Titulek #1'],
                ['id' => 19, 'locale' => 'spa', 'model' => 'TranslatedItem', 'foreign_key' => 1, 'field' => 'title', 'content' => 'Nuevo leyenda #1'],
            ],
            'Content' => [
                ['id' => 2, 'locale' => 'eng', 'model' => 'TranslatedItem', 'foreign_key' => 1, 'field' => 'content', 'content' => 'Content #1'],
                ['id' => 4, 'locale' => 'deu', 'model' => 'TranslatedItem', 'foreign_key' => 1, 'field' => 'content', 'content' => 'Inhalt #1'],
                ['id' => 6, 'locale' => 'cze', 'model' => 'TranslatedItem', 'foreign_key' => 1, 'field' => 'content', 'content' => 'Upraveny obsah #1'],
            ],
        ];
        $this->assertEqual($result, $expected);
    }

    /**
     * Test that saveAll() works with hasMany associations that contain
     * translations.
     */
    public function testSaveAllTranslatedAssociations()
    {
        $this->loadFixtures('Translate', 'TranslateArticle', 'TranslatedItem', 'TranslatedArticle', 'User');
        $Model = new TranslatedArticle();
        $Model->locale = 'eng';

        $data = [
            'TranslatedArticle' => [
                'user_id' => 1,
                'published' => 'Y',
                'title' => 'Title (eng) #1',
                'body' => 'Body (eng) #1',
            ],
            'TranslatedItem' => [
                [
                    'title' => 'Nuevo leyenda #1',
                    'content' => 'Upraveny obsah #1',
                ],
                [
                    'title' => 'New Title #2',
                    'content' => 'New Content #2',
                ],
            ],
        ];
        $result = $Model->saveAll($data);
        $this->assertTrue($result);

        $result = $Model->TranslatedItem->find('all', [
            'conditions' => ['translated_article_id' => $Model->id],
        ]);
        $this->assertEqual(2, count($result));
        $this->assertEqual($data['TranslatedItem'][0]['title'], $result[0]['TranslatedItem']['title']);
        $this->assertEqual($data['TranslatedItem'][1]['title'], $result[1]['TranslatedItem']['title']);
    }

    /**
     * testValidation method.
     */
    public function testValidation()
    {
        $this->loadFixtures('Translate', 'TranslatedItem');

        $TestModel = new TranslatedItem();
        $TestModel->locale = 'eng';
        $TestModel->validate['title'] = '/Only this title/';
        $data = [
            'TranslatedItem' => [
                'id' => 1,
                'title' => ['eng' => 'New Title #1', 'deu' => 'Neue Titel #1', 'cze' => 'Novy Titulek #1'],
                'content' => ['eng' => 'New Content #1', 'deu' => 'Neue Inhalt #1', 'cze' => 'Novy Obsah #1'],
            ],
        ];
        $TestModel->create();
        $this->assertFalse($TestModel->save($data));
        $this->assertEqual($TestModel->validationErrors['title'], 'This field cannot be left blank');

        $TestModel->locale = 'eng';
        $TestModel->validate['title'] = '/Only this title/';
        $data = ['TranslatedItem' => [
            'id' => 1,
            'title' => ['eng' => 'Only this title', 'deu' => 'Neue Titel #1', 'cze' => 'Novy Titulek #1'],
            'content' => ['eng' => 'New Content #1', 'deu' => 'Neue Inhalt #1', 'cze' => 'Novy Obsah #1'],
        ]];
        $TestModel->create();
        $this->assertTrue($TestModel->save($data));
    }

    /**
     * testAttachDetach method.
     */
    public function testAttachDetach()
    {
        $this->loadFixtures('Translate', 'TranslatedItem');

        $TestModel = new TranslatedItem();
        $Behavior = &$this->Model->Behaviors->Translate;

        $TestModel->unbindTranslation();
        $translations = ['title' => 'Title', 'content' => 'Content'];
        $TestModel->bindTranslation($translations, false);

        $result = array_keys($TestModel->hasMany);
        $expected = ['Title', 'Content'];
        $this->assertEqual($result, $expected);

        $TestModel->Behaviors->detach('Translate');
        $result = array_keys($TestModel->hasMany);
        $expected = [];
        $this->assertEqual($result, $expected);

        $result = isset($TestModel->Behaviors->Translate);
        $this->assertFalse($result);

        $result = isset($Behavior->settings[$TestModel->alias]);
        $this->assertFalse($result);

        $result = isset($Behavior->runtime[$TestModel->alias]);
        $this->assertFalse($result);

        $TestModel->Behaviors->attach('Translate', ['title' => 'Title', 'content' => 'Content']);
        $result = array_keys($TestModel->hasMany);
        $expected = ['Title', 'Content'];
        $this->assertEqual($result, $expected);

        $result = isset($TestModel->Behaviors->Translate);
        $this->assertTrue($result);

        $Behavior = $TestModel->Behaviors->Translate;

        $result = isset($Behavior->settings[$TestModel->alias]);
        $this->assertTrue($result);

        $result = isset($Behavior->runtime[$TestModel->alias]);
        $this->assertTrue($result);
    }

    /**
     * testAnotherTranslateTable method.
     */
    public function testAnotherTranslateTable()
    {
        $this->loadFixtures('Translate', 'TranslatedItem', 'TranslateTable');

        $TestModel = new TranslatedItemWithTable();
        $TestModel->locale = 'eng';
        $result = $TestModel->read(null, 1);
        $expected = [
            'TranslatedItemWithTable' => [
                'id' => 1,
                'slug' => 'first_translated',
                'locale' => 'eng',
                'title' => 'Another Title #1',
                'content' => 'Another Content #1',
                'translated_article_id' => 1,
            ],
        ];
        $this->assertEqual($result, $expected);
    }

    /**
     * testTranslateWithAssociations method.
     */
    public function testTranslateWithAssociations()
    {
        $this->loadFixtures('TranslateArticle', 'TranslatedArticle', 'TranslatedItem', 'User', 'Comment', 'ArticlesTag', 'Tag');

        $TestModel = new TranslatedArticle();
        $TestModel->locale = 'eng';
        $recursive = $TestModel->recursive;

        $result = $TestModel->read(null, 1);
        $expected = [
            'TranslatedArticle' => [
                'id' => 1,
                'user_id' => 1,
                'published' => 'Y',
                'created' => '2007-03-18 10:39:23',
                'updated' => '2007-03-18 10:41:31',
                'locale' => 'eng',
                'title' => 'Title (eng) #1',
                'body' => 'Body (eng) #1',
            ],
            'User' => [
                'id' => 1,
                'user' => 'mariano',
                'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
                'created' => '2007-03-17 01:16:23',
                'updated' => '2007-03-17 01:18:31',
            ],
            'TranslatedItem' => [
                [
                    'id' => 1,
                    'translated_article_id' => 1,
                    'slug' => 'first_translated',
                ],
                [
                    'id' => 2,
                    'translated_article_id' => 1,
                    'slug' => 'second_translated',
                ],
                [
                    'id' => 3,
                    'translated_article_id' => 1,
                    'slug' => 'third_translated',
                ],
            ],
        ];
        $this->assertEqual($result, $expected);

        $result = $TestModel->find('all', ['recursive' => -1]);
        $expected = [
            [
                'TranslatedArticle' => [
                    'id' => 1,
                    'user_id' => 1,
                    'published' => 'Y',
                    'created' => '2007-03-18 10:39:23',
                    'updated' => '2007-03-18 10:41:31',
                    'locale' => 'eng',
                    'title' => 'Title (eng) #1',
                    'body' => 'Body (eng) #1',
                ],
            ],
            [
                'TranslatedArticle' => [
                    'id' => 2,
                    'user_id' => 3,
                    'published' => 'Y',
                    'created' => '2007-03-18 10:41:23',
                    'updated' => '2007-03-18 10:43:31',
                    'locale' => 'eng',
                    'title' => 'Title (eng) #2',
                    'body' => 'Body (eng) #2',
                ],
            ],
            [
                'TranslatedArticle' => [
                    'id' => 3,
                    'user_id' => 1,
                    'published' => 'Y',
                    'created' => '2007-03-18 10:43:23',
                    'updated' => '2007-03-18 10:45:31',
                    'locale' => 'eng',
                    'title' => 'Title (eng) #3',
                    'body' => 'Body (eng) #3',
                ],
            ],
        ];
        $this->assertEqual($result, $expected);
        $this->assertEqual($TestModel->recursive, $recursive);

        $TestModel->recursive = -1;
        $result = $TestModel->read(null, 1);
        $expected = [
            'TranslatedArticle' => [
                'id' => 1,
                'user_id' => 1,
                'published' => 'Y',
                'created' => '2007-03-18 10:39:23',
                'updated' => '2007-03-18 10:41:31',
                'locale' => 'eng',
                'title' => 'Title (eng) #1',
                'body' => 'Body (eng) #1',
            ],
        ];
        $this->assertEqual($result, $expected);
    }

    /**
     * testTranslateTableWithPrefix method
     * Tests that is possible to have a translation model with a custom tablePrefix.
     */
    public function testTranslateTableWithPrefix()
    {
        $this->loadFixtures('TranslateWithPrefix', 'TranslatedItem');
        $TestModel = new TranslatedItem2();
        $TestModel->locale = 'eng';
        $result = $TestModel->read(null, 1);
        $expected = ['TranslatedItem' => [
            'id' => 1,
            'slug' => 'first_translated',
            'locale' => 'eng',
            'content' => 'Content #1',
            'title' => 'Title #1',
            'translated_article_id' => 1,
        ]];
        $this->assertEqual($result, $expected);
    }

    /**
     * Test infinite loops not occuring with unbindTranslation().
     */
    public function testUnbindTranslationInfinteLoop()
    {
        $this->loadFixtures('Translate', 'TranslatedItem');

        $TestModel = new TranslatedItem();
        $TestModel->Behaviors->detach('Translate');
        $TestModel->actsAs = [];
        $TestModel->Behaviors->attach('Translate');
        $TestModel->bindTranslation(['title', 'content'], true);
        $result = $TestModel->unbindTranslation();

        $this->assertFalse($result);
    }
}
