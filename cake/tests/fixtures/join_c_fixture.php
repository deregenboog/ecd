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
 * @since         CakePHP(tm) v 1.2.0.6317
 *
 * @license       http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */

/**
 * Short description for class.
 */
class JoinCFixture extends CakeTestFixture
{
    /**
     * name property.
     *
     * @var string 'JoinC'
     */
    public $name = 'JoinC';

    /**
     * fields property.
     *
     * @var array
     */
    public $fields = [
        'id' => ['type' => 'integer', 'key' => 'primary'],
        'name' => ['type' => 'string', 'default' => ''],
        'created' => ['type' => 'datetime', 'null' => true],
        'updated' => ['type' => 'datetime', 'null' => true],
    ];

    /**
     * records property.
     *
     * @var array
     */
    public $records = [
        ['name' => 'Join C 1', 'created' => '2008-01-03 10:56:11', 'updated' => '2008-01-03 10:56:11'],
        ['name' => 'Join C 2', 'created' => '2008-01-03 10:56:12', 'updated' => '2008-01-03 10:56:12'],
        ['name' => 'Join C 3', 'created' => '2008-01-03 10:56:13', 'updated' => '2008-01-03 10:56:13'],
    ];
}
