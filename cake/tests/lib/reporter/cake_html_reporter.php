<?php
/**
 * CakeHtmlReporter.
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
 * @since         CakePHP(tm) v 1.2.0.4433
 *
 * @license       http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */
include_once dirname(__FILE__).DS.'cake_base_reporter.php';

/**
 * CakeHtmlReporter Reports Results of TestSuites and Test Cases
 * in an HTML format / context.
 */
class CakeHtmlReporter extends CakeBaseReporter
{
    /**
     * Constructor.
     *
     * @param string $charset
     * @param string $params
     */
    public function CakeHtmlReporter($charset = 'utf-8', $params = [])
    {
        $params = array_map([$this, '_htmlEntities'], $params);
        $this->CakeBaseReporter($charset, $params);
    }

    /**
     * Paints the top of the web page setting the
     * title to the name of the starting test.
     *
     * @param string $test_name name class of test
     */
    public function paintHeader($testName)
    {
        $this->sendNoCacheHeaders();
        $this->paintDocumentStart();
        $this->paintTestMenu();
        printf("<h2>%s</h2>\n", $this->_htmlEntities($testName));
        echo "<ul class='tests'>\n";
    }

    /**
     * Paints the document start content contained in header.php.
     */
    public function paintDocumentStart()
    {
        ob_start();
        $baseDir = $this->params['baseDir'];
        include CAKE_TESTS_LIB.'templates'.DS.'header.php';
    }

    /**
     * Paints the menu on the left side of the test suite interface.
     * Contains all of the various plugin, core, and app buttons.
     */
    public function paintTestMenu()
    {
        $groups = $this->baseUrl().'?show=groups';
        $cases = $this->baseUrl().'?show=cases';
        $plugins = App::objects('plugin', null, false);
        sort($plugins);
        include CAKE_TESTS_LIB.'templates'.DS.'menu.php';
    }

    /**
     * Retrieves and paints the list of tests cases in an HTML format.
     */
    public function testCaseList()
    {
        $testCases = parent::testCaseList();
        $app = $this->params['app'];
        $plugin = $this->params['plugin'];

        $buffer = "<h3>Core Test Cases:</h3>\n<ul>";
        $urlExtra = null;
        if ($app) {
            $buffer = "<h3>App Test Cases:</h3>\n<ul>";
            $urlExtra = '&app=true';
        } elseif ($plugin) {
            $buffer = '<h3>'.Inflector::humanize($plugin)." Test Cases:</h3>\n<ul>";
            $urlExtra = '&plugin='.$plugin;
        }

        if (1 > count($testCases)) {
            $buffer .= '<strong>EMPTY</strong>';
        }

        foreach ($testCases as $testCaseFile => $testCase) {
            $title = explode(strpos($testCase, '\\') ? '\\' : '/', str_replace('.test.php', '', $testCase));
            $title[count($title) - 1] = Inflector::camelize($title[count($title) - 1]);
            $title = implode(' / ', $title);
            $buffer .= "<li><a href='".$this->baseUrl().'?case='.urlencode($testCase).$urlExtra."'>".$title."</a></li>\n";
        }
        $buffer .= "</ul>\n";
        echo $buffer;
    }

    /**
     * Retrieves and paints the list of group tests in an HTML format.
     */
    public function groupTestList()
    {
        $groupTests = parent::groupTestList();
        $app = $this->params['app'];
        $plugin = $this->params['plugin'];

        $buffer = "<h3>Core Test Groups:</h3>\n<ul>";
        $urlExtra = null;
        if ($app) {
            $buffer = "<h3>App Test Groups:</h3>\n<ul>";
            $urlExtra = '&app=true';
        } elseif ($plugin) {
            $buffer = '<h3>'.Inflector::humanize($plugin)." Test Groups:</h3>\n<ul>";
            $urlExtra = '&plugin='.$plugin;
        }

        $buffer .= "<li><a href='".$this->baseURL()."?group=all$urlExtra'>All tests</a></li>\n";

        foreach ($groupTests as $groupTest) {
            $buffer .= "<li><a href='".$this->baseURL()."?group={$groupTest}"."{$urlExtra}'>".$groupTest."</a></li>\n";
        }
        $buffer .= "</ul>\n";
        echo $buffer;
    }

    /**
     * Send the headers necessary to ensure the page is
     * reloaded on every request. Otherwise you could be
     * scratching your head over out of date test data.
     */
    public function sendNoCacheHeaders()
    {
        if (!headers_sent()) {
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
            header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
            header('Cache-Control: no-store, no-cache, must-revalidate');
            header('Cache-Control: post-check=0, pre-check=0', false);
            header('Pragma: no-cache');
        }
    }

    /**
     * Paints the end of the test with a summary of
     * the passes and failures.
     *
     * @param string $test_name name class of test
     */
    public function paintFooter($test_name)
    {
        $colour = ($this->getFailCount() + $this->getExceptionCount() > 0 ? 'red' : 'green');
        echo "</ul>\n";
        echo '<div style="';
        echo "padding: 8px; margin: 1em 0; background-color: $colour; color: white;";
        echo '">';
        echo $this->getTestCaseProgress().'/'.$this->getTestCaseCount();
        echo " test cases complete:\n";
        echo '<strong>'.$this->getPassCount().'</strong> passes, ';
        echo '<strong>'.$this->getFailCount().'</strong> fails and ';
        echo '<strong>'.$this->getExceptionCount().'</strong> exceptions.';
        echo "</div>\n";
        echo '<div style="padding:0 0 5px;">';
        echo '<p><strong>Time taken by tests (in seconds):</strong> '.$this->_timeDuration.'</p>';
        if (function_exists('memory_get_peak_usage')) {
            echo '<p><strong>Peak memory use: (in bytes):</strong> '.number_format(memory_get_peak_usage()).'</p>';
        }
        echo $this->_paintLinks();
        echo '</div>';
        if (
            isset($this->params['codeCoverage']) &&
            $this->params['codeCoverage'] &&
            class_exists('CodeCoverageManager')
        ) {
            CodeCoverageManager::report();
        }
        $this->paintDocumentEnd();
    }

