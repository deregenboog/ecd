<?php
/**
 * HTTP Socket connection class.
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * @see          http://cakephp.org CakePHP(tm) Project
 * @since         CakePHP(tm) v 1.2.0
 *
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::import('Core', ['CakeSocket', 'Set', 'Router']);

/**
 * Cake network socket connection class.
 *
 * Core base class for HTTP network communication. HttpSocket can be used as an
 * Object Oriented replacement for cURL in many places.
 */
class HttpSocket extends CakeSocket
{
    /**
     * Object description.
     *
     * @var string
     */
    public $description = 'HTTP-based DataSource Interface';

    /**
     * When one activates the $quirksMode by setting it to true, all checks meant to
     * enforce RFC 2616 (HTTP/1.1 specs).
     * will be disabled and additional measures to deal with non-standard responses will be enabled.
     *
     * @var bool
     */
    public $quirksMode = false;

    /**
     * The default values to use for a request.
     *
     * @var array
     */
    public $request = [
        'method' => 'GET',
        'uri' => [
            'scheme' => 'http',
            'host' => null,
            'port' => 80,
            'user' => null,
            'pass' => null,
            'path' => null,
            'query' => null,
            'fragment' => null,
        ],
        'auth' => [
            'method' => 'Basic',
            'user' => null,
            'pass' => null,
        ],
        'version' => '1.1',
        'body' => '',
        'line' => null,
        'header' => [
            'Connection' => 'close',
            'User-Agent' => 'CakePHP',
        ],
        'raw' => null,
        'cookies' => [],
    ];

    /**
     * The default structure for storing the response.
     *
     * @var array
     */
    public $response = [
        'raw' => [
            'status-line' => null,
            'header' => null,
            'body' => null,
            'response' => null,
        ],
        'status' => [
            'http-version' => null,
            'code' => null,
            'reason-phrase' => null,
        ],
        'header' => [],
        'body' => '',
        'cookies' => [],
    ];

    /**
     * Default configuration settings for the HttpSocket.
     *
     * @var array
     */
    public $config = [
        'persistent' => false,
        'host' => 'localhost',
        'protocol' => 'tcp',
        'port' => 80,
        'timeout' => 30,
        'request' => [
            'uri' => [
                'scheme' => 'http',
                'host' => 'localhost',
                'port' => 80,
            ],
            'auth' => [
                'method' => 'Basic',
                'user' => null,
                'pass' => null,
            ],
            'cookies' => [],
        ],
    ];

    /**
     * String that represents a line break.
     *
     * @var string
     */
    public $lineBreak = "\r\n";

    /**
     * Build an HTTP Socket using the specified configuration.
     *
     * You can use a url string to set the url and use default configurations for
     * all other options:
     *
     * `$http =& new HttpSocket('http://cakephp.org/');`
     *
     * Or use an array to configure multiple options:
     *
     * {{{
     * $http =& new HttpSocket(array(
     *    'host' => 'cakephp.org',
     *    'timeout' => 20
     * ));
     * }}}
     *
     * See HttpSocket::$config for options that can be used.
     *
     * @param mixed $config configuration information, either a string url or an array of options
     */
    public function __construct($config = [])
    {
        if (is_string($config)) {
            $this->_configUri($config);
        } elseif (is_array($config)) {
            if (isset($config['request']['uri']) && is_string($config['request']['uri'])) {
                $this->_configUri($config['request']['uri']);
                unset($config['request']['uri']);
            }
            $this->config = Set::merge($this->config, $config);
        }
        parent::__construct($this->config);
    }

