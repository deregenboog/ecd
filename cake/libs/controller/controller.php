<?php
/**
 * Base controller class.
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
 * @since         CakePHP(tm) v 0.2.9
 *
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * Include files.
 */
App::import('Controller', 'Component', false);
App::import('View', 'View', false);
/**
 * Controller.
 *
 * Application controller class for organization of business logic.
 * Provides basic functionality, such as rendering views inside layouts,
 * automatic model availability, redirection, callbacks, and more.
 *
 * @see          http://book.cakephp.org/1.3/en/The-Manual/Developing-With-CakePHP/Controllers.html#Introduction
 */
class Controller extends Object
{
    /**
     * The name of this controller. Controller names are plural, named after the model they manipulate.
     *
     * @var string
     *
     * @see http://book.cakephp.org/1.3/en/The-Manual/Developing-With-CakePHP/Controllers.html#Controller-Attributes
     */
    public $name = null;

    /**
     * Stores the current URL, relative to the webroot of the application.
     *
     * @var string
     */
    public $here = null;

    /**
     * The webroot of the application.
     *
     * @var string
     */
    public $webroot = null;

    /**
     * The name of the currently requested controller action.
     *
     * @var string
     */
    public $action = null;

    /**
     * An array containing the class names of models this controller uses.
     *
     * Example: `var $uses = array('Product', 'Post', 'Comment');`
     *
     * Can be set to array() to use no models.  Can be set to false to
     * use no models and prevent the merging of $uses with AppController
     *
     * @var mixed a single name as a string or a list of names as an array
     *
     * @see http://book.cakephp.org/1.3/en/The-Manual/Developing-With-CakePHP/Controllers.html#components-helpers-and-uses
     */
    public $uses = false;

    /**
     * An array containing the names of helpers this controller uses. The array elements should
     * not contain the "Helper" part of the classname.
     *
     * Example: `var $helpers = array('Html', 'Javascript', 'Time', 'Ajax');`
     *
     * @var mixed a single name as a string or a list of names as an array
     *
     * @see http://book.cakephp.org/1.3/en/The-Manual/Developing-With-CakePHP/Controllers.html#components-helpers-and-uses
     */
    public $helpers = ['Session', 'Html', 'Form'];

    /**
     * Parameters received in the current request: GET and POST data, information
     * about the request, etc.
     *
     * @var array
     *
     * @see http://book.cakephp.org/1.3/en/The-Manual/Developing-With-CakePHP/Controllers.html#The-Parameters-Attribute-params
     */
    public $params = [];

    /**
     * Data POSTed to the controller using the HtmlHelper. Data here is accessible
     * using the `$this->data['ModelName']['fieldName']` pattern.
     *
     * @var array
     */
    public $data = [];

    /**
     * Holds pagination defaults for controller actions. The keys that can be included
     * in this array are: 'conditions', 'fields', 'order', 'limit', 'page', and 'recursive',
     * similar to the keys in the second parameter of Model::find().
     *
     * Pagination defaults can also be supplied in a model-by-model basis by using
     * the name of the model as a key for a pagination array:
     *
     * {{{
     * var $paginate = array(
     * 		'Post' => array(...),
     * 		'Comment' => array(...)
     * 	);
     * }}}
     *
     * @var array
     *
     * @see http://book.cakephp.org/1.3/en/The-Manual/Developing-With-CakePHP/Controllers.html#Pagination
     */
    public $paginate = ['limit' => 20, 'page' => 1];

    /**
     * The name of the views subfolder containing views for this controller.
     *
     * @var string
     */
    public $viewPath = null;

    /**
     * The name of the layouts subfolder containing layouts for this controller.
     *
     * @var string
     */
    public $layoutPath = null;

    /**
     * Contains variables to be handed to the view.
     *
     * @var array
     */
    public $viewVars = [];

    /**
     * An array containing the class names of the models this controller uses.
     *
     * @var array array of model objects
     */
    public $modelNames = [];

    /**
     * Base URL path.
     *
     * @var string
     */
    public $base = null;

    /**
     * The name of the layout file to render the view inside of. The name specified
     * is the filename of the layout in /app/views/layouts without the .ctp
     * extension.
     *
     * @var string
     *
     * @see http://book.cakephp.org/1.3/en/The-Manual/Developing-With-CakePHP/Controllers.html#Page-related-Attributes-layout-and-pageTitle
     */
    public $layout = 'default';

    /**
     * Set to true to automatically render the view
     * after action logic.
     *
     * @var bool
     */
    public $autoRender = true;

    /**
     * Set to true to automatically render the layout around views.
     *
     * @var bool
     */
    public $autoLayout = true;

    /**
     * Instance of Component used to handle callbacks.
     *
     * @var string
     */
    public $Component = null;

