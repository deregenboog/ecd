<?php
/**
 * HttpSocketTest file.
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
App::import('Core', 'HttpSocket');

class TestHttpSocket extends HttpSocket
{
    /**
     * Convenience method for testing protected method.
     *
     * @param mixed $uri URI (see {@link _parseUri()})
     *
     * @return array Current configuration settings
     */
    public function configUri($uri = null)
    {
        return parent::_configUri($uri);
    }

    /**
     * Convenience method for testing protected method.
     *
     * @param string $uri  URI to parse
     * @param mixed  $base if true use default URI config, otherwise indexed array to set 'scheme', 'host', 'port', etc
     *
     * @return array Parsed URI
     */
    public function parseUri($uri = null, $base = [])
    {
        return parent::_parseUri($uri, $base);
    }

    /**
     * Convenience method for testing protected method.
     *
     * @param array  $uri         A $uri array, or uses $this->config if left empty
     * @param string $uriTemplate The Uri template/format to use
     *
     * @return string A fully qualified URL formated according to $uriTemplate
     */
    public function buildUri($uri = [], $uriTemplate = '%scheme://%user:%pass@%host:%port/%path?%query#%fragment')
    {
        return parent::_buildUri($uri, $uriTemplate);
    }

    /**
     * Convenience method for testing protected method.
     *
     * @param array $header Header to build
     *
     * @return string Header built from array
     */
    public function buildHeader($header, $mode = 'standard')
    {
        return parent::_buildHeader($header, $mode);
    }

    /**
     * Convenience method for testing protected method.
     *
     * @param string $message Message to parse
     *
     * @return array Parsed message (with indexed elements such as raw, status, header, body)
     */
    public function parseResponse($message)
    {
        return parent::_parseResponse($message);
    }

    /**
     * Convenience method for testing protected method.
     *
     * @param array $header Header as an indexed array (field => value)
     *
     * @return array Parsed header
     */
    public function parseHeader($header)
    {
        return parent::_parseHeader($header);
    }

    /**
     * Convenience method for testing protected method.
     *
     * @param mixed $query A query string to parse into an array or an array to return directly "as is"
     *
     * @return array The $query parsed into a possibly multi-level array. If an empty $query is given, an empty array is returned.
     */
    public function parseQuery($query)
    {
        return parent::_parseQuery($query);
    }

    /**
     * Convenience method for testing protected method.
     *
     * @param string $body     A string continaing the body to decode
     * @param mixed  $encoding Can be false in case no encoding is being used, or a string representing the encoding
     *
     * @return mixed Array or false
     */
    public function decodeBody($body, $encoding = 'chunked')
    {
        return parent::_decodeBody($body, $encoding);
    }

    /**
     * Convenience method for testing protected method.
     *
     * @param string $body A string continaing the chunked body to decode
     *
     * @return mixed Array or false
     */
    public function decodeChunkedBody($body)
    {
        return parent::_decodeChunkedBody($body);
    }

    /**
     * Convenience method for testing protected method.
     *
     * @param array  $request      Needs to contain a 'uri' key. Should also contain a 'method' key, otherwise defaults to GET.
     * @param string $versionToken The version token to use, defaults to HTTP/1.1
     *
     * @return string Request line
     */
    public function buildRequestLine($request = [], $versionToken = 'HTTP/1.1')
    {
        return parent::_buildRequestLine($request, $versionToken);
    }

    /**
     * Convenience method for testing protected method.
     *
     * @param bool $hex true to get them as HEX values, false otherwise
     *
     * @return array Escape chars
     */
    public function tokenEscapeChars($hex = true, $chars = null)
    {
        return parent::_tokenEscapeChars($hex, $chars);
    }

    /**
     * Convenience method for testing protected method.
     *
     * @param string $token Token to escape
     *
     * @return string Escaped token
     */
    public function EscapeToken($token, $chars = null)
    {
        return parent::_escapeToken($token, $chars);
    }

    /**
     * Convenience method for testing protected method.
     *
     * @param string $token Token to unescape
     *
     * @return string Unescaped token
     */
    public function unescapeToken($token, $chars = null)
    {
        return parent::_unescapeToken($token, $chars);
    }
}

/**
 * HttpSocketTest class.
 */
class HttpSocketTest extends CakeTestCase
{
    /**
     * Socket property.
     *
     * @var mixed null
     */
    public $Socket = null;

    /**
     * RequestSocket property.
     *
     * @var mixed null
     */
    public $RequestSocket = null;

    /**
     * This function sets up a TestHttpSocket instance we are going to use for testing.
     */
    public function setUp()
    {
        if (!class_exists('MockHttpSocket')) {
            Mock::generatePartial('TestHttpSocket', 'MockHttpSocket', ['read', 'write', 'connect']);
            Mock::generatePartial('TestHttpSocket', 'MockHttpSocketRequests', ['read', 'write', 'connect', 'request']);
        }

        $this->Socket = new MockHttpSocket();
        $this->RequestSocket = new MockHttpSocketRequests();
    }

    /**
     * We use this function to clean up after the test case was executed.
     */
    public function tearDown()
    {
        unset($this->Socket, $this->RequestSocket);
    }

    /**
     * Test that HttpSocket::__construct does what one would expect it to do.
     */
    public function testConstruct()
    {
        $this->Socket->reset();
        $baseConfig = $this->Socket->config;
        $this->Socket->expectNever('connect');
        $this->Socket->__construct(['host' => 'foo-bar']);
        $baseConfig['host'] = 'foo-bar';
        $baseConfig['protocol'] = getprotobyname($baseConfig['protocol']);
        $this->assertIdentical($this->Socket->config, $baseConfig);
        $this->Socket->reset();
        $baseConfig = $this->Socket->config;
        $this->Socket->__construct('http://www.cakephp.org:23/');
        $baseConfig['host'] = 'www.cakephp.org';
        $baseConfig['request']['uri']['host'] = 'www.cakephp.org';
        $baseConfig['port'] = 23;
        $baseConfig['request']['uri']['port'] = 23;
        $baseConfig['protocol'] = getprotobyname($baseConfig['protocol']);
        $this->assertIdentical($this->Socket->config, $baseConfig);

        $this->Socket->reset();
        $this->Socket->__construct(['request' => ['uri' => 'http://www.cakephp.org:23/']]);
        $this->assertIdentical($this->Socket->config, $baseConfig);
    }

