<?php
/**
 * Collect Task File.
 *
 * Copyright (c) 2007-2010 David Persson
 *
 * Distributed under the terms of the MIT License.
 * Redistributions of files must retain the above copyright notice.
 *
 * PHP version 5
 * CakePHP version 1.2
 *
 * @copyright  2007-2010 David Persson <davidpersson@gmx.de>
 * @license    http://www.opensource.org/licenses/mit-license.php The MIT License
 *
 * @see       http://github.com/davidpersson/media
 */
class CollectTask extends MediaShell
{
    /**
     * Holds mapped files.
     *
     * @var array
     */
    public $_map;
    /**
     * Holds search paths.
     *
     * @var array
     */
    public $_paths;
    /**
     * Indicates if copy or link method should be used.
     *
     * @var bool
     */
    public $_link;
    /**
     * Patterns to use for exlcuding files.
     *
     * @var array
     */
    public $_exclude;

    /**
     * Main task method.
     *
     * @return bool
     */
    public function execute()
    {
        $this->_map = [];
        $this->_paths = [];
        $this->_link = null;

        if (isset($this->params['link'])) {
            $this->_link = true;
        }
        if (empty($this->args)) {
            $this->_paths = $this->_paths();
        } else {
            $this->_paths = $this->args;
        }
        if (isset($this->params['exclude'])) {
            $this->_exclude += array_map('trim', explode(',', $this->params['exclude']));
        }

        foreach ($this->_paths as $path) {
            $this->_map($path);
        }
        if (empty($this->_map)) {
            $this->err('No files selected.');

            return false;
        }

        if (DS == '/' && !isset($this->link)) {
            $answer = $this->in('Would you like to link (instead of copy) the files?', 'y,n', 'n');
            $this->_link = 'y' == $answer;
        }
        $this->out();
        $this->out('Mapping');
        $this->hr();

        foreach ($this->_map as $old => $new) {
            $message = sprintf(
                '%-38s %s %38s',
                $this->shortPath($old),
                $this->_link ? '<-' : '->',
                $this->shortPath($new)
            );
            $this->out($message);
        }

        $this->out();

        if ('n' == $this->in('Looks OK?', 'y,n', 'y')) {
            return false;
        }

        $this->out();
        $this->out('Collecting');
        $this->hr();

        return $this->_perform();
    }

    /**
     * Returns all common paths where media files are stored.
     *
     * @return array
     */
    public function _paths()
    {
        $plugins = array_map('strtolower', App::objects('plugin'));

        foreach ($plugins as $plugin) {
            foreach (Configure::read('pluginPaths') as $pluginPath) {
                if (is_dir($pluginPath.$plugin)) {
                    $pluginVendorPaths[] = $pluginPath.$plugin.DS.'vendors'.DS;
                }
            }
        }

        $paths = array_merge(
            App::path('vendors'),
            [WWW_ROOT],
            $pluginVendorPaths
        );

        foreach ($paths as $key => $path) {
            $message = sprintf('Would you like to collect files from %s?', $this->shortPath($path));

            if ('n' == $this->in($message, 'y,n', 'y')) {
                unset($paths[$key]);
            }
        }

        $answer = 'y';

        while ('y' == $answer) {
            if ($answer = 'y' == $this->in('Would you like to add another path?', 'y,n', 'n')) {
                $path = $this->in('Path:');

                if (!is_dir($path)) {
                    $this->out('Directory does not exist!');
                } else {
                    $paths[] = $path;
                }
            }
        }

        return $paths;
    }

    /**
     * (Interactively) maps source files to destinations.
     *
     * @param string $path Path to search for source files
     *
     * @return array
     */
    public function _map($path)
    {
        $include = '.*[\/\\].*\.[a-z0-9]{2,3}$';

        $directories = ['.htaccess', '.DS_Store', 'media', '.git', '.svn', 'simpletest', 'empty'];
        $extensions = ['db', 'htm', 'html', 'txt', 'php', 'ctp'];

        $exclude = '.*[/\\\]('.implode('|', $directories).').*$';
        $exclude .= '|.*[/\\\].*\.('.implode('|', $extensions).')$';

        if (!empty($this->_exclude)) {
            $exclude = '|.*[/\\\]('.implode('|', $this->_exclude).').*$';
        }

        $Folder = new Folder($path);
        $files = $Folder->findRecursive($include);

        foreach ($files as $file) {
            if (preg_match('#'.$exclude.'#', $file)) {
                continue;
            }
            $search[] = '/'.preg_quote($Folder->pwd(), '/').'/';
            $search[] = '/('.implode('|', Medium::short()).')'.preg_quote(DS, '/').'/';
            $fragment = preg_replace($search, null, $file);

            $mapped = [
                $file => MEDIA_STATIC.Medium::short($file).DS.$fragment,
            ];

            while (in_array(current($mapped), $this->_map) && $mapped) {
                $mapped = $this->_handleCollision($mapped);
            }
            while (file_exists(current($mapped)) && $mapped) {
                $this->out($this->shortPath(current($mapped)).' already exists.');
                $answer = $this->in('Would you like to [r]ename or [s]kip?', 'r,s', 's');

                if ('s' == $answer) {
                    $mapped = [];
                } else {
                    $mapped = [key($mapped) => $this->_rename(current($mapped))];
                }
            }
            if ($mapped) {
                $this->_map[key($mapped)] = current($mapped);
            }
        }
    }

    /**
     * Deals with collisions of destination files.
     *
     * @param array $mapped An array where the key is the source and the value the destination file
     *
     * @return array
     */
    public function _handleCollision($mapped)
    {
        $Left = new File(array_search(current($mapped), $this->_map));
        $Right = new File(key($mapped));

        $this->out('Collision:');
        $this->out();
        $this->out(sprintf('%s', $this->shortPath($Left->pwd())));
        $this->out(sprintf('|  %s', $this->shortPath($Right->pwd())));
        $this->out(sprintf('|  | '));
        $this->out(sprintf('V  V '));
        $this->out(sprintf('%s', $this->shortPath(current($mapped))));
        $this->out();

        if ($Left->md5() == $Right->md5()) {
            $this->out('Both files have the same checksum.');
        }

        $answer = $this->in('Would you like to [r]ename or [s]kip?', 'r,s', 's');

        if ('s' == $answer) {
            return [];
        }

        return [$Right->pwd() => $this->_rename($Right->pwd())];
    }

    /**
     * Prompts for renaming a file's basename.
     *
     * @param string $file
     *
     * @return string Absolute path to the new file
     */
    public function _rename($file)
    {
        $message = sprintf('Rename %s to:', basename($file));
        $basename = $this->in($message, null, basename($file));

        return dirname($file).DS.$basename;
    }

    /**
     * Performs the copying/linking of mapped files.
     *
     * @return bool
     */
    public function _perform()
    {
        $this->progress(count($this->_map));
        $i = 0;

        foreach ($this->_map as $old => $new) {
            $Folder = new Folder(dirname($new).DS, true);

            if ($this->_link) {
                $result = symlink($old, $new);
            } else {
                $result = copy($old, $new);
            }

            $message = sprintf('[%-6s] %s', $result ? 'OK' : 'FAILED', $this->shortPath($old));
            $this->progress(++$i, $message);
        }
        $this->out();

        return true;
    }
}
