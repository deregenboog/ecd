<?php
/**
 * Security Component.
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
 * @since         CakePHP(tm) v 0.10.8.2156
 *
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::import('Core', ['String', 'Security']);

/**
 * SecurityComponent.
 *
 * @see http://book.cakephp.org/1.3/en/The-Manual/Core-Components/Security-Component.html#Security-Component
 */
class SecurityComponent extends Object
{
    /**
     * The controller method that will be called if this request is black-hole'd.
     *
     * @var string
     */
    public $blackHoleCallback = null;

    /**
     * List of controller actions for which a POST request is required.
     *
     * @var array
     *
     * @see SecurityComponent::requirePost()
     */
    public $requirePost = [];

    /**
     * List of controller actions for which a GET request is required.
     *
     * @var array
     *
     * @see SecurityComponent::requireGet()
     */
    public $requireGet = [];

    /**
     * List of controller actions for which a PUT request is required.
     *
     * @var array
     *
     * @see SecurityComponent::requirePut()
     */
    public $requirePut = [];

    /**
     * List of controller actions for which a DELETE request is required.
     *
     * @var array
     *
     * @see SecurityComponent::requireDelete()
     */
    public $requireDelete = [];

    /**
     * List of actions that require an SSL-secured connection.
     *
     * @var array
     *
     * @see SecurityComponent::requireSecure()
     */
    public $requireSecure = [];

    /**
     * List of actions that require a valid authentication key.
     *
     * @var array
     *
     * @see SecurityComponent::requireAuth()
     */
    public $requireAuth = [];

    /**
     * List of actions that require an HTTP-authenticated login (basic or digest).
     *
     * @var array
     *
     * @see SecurityComponent::requireLogin()
     */
    public $requireLogin = [];

    /**
     * Login options for SecurityComponent::requireLogin().
     *
     * @var array
     *
     * @see SecurityComponent::requireLogin()
     */
    public $loginOptions = ['type' => '', 'prompt' => null];

    /**
     * An associative array of usernames/passwords used for HTTP-authenticated logins.
     *
     * @var array
     *
     * @see SecurityComponent::requireLogin()
     */
    public $loginUsers = [];

    /**
     * Controllers from which actions of the current controller are allowed to receive
     * requests.
     *
     * @var array
     *
     * @see SecurityComponent::requireAuth()
     */
    public $allowedControllers = [];

    /**
     * Actions from which actions of the current controller are allowed to receive
     * requests.
     *
     * @var array
     *
     * @see SecurityComponent::requireAuth()
     */
    public $allowedActions = [];

    /**
     * Form fields to disable.
     *
     * @var array
     */
    public $disabledFields = [];

    /**
     * Whether to validate POST data.  Set to false to disable for data coming from 3rd party
     * services, etc.
     *
     * @var bool
     */
    public $validatePost = true;

    /**
     * Other components used by the Security component.
     *
     * @var array
     */
    public $components = ['RequestHandler', 'Session'];

    /**
     * Holds the current action of the controller.
     *
     * @var string
     */
    public $_action = null;

    /**
     * Initialize the SecurityComponent.
     *
     * @param object $controller Controller instance for the request
     * @param array  $settings   Settings to set to the component
     */
    public function initialize(&$controller, $settings = [])
    {
        $this->_set($settings);
    }

    /**
     * Component startup. All security checking happens here.
     *
     * @param object $controller Instantiating controller
     */
    public function startup(&$controller)
    {
        $this->_action = strtolower($controller->action);
        $this->_methodsRequired($controller);
        $this->_secureRequired($controller);
        $this->_authRequired($controller);
        $this->_loginRequired($controller);

        $isPost = ($this->RequestHandler->isPost() || $this->RequestHandler->isPut());
        $isRequestAction = (
            !isset($controller->params['requested']) ||
            1 != $controller->params['requested']
        );

        if ($isPost && $isRequestAction && $this->validatePost) {
            if (false === $this->_validatePost($controller)) {
                if (!$this->blackHole($controller, 'auth')) {
                    return null;
                }
            }
        }
        $this->_generateToken($controller);
    }

