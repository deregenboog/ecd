<?php
/**
 * UUID Tree behavior fixture.
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
 * @since         CakePHP(tm) v 1.2.0.7984
 *
 * @license       http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */

/**
 * UuidTreeFixture class.
 *
 * @uses          \CakeTestFixture
 */
class UuidTreeFixture extends CakeTestFixture
{
    /**
     * name property.
     *
     * @var string 'UuidTree'
     */
    public $name = 'UuidTree';

    /**
     * fields property.
     *
     * @var array
     */
    public $fields = [
        'id' => ['type' => 'string', 'length' => 36, 'key' => 'primary'],
        'name' => ['type' => 'string', 'null' => false],
        'parent_id' => ['type' => 'string', 'length' => 36, 'null' => true],
        'lft' => ['type' => 'integer', 'null' => false],
        'rght' => ['type' => 'integer', 'null' => false],
    ];
}
