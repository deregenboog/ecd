<?php
/**
 * HtmlHelperTest file.
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
App::import('Core', ['Helper', 'AppHelper', 'ClassRegistry', 'Controller', 'Model']);
App::import('Helper', ['Html', 'Form']);

if (!defined('FULL_BASE_URL')) {
    define('FULL_BASE_URL', 'http://cakephp.org');
}

/**
 * TheHtmlTestController class.
 */
class TheHtmlTestController extends Controller
{
    /**
     * name property.
     *
     * @var string 'TheTest'
     */
    public $name = 'TheTest';

    /**
     * uses property.
     *
     * @var mixed null
     */
    public $uses = null;
}

Mock::generate('View', 'HtmlHelperMockView');

/**
 * HtmlHelperTest class.
 */
class HtmlHelperTest extends CakeTestCase
{
    /**
     * Regexp for CDATA start block.
     *
     * @var string
     */
    public $cDataStart = 'preg:/^\/\/<!\[CDATA\[[\n\r]*/';
    /**
     * Regexp for CDATA end block.
     *
     * @var string
     */
    public $cDataEnd = 'preg:/[^\]]*\]\]\>[\s\r\n]*/';
    /**
     * html property.
     *
     * @var object
     */
    public $Html = null;

    /**
     * Backup of app encoding configuration setting.
     *
     * @var string
     */
    public $_appEncoding;

    /**
     * Backup of asset configuration settings.
     *
     * @var string
     */
    public $_asset;

    /**
     * Backup of debug configuration setting.
     *
     * @var int
     */
    public $_debug;

    /**
     * setUp method.
     */
    public function startTest()
    {
        $this->Html = new HtmlHelper();
        $view = new View(new TheHtmlTestController());
        ClassRegistry::addObject('view', $view);
        $this->_appEncoding = Configure::read('App.encoding');
        $this->_asset = Configure::read('Asset');
        $this->_debug = Configure::read('debug');
    }

    /**
     * endTest method.
     */
    public function endTest()
    {
        Configure::write('App.encoding', $this->_appEncoding);
        Configure::write('Asset', $this->_asset);
        Configure::write('debug', $this->_debug);
        ClassRegistry::flush();
        unset($this->Html);
    }

    /**
     * testDocType method.
     */
    public function testDocType()
    {
        $result = $this->Html->docType();
        $expected = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">';
        $this->assertEqual($result, $expected);

        $result = $this->Html->docType('html4-strict');
        $expected = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">';
        $this->assertEqual($result, $expected);

        $this->assertNull($this->Html->docType('non-existing-doctype'));
    }