    /**
     * Sets the actions that require a POST request, or empty for all actions.
     *
     * @see http://book.cakephp.org/1.3/en/The-Manual/Core-Components/Security-Component.html#requirePost
     */
    public function requirePost()
    {
        $args = func_get_args();
        $this->_requireMethod('Post', $args);
    }

    /**
     * Sets the actions that require a GET request, or empty for all actions.
     */
    public function requireGet()
    {
        $args = func_get_args();
        $this->_requireMethod('Get', $args);
    }

    /**
     * Sets the actions that require a PUT request, or empty for all actions.
     */
    public function requirePut()
    {
        $args = func_get_args();
        $this->_requireMethod('Put', $args);
    }

    /**
     * Sets the actions that require a DELETE request, or empty for all actions.
     */
    public function requireDelete()
    {
        $args = func_get_args();
        $this->_requireMethod('Delete', $args);
    }

    /**
     * Sets the actions that require a request that is SSL-secured, or empty for all actions.
     *
     * @see http://book.cakephp.org/1.3/en/The-Manual/Core-Components/Security-Component.html#requireSecure
     */
    public function requireSecure()
    {
        $args = func_get_args();
        $this->_requireMethod('Secure', $args);
    }

    /**
     * Sets the actions that require an authenticated request, or empty for all actions.
     *
     * @see http://book.cakephp.org/1.3/en/The-Manual/Core-Components/Security-Component.html#requireAuth
     */
    public function requireAuth()
    {
        $args = func_get_args();
        $this->_requireMethod('Auth', $args);
    }

    /**
     * Sets the actions that require an HTTP-authenticated request, or empty for all actions.
     *
     * @see http://book.cakephp.org/1.3/en/The-Manual/Core-Components/Security-Component.html#requireLogin
     */
    public function requireLogin()
    {
        $args = func_get_args();
        $base = $this->loginOptions;

        foreach ($args as $i => $arg) {
            if (is_array($arg)) {
                $this->loginOptions = $arg;
                unset($args[$i]);
            }
        }
        $this->loginOptions = array_merge($base, $this->loginOptions);
        $this->_requireMethod('Login', $args);

        if (isset($this->loginOptions['users'])) {
            $this->loginUsers = &$this->loginOptions['users'];
        }
    }

    /**
     * Attempts to validate the login credentials for an HTTP-authenticated request.
     *
     * @param string $type Either 'basic', 'digest', or null. If null/empty, will try both.
     *
     * @return mixed if successful, returns an array with login name and password, otherwise null
     *
     * @see http://book.cakephp.org/1.3/en/The-Manual/Core-Components/Security-Component.html#loginCredentials-string-type
     */
    public function loginCredentials($type = null)
    {
        switch (strtolower($type)) {
            case 'basic':
                $login = ['username' => env('PHP_AUTH_USER'), 'password' => env('PHP_AUTH_PW')];
                if (!empty($login['username'])) {
                    return $login;
                }
            break;
            case 'digest':
            default:
                $digest = null;

                if (version_compare(PHP_VERSION, '5.1') != -1) {
                    $digest = env('PHP_AUTH_DIGEST');
                } elseif (function_exists('apache_request_headers')) {
                    $headers = apache_request_headers();
                    if (isset($headers['Authorization']) && !empty($headers['Authorization']) && 'Digest ' == substr($headers['Authorization'], 0, 7)) {
                        $digest = substr($headers['Authorization'], 7);
                    }
                } else {
                    // Server doesn't support digest-auth headers
                    trigger_error(__('SecurityComponent::loginCredentials() - Server does not support digest authentication', true), E_USER_WARNING);
                }

                if (!empty($digest)) {
                    return $this->parseDigestAuthData($digest);
                }
            break;
        }

        return null;
    }

