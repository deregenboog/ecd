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
class SyfileFixture extends CakeTestFixture
{
    /**
     * name property.
     *
     * @var string 'Syfile'
     */
    public $name = 'Syfile';

    /**
     * fields property.
     *
     * @var array
     */
    public $fields = [
        'id' => ['type' => 'integer', 'key' => 'primary'],
        'image_id' => ['type' => 'integer', 'null' => true],
        'name' => ['type' => 'string', 'null' => false],
        'item_count' => ['type' => 'integer', 'null' => true],
    ];

    /**
     * records property.
     *
     * @var array
     */
    public $records = [
        ['image_id' => 1, 'name' => 'Syfile 1'],
        ['image_id' => 2, 'name' => 'Syfile 2'],
        ['image_id' => 5, 'name' => 'Syfile 3'],
        ['image_id' => 3, 'name' => 'Syfile 4'],
        ['image_id' => 4, 'name' => 'Syfile 5'],
        ['image_id' => null, 'name' => 'Syfile 6'],
    ];
}
