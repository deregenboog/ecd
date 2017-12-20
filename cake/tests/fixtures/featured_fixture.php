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
class FeaturedFixture extends CakeTestFixture
{
    /**
     * name property.
     *
     * @var string 'Featured'
     */
    public $name = 'Featured';

    /**
     * fields property.
     *
     * @var array
     */
    public $fields = [
        'id' => ['type' => 'integer', 'key' => 'primary'],
        'article_featured_id' => ['type' => 'integer', 'null' => false],
        'category_id' => ['type' => 'integer', 'null' => false],
        'published_date' => 'datetime',
        'end_date' => 'datetime',
        'created' => 'datetime',
        'updated' => 'datetime',
    ];

    /**
     * records property.
     *
     * @var array
     */
    public $records = [
        ['article_featured_id' => 1, 'category_id' => 1, 'published_date' => '2007-03-31 10:39:23', 'end_date' => '2007-05-15 10:39:23', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31'],
        ['article_featured_id' => 2, 'category_id' => 1, 'published_date' => '2007-03-31 10:39:23', 'end_date' => '2007-05-15 10:39:23', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31'],
    ];
}