    /**
     * Test that HttpSocket::configUri works properly with different types of arguments.
     */
    public function testConfigUri()
    {
        $this->Socket->reset();
        $r = $this->Socket->configUri('https://bob:secret@www.cakephp.org:23/?query=foo');
        $expected = [
            'persistent' => false,
            'host' => 'www.cakephp.org',
            'protocol' => 'tcp',
            'port' => 23,
            'timeout' => 30,
            'request' => [
                'uri' => [
                    'scheme' => 'https', 'host' => 'www.cakephp.org', 'port' => 23,
                ],
                'auth' => [
                    'method' => 'Basic', 'user' => 'bob', 'pass' => 'secret',
                ],
                'cookies' => [],
            ],
        ];
        $this->assertIdentical($this->Socket->config, $expected);
        $this->assertIdentical($r, $expected);
        $r = $this->Socket->configUri(['host' => 'www.foo-bar.org']);
        $expected['host'] = 'www.foo-bar.org';
        $expected['request']['uri']['host'] = 'www.foo-bar.org';
        $this->assertIdentical($this->Socket->config, $expected);
        $this->assertIdentical($r, $expected);

        $r = $this->Socket->configUri('http://www.foo.com');
        $expected = [
            'persistent' => false,
            'host' => 'www.foo.com',
            'protocol' => 'tcp',
            'port' => 80,
            'timeout' => 30,
            'request' => [
                'uri' => [
                    'scheme' => 'http', 'host' => 'www.foo.com', 'port' => 80,
                ],
                'auth' => [
                    'method' => 'Basic', 'user' => null, 'pass' => null,
                ],
                'cookies' => [],
            ],
        ];
        $this->assertIdentical($this->Socket->config, $expected);
        $this->assertIdentical($r, $expected);
        $r = $this->Socket->configUri('/this-is-broken');
        $this->assertIdentical($this->Socket->config, $expected);
        $this->assertIdentical($r, false);
        $r = $this->Socket->configUri(false);
        $this->assertIdentical($this->Socket->config, $expected);
        $this->assertIdentical($r, false);
    }

    /**
     * Tests that HttpSocket::request (the heart of the HttpSocket) is working properly.
     */
    public function testRequest()
    {
        $this->Socket->reset();

        $this->Socket->reset();
        $response = $this->Socket->request(true);
        $this->assertFalse($response);

        $tests = [
            0 => [
                'request' => 'http://www.cakephp.org/?foo=bar', 'expectation' => [
                    'config' => [
                        'persistent' => false, 'host' => 'www.cakephp.org', 'protocol' => 'tcp', 'port' => 80, 'timeout' => 30, 'request' => [
                            'uri' => [
                                'scheme' => 'http', 'host' => 'www.cakephp.org', 'port' => 80,
                            ], 'auth' => [
                                'method' => 'Basic', 'user' => null, 'pass' => null,
                            ],
                            'cookies' => [],
                        ],
                    ], 'request' => [
                        'method' => 'GET', 'uri' => [
                            'scheme' => 'http', 'host' => 'www.cakephp.org', 'port' => 80, 'user' => null, 'pass' => null, 'path' => '/', 'query' => ['foo' => 'bar'], 'fragment' => null,
                        ], 'auth' => [
                            'method' => 'Basic', 'user' => null, 'pass' => null,
                        ], 'version' => '1.1', 'body' => '', 'line' => "GET /?foo=bar HTTP/1.1\r\n", 'header' => "Host: www.cakephp.org\r\nConnection: close\r\nUser-Agent: CakePHP\r\n", 'raw' => '', 'cookies' => [],
                    ],
                ],
            ], 1 => [
                'request' => [
                    'uri' => [
                        'host' => 'www.cakephp.org', 'query' => '?foo=bar',
                    ],
                ],
            ], 2 => [
                'request' => 'www.cakephp.org/?foo=bar',
            ], 3 => [
                'request' => ['host' => '192.168.0.1', 'uri' => 'http://www.cakephp.org/?foo=bar'], 'expectation' => [
                    'request' => [
                        'uri' => ['host' => 'www.cakephp.org'],
                    ], 'config' => [
                        'request' => [
                            'uri' => ['host' => 'www.cakephp.org'],
                        ], 'host' => '192.168.0.1',
                    ],
                ],
            ], 'reset4' => [
                'request.uri.query' => [],
            ], 4 => [
                'request' => ['header' => ['Foo@woo' => 'bar-value']], 'expectation' => [
                    'request' => [
                        'header' => "Host: www.cakephp.org\r\nConnection: close\r\nUser-Agent: CakePHP\r\nFoo\"@\"woo: bar-value\r\n", 'line' => "GET / HTTP/1.1\r\n",
                    ],
                ],
            ], 5 => [
                'request' => ['header' => ['Foo@woo' => 'bar-value', 'host' => 'foo.com'], 'uri' => 'http://www.cakephp.org/'], 'expectation' => [
                    'request' => [
                        'header' => "Host: foo.com\r\nConnection: close\r\nUser-Agent: CakePHP\r\nFoo\"@\"woo: bar-value\r\n",
                    ], 'config' => [
                        'host' => 'www.cakephp.org',
                    ],
                ],
            ], 6 => [
                'request' => ['header' => "Foo: bar\r\n"], 'expectation' => [
                    'request' => [
                        'header' => "Foo: bar\r\n",
                    ],
                ],
            ], 7 => [
                'request' => ['header' => "Foo: bar\r\n", 'uri' => 'http://www.cakephp.org/search?q=http_socket#ignore-me'], 'expectation' => [
                    'request' => [
                        'uri' => [
                            'path' => '/search', 'query' => ['q' => 'http_socket'], 'fragment' => 'ignore-me',
                        ], 'line' => "GET /search?q=http_socket HTTP/1.1\r\n",
                    ],
                ],
            ], 'reset8' => [
                'request.uri.query' => [],
            ], 8 => [
                'request' => ['method' => 'POST', 'uri' => 'http://www.cakephp.org/posts/add', 'body' => ['name' => 'HttpSocket-is-released', 'date' => 'today']], 'expectation' => [
                    'request' => [
                        'method' => 'POST', 'uri' => [
                            'path' => '/posts/add', 'fragment' => null,
                        ], 'body' => 'name=HttpSocket-is-released&date=today', 'line' => "POST /posts/add HTTP/1.1\r\n", 'header' => "Host: www.cakephp.org\r\nConnection: close\r\nUser-Agent: CakePHP\r\nContent-Type: application/x-www-form-urlencoded\r\nContent-Length: 38\r\n", 'raw' => 'name=HttpSocket-is-released&date=today',
                    ],
                ],
            ], 9 => [
                'request' => ['method' => 'POST', 'uri' => 'http://www.cakephp.org:8080/posts/add', 'body' => ['name' => 'HttpSocket-is-released', 'date' => 'today']], 'expectation' => [
                    'config' => [
                        'port' => 8080, 'request' => [
                            'uri' => [
                                'port' => 8080,
                            ],
                        ],
                    ], 'request' => [
                        'uri' => [
                            'port' => 8080,
                        ], 'header' => "Host: www.cakephp.org:8080\r\nConnection: close\r\nUser-Agent: CakePHP\r\nContent-Type: application/x-www-form-urlencoded\r\nContent-Length: 38\r\n",
                    ],
                ],
            ], 10 => [
                'request' => ['method' => 'POST', 'uri' => 'https://www.cakephp.org/posts/add', 'body' => ['name' => 'HttpSocket-is-released', 'date' => 'today']], 'expectation' => [
                    'config' => [
                        'port' => 443, 'request' => [
                            'uri' => [
                                'scheme' => 'https', 'port' => 443,
                            ],
                        ],
                    ], 'request' => [
                        'uri' => [
                            'scheme' => 'https', 'port' => 443,
                        ], 'header' => "Host: www.cakephp.org\r\nConnection: close\r\nUser-Agent: CakePHP\r\nContent-Type: application/x-www-form-urlencoded\r\nContent-Length: 38\r\n",
                    ],
                ],
            ], 11 => [
                'request' => [
                        'method' => 'POST',
                        'uri' => 'https://www.cakephp.org/posts/add',
                        'body' => ['name' => 'HttpSocket-is-released', 'date' => 'today'],
                        'cookies' => ['foo' => ['value' => 'bar']],
                ], 'expectation' => [
                    'request' => [
                        'header' => "Host: www.cakephp.org\r\nConnection: close\r\nUser-Agent: CakePHP\r\nContent-Type: application/x-www-form-urlencoded\r\nContent-Length: 38\r\nCookie: foo=bar\r\n",
                        'cookies' => [
                            'foo' => ['value' => 'bar'],
                        ],
                    ],
                ],
            ],
        ];

        $expectation = [];
        foreach ($tests as $i => $test) {
            if (0 === strpos($i, 'reset')) {
                foreach ($test as $path => $val) {
                    $expectation = Set::insert($expectation, $path, $val);
                }
                continue;
            }

            if (isset($test['expectation'])) {
                $expectation = Set::merge($expectation, $test['expectation']);
            }
            $this->Socket->request($test['request']);

            $raw = $expectation['request']['raw'];
            $expectation['request']['raw'] = $expectation['request']['line'].$expectation['request']['header']."\r\n".$raw;

            $r = ['config' => $this->Socket->config, 'request' => $this->Socket->request];
            $v = $this->assertIdentical($r, $expectation, '%s in test #'.$i.' ');
            $expectation['request']['raw'] = $raw;
        }

        $this->Socket->reset();
        $request = ['method' => 'POST', 'uri' => 'http://www.cakephp.org/posts/add', 'body' => ['name' => 'HttpSocket-is-released', 'date' => 'today']];
        $response = $this->Socket->request($request);
        $this->assertIdentical($this->Socket->request['body'], 'name=HttpSocket-is-released&date=today');

        $request = ['uri' => '*', 'method' => 'GET'];
        $this->expectError(new PatternExpectation('/activate quirks mode/i'));
        $response = $this->Socket->request($request);
        $this->assertFalse($response);
        $this->assertFalse($this->Socket->response);

        $this->Socket->reset();
        $request = ['uri' => 'htpp://www.cakephp.org/'];
        $this->Socket->setReturnValue('connect', true);
        $this->Socket->setReturnValue('read', false);
        $this->Socket->_mock->_call_counts['read'] = 0;
        $number = mt_rand(0, 9999999);
        $serverResponse = "HTTP/1.x 200 OK\r\nDate: Mon, 16 Apr 2007 04:14:16 GMT\r\nServer: CakeHttp Server\r\nContent-Type: text/html\r\n\r\n<h1>Hello, your lucky number is ".$number.'</h1>';
        $this->Socket->setReturnValueAt(0, 'read', $serverResponse);
        $this->Socket->expect('write', ["GET / HTTP/1.1\r\nHost: www.cakephp.org\r\nConnection: close\r\nUser-Agent: CakePHP\r\n\r\n"]);
        $this->Socket->expectCallCount('read', 2);
        $response = $this->Socket->request($request);
        $this->assertIdentical($response, '<h1>Hello, your lucky number is '.$number.'</h1>');

        $this->Socket->reset();
        $serverResponse = "HTTP/1.x 200 OK\r\nSet-Cookie: foo=bar\r\nDate: Mon, 16 Apr 2007 04:14:16 GMT\r\nServer: CakeHttp Server\r\nContent-Type: text/html\r\n\r\n<h1>This is a cookie test!</h1>";
        unset($this->Socket->_mock->_actions->_at['read']);
        unset($this->Socket->_mock->_return_sequence['read']);
        $this->Socket->_mock->_call_counts['read'] = 0;
        $this->Socket->setReturnValueAt(0, 'read', $serverResponse);

        $this->Socket->connected = true;
        $this->Socket->request($request);
        $result = $this->Socket->response['cookies'];
        $expect = [
            'foo' => [
                'value' => 'bar',
            ],
        ];
        $this->assertEqual($result, $expect);
        $this->assertEqual($this->Socket->config['request']['cookies'], $expect);
        $this->assertFalse($this->Socket->connected);
    }