    /**
     * Issue the specified request. HttpSocket::get() and HttpSocket::post() wrap this
     * method and provide a more granular interface.
     *
     * @param mixed $request Either an URI string, or an array defining host/uri
     *
     * @return mixed false on error, request body on success
     */
    public function request($request = [])
    {
        $this->reset(false);

        if (is_string($request)) {
            $request = ['uri' => $request];
        } elseif (!is_array($request)) {
            return false;
        }

        if (!isset($request['uri'])) {
            $request['uri'] = null;
        }
        $uri = $this->_parseUri($request['uri']);
        $hadAuth = false;
        if (is_array($uri) && array_key_exists('user', $uri)) {
            $hadAuth = true;
        }
        if (!isset($uri['host'])) {
            $host = $this->config['host'];
        }
        if (isset($request['host'])) {
            $host = $request['host'];
            unset($request['host']);
        }
        $request['uri'] = $this->url($request['uri']);
        $request['uri'] = $this->_parseUri($request['uri'], true);
        $this->request = Set::merge($this->request, $this->config['request'], $request);

        if (!$hadAuth && !empty($this->config['request']['auth']['user'])) {
            $this->request['uri']['user'] = $this->config['request']['auth']['user'];
            $this->request['uri']['pass'] = $this->config['request']['auth']['pass'];
        }
        $this->_configUri($this->request['uri']);

        if (isset($host)) {
            $this->config['host'] = $host;
        }
        $cookies = null;

        if (is_array($this->request['header'])) {
            $this->request['header'] = $this->_parseHeader($this->request['header']);
            if (!empty($this->request['cookies'])) {
                $cookies = $this->buildCookies($this->request['cookies']);
            }
            $Host = $this->request['uri']['host'];
            $schema = '';
            $port = 0;
            if (isset($this->request['uri']['schema'])) {
                $schema = $this->request['uri']['schema'];
            }
            if (isset($this->request['uri']['port'])) {
                $port = $this->request['uri']['port'];
            }
            if (
                ('http' === $schema && 80 != $port) ||
                ('https' === $schema && 443 != $port) ||
                (80 != $port && 443 != $port)
            ) {
                $Host .= ':'.$port;
            }
            $this->request['header'] = array_merge(compact('Host'), $this->request['header']);
        }

        if (isset($this->request['auth']['user']) && isset($this->request['auth']['pass'])) {
            $this->request['header']['Authorization'] = $this->request['auth']['method'].' '.base64_encode($this->request['auth']['user'].':'.$this->request['auth']['pass']);
        }
        if (isset($this->request['uri']['user']) && isset($this->request['uri']['pass'])) {
            $this->request['header']['Authorization'] = $this->request['auth']['method'].' '.base64_encode($this->request['uri']['user'].':'.$this->request['uri']['pass']);
        }

        if (is_array($this->request['body'])) {
            $this->request['body'] = $this->_httpSerialize($this->request['body']);
        }

        if (!empty($this->request['body']) && !isset($this->request['header']['Content-Type'])) {
            $this->request['header']['Content-Type'] = 'application/x-www-form-urlencoded';
        }

        if (!empty($this->request['body']) && !isset($this->request['header']['Content-Length'])) {
            $this->request['header']['Content-Length'] = strlen($this->request['body']);
        }

        $connectionType = null;
        if (isset($this->request['header']['Connection'])) {
            $connectionType = $this->request['header']['Connection'];
        }
        $this->request['header'] = $this->_buildHeader($this->request['header']).$cookies;

        if (empty($this->request['line'])) {
            $this->request['line'] = $this->_buildRequestLine($this->request);
        }

        if (false === $this->quirksMode && false === $this->request['line']) {
            return $this->response = false;
        }

        if (false !== $this->request['line']) {
            $this->request['raw'] = $this->request['line'];
        }

        if (false !== $this->request['header']) {
            $this->request['raw'] .= $this->request['header'];
        }

        $this->request['raw'] .= "\r\n";
        $this->request['raw'] .= $this->request['body'];
        $this->write($this->request['raw']);

        $response = null;
        while ($data = $this->read()) {
            $response .= $data;
        }

        if ('close' == $connectionType) {
            $this->disconnect();
        }

        $this->response = $this->_parseResponse($response);
        if (!empty($this->response['cookies'])) {
            $this->config['request']['cookies'] = array_merge($this->config['request']['cookies'], $this->response['cookies']);
        }

        return $this->response['body'];
    }

