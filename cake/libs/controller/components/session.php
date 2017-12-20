<?php
/**
 * SessionComponent.  Provides access to Sessions from the Controller layer.
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
 * @since         CakePHP(tm) v 0.10.0.1232
 *
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
if (!class_exists('cakesession')) {
    require LIBS.'cake_session.php';
}

/**
 * Session Component.
 *
 * Session handling from the controller.
 *
 * @see http://book.cakephp.org/1.3/en/The-Manual/Core-Components/Sessions.html#Sessions
 */
class SessionComponent extends CakeSession
{
    /**
     * Used to determine if methods implementation is used, or bypassed.
     *
     * @var bool
     */
    public $__active = true;

    /**
     * Used to determine if request are from an Ajax request.
     *
     * @var bool
     */
    public $__bare = 0;

    /**
     * Class constructor.
     *
     * @param string $base The base path for the Session
     */
    public function __construct($base = null)
    {
        if (true === Configure::read('Session.start')) {
            parent::__construct($base);
        } else {
            $this->__active = false;
        }
    }

    /**
     * Startup method.
     *
     * @param object $controller Instantiating controller
     */
    public function startup(&$controller)
    {
        if (false === $this->started() && true === $this->__active) {
            $this->__start();
        }
    }

    /**
     * Starts Session on if 'Session.start' is set to false in core.php.
     *
     * @param string $base The base path for the Session
     */
    public function activate($base = null)
    {
        if (true === $this->__active) {
            return;
        }
        parent::__construct($base);
        $this->__active = true;
    }

    /**
     * Used to write a value to a session key.
     *
     * In your controller: $this->Session->write('Controller.sessKey', 'session value');
     *
     * @param string $name  The name of the key your are setting in the session.
     *                      This should be in a Controller.key format for better organizing
     * @param string $value the value you want to store in a session
     *
     * @return bool Success
     *
     * @see http://book.cakephp.org/1.3/en/The-Manual/Core-Components/Sessions.html#write
     */
    public function write($name, $value = null)
    {
        if (true === $this->__active) {
            $this->__start();
            if (is_array($name)) {
                foreach ($name as $key => $value) {
                    if (false === parent::write($key, $value)) {
                        return false;
                    }
                }

                return true;
            }
            if (false === parent::write($name, $value)) {
                return false;
            }

            return true;
        }

        return false;
    }

    /**
     * Used to read a session values for a key or return values for all keys.
     *
     * In your controller: $this->Session->read('Controller.sessKey');
     * Calling the method without a param will return all session vars
     *
     * @param string $name the name of the session key you want to read
     *
     * @return mixed value from the session vars
     *
     * @see http://book.cakephp.org/1.3/en/The-Manual/Core-Components/Sessions.html#read
     */
    public function read($name = null)
    {
        if (true === $this->__active) {
            $this->__start();

            return parent::read($name);
        }

        return false;
    }

    /**
     * Wrapper for SessionComponent::del();.
     *
     * In your controller: $this->Session->delete('Controller.sessKey');
     *
     * @param string $name the name of the session key you want to delete
     *
     * @return bool true is session variable is set and can be deleted, false is variable was not set
     *
     * @see http://book.cakephp.org/1.3/en/The-Manual/Core-Components/Sessions.html#delete
     */
    public function delete($name)
    {
        if (true === $this->__active) {
            $this->__start();

            return parent::delete($name);
        }

        return false;
    }

    /**
     * Used to check if a session variable is set.
     *
     * In your controller: $this->Session->check('Controller.sessKey');
     *
     * @param string $name the name of the session key you want to check
     *
     * @return bool true is session variable is set, false if not
     *
     * @see http://book.cakephp.org/1.3/en/The-Manual/Core-Components/Sessions.html#check
     */
    public function check($name)
    {
        if (true === $this->__active) {
            $this->__start();

            return parent::check($name);
        }

        return false;
    }

    /**
     * Used to determine the last error in a session.
     *
     * In your controller: $this->Session->error();
     *
     * @return string Last session error
     *
     * @see http://book.cakephp.org/1.3/en/The-Manual/Core-Components/Sessions.html#error
     */
    public function error()
    {
        if (true === $this->__active) {
            $this->__start();

            return parent::error();
        }

        return false;
    }

    /**
     * Used to set a session variable that can be used to output messages in the view.
     *
     * In your controller: $this->Session->setFlash('This has been saved');
     *
     * Additional params below can be passed to customize the output, or the Message.[key]
     *
     * @param string $message Message to be flashed
     * @param string $element element to wrap flash message in
     * @param array  $params  Parameters to be sent to layout as view variables
     * @param string $key     Message key, default is 'flash'
     *
     * @see http://book.cakephp.org/1.3/en/The-Manual/Core-Components/Sessions.html#setFlash
     */
    public function setFlash($message, $element = 'default', $params = [], $key = 'flash')
    {
        if (true === $this->__active) {
            $this->__start();
            $this->write('Message.'.$key, compact('message', 'element', 'params'));
        }
    }

    /**
     * Used to renew a session id.
     *
     * In your controller: $this->Session->renew();
     */
    public function renew()
    {
        if (true === $this->__active) {
            $this->__start();
            parent::renew();
        }
    }

    /**
     * Used to check for a valid session.
     *
     * In your controller: $this->Session->valid();
     *
     * @return bool true is session is valid, false is session is invalid
     */
    public function valid()
    {
        if (true === $this->__active) {
            $this->__start();

            return parent::valid();
        }

        return false;
    }

    /**
     * Used to destroy sessions.
     *
     * In your controller: $this->Session->destroy();
     *
     * @see http://book.cakephp.org/1.3/en/The-Manual/Core-Components/Sessions.html#destroy
     */
    public function destroy()
    {
        if (true === $this->__active) {
            $this->__start();
            parent::destroy();
        }
    }

    /**
     * Returns Session id.
     *
     * If $id is passed in a beforeFilter, the Session will be started
     * with the specified id
     *
     * @param $id string
     *
     * @return string
     */
    public function id($id = null)
    {
        return parent::id($id);
    }

    /**
     * Starts Session if SessionComponent is used in Controller::beforeFilter(),
     * or is called from.
     *
     * @return bool
     */
    public function __start()
    {
        if (false === $this->started()) {
            if (!$this->id() && parent::start()) {
                parent::_checkValid();
            } else {
                parent::start();
            }
        }

        return $this->started();
    }
}
