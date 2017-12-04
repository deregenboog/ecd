<?php
/**
 * Counter Cache Test Fixtures.
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
class CounterCachePostFixture extends CakeTestFixture
{
    public $name = 'CounterCachePost';

    public $fields = [
        'id' => ['type' => 'integer', 'key' => 'primary'],
        'title' => ['type' => 'string', 'length' => 255, 'null' => false],
        'user_id' => ['type' => 'integer', 'null' => true],
    ];

    public $records = [
        ['id' => 1, 'title' => 'Rock and Roll',  'user_id' => 66],
        ['id' => 2, 'title' => 'Music',   'user_id' => 66],
        ['id' => 3, 'title' => 'Food',   'user_id' => 301],
    ];
}