    /**
     * Issues a GET request to the specified URI, query, and request.
     *
     * Using a string uri and an array of query string parameters:
     *
     * `$response = $http->get('http://google.com/search', array('q' => 'cakephp', 'client' => 'safari'));`
     *
     * Would do a GET request to `http://google.com/search?q=cakephp&client=safari`
     *
     * You could express the same thing using a uri array and query string parameters:
     *
     * {{{
     * $response = $http->get(
     *     array('host' => 'google.com', 'path' => '/search'),
     *     array('q' => 'cakephp', 'client' => 'safari')
     * );
     * }}}
     *
     * @param mixed $uri     URI to request. Either a string uri, or a uri array, see HttpSocket::_parseUri()
     * @param array $query   Querystring parameters to append to URI
     * @param array $request An indexed array with indexes such as 'method' or uri
     *
     * @return mixed result of request, either false on failure or the response to the request
     */
    public function get($uri = null, $query = [], $request = [])
    {
        if (!empty($query)) {
            $uri = $this->_parseUri($uri);
            if (isset($uri['query'])) {
                $uri['query'] = array_merge($uri['query'], $query);
            } else {
                $uri['query'] = $query;
            }
            $uri = $this->_buildUri($uri);
        }

        $request = Set::merge(['method' => 'GET', 'uri' => $uri], $request);

        return $this->request($request);
    }

    /**
     * Issues a POST request to the specified URI, query, and request.
     *
     * `post()` can be used to post simple data arrays to a url:
     *
     * {{{
     * $response = $http->post('http://example.com', array(
     *     'username' => 'batman',
     *     'password' => 'bruce_w4yne'
     * ));
     * }}}
     *
     * @param mixed $uri     URI to request. See HttpSocket::_parseUri()
     * @param array $data    array of POST data keys and values
     * @param array $request An indexed array with indexes such as 'method' or uri
     *
     * @return mixed result of request, either false on failure or the response to the request
     */
    public function post($uri = null, $data = [], $request = [])
    {
        $request = Set::merge(['method' => 'POST', 'uri' => $uri, 'body' => $data], $request);

        return $this->request($request);
    }

    /**
     * Issues a PUT request to the specified URI, query, and request.
     *
     * @param mixed $uri     URI to request, See HttpSocket::_parseUri()
     * @param array $data    array of PUT data keys and values
     * @param array $request An indexed array with indexes such as 'method' or uri
     *
     * @return mixed Result of request
     */
    public function put($uri = null, $data = [], $request = [])
    {
        $request = Set::merge(['method' => 'PUT', 'uri' => $uri, 'body' => $data], $request);

        return $this->request($request);
    }

    /**
     * Issues a DELETE request to the specified URI, query, and request.
     *
     * @param mixed $uri     URI to request (see {@link _parseUri()})
     * @param array $data    Query to append to URI
     * @param array $request An indexed array with indexes such as 'method' or uri
     *
     * @return mixed Result of request
     */
    public function delete($uri = null, $data = [], $request = [])
    {
        $request = Set::merge(['method' => 'DELETE', 'uri' => $uri, 'body' => $data], $request);

        return $this->request($request);
    }

    /**
     * Normalizes urls into a $uriTemplate.  If no template is provided
     * a default one will be used. Will generate the url using the
     * current config information.
     *
     * ### Usage:
     *
     * After configuring part of the request parameters, you can use url() to generate
     * urls.
     *
     * {{{
     * $http->configUri('http://www.cakephp.org');
     * $url = $http->url('/search?q=bar');
     * }}}
     *
     * Would return `http://www.cakephp.org/search?q=bar`
     *
     * url() can also be used with custom templates:
     *
     * `$url = $http->url('http://www.cakephp/search?q=socket', '/%path?%query');`
     *
     * Would return `/search?q=socket`.
     *
     * @param mixed  $url         either a string or array of url options to create a url with
     * @param string $uriTemplate a template string to use for url formatting
     *
     * @return mixed either false on failure or a string containing the composed url
     */
    public function url($url = null, $uriTemplate = null)
    {
        if (is_null($url)) {
            $url = '/';
        }
        if (is_string($url)) {
            if ('/' == $url[0]) {
                $url = $this->config['request']['uri']['host'].':'.$this->config['request']['uri']['port'].$url;
            }
            if (!preg_match('/^.+:\/\/|\*|^\//', $url)) {
                $url = $this->config['request']['uri']['scheme'].'://'.$url;
            }
        } elseif (!is_array($url) && !empty($url)) {
            return false;
        }

        $base = array_merge($this->config['request']['uri'], ['scheme' => ['http', 'https'], 'port' => [80, 443]]);
        $url = $this->_parseUri($url, $base);

        if (empty($url)) {
            $url = $this->config['request']['uri'];
        }

        if (!empty($uriTemplate)) {
            return $this->_buildUri($url, $uriTemplate);
        }

        return $this->_buildUri($url);
    }

