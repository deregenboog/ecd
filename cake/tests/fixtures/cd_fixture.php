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
 * @since         CakePHP(tm) v 1.2.0.7198
 *
 * @license       http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */

/**
 * Short description for class.
 */
class CdFixture extends CakeTestFixture
{
    /**
     * name property.
     *
     * @var string 'Cd'
     */
    public $name = 'Cd';

    /**
     * fields property.
     *
     * @var array
     */
    public $fields = [
        'id' => ['type' => 'integer', 'key' => 'primary'],
        'title' => ['type' => 'string', 'length' => 255],
        'artist' => ['type' => 'string', 'length' => 255, 'null' => true],
        'genre' => ['type' => 'string', 'length' => 255, 'null' => true],
    ];

    /**
     * records property.
     *
     * @var array
     */
    public $records = [
        ['id' => 1, 'title' => 'Grace', 'artist' => 'Jeff Buckley', 'genre' => 'awesome'],
    ];
}
