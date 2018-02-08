<?php
/**
 * NoCrossContaminationGroupTest file.
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
 * NoCrossContaminationGroupTest class.
 *
 * This test group will run all tests
 * that are proper isolated to be run in sequence
 * without affected each other
 */
class NoCrossContaminationGroupTest extends TestSuite
{
    /**
     * label property.
     *
     * @var string
     */
    public $label = 'No Cross Contamination';

    /**
     * blacklist property.
     *
     * @var string
     */
    public $blacklist = ['cake_test_case.test.php', 'object.test.php'];

    /**
     * NoCrossContaminationGroupTest method.
     */
    public function NoCrossContaminationGroupTest()
    {
        App::import('Core', 'Folder');

        $Folder = new Folder(CORE_TEST_CASES);

        foreach ($Folder->findRecursive('.*\.test\.php', true) as $file) {
            if (in_array(basename($file), $this->blacklist)) {
                continue;
            }
            TestManager::addTestFile($this, $file);
        }
    }
}
