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
class AroFixture extends CakeTestFixture
{
    /**
     * name property.
     *
     * @var string 'Aro'
     */
    public $name = 'Aro';

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
        ['parent_id' => null, 'model' => null, 'foreign_key' => null, 'alias' => 'ROOT', 'lft' => 1, 'rght' => 8],
        ['parent_id' => '1', 'model' => 'Group', 'foreign_key' => '1', 'alias' => 'admins', 'lft' => 2, 'rght' => 7],
        ['parent_id' => '2', 'model' => 'AuthUser', 'foreign_key' => '1', 'alias' => 'Gandalf', 'lft' => 3, 'rght' => 4],
        ['parent_id' => '2', 'model' => 'AuthUser', 'foreign_key' => '2', 'alias' => 'Elrond', 'lft' => 5, 'rght' => 6],
    ];
}