    /**
     * testUrl method.
     */
    public function testUrl()
    {
        $this->Socket->reset(true);

        $this->assertIdentical($this->Socket->url(true), false);

        $url = $this->Socket->url('www.cakephp.org');
        $this->assertIdentical($url, 'http://www.cakephp.org/');

        $url = $this->Socket->url('https://www.cakephp.org/posts/add');
        $this->assertIdentical($url, 'https://www.cakephp.org/posts/add');
        $url = $this->Socket->url('http://www.cakephp/search?q=socket', '/%path?%query');
        $this->assertIdentical($url, '/search?q=socket');

        $this->Socket->config['request']['uri']['host'] = 'bakery.cakephp.org';
        $url = $this->Socket->url();
        $this->assertIdentical($url, 'http://bakery.cakephp.org/');

        $this->Socket->configUri('http://www.cakephp.org');
        $url = $this->Socket->url('/search?q=bar');
        $this->assertIdentical($url, 'http://www.cakephp.org/search?q=bar');

        $url = $this->Socket->url(['host' => 'www.foobar.org', 'query' => ['q' => 'bar']]);
        $this->assertIdentical($url, 'http://www.foobar.org/?q=bar');

        $url = $this->Socket->url(['path' => '/supersearch', 'query' => ['q' => 'bar']]);
        $this->assertIdentical($url, 'http://www.cakephp.org/supersearch?q=bar');

        $this->Socket->configUri('http://www.google.com');
        $url = $this->Socket->url('/search?q=socket');
        $this->assertIdentical($url, 'http://www.google.com/search?q=socket');

        $url = $this->Socket->url();
        $this->assertIdentical($url, 'http://www.google.com/');

        $this->Socket->configUri('https://www.google.com');
        $url = $this->Socket->url('/search?q=socket');
        $this->assertIdentical($url, 'https://www.google.com/search?q=socket');

        $this->Socket->reset();
        $this->Socket->configUri('www.google.com:443');
        $url = $this->Socket->url('/search?q=socket');
        $this->assertIdentical($url, 'https://www.google.com/search?q=socket');

        $this->Socket->reset();
        $this->Socket->configUri('www.google.com:8080');
        $url = $this->Socket->url('/search?q=socket');
        $this->assertIdentical($url, 'http://www.google.com:8080/search?q=socket');
    }

