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
class JoinACFixture extends CakeTestFixture
{
    /**
     * name property.
     *
     * @var string 'JoinAsJoinC'
     */
    public $name = 'JoinAsJoinC';

    /**
     * fields property.
     *
     * @var array
     */
    public $fields = [
        'id' => ['type' => 'integer', 'key' => 'primary'],
        'join_a_id' => ['type' => 'integer', 'length' => 10, 'null' => true],
        'join_c_id' => ['type' => 'integer', 'default' => null],
        'other' => ['type' => 'string', 'default' => ''],
        'created' => ['type' => 'datetime', 'null' => true],
        'updated' => ['type' => 'datetime', 'null' => true],
    ];

    /**
     * records property.
     *
     * @var array
     */
    public $records = [
        ['join_a_id' => 1, 'join_c_id' => 2, 'other' => 'Data for Join A 1 Join C 2', 'created' => '2008-01-03 10:57:22', 'updated' => '2008-01-03 10:57:22'],
        ['join_a_id' => 2, 'join_c_id' => 3, 'other' => 'Data for Join A 2 Join C 3', 'created' => '2008-01-03 10:57:23', 'updated' => '2008-01-03 10:57:23'],
        ['join_a_id' => 3, 'join_c_id' => 1, 'other' => 'Data for Join A 3 Join C 1', 'created' => '2008-01-03 10:57:24', 'updated' => '2008-01-03 10:57:24'],
    ];
}
