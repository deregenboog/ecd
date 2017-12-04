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
 * @since         CakePHP(tm) v 7660
 *
 * @license       http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */

/**
 * Short description for class.
 */
class TestPluginArticleFixture extends CakeTestFixture
{
    /**
     * name property.
     *
     * @var string 'Article'
     */
    public $name = 'TestPluginArticle';

    /**
     * fields property.
     *
     * @var array
     */
    public $fields = [
        'id' => ['type' => 'integer', 'key' => 'primary'],
        'user_id' => ['type' => 'integer', 'null' => false],
        'title' => ['type' => 'string', 'null' => false],
        'body' => 'text',
        'published' => ['type' => 'string', 'length' => 1, 'default' => 'N'],
        'created' => 'datetime',
        'updated' => 'datetime',
    ];

    /**
     * records property.
     *
     * @var array
     */
    public $records = [
        ['user_id' => 1, 'title' => 'First Plugin Article', 'body' => 'First Plugin Article Body', 'published' => 'Y', 'created' => '2008-09-24 10:39:23', 'updated' => '2008-09-24 10:41:31'],
        ['user_id' => 3, 'title' => 'Second Plugin Article', 'body' => 'Second Plugin Article Body', 'published' => 'Y', 'created' => '2008-09-24 10:41:23', 'updated' => '2008-09-24 10:43:31'],
        ['user_id' => 1, 'title' => 'Third Plugin Article', 'body' => 'Third Plugin Article Body', 'published' => 'Y', 'created' => '2008-09-24 10:43:23', 'updated' => '2008-09-24 10:45:31'],
    ];
}