    /**
     * Parses the given message and breaks it down in parts.
     *
     * @param string $message Message to parse
     *
     * @return array Parsed message (with indexed elements such as raw, status, header, body)
     */
    public function _parseResponse($message)
    {
        if (is_array($message)) {
            return $message;
        } elseif (!is_string($message)) {
            return false;
        }

        static $responseTemplate;

        if (empty($responseTemplate)) {
            $classVars = get_class_vars(__CLASS__);
            $responseTemplate = $classVars['response'];
        }

        $response = $responseTemplate;

        if (!preg_match("/^(.+\r\n)(.*)(?<=\r\n)\r\n/Us", $message, $match)) {
            return false;
        }

        list($null, $response['raw']['status-line'], $response['raw']['header']) = $match;
        $response['raw']['response'] = $message;
        $response['raw']['body'] = substr($message, strlen($match[0]));

        if (preg_match("/(.+) ([0-9]{3}) (.+)\r\n/DU", $response['raw']['status-line'], $match)) {
            $response['status']['http-version'] = $match[1];
            $response['status']['code'] = (int) $match[2];
            $response['status']['reason-phrase'] = $match[3];
        }

        $response['header'] = $this->_parseHeader($response['raw']['header']);
        $transferEncoding = null;
        if (isset($response['header']['Transfer-Encoding'])) {
            $transferEncoding = $response['header']['Transfer-Encoding'];
        }
        $decoded = $this->_decodeBody($response['raw']['body'], $transferEncoding);
        $response['body'] = $decoded['body'];

        if (!empty($decoded['header'])) {
            $response['header'] = $this->_parseHeader($this->_buildHeader($response['header']).$this->_buildHeader($decoded['header']));
        }

        if (!empty($response['header'])) {
            $response['cookies'] = $this->parseCookies($response['header']);
        }

        foreach ($response['raw'] as $field => $val) {
            if ('' === $val) {
                $response['raw'][$field] = null;
            }
        }

        return $response;
    }

    /**
     * Generic function to decode a $body with a given $encoding. Returns either an array with the keys
     * 'body' and 'header' or false on failure.
     *
     * @param string $body     a string continaing the body to decode
     * @param mixed  $encoding can be false in case no encoding is being used, or a string representing the encoding
     *
     * @return mixed array of response headers and body or false
     */
    public function _decodeBody($body, $encoding = 'chunked')
    {
        if (!is_string($body)) {
            return false;
        }
        if (empty($encoding)) {
            return ['body' => $body, 'header' => false];
        }
        $decodeMethod = '_decode'.Inflector::camelize(str_replace('-', '_', $encoding)).'Body';

        if (!is_callable([&$this, $decodeMethod])) {
            if (!$this->quirksMode) {
                trigger_error(sprintf(__('HttpSocket::_decodeBody - Unknown encoding: %s. Activate quirks mode to surpress error.', true), h($encoding)), E_USER_WARNING);
            }

            return ['body' => $body, 'header' => false];
        }

        return $this->{$decodeMethod}($body);
    }

