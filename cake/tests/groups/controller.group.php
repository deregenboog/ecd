<?php
/**
 * ControllerGroupTest file.
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
 * @since         CakePHP(tm) v 1.2.0.4206
 *
 * @license       http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */

/**
 * ControllerGroupTest.
 */
class ControllerGroupTest extends TestSuite
{
    /**
     * label property.
     *
     * @var string 'All cake/libs/controller/* (Not yet implemented)'
     */
    public $label = 'Component, Controllers, Scaffold test cases.';

    /**
     * LibControllerGroupTest method.
     */
    public function ControllerGroupTest()
    {
        TestManager::addTestFile($this, CORE_TEST_CASES.DS.'libs'.DS.'controller'.DS.'controller');
        TestManager::addTestFile($this, CORE_TEST_CASES.DS.'libs'.DS.'controller'.DS.'scaffold');
        TestManager::addTestFile($this, CORE_TEST_CASES.DS.'libs'.DS.'controller'.DS.'pages_controller');
        TestManager::addTestFile($this, CORE_TEST_CASES.DS.'libs'.DS.'controller'.DS.'component');
        TestManager::addTestFile($this, CORE_TEST_CASES.DS.'libs'.DS.'controller'.DS.'controller_merge_vars');
    }
}