    /**
     * testGet method.
     */
    public function testGet()
    {
        $this->RequestSocket->reset();

        $this->RequestSocket->expect('request', a(['method' => 'GET', 'uri' => 'http://www.google.com/']));
        $this->RequestSocket->get('http://www.google.com/');

        $this->RequestSocket->expect('request', a(['method' => 'GET', 'uri' => 'http://www.google.com/?foo=bar']));
        $this->RequestSocket->get('http://www.google.com/', ['foo' => 'bar']);

        $this->RequestSocket->expect('request', a(['method' => 'GET', 'uri' => 'http://www.google.com/?foo=bar']));
        $this->RequestSocket->get('http://www.google.com/', 'foo=bar');

        $this->RequestSocket->expect('request', a(['method' => 'GET', 'uri' => 'http://www.google.com/?foo=23&foobar=42']));
        $this->RequestSocket->get('http://www.google.com/?foo=bar', ['foobar' => '42', 'foo' => '23']);

        $this->RequestSocket->expect('request', a(['method' => 'GET', 'uri' => 'http://www.google.com/', 'auth' => ['user' => 'foo', 'pass' => 'bar']]));
        $this->RequestSocket->get('http://www.google.com/', null, ['auth' => ['user' => 'foo', 'pass' => 'bar']]);
    }

    /**
     * test that two consecutive get() calls reset the authentication credentials.
     */
    public function testConsecutiveGetResetsAuthCredentials()
    {
        $socket = new MockHttpSocket();
        $socket->config['request']['auth'] = [
            'method' => 'Basic',
            'user' => 'mark',
            'pass' => 'secret',
        ];
        $socket->get('http://mark:secret@example.com/test');
        $this->assertEqual($socket->request['uri']['user'], 'mark');
        $this->assertEqual($socket->request['uri']['pass'], 'secret');

        $socket->get('/test2');
        $this->assertEqual($socket->request['auth']['user'], 'mark');
        $this->assertEqual($socket->request['auth']['pass'], 'secret');

        $socket->get('/test3');
        $this->assertEqual($socket->request['auth']['user'], 'mark');
        $this->assertEqual($socket->request['auth']['pass'], 'secret');
    }

    /**
     * testPostPutDelete method.
     */
    public function testPostPutDelete()
    {
        $this->RequestSocket->reset();

        foreach (['POST', 'PUT', 'DELETE'] as $method) {
            $this->RequestSocket->expect('request', a(['method' => $method, 'uri' => 'http://www.google.com/', 'body' => []]));
            $this->RequestSocket->{low($method)}('http://www.google.com/');

            $this->RequestSocket->expect('request', a(['method' => $method, 'uri' => 'http://www.google.com/', 'body' => ['Foo' => 'bar']]));
            $this->RequestSocket->{low($method)}('http://www.google.com/', ['Foo' => 'bar']);

            $this->RequestSocket->expect('request', a(['method' => $method, 'uri' => 'http://www.google.com/', 'body' => null, 'line' => 'Hey Server']));
            $this->RequestSocket->{low($method)}('http://www.google.com/', null, ['line' => 'Hey Server']);
        }
    }

    /**
     * testParseResponse method.
     */
    public function testParseResponse()
    {
        $this->Socket->reset();

        $r = $this->Socket->parseResponse(['foo' => 'bar']);
        $this->assertIdentical($r, ['foo' => 'bar']);

        $r = $this->Socket->parseResponse(true);
        $this->assertIdentical($r, false);

        $r = $this->Socket->parseResponse("HTTP Foo\r\nBar: La");
        $this->assertIdentical($r, false);

        $tests = [
            'simple-request' => [
                'response' => [
                    'status-line' => "HTTP/1.x 200 OK\r\n",
                    'header' => "Date: Mon, 16 Apr 2007 04:14:16 GMT\r\nServer: CakeHttp Server\r\n",
                    'body' => "<h1>Hello World</h1>\r\n<p>It's good to be html</p>",
                ], 'expectations' => [
                    'status.http-version' => 'HTTP/1.x',
                    'status.code' => 200,
                    'status.reason-phrase' => 'OK',
                    'header' => $this->Socket->parseHeader("Date: Mon, 16 Apr 2007 04:14:16 GMT\r\nServer: CakeHttp Server\r\n"),
                    'body' => "<h1>Hello World</h1>\r\n<p>It's good to be html</p>",
                ],
            ],
            'no-header' => [
                'response' => [
                    'status-line' => "HTTP/1.x 404 OK\r\n",
                    'header' => null,
                ], 'expectations' => [
                    'status.code' => 404,
                    'header' => [],
                ],
            ],
            'chunked' => [
                'response' => [
                    'header' => "Transfer-Encoding: chunked\r\n",
                    'body' => "19\r\nThis is a chunked message\r\n0\r\n",
                ],
                'expectations' => [
                    'body' => 'This is a chunked message',
                    'header' => $this->Socket->parseHeader("Transfer-Encoding: chunked\r\n"),
                ],
            ],
            'enitity-header' => [
                'response' => [
                    'body' => "19\r\nThis is a chunked message\r\n0\r\nFoo: Bar\r\n",
                ],
                'expectations' => [
                    'header' => $this->Socket->parseHeader("Transfer-Encoding: chunked\r\nFoo: Bar\r\n"),
                ],
            ],
            'enitity-header-combine' => [
                'response' => [
                    'header' => "Transfer-Encoding: chunked\r\nFoo: Foobar\r\n",
                ],
                'expectations' => [
                    'header' => $this->Socket->parseHeader("Transfer-Encoding: chunked\r\nFoo: Foobar\r\nFoo: Bar\r\n"),
                ],
            ],
        ];

        $testResponse = [];
        $expectations = [];

        foreach ($tests as $name => $test) {
            $testResponse = array_merge($testResponse, $test['response']);
            $testResponse['response'] = $testResponse['status-line'].$testResponse['header']."\r\n".$testResponse['body'];
            $r = $this->Socket->parseResponse($testResponse['response']);
            $expectations = array_merge($expectations, $test['expectations']);

            foreach ($expectations as $property => $expectedVal) {
                $val = Set::extract($r, $property);
                $this->assertIdentical($val, $expectedVal, 'Test "'.$name.'": response.'.$property.' - %s');
            }

            foreach (['status-line', 'header', 'body', 'response'] as $field) {
                $this->assertIdentical($r['raw'][$field], $testResponse[$field], 'Test response.raw.'.$field.': %s');
            }
        }
    }

