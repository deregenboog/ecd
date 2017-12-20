<?php
/**
 * TestSuiteGroupTest file.
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

/**
 * TestSuiteGroupTest class.
 *
 * This test group will run the test cases for the test suite classes.
 */
class TestSuiteGroupTest extends TestSuite
{
    /**
     * label property.
     *
     * @var string 'Socket and HttpSocket tests'
     */
    public $label = 'TestSuite';

    /**
     * TestSuiteGroupTest method.
     */
    public function TestSuiteGroupTest()
    {
        TestManager::addTestFile($this, CORE_TEST_CASES.DS.'libs'.DS.'test_manager');
        TestManager::addTestFile($this, CORE_TEST_CASES.DS.'libs'.DS.'code_coverage_manager');
        TestManager::addTestFile($this, CORE_TEST_CASES.DS.'libs'.DS.'cake_test_case');
        TestManager::addTestFile($this, CORE_TEST_CASES.DS.'libs'.DS.'cake_test_fixture');
    }
}
