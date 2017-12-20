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
 * @since         CakePHP(tm) v 1.2.0.7953
 *
 * @license       http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */

/**
 * Short description for class.
 */
class FruitsUuidTagFixture extends CakeTestFixture
{
    /**
     * name property.
     *
     * @var string 'FruitsUuidTag'
     */
    public $name = 'FruitsUuidTag';

    /**
     * fields property.
     *
     * @var array
     */
    public $fields = [
        'fruit_id' => ['type' => 'string', 'null' => false, 'length' => 36, 'key' => 'primary'],
        'uuid_tag_id' => ['type' => 'string', 'null' => false, 'length' => 36, 'key' => 'primary'],
        'indexes' => [
            'unique_fruits_tags' => ['unique' => true, 'column' => ['fruit_id', 'uuid_tag_id']],
        ],
    ];

    /**
     * records property.
     *
     * @var array
     */
    public $records = [
        ['fruit_id' => '481fc6d0-b920-43e0-a40d-6d1740cf8569', 'uuid_tag_id' => '481fc6d0-b920-43e0-e50f-6d1740cf8569'],
    ];
}
