<?php
/**
 * CakeBaseReporter contains common functionality to all cake test suite reporters.
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
 * @see          http://cakephp.org CakePHP(tm) Project
 * @since         CakePHP(tm) v 1.3
 *
 * @license       http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */

/**
 * CakeBaseReporter contains common reporting features used in the CakePHP Test suite.
 */
class CakeBaseReporter extends SimpleReporter
{
    /**
     * Time the test runs started.
     *
     * @var int
     */
    public $_timeStart = 0;

    /**
     * Time the test runs ended.
     *
     * @var int
     */
    public $_timeEnd = 0;

    /**
     * Duration of all test methods.
     *
     * @var int
     */
    public $_timeDuration = 0;

    /**
     * Array of request parameters.  Usually parsed GET params.
     *
     * @var array
     */
    public $params = [];

    /**
     * Character set for the output of test reporting.
     *
     * @var string
     */
    public $_characterSet;

    /**
     * Does nothing yet. The first output will
     * be sent on the first test start.
     *
     * ### Params
     *
     * - show_passes - Should passes be shown
     * - plugin - Plugin test being run?
     * - app - App test being run.
     * - case - The case being run
     * - codeCoverage - Whether the case/group being run is being code covered.
     *
     * @param string $charset The character set to output with. Defaults to UTF-8
     * @param array  $params  Array of request parameters the reporter should use. See above.
     */
    public function CakeBaseReporter($charset = 'utf-8', $params = [])
    {
        $this->SimpleReporter();
        if (!$charset) {
            $charset = 'utf-8';
        }
        $this->_characterSet = $charset;
        $this->params = $params;
    }

    /**
     * Signals / Paints the beginning of a TestSuite executing.
     * Starts the timer for the TestSuite execution time.
     *
     * @param string $test_name name of the test that is being run
     * @param int    $size
     */
    public function paintGroupStart($test_name, $size)
    {
        if (empty($this->_timeStart)) {
            $this->_timeStart = $this->_getTime();
        }
        parent::paintGroupStart($test_name, $size);
    }

    /**
     * Signals/Paints the end of a TestSuite. All test cases have run
     * and timers are stopped.
     *
     * @param string $test_name name of the test that is being run
     */
    public function paintGroupEnd($test_name)
    {
        $this->_timeEnd = $this->_getTime();
        $this->_timeDuration = $this->_timeEnd - $this->_timeStart;
        parent::paintGroupEnd($test_name);
    }

    /**
     * Paints the beginning of a test method being run.  This is used
     * to start/resume the code coverage tool.
     *
     * @param string $method the method name being run
     */
    public function paintMethodStart($method)
    {
        parent::paintMethodStart($method);
        if (!empty($this->params['codeCoverage'])) {
            CodeCoverageManager::start();
        }
    }

    /**
     * Paints the end of a test method being run.  This is used
     * to pause the collection of code coverage if its being used.
     *
     * @param string $method the name of the method being run
     */
    public function paintMethodEnd($method)
    {
        parent::paintMethodEnd($method);
        if (!empty($this->params['codeCoverage'])) {
            CodeCoverageManager::stop();
        }
    }

    /**
     * Get the current time in microseconds. Similar to getMicrotime in basics.php
     * but in a separate function to reduce dependancies.
     *
     * @return float Time in microseconds
     */
    public function _getTime()
    {
        list($usec, $sec) = explode(' ', microtime());

        return (float) $sec + (float) $usec;
    }

    /**
     * Retrieves a list of test cases from the active Manager class,
     * displaying it in the correct format for the reporter subclass.
     *
     * @return mixed
     */
    public function testCaseList()
    {
        $testList = TestManager::getTestCaseList();

        return $testList;
    }

    /**
     * Retrieves a list of group test cases from the active Manager class
     * displaying it in the correct format for the reporter subclass.
     */
    public function groupTestList()
    {
        $testList = TestManager::getGroupTestList();

        return $testList;
    }

    /**
     * Paints the start of the response from the test suite.
     * Used to paint things like head elements in an html page.
     */
    public function paintDocumentStart()
    {
    }

    /**
     * Paints the end of the response from the test suite.
     * Used to paint things like </body> in an html page.
     */
    public function paintDocumentEnd()
    {
    }

    /**
     * Paint a list of test sets, core, app, and plugin test sets
     * available.
     */
    public function paintTestMenu()
    {
    }

    /**
     * Get the baseUrl if one is available.
     *
     * @return string the base url for the request
     */
    public function baseUrl()
    {
        if (!empty($_SERVER['PHP_SELF'])) {
            return $_SERVER['PHP_SELF'];
        }

        return '';
    }
}
