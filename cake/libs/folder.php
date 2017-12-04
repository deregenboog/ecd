<?php
/**
 * Convenience class for handling directories.
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
 * Included libraries.
 */
if (!class_exists('Object')) {
    require LIBS.'object.php';
}

/**
 * Folder structure browser, lists folders and files.
 * Provides an Object interface for Common directory related tasks.
 */
class Folder extends Object
{
    /**
     * Path to Folder.
     *
     * @var string
     */
    public $path = null;

    /**
     * Sortedness. Whether or not list results
     * should be sorted by name.
     *
     * @var bool
     */
    public $sort = false;

    /**
     * Mode to be used on create. Does nothing on windows platforms.
     *
     * @var int
     */
    public $mode = 0755;

    /**
     * Holds messages from last method.
     *
     * @var array
     */
    public $__messages = [];

    /**
     * Holds errors from last method.
     *
     * @var array
     */
    public $__errors = false;

    /**
     * Holds array of complete directory paths.
     *
     * @var array
     */
    public $__directories;

    /**
     * Holds array of complete file paths.
     *
     * @var array
     */
    public $__files;

    /**
     * Constructor.
     *
     * @param string $path   Path to folder
     * @param bool   $create Create folder if not found
     * @param mixed  $mode   Mode (CHMOD) to apply to created folder, false to ignore
     */
    public function __construct($path = false, $create = false, $mode = false)
    {
        parent::__construct();
        if (empty($path)) {
            $path = TMP;
        }
        if ($mode) {
            $this->mode = $mode;
        }

        if (!file_exists($path) && true === $create) {
            $this->create($path, $this->mode);
        }
        if (!self::isAbsolute($path)) {
            $path = realpath($path);
        }
        if (!empty($path)) {
            $this->cd($path);
        }
    }

    /**
     * Return current path.
     *
     * @return string Current path
     */
    public function pwd()
    {
        return $this->path;
    }

    /**
     * Change directory to $path.
     *
     * @param string $path Path to the directory to change to
     *
     * @return string The new path. Returns false on failure
     */
    public function cd($path)
    {
        $path = $this->realpath($path);
        if (is_dir($path)) {
            return $this->path = $path;
        }

        return false;
    }

    /**
     * Returns an array of the contents of the current directory.
     * The returned array holds two arrays: One of directories and one of files.
     *
     * @param bool  $sort       whether you want the results sorted, set this and the sort property
     *                          to false to get unsorted results
     * @param mixed $exceptions Either an array or boolean true will not grab dot files
     * @param bool  $fullPath   True returns the full path
     *
     * @return mixed Contents of current directory as an array, an empty array on failure
     */
    public function read($sort = true, $exceptions = false, $fullPath = false)
    {
        $dirs = $files = [];

        if (!$this->pwd()) {
            return [$dirs, $files];
        }
        if (is_array($exceptions)) {
            $exceptions = array_flip($exceptions);
        }
        $skipHidden = isset($exceptions['.']) || true === $exceptions;

        if (false === ($dir = @opendir($this->path))) {
            return [$dirs, $files];
        }

        while (false !== ($item = readdir($dir))) {
            if ('.' === $item || '..' === $item || ($skipHidden && '.' === $item[0]) || isset($exceptions[$item])) {
                continue;
            }

            $path = self::addPathElement($this->path, $item);
            if (is_dir($path)) {
                $dirs[] = $fullPath ? $path : $item;
            } else {
                $files[] = $fullPath ? $path : $item;
            }
        }

        if ($sort || $this->sort) {
            sort($dirs);
            sort($files);
        }

        closedir($dir);

        return [$dirs, $files];
    }

    /**
     * Returns an array of all matching files in current directory.
     *
     * @param string $pattern Preg_match pattern (Defaults to: .*)
     * @param bool   $sort    whether results should be sorted
     *
     * @return array Files that match given pattern
     */
    public function find($regexpPattern = '.*', $sort = false)
    {
        list($dirs, $files) = $this->read($sort);

        return array_values(preg_grep('/^'.$regexpPattern.'$/i', $files));
    }

