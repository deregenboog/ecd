<?php
/**
 * TestManagerTest file.
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) Tests <http://book.cakephp.org/1.3/en/The-Manual/Common-Tasks-With-CakePHP/Testing.html>
 * Copyright 2005-2012, Cake Software Foundation, Inc.
 *								1785 E. Sahara Avenue, Suite 490-204
 *								Las Vegas, Nevada 89104
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
 * TestManagerTest class.
 */
class TestManagerTest extends CakeTestCase
{
    /**
     * setUp method.
     */
    public function setUp()
    {
        $this->TestManager = new TestManager();
        $this->Reporter = new CakeHtmlReporter();
    }

    /**
     * testRunAllTests method.
     */
    public function testRunAllTests()
    {
        $folder = new Folder($this->TestManager->_getTestsPath());
        $extension = str_replace('.', '\.', $this->TestManager->getExtension('test'));
        $out = $folder->findRecursive('.*'.$extension);

        $reporter = new CakeHtmlReporter();
        $list = $this->TestManager->runAllTests($reporter, true);

        $this->assertEqual(count($out), count($list));
    }

    /**
     * testRunTestCase method.
     */
    public function testRunTestCase()
    {
        $file = md5(time());
        $result = $this->TestManager->runTestCase($file, $this->Reporter);
        $this->assertError('Test case '.$file.' cannot be found');
        $this->assertFalse($result);

        $file = str_replace(CORE_TEST_CASES, '', __FILE__);
        $result = $this->TestManager->runTestCase($file, $this->Reporter, true);
        $this->assertTrue($result);
    }

    /**
     * testRunGroupTest method.
     */
    public function testRunGroupTest()
    {
    }

    /**
     * testAddTestCasesFromDirectory method.
     */
    public function testAddTestCasesFromDirectory()
    {
    }

    /**
     * testAddTestFile method.
     */
    public function testAddTestFile()
    {
    }

    /**
     * testGetTestCaseList method.
     */
    public function testGetTestCaseList()
    {
    }

    /**
     * testGetGroupTestList method.
     */
    public function testGetGroupTestList()
    {
    }
}
