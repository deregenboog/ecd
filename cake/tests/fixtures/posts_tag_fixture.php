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
 * @since         CakePHP(tm) v 1.2.0.4667
 *
 * @license       http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */

/**
 * Short description for class.
 */
class PostsTagFixture extends CakeTestFixture
{
    /**
     * name property.
     *
     * @var string 'PostsTag'
     */
    public $name = 'PostsTag';

    /**
     * fields property.
     *
     * @var array
     */
    public $fields = [
        'post_id' => ['type' => 'integer', 'null' => false],
        'tag_id' => ['type' => 'string', 'null' => false],
        'indexes' => ['posts_tag' => ['column' => ['tag_id', 'post_id'], 'unique' => 1]],
    ];

    /**
     * records property.
     *
     * @var array
     */
    public $records = [
        ['post_id' => 1, 'tag_id' => 'tag1'],
        ['post_id' => 1, 'tag_id' => 'tag2'],
        ['post_id' => 2, 'tag_id' => 'tag1'],
        ['post_id' => 2, 'tag_id' => 'tag3'],
    ];
}
