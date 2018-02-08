<?php
/**
 * Session class for Cake.
 *
 * Cake abstracts the handling of sessions.
 * There are several convenient methods to access session information.
 * This class is the implementation of those methods.
 * They are mostly used by the Session Component.
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
 * @since         CakePHP(tm) v .0.10.0.1222
 *
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
if (!class_exists('Security')) {
    App::import('Core', 'Security');
}

/**
 * Session class for Cake.
 *
 * Cake abstracts the handling of sessions. There are several convenient methods to access session information.
 * This class is the implementation of those methods. They are mostly used by the Session Component.
 */
class CakeSession extends Object
{
    /**
     * True if the Session is still valid.
     *
     * @var bool
     */
    public $valid = false;

    /**
     * Error messages for this session.
     *
     * @var array
     */
    public $error = false;

    /**
     * User agent string.
     *
     * @var string
     */
    public $_userAgent = '';

    /**
     * Path to where the session is active.
     *
     * @var string
     */
    public $path = '/';

    /**
     * Error number of last occurred error.
     *
     * @var int
     */
    public $lastError = null;

    /**
     * 'Security.level' setting, "high", "medium", or "low".
     *
     * @var string
     */
    public $security = null;

    /**
     * Start time for this session.
     *
     * @var int
     */
    public $time = false;

    /**
     * Time when this session becomes invalid.
     *
     * @var int
     */
    public $sessionTime = false;

    /**
     * The number of seconds to set for session.cookie_lifetime.  0 means
     * at browser close.
     *
     * @var int
     */
    public $cookieLifeTime = false;

    /**
     * Keeps track of keys to watch for writes on.
     *
     * @var array
     */
    public $watchKeys = [];

    /**
     * Current Session id.
     *
     * @var string
     */
    public $id = null;

    /**
     * Hostname.
     *
     * @var string
     */
    public $host = null;

    /**
     * Session timeout multiplier factor.
     *
     * @var int
     */
    public $timeout = null;

    /**
     * Constructor.
     *
     * @param string $base  The base path for the Session
     * @param bool   $start Should session be started right now
     */
    public function __construct($base = null, $start = true)
    {
        App::import('Core', ['Set', 'Security']);
        $this->time = time();

        if (true === Configure::read('Session.checkAgent') || null === Configure::read('Session.checkAgent')) {
            if (null != env('HTTP_USER_AGENT')) {
                $this->_userAgent = md5(env('HTTP_USER_AGENT').Configure::read('Security.salt'));
            }
        }
        if ('database' === Configure::read('Session.save')) {
            $modelName = Configure::read('Session.model');
            $database = Configure::read('Session.database');
            $table = Configure::read('Session.table');

            if (empty($database)) {
                $database = 'default';
            }
            $settings = [
                'class' => 'Session',
                'alias' => 'Session',
                'table' => 'cake_sessions',
                'ds' => $database,
            ];
            if (!empty($modelName)) {
                $settings['class'] = $modelName;
            }
            if (!empty($table)) {
                $settings['table'] = $table;
            }
            ClassRegistry::init($settings);
        }
        if (true === $start) {
            if (!empty($base)) {
                $this->path = $base;
                if (false !== strpos($base, 'index.php')) {
                    $this->path = str_replace('index.php', '', $base);
                }
                if (false !== strpos($base, '?')) {
                    $this->path = str_replace('?', '', $base);
                }
            }
            $this->host = env('HTTP_HOST');

            if (false !== strpos($this->host, ':')) {
                $this->host = substr($this->host, 0, strpos($this->host, ':'));
            }
        }
        if (isset($_SESSION) || true === $start) {
            $this->sessionTime = $this->time + (Security::inactiveMins() * Configure::read('Session.timeout'));
            $this->security = Configure::read('Security.level');
        }
        parent::__construct();
    }

    /**
     * Starts the Session.
     *
     * @return bool True if session was started
     */
    public function start()
    {
        if ($this->started()) {
            return true;
        }
        if (function_exists('session_write_close')) {
            session_write_close();
        }
        $this->__initSession();
        $this->__startSession();

        return $this->started();
    }

    /**
     * Determine if Session has been started.
     *
     * @return bool true if session has been started
     */
    public function started()
    {
        if (isset($_SESSION) && session_id()) {
            return true;
        }

        return false;
    }

    /**
     * Returns true if given variable is set in session.
     *
     * @param string $name Variable name to check for
     *
     * @return bool True if variable is there
     */
    public function check($name)
    {
        if (empty($name)) {
            return false;
        }
        $result = Set::classicExtract($_SESSION, $name);

        return isset($result);
    }

