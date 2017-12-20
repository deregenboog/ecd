<?php
/**
 * Session Helper provides access to the Session in the Views.
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
 * @since         CakePHP(tm) v 1.1.7.3328
 *
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
if (!class_exists('cakesession')) {
    require LIBS.'cake_session.php';
}
/**
 * Session Helper.
 *
 * Session reading from the view.
 *
 * @see http://book.cakephp.org/1.3/en/The-Manual/Core-Helpers/Session.html#Session
 */
class SessionHelper extends CakeSession
{
    /**
     * List of helpers used by this helper.
     *
     * @var array
     */
    public $helpers = [];

    /**
     * Used to determine if methods implementation is used, or bypassed.
     *
     * @var bool
     */
    public $__active = true;

    /**
     * Class constructor.
     *
     * @param string $base
     */
    public function __construct($base = null)
    {
        if (true === Configure::read('Session.start')) {
            parent::__construct($base, false);
            $this->start();
            $this->__active = true;
        } else {
            $this->__active = false;
        }
    }

    /**
     * Turn sessions on if 'Session.start' is set to false in core.php.
     *
     * @param string $base
     */
    public function activate($base = null)
    {
        $this->__active = true;
    }

    /**
     * Used to read a session values set in a controller for a key or return values for all keys.
     *
     * In your view: `$session->read('Controller.sessKey');`
     * Calling the method without a param will return all session vars
     *
     * @param string $name the name of the session key you want to read
     *
     * @return values from the session vars
     *
     * @see http://book.cakephp.org/1.3/en/The-Manual/Core-Helpers/Session.html#Methods
     */
    public function read($name = null)
    {
        if (true === $this->__active && $this->__start()) {
            return parent::read($name);
        }

        return false;
    }

    /**
     * Used to check is a session key has been set.
     *
     * In your view: `$session->check('Controller.sessKey');`
     *
     * @param string $name
     *
     * @return bool
     *
     * @see http://book.cakephp.org/1.3/en/The-Manual/Core-Helpers/Session.html#Methods
     */
    public function check($name)
    {
        if (true === $this->__active && $this->__start()) {
            return parent::check($name);
        }

        return false;
    }

    /**
     * Returns last error encountered in a session.
     *
     * In your view: `$session->error();`
     *
     * @return string last error
     *
     * @see http://book.cakephp.org/1.3/en/The-Manual/Core-Helpers/Session.html#Methods
     */
    public function error()
    {
        if (true === $this->__active && $this->__start()) {
            return parent::error();
        }

        return false;
    }

    /**
     * Used to render the message set in Controller::Session::setFlash().
     *
     * In your view: $session->flash('somekey');
     * Will default to flash if no param is passed
     *
     * @param string $key The [Message.]key you are rendering in the view.
     *
     * @return bool|string will return the value if $key is set, or false if not set
     *
     * @see http://book.cakephp.org/1.3/en/The-Manual/Core-Helpers/Session.html#Methods
     * @see http://book.cakephp.org/1.3/en/The-Manual/Core-Helpers/Session.html#flash
     */
    public function flash($key = 'flash')
    {
        $out = false;

        if (true === $this->__active && $this->__start()) {
            if (parent::check('Message.'.$key)) {
                $flash = parent::read('Message.'.$key);

                if ('default' == $flash['element']) {
                    if (!empty($flash['params']['class'])) {
                        $class = $flash['params']['class'];
                    } else {
                        $class = 'message';
                    }
                    $out = '<div id="'.$key.'Message" class="'.$class.'">'.$flash['message'].'</div>';
                } elseif ('' == $flash['element'] || null == $flash['element']) {
                    $out = $flash['message'];
                } else {
                    $view = &ClassRegistry::getObject('view');
                    $tmpVars = $flash['params'];
                    $tmpVars['message'] = $flash['message'];
                    $out = $view->element($flash['element'], $tmpVars);
                }
                parent::delete('Message.'.$key);
            }
        }

        return $out;
    }

    /**
     * Used to check is a session is valid in a view.
     *
     * @return bool
     */
    public function valid()
    {
        if (true === $this->__active && $this->__start()) {
            return parent::valid();
        }
    }

    /**
     * Override CakeSession::write().
     * This method should not be used in a view.
     *
     * @return bool
     */
    public function write($name, $value)
    {
        trigger_error(__('You can not write to a Session from the view', true), E_USER_WARNING);
    }

    /**
     * Determine if Session has been started
     * and attempt to start it if not.
     *
     * @return bool true if Session is already started, false if
     *              Session could not be started
     */
    public function __start()
    {
        if (!$this->started()) {
            return $this->start();
        }

        return true;
    }
}