    /**
     * Array containing the names of components this controller uses. Component names
     * should not contain the "Component" portion of the classname.
     *
     * Example: `var $components = array('Session', 'RequestHandler', 'Acl');`
     *
     * @var array
     *
     * @see http://book.cakephp.org/1.3/en/The-Manual/Developing-With-CakePHP/Controllers.html#components-helpers-and-uses
     */
    public $components = ['Session'];

    /**
     * The name of the View class this controller sends output to.
     *
     * @var string
     */
    public $view = 'View';

    /**
     * File extension for view templates. Defaults to Cake's conventional ".ctp".
     *
     * @var string
     */
    public $ext = '.ctp';

    /**
     * The output of the requested action.  Contains either a variable
     * returned from the action, or the data of the rendered view;
     * You can use this var in child controllers' afterFilter() callbacks to alter output.
     *
     * @var string
     */
    public $output = null;

    /**
     * Automatically set to the name of a plugin.
     *
     * @var string
     */
    public $plugin = null;

    /**
     * Used to define methods a controller that will be cached. To cache a
     * single action, the value is set to an array containing keys that match
     * action names and values that denote cache expiration times (in seconds).
     *
     * Example:
     *
     * {{{
     * var $cacheAction = array(
     *		'view/23/' => 21600,
     *		'recalled/' => 86400
     *	);
     * }}}
     *
     * $cacheAction can also be set to a strtotime() compatible string. This
     * marks all the actions in the controller for view caching.
     *
     * @var mixed
     *
     * @see http://book.cakephp.org/1.3/en/The-Manual/Developing-With-CakePHP/Controllers.html#Caching-in-the-Controller
     */
    public $cacheAction = false;

    /**
     * Used to create cached instances of models a controller uses.
     * When set to true, all models related to the controller will be cached.
     * This can increase performance in many cases.
     *
     * @var bool
     */
    public $persistModel = false;

    /**
     * Holds all params passed and named.
     *
     * @var mixed
     */
    public $passedArgs = [];

    /**
     * Triggers Scaffolding.
     *
     * @var mixed
     *
     * @see http://book.cakephp.org/1.3/en/The-Manual/Developing-With-CakePHP/Controllers.html#Scaffolding
     */
    public $scaffold = false;

    /**
     * Holds current methods of the controller.
     *
     * @var array
     *
     * @see
     */
    public $methods = [];

    /**
     * This controller's primary model class name, the Inflector::classify()'ed version of
     * the controller's $name property.
     *
     * Example: For a controller named 'Comments', the modelClass would be 'Comment'
     *
     * @var string
     */
    public $modelClass = null;

    /**
     * This controller's model key name, an underscored version of the controller's $modelClass property.
     *
     * Example: For a controller named 'ArticleComments', the modelKey would be 'article_comment'
     *
     * @var string
     */
    public $modelKey = null;

    /**
     * Holds any validation errors produced by the last call of the validateErrors() method/.
     *
     * @var array Validation errors, or false if none
     */
    public $validationErrors = null;

    /**
     * Contains a list of the HTTP codes that CakePHP recognizes. These may be
     * queried and/or modified through Controller::httpCodes(), which is also
     * tasked with their lazy-loading.
     *
     * @var array associative array of HTTP codes and their associated messages
     */
    public $__httpCodes = null;

    /**
     * Constructor.
     */
    public function __construct()
    {
        if (null === $this->name) {
            $r = null;
            if (!preg_match('/(.*)Controller/i', get_class($this), $r)) {
                __('Controller::__construct() : Can not get or parse my own class name, exiting.');
                $this->_stop();
            }
            $this->name = $r[1];
        }

        if (null == $this->viewPath) {
            $this->viewPath = Inflector::underscore($this->name);
        }
        $this->modelClass = Inflector::classify($this->name);
        $this->modelKey = Inflector::underscore($this->modelClass);
        $this->Component = new Component();

        $childMethods = get_class_methods($this);
        $parentMethods = get_class_methods('Controller');

        foreach ($childMethods as $key => $value) {
            $childMethods[$key] = strtolower($value);
        }

        foreach ($parentMethods as $key => $value) {
            $parentMethods[$key] = strtolower($value);
        }
        $this->methods = array_diff($childMethods, $parentMethods);
        parent::__construct();
    }