    /**
     * Decodes a chunked message $body and returns either an array with the keys 'body' and 'header' or false as
     * a result.
     *
     * @param string $body a string continaing the chunked body to decode
     *
     * @return mixed array of response headers and body or false
     */
    public function _decodeChunkedBody($body)
    {
        if (!is_string($body)) {
            return false;
        }

        $decodedBody = null;
        $chunkLength = null;

        while (0 !== $chunkLength) {
            if (!preg_match("/^([0-9a-f]+) *(?:;(.+)=(.+))?\r\n/iU", $body, $match)) {
                if (!$this->quirksMode) {
                    trigger_error(__('HttpSocket::_decodeChunkedBody - Could not parse malformed chunk. Activate quirks mode to do this.', true), E_USER_WARNING);

                    return false;
                }
                break;
            }

            $chunkSize = 0;
            $hexLength = 0;
            $chunkExtensionName = '';
            $chunkExtensionValue = '';
            if (isset($match[0])) {
                $chunkSize = $match[0];
            }
            if (isset($match[1])) {
                $hexLength = $match[1];
            }
            if (isset($match[2])) {
                $chunkExtensionName = $match[2];
            }
            if (isset($match[3])) {
                $chunkExtensionValue = $match[3];
            }

            $body = substr($body, strlen($chunkSize));
            $chunkLength = hexdec($hexLength);
            $chunk = substr($body, 0, $chunkLength);
            if (!empty($chunkExtensionName)) {
                /*
                 * @todo See if there are popular chunk extensions we should implement
                 */
            }
            $decodedBody .= $chunk;
            if (0 !== $chunkLength) {
                $body = substr($body, $chunkLength + strlen("\r\n"));
            }
        }

        $entityHeader = false;
        if (!empty($body)) {
            $entityHeader = $this->_parseHeader($body);
        }

        return ['body' => $decodedBody, 'header' => $entityHeader];
    }

    /**
     * Parses and sets the specified URI into current request configuration.
     *
     * @param mixed $uri URI, See HttpSocket::_parseUri()
     *
     * @return array Current configuration settings
     */
    public function _configUri($uri = null)
    {
        if (empty($uri)) {
            return false;
        }

        if (is_array($uri)) {
            $uri = $this->_parseUri($uri);
        } else {
            $uri = $this->_parseUri($uri, true);
        }

        if (!isset($uri['host'])) {
            return false;
        }
        $config = [
            'request' => [
                'uri' => array_intersect_key($uri, $this->config['request']['uri']),
                'auth' => array_intersect_key($uri, $this->config['request']['auth']),
            ],
        ];
        $this->config = Set::merge($this->config, $config);
        $this->config = Set::merge($this->config, array_intersect_key($this->config['request']['uri'], $this->config));

        return $this->config;
    }

    /**
     * Takes a $uri array and turns it into a fully qualified URL string.
     *
     * @param mixed  $uri         Either A $uri array, or a request string.  Will use $this->config if left empty.
     * @param string $uriTemplate the Uri template/format to use
     *
     * @return mixed A fully qualified URL formated according to $uriTemplate, or false on failure
     */
    public function _buildUri($uri = [], $uriTemplate = '%scheme://%user:%pass@%host:%port/%path?%query#%fragment')
    {
        if (is_string($uri)) {
            $uri = ['host' => $uri];
        }
        $uri = $this->_parseUri($uri, true);

        if (!is_array($uri) || empty($uri)) {
            return false;
        }

        $uri['path'] = preg_replace('/^\//', null, $uri['path']);
        $uri['query'] = $this->_httpSerialize($uri['query']);
        $stripIfEmpty = [
            'query' => '?%query',
            'fragment' => '#%fragment',
            'user' => '%user:%pass@',
            'host' => '%host:%port/',
        ];

        foreach ($stripIfEmpty as $key => $strip) {
            if (empty($uri[$key])) {
                $uriTemplate = str_replace($strip, null, $uriTemplate);
            }
        }

        $defaultPorts = ['http' => 80, 'https' => 443];
        if (array_key_exists($uri['scheme'], $defaultPorts) && $defaultPorts[$uri['scheme']] == $uri['port']) {
            $uriTemplate = str_replace(':%port', null, $uriTemplate);
        }
        foreach ($uri as $property => $value) {
            $uriTemplate = str_replace('%'.$property, $value, $uriTemplate);
        }

        if ('/*' === $uriTemplate) {
            $uriTemplate = '*';
        }

        return $uriTemplate;
    }

