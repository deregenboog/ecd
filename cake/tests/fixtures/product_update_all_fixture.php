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
class ProductUpdateAllFixture extends CakeTestFixture
{
    public $name = 'ProductUpdateAll';
    public $table = 'product_update_all';

    public $fields = [
            'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
            'name' => ['type' => 'string', 'null' => false, 'length' => 29],
            'groupcode' => ['type' => 'integer', 'null' => false, 'length' => 4],
            'group_id' => ['type' => 'integer', 'null' => false, 'length' => 8],
            'indexes' => ['PRIMARY' => ['column' => 'id', 'unique' => 1]],
            ];
    public $records = [
        [
            'id' => 1,
            'name' => 'product one',
            'groupcode' => 120,
            'group_id' => 1,
            ],
        [
            'id' => 2,
            'name' => 'product two',
            'groupcode' => 120,
            'group_id' => 1, ],
        [
            'id' => 3,
            'name' => 'product three',
            'groupcode' => 125,
            'group_id' => 2, ],
        [
            'id' => 4,
            'name' => 'product four',
            'groupcode' => 135,
            'group_id' => 4, ],
        ];
}