    /**
     * Generates the text of an HTTP-authentication request header from an array of options.
     *
     * @param array $options Set of options for header
     *
     * @return string HTTP-authentication request header
     *
     * @see http://book.cakephp.org/1.3/en/The-Manual/Core-Components/Security-Component.html#loginRequest-array-options
     */
    public function loginRequest($options = [])
    {
        $options = array_merge($this->loginOptions, $options);
        $this->_setLoginDefaults($options);
        $auth = 'WWW-Authenticate: '.ucfirst($options['type']);
        $out = ['realm="'.$options['realm'].'"'];

        if ('digest' == strtolower($options['type'])) {
            $out[] = 'qop="auth"';
            $out[] = 'nonce="'.uniqid('').'"';
            $out[] = 'opaque="'.md5($options['realm']).'"';
        }

        return $auth.' '.implode(',', $out);
    }

    /**
     * Parses an HTTP digest authentication response, and returns an array of the data, or null on failure.
     *
     * @param string $digest Digest authentication response
     *
     * @return array Digest authentication parameters
     *
     * @see http://book.cakephp.org/1.3/en/The-Manual/Core-Components/Security-Component.html#parseDigestAuthData-string-digest
     */
    public function parseDigestAuthData($digest)
    {
        if ('Digest ' == substr($digest, 0, 7)) {
            $digest = substr($digest, 7);
        }
        $keys = [];
        $match = [];
        $req = ['nonce' => 1, 'nc' => 1, 'cnonce' => 1, 'qop' => 1, 'username' => 1, 'uri' => 1, 'response' => 1];
        preg_match_all('/(\w+)=([\'"]?)([a-zA-Z0-9@=.\/_-]+)\2/', $digest, $match, PREG_SET_ORDER);

        foreach ($match as $i) {
            $keys[$i[1]] = $i[3];
            unset($req[$i[1]]);
        }

        if (empty($req)) {
            return $keys;
        }

        return null;
    }

    /**
     * Generates a hash to be compared with an HTTP digest-authenticated response.
     *
     * @param array $data HTTP digest response data, as parsed by SecurityComponent::parseDigestAuthData()
     *
     * @return string Digest authentication hash
     *
     * @see SecurityComponent::parseDigestAuthData()
     * @see http://book.cakephp.org/1.3/en/The-Manual/Core-Components/Security-Component.html#generateDigestResponseHash-array-data
     */
    public function generateDigestResponseHash($data)
    {
        return md5(
            md5($data['username'].':'.$this->loginOptions['realm'].':'.$this->loginUsers[$data['username']]).
            ':'.$data['nonce'].':'.$data['nc'].':'.$data['cnonce'].':'.$data['qop'].':'.
            md5(env('REQUEST_METHOD').':'.$data['uri'])
        );
    }

    /**
     * Black-hole an invalid request with a 404 error or custom callback. If SecurityComponent::$blackHoleCallback
     * is specified, it will use this callback by executing the method indicated in $error.
     *
     * @param object $controller Instantiating controller
     * @param string $error      Error method
     *
     * @return mixed If specified, controller blackHoleCallback's response, or no return otherwise
     *
     * @see SecurityComponent::$blackHoleCallback
     * @see http://book.cakephp.org/1.3/en/The-Manual/Core-Components/Security-Component.html#blackHole-object-controller-string-error
     */
    public function blackHole(&$controller, $error = '')
    {
        if (null == $this->blackHoleCallback) {
            $code = 404;
            if ('login' == $error) {
                $code = 401;
                $controller->header($this->loginRequest());
            }
            $controller->redirect(null, $code, true);
        } else {
            return $this->_callback($controller, $this->blackHoleCallback, [$error]);
        }
    }

    /**
     * Sets the actions that require a $method HTTP request, or empty for all actions.
     *
     * @param string $method  The HTTP method to assign controller actions to
     * @param array  $actions controller actions to set the required HTTP method to
     */
    public function _requireMethod($method, $actions = [])
    {
        if (isset($actions[0]) && is_array($actions[0])) {
            $actions = $actions[0];
        }
        $this->{'require'.$method} = (empty($actions)) ? ['*'] : $actions;
    }

