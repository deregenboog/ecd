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
class AroTwoFixture extends CakeTestFixture
{
    /**
     * name property.
     *
     * @var string 'AroTwo'
     */
    public $name = 'AroTwo';

    /**
     * fields property.
     *
     * @var array
     */
    public $fields = [
        'id' => ['type' => 'integer', 'key' => 'primary'],
        'parent_id' => ['type' => 'integer', 'length' => 10, 'null' => true],
        'model' => ['type' => 'string', 'null' => true],
        'foreign_key' => ['type' => 'integer', 'length' => 10, 'null' => true],
        'alias' => ['type' => 'string', 'default' => ''],
        'lft' => ['type' => 'integer', 'length' => 10, 'null' => true],
        'rght' => ['type' => 'integer', 'length' => 10, 'null' => true],
    ];

    /**
     * records property.
     *
     * @var array
     */
    public $records = [
        ['id' => 1, 'parent_id' => null, 'model' => null, 'foreign_key' => null, 'alias' => 'root', 	'lft' => '1',  'rght' => '20'],
        ['id' => 2, 'parent_id' => 1, 'model' => 'Group', 'foreign_key' => '1', 'alias' => 'admin', 	'lft' => '2',   'rght' => '5'],
        ['id' => 3, 'parent_id' => 1, 'model' => 'Group', 'foreign_key' => '2', 'alias' => 'managers', 'lft' => '6',  'rght' => '9'],
        ['id' => 4, 'parent_id' => 1, 'model' => 'Group', 'foreign_key' => '3', 'alias' => 'users',    'lft' => '10', 'rght' => '19'],
        ['id' => 5, 'parent_id' => 2, 'model' => 'User',  'foreign_key' => '1', 'alias' => 'Bobs',      'lft' => '3',  'rght' => '4'],
        ['id' => 6, 'parent_id' => 3, 'model' => 'User',  'foreign_key' => '2', 'alias' => 'Lumbergh',  'lft' => '7',  'rght' => '8'],
        ['id' => 7, 'parent_id' => 4, 'model' => 'User',  'foreign_key' => '3', 'alias' => 'Samir',     'lft' => '11',  'rght' => '12'],
        ['id' => 8, 'parent_id' => 4, 'model' => 'User',  'foreign_key' => '4', 'alias' => 'Micheal',   'lft' => '13',  'rght' => '14'],
        ['id' => 9, 'parent_id' => 4, 'model' => 'User',  'foreign_key' => '5', 'alias' => 'Peter',     'lft' => '15',  'rght' => '16'],
        ['id' => 10, 'parent_id' => 4, 'model' => 'User',  'foreign_key' => '6', 'alias' => 'Milton',   'lft' => '17',  'rght' => '18'],
    ];
}
