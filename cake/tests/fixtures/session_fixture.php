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
class SessionFixture extends CakeTestFixture
{
    /**
     * name property.
     *
     * @var string 'Session'
     */
    public $name = 'Session';

    /**
     * table property.
     *
     * @var string
     */
    // var $table = 'sessions';

    /**
     * fields property.
     *
     * @var array
     */
    public $fields = [
        'id' => ['type' => 'string', 'length' => 255, 'key' => 'primary'],
        'data' => ['type' => 'text', 'null' => true],
        'expires' => ['type' => 'integer', 'length' => 11, 'null' => true],
    ];

    /**
     * records property.
     *
     * @var array
     */
    public $records = [];
}