    /**
     * testDecodeBody method.
     */
    public function testDecodeBody()
    {
        $this->Socket->reset();

        $r = $this->Socket->decodeBody(true);
        $this->assertIdentical($r, false);

        $r = $this->Socket->decodeBody('Foobar', false);
        $this->assertIdentical($r, ['body' => 'Foobar', 'header' => false]);

        $encodings = [
            'chunked' => [
                'encoded' => "19\r\nThis is a chunked message\r\n0\r\n",
                'decoded' => ['body' => 'This is a chunked message', 'header' => false],
            ],
            'foo-coded' => [
                'encoded' => '!Foobar!',
                'decoded' => ['body' => '!Foobar!', 'header' => false],
                'error' => new PatternExpectation('/unknown encoding: foo-coded/i'),
            ],
        ];

        foreach ($encodings as $encoding => $sample) {
            if (isset($sample['error'])) {
                $this->expectError($sample['error']);
            }

            $r = $this->Socket->decodeBody($sample['encoded'], $encoding);
            $this->assertIdentical($r, $sample['decoded']);

            if (isset($sample['error'])) {
                $this->Socket->quirksMode = true;
                $r = $this->Socket->decodeBody($sample['encoded'], $encoding);
                $this->assertIdentical($r, $sample['decoded']);
                $this->Socket->quirksMode = false;
            }
        }
    }

    /**
     * testDecodeChunkedBody method.
     */
    public function testDecodeChunkedBody()
    {
        $this->Socket->reset();

        $r = $this->Socket->decodeChunkedBody(true);
        $this->assertIdentical($r, false);

        $encoded = "19\r\nThis is a chunked message\r\n0\r\n";
        $decoded = 'This is a chunked message';
        $r = $this->Socket->decodeChunkedBody($encoded);
        $this->assertIdentical($r['body'], $decoded);
        $this->assertIdentical($r['header'], false);

        $encoded = "19 \r\nThis is a chunked message\r\n0\r\n";
        $r = $this->Socket->decodeChunkedBody($encoded);
        $this->assertIdentical($r['body'], $decoded);

        $encoded = "19\r\nThis is a chunked message\r\nE\r\n\nThat is cool\n\r\n0\r\n";
        $decoded = "This is a chunked message\nThat is cool\n";
        $r = $this->Socket->decodeChunkedBody($encoded);
        $this->assertIdentical($r['body'], $decoded);
        $this->assertIdentical($r['header'], false);

        $encoded = "19\r\nThis is a chunked message\r\nE;foo-chunk=5\r\n\nThat is cool\n\r\n0\r\n";
        $r = $this->Socket->decodeChunkedBody($encoded);
        $this->assertIdentical($r['body'], $decoded);
        $this->assertIdentical($r['header'], false);

        $encoded = "19\r\nThis is a chunked message\r\nE\r\n\nThat is cool\n\r\n0\r\nfoo-header: bar\r\ncake: PHP\r\n\r\n";
        $r = $this->Socket->decodeChunkedBody($encoded);
        $this->assertIdentical($r['body'], $decoded);
        $this->assertIdentical($r['header'], ['Foo-Header' => 'bar', 'Cake' => 'PHP']);

        $encoded = "19\r\nThis is a chunked message\r\nE\r\n\nThat is cool\n\r\n";
        $this->expectError(new PatternExpectation('/activate quirks mode/i'));
        $r = $this->Socket->decodeChunkedBody($encoded);
        $this->assertIdentical($r, false);

        $this->Socket->quirksMode = true;
        $r = $this->Socket->decodeChunkedBody($encoded);
        $this->assertIdentical($r['body'], $decoded);
        $this->assertIdentical($r['header'], false);

        $encoded = "19\r\nThis is a chunked message\r\nE\r\n\nThat is cool\n\r\nfoo-header: bar\r\ncake: PHP\r\n\r\n";
        $r = $this->Socket->decodeChunkedBody($encoded);
        $this->assertIdentical($r['body'], $decoded);
        $this->assertIdentical($r['header'], ['Foo-Header' => 'bar', 'Cake' => 'PHP']);
    }

    /**
     * testBuildRequestLine method.
     */
    public function testBuildRequestLine()
    {
        $this->Socket->reset();

        $this->expectError(new PatternExpectation('/activate quirks mode/i'));
        $r = $this->Socket->buildRequestLine('Foo');
        $this->assertIdentical($r, false);

        $this->Socket->quirksMode = true;
        $r = $this->Socket->buildRequestLine('Foo');
        $this->assertIdentical($r, 'Foo');
        $this->Socket->quirksMode = false;

        $r = $this->Socket->buildRequestLine(true);
        $this->assertIdentical($r, false);

        $r = $this->Socket->buildRequestLine(['foo' => 'bar', 'method' => 'foo']);
        $this->assertIdentical($r, false);

        $r = $this->Socket->buildRequestLine(['method' => 'GET', 'uri' => 'http://www.cakephp.org/search?q=socket']);
        $this->assertIdentical($r, "GET /search?q=socket HTTP/1.1\r\n");

        $request = [
            'method' => 'GET',
            'uri' => [
                'path' => '/search',
                'query' => ['q' => 'socket'],
            ],
        ];
        $r = $this->Socket->buildRequestLine($request);
        $this->assertIdentical($r, "GET /search?q=socket HTTP/1.1\r\n");

        unset($request['method']);
        $r = $this->Socket->buildRequestLine($request);
        $this->assertIdentical($r, "GET /search?q=socket HTTP/1.1\r\n");

        $r = $this->Socket->buildRequestLine($request, 'CAKE-HTTP/0.1');
        $this->assertIdentical($r, "GET /search?q=socket CAKE-HTTP/0.1\r\n");

        $request = ['method' => 'OPTIONS', 'uri' => '*'];
        $r = $this->Socket->buildRequestLine($request);
        $this->assertIdentical($r, "OPTIONS * HTTP/1.1\r\n");

        $request['method'] = 'GET';
        $this->expectError(new PatternExpectation('/activate quirks mode/i'));
        $r = $this->Socket->buildRequestLine($request);
        $this->assertIdentical($r, false);

        $this->expectError(new PatternExpectation('/activate quirks mode/i'));
        $r = $this->Socket->buildRequestLine("GET * HTTP/1.1\r\n");
        $this->assertIdentical($r, false);

        $this->Socket->quirksMode = true;
        $r = $this->Socket->buildRequestLine($request);
        $this->assertIdentical($r, "GET * HTTP/1.1\r\n");

        $r = $this->Socket->buildRequestLine("GET * HTTP/1.1\r\n");
        $this->assertIdentical($r, "GET * HTTP/1.1\r\n");
    }

