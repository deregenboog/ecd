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
 * @since         CakePHP(tm) v 1.2.0.6700
 *
 * @license       http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */

/**
 * Short description for class.
 */
class DataTestFixture extends CakeTestFixture
{
    /**
     * name property.
     *
     * @var string 'DataTest'
     */
    public $name = 'DataTest';

    /**
     * fields property.
     *
     * @var array
     */
    public $fields = [
        'id' => ['type' => 'integer', 'key' => 'primary'],
        'count' => ['type' => 'integer', 'default' => 0],
        'float' => ['type' => 'float', 'default' => 0],
        //'timestamp' => array('type' => 'timestamp', 'default' => null, 'null' => true),
        'created' => ['type' => 'datetime', 'default' => null],
        'updated' => ['type' => 'datetime', 'default' => null],
    ];

    /**
     * records property.
     *
     * @var array
     */
    public $records = [
        [
            'count' => 2,
            'float' => 2.4,
            'created' => '2010-09-06 12:28:00',
            'updated' => '2010-09-06 12:28:00',
        ],
    ];
}