    /**
     * Parses the given URI and breaks it down into pieces as an indexed array with elements
     * such as 'scheme', 'port', 'query'.
     *
     * @param string $uri  URI to parse
     * @param mixed  $base if true use default URI config, otherwise indexed array to set 'scheme', 'host', 'port', etc
     *
     * @return array Parsed URI
     */
    public function _parseUri($uri = null, $base = [])
    {
        $uriBase = [
            'scheme' => ['http', 'https'],
            'host' => null,
            'port' => [80, 443],
            'user' => null,
            'pass' => null,
            'path' => '/',
            'query' => null,
            'fragment' => null,
        ];

        if (is_string($uri)) {
            $uri = parse_url($uri);
        }
        if (!is_array($uri) || empty($uri)) {
            return false;
        }
        if (true === $base) {
            $base = $uriBase;
        }

        if (isset($base['port'], $base['scheme']) && is_array($base['port']) && is_array($base['scheme'])) {
            if (isset($uri['scheme']) && !isset($uri['port'])) {
                $base['port'] = $base['port'][array_search($uri['scheme'], $base['scheme'])];
            } elseif (isset($uri['port']) && !isset($uri['scheme'])) {
                $base['scheme'] = $base['scheme'][array_search($uri['port'], $base['port'])];
            }
        }

        if (is_array($base) && !empty($base)) {
            $uri = array_merge($base, $uri);
        }

        if (isset($uri['scheme']) && is_array($uri['scheme'])) {
            $uri['scheme'] = array_shift($uri['scheme']);
        }
        if (isset($uri['port']) && is_array($uri['port'])) {
            $uri['port'] = array_shift($uri['port']);
        }

        if (array_key_exists('query', $uri)) {
            $uri['query'] = $this->_parseQuery($uri['query']);
        }

        if (!array_intersect_key($uriBase, $uri)) {
            return false;
        }

        return $uri;
    }

    /**
     * This function can be thought of as a reverse to PHP5's http_build_query(). It takes a given query string and turns it into an array and
     * supports nesting by using the php bracket syntax. So this menas you can parse queries like:.
     *
     * - ?key[subKey]=value
     * - ?key[]=value1&key[]=value2
     *
     * A leading '?' mark in $query is optional and does not effect the outcome of this function.
     * For the complete capabilities of this implementation take a look at HttpSocketTest::testparseQuery()
     *
     * @param mixed $query A query string to parse into an array or an array to return directly "as is"
     *
     * @return array The $query parsed into a possibly multi-level array. If an empty $query is
     *               given, an empty array is returned.
     */
    public function _parseQuery($query)
    {
        if (is_array($query)) {
            return $query;
        }
        $parsedQuery = [];

        if (is_string($query) && !empty($query)) {
            $query = preg_replace('/^\?/', '', $query);
            $items = explode('&', $query);

            foreach ($items as $item) {
                if (false !== strpos($item, '=')) {
                    list($key, $value) = explode('=', $item, 2);
                } else {
                    $key = $item;
                    $value = null;
                }

                $key = urldecode($key);
                $value = urldecode($value);

                if (preg_match_all('/\[([^\[\]]*)\]/iUs', $key, $matches)) {
                    $subKeys = $matches[1];
                    $rootKey = substr($key, 0, strpos($key, '['));
                    if (!empty($rootKey)) {
                        array_unshift($subKeys, $rootKey);
                    }
                    $queryNode = &$parsedQuery;

                    foreach ($subKeys as $subKey) {
                        if (!is_array($queryNode)) {
                            $queryNode = [];
                        }

                        if ('' === $subKey) {
                            $queryNode[] = [];
                            end($queryNode);
                            $subKey = key($queryNode);
                        }
                        $queryNode = &$queryNode[$subKey];
                    }
                    $queryNode = $value;
                } else {
                    $parsedQuery[$key] = $value;
                }
            }
        }

        return $parsedQuery;
    }