    /**
     * Returns an array of all matching files in and below current directory.
     *
     * @param string $pattern Preg_match pattern (Defaults to: .*)
     * @param bool   $sort    whether results should be sorted
     *
     * @return array Files matching $pattern
     */
    public function findRecursive($pattern = '.*', $sort = false)
    {
        if (!$this->pwd()) {
            return [];
        }
        $startsOn = $this->path;
        $out = $this->_findRecursive($pattern, $sort);
        $this->cd($startsOn);

        return $out;
    }

    /**
     * Private helper function for findRecursive.
     *
     * @param string $pattern Pattern to match against
     * @param bool   $sort    whether results should be sorted
     *
     * @return array Files matching pattern
     */
    public function _findRecursive($pattern, $sort = false)
    {
        list($dirs, $files) = $this->read($sort);
        $found = [];

        foreach ($files as $file) {
            if (preg_match('/^'.$pattern.'$/i', $file)) {
                $found[] = self::addPathElement($this->path, $file);
            }
        }
        $start = $this->path;

        foreach ($dirs as $dir) {
            $this->cd(self::addPathElement($start, $dir));
            $found = array_merge($found, $this->findRecursive($pattern, $sort));
        }

        return $found;
    }

    /**
     * Returns true if given $path is a Windows path.
     *
     * @param string $path Path to check
     *
     * @return bool true if windows path, false otherwise
     * @static
     */
    public function isWindowsPath($path)
    {
        return preg_match('/^[A-Z]:\\\\/i', $path) || '\\\\' == substr($path, 0, 2);
    }

    /**
     * Returns true if given $path is an absolute path.
     *
     * @param string $path Path to check
     *
     * @return bool true if path is absolute
     * @static
     */
    public function isAbsolute($path)
    {
        return !empty($path) && ('/' === $path[0] || preg_match('/^[A-Z]:\\\\/i', $path) || '\\\\' == substr($path, 0, 2));
    }

    /**
     * Returns a correct set of slashes for given $path. (\\ for Windows paths and / for other paths.).
     *
     * @param string $path Path to check
     *
     * @return string Set of slashes ("\\" or "/")
     * @static
     */
    public function normalizePath($path)
    {
        return self::correctSlashFor($path);
    }

    /**
     * Returns a correct set of slashes for given $path. (\\ for Windows paths and / for other paths.).
     *
     * @param string $path Path to check
     *
     * @return string Set of slashes ("\\" or "/")
     * @static
     */
    public function correctSlashFor($path)
    {
        return (self::isWindowsPath($path)) ? '\\' : '/';
    }

    /**
     * Returns $path with added terminating slash (corrected for Windows or other OS).
     *
     * @param string $path Path to check
     *
     * @return string Path with ending slash
     * @static
     */
    public function slashTerm($path)
    {
        if (self::isSlashTerm($path)) {
            return $path;
        }

        return $path.self::correctSlashFor($path);
    }

    /**
     * Returns $path with $element added, with correct slash in-between.
     *
     * @param string $path    Path
     * @param string $element Element to and at end of path
     *
     * @return string Combined path
     * @static
     */
    public function addPathElement($path, $element)
    {
        return rtrim($path, DS).DS.$element;
    }

    /**
     * Returns true if the File is in a given CakePath.
     *
     * @param string $path the path to check
     *
     * @return bool
     */
    public function inCakePath($path = '')
    {
        $dir = substr(self::slashTerm(ROOT), 0, -1);
        $newdir = $dir.$path;

        return $this->inPath($newdir);
    }

    /**
     * Returns true if the File is in given path.
     *
     * @param string $path    the path to check that the current pwd() resides with in
     * @param bool   $reverse
     *
     * @return bool
     */
    public function inPath($path = '', $reverse = false)
    {
        $dir = self::slashTerm($path);
        $current = self::slashTerm($this->pwd());

        if (!$reverse) {
            $return = preg_match('/^(.*)'.preg_quote($dir, '/').'(.*)/', $current);
        } else {
            $return = preg_match('/^(.*)'.preg_quote($current, '/').'(.*)/', $dir);
        }

        return (bool) $return;
    }

