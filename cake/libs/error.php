<?php
/**
 * Error handler.
 *
 * Provides Error Capturing for Framework errors.
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
 * @since         CakePHP(tm) v 0.10.5.1732
 *
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::import('Controller', 'App');

/**
 * Error Handling Controller.
 *
 * Controller used by ErrorHandler to render error views.
 */
class CakeErrorController extends AppController
{
    public $name = 'CakeError';

    /**
     * Uses Property.
     *
     * @var array
     */
    public $uses = [];

    /**
     * __construct.
     */
    public function __construct()
    {
        parent::__construct();
        $this->_set(Router::getPaths());
        $this->params = Router::getParams();
        $this->constructClasses();
        $this->Component->initialize($this);
        $this->_set(['cacheAction' => false, 'viewPath' => 'errors']);
    }
}

/**
 * Error Handler.
 *
 * Captures and handles all cakeError() calls.
 * Displays helpful framework errors when debug > 1.
 * When debug < 1 cakeError() will render 404 or 500 errors.
 */
class ErrorHandler extends Object
{
    /**
     * Controller instance.
     *
     * @var Controller
     */
    public $controller = null;

    /**
     * Class constructor.
     *
     * @param string $method   Method producing the error
     * @param array  $messages Error messages
     */
    public function __construct($method, $messages)
    {
        App::import('Core', 'Sanitize');
        static $__previousError = null;

        if ($__previousError != [$method, $messages]) {
            $__previousError = [$method, $messages];
            $this->controller = new CakeErrorController();
        } else {
            $this->controller = new Controller();
            $this->controller->viewPath = 'errors';
        }
        $options = ['escape' => false];
        $messages = Sanitize::clean($messages, $options);

        if (!isset($messages[0])) {
            $messages = [$messages];
        }

        if (method_exists($this->controller, 'apperror')) {
            return $this->controller->appError($method, $messages);
        }

        if (!in_array(strtolower($method), array_map('strtolower', get_class_methods($this)))) {
            $method = 'error';
        }

        if ('error' !== $method) {
            if (0 == Configure::read('debug')) {
                $parentClass = get_parent_class($this);
                if ('errorhandler' != strtolower($parentClass)) {
                    $method = 'error404';
                }
                $parentMethods = array_map('strtolower', get_class_methods($parentClass));
                if (in_array(strtolower($method), $parentMethods)) {
                    $method = 'error404';
                }
                if (isset($messages[0]['code']) && $messages[0]['code'] == 500) {
                    $method = 'error500';
                }
            }
        }
        $this->dispatchMethod($method, $messages);
        $this->_stop();
    }

    /**
     * Displays an error page (e.g. 404 Not found).
     *
     * @param array $params Parameters for controller
     */
    public function error($params)
    {
        extract($params, EXTR_OVERWRITE);
        $this->controller->set([
            'code' => $code,
            'name' => $name,
            'message' => $message,
            'title' => $code.' '.$name,
        ]);
        $this->_outputMessage('error404');
    }

    /**
     * Convenience method to display a 404 page.
     *
     * @param array $params Parameters for controller
     */
    public function error404($params)
    {
        extract($params, EXTR_OVERWRITE);

        if (!isset($url)) {
            $url = $this->controller->here;
        }
        $url = Router::normalize($url);
        $this->controller->header('HTTP/1.0 404 Not Found');
        $this->controller->set([
            'code' => '404',
            'name' => __('Not Found', true),
            'message' => h($url),
            'base' => $this->controller->base,
        ]);
        $this->_outputMessage('error404');
    }

    /**
     * Convenience method to display a 500 page.
     *
     * @param array $params Parameters for controller
     */
    public function error500($params)
    {
        extract($params, EXTR_OVERWRITE);

        if (!isset($url)) {
            $url = $this->controller->here;
        }
        $url = Router::normalize($url);
        $this->controller->header('HTTP/1.0 500 Internal Server Error');
        $this->controller->set([
            'code' => '500',
            'name' => __('An Internal Error Has Occurred', true),
            'message' => h($url),
            'base' => $this->controller->base,
        ]);
        $this->_outputMessage('error500');
    }

    /**
     * Renders the Missing Controller web page.
     *
     * @param array $params Parameters for controller
     */
    public function missingController($params)
    {
        extract($params, EXTR_OVERWRITE);

        $controllerName = str_replace('Controller', '', $className);
        $this->controller->set([
            'controller' => $className,
            'controllerName' => $controllerName,
            'title' => __('Missing Controller', true),
        ]);
        $this->_outputMessage('missingController');
    }

    /**
     * Renders the Missing Action web page.
     *
     * @param array $params Parameters for controller
     */
    public function missingAction($params)
    {
        extract($params, EXTR_OVERWRITE);

        $controllerName = str_replace('Controller', '', $className);
        $this->controller->set([
            'controller' => $className,
            'controllerName' => $controllerName,
            'action' => $action,
            'title' => __('Missing Method in Controller', true),
        ]);
        $this->_outputMessage('missingAction');
    }

    /**
     * Renders the Private Action web page.
     *
     * @param array $params Parameters for controller
     */
    public function privateAction($params)
    {
        extract($params, EXTR_OVERWRITE);

        $this->controller->set([
            'controller' => $className,
            'action' => $action,
            'title' => __('Trying to access private method in class', true),
        ]);
        $this->_outputMessage('privateAction');
    }