    /**
     * Returns the Session id.
     *
     * @param id $name string
     *
     * @return string Session id
     */
    public function id($id = null)
    {
        if ($id) {
            $this->id = $id;
            session_id($this->id);
        }
        if ($this->started()) {
            return session_id();
        } else {
            return $this->id;
        }
    }

    /**
     * Removes a variable from session.
     *
     * @param string $name Session variable to remove
     *
     * @return bool Success
     */
    public function delete($name)
    {
        if ($this->check($name)) {
            if (in_array($name, $this->watchKeys)) {
                trigger_error(sprintf(__('Deleting session key {%s}', true), $name), E_USER_NOTICE);
            }
            $this->__overwrite($_SESSION, Set::remove($_SESSION, $name));

            return false == $this->check($name);
        }
        $this->__setError(2, sprintf(__("%s doesn't exist", true), $name));

        return false;
    }

    /**
     * Used to write new data to _SESSION, since PHP doesn't like us setting the _SESSION var itself.
     *
     * @param array $old Set of old variables => values
     * @param array $new New set of variable => value
     */
    public function __overwrite(&$old, $new)
    {
        if (!empty($old)) {
            foreach ($old as $key => $var) {
                if (!isset($new[$key])) {
                    unset($old[$key]);
                }
            }
        }
        foreach ($new as $key => $var) {
            $old[$key] = $var;
        }
    }

    /**
     * Return error description for given error number.
     *
     * @param int $errorNumber Error to set
     *
     * @return string Error as string
     */
    public function __error($errorNumber)
    {
        if (!is_array($this->error) || !array_key_exists($errorNumber, $this->error)) {
            return false;
        } else {
            return $this->error[$errorNumber];
        }
    }

    /**
     * Returns last occurred error as a string, if any.
     *
     * @return mixed error description as a string, or false
     */
    public function error()
    {
        if ($this->lastError) {
            return $this->__error($this->lastError);
        } else {
            return false;
        }
    }

    /**
     * Returns true if session is valid.
     *
     * @return bool Success
     */
    public function valid()
    {
        if ($this->read('Config')) {
            if ((false === Configure::read('Session.checkAgent') || $this->_userAgent == $this->read('Config.userAgent')) && $this->time <= $this->read('Config.time')) {
                if (false === $this->error) {
                    $this->valid = true;
                }
            } else {
                $this->valid = false;
                $this->__setError(1, 'Session Highjacking Attempted !!!');
            }
        }

        return $this->valid;
    }

    /**
     * Returns given session variable, or all of them, if no parameters given.
     *
     * @param mixed $name The name of the session variable (or a path as sent to Set.extract)
     *
     * @return mixed The value of the session variable
     */
    public function read($name = null)
    {
        if (is_null($name)) {
            return $this->__returnSessionVars();
        }
        if (empty($name)) {
            return false;
        }
        $result = Set::classicExtract($_SESSION, $name);

        if (!is_null($result)) {
            return $result;
        }
        $this->__setError(2, "$name doesn't exist");

        return null;
    }

    /**
     * Returns all session variables.
     *
     * @return mixed full $_SESSION array, or false on error
     */
    public function __returnSessionVars()
    {
        if (!empty($_SESSION)) {
            return $_SESSION;
        }
        $this->__setError(2, 'No Session vars set');

        return false;
    }

    /**
     * Tells Session to write a notification when a certain session path or subpath is written to.
     *
     * @param mixed $var The variable path to watch
     */
    public function watch($var)
    {
        if (empty($var)) {
            return false;
        }
        if (!in_array($var, $this->watchKeys, true)) {
            $this->watchKeys[] = $var;
        }
    }

    /**
     * Tells Session to stop watching a given key path.
     *
     * @param mixed $var The variable path to watch
     */
    public function ignore($var)
    {
        if (!in_array($var, $this->watchKeys)) {
            return;
        }
        foreach ($this->watchKeys as $i => $key) {
            if ($key == $var) {
                unset($this->watchKeys[$i]);
                $this->watchKeys = array_values($this->watchKeys);

                return;
            }
        }
    }

    /**
     * Writes value to given session variable name.
     *
     * @param mixed  $name  Name of variable
     * @param string $value Value to write
     *
     * @return bool True if the write was successful, false if the write failed
     */
    public function write($name, $value)
    {
        if (empty($name)) {
            return false;
        }
        if (in_array($name, $this->watchKeys)) {
            trigger_error(sprintf(__('Writing session key {%s}: %s', true), $name, Debugger::exportVar($value)), E_USER_NOTICE);
        }
        $this->__overwrite($_SESSION, Set::insert($_SESSION, $name, $value));

        return Set::classicExtract($_SESSION, $name) === $value;
    }