    /**
     * Check if HTTP methods are required.
     *
     * @param object $controller Instantiating controller
     *
     * @return bool true if $method is required
     */
    public function _methodsRequired(&$controller)
    {
        foreach (['Post', 'Get', 'Put', 'Delete'] as $method) {
            $property = 'require'.$method;
            if (is_array($this->$property) && !empty($this->$property)) {
                $require = array_map('strtolower', $this->$property);

                if (in_array($this->_action, $require) || $this->$property == ['*']) {
                    if (!$this->RequestHandler->{'is'.$method}()) {
                        if (!$this->blackHole($controller, strtolower($method))) {
                            return null;
                        }
                    }
                }
            }
        }

        return true;
    }

    /**
     * Check if access requires secure connection.
     *
     * @param object $controller Instantiating controller
     *
     * @return bool true if secure connection required
     */
    public function _secureRequired(&$controller)
    {
        if (is_array($this->requireSecure) && !empty($this->requireSecure)) {
            $requireSecure = array_map('strtolower', $this->requireSecure);

            if (in_array($this->_action, $requireSecure) || $this->requireSecure == ['*']) {
                if (!$this->RequestHandler->isSSL()) {
                    if (!$this->blackHole($controller, 'secure')) {
                        return null;
                    }
                }
            }
        }

        return true;
    }

    /**
     * Check if authentication is required.
     *
     * @param object $controller Instantiating controller
     *
     * @return bool true if authentication required
     */
    public function _authRequired(&$controller)
    {
        if (is_array($this->requireAuth) && !empty($this->requireAuth) && !empty($controller->data)) {
            $requireAuth = array_map('strtolower', $this->requireAuth);

            if (in_array($this->_action, $requireAuth) || $this->requireAuth == ['*']) {
                if (!isset($controller->data['_Token'])) {
                    if (!$this->blackHole($controller, 'auth')) {
                        return null;
                    }
                }

                if ($this->Session->check('_Token')) {
                    $tData = unserialize($this->Session->read('_Token'));

                    if (!empty($tData['allowedControllers']) && !in_array($controller->params['controller'], $tData['allowedControllers']) || !empty($tData['allowedActions']) && !in_array($controller->params['action'], $tData['allowedActions'])) {
                        if (!$this->blackHole($controller, 'auth')) {
                            return null;
                        }
                    }
                } else {
                    if (!$this->blackHole($controller, 'auth')) {
                        return null;
                    }
                }
            }
        }

        return true;
    }

    /**
     * Check if login is required.
     *
     * @param object $controller Instantiating controller
     *
     * @return bool true if login is required
     */
    public function _loginRequired(&$controller)
    {
        if (is_array($this->requireLogin) && !empty($this->requireLogin)) {
            $requireLogin = array_map('strtolower', $this->requireLogin);

            if (in_array($this->_action, $requireLogin) || $this->requireLogin == ['*']) {
                $login = $this->loginCredentials($this->loginOptions['type']);

                if (null == $login) {
                    $controller->header($this->loginRequest());

                    if (!empty($this->loginOptions['prompt'])) {
                        $this->_callback($controller, $this->loginOptions['prompt']);
                    } else {
                        $this->blackHole($controller, 'login');
                    }
                } else {
                    if (isset($this->loginOptions['login'])) {
                        $this->_callback($controller, $this->loginOptions['login'], [$login]);
                    } else {
                        if ('digest' == strtolower($this->loginOptions['type'])) {
                            if ($login && isset($this->loginUsers[$login['username']])) {
                                if ($login['response'] == $this->generateDigestResponseHash($login)) {
                                    return true;
                                }
                            }
                            $this->blackHole($controller, 'login');
                        } else {
                            if (
                                !(in_array($login['username'], array_keys($this->loginUsers)) &&
                                $this->loginUsers[$login['username']] == $login['password'])
                            ) {
                                $this->blackHole($controller, 'login');
                            }
                        }
                    }
                }
            }
        }

        return true;
    }