    /**
     * Builds a request line according to HTTP/1.1 specs. Activate quirks mode to work outside specs.
     *
     * @param array  $request      Needs to contain a 'uri' key. Should also contain a 'method' key, otherwise defaults to GET.
     * @param string $versionToken The version token to use, defaults to HTTP/1.1
     *
     * @return string Request line
     */
    public function _buildRequestLine($request = [], $versionToken = 'HTTP/1.1')
    {
        $asteriskMethods = ['OPTIONS'];

        if (is_string($request)) {
            $isValid = preg_match("/(.+) (.+) (.+)\r\n/U", $request, $match);
            if (!$this->quirksMode && (!$isValid || ('*' == $match[2] && !in_array($match[3], $asteriskMethods)))) {
                trigger_error(__('HttpSocket::_buildRequestLine - Passed an invalid request line string. Activate quirks mode to do this.', true), E_USER_WARNING);

                return false;
            }

            return $request;
        } elseif (!is_array($request)) {
            return false;
        } elseif (!array_key_exists('uri', $request)) {
            return false;
        }

        $request['uri'] = $this->_parseUri($request['uri']);
        $request = array_merge(['method' => 'GET'], $request);
        $request['uri'] = $this->_buildUri($request['uri'], '/%path?%query');

        if (!$this->quirksMode && '*' === $request['uri'] && !in_array($request['method'], $asteriskMethods)) {
            trigger_error(sprintf(__('HttpSocket::_buildRequestLine - The "*" asterisk character is only allowed for the following methods: %s. Activate quirks mode to work outside of HTTP/1.1 specs.', true), join(',', $asteriskMethods)), E_USER_WARNING);

            return false;
        }

        return $request['method'].' '.$request['uri'].' '.$versionToken.$this->lineBreak;
    }

    /**
     * Serializes an array for transport.
     *
     * @param array $data Data to serialize
     *
     * @return string Serialized variable
     */
    public function _httpSerialize($data = [])
    {
        if (is_string($data)) {
            return $data;
        }
        if (empty($data) || !is_array($data)) {
            return false;
        }

        return substr(Router::queryString($data), 1);
    }

    /**
     * Builds the header.
     *
     * @param array $header Header to build
     *
     * @return string Header built from array
     */
    public function _buildHeader($header, $mode = 'standard')
    {
        if (is_string($header)) {
            return $header;
        } elseif (!is_array($header)) {
            return false;
        }

        $returnHeader = '';
        foreach ($header as $field => $contents) {
            if (is_array($contents) && 'standard' == $mode) {
                $contents = implode(',', $contents);
            }
            foreach ((array) $contents as $content) {
                $contents = preg_replace("/\r\n(?![\t ])/", "\r\n ", $content);
                $field = $this->_escapeToken($field);

                $returnHeader .= $field.': '.$contents.$this->lineBreak;
            }
        }

        return $returnHeader;
    }

    /**
     * Parses an array based header.
     *
     * @param array $header Header as an indexed array (field => value)
     *
     * @return array Parsed header
     */
    public function _parseHeader($header)
    {
        if (is_array($header)) {
            foreach ($header as $field => $value) {
                unset($header[$field]);
                $field = strtolower($field);
                preg_match_all('/(?:^|(?<=-))[a-z]/U', $field, $offsets, PREG_OFFSET_CAPTURE);

                foreach ($offsets[0] as $offset) {
                    $field = substr_replace($field, strtoupper($offset[0]), $offset[1], 1);
                }
                $header[$field] = $value;
            }

            return $header;
        } elseif (!is_string($header)) {
            return false;
        }

        preg_match_all("/(.+):(.+)(?:(?<![\t ])".$this->lineBreak.'|$)/Uis', $header, $matches, PREG_SET_ORDER);

        $header = [];
        foreach ($matches as $match) {
            list(, $field, $value) = $match;

            $value = trim($value);
            $value = preg_replace("/[\t ]\r\n/", "\r\n", $value);

            $field = $this->_unescapeToken($field);

            $field = strtolower($field);
            preg_match_all('/(?:^|(?<=-))[a-z]/U', $field, $offsets, PREG_OFFSET_CAPTURE);
            foreach ($offsets[0] as $offset) {
                $field = substr_replace($field, strtoupper($offset[0]), $offset[1], 1);
            }

            if (!isset($header[$field])) {
                $header[$field] = $value;
            } else {
                $header[$field] = array_merge((array) $header[$field], (array) $value);
            }
        }

        return $header;
    }

