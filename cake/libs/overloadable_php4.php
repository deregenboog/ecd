<?php
/**
 * Overload abstraction interface.  Merges differences between PHP4 and 5.
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
 * @since         CakePHP(tm) v 1.2
 *
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * Overloadable class selector.
 *
 * Load the interface class based on the version of PHP.
 */
class Overloadable extends Object
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->overload();
        parent::__construct();
    }

    /**
     * Overload implementation.
     */
    public function overload()
    {
        if (function_exists('overload')) {
            if (func_num_args() > 0) {
                foreach (func_get_args() as $class) {
                    if (is_object($class)) {
                        overload(get_class($class));
                    } elseif (is_string($class)) {
                        overload($class);
                    }
                }
            } else {
                overload(get_class($this));
            }
        }
    }

    /**
     * Magic method handler.
     *
     * @param string $method Method name
     * @param array  $params Parameters to send to method
     * @param mixed  $return Where to store return value from method
     *
     * @return bool Success
     */
    public function __call($method, $params, &$return)
    {
        if (!method_exists($this, 'call__')) {
            trigger_error(sprintf(__('Magic method handler call__ not defined in %s', true), get_class($this)), E_USER_ERROR);
        }
        $return = $this->call__($method, $params);

        return true;
    }
}
Overloadable::overload('Overloadable');

/**
 * Overloadable2 class selector.
 *
 * Load the interface class based on the version of PHP.
 */
class Overloadable2 extends Object
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->overload();
        parent::__construct();
    }

    /**
     * Overload implementation.
     */
    public function overload()
    {
        if (function_exists('overload')) {
            if (func_num_args() > 0) {
                foreach (func_get_args() as $class) {
                    if (is_object($class)) {
                        overload(get_class($class));
                    } elseif (is_string($class)) {
                        overload($class);
                    }
                }
            } else {
                overload(get_class($this));
            }
        }
    }

    /**
     * Magic method handler.
     *
     * @param string $method Method name
     * @param array  $params Parameters to send to method
     * @param mixed  $return Where to store return value from method
     *
     * @return bool Success
     */
    public function __call($method, $params, &$return)
    {
        if (!method_exists($this, 'call__')) {
            trigger_error(sprintf(__('Magic method handler call__ not defined in %s', true), get_class($this)), E_USER_ERROR);
        }
        $return = $this->call__($method, $params);

        return true;
    }

    /**
     * Getter.
     *
     * @param mixed $name  What to get
     * @param mixed $value Where to store returned value
     *
     * @return bool Success
     */
    public function __get($name, &$value)
    {
        $value = $this->get__($name);

        return true;
    }

    /**
     * Setter.
     *
     * @param mixed $name  What to set
     * @param mixed $value Value to set
     *
     * @return bool Success
     */
    public function __set($name, $value)
    {
        $this->set__($name, $value);

        return true;
    }
}
Overloadable::overload('Overloadable2');
