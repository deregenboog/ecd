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
 * @since         CakePHP(tm) v 1.2.0.7198
 *
 * @license       http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */

/**
 * Short description for class.
 */
class BookFixture extends CakeTestFixture
{
    /**
     * name property.
     *
     * @var string 'Book'
     */
    public $name = 'Book';

    /**
     * fields property.
     *
     * @var array
     */
    public $fields = [
        'id' => ['type' => 'integer', 'key' => 'primary'],
        'isbn' => ['type' => 'string', 'length' => 13],
        'title' => ['type' => 'string', 'length' => 255],
        'author' => ['type' => 'string', 'length' => 255],
        'year' => ['type' => 'integer', 'null' => true],
        'pages' => ['type' => 'integer', 'null' => true],
    ];

    /**
     * records property.
     *
     * @var array
     */
    public $records = [
        ['id' => 1, 'isbn' => '1234567890', 'title' => 'Faust', 'author' => 'Johann Wolfgang von Goethe'],
    ];
}