    /**
     * Merge components, helpers, and uses vars from AppController and PluginAppController.
     */
    public function __mergeVars()
    {
        $pluginName = Inflector::camelize($this->plugin);
        $pluginController = $pluginName.'AppController';

        if (is_subclass_of($this, 'AppController') || is_subclass_of($this, $pluginController)) {
            $appVars = get_class_vars('AppController');
            $uses = $appVars['uses'];
            $merge = ['components', 'helpers'];
            $plugin = null;

            if (!empty($this->plugin)) {
                $plugin = $pluginName.'.';
                if (!is_subclass_of($this, $pluginController)) {
                    $pluginController = null;
                }
            } else {
                $pluginController = null;
            }

            if ($uses == $this->uses && !empty($this->uses)) {
                if (!in_array($plugin.$this->modelClass, $this->uses)) {
                    array_unshift($this->uses, $plugin.$this->modelClass);
                } elseif ($this->uses[0] !== $plugin.$this->modelClass) {
                    $this->uses = array_flip($this->uses);
                    unset($this->uses[$plugin.$this->modelClass]);
                    $this->uses = array_flip($this->uses);
                    array_unshift($this->uses, $plugin.$this->modelClass);
                }
            } else {
                $merge[] = 'uses';
            }

            foreach ($merge as $var) {
                if (!empty($appVars[$var]) && is_array($this->{$var})) {
                    if ('uses' !== $var) {
                        $normal = Set::normalize($this->{$var});
                        $app = Set::normalize($appVars[$var]);
                        if ($app !== $normal) {
                            $this->{$var} = Set::merge($app, $normal);
                        }
                    } else {
                        $this->{$var} = array_merge($this->{$var}, array_diff($appVars[$var], $this->{$var}));
                    }
                }
            }
        }

        if ($pluginController && null != $pluginName) {
            $appVars = get_class_vars($pluginController);
            $uses = $appVars['uses'];
            $merge = ['components', 'helpers'];

            if (null !== $this->uses && false !== $this->uses) {
                $merge[] = 'uses';
            }

            foreach ($merge as $var) {
                if (isset($appVars[$var]) && !empty($appVars[$var]) && is_array($this->{$var})) {
                    if ('uses' !== $var) {
                        $normal = Set::normalize($this->{$var});
                        $app = Set::normalize($appVars[$var]);
                        if ($app !== $normal) {
                            $this->{$var} = Set::merge($app, $normal);
                        }
                    } else {
                        $this->{$var} = array_merge($this->{$var}, array_diff($appVars[$var], $this->{$var}));
                    }
                }
            }
        }
    }

    /**
     * Loads Model classes based on the uses property
     * see Controller::loadModel(); for more info.
     * Loads Components and prepares them for initialization.
     *
     * @return mixed true if models found and instance created, or cakeError if models not found
     *
     * @see Controller::loadModel()
     * @see http://book.cakephp.org/1.3/en/The-Manual/Developing-With-CakePHP/Controllers.html#Controller-Methods#constructClasses-986
     */
    public function constructClasses()
    {
        $this->__mergeVars();
        $this->Component->init($this);

        if (null !== $this->uses || ($this->uses !== [])) {
            if (empty($this->passedArgs) || !isset($this->passedArgs['0'])) {
                $id = false;
            } else {
                $id = $this->passedArgs['0'];
            }

            if (false === $this->uses) {
                $this->loadModel($this->modelClass, $id);
            } elseif ($this->uses) {
                $uses = is_array($this->uses) ? $this->uses : [$this->uses];
                $modelClassName = $uses[0];
                if (false !== strpos($uses[0], '.')) {
                    list($plugin, $modelClassName) = explode('.', $uses[0]);
                }
                $this->modelClass = $modelClassName;
                foreach ($uses as $modelClass) {
                    $this->loadModel($modelClass);
                }
            }
        }

        return true;
    }

    /**
     * Perform the startup process for this controller.
     * Fire the Component and Controller callbacks in the correct order.
     *
     * - Initializes components, which fires their `initialize` callback
     * - Calls the controller `beforeFilter`.
     * - triggers Component `startup` methods.
     */
    public function startupProcess()
    {
        $this->Component->initialize($this);
        $this->beforeFilter();
        $this->Component->triggerCallback('startup', $this);
    }

    /**
     * Perform the various shutdown processes for this controller.
     * Fire the Component and Controller callbacks in the correct order.
     *
     * - triggers the component `shutdown` callback.
     * - calls the Controller's `afterFilter` method.
     */
    public function shutdownProcess()
    {
        $this->Component->triggerCallback('shutdown', $this);
        $this->afterFilter();
    }

