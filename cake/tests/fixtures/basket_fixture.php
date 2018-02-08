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
class BasketFixture extends CakeTestFixture
{
    /**
     * name property.
     *
     * @var string 'Basket'
     */
    public $name = 'Basket';

    /**
     * fields property.
     *
     * @var array
     */
    public $fields = [
        'id' => ['type' => 'integer', 'key' => 'primary'],
        'type' => ['type' => 'string', 'length' => 255],
        'name' => ['type' => 'string', 'length' => 255],
        'object_id' => ['type' => 'integer'],
        'user_id' => ['type' => 'integer'],
    ];

    /**
     * records property.
     *
     * @var array
     */
    public $records = [
        ['id' => 1, 'type' => 'nonfile', 'name' => 'basket1', 'object_id' => 1, 'user_id' => 1],
        ['id' => 2, 'type' => 'file', 'name' => 'basket2', 'object_id' => 2, 'user_id' => 1],
    ];
}