    /**
     * Renders the links that for accessing things in the test suite.
     */
    public function _paintLinks()
    {
        $show = $query = [];
        if (!empty($this->params['group'])) {
            $show['show'] = 'groups';
        } elseif (!empty($this->params['case'])) {
            $show['show'] = 'cases';
        }

        if (!empty($this->params['app'])) {
            $show['app'] = $query['app'] = 'true';
        }
        if (!empty($this->params['plugin'])) {
            $show['plugin'] = $query['plugin'] = $this->params['plugin'];
        }
        if (!empty($this->params['case'])) {
            $query['case'] = $this->params['case'];
        } elseif (!empty($this->params['group'])) {
            $query['group'] = $this->params['group'];
        }
        $show = $this->_queryString($show);
        $query = $this->_queryString($query);

        echo "<p><a href='".$this->baseUrl().$show."'>Run more tests</a> | <a href='".$this->baseUrl().$query."&show_passes=1'>Show Passes</a> | \n";
        echo " <a href='".$this->baseUrl().$query."&amp;code_coverage=true'>Analyze Code Coverage</a></p>\n";
    }

    /**
     * Convert an array of parameters into a query string url.
     *
     * @param array $url Url hash to be converted
     *
     * @return string Converted url query string
     */
    public function _queryString($url)
    {
        $out = '?';
        $params = [];
        foreach ($url as $key => $value) {
            $params[] = "$key=$value";
        }
        $out .= implode('&amp;', $params);

        return $out;
    }

    /**
     * Paints the end of the document html.
     */
    public function paintDocumentEnd()
    {
        $baseDir = $this->params['baseDir'];
        include CAKE_TESTS_LIB.'templates'.DS.'footer.php';
        ob_end_flush();
    }

    /**
     * Paints the test failure with a breadcrumbs
     * trail of the nesting test suites below the
     * top level test.
     *
     * @param string $message failure message displayed in
     *                        the context of the other tests
     */
    public function paintFail($message)
    {
        parent::paintFail($message);
        echo "<li class='fail'>\n";
        echo '<span>Failed</span>';
        echo "<div class='msg'>".$this->_htmlEntities($message)."</div>\n";
        $breadcrumb = $this->getTestList();
        array_shift($breadcrumb);
        echo '<div>'.implode(' -&gt; ', $breadcrumb)."</div>\n";
        echo "</li>\n";
    }

    /**
     * Paints the test pass with a breadcrumbs
     * trail of the nesting test suites below the
     * top level test.
     *
     * @param string $message pass message displayed in the context of the other tests
     */
    public function paintPass($message)
    {
        parent::paintPass($message);

        if (isset($this->params['show_passes']) && $this->params['show_passes']) {
            echo "<li class='pass'>\n";
            echo '<span>Passed</span> ';
            $breadcrumb = $this->getTestList();
            array_shift($breadcrumb);
            echo implode(' -&gt; ', $breadcrumb);
            echo '<br />'.$this->_htmlEntities($message)."\n";
            echo "</li>\n";
        }
    }

    /**
     * Paints a PHP error.
     *
     * @param string $message message is ignored
     */
    public function paintError($message)
    {
        parent::paintError($message);
        echo "<li class='error'>\n";
        echo '<span>Error</span>';
        echo "<div class='msg'>".$this->_htmlEntities($message)."</div>\n";
        $breadcrumb = $this->getTestList();
        array_shift($breadcrumb);
        echo '<div>'.implode(' -&gt; ', $breadcrumb)."</div>\n";
        echo "</li>\n";
    }

    /**
     * Paints a PHP exception.
     *
     * @param Exception $exception exception to display
     */
    public function paintException($exception)
    {
        parent::paintException($exception);
        echo "<li class='fail'>\n";
        echo '<span>Exception</span>';
        $message = 'Unexpected exception of type ['.get_class($exception).
            '] with message ['.$exception->getMessage().
            '] in ['.$exception->getFile().
            ' line '.$exception->getLine().']';
        echo "<div class='msg'>".$this->_htmlEntities($message)."</div>\n";
        $breadcrumb = $this->getTestList();
        array_shift($breadcrumb);
        echo '<div>'.implode(' -&gt; ', $breadcrumb)."</div>\n";
        echo "</li>\n";
    }

    /**
     * Prints the message for skipping tests.
     *
     * @param string $message text of skip condition
     */
    public function paintSkip($message)
    {
        parent::paintSkip($message);
        echo "<li class='skipped'>\n";
        echo '<span>Skipped</span> ';
        echo $this->_htmlEntities($message);
        echo "</li>\n";
    }

    /**
     * Paints formatted text such as dumped variables.
     *
     * @param string $message text to show
     */
    public function paintFormattedMessage($message)
    {
        echo '<pre>'.$this->_htmlEntities($message).'</pre>';
    }

    /**
     * Character set adjusted entity conversion.
     *
     * @param string $message plain text or Unicode message
     *
     * @return string browser readable message
     */
    public function _htmlEntities($message)
    {
        return htmlentities($message, ENT_COMPAT, $this->_characterSet);
    }
}