    /**
     * Queries & sets valid HTTP response codes & messages.
     *
     * @param mixed $code If $code is an integer, then the corresponding code/message is
     *                    returned if it exists, null if it does not exist. If $code is an array,
     *                    then the 'code' and 'message' keys of each nested array are added to the default
     *                    HTTP codes. Example:
     *
     *        httpCodes(404); // returns array(404 => 'Not Found')
     *
     *        httpCodes(array(
     *            701 => 'Unicorn Moved',
     *            800 => 'Unexpected Minotaur'
     *        )); // sets these new values, and returns true
     *
     * @return mixed associative array of the HTTP codes as keys, and the message
     *               strings as values, or null of the given $code does not exist
     */
    public function httpCodes($code = null)
    {
        if (empty($this->__httpCodes)) {
            $this->__httpCodes = [
                100 => 'Continue', 101 => 'Switching Protocols',
                200 => 'OK', 201 => 'Created', 202 => 'Accepted',
                203 => 'Non-Authoritative Information', 204 => 'No Content',
                205 => 'Reset Content', 206 => 'Partial Content',
                300 => 'Multiple Choices', 301 => 'Moved Permanently',
                302 => 'Found', 303 => 'See Other',
                304 => 'Not Modified', 305 => 'Use Proxy', 307 => 'Temporary Redirect',
                400 => 'Bad Request', 401 => 'Unauthorized', 402 => 'Payment Required',
                403 => 'Forbidden', 404 => 'Not Found', 405 => 'Method Not Allowed',
                406 => 'Not Acceptable', 407 => 'Proxy Authentication Required',
                408 => 'Request Time-out', 409 => 'Conflict', 410 => 'Gone',
                411 => 'Length Required', 412 => 'Precondition Failed',
                413 => 'Request Entity Too Large', 414 => 'Request-URI Too Large',
                415 => 'Unsupported Media Type', 416 => 'Requested range not satisfiable',
                417 => 'Expectation Failed', 500 => 'Internal Server Error',
                501 => 'Not Implemented', 502 => 'Bad Gateway',
                503 => 'Service Unavailable', 504 => 'Gateway Time-out',
            ];
        }

        if (empty($code)) {
            return $this->__httpCodes;
        }

        if (is_array($code)) {
            $this->__httpCodes = $code + $this->__httpCodes;

            return true;
        }

        if (!isset($this->__httpCodes[$code])) {
            return null;
        }

        return [$code => $this->__httpCodes[$code]];
    }

    /**
     * Loads and instantiates models required by this controller.
     * If Controller::$persistModel; is true, controller will cache model instances on first request,
     * additional request will used cached models.
     * If the model is non existent, it will throw a missing database table error, as Cake generates
     * dynamic models for the time being.
     *
     * @param string $modelClass Name of model class to load
     * @param mixed  $id         Initial ID the instanced model class should have
     *
     * @return mixed true when single model found and instance created, error returned if model not found
     */
    public function loadModel($modelClass = null, $id = null)
    {
        if (null === $modelClass) {
            $modelClass = $this->modelClass;
        }
        $cached = false;
        $object = null;
        $plugin = null;
        if (false === $this->uses) {
            if ($this->plugin) {
                $plugin = $this->plugin.'.';
            }
        }
        list($plugin, $modelClass) = pluginSplit($modelClass, true, $plugin);

        if (true === $this->persistModel) {
            $cached = $this->_persist($modelClass, null, $object);
        }

        if ((false === $cached)) {
            $this->modelNames[] = $modelClass;

            if (!PHP5) {
                $this->{$modelClass} = &ClassRegistry::init([
                    'class' => $plugin.$modelClass, 'alias' => $modelClass, 'id' => $id,
                ]);
            } else {
                $this->{$modelClass} = ClassRegistry::init([
                    'class' => $plugin.$modelClass, 'alias' => $modelClass, 'id' => $id,
                ]);
            }

            if (!$this->{$modelClass}) {
                return $this->cakeError('missingModel', [[
                    'className' => $modelClass, 'webroot' => '', 'base' => $this->base,
                ]]);
            }

            if (true === $this->persistModel) {
                $this->_persist($modelClass, true, $this->{$modelClass});
                $registry = &ClassRegistry::getInstance();
                $this->_persist($modelClass.'registry', true, $registry->__objects, 'registry');
            }
        } else {
            $this->_persist($modelClass.'registry', true, $object, 'registry');
            $this->_persist($modelClass, true, $object);
            $this->modelNames[] = $modelClass;
        }

        return true;
    }

    /**
     * Redirects to given $url, after turning off $this->autoRender.
     * Script execution is halted after the redirect.
     *
     * @param mixed $url    A string or array-based URL pointing to another location within the app,
     *                      or an absolute URL
     * @param int   $status Optional HTTP status code (eg: 404)
     * @param bool  $exit   If true, exit() will be called after the redirect
     *
     * @return mixed void if $exit = false. Terminates script if $exit = true
     *
     * @see http://book.cakephp.org/1.3/en/The-Manual/Developing-With-CakePHP/Controllers.html#redirect
     */
    public function redirect($url, $status = null, $exit = true)
    {
        $this->autoRender = false;

        if (is_array($status)) {
            extract($status, EXTR_OVERWRITE);
        }
        $response = $this->Component->beforeRedirect($this, $url, $status, $exit);

        if (false === $response) {
            return;
        }
        if (is_array($response)) {
            foreach ($response as $resp) {
                if (is_array($resp) && isset($resp['url'])) {
                    extract($resp, EXTR_OVERWRITE);
                } elseif (null !== $resp) {
                    $url = $resp;
                }
            }
        }

        if (function_exists('session_write_close')) {
            session_write_close();
        }

        if (!empty($status)) {
            $codes = $this->httpCodes();

            if (is_string($status)) {
                $codes = array_flip($codes);
            }

            if (isset($codes[$status])) {
                $code = $msg = $codes[$status];
                if (is_numeric($status)) {
                    $code = $status;
                }
                if (is_string($status)) {
                    $msg = $status;
                }
                $status = "HTTP/1.1 {$code} {$msg}";
            } else {
                $status = null;
            }
            $this->header($status);
        }

        if (null !== $url) {
            $this->header('Location: '.Router::url($url, true));
        }

        if (!empty($status) && ($status >= 300 && $status < 400)) {
            $this->header($status);
        }

        if ($exit) {
            $this->_stop();
        }
    }