    /**
     * Parses cookies in response headers.
     *
     * @param array $header header array containing one ore more 'Set-Cookie' headers
     *
     * @return mixed either false on no cookies, or an array of cookies received
     *
     * @todo Make this 100% RFC 2965 confirm
     */
    public function parseCookies($header)
    {
        if (!isset($header['Set-Cookie'])) {
            return false;
        }

        $cookies = [];
        foreach ((array) $header['Set-Cookie'] as $cookie) {
            if (false !== strpos($cookie, '";"')) {
                $cookie = str_replace('";"', '{__cookie_replace__}', $cookie);
                $parts = str_replace('{__cookie_replace__}', '";"', explode(';', $cookie));
            } else {
                $parts = preg_split('/\;[ \t]*/', $cookie);
            }

            list($name, $value) = explode('=', array_shift($parts), 2);
            $cookies[$name] = compact('value');

            foreach ($parts as $part) {
                if (false !== strpos($part, '=')) {
                    list($key, $value) = explode('=', $part);
                } else {
                    $key = $part;
                    $value = true;
                }

                $key = strtolower($key);
                if (!isset($cookies[$name][$key])) {
                    $cookies[$name][$key] = $value;
                }
            }
        }

        return $cookies;
    }

    /**
     * Builds cookie headers for a request.
     *
     * @param array $cookies array of cookies to send with the request
     *
     * @return string cookie header string to be sent with the request
     *
     * @todo Refactor token escape mechanism to be configurable
     */
    public function buildCookies($cookies)
    {
        $header = [];
        foreach ($cookies as $name => $cookie) {
            $header[] = $name.'='.$this->_escapeToken($cookie['value'], [';']);
        }
        $header = $this->_buildHeader(['Cookie' => implode('; ', $header)], 'pragmatic');

        return $header;
    }

    /**
     * Unescapes a given $token according to RFC 2616 (HTTP 1.1 specs).
     *
     * @param string $token Token to unescape
     *
     * @return string Unescaped token
     *
     * @todo Test $chars parameter
     */
    public function _unescapeToken($token, $chars = null)
    {
        $regex = '/"(['.join('', $this->_tokenEscapeChars(true, $chars)).'])"/';
        $token = preg_replace($regex, '\\1', $token);

        return $token;
    }

    /**
     * Escapes a given $token according to RFC 2616 (HTTP 1.1 specs).
     *
     * @param string $token Token to escape
     *
     * @return string Escaped token
     *
     * @todo Test $chars parameter
     */
    public function _escapeToken($token, $chars = null)
    {
        $regex = '/(['.join('', $this->_tokenEscapeChars(true, $chars)).'])/';
        $token = preg_replace($regex, '"\\1"', $token);

        return $token;
    }

    /**
     * Gets escape chars according to RFC 2616 (HTTP 1.1 specs).
     *
     * @param bool $hex true to get them as HEX values, false otherwise
     *
     * @return array Escape chars
     *
     * @todo Test $chars parameter
     */
    public function _tokenEscapeChars($hex = true, $chars = null)
    {
        if (!empty($chars)) {
            $escape = $chars;
        } else {
            $escape = ['"', '(', ')', '<', '>', '@', ',', ';', ':', '\\', '/', '[', ']', '?', '=', '{', '}', ' '];
            for ($i = 0; $i <= 31; ++$i) {
                $escape[] = chr($i);
            }
            $escape[] = chr(127);
        }

        if (false == $hex) {
            return $escape;
        }
        $regexChars = '';
        foreach ($escape as $key => $char) {
            $escape[$key] = '\\x'.str_pad(dechex(ord($char)), 2, '0', STR_PAD_LEFT);
        }

        return $escape;
    }

    /**
     * Resets the state of this HttpSocket instance to it's initial state (before Object::__construct got executed) or does
     * the same thing partially for the request and the response property only.
     *
     * @param bool $full If set to false only HttpSocket::response and HttpSocket::request are reseted
     *
     * @return bool True on success
     */
    public function reset($full = true)
    {
        static $initalState = [];
        if (empty($initalState)) {
            $initalState = get_class_vars(__CLASS__);
        }
        if (false == $full) {
            $this->request = $initalState['request'];
            $this->response = $initalState['response'];

            return true;
        }
        parent::reset($initalState);

        return true;
    }
}