    /**
     * Helper method to destroy invalid sessions.
     */
    public function destroy()
    {
        if ($this->started()) {
            session_destroy();
        }
        $_SESSION = null;
        $this->__construct($this->path);
        $this->start();
        $this->renew();
        $this->_checkValid();
    }

    /**
     * Helper method to initialize a session, based on Cake core settings.
     */
    public function __initSession()
    {
        $iniSet = function_exists('ini_set');
        if ($iniSet && env('HTTPS')) {
            ini_set('session.cookie_secure', 1);
        }
        if ($iniSet && ('high' === $this->security || 'medium' === $this->security)) {
            ini_set('session.referer_check', $this->host);
        }

        if ('high' == $this->security) {
            $this->cookieLifeTime = 0;
        } else {
            $this->cookieLifeTime = Configure::read('Session.timeout') * (Security::inactiveMins() * 60);
        }

        switch (Configure::read('Session.save')) {
            case 'cake':
                if (empty($_SESSION)) {
                    if ($iniSet) {
                        ini_set('session.use_trans_sid', 0);
                        ini_set('url_rewriter.tags', '');
                        ini_set('session.serialize_handler', 'php');
                        ini_set('session.use_cookies', 1);
                        ini_set('session.name', Configure::read('Session.cookie'));
                        ini_set('session.cookie_lifetime', $this->cookieLifeTime);
                        ini_set('session.cookie_path', $this->path);
                        ini_set('session.auto_start', 0);
                        ini_set('session.save_path', TMP.'sessions');
                    }
                }
            break;
            case 'database':
                if (empty($_SESSION)) {
                    if (null === Configure::read('Session.model')) {
                        trigger_error(__("You must set the all Configure::write('Session.*') in core.php to use database storage"), E_USER_WARNING);
                        $this->_stop();
                    }
                    if ($iniSet) {
                        ini_set('session.use_trans_sid', 0);
                        ini_set('url_rewriter.tags', '');
                        ini_set('session.save_handler', 'user');
                        ini_set('session.serialize_handler', 'php');
                        ini_set('session.use_cookies', 1);
                        ini_set('session.name', Configure::read('Session.cookie'));
                        ini_set('session.cookie_lifetime', $this->cookieLifeTime);
                        ini_set('session.cookie_path', $this->path);
                        ini_set('session.auto_start', 0);
                    }
                }
                session_set_save_handler(
                    ['CakeSession', '__open'],
                    ['CakeSession', '__close'],
                    ['CakeSession', '__read'],
                    ['CakeSession', '__write'],
                    ['CakeSession', '__destroy'],
                    ['CakeSession', '__gc']
                );
            break;
            case 'php':
                if (empty($_SESSION)) {
                    if ($iniSet) {
                        ini_set('session.use_trans_sid', 0);
                        ini_set('session.name', Configure::read('Session.cookie'));
                        ini_set('session.cookie_lifetime', $this->cookieLifeTime);
                        ini_set('session.cookie_path', $this->path);
                    }
                }
            break;
            case 'cache':
                if (empty($_SESSION)) {
                    if (!class_exists('Cache')) {
                        require LIBS.'cache.php';
                    }
                    if ($iniSet) {
                        ini_set('session.use_trans_sid', 0);
                        ini_set('url_rewriter.tags', '');
                        ini_set('session.save_handler', 'user');
                        ini_set('session.use_cookies', 1);
                        ini_set('session.name', Configure::read('Session.cookie'));
                        ini_set('session.cookie_lifetime', $this->cookieLifeTime);
                        ini_set('session.cookie_path', $this->path);
                    }
                }
                session_set_save_handler(
                    ['CakeSession', '__open'],
                    ['CakeSession', '__close'],
                    ['Cache', 'read'],
                    ['Cache', 'write'],
                    ['Cache', 'delete'],
                    ['Cache', 'gc']
                );
            break;
            default:
                $config = CONFIGS.Configure::read('Session.save').'.php';

                if (is_file($config)) {
                    require $config;
                }
            break;
        }
    }

    /**
     * Helper method to start a session.
     */
    public function __startSession()
    {
        if (headers_sent()) {
            if (empty($_SESSION)) {
                $_SESSION = [];
            }

            return true;
        } elseif (!isset($_SESSION)) {
            session_cache_limiter('must-revalidate');
            session_start();
            header('P3P: CP="NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM"');

            return true;
        } else {
            session_start();

            return true;
        }
    }

