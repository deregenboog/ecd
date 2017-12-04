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
class JoinABFixture extends CakeTestFixture
{
    /**
     * name property.
     *
     * @var string 'JoinAsJoinB'
     */
    public $name = 'JoinAsJoinB';

    /**
     * fields property.
     *
     * @var array
     */
    public $fields = [
        'id' => ['type' => 'integer', 'key' => 'primary'],
        'join_a_id' => ['type' => 'integer', 'length' => 10, 'null' => true],
        'join_b_id' => ['type' => 'integer', 'default' => null],
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
        ['join_a_id' => 1, 'join_b_id' => 2, 'other' => 'Data for Join A 1 Join B 2', 'created' => '2008-01-03 10:56:33', 'updated' => '2008-01-03 10:56:33'],
        ['join_a_id' => 2, 'join_b_id' => 3, 'other' => 'Data for Join A 2 Join B 3', 'created' => '2008-01-03 10:56:34', 'updated' => '2008-01-03 10:56:34'],
        ['join_a_id' => 3, 'join_b_id' => 1, 'other' => 'Data for Join A 3 Join B 1', 'created' => '2008-01-03 10:56:35', 'updated' => '2008-01-03 10:56:35'],
    ];
}