    /**
     * Renders the Missing Table web page.
     *
     * @param array $params Parameters for controller
     */
    public function missingTable($params)
    {
        extract($params, EXTR_OVERWRITE);

        $this->controller->header('HTTP/1.0 500 Internal Server Error');
        $this->controller->set([
            'code' => '500',
            'model' => $className,
            'table' => $table,
            'title' => __('Missing Database Table', true),
        ]);
        $this->_outputMessage('missingTable');
    }

    /**
     * Renders the Missing Database web page.
     *
     * @param array $params Parameters for controller
     */
    public function missingDatabase($params = [])
    {
        $this->controller->header('HTTP/1.0 500 Internal Server Error');
        $this->controller->set([
            'code' => '500',
            'title' => __('Scaffold Missing Database Connection', true),
        ]);
        $this->_outputMessage('missingScaffolddb');
    }

    /**
     * Renders the Missing View web page.
     *
     * @param array $params Parameters for controller
     */
    public function missingView($params)
    {
        extract($params, EXTR_OVERWRITE);

        $this->controller->set([
            'controller' => $className,
            'action' => $action,
            'file' => $file,
            'title' => __('Missing View', true),
        ]);
        $this->_outputMessage('missingView');
    }

    /**
     * Renders the Missing Layout web page.
     *
     * @param array $params Parameters for controller
     */
    public function missingLayout($params)
    {
        extract($params, EXTR_OVERWRITE);

        $this->controller->layout = 'default';
        $this->controller->set([
            'file' => $file,
            'title' => __('Missing Layout', true),
        ]);
        $this->_outputMessage('missingLayout');
    }

    /**
     * Renders the Database Connection web page.
     *
     * @param array $params Parameters for controller
     */
    public function missingConnection($params)
    {
        extract($params, EXTR_OVERWRITE);

        $this->controller->header('HTTP/1.0 500 Internal Server Error');
        $this->controller->set([
            'code' => '500',
            'model' => $className,
            'title' => __('Missing Database Connection', true),
        ]);
        $this->_outputMessage('missingConnection');
    }

    /**
     * Renders the Missing Helper file web page.
     *
     * @param array $params Parameters for controller
     */
    public function missingHelperFile($params)
    {
        extract($params, EXTR_OVERWRITE);

        $this->controller->set([
            'helperClass' => Inflector::camelize($helper).'Helper',
            'file' => $file,
            'title' => __('Missing Helper File', true),
        ]);
        $this->_outputMessage('missingHelperFile');
    }

    /**
     * Renders the Missing Helper class web page.
     *
     * @param array $params Parameters for controller
     */
    public function missingHelperClass($params)
    {
        extract($params, EXTR_OVERWRITE);

        $this->controller->set([
            'helperClass' => Inflector::camelize($helper).'Helper',
            'file' => $file,
            'title' => __('Missing Helper Class', true),
        ]);
        $this->_outputMessage('missingHelperClass');
    }

    /**
     * Renders the Missing Behavior file web page.
     *
     * @param array $params Parameters for controller
     */
    public function missingBehaviorFile($params)
    {
        extract($params, EXTR_OVERWRITE);

        $this->controller->set([
            'behaviorClass' => Inflector::camelize($behavior).'Behavior',
            'file' => $file,
            'title' => __('Missing Behavior File', true),
        ]);
        $this->_outputMessage('missingBehaviorFile');
    }

    /**
     * Renders the Missing Behavior class web page.
     *
     * @param array $params Parameters for controller
     */
    public function missingBehaviorClass($params)
    {
        extract($params, EXTR_OVERWRITE);

        $this->controller->set([
            'behaviorClass' => Inflector::camelize($behavior).'Behavior',
            'file' => $file,
            'title' => __('Missing Behavior Class', true),
        ]);
        $this->_outputMessage('missingBehaviorClass');
    }

    /**
     * Renders the Missing Component file web page.
     *
     * @param array $params Parameters for controller
     */
    public function missingComponentFile($params)
    {
        extract($params, EXTR_OVERWRITE);

        $this->controller->set([
            'controller' => $className,
            'component' => $component,
            'file' => $file,
            'title' => __('Missing Component File', true),
        ]);
        $this->_outputMessage('missingComponentFile');
    }

    /**
     * Renders the Missing Component class web page.
     *
     * @param array $params Parameters for controller
     */
    public function missingComponentClass($params)
    {
        extract($params, EXTR_OVERWRITE);

        $this->controller->set([
            'controller' => $className,
            'component' => $component,
            'file' => $file,
            'title' => __('Missing Component Class', true),
        ]);
        $this->_outputMessage('missingComponentClass');
    }

    /**
     * Renders the Missing Model class web page.
     *
     * @param unknown_type $params Parameters for controller
     */
    public function missingModel($params)
    {
        extract($params, EXTR_OVERWRITE);

        $this->controller->set([
            'model' => $className,
            'title' => __('Missing Model', true),
        ]);
        $this->_outputMessage('missingModel');
    }

    /**
     * Output message.
     */
    public function _outputMessage($template)
    {
        $this->controller->render($template);
        $this->controller->afterFilter();
        echo $this->controller->output;
    }
}
