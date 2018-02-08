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
class UuidTagFixture extends CakeTestFixture
{
    /**
     * name property.
     *
     * @var string 'UuidTag'
     */
    public $name = 'UuidTag';

    /**
     * fields property.
     *
     * @var array
     */
    public $fields = [
        'id' => ['type' => 'string', 'length' => 36, 'key' => 'primary'],
        'name' => ['type' => 'string', 'length' => 255],
        'created' => ['type' => 'datetime'],
];

    /**
     * records property.
     *
     * @var array
     */
    public $records = [
        ['id' => '481fc6d0-b920-43e0-e50f-6d1740cf8569', 'name' => 'MyTag', 'created' => '2009-12-09 12:30:00'],
    ];
}
