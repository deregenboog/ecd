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
class TranslatedItemFixture extends CakeTestFixture
{
    /**
     * name property.
     *
     * @var string 'TranslatedItem'
     */
    public $name = 'TranslatedItem';

    /**
     * fields property.
     *
     * @var array
     */
    public $fields = [
        'id' => ['type' => 'integer', 'key' => 'primary'],
        'translated_article_id' => ['type' => 'integer'],
        'slug' => ['type' => 'string', 'null' => false],
    ];

    /**
     * records property.
     *
     * @var array
     */
    public $records = [
        ['translated_article_id' => 1, 'slug' => 'first_translated'],
        ['translated_article_id' => 1, 'slug' => 'second_translated'],
        ['translated_article_id' => 1, 'slug' => 'third_translated'],
    ];
}
