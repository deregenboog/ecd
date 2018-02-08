<?php
/**
 * Xcache storage engine for cache.
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
 * @since         CakePHP(tm) v 1.2.0.4947
 *
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * Xcache storage engine for cache.
 *
 * @see          http://trac.lighttpd.net/xcache/ Xcache
 */
class XcacheEngine extends CacheEngine
{
    /**
     * Settings.
     *
     *  - PHP_AUTH_USER = xcache.admin.user, default cake
     *  - PHP_AUTH_PW = xcache.admin.password, default cake
     *
     * @var array
     */
    public $settings = [];

    /**
     * Initialize the Cache Engine.
     *
     * Called automatically by the cache frontend
     * To reinitialize the settings call Cache::engine('EngineName', [optional] settings = array());
     *
     * @param array $setting array of setting for the engine
     *
     * @return bool True if the engine has been successfully initialized, false if not
     */
    public function init($settings)
    {
        parent::init(array_merge([
            'engine' => 'Xcache', 'prefix' => Inflector::slug(APP_DIR).'_', 'PHP_AUTH_USER' => 'user', 'PHP_AUTH_PW' => 'password',
            ], $settings)
        );

        return function_exists('xcache_info');
    }

    /**
     * Write data for key into cache.
     *
     * @param string $key      Identifier for the data
     * @param mixed  $value    Data to be cached
     * @param int    $duration How long to cache the data, in seconds
     *
     * @return bool True if the data was succesfully cached, false on failure
     */
    public function write($key, &$value, $duration)
    {
        $expires = time() + $duration;
        xcache_set($key.'_expires', $expires, $duration);

        return xcache_set($key, $value, $duration);
    }

    /**
     * Read a key from the cache.
     *
     * @param string $key Identifier for the data
     *
     * @return mixed The cached data, or false if the data doesn't exist, has expired, or if there was an error fetching it
     */
    public function read($key)
    {
        if (xcache_isset($key)) {
            $time = time();
            $cachetime = intval(xcache_get($key.'_expires'));
            if ($cachetime < $time || ($time + $this->settings['duration']) < $cachetime) {
                return false;
            }

            return xcache_get($key);
        }

        return false;
    }

    /**
     * Increments the value of an integer cached key
     * If the cache key is not an integer it will be treated as 0.
     *
     * @param string $key      Identifier for the data
     * @param int    $offset   How much to increment
     * @param int    $duration How long to cache the data, in seconds
     *
     * @return New incremented value, false otherwise
     */
    public function increment($key, $offset = 1)
    {
        return xcache_inc($key, $offset);
    }

    /**
     * Decrements the value of an integer cached key.
     * If the cache key is not an integer it will be treated as 0.
     *
     * @param string $key      Identifier for the data
     * @param int    $offset   How much to substract
     * @param int    $duration How long to cache the data, in seconds
     *
     * @return New decremented value, false otherwise
     */
    public function decrement($key, $offset = 1)
    {
        return xcache_dec($key, $offset);
    }

    /**
     * Delete a key from the cache.
     *
     * @param string $key Identifier for the data
     *
     * @return bool True if the value was succesfully deleted, false if it didn't exist or couldn't be removed
     */
    public function delete($key)
    {
        return xcache_unset($key);
    }

    /**
     * Delete all keys from the cache.
     *
     * @return bool True if the cache was succesfully cleared, false otherwise
     */
    public function clear()
    {
        $this->__auth();
        $max = xcache_count(XC_TYPE_VAR);
        for ($i = 0; $i < $max; ++$i) {
            xcache_clear_cache(XC_TYPE_VAR, $i);
        }
        $this->__auth(true);

        return true;
    }

    /**
     * Populates and reverses $_SERVER authentication values
     * Makes necessary changes (and reverting them back) in $_SERVER.
     *
     * This has to be done because xcache_clear_cache() needs to pass Basic Http Auth
     * (see xcache.admin configuration settings)
     *
     * @param bool Revert changes
     */
    public function __auth($reverse = false)
    {
        static $backup = [];
        $keys = ['PHP_AUTH_USER' => 'user', 'PHP_AUTH_PW' => 'password'];
        foreach ($keys as $key => $setting) {
            if ($reverse) {
                if (isset($backup[$key])) {
                    $_SERVER[$key] = $backup[$key];
                    unset($backup[$key]);
                } else {
                    unset($_SERVER[$key]);
                }
            } else {
                $value = env($key);
                if (!empty($value)) {
                    $backup[$key] = $value;
                }
                if (!empty($this->settings[$setting])) {
                    $_SERVER[$key] = $this->settings[$setting];
                } elseif (!empty($this->settings[$key])) {
                    $_SERVER[$key] = $this->settings[$key];
                } else {
                    $_SERVER[$key] = $value;
                }
            }
        }
    }
}
