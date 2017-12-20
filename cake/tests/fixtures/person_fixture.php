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
 * @since         CakePHP(tm) v 1.2.0.6700
 *
 * @license       http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */

/**
 * Short description for class.
 */
class PersonFixture extends CakeTestFixture
{
    /**
     * name property.
     *
     * @var string 'Person'
     */
    public $name = 'Person';

    /**
     * fields property.
     *
     * @var array
     */
    public $fields = [
        'id' => ['type' => 'integer', 'null' => false, 'key' => 'primary'],
        'name' => ['type' => 'string', 'null' => false, 'length' => 32],
        'mother_id' => ['type' => 'integer', 'null' => false, 'key' => 'index'],
        'father_id' => ['type' => 'integer', 'null' => false],
        'indexes' => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'mother_id' => ['column' => ['mother_id', 'father_id'], 'unique' => 0],
        ],
    ];

    /**
     * records property.
     *
     * @var array
     */
    public $records = [
        ['name' => 'person', 'mother_id' => 2, 'father_id' => 3],
        ['name' => 'mother', 'mother_id' => 4, 'father_id' => 5],
        ['name' => 'father', 'mother_id' => 6, 'father_id' => 7],
        ['name' => 'mother - grand mother', 'mother_id' => 0, 'father_id' => 0],
        ['name' => 'mother - grand father', 'mother_id' => 0, 'father_id' => 0],
        ['name' => 'father - grand mother', 'mother_id' => 0, 'father_id' => 0],
        ['name' => 'father - grand father', 'mother_id' => 0, 'father_id' => 0],
    ];
}