    /**
     * Convenience and object wrapper method for header().  Useful when doing tests and
     * asserting that particular headers have been set.
     *
     * @param string $status the header message that is being set
     */
    public function header($status)
    {
        header($status);
    }

    /**
     * Saves a variable for use inside a view template.
     *
     * @param mixed $one a string or an array of data
     * @param mixed $two Value in case $one is a string (which then works as the key).
     *                   Unused if $one is an associative array, otherwise serves as the values to $one's keys.
     *
     * @see http://book.cakephp.org/1.3/en/The-Manual/Developing-With-CakePHP/Controllers.html#set
     */
    public function set($one, $two = null)
    {
        $data = [];

        if (is_array($one)) {
            if (is_array($two)) {
                $data = array_combine($one, $two);
            } else {
                $data = $one;
            }
        } else {
            $data = [$one => $two];
        }
        $this->viewVars = $data + $this->viewVars;
    }

    /**
     * Internally redirects one action to another. Does not perform another HTTP request unlike Controller::redirect().
     *
     * Examples:
     *
     * {{{
     * setAction('another_action');
     * setAction('action_with_parameters', $parameter1);
     * }}}
     *
     * @param string $action The new action to be 'redirected' to
     * @param mixed  any other parameters passed to this method will be passed as
     *    parameters to the new action
     *
     * @return mixed Returns the return value of the called action
     */
    public function setAction($action)
    {
        $this->action = $action;
        $args = func_get_args();
        unset($args[0]);

        return call_user_func_array([&$this, $action], $args);
    }

    /**
     * Controller callback to tie into Auth component.
     * Only called when AuthComponent::$authorize is set to 'controller'.
     *
     * @return bool true if authorized, false otherwise
     *
     * @see http://book.cakephp.org/1.3/en/The-Manual/Developing-With-CakePHP/Controllers.html#authorize
     */
    public function isAuthorized()
    {
        trigger_error(sprintf(
            __('%sController::isAuthorized() is not defined.', true), $this->name
        ), E_USER_WARNING);

        return false;
    }

    /**
     * Returns number of errors in a submitted FORM.
     *
     * @return int Number of errors
     */
    public function validate()
    {
        $args = func_get_args();
        $errors = call_user_func_array([&$this, 'validateErrors'], $args);

        if (false === $errors) {
            return 0;
        }

        return count($errors);
    }

    /**
     * Validates models passed by parameters. Example:.
     *
     * `$errors = $this->validateErrors($this->Article, $this->User);`
     *
     * @param mixed A list of models as a variable argument
     *
     * @return array Validation errors, or false if none
     */
    public function validateErrors()
    {
        $objects = func_get_args();

        if (empty($objects)) {
            return false;
        }

        $errors = [];
        foreach ($objects as $object) {
            if (isset($this->{$object->alias})) {
                $object = &$this->{$object->alias};
            }
            $object->set($object->data);
            $errors = array_merge($errors, (array) $object->invalidFields());
        }

        return $this->validationErrors = (!empty($errors) ? $errors : false);
    }

