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
class CounterCacheUserFixture extends CakeTestFixture
{
    public $name = 'CounterCacheUser';

    public $fields = [
        'id' => ['type' => 'integer', 'key' => 'primary'],
        'name' => ['type' => 'string', 'length' => 255, 'null' => false],
        'post_count' => ['type' => 'integer', 'null' => true],
    ];

    public $records = [
        ['id' => 66, 'name' => 'Alexander', 'post_count' => 2],
        ['id' => 301, 'name' => 'Steven', 'post_count' => 1],
    ];
}
