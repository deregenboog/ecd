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
class GroupUpdateAllFixture extends CakeTestFixture
{
    public $name = 'GroupUpdateAll';
    public $table = 'group_update_all';

    public $fields = [
            'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
            'name' => ['type' => 'string', 'null' => false, 'length' => 29],
            'code' => ['type' => 'integer', 'null' => false, 'length' => 4],
            'indexes' => ['PRIMARY' => ['column' => 'id', 'unique' => 1]],
            ];
    public $records = [
        [
            'id' => 1,
            'name' => 'group one',
            'code' => 120, ],
        [
            'id' => 2,
            'name' => 'group two',
            'code' => 125, ],
        [
            'id' => 3,
            'name' => 'group three',
            'code' => 130, ],
        [
            'id' => 4,
            'name' => 'group four',
            'code' => 135, ],
        ];
}
