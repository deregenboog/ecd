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
     * Overload implementation. No need for implementation in PHP5.
     */
    public function overload()
    {
    }

    /**
     * Magic method handler.
     *
     * @param string $method Method name
     * @param array  $params Parameters to send to method
     *
     * @return mixed Return value from method
     */
    public function __call($method, $params)
    {
        if (!method_exists($this, 'call__')) {
            trigger_error(sprintf(__('Magic method handler call__ not defined in %s', true), get_class($this)), E_USER_ERROR);
        }

        return $this->call__($method, $params);
    }
}

/**
 * Overloadable2 class selector.
 *
 * Load the interface class based on the version of PHP.
 */
class Overloadable2 extends Object
{
    /**
     * Overload implementation. No need for implementation in PHP5.
     */
    public function overload()
    {
    }

    /**
     * Magic method handler.
     *
     * @param string $method Method name
     * @param array  $params Parameters to send to method
     *
     * @return mixed Return value from method
     */
    public function __call($method, $params)
    {
        if (!method_exists($this, 'call__')) {
            trigger_error(sprintf(__('Magic method handler call__ not defined in %s', true), get_class($this)), E_USER_ERROR);
        }

        return $this->call__($method, $params);
    }

    /**
     * Getter.
     *
     * @param mixed $name  What to get
     * @param mixed $value Where to store returned value
     *
     * @return bool Success
     */
    public function __get($name)
    {
        return $this->get__($name);
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
        return $this->set__($name, $value);
    }
}