    /**
     * Asserts that HttpSocket::parseUri is working properly.
     */
    public function testParseUri()
    {
        $this->Socket->reset();

        $uri = $this->Socket->parseUri(['invalid' => 'uri-string']);
        $this->assertIdentical($uri, false);

        $uri = $this->Socket->parseUri(['invalid' => 'uri-string'], ['host' => 'somehost']);
        $this->assertIdentical($uri, ['host' => 'somehost', 'invalid' => 'uri-string']);

        $uri = $this->Socket->parseUri(false);
        $this->assertIdentical($uri, false);

        $uri = $this->Socket->parseUri('/my-cool-path');
        $this->assertIdentical($uri, ['path' => '/my-cool-path']);

        $uri = $this->Socket->parseUri('http://bob:foo123@www.cakephp.org:40/search?q=dessert#results');
        $this->assertIdentical($uri, [
            'scheme' => 'http',
            'host' => 'www.cakephp.org',
            'port' => 40,
            'user' => 'bob',
            'pass' => 'foo123',
            'path' => '/search',
            'query' => ['q' => 'dessert'],
            'fragment' => 'results',
        ]);

        $uri = $this->Socket->parseUri('http://www.cakephp.org/');
        $this->assertIdentical($uri, [
            'scheme' => 'http',
            'host' => 'www.cakephp.org',
            'path' => '/',
        ]);

        $uri = $this->Socket->parseUri('http://www.cakephp.org', true);
        $this->assertIdentical($uri, [
            'scheme' => 'http',
            'host' => 'www.cakephp.org',
            'port' => 80,
            'user' => null,
            'pass' => null,
            'path' => '/',
            'query' => [],
            'fragment' => null,
        ]);

        $uri = $this->Socket->parseUri('https://www.cakephp.org', true);
        $this->assertIdentical($uri, [
            'scheme' => 'https',
            'host' => 'www.cakephp.org',
            'port' => 443,
            'user' => null,
            'pass' => null,
            'path' => '/',
            'query' => [],
            'fragment' => null,
        ]);

        $uri = $this->Socket->parseUri('www.cakephp.org:443/query?foo', true);
        $this->assertIdentical($uri, [
            'scheme' => 'https',
            'host' => 'www.cakephp.org',
            'port' => 443,
            'user' => null,
            'pass' => null,
            'path' => '/query',
            'query' => ['foo' => ''],
            'fragment' => null,
        ]);

        $uri = $this->Socket->parseUri('http://www.cakephp.org', ['host' => 'piephp.org', 'user' => 'bob', 'fragment' => 'results']);
        $this->assertIdentical($uri, [
            'host' => 'www.cakephp.org',
            'user' => 'bob',
            'fragment' => 'results',
            'scheme' => 'http',
        ]);

        $uri = $this->Socket->parseUri('https://www.cakephp.org', ['scheme' => 'http', 'port' => 23]);
        $this->assertIdentical($uri, [
            'scheme' => 'https',
            'port' => 23,
            'host' => 'www.cakephp.org',
        ]);

        $uri = $this->Socket->parseUri('www.cakephp.org:59', ['scheme' => ['http', 'https'], 'port' => 80]);
        $this->assertIdentical($uri, [
            'scheme' => 'http',
            'port' => 59,
            'host' => 'www.cakephp.org',
        ]);

        $uri = $this->Socket->parseUri(['scheme' => 'http', 'host' => 'www.google.com', 'port' => 8080], ['scheme' => ['http', 'https'], 'host' => 'www.google.com', 'port' => [80, 443]]);
        $this->assertIdentical($uri, [
            'scheme' => 'http',
            'host' => 'www.google.com',
            'port' => 8080,
        ]);

        $uri = $this->Socket->parseUri('http://www.cakephp.org/?param1=value1&param2=value2%3Dvalue3');
        $this->assertIdentical($uri, [
            'scheme' => 'http',
            'host' => 'www.cakephp.org',
            'path' => '/',
            'query' => [
                'param1' => 'value1',
                'param2' => 'value2=value3',
            ],
        ]);

        $uri = $this->Socket->parseUri('http://www.cakephp.org/?param1=value1&param2=value2=value3');
        $this->assertIdentical($uri, [
            'scheme' => 'http',
            'host' => 'www.cakephp.org',
            'path' => '/',
            'query' => [
                'param1' => 'value1',
                'param2' => 'value2=value3',
            ],
        ]);
    }

    /**
     * Tests that HttpSocket::buildUri can turn all kinds of uri arrays (and strings) into fully or partially qualified URI's.
     */
    public function testBuildUri()
    {
        $this->Socket->reset();

        $r = $this->Socket->buildUri(true);
        $this->assertIdentical($r, false);

        $r = $this->Socket->buildUri('foo.com');
        $this->assertIdentical($r, 'http://foo.com/');

        $r = $this->Socket->buildUri(['host' => 'www.cakephp.org']);
        $this->assertIdentical($r, 'http://www.cakephp.org/');

        $r = $this->Socket->buildUri(['host' => 'www.cakephp.org', 'scheme' => 'https']);
        $this->assertIdentical($r, 'https://www.cakephp.org/');

        $r = $this->Socket->buildUri(['host' => 'www.cakephp.org', 'port' => 23]);
        $this->assertIdentical($r, 'http://www.cakephp.org:23/');

        $r = $this->Socket->buildUri(['path' => 'www.google.com/search', 'query' => 'q=cakephp']);
        $this->assertIdentical($r, 'http://www.google.com/search?q=cakephp');

        $r = $this->Socket->buildUri(['host' => 'www.cakephp.org', 'scheme' => 'https', 'port' => 79]);
        $this->assertIdentical($r, 'https://www.cakephp.org:79/');

        $r = $this->Socket->buildUri(['host' => 'www.cakephp.org', 'path' => 'foo']);
        $this->assertIdentical($r, 'http://www.cakephp.org/foo');

        $r = $this->Socket->buildUri(['host' => 'www.cakephp.org', 'path' => '/foo']);
        $this->assertIdentical($r, 'http://www.cakephp.org/foo');

        $r = $this->Socket->buildUri(['host' => 'www.cakephp.org', 'path' => '/search', 'query' => ['q' => 'HttpSocket']]);
        $this->assertIdentical($r, 'http://www.cakephp.org/search?q=HttpSocket');

        $r = $this->Socket->buildUri(['host' => 'www.cakephp.org', 'fragment' => 'bar']);
        $this->assertIdentical($r, 'http://www.cakephp.org/#bar');

        $r = $this->Socket->buildUri([
            'scheme' => 'https',
            'host' => 'www.cakephp.org',
            'port' => 25,
            'user' => 'bob',
            'pass' => 'secret',
            'path' => '/cool',
            'query' => ['foo' => 'bar'],
            'fragment' => 'comment',
        ]);
        $this->assertIdentical($r, 'https://bob:secret@www.cakephp.org:25/cool?foo=bar#comment');

        $r = $this->Socket->buildUri(['host' => 'www.cakephp.org', 'fragment' => 'bar'], '%fragment?%host');
        $this->assertIdentical($r, 'bar?www.cakephp.org');

        $r = $this->Socket->buildUri(['host' => 'www.cakephp.org'], '%fragment???%host');
        $this->assertIdentical($r, '???www.cakephp.org');

        $r = $this->Socket->buildUri(['path' => '*'], '/%path?%query');
        $this->assertIdentical($r, '*');

        $r = $this->Socket->buildUri(['scheme' => 'foo', 'host' => 'www.cakephp.org']);
        $this->assertIdentical($r, 'foo://www.cakephp.org:80/');
    }