    /**
     * Instantiates the correct view class, hands it its data, and uses it to render the view output.
     *
     * @param string $action Action name to render
     * @param string $layout Layout to use
     * @param string $file   File to use for rendering
     *
     * @return string Full output string of view contents
     *
     * @see http://book.cakephp.org/1.3/en/The-Manual/Developing-With-CakePHP/Controllers.html#render
     */
    public function render($action = null, $layout = null, $file = null)
    {
        $this->beforeRender();
        $this->Component->triggerCallback('beforeRender', $this);

        $viewClass = $this->view;
        if ('View' != $this->view) {
            list($plugin, $viewClass) = pluginSplit($viewClass);
            $viewClass = $viewClass.'View';
            App::import('View', $this->view);
        }

        $this->params['models'] = $this->modelNames;

        if (Configure::read() > 2) {
            $this->set('cakeDebug', $this);
        }

        $View = new $viewClass($this);

        if (!empty($this->modelNames)) {
            $models = [];
            foreach ($this->modelNames as $currentModel) {
                if (isset($this->$currentModel) && is_a($this->$currentModel, 'Model')) {
                    $models[] = Inflector::underscore($currentModel);
                }
                $isValidModel = (
                    isset($this->$currentModel) && is_a($this->$currentModel, 'Model') &&
                    !empty($this->$currentModel->validationErrors)
                );
                if ($isValidModel) {
                    $View->validationErrors[Inflector::camelize($currentModel)] = &$this->$currentModel->validationErrors;
                }
            }
            $models = array_diff(ClassRegistry::keys(), $models);
            foreach ($models as $currentModel) {
                if (ClassRegistry::isKeySet($currentModel)) {
                    $currentObject = &ClassRegistry::getObject($currentModel);
                    if (is_a($currentObject, 'Model') && !empty($currentObject->validationErrors)) {
                        $View->validationErrors[Inflector::camelize($currentModel)] = &$currentObject->validationErrors;
                    }
                }
            }
        }

        $this->autoRender = false;
        $this->output .= $View->render($action, $layout, $file);

        return $this->output;
    }

    /**
     * Returns the referring URL for this request.
     *
     * @param string $default Default URL to use if HTTP_REFERER cannot be read from headers
     * @param bool   $local   If true, restrict referring URLs to local server
     *
     * @return string Referring URL
     *
     * @see http://book.cakephp.org/1.3/en/The-Manual/Developing-With-CakePHP/Controllers.html#referer
     */
    public function referer($default = null, $local = false)
    {
        $ref = env('HTTP_REFERER');
        if (!empty($ref) && defined('FULL_BASE_URL')) {
            $base = FULL_BASE_URL.$this->webroot;
            if (0 === strpos($ref, $base)) {
                $return = substr($ref, strlen($base));
                if ('/' != $return[0]) {
                    $return = '/'.$return;
                }

                return $return;
            } elseif (!$local) {
                return $ref;
            }
        }

        if (null != $default) {
            $url = Router::url($default, true);

            return $url;
        }

        return '/';
    }

    /**
     * Forces the user's browser not to cache the results of the current request.
     *
     * @see http://book.cakephp.org/1.3/en/The-Manual/Developing-With-CakePHP/Controllers.html#disableCache
     */
    public function disableCache()
    {
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header('Cache-Control: post-check=0, pre-check=0', false);
        header('Pragma: no-cache');
    }

    /**
     * Shows a message to the user for $pause seconds, then redirects to $url.
     * Uses flash.ctp as the default layout for the message.
     * Does not work if the current debug level is higher than 0.
     *
     * @param string $message Message to display to the user
     * @param mixed  $url     Relative string or array-based URL to redirect to after the time expires
     * @param int    $pause   Time to show the message
     * @param string $layout  Layout you want to use, defaults to 'flash'
     *
     * @see http://book.cakephp.org/1.3/en/The-Manual/Developing-With-CakePHP/Controllers.html#flash
     */
    public function flash($message, $url, $pause = 1, $layout = 'flash')
    {
        $this->autoRender = false;
        $this->set('url', Router::url($url));
        $this->set('message', $message);
        $this->set('pause', $pause);
        $this->set('page_title', $message);
        $this->render(false, $layout);
    }

    /**
     * Converts POST'ed form data to a model conditions array, suitable for use in a Model::find() call.
     *
     * @param array  $data      POST'ed data organized by model and field
     * @param mixed  $op        A string containing an SQL comparison operator, or an array matching operators
     *                          to fields
     * @param string $bool      SQL boolean operator: AND, OR, XOR, etc
     * @param bool   $exclusive If true, and $op is an array, fields not included in $op will not be
     *                          included in the returned conditions
     *
     * @return array An array of model conditions
     *
     * @see http://book.cakephp.org/1.3/en/The-Manual/Developing-With-CakePHP/Controllers.html#postConditions
     */
    public function postConditions($data = [], $op = null, $bool = 'AND', $exclusive = false)
    {
        if (!is_array($data) || empty($data)) {
            if (!empty($this->data)) {
                $data = $this->data;
            } else {
                return null;
            }
        }
        $cond = [];

        if (null === $op) {
            $op = '';
        }

        $arrayOp = is_array($op);
        foreach ($data as $model => $fields) {
            foreach ($fields as $field => $value) {
                $key = $model.'.'.$field;
                $fieldOp = $op;
                if ($arrayOp) {
                    if (array_key_exists($key, $op)) {
                        $fieldOp = $op[$key];
                    } elseif (array_key_exists($field, $op)) {
                        $fieldOp = $op[$field];
                    } else {
                        $fieldOp = false;
                    }
                }
                if ($exclusive && false === $fieldOp) {
                    continue;
                }
                $fieldOp = strtoupper(trim($fieldOp));
                if ('LIKE' === $fieldOp) {
                    $key = $key.' LIKE';
                    $value = '%'.$value.'%';
                } elseif ($fieldOp && '=' != $fieldOp) {
                    $key = $key.' '.$fieldOp;
                }
                $cond[$key] = $value;
            }
        }
        if (null != $bool && 'AND' != strtoupper($bool)) {
            $cond = [$bool => $cond];
        }

        return $cond;
    }