    /**
     * testLink method.
     */
    public function testLink()
    {
        $result = $this->Html->link('/home');
        $expected = ['a' => ['href' => '/home'], 'preg:/\/home/', '/a'];
        $this->assertTags($result, $expected);

        $result = $this->Html->link('Posts', ['controller' => 'posts', 'action' => 'index', 'full_base' => true]);
        $expected = ['a' => ['href' => FULL_BASE_URL.'/posts'], 'Posts', '/a'];
        $this->assertTags($result, $expected);

        $result = $this->Html->link('Home', '/home', ['confirm' => 'Are you sure you want to do this?']);
        $expected = [
            'a' => ['href' => '/home', 'onclick' => 'return confirm(&#039;Are you sure you want to do this?&#039;);'],
            'Home',
            '/a',
        ];
        $this->assertTags($result, $expected, true);

        $result = $this->Html->link('Home', '/home', ['default' => false]);
        $expected = [
            'a' => ['href' => '/home', 'onclick' => 'event.returnValue = false; return false;'],
            'Home',
            '/a',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Html->link('Next >', '#');
        $expected = [
            'a' => ['href' => '#'],
            'Next &gt;',
            '/a',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Html->link('Next >', '#', ['escape' => true]);
        $expected = [
            'a' => ['href' => '#'],
            'Next &gt;',
            '/a',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Html->link('Next >', '#', ['escape' => 'utf-8']);
        $expected = [
            'a' => ['href' => '#'],
            'Next &gt;',
            '/a',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Html->link('Next >', '#', ['escape' => false]);
        $expected = [
            'a' => ['href' => '#'],
            'Next >',
            '/a',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Html->link('Next >', '#', [
            'title' => 'to escape &#8230; or not escape?',
            'escape' => false,
        ]);
        $expected = [
            'a' => ['href' => '#', 'title' => 'to escape &#8230; or not escape?'],
            'Next >',
            '/a',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Html->link('Next >', '#', [
            'title' => 'to escape &#8230; or not escape?',
            'escape' => true,
        ]);
        $expected = [
            'a' => ['href' => '#', 'title' => 'to escape &amp;#8230; or not escape?'],
            'Next &gt;',
            '/a',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Html->link('Original size', [
            'controller' => 'images', 'action' => 'view', 3, '?' => ['height' => 100, 'width' => 200],
        ]);
        $expected = [
            'a' => ['href' => '/images/view/3?height=100&amp;width=200'],
            'Original size',
            '/a',
        ];
        $this->assertTags($result, $expected);

        Configure::write('Asset.timestamp', false);

        $result = $this->Html->link($this->Html->image('test.gif'), '#', ['escape' => false]);
        $expected = [
            'a' => ['href' => '#'],
            'img' => ['src' => 'img/test.gif', 'alt' => ''],
            '/a',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Html->image('test.gif', ['url' => '#']);
        $expected = [
            'a' => ['href' => '#'],
            'img' => ['src' => 'img/test.gif', 'alt' => ''],
            '/a',
        ];
        $this->assertTags($result, $expected);

        Configure::write('Asset.timestamp', 'force');

        $result = $this->Html->link($this->Html->image('test.gif'), '#', ['escape' => false]);
        $expected = [
            'a' => ['href' => '#'],
            'img' => ['src' => 'preg:/img\/test\.gif\?\d*/', 'alt' => ''],
            '/a',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Html->image('test.gif', ['url' => '#']);
        $expected = [
            'a' => ['href' => '#'],
            'img' => ['src' => 'preg:/img\/test\.gif\?\d*/', 'alt' => ''],
            '/a',
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * testImageTag method.
     */
    public function testImageTag()
    {
        Configure::write('Asset.timestamp', false);

        $result = $this->Html->image('test.gif');
        $this->assertTags($result, ['img' => ['src' => 'img/test.gif', 'alt' => '']]);

        $result = $this->Html->image('http://google.com/logo.gif');
        $this->assertTags($result, ['img' => ['src' => 'http://google.com/logo.gif', 'alt' => '']]);

        $result = $this->Html->image(['controller' => 'test', 'action' => 'view', 1, 'ext' => 'gif']);
        $this->assertTags($result, ['img' => ['src' => '/test/view/1.gif', 'alt' => '']]);

        $result = $this->Html->image('/test/view/1.gif');
        $this->assertTags($result, ['img' => ['src' => '/test/view/1.gif', 'alt' => '']]);
    }

    /**
     * test image() with Asset.timestamp.
     */
    public function testImageWithTimestampping()
    {
        Configure::write('Asset.timestamp', 'force');

        $this->Html->webroot = '/';
        $result = $this->Html->image('cake.icon.png');
        $this->assertTags($result, ['img' => ['src' => 'preg:/\/img\/cake\.icon\.png\?\d+/', 'alt' => '']]);

        Configure::write('debug', 0);
        Configure::write('Asset.timestamp', 'force');

        $result = $this->Html->image('cake.icon.png');
        $this->assertTags($result, ['img' => ['src' => 'preg:/\/img\/cake\.icon\.png\?\d+/', 'alt' => '']]);

        $webroot = $this->Html->webroot;
        $this->Html->webroot = '/testing/longer/';
        $result = $this->Html->image('cake.icon.png');
        $expected = [
            'img' => ['src' => 'preg:/\/testing\/longer\/img\/cake\.icon\.png\?[0-9]+/', 'alt' => ''],
        ];
        $this->assertTags($result, $expected);
        $this->Html->webroot = $webroot;
    }

    /**
     * Tests creation of an image tag using a theme and asset timestamping.
     *
     * @see https://trac.cakephp.org/ticket/6490
     */
    public function testImageTagWithTheme()
    {
        if ($this->skipIf(!is_writable(WWW_ROOT.'theme'), 'Cannot write to webroot/theme')) {
            return;
        }
        App::import('Core', 'File');

        $testfile = WWW_ROOT.'theme'.DS.'test_theme'.DS.'img'.DS.'__cake_test_image.gif';
        $file = new File($testfile, true);

        App::build([
            'views' => [TEST_CAKE_CORE_INCLUDE_PATH.'tests'.DS.'test_app'.DS.'views'.DS],
        ]);
        Configure::write('Asset.timestamp', true);
        Configure::write('debug', 1);

        $this->Html->webroot = '/';
        $this->Html->theme = 'test_theme';
        $result = $this->Html->image('__cake_test_image.gif');
        $this->assertTags($result, [
            'img' => [
                'src' => 'preg:/\/theme\/test_theme\/img\/__cake_test_image\.gif\?\d+/',
                'alt' => '',
        ], ]);

        $webroot = $this->Html->webroot;
        $this->Html->webroot = '/testing/';
        $result = $this->Html->image('__cake_test_image.gif');

        $this->assertTags($result, [
            'img' => [
                'src' => 'preg:/\/testing\/theme\/test_theme\/img\/__cake_test_image\.gif\?\d+/',
                'alt' => '',
        ], ]);
        $this->Html->webroot = $webroot;

        $dir = new Folder(WWW_ROOT.'theme'.DS.'test_theme');
        $dir->delete();
    }

    /**
     * test theme assets in main webroot path.
     */
    public function testThemeAssetsInMainWebrootPath()
    {
        Configure::write('Asset.timestamp', false);
        App::build([
            'views' => [TEST_CAKE_CORE_INCLUDE_PATH.'tests'.DS.'test_app'.DS.'views'.DS],
        ]);
        $webRoot = Configure::read('App.www_root');
        Configure::write('App.www_root', TEST_CAKE_CORE_INCLUDE_PATH.'tests'.DS.'test_app'.DS.'webroot'.DS);

        $webroot = $this->Html->webroot;
        $this->Html->theme = 'test_theme';
        $result = $this->Html->css('webroot_test');
        $expected = [
            'link' => ['rel' => 'stylesheet', 'type' => 'text/css', 'href' => 'preg:/.*theme\/test_theme\/css\/webroot_test\.css/'],
        ];
        $this->assertTags($result, $expected);

        $webroot = $this->Html->webroot;
        $this->Html->theme = 'test_theme';
        $result = $this->Html->css('theme_webroot');
        $expected = [
            'link' => ['rel' => 'stylesheet', 'type' => 'text/css', 'href' => 'preg:/.*theme\/test_theme\/css\/theme_webroot\.css/'],
        ];
        $this->assertTags($result, $expected);

        Configure::write('App.www_root', $webRoot);
    }

    /**
     * testStyle method.
     */
    public function testStyle()
    {
        $result = $this->Html->style(['display' => 'none', 'margin' => '10px']);
        $expected = 'display:none; margin:10px;';
        $this->assertPattern('/^display\s*:\s*none\s*;\s*margin\s*:\s*10px\s*;?$/', $expected);

        $result = $this->Html->style(['display' => 'none', 'margin' => '10px'], false);
        $lines = explode("\n", $result);
        $this->assertPattern('/^\s*display\s*:\s*none\s*;\s*$/', $lines[0]);
        $this->assertPattern('/^\s*margin\s*:\s*10px\s*;?$/', $lines[1]);
    }

    /**
     * testCssLink method.
     */
    public function testCssLink()
    {
        Configure::write('Asset.timestamp', false);
        Configure::write('Asset.filter.css', false);

        $result = $this->Html->css('screen');
        $expected = [
            'link' => ['rel' => 'stylesheet', 'type' => 'text/css', 'href' => 'preg:/.*css\/screen\.css/'],
        ];
        $this->assertTags($result, $expected);

        $result = $this->Html->css('screen.css');
        $this->assertTags($result, $expected);

        $result = $this->Html->css('my.css.library');
        $expected['link']['href'] = 'preg:/.*css\/my\.css\.library\.css/';
        $this->assertTags($result, $expected);

        $result = $this->Html->css('screen.css?1234');
        $expected['link']['href'] = 'preg:/.*css\/screen\.css\?1234/';
        $this->assertTags($result, $expected);

        $result = $this->Html->css('http://whatever.com/screen.css?1234');
        $expected['link']['href'] = 'preg:/http:\/\/.*\/screen\.css\?1234/';
        $this->assertTags($result, $expected);

        Configure::write('Asset.filter.css', 'css.php');
        $result = $this->Html->css('cake.generic');
        $expected['link']['href'] = 'preg:/.*ccss\/cake\.generic\.css/';
        $this->assertTags($result, $expected);

        $result = $this->Html->css('//example.com/css/cake.generic.css');
        $expected['link']['href'] = 'preg:/.*example\.com\/css\/cake\.generic\.css/';
        $this->assertTags($result, $expected);

        Configure::write('Asset.filter.css', false);

        $result = explode("\n", trim($this->Html->css(['cake.generic', 'vendor.generic'])));
        $expected['link']['href'] = 'preg:/.*css\/cake\.generic\.css/';
        $this->assertTags($result[0], $expected);
        $expected['link']['href'] = 'preg:/.*css\/vendor\.generic\.css/';
        $this->assertTags($result[1], $expected);
        $this->assertEqual(count($result), 2);

        ClassRegistry::removeObject('view');
        $view = new HtmlHelperMockView();
        ClassRegistry::addObject('view', $view);
        $view->expectAt(0, 'addScript', [new PatternExpectation('/css_in_head.css/')]);
        $result = $this->Html->css('css_in_head', null, ['inline' => false]);
        $this->assertNull($result);

        $view = &ClassRegistry::getObject('view');
        $view->expectAt(1, 'addScript', [new NoPatternExpectation('/inline=""/')]);
        $result = $this->Html->css('more_css_in_head', null, ['inline' => false]);
        $this->assertNull($result);
    }

    /**
     * test use of css() and timestamping.
     */
    public function testCssTimestamping()
    {
        Configure::write('debug', 2);
        Configure::write('Asset.timestamp', true);

        $expected = [
            'link' => ['rel' => 'stylesheet', 'type' => 'text/css', 'href' => ''],
        ];

        $result = $this->Html->css('cake.generic');
        $expected['link']['href'] = 'preg:/.*css\/cake\.generic\.css\?[0-9]+/';
        $this->assertTags($result, $expected);

        Configure::write('debug', 0);

        $result = $this->Html->css('cake.generic');
        $expected['link']['href'] = 'preg:/.*css\/cake\.generic\.css/';
        $this->assertTags($result, $expected);

        Configure::write('Asset.timestamp', 'force');

        $result = $this->Html->css('cake.generic');
        $expected['link']['href'] = 'preg:/.*css\/cake\.generic\.css\?[0-9]+/';
        $this->assertTags($result, $expected);

        $webroot = $this->Html->webroot;
        $this->Html->webroot = '/testing/';
        $result = $this->Html->css('cake.generic');
        $expected['link']['href'] = 'preg:/\/testing\/css\/cake\.generic\.css\?[0-9]+/';
        $this->assertTags($result, $expected);
        $this->Html->webroot = $webroot;

        $webroot = $this->Html->webroot;
        $this->Html->webroot = '/testing/longer/';
        $result = $this->Html->css('cake.generic');
        $expected['link']['href'] = 'preg:/\/testing\/longer\/css\/cake\.generic\.css\?[0-9]+/';
        $this->assertTags($result, $expected);
        $this->Html->webroot = $webroot;
    }

    /**
     * test timestamp enforcement for script tags.
     */
    public function testScriptTimestamping()
    {
        $skip = $this->skipIf(!is_writable(JS), 'webroot/js is not Writable, timestamp testing has been skipped');
        if ($skip) {
            return;
        }
        Configure::write('debug', 2);
        Configure::write('Asset.timestamp', true);

        touch(WWW_ROOT.'js'.DS.'__cake_js_test.js');
        $timestamp = substr(strtotime('now'), 0, 8);

        $result = $this->Html->script('__cake_js_test', ['inline' => true, 'once' => false]);
        $this->assertPattern('/__cake_js_test.js\?'.$timestamp.'[0-9]{2}"/', $result, 'Timestamp value not found %s');

        Configure::write('debug', 0);
        Configure::write('Asset.timestamp', 'force');
        $result = $this->Html->script('__cake_js_test', ['inline' => true, 'once' => false]);
        $this->assertPattern('/__cake_js_test.js\?'.$timestamp.'[0-9]{2}"/', $result, 'Timestamp value not found %s');
        unlink(WWW_ROOT.'js'.DS.'__cake_js_test.js');
        Configure::write('Asset.timestamp', false);
    }

    /**
     * test that scripts added with uses() are only ever included once.
     * test script tag generation.
     */
    public function testScript()
    {
        Configure::write('Asset.timestamp', false);
        $result = $this->Html->script('foo');
        $expected = [
            'script' => ['type' => 'text/javascript', 'src' => 'js/foo.js'],
        ];
        $this->assertTags($result, $expected);

        $result = $this->Html->script(['foobar', 'bar']);
        $expected = [
            ['script' => ['type' => 'text/javascript', 'src' => 'js/foobar.js']],
            '/script',
            ['script' => ['type' => 'text/javascript', 'src' => 'js/bar.js']],
            '/script',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Html->script('jquery-1.3');
        $expected = [
            'script' => ['type' => 'text/javascript', 'src' => 'js/jquery-1.3.js'],
        ];
        $this->assertTags($result, $expected);

        $result = $this->Html->script('test.json');
        $expected = [
            'script' => ['type' => 'text/javascript', 'src' => 'js/test.json.js'],
        ];
        $this->assertTags($result, $expected);

        $result = $this->Html->script('http://example.com/test.json');
        $expected = [
            'script' => ['type' => 'text/javascript', 'src' => 'http://example.com/test.json'],
        ];
        $this->assertTags($result, $expected);

        $result = $this->Html->script('/plugin/js/jquery-1.3.2.js?someparam=foo');
        $expected = [
            'script' => ['type' => 'text/javascript', 'src' => '/plugin/js/jquery-1.3.2.js?someparam=foo'],
        ];
        $this->assertTags($result, $expected);

        $result = $this->Html->script('test.json.js?foo=bar');
        $expected = [
            'script' => ['type' => 'text/javascript', 'src' => 'js/test.json.js?foo=bar'],
        ];
        $this->assertTags($result, $expected);

        $result = $this->Html->script('foo');
        $this->assertNull($result, 'Script returned upon duplicate inclusion %s');

        $result = $this->Html->script(['foo', 'bar', 'baz']);
        $this->assertNoPattern('/foo.js/', $result);

        $result = $this->Html->script('foo', ['inline' => true, 'once' => false]);
        $this->assertNotNull($result);

        $result = $this->Html->script('jquery-1.3.2', ['defer' => true, 'encoding' => 'utf-8']);
        $expected = [
            'script' => ['type' => 'text/javascript', 'src' => 'js/jquery-1.3.2.js', 'defer' => 'defer', 'encoding' => 'utf-8'],
        ];
        $this->assertTags($result, $expected);

        $view = &ClassRegistry::getObject('view');
        $view = new HtmlHelperMockView();
        $view->expectAt(0, 'addScript', [new PatternExpectation('/script_in_head.js/')]);
        $result = $this->Html->script('script_in_head', ['inline' => false]);
        $this->assertNull($result);
    }

    /**
     * Test that Asset.filter.js works.
     */
    public function testScriptAssetFilter()
    {
        Configure::write('Asset.filter.js', 'js.php');

        $result = $this->Html->script('jquery-1.3');
        $expected = [
            'script' => ['type' => 'text/javascript', 'src' => 'cjs/jquery-1.3.js'],
        ];
        $this->assertTags($result, $expected);

        $result = $this->Html->script('//example.com/js/jquery-1.3.js');
        $expected = [
            'script' => ['type' => 'text/javascript', 'src' => '//example.com/js/jquery-1.3.js'],
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * test a script file in the webroot/theme dir.
     */
    public function testScriptInTheme()
    {
        if ($this->skipIf(!is_writable(WWW_ROOT.'theme'), 'Cannot write to webroot/theme')) {
            return;
        }
        App::import('Core', 'File');

        $testfile = WWW_ROOT.'theme'.DS.'test_theme'.DS.'js'.DS.'__test_js.js';
        $file = new File($testfile, true);

        App::build([
            'views' => [TEST_CAKE_CORE_INCLUDE_PATH.'tests'.DS.'test_app'.DS.'views'.DS],
        ]);

        $this->Html->webroot = '/';
        $this->Html->theme = 'test_theme';
        $result = $this->Html->script('__test_js.js');
        $expected = [
            'script' => ['src' => '/theme/test_theme/js/__test_js.js', 'type' => 'text/javascript'],
        ];
        $this->assertTags($result, $expected);
        App::build();
    }

    /**
     * test Script block generation.
     */
    public function testScriptBlock()
    {
        $result = $this->Html->scriptBlock('window.foo = 2;');
        $expected = [
            'script' => ['type' => 'text/javascript'],
            $this->cDataStart,
            'window.foo = 2;',
            $this->cDataEnd,
            '/script',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Html->scriptBlock('window.foo = 2;', ['safe' => false]);
        $expected = [
            'script' => ['type' => 'text/javascript'],
            'window.foo = 2;',
            '/script',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Html->scriptBlock('window.foo = 2;', ['safe' => true]);
        $expected = [
            'script' => ['type' => 'text/javascript'],
            $this->cDataStart,
            'window.foo = 2;',
            $this->cDataEnd,
            '/script',
        ];
        $this->assertTags($result, $expected);

        $view = &ClassRegistry::getObject('view');
        $view = new HtmlHelperMockView();
        $view->expectAt(0, 'addScript', [new PatternExpectation('/window\.foo\s\=\s2;/')]);

        $result = $this->Html->scriptBlock('window.foo = 2;', ['inline' => false]);
        $this->assertNull($result);

        $result = $this->Html->scriptBlock('window.foo = 2;', ['safe' => false, 'encoding' => 'utf-8']);
        $expected = [
            'script' => ['type' => 'text/javascript', 'encoding' => 'utf-8'],
            'window.foo = 2;',
            '/script',
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * test script tag output buffering when using scriptStart() and scriptEnd();.
     */
    public function testScriptStartAndScriptEnd()
    {
        $result = $this->Html->scriptStart(['safe' => true]);
        $this->assertNull($result);
        echo 'this is some javascript';

        $result = $this->Html->scriptEnd();
        $expected = [
            'script' => ['type' => 'text/javascript'],
            $this->cDataStart,
            'this is some javascript',
            $this->cDataEnd,
            '/script',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Html->scriptStart(['safe' => false]);
        $this->assertNull($result);
        echo 'this is some javascript';

        $result = $this->Html->scriptEnd();
        $expected = [
            'script' => ['type' => 'text/javascript'],
            'this is some javascript',
            '/script',
        ];
        $this->assertTags($result, $expected);

        ClassRegistry::removeObject('view');
        $View = new HtmlHelperMockView();

        $View->expectOnce('addScript');
        ClassRegistry::addObject('view', $View);

        $result = $this->Html->scriptStart(['safe' => false, 'inline' => false]);
        $this->assertNull($result);
        echo 'this is some javascript';

        $result = $this->Html->scriptEnd();
        $this->assertNull($result);
    }

    /**
     * testCharsetTag method.
     */
    public function testCharsetTag()
    {
        Configure::write('App.encoding', null);
        $result = $this->Html->charset();
        $this->assertTags($result, ['meta' => ['http-equiv' => 'Content-Type', 'content' => 'text/html; charset=utf-8']]);

        Configure::write('App.encoding', 'ISO-8859-1');
        $result = $this->Html->charset();
        $this->assertTags($result, ['meta' => ['http-equiv' => 'Content-Type', 'content' => 'text/html; charset=iso-8859-1']]);

        $result = $this->Html->charset('UTF-7');
        $this->assertTags($result, ['meta' => ['http-equiv' => 'Content-Type', 'content' => 'text/html; charset=UTF-7']]);
    }

    /**
     * testBreadcrumb method.
     */
    public function testBreadcrumb()
    {
        $this->Html->addCrumb('First', '#first');
        $this->Html->addCrumb('Second', '#second');
        $this->Html->addCrumb('Third', '#third');

        $result = $this->Html->getCrumbs();
        $expected = [
            ['a' => ['href' => '#first']],
            'First',
            '/a',
            '&raquo;',
            ['a' => ['href' => '#second']],
            'Second',
            '/a',
            '&raquo;',
            ['a' => ['href' => '#third']],
            'Third',
            '/a',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Html->getCrumbs(' &gt; ');
        $expected = [
            ['a' => ['href' => '#first']],
            'First',
            '/a',
            ' &gt; ',
            ['a' => ['href' => '#second']],
            'Second',
            '/a',
            ' &gt; ',
            ['a' => ['href' => '#third']],
            'Third',
            '/a',
        ];
        $this->assertTags($result, $expected);

        $this->assertPattern('/^<a[^<>]+>First<\/a> &gt; <a[^<>]+>Second<\/a> &gt; <a[^<>]+>Third<\/a>$/', $result);
        $this->assertPattern('/<a\s+href=["\']+\#first["\']+[^<>]*>First<\/a>/', $result);
        $this->assertPattern('/<a\s+href=["\']+\#second["\']+[^<>]*>Second<\/a>/', $result);
        $this->assertPattern('/<a\s+href=["\']+\#third["\']+[^<>]*>Third<\/a>/', $result);
        $this->assertNoPattern('/<a[^<>]+[^href]=[^<>]*>/', $result);

        $this->Html->addCrumb('Fourth', null);

        $result = $this->Html->getCrumbs();
        $expected = [
            ['a' => ['href' => '#first']],
            'First',
            '/a',
            '&raquo;',
            ['a' => ['href' => '#second']],
            'Second',
            '/a',
            '&raquo;',
            ['a' => ['href' => '#third']],
            'Third',
            '/a',
            '&raquo;',
            'Fourth',
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * testNestedList method.
     */
    public function testNestedList()
    {
        $list = [
            'Item 1',
            'Item 2' => [
                'Item 2.1',
            ],
            'Item 3',
            'Item 4' => [
                'Item 4.1',
                'Item 4.2',
                'Item 4.3' => [
                    'Item 4.3.1',
                    'Item 4.3.2',
                ],
            ],
            'Item 5' => [
                'Item 5.1',
                'Item 5.2',
            ],
        ];

        $result = $this->Html->nestedList($list);
        $expected = [
            '<ul',
            '<li', 'Item 1', '/li',
            '<li', 'Item 2',
            '<ul', '<li', 'Item 2.1', '/li', '/ul',
            '/li',
            '<li', 'Item 3', '/li',
            '<li', 'Item 4',
            '<ul',
            '<li', 'Item 4.1', '/li',
            '<li', 'Item 4.2', '/li',
            '<li', 'Item 4.3',
            '<ul',
            '<li', 'Item 4.3.1', '/li',
            '<li', 'Item 4.3.2', '/li',
            '/ul',
            '/li',
            '/ul',
            '/li',
            '<li', 'Item 5',
            '<ul',
            '<li', 'Item 5.1', '/li',
            '<li', 'Item 5.2', '/li',
            '/ul',
            '/li',
            '/ul',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Html->nestedList($list, null);
        $expected = [
            '<ul',
            '<li', 'Item 1', '/li',
            '<li', 'Item 2',
            '<ul', '<li', 'Item 2.1', '/li', '/ul',
            '/li',
            '<li', 'Item 3', '/li',
            '<li', 'Item 4',
            '<ul',
            '<li', 'Item 4.1', '/li',
            '<li', 'Item 4.2', '/li',
            '<li', 'Item 4.3',
            '<ul',
            '<li', 'Item 4.3.1', '/li',
            '<li', 'Item 4.3.2', '/li',
            '/ul',
            '/li',
            '/ul',
            '/li',
            '<li', 'Item 5',
            '<ul',
            '<li', 'Item 5.1', '/li',
            '<li', 'Item 5.2', '/li',
            '/ul',
            '/li',
            '/ul',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Html->nestedList($list, [], [], 'ol');
        $expected = [
            '<ol',
            '<li', 'Item 1', '/li',
            '<li', 'Item 2',
            '<ol', '<li', 'Item 2.1', '/li', '/ol',
            '/li',
            '<li', 'Item 3', '/li',
            '<li', 'Item 4',
            '<ol',
            '<li', 'Item 4.1', '/li',
            '<li', 'Item 4.2', '/li',
            '<li', 'Item 4.3',
            '<ol',
            '<li', 'Item 4.3.1', '/li',
            '<li', 'Item 4.3.2', '/li',
            '/ol',
            '/li',
            '/ol',
            '/li',
            '<li', 'Item 5',
            '<ol',
            '<li', 'Item 5.1', '/li',
            '<li', 'Item 5.2', '/li',
            '/ol',
            '/li',
            '/ol',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Html->nestedList($list, 'ol');
        $expected = [
            '<ol',
            '<li', 'Item 1', '/li',
            '<li', 'Item 2',
            '<ol', '<li', 'Item 2.1', '/li', '/ol',
            '/li',
            '<li', 'Item 3', '/li',
            '<li', 'Item 4',
            '<ol',
            '<li', 'Item 4.1', '/li',
            '<li', 'Item 4.2', '/li',
            '<li', 'Item 4.3',
            '<ol',
            '<li', 'Item 4.3.1', '/li',
            '<li', 'Item 4.3.2', '/li',
            '/ol',
            '/li',
            '/ol',
            '/li',
            '<li', 'Item 5',
            '<ol',
            '<li', 'Item 5.1', '/li',
            '<li', 'Item 5.2', '/li',
            '/ol',
            '/li',
            '/ol',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Html->nestedList($list, ['class' => 'list']);
        $expected = [
            ['ul' => ['class' => 'list']],
            '<li', 'Item 1', '/li',
            '<li', 'Item 2',
            ['ul' => ['class' => 'list']], '<li', 'Item 2.1', '/li', '/ul',
            '/li',
            '<li', 'Item 3', '/li',
            '<li', 'Item 4',
            ['ul' => ['class' => 'list']],
            '<li', 'Item 4.1', '/li',
            '<li', 'Item 4.2', '/li',
            '<li', 'Item 4.3',
            ['ul' => ['class' => 'list']],
            '<li', 'Item 4.3.1', '/li',
            '<li', 'Item 4.3.2', '/li',
            '/ul',
            '/li',
            '/ul',
            '/li',
            '<li', 'Item 5',
            ['ul' => ['class' => 'list']],
            '<li', 'Item 5.1', '/li',
            '<li', 'Item 5.2', '/li',
            '/ul',
            '/li',
            '/ul',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Html->nestedList($list, [], ['class' => 'item']);
        $expected = [
            '<ul',
            ['li' => ['class' => 'item']], 'Item 1', '/li',
            ['li' => ['class' => 'item']], 'Item 2',
            '<ul', ['li' => ['class' => 'item']], 'Item 2.1', '/li', '/ul',
            '/li',
            ['li' => ['class' => 'item']], 'Item 3', '/li',
            ['li' => ['class' => 'item']], 'Item 4',
            '<ul',
            ['li' => ['class' => 'item']], 'Item 4.1', '/li',
            ['li' => ['class' => 'item']], 'Item 4.2', '/li',
            ['li' => ['class' => 'item']], 'Item 4.3',
            '<ul',
            ['li' => ['class' => 'item']], 'Item 4.3.1', '/li',
            ['li' => ['class' => 'item']], 'Item 4.3.2', '/li',
            '/ul',
            '/li',
            '/ul',
            '/li',
            ['li' => ['class' => 'item']], 'Item 5',
            '<ul',
            ['li' => ['class' => 'item']], 'Item 5.1', '/li',
            ['li' => ['class' => 'item']], 'Item 5.2', '/li',
            '/ul',
            '/li',
            '/ul',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Html->nestedList($list, [], ['even' => 'even', 'odd' => 'odd']);
        $expected = [
            '<ul',
            ['li' => ['class' => 'odd']], 'Item 1', '/li',
            ['li' => ['class' => 'even']], 'Item 2',
            '<ul', ['li' => ['class' => 'odd']], 'Item 2.1', '/li', '/ul',
            '/li',
            ['li' => ['class' => 'odd']], 'Item 3', '/li',
            ['li' => ['class' => 'even']], 'Item 4',
            '<ul',
            ['li' => ['class' => 'odd']], 'Item 4.1', '/li',
            ['li' => ['class' => 'even']], 'Item 4.2', '/li',
            ['li' => ['class' => 'odd']], 'Item 4.3',
            '<ul',
            ['li' => ['class' => 'odd']], 'Item 4.3.1', '/li',
            ['li' => ['class' => 'even']], 'Item 4.3.2', '/li',
            '/ul',
            '/li',
            '/ul',
            '/li',
            ['li' => ['class' => 'odd']], 'Item 5',
            '<ul',
            ['li' => ['class' => 'odd']], 'Item 5.1', '/li',
            ['li' => ['class' => 'even']], 'Item 5.2', '/li',
            '/ul',
            '/li',
            '/ul',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Html->nestedList($list, ['class' => 'list'], ['class' => 'item']);
        $expected = [
            ['ul' => ['class' => 'list']],
            ['li' => ['class' => 'item']], 'Item 1', '/li',
            ['li' => ['class' => 'item']], 'Item 2',
            ['ul' => ['class' => 'list']], ['li' => ['class' => 'item']], 'Item 2.1', '/li', '/ul',
            '/li',
            ['li' => ['class' => 'item']], 'Item 3', '/li',
            ['li' => ['class' => 'item']], 'Item 4',
            ['ul' => ['class' => 'list']],
            ['li' => ['class' => 'item']], 'Item 4.1', '/li',
            ['li' => ['class' => 'item']], 'Item 4.2', '/li',
            ['li' => ['class' => 'item']], 'Item 4.3',
            ['ul' => ['class' => 'list']],
            ['li' => ['class' => 'item']], 'Item 4.3.1', '/li',
            ['li' => ['class' => 'item']], 'Item 4.3.2', '/li',
            '/ul',
            '/li',
            '/ul',
            '/li',
            ['li' => ['class' => 'item']], 'Item 5',
            ['ul' => ['class' => 'list']],
            ['li' => ['class' => 'item']], 'Item 5.1', '/li',
            ['li' => ['class' => 'item']], 'Item 5.2', '/li',
            '/ul',
            '/li',
            '/ul',
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * testMeta method.
     */
    public function testMeta()
    {
        $result = $this->Html->meta('this is an rss feed', ['controller' => 'posts', 'ext' => 'rss']);
        $this->assertTags($result, ['link' => ['href' => 'preg:/.*\/posts\.rss/', 'type' => 'application/rss+xml', 'rel' => 'alternate', 'title' => 'this is an rss feed']]);

        $result = $this->Html->meta('rss', ['controller' => 'posts', 'ext' => 'rss'], ['title' => 'this is an rss feed']);
        $this->assertTags($result, ['link' => ['href' => 'preg:/.*\/posts\.rss/', 'type' => 'application/rss+xml', 'rel' => 'alternate', 'title' => 'this is an rss feed']]);

        $result = $this->Html->meta('atom', ['controller' => 'posts', 'ext' => 'xml']);
        $this->assertTags($result, ['link' => ['href' => 'preg:/.*\/posts\.xml/', 'type' => 'application/atom+xml', 'title' => 'atom']]);

        $result = $this->Html->meta('non-existing');
        $this->assertTags($result, ['<meta']);

        $result = $this->Html->meta('non-existing', '/posts.xpp');
        $this->assertTags($result, ['link' => ['href' => 'preg:/.*\/posts\.xpp/', 'type' => 'application/rss+xml', 'rel' => 'alternate', 'title' => 'non-existing']]);

        $result = $this->Html->meta('non-existing', '/posts.xpp', ['type' => 'atom']);
        $this->assertTags($result, ['link' => ['href' => 'preg:/.*\/posts\.xpp/', 'type' => 'application/atom+xml', 'title' => 'non-existing']]);

        $result = $this->Html->meta('atom', ['controller' => 'posts', 'ext' => 'xml'], ['link' => '/articles.rss']);
        $this->assertTags($result, ['link' => ['href' => 'preg:/.*\/articles\.rss/', 'type' => 'application/atom+xml', 'title' => 'atom']]);

        $result = $this->Html->meta(['link' => 'favicon.ico', 'rel' => 'icon']);
        $expected = [
            'link' => ['href' => 'preg:/.*favicon\.ico/', 'rel' => 'icon'],
            ['link' => ['href' => 'preg:/.*favicon\.ico/', 'rel' => 'shortcut icon']],
        ];
        $this->assertTags($result, $expected);

        $result = $this->Html->meta('icon', 'favicon.ico');
        $expected = [
            'link' => ['href' => 'preg:/.*favicon\.ico/', 'type' => 'image/x-icon', 'rel' => 'icon'],
            ['link' => ['href' => 'preg:/.*favicon\.ico/', 'type' => 'image/x-icon', 'rel' => 'shortcut icon']],
        ];
        $this->assertTags($result, $expected);

        $result = $this->Html->meta('keywords', 'these, are, some, meta, keywords');
        $this->assertTags($result, ['meta' => ['name' => 'keywords', 'content' => 'these, are, some, meta, keywords']]);
        $this->assertPattern('/\s+\/>$/', $result);

        $result = $this->Html->meta('description', 'this is the meta description');
        $this->assertTags($result, ['meta' => ['name' => 'description', 'content' => 'this is the meta description']]);

        $result = $this->Html->meta(['name' => 'ROBOTS', 'content' => 'ALL']);
        $this->assertTags($result, ['meta' => ['name' => 'ROBOTS', 'content' => 'ALL']]);

        $this->assertNull($this->Html->meta(['name' => 'ROBOTS', 'content' => 'ALL'], null, ['inline' => false]));
        $view = &ClassRegistry::getObject('view');
        $result = $view->__scripts[0];
        $this->assertTags($result, ['meta' => ['name' => 'ROBOTS', 'content' => 'ALL']]);
    }

    /**
     * testTableHeaders method.
     */
    public function testTableHeaders()
    {
        $result = $this->Html->tableHeaders(['ID', 'Name', 'Date']);
        $expected = ['<tr', '<th', 'ID', '/th', '<th', 'Name', '/th', '<th', 'Date', '/th', '/tr'];
        $this->assertTags($result, $expected);
    }

    /**
     * testTableCells method.
     */
    public function testTableCells()
    {
        $tr = [
            'td content 1',
            ['td content 2', ['width' => '100px']],
            ['td content 3', 'width=100px'],
        ];
        $result = $this->Html->tableCells($tr);
        $expected = [
            '<tr',
            '<td', 'td content 1', '/td',
            ['td' => ['width' => '100px']], 'td content 2', '/td',
            ['td' => ['width' => 'preg:/100px/']], 'td content 3', '/td',
            '/tr',
        ];
        $this->assertTags($result, $expected);

        $tr = ['td content 1', 'td content 2', 'td content 3'];
        $result = $this->Html->tableCells($tr, null, null, true);
        $expected = [
            '<tr',
            ['td' => ['class' => 'column-1']], 'td content 1', '/td',
            ['td' => ['class' => 'column-2']], 'td content 2', '/td',
            ['td' => ['class' => 'column-3']], 'td content 3', '/td',
            '/tr',
        ];
        $this->assertTags($result, $expected);

        $tr = ['td content 1', 'td content 2', 'td content 3'];
        $result = $this->Html->tableCells($tr, true);
        $expected = [
            '<tr',
            ['td' => ['class' => 'column-1']], 'td content 1', '/td',
            ['td' => ['class' => 'column-2']], 'td content 2', '/td',
            ['td' => ['class' => 'column-3']], 'td content 3', '/td',
            '/tr',
        ];
        $this->assertTags($result, $expected);

        $tr = [
            ['td content 1', 'td content 2', 'td content 3'],
            ['td content 1', 'td content 2', 'td content 3'],
            ['td content 1', 'td content 2', 'td content 3'],
        ];
        $result = $this->Html->tableCells($tr, ['class' => 'odd'], ['class' => 'even']);
        $expected = "<tr class=\"even\"><td>td content 1</td> <td>td content 2</td> <td>td content 3</td></tr>\n<tr class=\"odd\"><td>td content 1</td> <td>td content 2</td> <td>td content 3</td></tr>\n<tr class=\"even\"><td>td content 1</td> <td>td content 2</td> <td>td content 3</td></tr>";
        $this->assertEqual($result, $expected);

        $tr = [
            ['td content 1', 'td content 2', 'td content 3'],
            ['td content 1', 'td content 2', 'td content 3'],
            ['td content 1', 'td content 2', 'td content 3'],
            ['td content 1', 'td content 2', 'td content 3'],
        ];
        $result = $this->Html->tableCells($tr, ['class' => 'odd'], ['class' => 'even']);
        $expected = "<tr class=\"odd\"><td>td content 1</td> <td>td content 2</td> <td>td content 3</td></tr>\n<tr class=\"even\"><td>td content 1</td> <td>td content 2</td> <td>td content 3</td></tr>\n<tr class=\"odd\"><td>td content 1</td> <td>td content 2</td> <td>td content 3</td></tr>\n<tr class=\"even\"><td>td content 1</td> <td>td content 2</td> <td>td content 3</td></tr>";
        $this->assertEqual($result, $expected);

        $tr = [
            ['td content 1', 'td content 2', 'td content 3'],
            ['td content 1', 'td content 2', 'td content 3'],
            ['td content 1', 'td content 2', 'td content 3'],
        ];
        $this->Html->tableCells($tr, ['class' => 'odd'], ['class' => 'even']);
        $result = $this->Html->tableCells($tr, ['class' => 'odd'], ['class' => 'even'], false, false);
        $expected = "<tr class=\"odd\"><td>td content 1</td> <td>td content 2</td> <td>td content 3</td></tr>\n<tr class=\"even\"><td>td content 1</td> <td>td content 2</td> <td>td content 3</td></tr>\n<tr class=\"odd\"><td>td content 1</td> <td>td content 2</td> <td>td content 3</td></tr>";
        $this->assertEqual($result, $expected);
    }

    /**
     * testTag method.
     */
    public function testTag()
    {
        $result = $this->Html->tag('div');
        $this->assertTags($result, '<div');

        $result = $this->Html->tag('div', 'text');
        $this->assertTags($result, '<div', 'text', '/div');

        $result = $this->Html->tag('div', '<text>', 'class-name');
        $this->assertTags($result, ['div' => ['class' => 'class-name'], 'preg:/<text>/', '/div']);

        $result = $this->Html->tag('div', '<text>', ['class' => 'class-name', 'escape' => true]);
        $this->assertTags($result, ['div' => ['class' => 'class-name'], '&lt;text&gt;', '/div']);
    }

    /**
     * testDiv method.
     */
    public function testDiv()
    {
        $result = $this->Html->div('class-name');
        $this->assertTags($result, ['div' => ['class' => 'class-name']]);

        $result = $this->Html->div('class-name', 'text');
        $this->assertTags($result, ['div' => ['class' => 'class-name'], 'text', '/div']);

        $result = $this->Html->div('class-name', '<text>', ['escape' => true]);
        $this->assertTags($result, ['div' => ['class' => 'class-name'], '&lt;text&gt;', '/div']);
    }

    /**
     * testPara method.
     */
    public function testPara()
    {
        $result = $this->Html->para('class-name', '');
        $this->assertTags($result, ['p' => ['class' => 'class-name']]);

        $result = $this->Html->para('class-name', 'text');
        $this->assertTags($result, ['p' => ['class' => 'class-name'], 'text', '/p']);

        $result = $this->Html->para('class-name', '<text>', ['escape' => true]);
        $this->assertTags($result, ['p' => ['class' => 'class-name'], '&lt;text&gt;', '/p']);
    }
}