    /**
     * Asserts that HttpSocket::parseQuery is working properly.
     */
    public function testParseQuery()
    {
        $this->Socket->reset();

        $query = $this->Socket->parseQuery(['framework' => 'cakephp']);
        $this->assertIdentical($query, ['framework' => 'cakephp']);

        $query = $this->Socket->parseQuery('');
        $this->assertIdentical($query, []);

        $query = $this->Socket->parseQuery('framework=cakephp');
        $this->assertIdentical($query, ['framework' => 'cakephp']);

        $query = $this->Socket->parseQuery('?framework=cakephp');
        $this->assertIdentical($query, ['framework' => 'cakephp']);

        $query = $this->Socket->parseQuery('a&b&c');
        $this->assertIdentical($query, ['a' => '', 'b' => '', 'c' => '']);

        $query = $this->Socket->parseQuery('value=12345');
        $this->assertIdentical($query, ['value' => '12345']);

        $query = $this->Socket->parseQuery('a[0]=foo&a[1]=bar&a[2]=cake');
        $this->assertIdentical($query, ['a' => [0 => 'foo', 1 => 'bar', 2 => 'cake']]);

        $query = $this->Socket->parseQuery('a[]=foo&a[]=bar&a[]=cake');
        $this->assertIdentical($query, ['a' => [0 => 'foo', 1 => 'bar', 2 => 'cake']]);

        $query = $this->Socket->parseQuery('a]][[=foo&[]=bar&]]][]=cake');
        $this->assertIdentical($query, ['a]][[' => 'foo', 0 => 'bar', ']]]' => ['cake']]);

        $query = $this->Socket->parseQuery('a[][]=foo&a[][]=bar&a[][]=cake');
        $expectedQuery = [
            'a' => [
                0 => [
                    0 => 'foo',
                ],
                1 => [
                    0 => 'bar',
                ],
                [
                    0 => 'cake',
                ],
            ],
        ];
        $this->assertIdentical($query, $expectedQuery);

        $query = $this->Socket->parseQuery('a[][]=foo&a[bar]=php&a[][]=bar&a[][]=cake');
        $expectedQuery = [
            'a' => [
                0 => [
                    0 => 'foo',
                ],
                'bar' => 'php',
                1 => [
                    0 => 'bar',
                ],
                [
                    0 => 'cake',
                ],
            ],
        ];
        $this->assertIdentical($query, $expectedQuery);

        $query = $this->Socket->parseQuery('user[]=jim&user[3]=tom&user[]=bob');
        $expectedQuery = [
            'user' => [
                0 => 'jim',
                3 => 'tom',
                4 => 'bob',
            ],
        ];
        $this->assertIdentical($query, $expectedQuery);

        $queryStr = 'user[0]=foo&user[0][items][]=foo&user[0][items][]=bar&user[][name]=jim&user[1][items][personal][]=book&user[1][items][personal][]=pen&user[1][items][]=ball&user[count]=2&empty';
        $query = $this->Socket->parseQuery($queryStr);
        $expectedQuery = [
            'user' => [
                0 => [
                    'items' => [
                        'foo',
                        'bar',
                    ],
                ],
                1 => [
                    'name' => 'jim',
                    'items' => [
                        'personal' => [
                            'book', 'pen',
                        ],
                        'ball',
                    ],
                ],
                'count' => '2',
            ],
            'empty' => '',
        ];
        $this->assertIdentical($query, $expectedQuery);
    }

    /**
     * Tests that HttpSocket::buildHeader can turn a given $header array into a proper header string according to
     * HTTP 1.1 specs.
     */
    public function testBuildHeader()
    {
        $this->Socket->reset();

        $r = $this->Socket->buildHeader(true);
        $this->assertIdentical($r, false);

        $r = $this->Socket->buildHeader('My raw header');
        $this->assertIdentical($r, 'My raw header');

        $r = $this->Socket->buildHeader(['Host' => 'www.cakephp.org']);
        $this->assertIdentical($r, "Host: www.cakephp.org\r\n");

        $r = $this->Socket->buildHeader(['Host' => 'www.cakephp.org', 'Connection' => 'Close']);
        $this->assertIdentical($r, "Host: www.cakephp.org\r\nConnection: Close\r\n");

        $r = $this->Socket->buildHeader(['People' => ['Bob', 'Jim', 'John']]);
        $this->assertIdentical($r, "People: Bob,Jim,John\r\n");

        $r = $this->Socket->buildHeader(['Multi-Line-Field' => "This is my\r\nMulti Line field"]);
        $this->assertIdentical($r, "Multi-Line-Field: This is my\r\n Multi Line field\r\n");

        $r = $this->Socket->buildHeader(['Multi-Line-Field' => "This is my\r\n Multi Line field"]);
        $this->assertIdentical($r, "Multi-Line-Field: This is my\r\n Multi Line field\r\n");

        $r = $this->Socket->buildHeader(['Multi-Line-Field' => "This is my\r\n\tMulti Line field"]);
        $this->assertIdentical($r, "Multi-Line-Field: This is my\r\n\tMulti Line field\r\n");

        $r = $this->Socket->buildHeader(['Test@Field' => 'My value']);
        $this->assertIdentical($r, "Test\"@\"Field: My value\r\n");
    }

    /**
     * Test that HttpSocket::parseHeader can take apart a given (and valid) $header string and turn it into an array.
     */
    public function testParseHeader()
    {
        $this->Socket->reset();

        $r = $this->Socket->parseHeader(['foo' => 'Bar', 'fOO-bAr' => 'quux']);
        $this->assertIdentical($r, ['Foo' => 'Bar', 'Foo-Bar' => 'quux']);

        $r = $this->Socket->parseHeader(true);
        $this->assertIdentical($r, false);

        $header = "Host: cakephp.org\t\r\n";
        $r = $this->Socket->parseHeader($header);
        $expected = [
            'Host' => 'cakephp.org',
        ];
        $this->assertIdentical($r, $expected);

        $header = "Date:Sat, 07 Apr 2007 10:10:25 GMT\r\nX-Powered-By: PHP/5.1.2\r\n";
        $r = $this->Socket->parseHeader($header);
        $expected = [
            'Date' => 'Sat, 07 Apr 2007 10:10:25 GMT', 'X-Powered-By' => 'PHP/5.1.2',
        ];
        $this->assertIdentical($r, $expected);

        $header = "people: Jim,John\r\nfoo-LAND: Bar\r\ncAKe-PHP: rocks\r\n";
        $r = $this->Socket->parseHeader($header);
        $expected = [
            'People' => 'Jim,John', 'Foo-Land' => 'Bar', 'Cake-Php' => 'rocks',
        ];
        $this->assertIdentical($r, $expected);

        $header = "People: Jim,John,Tim\r\nPeople: Lisa,Tina,Chelsea\r\n";
        $r = $this->Socket->parseHeader($header);
        $expected = [
            'People' => ['Jim,John,Tim', 'Lisa,Tina,Chelsea'],
        ];
        $this->assertIdentical($r, $expected);

        $header = "Multi-Line: I am a \r\nmulti line\t\r\nfield value.\r\nSingle-Line: I am not\r\n";
        $r = $this->Socket->parseHeader($header);
        $expected = [
            'Multi-Line' => "I am a\r\nmulti line\r\nfield value.", 'Single-Line' => 'I am not',
        ];
        $this->assertIdentical($r, $expected);

        $header = "Esc\"@\"ped: value\r\n";
        $r = $this->Socket->parseHeader($header);
        $expected = [
            'Esc@ped' => 'value',
        ];
        $this->assertIdentical($r, $expected);
    }