    /**
     * Handles automatic pagination of model records.
     *
     * @param mixed $object    Model to paginate (e.g: model instance, or 'Model', or 'Model.InnerModel')
     * @param mixed $scope     Conditions to use while paginating
     * @param array $whitelist List of allowed options for paging
     *
     * @return array Model query results
     *
     * @see http://book.cakephp.org/1.3/en/The-Manual/Developing-With-CakePHP/Controllers.html#Controller-Setup
     */
    public function paginate($object = null, $scope = [], $whitelist = [])
    {
        if (is_array($object)) {
            $whitelist = $scope;
            $scope = $object;
            $object = null;
        }
        $assoc = null;

        if (is_string($object)) {
            $assoc = null;
            if (false !== strpos($object, '.')) {
                list($object, $assoc) = pluginSplit($object);
            }

            if ($assoc && isset($this->{$object}->{$assoc})) {
                $object = &$this->{$object}->{$assoc};
            } elseif (
                $assoc && isset($this->{$this->modelClass}) &&
                isset($this->{$this->modelClass}->{$assoc}
            )) {
                $object = &$this->{$this->modelClass}->{$assoc};
            } elseif (isset($this->{$object})) {
                $object = &$this->{$object};
            } elseif (
                isset($this->{$this->modelClass}) && isset($this->{$this->modelClass}->{$object}
            )) {
                $object = &$this->{$this->modelClass}->{$object};
            }
        } elseif (empty($object) || null === $object) {
            if (isset($this->{$this->modelClass})) {
                $object = &$this->{$this->modelClass};
            } else {
                $className = null;
                $name = $this->uses[0];
                if (false !== strpos($this->uses[0], '.')) {
                    list($name, $className) = explode('.', $this->uses[0]);
                }
                if ($className) {
                    $object = &$this->{$className};
                } else {
                    $object = &$this->{$name};
                }
            }
        }

        if (!is_object($object)) {
            trigger_error(sprintf(
                __('Controller::paginate() - can\'t find model %1$s in controller %2$sController',
                    true
                ), $object, $this->name
            ), E_USER_WARNING);

            return [];
        }
        $options = array_merge($this->params, $this->params['url'], $this->passedArgs);

        if (isset($this->paginate[$object->alias])) {
            $defaults = $this->paginate[$object->alias];
        } else {
            $defaults = $this->paginate;
        }

        if (isset($options['show'])) {
            $options['limit'] = $options['show'];
        }

        if (isset($options['order']) && empty($options['sort'])) {
            $options['sort'] = $options['order'];
            unset($options['order']);
        }

        if (isset($options['sort'])) {
            $direction = null;
            if (isset($options['direction'])) {
                $direction = strtolower($options['direction']);
            }
            if ('asc' != $direction && 'desc' != $direction) {
                $direction = 'asc';
            }
            $options['order'] = [$options['sort'] => $direction];
        }

        if (!empty($options['order']) && is_array($options['order'])) {
            $alias = $object->alias;
            $key = $field = key($options['order']);

            if (false !== strpos($key, '.')) {
                list($alias, $field) = explode('.', $key);
            }
            $value = $options['order'][$key];
            unset($options['order'][$key]);
            $correctAlias = ($alias == $object->alias);

            if ($correctAlias && $object->hasField($field)) {
                $options['order'][$object->alias.'.'.$field] = $value;
            } elseif ($correctAlias && $object->hasField($key, true)) {
                $options['order'][$field] = $value;
            } elseif (isset($object->{$alias}) && $object->{$alias}->hasField($field)) {
                $options['order'][$alias.'.'.$field] = $value;
            }
        }
        $vars = ['fields', 'order', 'limit', 'page', 'recursive'];
        $keys = array_keys($options);
        $count = count($keys);

        for ($i = 0; $i < $count; ++$i) {
            if (!in_array($keys[$i], $vars, true)) {
                unset($options[$keys[$i]]);
            }
            if (empty($whitelist) && ('fields' === $keys[$i] || 'recursive' === $keys[$i])) {
                unset($options[$keys[$i]]);
            } elseif (!empty($whitelist) && !in_array($keys[$i], $whitelist)) {
                unset($options[$keys[$i]]);
            }
        }
        $conditions = $fields = $order = $limit = $page = $recursive = null;

        if (!isset($defaults['conditions'])) {
            $defaults['conditions'] = [];
        }

        $type = 'all';

        if (isset($defaults[0])) {
            $type = $defaults[0];
            unset($defaults[0]);
        }

        $options = array_merge(['page' => 1, 'limit' => 20], $defaults, $options);
        $options['limit'] = (int) $options['limit'];
        if (empty($options['limit']) || $options['limit'] < 1) {
            $options['limit'] = 1;
        }

        extract($options);

        if (is_array($scope) && !empty($scope)) {
            $conditions = array_merge($conditions, $scope);
        } elseif (is_string($scope)) {
            $conditions = [$conditions, $scope];
        }
        if (null === $recursive) {
            $recursive = $object->recursive;
        }

        $extra = array_diff_key($defaults, compact(
            'conditions', 'fields', 'order', 'limit', 'page', 'recursive'
        ));
        if ('all' !== $type) {
            $extra['type'] = $type;
        }

        if (method_exists($object, 'paginateCount')) {
            $count = $object->paginateCount($conditions, $recursive, $extra);
        } else {
            $parameters = compact('conditions');
            if ($recursive != $object->recursive) {
                $parameters['recursive'] = $recursive;
            }
            $count = $object->find('count', array_merge($parameters, $extra));
        }
        $pageCount = intval(ceil($count / $limit));

        if ('last' === $page || $page >= $pageCount) {
            $options['page'] = $page = $pageCount;
        } elseif (intval($page) < 1) {
            $options['page'] = $page = 1;
        }
        $page = $options['page'] = (int) $page;

        if (method_exists($object, 'paginate')) {
            $results = $object->paginate(
                $conditions, $fields, $order, $limit, $page, $recursive, $extra
            );
        } else {
            $parameters = compact('conditions', 'fields', 'order', 'limit', 'page');
            if ($recursive != $object->recursive) {
                $parameters['recursive'] = $recursive;
            }
            $results = $object->find($type, array_merge($parameters, $extra));
        }
        $paging = [
            'page' => $page,
            'current' => count($results),
            'count' => $count,
            'prevPage' => ($page > 1),
            'nextPage' => ($count > ($page * $limit)),
            'pageCount' => $pageCount,
            'defaults' => array_merge(['limit' => 20, 'step' => 1], $defaults),
            'options' => $options,
        ];
        $this->params['paging'][$object->alias] = $paging;

        if (!in_array('Paginator', $this->helpers) && !array_key_exists('Paginator', $this->helpers)) {
            $this->helpers[] = 'Paginator';
        }

        return $results;
    }