    /**
     * Helper method to create a new session.
     */
    public function _checkValid()
    {
        if ($this->read('Config')) {
            if ((false === Configure::read('Session.checkAgent') || $this->_userAgent == $this->read('Config.userAgent')) && $this->time <= $this->read('Config.time')) {
                $time = $this->read('Config.time');
                $this->write('Config.time', $this->sessionTime);
                if ('high' === Configure::read('Security.level')) {
                    $check = $this->read('Config.timeout');
                    $check -= 1;
                    $this->write('Config.timeout', $check);

                    if (time() > ($time - (Security::inactiveMins() * Configure::read('Session.timeout')) + 2) || $check < 1) {
                        $this->renew();
                        $this->write('Config.timeout', 10);
                    }
                }
                $this->valid = true;
            } else {
                $this->destroy();
                $this->valid = false;
                $this->__setError(1, 'Session Highjacking Attempted !!!');
            }
        } else {
            $this->write('Config.userAgent', $this->_userAgent);
            $this->write('Config.time', $this->sessionTime);
            $this->write('Config.timeout', 10);
            $this->valid = true;
            $this->__setError(1, 'Session is valid');
        }
    }

    /**
     * Helper method to restart a session.
     */
    public function __regenerateId()
    {
        $oldSessionId = session_id();
        if ($oldSessionId) {
            if ('' != session_id() || isset($_COOKIE[session_name()])) {
                setcookie(Configure::read('Session.cookie'), '', time() - 42000, $this->path);
            }
            session_regenerate_id(true);
            if (PHP_VERSION < 5.1) {
                $sessionPath = session_save_path();
                if (empty($sessionPath)) {
                    $sessionPath = '/tmp';
                }
                $newSessid = session_id();

                if (function_exists('session_write_close')) {
                    session_write_close();
                }
                $this->__initSession();
                session_id($oldSessionId);
                session_start();
                session_destroy();
                $file = $sessionPath.DS.'sess_'.$oldSessionId;
                @unlink($file);
                $this->__initSession();
                session_id($newSessid);
                session_start();
            }
        }
    }

    /**
     * Restarts this session.
     */
    public function renew()
    {
        $this->__regenerateId();
    }

    /**
     * Helper method to set an internal error message.
     *
     * @param int    $errorNumber  Number of the error
     * @param string $errorMessage Description of the error
     */
    public function __setError($errorNumber, $errorMessage)
    {
        if (false === $this->error) {
            $this->error = [];
        }
        $this->error[$errorNumber] = $errorMessage;
        $this->lastError = $errorNumber;
    }

    /**
     * Method called on open of a database session.
     *
     * @return bool Success
     */
    public function __open()
    {
        return true;
    }

    /**
     * Method called on close of a database session.
     *
     * @return bool Success
     */
    public function __close()
    {
        $probability = mt_rand(1, 150);
        if ($probability <= 3) {
            switch (Configure::read('Session.save')) {
                case 'cache':
                    Cache::gc();
                break;
                default:
                    self::__gc();
                break;
            }
        }

        return true;
    }

    /**
     * Method used to read from a database session.
     *
     * @param mixed $id The key of the value to read
     *
     * @return mixed The value of the key or false if it does not exist
     */
    public function __read($id)
    {
        $model = &ClassRegistry::getObject('Session');

        $row = $model->find('first', [
            'conditions' => [$model->primaryKey => $id],
        ]);

        if (empty($row[$model->alias]['data'])) {
            return false;
        }

        return $row[$model->alias]['data'];
    }

    /**
     * Helper function called on write for database sessions.
     *
     * @param int   $id   ID that uniquely identifies session in database
     * @param mixed $data the value of the data to be saved
     *
     * @return bool true for successful write, false otherwise
     */
    public function __write($id, $data)
    {
        if (!$id) {
            return false;
        }
        $expires = time() + Configure::read('Session.timeout') * Security::inactiveMins();
        $model = &ClassRegistry::getObject('Session');
        $return = $model->save([$model->primaryKey => $id] + compact('data', 'expires'));

        return $return;
    }

    /**
     * Method called on the destruction of a database session.
     *
     * @param int $id ID that uniquely identifies session in database
     *
     * @return bool true for successful delete, false otherwise
     */
    public function __destroy($id)
    {
        $model = &ClassRegistry::getObject('Session');
        $return = $model->delete($id);

        return $return;
    }

    /**
     * Helper function called on gc for database sessions.
     *
     * @param int $expires Timestamp (defaults to current time)
     *
     * @return bool Success
     */
    public function __gc($expires = null)
    {
        $model = &ClassRegistry::getObject('Session');

        if (!$expires) {
            $expires = time();
        }

        $return = $model->deleteAll([$model->alias.'.expires <' => $expires], false, false);

        return $return;
    }
}
