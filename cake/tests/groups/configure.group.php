<?php
/**
 * ConfigureGroupTest file.
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
 * ConfigureGroupTest class.
 *
 * This test group will run all test for the configure and loader.
 */
class ConfigureGroupTest extends TestSuite
{
    /**
     * label property.
     *
     * @var string 'Configure, Loader, ClassRegistry Tests'
     */
    public $label = 'Configure, App and ClassRegistry';

    /**
     * ConfigureGroupTest method.
     */
    public function ConfigureGroupTest()
    {
        TestManager::addTestFile($this, CORE_TEST_CASES.DS.'libs'.DS.'configure');
        TestManager::addTestFile($this, CORE_TEST_CASES.DS.'libs'.DS.'class_registry');
    }
}