    /**
     * Called before the controller action.
     *
     * @see http://book.cakephp.org/1.3/en/The-Manual/Developing-With-CakePHP/Controllers.html#Callbacks
     */
    public function beforeFilter()
    {
    }

    /**
     * Called after the controller action is run, but before the view is rendered.
     *
     * @see http://book.cakephp.org/1.3/en/The-Manual/Developing-With-CakePHP/Controllers.html#Callbacks
     */
    public function beforeRender()
    {
    }

    /**
     * Called after the controller action is run and rendered.
     *
     * @see http://book.cakephp.org/1.3/en/The-Manual/Developing-With-CakePHP/Controllers.html#Callbacks
     */
    public function afterFilter()
    {
    }

    /**
     * This method should be overridden in child classes.
     *
     * @param string $method name of method called example index, edit, etc
     *
     * @return bool Success
     *
     * @see http://book.cakephp.org/1.3/en/The-Manual/Developing-With-CakePHP/Controllers.html#Callbacks
     */
    public function _beforeScaffold($method)
    {
        return true;
    }

    /**
     * This method should be overridden in child classes.
     *
     * @param string $method name of method called either edit or update
     *
     * @return bool Success
     *
     * @see http://book.cakephp.org/1.3/en/The-Manual/Developing-With-CakePHP/Controllers.html#Callbacks
     */
    public function _afterScaffoldSave($method)
    {
        return true;
    }

    /**
     * This method should be overridden in child classes.
     *
     * @param string $method name of method called either edit or update
     *
     * @return bool Success
     *
     * @see http://book.cakephp.org/1.3/en/The-Manual/Developing-With-CakePHP/Controllers.html#Callbacks
     */
    public function _afterScaffoldSaveError($method)
    {
        return true;
    }

    /**
     * This method should be overridden in child classes.
     * If not it will render a scaffold error.
     * Method MUST return true in child classes.
     *
     * @param string $method name of method called example index, edit, etc
     *
     * @return bool Success
     *
     * @see http://book.cakephp.org/1.3/en/The-Manual/Developing-With-CakePHP/Controllers.html#Callbacks
     */
    public function _scaffoldError($method)
    {
        return false;
    }
}
