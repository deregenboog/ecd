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
class ProductFixture extends CakeTestFixture
{
    /**
     * name property.
     *
     * @var string 'Product'
     */
    public $name = 'Product';

    /**
     * fields property.
     *
     * @var array
     */
    public $fields = [
        'id' => ['type' => 'integer', 'key' => 'primary'],
        'name' => ['type' => 'string', 'length' => 255, 'null' => false],
        'type' => ['type' => 'string', 'length' => 255, 'null' => false],
        'price' => ['type' => 'integer', 'null' => false],
    ];

    /**
     * records property.
     *
     * @var array
     */
    public $records = [
        ['name' => 'Park\'s Great Hits', 'type' => 'Music', 'price' => 19],
        ['name' => 'Silly Puddy', 'type' => 'Toy', 'price' => 3],
        ['name' => 'Playstation', 'type' => 'Toy', 'price' => 89],
        ['name' => 'Men\'s T-Shirt', 'type' => 'Clothing', 'price' => 32],
        ['name' => 'Blouse', 'type' => 'Clothing', 'price' => 34],
        ['name' => 'Electronica 2002', 'type' => 'Music', 'price' => 4],
        ['name' => 'Country Tunes', 'type' => 'Music', 'price' => 21],
        ['name' => 'Watermelon', 'type' => 'Food', 'price' => 9],
    ];
}
