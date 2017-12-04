<?php
/**
 * JsHelper Test Case.
 *
 * TestCase for the JsHelper
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
 * @since         CakePHP(tm) v 1.3
 *
 * @license       http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */
App::import('Helper', ['Js', 'Html', 'Form']);
App::import('Core', ['View', 'ClassRegistry']);

Mock::generate('JsBaseEngineHelper', 'TestJsEngineHelper', ['methodOne']);
Mock::generate('View', 'JsHelperMockView');

class OptionEngineHelper extends JsBaseEngineHelper
{
    public $_optionMap = [
        'request' => [
            'complete' => 'success',
            'request' => 'beforeSend',
            'type' => 'dataType',
        ],
    ];

    /**
     * test method for testing option mapping.
     *
     * @return array
     */
    public function testMap($options = [])
    {
        return $this->_mapOptions('request', $options);
    }

    /**
     * test method for option parsing.
     */
    public function testParseOptions($options, $safe = [])
    {
        return $this->_parseOptions($options, $safe);
    }
}

/**
 * JsHelper TestCase.
 */
class JsHelperTestCase extends CakeTestCase
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
     * startTest method.
     */
    public function startTest()
    {
        $this->_asset = Configure::read('Asset.timestamp');
        Configure::write('Asset.timestamp', false);

        $this->Js = new JsHelper('JsBase');
        $this->Js->Html = new HtmlHelper();
        $this->Js->Form = new FormHelper();
        $this->Js->Form->Html = new HtmlHelper();
        $this->Js->JsBaseEngine = new JsBaseEngineHelper();

        $view = new JsHelperMockView();
        ClassRegistry::addObject('view', $view);
    }

    /**
     * endTest method.
     */
    public function endTest()
    {
        Configure::write('Asset.timestamp', $this->_asset);
        ClassRegistry::removeObject('view');
        unset($this->Js);
    }

    /**
     * Switches $this->Js to a mocked engine.
     */
    public function _useMock()
    {
        $this->Js = new JsHelper(['TestJs']);
        $this->Js->TestJsEngine = new TestJsEngineHelper($this);
        $this->Js->Html = new HtmlHelper();
        $this->Js->Form = new FormHelper();
        $this->Js->Form->Html = new HtmlHelper();
    }

    /**
     * test object construction.
     */
    public function testConstruction()
    {
        $js = new JsHelper();
        $this->assertEqual($js->helpers, ['Html', 'Form', 'JqueryEngine']);

        $js = new JsHelper(['mootools']);
        $this->assertEqual($js->helpers, ['Html', 'Form', 'mootoolsEngine']);

        $js = new JsHelper('prototype');
        $this->assertEqual($js->helpers, ['Html', 'Form', 'prototypeEngine']);

        $js = new JsHelper('MyPlugin.Dojo');
        $this->assertEqual($js->helpers, ['Html', 'Form', 'MyPlugin.DojoEngine']);
    }

    /**
     * test that methods dispatch internally and to the engine class.
     */
    public function testMethodDispatching()
    {
        $this->_useMock();
        $this->Js->TestJsEngine->expectOnce('dispatchMethod', [new PatternExpectation('/methodOne/i'), []]);

        $this->Js->methodOne();

        $this->Js->TestEngine = new StdClass();
        $this->expectError();
        $this->Js->someMethodThatSurelyDoesntExist();
    }

    /**
     * Test that method dispatching respects buffer parameters and bufferedMethods Lists.
     */
    public function testMethodDispatchWithBuffering()
    {
        $this->_useMock();

        $this->Js->TestJsEngine->bufferedMethods = ['event', 'sortables'];
        $this->Js->TestJsEngine->setReturnValue('dispatchMethod', 'This is an event call', ['event', '*']);

        $this->Js->event('click', 'foo');
        $result = $this->Js->getBuffer();
        $this->assertEqual(count($result), 1);
        $this->assertEqual($result[0], 'This is an event call');

        $result = $this->Js->event('click', 'foo', ['buffer' => false]);
        $buffer = $this->Js->getBuffer();
        $this->assertTrue(empty($buffer));
        $this->assertEqual($result, 'This is an event call');

        $result = $this->Js->event('click', 'foo', false);
        $buffer = $this->Js->getBuffer();
        $this->assertTrue(empty($buffer));
        $this->assertEqual($result, 'This is an event call');

        $this->Js->TestJsEngine->setReturnValue('dispatchMethod', 'I am not buffered.', ['effect', '*']);

        $result = $this->Js->effect('slideIn');
        $buffer = $this->Js->getBuffer();
        $this->assertTrue(empty($buffer));
        $this->assertEqual($result, 'I am not buffered.');

        $result = $this->Js->effect('slideIn', true);
        $buffer = $this->Js->getBuffer();
        $this->assertNull($result);
        $this->assertEqual(count($buffer), 1);
        $this->assertEqual($buffer[0], 'I am not buffered.');

        $result = $this->Js->effect('slideIn', ['speed' => 'slow'], true);
        $buffer = $this->Js->getBuffer();
        $this->assertNull($result);
        $this->assertEqual(count($buffer), 1);
        $this->assertEqual($buffer[0], 'I am not buffered.');

        $result = $this->Js->effect('slideIn', ['speed' => 'slow', 'buffer' => true]);
        $buffer = $this->Js->getBuffer();
        $this->assertNull($result);
        $this->assertEqual(count($buffer), 1);
        $this->assertEqual($buffer[0], 'I am not buffered.');
    }

    /**
     * test that writeScripts generates scripts inline.
     */
    public function testWriteScriptsNoFile()
    {
        $this->_useMock();
        $this->Js->buffer('one = 1;');
        $this->Js->buffer('two = 2;');
        $result = $this->Js->writeBuffer(['onDomReady' => false, 'cache' => false, 'clear' => false]);
        $expected = [
            'script' => ['type' => 'text/javascript'],
            $this->cDataStart,
            "one = 1;\ntwo = 2;",
            $this->cDataEnd,
            '/script',
        ];
        $this->assertTags($result, $expected, true);

        $this->Js->TestJsEngine->expectAtLeastOnce('domReady');
        $result = $this->Js->writeBuffer(['onDomReady' => true, 'cache' => false, 'clear' => false]);

        ClassRegistry::removeObject('view');
        $view = new JsHelperMockView();
        ClassRegistry::addObject('view', $view);

        $view->expectCallCount('addScript', 1);
        $view->expectAt(0, 'addScript', [new PatternExpectation('/one\s\=\s1;\ntwo\s\=\s2;/')]);
        $result = $this->Js->writeBuffer(['onDomReady' => false, 'inline' => false, 'cache' => false]);
    }

    /**
     * test that writing the buffer with inline = false includes a script tag.
     */
    public function testWriteBufferNotInline()
    {
        $this->Js->set('foo', 1);

        $view = new JsHelperMockView();
        ClassRegistry::removeObject('view');
        ClassRegistry::addObject('view', $view);
        $view->expectCallCount('addScript', 1);

        $pattern = new PatternExpectation('#<script type="text\/javascript">window.app \= \{"foo"\:1\}\;<\/script>#');
        $view->expectAt(0, 'addScript', [$pattern]);

        $result = $this->Js->writeBuffer(['onDomReady' => false, 'inline' => false, 'safe' => false]);
    }

    /**
     * test that writeBuffer() sets domReady = false when the request is done by XHR.
     * Including a domReady() when in XHR can cause issues as events aren't triggered by some libraries.
     */
    public function testWriteBufferAndXhr()
    {
        $this->_useMock();
        $this->Js->params['isAjax'] = true;
        $this->Js->buffer('alert("test");');
        $this->Js->TestJsEngine->expectCallCount('dispatchMethod', 0);
        $result = $this->Js->writeBuffer();
    }

    /**
     * test that writeScripts makes files, and puts the events into them.
     */
    public function testWriteScriptsInFile()
    {
        if ($this->skipIf(!is_writable(JS), 'webroot/js is not Writable, script caching test has been skipped')) {
            return;
        }
        $this->Js->JsBaseEngine = new TestJsEngineHelper();
        $this->Js->buffer('one = 1;');
        $this->Js->buffer('two = 2;');
        $result = $this->Js->writeBuffer(['onDomReady' => false, 'cache' => true]);
        $expected = [
            'script' => ['type' => 'text/javascript', 'src' => 'preg:/(.)*\.js/'],
        ];
        $this->assertTags($result, $expected);
        preg_match('/src="(.*\.js)"/', $result, $filename);
        $this->assertTrue(file_exists(WWW_ROOT.$filename[1]));
        $contents = file_get_contents(WWW_ROOT.$filename[1]);
        $this->assertPattern('/one\s=\s1;\ntwo\s=\s2;/', $contents);

        @unlink(WWW_ROOT.$filename[1]);
    }

    /**
     * test link().
     */
    public function testLinkWithMock()
    {
        $this->_useMock();
        $options = ['update' => '#content'];

        $this->Js->TestJsEngine->setReturnValue('dispatchMethod', 'ajax code', ['request', '*']);
        $this->Js->TestJsEngine->expectAt(0, 'dispatchMethod', ['get', new AnythingExpectation()]);
        $this->Js->TestJsEngine->expectAt(1, 'dispatchMethod', [
            'request', ['/posts/view/1', $options],
        ]);
        $this->Js->TestJsEngine->expectAt(2, 'dispatchMethod', [
            'event', ['click', 'ajax code', $options + ['buffer' => null]],
        ]);

        $result = $this->Js->link('test link', '/posts/view/1', $options);
        $expected = [
            'a' => ['id' => 'preg:/link-\d+/', 'href' => '/posts/view/1'],
            'test link',
            '/a',
        ];
        $this->assertTags($result, $expected);

        $options = [
            'confirm' => 'Are you sure?',
            'update' => '#content',
            'class' => 'my-class',
            'id' => 'custom-id',
            'escape' => false,
        ];
        $this->Js->TestJsEngine->expectAt(0, 'confirm', [$options['confirm']]);
        $this->Js->TestJsEngine->expectAt(1, 'request', ['/posts/view/1', '*']);
        $code = <<<CODE
var _confirm = confirm("Are you sure?");
if (!_confirm) {
	return false;
}
CODE;
        $this->Js->TestJsEngine->expectAt(1, 'event', ['click', $code]);
        $result = $this->Js->link('test link »', '/posts/view/1', $options);
        $expected = [
            'a' => ['id' => $options['id'], 'class' => $options['class'], 'href' => '/posts/view/1'],
            'test link »',
            '/a',
        ];
        $this->assertTags($result, $expected);

        $options = ['id' => 'something', 'htmlAttributes' => ['arbitrary' => 'value', 'batman' => 'robin']];
        $result = $this->Js->link('test link', '/posts/view/1', $options);
        $expected = [
            'a' => ['id' => $options['id'], 'href' => '/posts/view/1', 'arbitrary' => 'value',
                'batman' => 'robin', ],
            'test link',
            '/a',
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * test that link() and no buffering returns an <a> and <script> tags.
     */
    public function testLinkWithNoBuffering()
    {
        $this->_useMock();
        $this->Js->TestJsEngine->setReturnValue('dispatchMethod', 'ajax code', [
            'request', ['/posts/view/1', ['update' => '#content']],
        ]);
        $this->Js->TestJsEngine->setReturnValue('dispatchMethod', '-event handler-', ['event', '*']);

        $options = ['update' => '#content', 'buffer' => false];
        $result = $this->Js->link('test link', '/posts/view/1', $options);
        $expected = [
            'a' => ['id' => 'preg:/link-\d+/', 'href' => '/posts/view/1'],
            'test link',
            '/a',
            'script' => ['type' => 'text/javascript'],
            $this->cDataStart,
            '-event handler-',
            $this->cDataEnd,
            '/script',
        ];
        $this->assertTags($result, $expected);

        $options = ['update' => '#content', 'buffer' => false, 'safe' => false];
        $result = $this->Js->link('test link', '/posts/view/1', $options);
        $expected = [
            'a' => ['id' => 'preg:/link-\d+/', 'href' => '/posts/view/1'],
            'test link',
            '/a',
            'script' => ['type' => 'text/javascript'],
            '-event handler-',
            '/script',
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * test submit() with a Mock to check Engine method calls.
     */
    public function testSubmitWithMock()
    {
        $this->_useMock();

        $options = ['update' => '#content', 'id' => 'test-submit', 'style' => 'margin: 0'];
        $this->Js->TestJsEngine->setReturnValue('dispatchMethod', 'serialize-code', ['serializeform', '*']);
        $this->Js->TestJsEngine->setReturnValue('dispatchMethod', 'serialize-code', ['serializeForm', '*']);
        $this->Js->TestJsEngine->setReturnValue('dispatchMethod', 'ajax-code', ['request', '*']);

        $this->Js->TestJsEngine->expectAt(0, 'dispatchMethod', ['get', '*']);
        $this->Js->TestJsEngine->expectAt(1, 'dispatchMethod', [new PatternExpectation('/serializeForm/i'), '*']);
        $this->Js->TestJsEngine->expectAt(2, 'dispatchMethod', ['request', '*']);

        $params = [
            'update' => $options['update'], 'data' => 'serialize-code',
            'method' => 'post', 'dataExpression' => true, 'buffer' => null,
        ];
        $this->Js->TestJsEngine->expectAt(3, 'dispatchMethod', [
            'event', ['click', 'ajax-code', $params],
        ]);

        $result = $this->Js->submit('Save', $options);
        $expected = [
            'div' => ['class' => 'submit'],
            'input' => ['type' => 'submit', 'id' => $options['id'], 'value' => 'Save', 'style' => 'margin: 0'],
            '/div',
        ];
        $this->assertTags($result, $expected);

        $this->Js->TestJsEngine->expectAt(4, 'dispatchMethod', ['get', '*']);
        $this->Js->TestJsEngine->expectAt(5, 'dispatchMethod', [new PatternExpectation('/serializeForm/i'), '*']);
        $requestParams = [
            '/custom/url', [
                'update' => '#content',
                'data' => 'serialize-code',
                'method' => 'post',
                'dataExpression' => true,
            ],
        ];
        $this->Js->TestJsEngine->expectAt(6, 'dispatchMethod', ['request', $requestParams]);

        $params = [
            'update' => '#content', 'data' => 'serialize-code',
            'method' => 'post', 'dataExpression' => true, 'buffer' => null,
        ];
        $this->Js->TestJsEngine->expectAt(7, 'dispatchMethod', [
            'event', ['click', 'ajax-code', $params],
        ]);

        $options = ['update' => '#content', 'id' => 'test-submit', 'url' => '/custom/url'];
        $result = $this->Js->submit('Save', $options);
        $expected = [
            'div' => ['class' => 'submit'],
            'input' => ['type' => 'submit', 'id' => $options['id'], 'value' => 'Save'],
            '/div',
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * test that no buffer works with submit() and that parameters are leaking into the script tag.
     */
    public function testSubmitWithNoBuffer()
    {
        $this->_useMock();
        $options = ['update' => '#content', 'id' => 'test-submit', 'buffer' => false, 'safe' => false];
        $this->Js->TestJsEngine->setReturnValue('dispatchMethod', 'serialize-code', ['serializeform', '*']);
        $this->Js->TestJsEngine->setReturnValue('dispatchMethod', 'serialize-code', ['serializeForm', '*']);
        $this->Js->TestJsEngine->setReturnValue('dispatchMethod', 'ajax-code', ['request', '*']);
        $this->Js->TestJsEngine->setReturnValue('dispatchMethod', 'event-handler', ['event', '*']);

        $this->Js->TestJsEngine->expectAt(0, 'dispatchMethod', ['get', '*']);
        $this->Js->TestJsEngine->expectAt(1, 'dispatchMethod', [new PatternExpectation('/serializeForm/i'), '*']);
        $this->Js->TestJsEngine->expectAt(2, 'dispatchMethod', ['request', [
            '', ['update' => $options['update'], 'data' => 'serialize-code', 'method' => 'post', 'dataExpression' => true],
        ]]);

        $params = [
            'update' => $options['update'], 'data' => 'serialize-code',
            'method' => 'post', 'dataExpression' => true, 'buffer' => false,
        ];
        $this->Js->TestJsEngine->expectAt(3, 'dispatchMethod', [
            'event', ['click', 'ajax-code', $params],
        ]);

        $result = $this->Js->submit('Save', $options);
        $expected = [
            'div' => ['class' => 'submit'],
            'input' => ['type' => 'submit', 'id' => $options['id'], 'value' => 'Save'],
            '/div',
            'script' => ['type' => 'text/javascript'],
            'event-handler',
            '/script',
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * Test that Object::Object() is not breaking json output in JsHelper.
     */
    public function testObjectPassThrough()
    {
        $result = $this->Js->object(['one' => 'first', 'two' => 'second']);
        $expected = '{"one":"first","two":"second"}';
        $this->assertEqual($result, $expected);
    }

    /**
     * Test that inherited Helper::value() is overwritten in JsHelper::value()
     * and calls JsBaseEngineHelper::value().
     */
    public function testValuePassThrough()
    {
        $result = $this->Js->value('string "quote"', true);
        $expected = '"string \"quote\""';
        $this->assertEqual($result, $expected);
    }

    /**
     * test set()'ing variables to the Javascript buffer and controlling the output var name.
     */
    public function testSet()
    {
        $this->Js->set('loggedIn', true);
        $this->Js->set(['height' => 'tall', 'color' => 'purple']);
        $result = $this->Js->getBuffer();
        $expected = 'window.app = {"loggedIn":true,"height":"tall","color":"purple"};';
        $this->assertEqual($result[0], $expected);

        $this->Js->set('loggedIn', true);
        $this->Js->set(['height' => 'tall', 'color' => 'purple']);
        $this->Js->setVariable = 'WICKED';
        $result = $this->Js->getBuffer();
        $expected = 'window.WICKED = {"loggedIn":true,"height":"tall","color":"purple"};';
        $this->assertEqual($result[0], $expected);

        $this->Js->set('loggedIn', true);
        $this->Js->set(['height' => 'tall', 'color' => 'purple']);
        $this->Js->setVariable = 'Application.variables';
        $result = $this->Js->getBuffer();
        $expected = 'Application.variables = {"loggedIn":true,"height":"tall","color":"purple"};';
        $this->assertEqual($result[0], $expected);
    }

    /**
     * test that vars set with Js->set() go to the top of the buffered scripts list.
     */
    public function testSetVarsAtTopOfBufferedScripts()
    {
        $this->Js->set(['height' => 'tall', 'color' => 'purple']);
        $this->Js->alert('hey you!', ['buffer' => true]);
        $this->Js->confirm('Are you sure?', ['buffer' => true]);
        $result = $this->Js->getBuffer(false);

        $expected = 'window.app = {"height":"tall","color":"purple"};';
        $this->assertEqual($result[0], $expected);
        $this->assertEqual($result[1], 'alert("hey you!");');
        $this->assertEqual($result[2], 'confirm("Are you sure?");');
    }
}

/**
 * JsBaseEngine Class Test case.
 */
class JsBaseEngineTestCase extends CakeTestCase
{
    /**
     * startTest method.
     */
    public function startTest()
    {
        $this->JsEngine = new JsBaseEngineHelper();
    }

    /**
     * endTest method.
     */
    public function endTest()
    {
        ClassRegistry::removeObject('view');
        unset($this->JsEngine);
    }

    /**
     * test escape string skills.
     */
    public function testEscaping()
    {
        $result = $this->JsEngine->escape('');
        $expected = '';
        $this->assertEqual($result, $expected);

        $result = $this->JsEngine->escape('CakePHP'."\n".'Rapid Development Framework');
        $expected = 'CakePHP\\nRapid Development Framework';
        $this->assertEqual($result, $expected);

        $result = $this->JsEngine->escape('CakePHP'."\r\n".'Rapid Development Framework'."\r".'For PHP');
        $expected = 'CakePHP\\r\\nRapid Development Framework\\rFor PHP';
        $this->assertEqual($result, $expected);

        $result = $this->JsEngine->escape('CakePHP: "Rapid Development Framework"');
        $expected = 'CakePHP: \\"Rapid Development Framework\\"';
        $this->assertEqual($result, $expected);

        $result = $this->JsEngine->escape("CakePHP: 'Rapid Development Framework'");
        $expected = "CakePHP: 'Rapid Development Framework'";
        $this->assertEqual($result, $expected);

        $result = $this->JsEngine->escape('my \\"string\\"');
        $expected = 'my \\\\\\"string\\\\\\"';
        $this->assertEqual($result, $expected);
    }

    /**
     * test prompt() creation.
     */
    public function testPrompt()
    {
        $result = $this->JsEngine->prompt('Hey, hey you', 'hi!');
        $expected = 'prompt("Hey, hey you", "hi!");';
        $this->assertEqual($result, $expected);

        $result = $this->JsEngine->prompt('"Hey"', '"hi"');
        $expected = 'prompt("\"Hey\"", "\"hi\"");';
        $this->assertEqual($result, $expected);
    }

    /**
     * test alert generation.
     */
    public function testAlert()
    {
        $result = $this->JsEngine->alert('Hey there');
        $expected = 'alert("Hey there");';
        $this->assertEqual($result, $expected);

        $result = $this->JsEngine->alert('"Hey"');
        $expected = 'alert("\"Hey\"");';
        $this->assertEqual($result, $expected);
    }

    /**
     * test confirm generation.
     */
    public function testConfirm()
    {
        $result = $this->JsEngine->confirm('Are you sure?');
        $expected = 'confirm("Are you sure?");';
        $this->assertEqual($result, $expected);

        $result = $this->JsEngine->confirm('"Are you sure?"');
        $expected = 'confirm("\"Are you sure?\"");';
        $this->assertEqual($result, $expected);
    }

    /**
     * test Redirect.
     */
    public function testRedirect()
    {
        $result = $this->JsEngine->redirect(['controller' => 'posts', 'action' => 'view', 1]);
        $expected = 'window.location = "/posts/view/1";';
        $this->assertEqual($result, $expected);
    }

    /**
     * testObject encoding with non-native methods.
     */
    public function testObject()
    {
        $this->JsEngine->useNative = false;

        $object = ['title' => 'New thing', 'indexes' => [5, 6, 7, 8]];
        $result = $this->JsEngine->object($object);
        $expected = '{"title":"New thing","indexes":[5,6,7,8]}';
        $this->assertEqual($result, $expected);

        $result = $this->JsEngine->object(['default' => 0]);
        $expected = '{"default":0}';
        $this->assertEqual($result, $expected);

        $result = $this->JsEngine->object([
            '2007' => [
                'Spring' => [
                    '1' => ['id' => 1, 'name' => 'Josh'], '2' => ['id' => 2, 'name' => 'Becky'],
                ],
                'Fall' => [
                    '1' => ['id' => 1, 'name' => 'Josh'], '2' => ['id' => 2, 'name' => 'Becky'],
                ],
            ],
            '2006' => [
                'Spring' => [
                    '1' => ['id' => 1, 'name' => 'Josh'], '2' => ['id' => 2, 'name' => 'Becky'],
                ],
                'Fall' => [
                    '1' => ['id' => 1, 'name' => 'Josh'], '2' => ['id' => 2, 'name' => 'Becky'],
                ],
            ],
        ]);
        $expected = '{"2007":{"Spring":{"1":{"id":1,"name":"Josh"},"2":{"id":2,"name":"Becky"}},"Fall":{"1":{"id":1,"name":"Josh"},"2":{"id":2,"name":"Becky"}}},"2006":{"Spring":{"1":{"id":1,"name":"Josh"},"2":{"id":2,"name":"Becky"}},"Fall":{"1":{"id":1,"name":"Josh"},"2":{"id":2,"name":"Becky"}}}}';
        $this->assertEqual($result, $expected);

        foreach (['true' => true, 'false' => false, 'null' => null] as $expected => $data) {
            $result = $this->JsEngine->object($data);
            $this->assertEqual($result, $expected);
        }

        $object = ['title' => 'New thing', 'indexes' => [5, 6, 7, 8], 'object' => ['inner' => ['value' => 1]]];
        $result = $this->JsEngine->object($object, ['prefix' => 'PREFIX', 'postfix' => 'POSTFIX']);
        $this->assertPattern('/^PREFIX/', $result);
        $this->assertPattern('/POSTFIX$/', $result);
        $this->assertNoPattern('/.PREFIX./', $result);
        $this->assertNoPattern('/.POSTFIX./', $result);
    }

    /**
     * test compatibility of JsBaseEngineHelper::object() vs. json_encode().
     */
    public function testObjectAgainstJsonEncode()
    {
        $skip = $this->skipIf(!function_exists('json_encode'), 'json_encode() not found, comparison tests skipped. %s');
        if ($skip) {
            return;
        }
        $this->JsEngine->useNative = false;
        $data = [];
        $data['mystring'] = 'simple string';
        $this->assertEqual(json_encode($data), $this->JsEngine->object($data));

        $data['mystring'] = 'strÃ¯ng with spÃ©cial chÃ¢rs';
        $this->assertEqual(json_encode($data), $this->JsEngine->object($data));

        $data['mystring'] = "a two lines\nstring";
        $this->assertEqual(json_encode($data), $this->JsEngine->object($data));

        $data['mystring'] = "a \t tabbed \t string";
        $this->assertEqual(json_encode($data), $this->JsEngine->object($data));

        $data['mystring'] = 'a "double-quoted" string';
        $this->assertEqual(json_encode($data), $this->JsEngine->object($data));

        $data['mystring'] = 'a \\"double-quoted\\" string';
        $this->assertEqual(json_encode($data), $this->JsEngine->object($data));

        unset($data['mystring']);
        $data[3] = [1, 2, 3];
        $this->assertEqual(json_encode($data), $this->JsEngine->object($data));

        unset($data[3]);
        $data = ['mystring' => null, 'bool' => false, 'array' => [1, 44, 66]];
        $this->assertEqual(json_encode($data), $this->JsEngine->object($data));
    }

    /**
     * test that JSON made with JsBaseEngineHelper::object() against json_decode().
     */
    public function testObjectAgainstJsonDecode()
    {
        $skip = $this->skipIf(!function_exists('json_encode'), 'json_encode() not found, comparison tests skipped. %s');
        if ($skip) {
            return;
        }
        $this->JsEngine->useNative = false;

        $data = ['simple string'];
        $result = $this->JsEngine->object($data);
        $this->assertEqual(json_decode($result), $data);

        $data = ['my "string"'];
        $result = $this->JsEngine->object($data);
        $this->assertEqual(json_decode($result), $data);

        $data = ['my \\"string\\"'];
        $result = $this->JsEngine->object($data);
        $this->assertEqual(json_decode($result), $data);
    }

    /**
     * test Mapping of options.
     */
    public function testOptionMapping()
    {
        $JsEngine = new OptionEngineHelper();
        $result = $JsEngine->testMap();
        $this->assertEqual($result, []);

        $result = $JsEngine->testMap(['foo' => 'bar', 'baz' => 'sho']);
        $this->assertEqual($result, ['foo' => 'bar', 'baz' => 'sho']);

        $result = $JsEngine->testMap(['complete' => 'myFunc', 'type' => 'json', 'update' => '#element']);
        $this->assertEqual($result, ['success' => 'myFunc', 'dataType' => 'json', 'update' => '#element']);

        $result = $JsEngine->testMap(['success' => 'myFunc', 'dataType' => 'json', 'update' => '#element']);
        $this->assertEqual($result, ['success' => 'myFunc', 'dataType' => 'json', 'update' => '#element']);
    }

    /**
     * test that option parsing escapes strings and saves what is supposed to be saved.
     */
    public function testOptionParsing()
    {
        $JsEngine = new OptionEngineHelper();

        $result = $JsEngine->testParseOptions(['url' => '/posts/view/1', 'key' => 1]);
        $expected = 'key:1, url:"\\/posts\\/view\\/1"';
        $this->assertEqual($result, $expected);

        $result = $JsEngine->testParseOptions(['url' => '/posts/view/1', 'success' => 'doSuccess'], ['success']);
        $expected = 'success:doSuccess, url:"\\/posts\\/view\\/1"';
        $this->assertEqual($result, $expected);
    }
}
