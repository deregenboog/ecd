<?php
/**
 * File Storage engine for cache.
 *
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
 * @since         CakePHP(tm) v 1.2.0.4933
 *
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
if (!class_exists('File')) {
    require LIBS.'file.php';
}
/**
 * File Storage engine for cache.
 *
 * @todo use the File and Folder classes (if it's not a too big performance hit)
 */
class FileEngine extends CacheEngine
{
    /**
     * Instance of File class.
     *
     * @var File
     */
    public $_File = null;

    /**
     * Settings.
     *
     * - path = absolute path to cache directory, default => CACHE
     * - prefix = string prefix for filename, default => cake_
     * - lock = enable file locking on write, default => false
     * - serialize = serialize the data, default => true
     *
     * @var array
     *
     * @see CacheEngine::__defaults
     */
    public $settings = [];

    /**
     * True unless FileEngine::__active(); fails.
     *
     * @var bool
     */
    public $_init = true;

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
    public function init($settings = [])
    {
        parent::init(array_merge(
            [
                'engine' => 'File', 'path' => CACHE, 'prefix' => 'cake_', 'lock' => false,
                'serialize' => true, 'isWindows' => false,
            ],
            $settings
        ));
        if (!isset($this->_File)) {
            $this->_File = new File($this->settings['path'].DS.'cake');
        }

        if (DIRECTORY_SEPARATOR === '\\') {
            $this->settings['isWindows'] = true;
        }

        $path = $this->_File->Folder->cd($this->settings['path']);
        if ($path) {
            $this->settings['path'] = $path;
        }

        return $this->__active();
    }

    /**
     * Garbage collection. Permanently remove all expired and deleted data.
     *
     * @return bool True if garbage collection was succesful, false on failure
     */
    public function gc()
    {
        return $this->clear(true);
    }

    /**
     * Write data for key into cache.
     *
     * @param string $key      Identifier for the data
     * @param mixed  $data     Data to be cached
     * @param mixed  $duration How long to cache the data, in seconds
     *
     * @return bool True if the data was succesfully cached, false on failure
     */
    public function write($key, &$data, $duration)
    {
        if ('' === $data || !$this->_init) {
            return false;
        }

        if (false === $this->_setKey($key)) {
            return false;
        }

        $lineBreak = "\n";

        if ($this->settings['isWindows']) {
            $lineBreak = "\r\n";
        }

        if (!empty($this->settings['serialize'])) {
            if ($this->settings['isWindows']) {
                $data = str_replace('\\', '\\\\\\\\', serialize($data));
            } else {
                $data = serialize($data);
            }
        }

        $expires = time() + $duration;
        $contents = $expires.$lineBreak.$data.$lineBreak;
        $old = umask(0);
        $handle = fopen($this->_File->path, 'a');
        umask($old);

        if (!$handle) {
            return false;
        }

        if ($this->settings['lock']) {
            flock($handle, LOCK_EX);
        }

        $success = ftruncate($handle, 0) && fwrite($handle, $contents) && fflush($handle);

        if ($this->settings['lock']) {
            flock($handle, LOCK_UN);
        }

        fclose($handle);

        return $success;
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
        if (false === $this->_setKey($key) || !$this->_init || !$this->_File->exists()) {
            return false;
        }
        if ($this->settings['lock']) {
            $this->_File->lock = true;
        }
        $time = time();
        $cachetime = intval($this->_File->read(11));

        if (false !== $cachetime && ($cachetime < $time || ($time + $this->settings['duration']) < $cachetime)) {
            $this->_File->close();

            return false;
        }
        $data = $this->_File->read(true);

        if ('' !== $data && !empty($this->settings['serialize'])) {
            if ($this->settings['isWindows']) {
                $data = str_replace('\\\\\\\\', '\\', $data);
            }
            $data = unserialize((string) $data);
        }
        $this->_File->close();

        return $data;
    }

    /**
     * Delete a key from the cache.
     *
     * @param string $key Identifier for the data
     *
     * @return bool True if the value was successfully deleted, false if it didn't exist or couldn't be removed
     */
    public function delete($key)
    {
        if (false === $this->_setKey($key) || !$this->_init) {
            return false;
        }

        return $this->_File->delete();
    }

    /**
     * Delete all values from the cache.
     *
     * @param bool $check Optional - only delete expired cache items
     *
     * @return bool True if the cache was succesfully cleared, false otherwise
     */
    public function clear($check)
    {
        if (!$this->_init) {
            return false;
        }
        $dir = dir($this->settings['path']);
        if ($check) {
            $now = time();
            $threshold = $now - $this->settings['duration'];
        }
        $prefixLength = strlen($this->settings['prefix']);
        while (false !== ($entry = $dir->read())) {
            if (substr($entry, 0, $prefixLength) !== $this->settings['prefix']) {
                continue;
            }
            if (false === $this->_setKey($entry)) {
                continue;
            }
            if ($check) {
                $mtime = $this->_File->lastChange();

                if (false === $mtime || $mtime > $threshold) {
                    continue;
                }

                $expires = $this->_File->read(11);
                $this->_File->close();

                if ($expires > $now) {
                    continue;
                }
            }
            $this->_File->delete();
        }
        $dir->close();

        return true;
    }

    /**
     * Get absolute file for a given key.
     *
     * @param string $key The key
     *
     * @return mixed Absolute cache file for the given key or false if erroneous
     */
    public function _setKey($key)
    {
        $this->_File->Folder->cd($this->settings['path']);
        if ($key !== $this->_File->name) {
            $this->_File->name = $key;
            $this->_File->path = null;
        }
        if (!$this->_File->Folder->inPath($this->_File->pwd(), true)) {
            return false;
        }
    }

    /**
     * Determine is cache directory is writable.
     *
     * @return bool
     */
    public function __active()
    {
        if ($this->_init && !is_writable($this->settings['path'])) {
            $this->_init = false;
            trigger_error(sprintf(__('%s is not writable', true), $this->settings['path']), E_USER_WARNING);

            return false;
        }

        return true;
    }
}