    /**
     * testParseCookies method.
     */
    public function testParseCookies()
    {
        $header = [
            'Set-Cookie' => [
                'foo=bar',
                'people=jim,jack,johnny";";Path=/accounts',
                'google=not=nice',
            ],
            'Transfer-Encoding' => 'chunked',
            'Date' => 'Sun, 18 Nov 2007 18:57:42 GMT',
        ];
        $cookies = $this->Socket->parseCookies($header);
        $expected = [
            'foo' => [
                'value' => 'bar',
            ],
            'people' => [
                'value' => 'jim,jack,johnny";"',
                'path' => '/accounts',
            ],
            'google' => [
                'value' => 'not=nice',
            ],
        ];
        $this->assertEqual($cookies, $expected);

        $header['Set-Cookie'][] = 'cakephp=great; Secure';
        $expected['cakephp'] = ['value' => 'great', 'secure' => true];
        $cookies = $this->Socket->parseCookies($header);
        $this->assertEqual($cookies, $expected);

        $header['Set-Cookie'] = 'foo=bar';
        unset($expected['people'], $expected['cakephp'], $expected['google']);
        $cookies = $this->Socket->parseCookies($header);
        $this->assertEqual($cookies, $expected);
    }

    /**
     * testBuildCookies method.
     *
     * @todo Test more scenarios
     */
    public function testBuildCookies()
    {
        $cookies = [
            'foo' => [
                'value' => 'bar',
            ],
            'people' => [
                'value' => 'jim,jack,johnny;',
                'path' => '/accounts',
            ],
        ];
        $expect = "Cookie: foo=bar; people=jim,jack,johnny\";\"\r\n";
        $result = $this->Socket->buildCookies($cookies);
        $this->assertEqual($result, $expect);
    }

    /**
     * Tests that HttpSocket::_tokenEscapeChars() returns the right characters.
     */
    public function testTokenEscapeChars()
    {
        $this->Socket->reset();

        $expected = [
            '\x22', '\x28', '\x29', '\x3c', '\x3e', '\x40', '\x2c', '\x3b', '\x3a', '\x5c', '\x2f', '\x5b', '\x5d', '\x3f', '\x3d', '\x7b',
            '\x7d', '\x20', '\x00', '\x01', '\x02', '\x03', '\x04', '\x05', '\x06', '\x07', '\x08', '\x09', '\x0a', '\x0b', '\x0c', '\x0d',
            '\x0e', '\x0f', '\x10', '\x11', '\x12', '\x13', '\x14', '\x15', '\x16', '\x17', '\x18', '\x19', '\x1a', '\x1b', '\x1c', '\x1d',
            '\x1e', '\x1f', '\x7f',
        ];
        $r = $this->Socket->tokenEscapeChars();
        $this->assertEqual($r, $expected);

        foreach ($expected as $key => $char) {
            $expected[$key] = chr(hexdec(substr($char, 2)));
        }

        $r = $this->Socket->tokenEscapeChars(false);
        $this->assertEqual($r, $expected);
    }

    /**
     * Test that HttpSocket::escapeToken is escaping all characters as descriped in RFC 2616 (HTTP 1.1 specs).
     */
    public function testEscapeToken()
    {
        $this->Socket->reset();

        $this->assertIdentical($this->Socket->escapeToken('Foo'), 'Foo');

        $escape = $this->Socket->tokenEscapeChars(false);
        foreach ($escape as $char) {
            $token = 'My-special-'.$char.'-Token';
            $escapedToken = $this->Socket->escapeToken($token);
            $expectedToken = 'My-special-"'.$char.'"-Token';

            $this->assertIdentical($escapedToken, $expectedToken, 'Test token escaping for ASCII '.ord($char));
        }

        $token = 'Extreme-:Token-	-"@-test';
        $escapedToken = $this->Socket->escapeToken($token);
        $expectedToken = 'Extreme-":"Token-"	"-""""@"-test';
        $this->assertIdentical($expectedToken, $escapedToken);
    }

    /**
     * Test that escaped token strings are properly unescaped by HttpSocket::unescapeToken.
     */
    public function testUnescapeToken()
    {
        $this->Socket->reset();

        $this->assertIdentical($this->Socket->unescapeToken('Foo'), 'Foo');

        $escape = $this->Socket->tokenEscapeChars(false);
        foreach ($escape as $char) {
            $token = 'My-special-"'.$char.'"-Token';
            $unescapedToken = $this->Socket->unescapeToken($token);
            $expectedToken = 'My-special-'.$char.'-Token';

            $this->assertIdentical($unescapedToken, $expectedToken, 'Test token unescaping for ASCII '.ord($char));
        }

        $token = 'Extreme-":"Token-"	"-""""@"-test';
        $escapedToken = $this->Socket->unescapeToken($token);
        $expectedToken = 'Extreme-:Token-	-"@-test';
        $this->assertIdentical($expectedToken, $escapedToken);
    }

    /**
     * This tests asserts HttpSocket::reset() resets a HttpSocket instance to it's initial state (before Object::__construct
     * got executed).
     */
    public function testReset()
    {
        $this->Socket->reset();

        $initialState = get_class_vars('HttpSocket');
        foreach ($initialState as $property => $value) {
            $this->Socket->{$property} = 'Overwritten';
        }

        $return = $this->Socket->reset();

        foreach ($initialState as $property => $value) {
            $this->assertIdentical($this->Socket->{$property}, $value);
        }

        $this->assertIdentical($return, true);
    }

    /**
     * This tests asserts HttpSocket::reset(false) resets certain HttpSocket properties to their initial state (before
     * Object::__construct got executed).
     */
    public function testPartialReset()
    {
        $this->Socket->reset();

        $partialResetProperties = ['request', 'response'];
        $initialState = get_class_vars('HttpSocket');

        foreach ($initialState as $property => $value) {
            $this->Socket->{$property} = 'Overwritten';
        }

        $return = $this->Socket->reset(false);

        foreach ($initialState as $property => $originalValue) {
            if (in_array($property, $partialResetProperties)) {
                $this->assertIdentical($this->Socket->{$property}, $originalValue);
            } else {
                $this->assertIdentical($this->Socket->{$property}, 'Overwritten');
            }
        }
        $this->assertIdentical($return, true);
    }
}
