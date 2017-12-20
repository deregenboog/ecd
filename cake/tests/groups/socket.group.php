<?php
/**
 * Socket and HttpSocket Group tests.
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) Tests <http://book.cakephp.org/1.3/en/The-Manual/Common-Tasks-With-CakePHP/Testing.html>
 * Copyright 2005-2012, Cake Software Foundation, Inc.
 *
 *  Licensed under The Open Group Test Suite License
 *  Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc.
 *
 * @see          http://book.cakephp.org/1.3/en/The-Manual/Common-Tasks-With-CakePHP/Testing.html CakePHP(tm) Tests
 * @since         CakePHP(tm) v 1.2.0.4206
 *
 * @license       http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */

/** Socket and HttpSocket tests
 *
 * This test group will run socket class tests (socket, http_socket).
 */

/**
 * SocketGroupTest class.
 */
class SocketGroupTest extends TestSuite
{
    /**
     * label property.
     *
     * @var string 'Socket and HttpSocket tests'
     */
    public $label = 'CakeSocket and HttpSocket tests';

    /**
     * SocketGroupTest method.
     */
    public function SocketGroupTest()
    {
        TestManager::addTestFile($this, CORE_TEST_CASES.DS.'libs'.DS.'cake_socket');
        TestManager::addTestFile($this, CORE_TEST_CASES.DS.'libs'.DS.'http_socket');
    }
}
