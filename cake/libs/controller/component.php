<?php
/**
 * PHP versions 4 and 5.
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
 * @since         CakePHP(tm) v TBD
 *
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * Handler for Controller::$components.
 *
 * @see          http://book.cakephp.org/1.3/en/The-Manual/Developing-with-CakePHP/Components.html
 */
class Component extends Object
{
    /**
     * Contains various controller variable information (plugin, name, base).
     *
     * @var object
     */
    public $__controllerVars = ['plugin' => null, 'name' => null, 'base' => null];

    /**
     * List of loaded components.
     *
     * @var object
     */
    public $_loaded = [];

    /**
     * List of components attached directly to the controller, which callbacks
     * should be executed on.
     *
     * @var object
     */
    public $_primary = [];

    /**
     * Settings for loaded components.
     *
     * @var array
     */
    public $__settings = [];

    /**
     * Used to initialize the components for current controller.
     *
     * @param object $controller Controller with components to load
     */
    public function init(&$controller)
    {
        if (!is_array($controller->components)) {
            return;
        }
        $this->__controllerVars = [
            'plugin' => $controller->plugin, 'name' => $controller->name,
            'base' => $controller->base,
        ];

        $this->_loadComponents($controller);
    }

    /**
     * Called before the Controller::beforeFilter().
     *
     * @param object $controller Controller with components to initialize
     *
     * @see http://book.cakephp.org/1.3/en/The-Manual/Developing-with-CakePHP/Components.html#mvc-class-access-within-components
     */
    public function initialize(&$controller)
    {
        foreach (array_keys($this->_loaded) as $name) {
            $component = &$this->_loaded[$name];

            if (method_exists($component, 'initialize') && true === $component->enabled) {
                $settings = [];
                if (isset($this->__settings[$name])) {
                    $settings = $this->__settings[$name];
                }
                $component->initialize($controller, $settings);
            }
        }
    }

    /**
     * Called after the Controller::beforeFilter() and before the controller action.
     *
     * @param object $controller Controller with components to startup
     *
     * @see http://book.cakephp.org/1.3/en/The-Manual/Developing-with-CakePHP/Components.html#mvc-class-access-within-components
     * @deprecated See Component::triggerCallback()
     */
    public function startup(&$controller)
    {
        $this->triggerCallback('startup', $controller);
    }

    /**
     * Called after the Controller::beforeRender(), after the view class is loaded, and before the
     * Controller::render().
     *
     * @param object $controller Controller with components to beforeRender
     *
     * @deprecated See Component::triggerCallback()
     */
    public function beforeRender(&$controller)
    {
        $this->triggerCallback('beforeRender', $controller);
    }

    /**
     * Called before Controller::redirect().
     *
     * @param object $controller Controller with components to beforeRedirect
     */
    public function beforeRedirect(&$controller, $url, $status = null, $exit = true)
    {
        $response = [];

        foreach ($this->_primary as $name) {
            $component = &$this->_loaded[$name];

            if (true === $component->enabled && method_exists($component, 'beforeRedirect')) {
                $resp = $component->beforeRedirect($controller, $url, $status, $exit);
                if (false === $resp) {
                    return false;
                }
                $response[] = $resp;
            }
        }

        return $response;
    }

    /**
     * Called after Controller::render() and before the output is printed to the browser.
     *
     * @param object $controller Controller with components to shutdown
     *
     * @deprecated See Component::triggerCallback()
     */
    public function shutdown(&$controller)
    {
        $this->triggerCallback('shutdown', $controller);
    }

    /**
     * Trigger a callback on all primary components.  Will fire $callback on all components
     * that have such a method.  You can implement and fire custom callbacks in addition to the
     * standard ones.
     *
     * example use, from inside a controller:
     *
     * `$this->Component->triggerCallback('beforeFilter', $this);`
     *
     * will trigger the beforeFilter callback on all components that have implemented one. You
     * can trigger any method in this fashion.
     *
     * @param Controller $controller Controller instance
     * @param string     $callback   callback to trigger
     */
    public function triggerCallback($callback, &$controller)
    {
        foreach ($this->_primary as $name) {
            $component = &$this->_loaded[$name];
            if (method_exists($component, $callback) && true === $component->enabled) {
                $component->{$callback}($controller);
            }
        }
    }

    /**
     * Loads components used by this component.
     *
     * @param object $object Object with a Components array
     * @param object $parent the parent of the current object
     */
    public function _loadComponents(&$object, $parent = null)
    {
        $base = $this->__controllerVars['base'];
        $normal = Set::normalize($object->components);
        foreach ((array) $normal as $component => $config) {
            $plugin = isset($this->__controllerVars['plugin']) ? $this->__controllerVars['plugin'].'.' : null;
            list($plugin, $component) = pluginSplit($component, true, $plugin);
            $componentCn = $component.'Component';

            if (!class_exists($componentCn)) {
                if (is_null($plugin) || !App::import('Component', $plugin.$component)) {
                    if (!App::import('Component', $component)) {
                        $this->cakeError('missingComponentFile', [[
                            'className' => $this->__controllerVars['name'],
                            'component' => $component,
                            'file' => Inflector::underscore($component).'.php',
                            'base' => $base,
                            'code' => 500,
                        ]]);

                        return false;
                    }
                }

                if (!class_exists($componentCn)) {
                    $this->cakeError('missingComponentClass', [[
                        'className' => $this->__controllerVars['name'],
                        'component' => $component,
                        'file' => Inflector::underscore($component).'.php',
                        'base' => $base,
                        'code' => 500,
                    ]]);

                    return false;
                }
            }

            if (null === $parent) {
                $this->_primary[] = $component;
            }

            if (isset($this->_loaded[$component])) {
                $object->{$component} = &$this->_loaded[$component];

                if (!empty($config) && isset($this->__settings[$component])) {
                    $this->__settings[$component] = array_merge($this->__settings[$component], $config);
                } elseif (!empty($config)) {
                    $this->__settings[$component] = $config;
                }
            } else {
                if ('SessionComponent' === $componentCn) {
                    $object->{$component} = new $componentCn($base);
                } else {
                    if (PHP5) {
                        $object->{$component} = new $componentCn();
                    } else {
                        $object->{$component} = new $componentCn();
                    }
                }
                $object->{$component}->enabled = true;
                $this->_loaded[$component] = &$object->{$component};
                if (!empty($config)) {
                    $this->__settings[$component] = $config;
                }

                if (isset($object->{$component}->components) && is_array($object->{$component}->components) && (!isset($object->{$component}->{$parent}))) {
                    $this->_loadComponents($object->{$component}, $component);
                }
            }
        }
    }
}