    /**
     * Validate submitted form.
     *
     * @param object $controller Instantiating controller
     *
     * @return bool true if submitted form is valid
     */
    public function _validatePost(&$controller)
    {
        if (empty($controller->data)) {
            return true;
        }
        $data = $controller->data;

        if (!isset($data['_Token']) || !isset($data['_Token']['fields']) || !isset($data['_Token']['key'])) {
            return false;
        }
        $token = $data['_Token']['key'];

        if ($this->Session->check('_Token')) {
            $tokenData = unserialize($this->Session->read('_Token'));

            if ($tokenData['expires'] < time() || $tokenData['key'] !== $token) {
                return false;
            }
        } else {
            return false;
        }

        $locked = null;
        $check = $controller->data;
        $token = urldecode($check['_Token']['fields']);

        if (strpos($token, ':')) {
            list($token, $locked) = explode(':', $token, 2);
        }
        unset($check['_Token']);

        $locked = explode('|', $locked);

        $lockedFields = [];
        $fields = Set::flatten($check);
        $fieldList = array_keys($fields);
        $multi = [];

        foreach ($fieldList as $i => $key) {
            if (preg_match('/\.\d+$/', $key)) {
                $multi[$i] = preg_replace('/\.\d+$/', '', $key);
                unset($fieldList[$i]);
            }
        }
        if (!empty($multi)) {
            $fieldList += array_unique($multi);
        }

        foreach ($fieldList as $i => $key) {
            $isDisabled = false;
            $isLocked = (is_array($locked) && in_array($key, $locked));

            if (!empty($this->disabledFields)) {
                foreach ((array) $this->disabledFields as $disabled) {
                    $disabled = explode('.', $disabled);
                    $field = array_values(array_intersect(explode('.', $key), $disabled));
                    $isDisabled = ($field === $disabled);
                    if ($isDisabled) {
                        break;
                    }
                }
            }

            if ($isDisabled || $isLocked) {
                unset($fieldList[$i]);
                if ($isLocked) {
                    $lockedFields[$key] = $fields[$key];
                }
            }
        }
        sort($fieldList, SORT_STRING);
        ksort($lockedFields, SORT_STRING);

        $fieldList += $lockedFields;
        $url = $controller->here;
        $check = Security::hash($url.serialize($fieldList).Configure::read('Security.salt'));

        return $token === $check;
    }

    /**
     * Add authentication key for new form posts.
     *
     * @param object $controller Instantiating controller
     *
     * @return bool Success
     */
    public function _generateToken(&$controller)
    {
        if (isset($controller->params['requested']) && 1 === $controller->params['requested']) {
            if ($this->Session->check('_Token')) {
                $tokenData = unserialize($this->Session->read('_Token'));
                $controller->params['_Token'] = $tokenData;
            }

            return false;
        }
        $authKey = Security::generateAuthKey();
        $expires = strtotime('+'.Security::inactiveMins().' minutes');
        $token = [
            'key' => $authKey,
            'expires' => $expires,
            'allowedControllers' => $this->allowedControllers,
            'allowedActions' => $this->allowedActions,
            'disabledFields' => $this->disabledFields,
        ];

        if (!isset($controller->data)) {
            $controller->data = [];
        }

        if ($this->Session->check('_Token')) {
            $tokenData = unserialize($this->Session->read('_Token'));
            $valid = (
                isset($tokenData['expires']) &&
                $tokenData['expires'] > time() &&
                isset($tokenData['key'])
            );

            if ($valid) {
                $token['key'] = $tokenData['key'];
            }
        }
        $controller->params['_Token'] = $token;
        $this->Session->write('_Token', serialize($token));

        return true;
    }

    /**
     * Sets the default login options for an HTTP-authenticated request.
     *
     * @param array $options Default login options
     */
    public function _setLoginDefaults(&$options)
    {
        $options = array_merge([
            'type' => 'basic',
            'realm' => env('SERVER_NAME'),
            'qop' => 'auth',
            'nonce' => Str::uuid(),
        ], array_filter($options));
        $options = array_merge(['opaque' => md5($options['realm'])], $options);
    }

    /**
     * Calls a controller callback method.
     *
     * @param object $controller Controller to run callback on
     * @param string $method     Method to execute
     * @param array  $params     Parameters to send to method
     *
     * @return mixed Controller callback method's response
     */
    public function _callback(&$controller, $method, $params = [])
    {
        if (is_callable([$controller, $method])) {
            return call_user_func_array([&$controller, $method], empty($params) ? null : $params);
        } else {
            return null;
        }
    }
}