    /**
     * Change the mode on a directory structure recursively. This includes changing the mode on files as well.
     *
     * @param string $path       The path to chmod
     * @param int    $mode       octal value 0755
     * @param bool   $recursive  chmod recursively, set to false to only change the current directory
     * @param array  $exceptions array of files, directories to skip
     *
     * @return bool Returns TRUE on success, FALSE on failure
     */
    public function chmod($path, $mode = false, $recursive = true, $exceptions = [])
    {
        if (!$mode) {
            $mode = $this->mode;
        }

        if (false === $recursive && is_dir($path)) {
            if (@chmod($path, intval($mode, 8))) {
                $this->__messages[] = sprintf(__('%s changed to %s', true), $path, $mode);

                return true;
            }

            $this->__errors[] = sprintf(__('%s NOT changed to %s', true), $path, $mode);

            return false;
        }

        if (is_dir($path)) {
            $paths = $this->tree($path);

            foreach ($paths as $type) {
                foreach ($type as $key => $fullpath) {
                    $check = explode(DS, $fullpath);
                    $count = count($check);

                    if (in_array($check[$count - 1], $exceptions)) {
                        continue;
                    }

                    if (@chmod($fullpath, intval($mode, 8))) {
                        $this->__messages[] = sprintf(__('%s changed to %s', true), $fullpath, $mode);
                    } else {
                        $this->__errors[] = sprintf(__('%s NOT changed to %s', true), $fullpath, $mode);
                    }
                }
            }

            if (empty($this->__errors)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns an array of nested directories and files in each directory.
     *
     * @param string $path       the directory path to build the tree from
     * @param mixed  $exceptions array of files to exclude, defaults to excluding hidden files
     * @param string $type       either file or dir. null returns both files and directories
     *
     * @return mixed array of nested directories and files in each directory
     */
    public function tree($path, $exceptions = true, $type = null)
    {
        $original = $this->path;
        $path = rtrim($path, DS);
        if (!$this->cd($path)) {
            if (null === $type) {
                return [[], []];
            }

            return [];
        }
        $this->__files = [];
        $this->__directories = [$this->realpath($path)];
        $directories = [];

        if (false === $exceptions) {
            $exceptions = true;
        }
        while (!empty($this->__directories)) {
            $dir = array_pop($this->__directories);
            $this->__tree($dir, $exceptions);
            $directories[] = $dir;
        }

        if (null === $type) {
            return [$directories, $this->__files];
        }
        if ('dir' === $type) {
            return $directories;
        }
        $this->cd($original);

        return $this->__files;
    }

    /**
     * Private method to list directories and files in each directory.
     *
     * @param string $path       the Path to read
     * @param mixed  $exceptions array of files to exclude from the read that will be performed
     */
    public function __tree($path, $exceptions)
    {
        $this->path = $path;
        list($dirs, $files) = $this->read(false, $exceptions, true);
        $this->__directories = array_merge($this->__directories, $dirs);
        $this->__files = array_merge($this->__files, $files);
    }

    /**
     * Create a directory structure recursively. Can be used to create
     * deep path structures like `/foo/bar/baz/shoe/horn`.
     *
     * @param string $pathname The directory structure to create
     * @param int    $mode     octal value 0755
     *
     * @return bool Returns TRUE on success, FALSE on failure
     */
    public function create($pathname, $mode = false)
    {
        if (is_dir($pathname) || empty($pathname)) {
            return true;
        }

        if (!$mode) {
            $mode = $this->mode;
        }

        if (is_file($pathname)) {
            $this->__errors[] = sprintf(__('%s is a file', true), $pathname);

            return false;
        }
        $pathname = rtrim($pathname, DS);
        $nextPathname = substr($pathname, 0, strrpos($pathname, DS));

        if ($this->create($nextPathname, $mode)) {
            if (!file_exists($pathname)) {
                $old = umask(0);
                if (mkdir($pathname, $mode)) {
                    umask($old);
                    $this->__messages[] = sprintf(__('%s created', true), $pathname);

                    return true;
                } else {
                    umask($old);
                    $this->__errors[] = sprintf(__('%s NOT created', true), $pathname);

                    return false;
                }
            }
        }

        return false;
    }

    /**
     * Returns the size in bytes of this Folder and its contents.
     *
     * @param string $directory Path to directory
     *
     * @return int size in bytes of current folder
     */
    public function dirsize()
    {
        $size = 0;
        $directory = self::slashTerm($this->path);
        $stack = [$directory];
        $count = count($stack);
        for ($i = 0, $j = $count; $i < $j; ++$i) {
            if (is_file($stack[$i])) {
                $size += filesize($stack[$i]);
            } elseif (is_dir($stack[$i])) {
                $dir = dir($stack[$i]);
                if ($dir) {
                    while (false !== ($entry = $dir->read())) {
                        if ('.' === $entry || '..' === $entry) {
                            continue;
                        }
                        $add = $stack[$i].$entry;

                        if (is_dir($stack[$i].$entry)) {
                            $add = self::slashTerm($add);
                        }
                        $stack[] = $add;
                    }
                    $dir->close();
                }
            }
            $j = count($stack);
        }

        return $size;
    }

    /**
     * Recursively Remove directories if the system allows.
     *
     * @param string $path Path of directory to delete
     *
     * @return bool Success
     */
    public function delete($path = null)
    {
        if (!$path) {
            $path = $this->pwd();
        }
        if (!$path) {
            return null;
        }
        $path = self::slashTerm($path);
        if (true === is_dir($path)) {
            $normalFiles = glob($path.'*');
            $hiddenFiles = glob($path.'\.?*');

            $normalFiles = $normalFiles ? $normalFiles : [];
            $hiddenFiles = $hiddenFiles ? $hiddenFiles : [];

            $files = array_merge($normalFiles, $hiddenFiles);
            if (is_array($files)) {
                foreach ($files as $file) {
                    if (preg_match('/(\.|\.\.)$/', $file)) {
                        continue;
                    }
                    if (true === is_file($file)) {
                        if (@unlink($file)) {
                            $this->__messages[] = sprintf(__('%s removed', true), $file);
                        } else {
                            $this->__errors[] = sprintf(__('%s NOT removed', true), $file);
                        }
                    } elseif (true === is_dir($file) && false === $this->delete($file)) {
                        return false;
                    }
                }
            }
            $path = substr($path, 0, strlen($path) - 1);
            if (false === rmdir($path)) {
                $this->__errors[] = sprintf(__('%s NOT removed', true), $path);

                return false;
            } else {
                $this->__messages[] = sprintf(__('%s removed', true), $path);
            }
        }

        return true;
    }

    /**
     * Recursive directory copy.
     *
     * ### Options
     *
     * - `to` The directory to copy to.
     * - `from` The directory to copy from, this will cause a cd() to occur, changing the results of pwd().
     * - `mode` The mode to copy the files/directories with.
     * - `skip` Files/directories to skip.
     *
     * @param mixed $options either an array of options (see above) or a string of the destination directory
     *
     * @return bool Success
     */
    public function copy($options = [])
    {
        if (!$this->pwd()) {
            return false;
        }
        $to = null;
        if (is_string($options)) {
            $to = $options;
            $options = [];
        }
        $options = array_merge(['to' => $to, 'from' => $this->path, 'mode' => $this->mode, 'skip' => []], $options);

        $fromDir = $options['from'];
        $toDir = $options['to'];
        $mode = $options['mode'];

        if (!$this->cd($fromDir)) {
            $this->__errors[] = sprintf(__('%s not found', true), $fromDir);

            return false;
        }

        if (!is_dir($toDir)) {
            $this->create($toDir, $mode);
        }

        if (!is_writable($toDir)) {
            $this->__errors[] = sprintf(__('%s not writable', true), $toDir);

            return false;
        }

        $exceptions = array_merge(['.', '..', '.svn'], $options['skip']);
        if ($handle = @opendir($fromDir)) {
            while (false !== ($item = readdir($handle))) {
                if (!in_array($item, $exceptions)) {
                    $from = self::addPathElement($fromDir, $item);
                    $to = self::addPathElement($toDir, $item);
                    if (is_file($from)) {
                        if (copy($from, $to)) {
                            chmod($to, intval($mode, 8));
                            touch($to, filemtime($from));
                            $this->__messages[] = sprintf(__('%s copied to %s', true), $from, $to);
                        } else {
                            $this->__errors[] = sprintf(__('%s NOT copied to %s', true), $from, $to);
                        }
                    }

                    if (is_dir($from) && !file_exists($to)) {
                        $old = umask(0);
                        if (mkdir($to, $mode)) {
                            umask($old);
                            $old = umask(0);
                            chmod($to, $mode);
                            umask($old);
                            $this->__messages[] = sprintf(__('%s created', true), $to);
                            $options = array_merge($options, ['to' => $to, 'from' => $from]);
                            $this->copy($options);
                        } else {
                            $this->__errors[] = sprintf(__('%s not created', true), $to);
                        }
                    }
                }
            }
            closedir($handle);
        } else {
            return false;
        }

        if (!empty($this->__errors)) {
            return false;
        }

        return true;
    }

    /**
     * Recursive directory move.
     *
     * ### Options
     *
     * - `to` The directory to copy to.
     * - `from` The directory to copy from, this will cause a cd() to occur, changing the results of pwd().
     * - `chmod` The mode to copy the files/directories with.
     * - `skip` Files/directories to skip.
     *
     * @param array $options (to, from, chmod, skip)
     *
     * @return bool Success
     */
    public function move($options)
    {
        $to = null;
        if (is_string($options)) {
            $to = $options;
            $options = (array) $options;
        }
        $options = array_merge(['to' => $to, 'from' => $this->path, 'mode' => $this->mode, 'skip' => []], $options);

        if ($this->copy($options)) {
            if ($this->delete($options['from'])) {
                return $this->cd($options['to']);
            }
        }

        return false;
    }

    /**
     * get messages from latest method.
     *
     * @return array
     */
    public function messages()
    {
        return $this->__messages;
    }

    /**
     * get error from latest method.
     *
     * @return array
     */
    public function errors()
    {
        return $this->__errors;
    }

    /**
     * Get the real path (taking ".." and such into account).
     *
     * @param string $path Path to resolve
     *
     * @return string The resolved path
     */
    public function realpath($path)
    {
        $path = str_replace('/', DS, trim($path));
        if (false === strpos($path, '..')) {
            if (!self::isAbsolute($path)) {
                $path = self::addPathElement($this->path, $path);
            }

            return $path;
        }
        $parts = explode(DS, $path);
        $newparts = [];
        $newpath = '';
        if (DS === $path[0]) {
            $newpath = DS;
        }

        while (null !== ($part = array_shift($parts))) {
            if ('.' === $part || '' === $part) {
                continue;
            }
            if ('..' === $part) {
                if (!empty($newparts)) {
                    array_pop($newparts);
                    continue;
                } else {
                    return false;
                }
            }
            $newparts[] = $part;
        }
        $newpath .= implode(DS, $newparts);

        return self::slashTerm($newpath);
    }

    /**
     * Returns true if given $path ends in a slash (i.e. is slash-terminated).
     *
     * @param string $path Path to check
     *
     * @return bool true if path ends with slash, false otherwise
     * @static
     */
    public function isSlashTerm($path)
    {
        $lastChar = $path[strlen($path) - 1];

        return '/' === $lastChar || '\\' === $lastChar;
    }
}
