<?php
/**
 * XmlGroupTest file.
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
 * XmlGroupTest class.
 *
 * This test group will run view class tests (view, theme).
 */
class XmlGroupTest extends TestSuite
{
    /**
     * label property.
     *
     * @var string 'All core views'
     */
    public $label = 'Xml based classes (Xml, XmlHelper and RssHelper)';

    /**
     * XmlGroupTest method.
     */
    public function XmlGroupTest()
    {
        TestManager::addTestFile($this, CORE_TEST_CASES.DS.'libs'.DS.'xml');
        TestManager::addTestFile($this, CORE_TEST_CASES.DS.'libs'.DS.'view'.DS.'helpers'.DS.'rss');
        TestManager::addTestFile($this, CORE_TEST_CASES.DS.'libs'.DS.'view'.DS.'helpers'.DS.'xml');
    }
}
