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
class ItemFixture extends CakeTestFixture
{
    /**
     * name property.
     *
     * @var string 'Item'
     */
    public $name = 'Item';

    /**
     * fields property.
     *
     * @var array
     */
    public $fields = [
        'id' => ['type' => 'integer', 'key' => 'primary'],
        'syfile_id' => ['type' => 'integer', 'null' => false],
        'published' => ['type' => 'boolean', 'null' => false],
        'name' => ['type' => 'string', 'null' => false],
    ];

    /**
     * records property.
     *
     * @var array
     */
    public $records = [
        ['syfile_id' => 1, 'published' => 0, 'name' => 'Item 1'],
        ['syfile_id' => 2, 'published' => 0, 'name' => 'Item 2'],
        ['syfile_id' => 3, 'published' => 0, 'name' => 'Item 3'],
        ['syfile_id' => 4, 'published' => 0, 'name' => 'Item 4'],
        ['syfile_id' => 5, 'published' => 0, 'name' => 'Item 5'],
        ['syfile_id' => 6, 'published' => 0, 'name' => 'Item 6'],
    ];
}
