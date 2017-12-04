<?php
/**
 * Short description for file.
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

/**
 * Short description for class.
 */
class TranslateArticleFixture extends CakeTestFixture
{
    /**
     * name property.
     *
     * @var string 'Translate'
     */
    public $name = 'TranslateArticle';

    /**
     * table property.
     *
     * @var string 'i18n'
     */
    public $table = 'article_i18n';

    /**
     * fields property.
     *
     * @var array
     */
    public $fields = [
        'id' => ['type' => 'integer', 'key' => 'primary'],
        'locale' => ['type' => 'string', 'length' => 6, 'null' => false],
        'model' => ['type' => 'string', 'null' => false],
        'foreign_key' => ['type' => 'integer', 'null' => false],
        'field' => ['type' => 'string', 'null' => false],
        'content' => ['type' => 'text'],
    ];

    /**
     * records property.
     *
     * @var array
     */
    public $records = [
        ['id' => 1, 'locale' => 'eng', 'model' => 'TranslatedArticle', 'foreign_key' => 1, 'field' => 'title', 'content' => 'Title (eng) #1'],
        ['id' => 2, 'locale' => 'eng', 'model' => 'TranslatedArticle', 'foreign_key' => 1, 'field' => 'body', 'content' => 'Body (eng) #1'],
        ['id' => 3, 'locale' => 'deu', 'model' => 'TranslatedArticle', 'foreign_key' => 1, 'field' => 'title', 'content' => 'Title (deu) #1'],
        ['id' => 4, 'locale' => 'deu', 'model' => 'TranslatedArticle', 'foreign_key' => 1, 'field' => 'body', 'content' => 'Body (deu) #1'],
        ['id' => 5, 'locale' => 'cze', 'model' => 'TranslatedArticle', 'foreign_key' => 1, 'field' => 'title', 'content' => 'Title (cze) #1'],
        ['id' => 6, 'locale' => 'cze', 'model' => 'TranslatedArticle', 'foreign_key' => 1, 'field' => 'body', 'content' => 'Body (cze) #1'],
        ['id' => 7, 'locale' => 'eng', 'model' => 'TranslatedArticle', 'foreign_key' => 2, 'field' => 'title', 'content' => 'Title (eng) #2'],
        ['id' => 8, 'locale' => 'eng', 'model' => 'TranslatedArticle', 'foreign_key' => 2, 'field' => 'body', 'content' => 'Body (eng) #2'],
        ['id' => 9, 'locale' => 'deu', 'model' => 'TranslatedArticle', 'foreign_key' => 2, 'field' => 'title', 'content' => 'Title (deu) #2'],
        ['id' => 10, 'locale' => 'deu', 'model' => 'TranslatedArticle', 'foreign_key' => 2, 'field' => 'body', 'content' => 'Body (deu) #2'],
        ['id' => 11, 'locale' => 'cze', 'model' => 'TranslatedArticle', 'foreign_key' => 2, 'field' => 'title', 'content' => 'Title (cze) #2'],
        ['id' => 12, 'locale' => 'cze', 'model' => 'TranslatedArticle', 'foreign_key' => 2, 'field' => 'body', 'content' => 'Body (cze) #2'],
        ['id' => 13, 'locale' => 'eng', 'model' => 'TranslatedArticle', 'foreign_key' => 3, 'field' => 'title', 'content' => 'Title (eng) #3'],
        ['id' => 14, 'locale' => 'eng', 'model' => 'TranslatedArticle', 'foreign_key' => 3, 'field' => 'body', 'content' => 'Body (eng) #3'],
        ['id' => 15, 'locale' => 'deu', 'model' => 'TranslatedArticle', 'foreign_key' => 3, 'field' => 'title', 'content' => 'Title (deu) #3'],
        ['id' => 16, 'locale' => 'deu', 'model' => 'TranslatedArticle', 'foreign_key' => 3, 'field' => 'body', 'content' => 'Body (deu) #3'],
        ['id' => 17, 'locale' => 'cze', 'model' => 'TranslatedArticle', 'foreign_key' => 3, 'field' => 'title', 'content' => 'Title (cze) #3'],
        ['id' => 18, 'locale' => 'cze', 'model' => 'TranslatedArticle', 'foreign_key' => 3, 'field' => 'body', 'content' => 'Body (cze) #3'],
    ];
}
