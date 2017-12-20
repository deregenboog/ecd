<?php
/**
 * App and Configure classes.
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
 * @since         CakePHP(tm) v 1.0.0.2363
 *
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * Configuration class (singleton). Used for managing runtime configuration information.
 *
 * @see          http://book.cakephp.org/1.3/en/The-Manual/Developing-with-CakePHP/Configuration.html#the-configuration-class
 */
class Configure extends Object
{
    /**
     * Current debug level.
     *
     * @see          http://book.cakephp.org/1.3/en/The-Manual/Developing-with-CakePHP/Configuration.html#cakephp-core-configuration-variables
     *
     * @var int
     */
    public $debug = 0;

    /**
     * Returns a singleton instance of the Configure class.
     *
     * @return Configure instance
     */
    public function &getInstance($boot = true)
    {
        static $instance = [];
        if (!$instance) {
            if (!class_exists('Set')) {
                require LIBS.'set.php';
            }
            $instance[0] = new self();
            $instance[0]->__loadBootstrap($boot);
        }

        return $instance[0];
    }

    /**
     * Used to store a dynamic variable in the Configure instance.
     *
     * Usage:
     * {{{
     * Configure::write('One.key1', 'value of the Configure::One[key1]');
     * Configure::write(array('One.key1' => 'value of the Configure::One[key1]'));
     * Configure::write('One', array(
     *     'key1' => 'value of the Configure::One[key1]',
     *     'key2' => 'value of the Configure::One[key2]'
     * );
     *
     * Configure::write(array(
     *     'One.key1' => 'value of the Configure::One[key1]',
     *     'One.key2' => 'value of the Configure::One[key2]'
     * ));
     * }}}
     *
     * @see http://book.cakephp.org/1.3/en/The-Manual/Developing-with-CakePHP/Configuration.html#write
     * @see http://book.cakephp.org/view/926/write
     *
     * @param array $config Name of var to write
     * @param mixed $value  Value to set for var
     *
     * @return bool True if write was successful
     */
    public function write($config, $value = null)
    {
        $_this = &self::getInstance();

        if (!is_array($config)) {
            $config = [$config => $value];
        }

        foreach ($config as $name => $value) {
            if (false === strpos($name, '.')) {
                $_this->{$name} = $value;
            } else {
                $names = explode('.', $name, 4);
                switch (count($names)) {
                    case 2:
                        $_this->{$names[0]}[$names[1]] = $value;
                    break;
                    case 3:
                        $_this->{$names[0]}[$names[1]][$names[2]] = $value;
                        break;
                    case 4:
                        $names = explode('.', $name, 2);
                        if (!isset($_this->{$names[0]})) {
                            $_this->{$names[0]} = [];
                        }
                        $_this->{$names[0]} = Set::insert($_this->{$names[0]}, $names[1], $value);
                    break;
                }
            }
        }

        if (isset($config['debug']) || isset($config['log'])) {
            $reporting = 0;
            if ($_this->debug) {
                if (!class_exists('Debugger')) {
                    require LIBS.'debugger.php';
                }
                $reporting = E_ALL & ~E_DEPRECATED & ~E_STRICT;
                if (function_exists('ini_set')) {
                    ini_set('display_errors', 1);
                }
                $callback = ['Debugger', 'getInstance'];
            } elseif (function_exists('ini_set')) {
                ini_set('display_errors', 0);
            }

            if (isset($_this->log) && $_this->log) {
                if (is_integer($_this->log) && !$_this->debug) {
                    $reporting = $_this->log;
                } else {
                    $reporting = E_ALL & ~E_DEPRECATED & ~E_STRICT;
                }
                error_reporting($reporting);
                if (!class_exists('CakeLog')) {
                    require LIBS.'cake_log.php';
                }
                if (empty($callback)) {
                    $callback = ['CakeLog', 'getInstance'];
                }
            }
            if (!empty($callback) && !defined('DISABLE_DEFAULT_ERROR_HANDLING') && class_exists('Debugger')) {
                Debugger::invoke(call_user_func($callback));
            }
            error_reporting($reporting);
        }

        return true;
    }

    /**
     * Used to read information stored in the Configure instance.
     *
     * Usage:
     * {{{
     * Configure::read('Name'); will return all values for Name
     * Configure::read('Name.key'); will return only the value of Configure::Name[key]
     * }}}
     *
     * @see http://book.cakephp.org/1.3/en/The-Manual/Developing-with-CakePHP/Configuration.html#read
     *
     * @param string $var Variable to obtain.  Use '.' to access array elements.
     *
     * @return string value of Configure::$var
     */
    public function read($var = 'debug')
    {
        $_this = &self::getInstance();

        if ('debug' === $var) {
            return $_this->debug;
        }

        if (false !== strpos($var, '.')) {
            $names = explode('.', $var, 3);
            $var = $names[0];
        }
        if (!isset($_this->{$var})) {
            return null;
        }
        if (!isset($names[1])) {
            return $_this->{$var};
        }
        switch (count($names)) {
            case 2:
                if (isset($_this->{$var}[$names[1]])) {
                    return $_this->{$var}[$names[1]];
                }
            break;
            case 3:
                if (isset($_this->{$var}[$names[1]][$names[2]])) {
                    return $_this->{$var}[$names[1]][$names[2]];
                }
                if (!isset($_this->{$var}[$names[1]])) {
                    return null;
                }

                return Set::classicExtract($_this->{$var}[$names[1]], $names[2]);
            break;
        }

        return null;
    }

    /**
     * Used to delete a variable from the Configure instance.
     *
     * Usage:
     * {{{
     * Configure::delete('Name'); will delete the entire Configure::Name
     * Configure::delete('Name.key'); will delete only the Configure::Name[key]
     * }}}
     *
     * @see http://book.cakephp.org/1.3/en/The-Manual/Developing-with-CakePHP/Configuration.html#delete
     *
     * @param string $var the var to be deleted
     */
    public function delete($var = null)
    {
        $_this = &self::getInstance();

        if (false === strpos($var, '.')) {
            unset($_this->{$var});

            return;
        }

        $names = explode('.', $var, 2);
        $_this->{$names[0]} = Set::remove($_this->{$names[0]}, $names[1]);
    }

    /**
     * Loads a file from app/config/configure_file.php.
     * Config file variables should be formated like:
     *  `$config['name'] = 'value';`
     * These will be used to create dynamic Configure vars. load() is also used to
     * load stored config files created with Configure::store().
     *
     * - To load config files from app/config use `Configure::load('configure_file');`.
     * - To load config files from a plugin `Configure::load('plugin.configure_file');`.
     *
     * @see http://book.cakephp.org/1.3/en/The-Manual/Developing-with-CakePHP/Configuration.html#load
     *
     * @param string $fileName name of file to load, extension must be .php and only the name
     *                         should be used, not the extenstion
     *
     * @return mixed false if file not found, void if load successful
     */
    public function load($fileName)
    {
        $found = $plugin = $pluginPath = false;
        list($plugin, $fileName) = pluginSplit($fileName);
        if ($plugin) {
            $pluginPath = App::pluginPath($plugin);
        }
        $pos = strpos($fileName, '..');

        if (false === $pos) {
            if ($pluginPath && file_exists($pluginPath.'config'.DS.$fileName.'.php')) {
                include $pluginPath.'config'.DS.$fileName.'.php';
                $found = true;
            } elseif (file_exists(CONFIGS.$fileName.'.php')) {
                include CONFIGS.$fileName.'.php';
                $found = true;
            } elseif (file_exists(CACHE.'persistent'.DS.$fileName.'.php')) {
                include CACHE.'persistent'.DS.$fileName.'.php';
                $found = true;
            } else {
                foreach (App::core('cake') as $key => $path) {
                    if (file_exists($path.DS.'config'.DS.$fileName.'.php')) {
                        include $path.DS.'config'.DS.$fileName.'.php';
                        $found = true;
                        break;
                    }
                }
            }
        }

        if (!$found) {
            return false;
        }

        if (!isset($config)) {
            trigger_error(sprintf(__('Configure::load() - no variable $config found in %s.php', true), $fileName), E_USER_WARNING);

            return false;
        }

        return self::write($config);
    }

    /**
     * Used to determine the current version of CakePHP.
     *
     * Usage `Configure::version();`
     *
     * @see http://book.cakephp.org/1.3/en/The-Manual/Developing-with-CakePHP/Configuration.html#version
     *
     * @return string Current version of CakePHP
     */
    public function version()
    {
        $_this = &self::getInstance();

        if (!isset($_this->Cake['version'])) {
            require(CORE_PATH.'cake'.DS.'config'.DS.'config.php');
            $_this->write($config);
        }

        return $_this->Cake['version'];
    }

    /**
     * Used to write a config file to disk.
     *
     * {{{
     * Configure::store('Model', 'class_paths', array('Users' => array(
     *      'path' => 'users', 'plugin' => true
     * )));
     * }}}
     *
     * @param string $type Type of config file to write, ex: Models, Controllers, Helpers, Components
     * @param string $name file name
     * @param array  $data array of values to store
     */
    public function store($type, $name, $data = [])
    {
        $write = true;
        $content = '';

        foreach ($data as $key => $value) {
            $content .= "\$config['$type']['$key'] = ".var_export($value, true).";\n";
        }
        if (is_null($type)) {
            $write = false;
        }
        self::__writeConfig($content, $name, $write);
    }

    /**
     * Creates a cached version of a configuration file.
     * Appends values passed from Configure::store() to the cached file.
     *
     * @param string $content Content to write on file
     * @param string $name    Name to use for cache file
     * @param bool   $write   true if content should be written, false otherwise
     */
    public function __writeConfig($content, $name, $write = true)
    {
        $file = CACHE.'persistent'.DS.$name.'.php';

        if (self::read() > 0) {
            $expires = '+10 seconds';
        } else {
            $expires = '+999 days';
        }
        $cache = cache('persistent'.DS.$name.'.php', null, $expires);

        if (null === $cache) {
            cache('persistent'.DS.$name.'.php', "<?php\n\$config = array();\n", $expires);
        }

        if (true === $write) {
            if (!class_exists('File')) {
                require LIBS.'file.php';
            }
            $fileClass = new File($file);

            if ($fileClass->writable()) {
                $fileClass->append($content);
            }
        }
    }

    /**
     * @deprecated
     * @see App::objects()
     */
    public function listObjects($type, $path = null, $cache = true)
    {
        return App::objects($type, $path, $cache);
    }

    /**
     * @deprecated
     * @see App::core()
     */
    public function corePaths($type = null)
    {
        return App::core($type);
    }

    /**
     * @deprecated
     * @see App::build()
     */
    public function buildPaths($paths)
    {
        return App::build($paths);
    }

    /**
     * Loads app/config/bootstrap.php.
     * If the alternative paths are set in this file
     * they will be added to the paths vars.
     *
     * @param bool $boot Load application bootstrap (if true)
     */
    public function __loadBootstrap($boot)
    {
        if ($boot) {
            self::write('App', ['base' => false, 'baseUrl' => false, 'dir' => APP_DIR, 'webroot' => WEBROOT_DIR, 'www_root' => WWW_ROOT]);

            if (!include(CONFIGS.'core.php')) {
                trigger_error(sprintf(__("Can't find application core file. Please create %score.php, and make sure it is readable by PHP.", true), CONFIGS), E_USER_ERROR);
            }

            if (true !== self::read('Cache.disable')) {
                $cache = Cache::config('default');

                if (empty($cache['settings'])) {
                    trigger_error(__('Cache not configured properly. Please check Cache::config(); in APP/config/core.php', true), E_USER_WARNING);
                    $cache = Cache::config('default', ['engine' => 'File']);
                }
                $path = $prefix = $duration = null;

                if (!empty($cache['settings']['path'])) {
                    $path = realpath($cache['settings']['path']);
                } else {
                    $prefix = $cache['settings']['prefix'];
                }

                if (self::read() >= 1) {
                    $duration = '+10 seconds';
                } else {
                    $duration = '+999 days';
                }

                if (false === Cache::config('_cake_core_')) {
                    Cache::config('_cake_core_', array_merge((array) $cache['settings'], [
                        'prefix' => $prefix.'cake_core_', 'path' => $path.DS.'persistent'.DS,
                        'serialize' => true, 'duration' => $duration,
                    ]));
                }

                if (false === Cache::config('_cake_model_')) {
                    Cache::config('_cake_model_', array_merge((array) $cache['settings'], [
                        'prefix' => $prefix.'cake_model_', 'path' => $path.DS.'models'.DS,
                        'serialize' => true, 'duration' => $duration,
                    ]));
                }
                Cache::config('default');
            }
            App::build();
            if (!include(CONFIGS.'bootstrap.php')) {
                trigger_error(sprintf(__("Can't find application bootstrap file. Please create %sbootstrap.php, and make sure it is readable by PHP.", true), CONFIGS), E_USER_ERROR);
            }
        }
    }
}

/**
 * Class/file loader and path management.
 *
 * @see          http://book.cakephp.org/1.3/en/The-Manual/Developing-with-CakePHP/Configuration.html#the-app-class
 * @since         CakePHP(tm) v 1.2.0.6001
 */
class App extends Object
{
    /**
     * List of object types and their properties.
     *
     * @var array
     */
    public $types = [
        'class' => ['suffix' => '.php', 'extends' => null, 'core' => true],
        'file' => ['suffix' => '.php', 'extends' => null, 'core' => true],
        'model' => ['suffix' => '.php', 'extends' => 'AppModel', 'core' => false],
        'behavior' => ['suffix' => '.php', 'extends' => 'ModelBehavior', 'core' => true],
        'controller' => ['suffix' => '_controller.php', 'extends' => 'AppController', 'core' => true],
        'component' => ['suffix' => '.php', 'extends' => null, 'core' => true],
        'lib' => ['suffix' => '.php', 'extends' => null, 'core' => true],
        'view' => ['suffix' => '.php', 'extends' => null, 'core' => true],
        'helper' => ['suffix' => '.php', 'extends' => 'AppHelper', 'core' => true],
        'vendor' => ['suffix' => '', 'extends' => null, 'core' => true],
        'shell' => ['suffix' => '.php', 'extends' => 'Shell', 'core' => true],
        'plugin' => ['suffix' => '', 'extends' => null, 'core' => true],
    ];

    /**
     * List of additional path(s) where model files reside.
     *
     * @var array
     */
    public $models = [];

    /**
     * List of additional path(s) where behavior files reside.
     *
     * @var array
     */
    public $behaviors = [];

    /**
     * List of additional path(s) where controller files reside.
     *
     * @var array
     */
    public $controllers = [];

    /**
     * List of additional path(s) where component files reside.
     *
     * @var array
     */
    public $components = [];

    /**
     * List of additional path(s) where datasource files reside.
     *
     * @var array
     */
    public $datasources = [];

    /**
     * List of additional path(s) where libs files reside.
     *
     * @var array
     */
    public $libs = [];
    /**
     * List of additional path(s) where view files reside.
     *
     * @var array
     */
    public $views = [];

    /**
     * List of additional path(s) where helper files reside.
     *
     * @var array
     */
    public $helpers = [];

    /**
     * List of additional path(s) where plugins reside.
     *
     * @var array
     */
    public $plugins = [];

    /**
     * List of additional path(s) where vendor packages reside.
     *
     * @var array
     */
    public $vendors = [];

    /**
     * List of additional path(s) where locale files reside.
     *
     * @var array
     */
    public $locales = [];

    /**
     * List of additional path(s) where console shell files reside.
     *
     * @var array
     */
    public $shells = [];

    /**
     * Paths to search for files.
     *
     * @var array
     */
    public $search = [];

    /**
     * Whether or not to return the file that is loaded.
     *
     * @var bool
     */
    public $return = false;

    /**
     * Holds key/value pairs of $type => file path.
     *
     * @var array
     */
    public $__map = [];

    /**
     * Holds paths for deep searching of files.
     *
     * @var array
     */
    public $__paths = [];

    /**
     * Holds loaded files.
     *
     * @var array
     */
    public $__loaded = [];

    /**
     * Holds and key => value array of object types.
     *
     * @var array
     */
    public $__objects = [];

    /**
     * Used to read information stored path.
     *
     * Usage:
     *
     * `App::path('models'); will return all paths for models`
     *
     * @param string $type type of path
     *
     * @return string array
     */
    public function path($type)
    {
        $_this = &self::getInstance();
        if (!isset($_this->{$type})) {
            return [];
        }

        return $_this->{$type};
    }

    /**
     * Build path references. Merges the supplied $paths
     * with the base paths and the default core paths.
     *
     * @param array $paths paths defines in config/bootstrap.php
     * @param bool  $reset true will set paths, false merges paths [default] false
     */
    public function build($paths = [], $reset = false)
    {
        $_this = &self::getInstance();
        $defaults = [
            'models' => [MODELS],
            'behaviors' => [BEHAVIORS],
            'datasources' => [MODELS.'datasources'],
            'controllers' => [CONTROLLERS],
            'components' => [COMPONENTS],
            'libs' => [APPLIBS],
            'views' => [VIEWS],
            'helpers' => [HELPERS],
            'locales' => [APP.'locale'.DS],
            'shells' => [APP.'vendors'.DS.'shells'.DS, VENDORS.'shells'.DS],
            'vendors' => [APP.'vendors'.DS, VENDORS],
            'plugins' => [APP.'plugins'.DS],
        ];

        if (true == $reset) {
            foreach ($paths as $type => $new) {
                $_this->{$type} = (array) $new;
            }

            return $paths;
        }

        $core = $_this->core();
        $app = ['models' => true, 'controllers' => true, 'helpers' => true];

        foreach ($defaults as $type => $default) {
            $merge = [];

            if (isset($app[$type])) {
                $merge = [APP];
            }
            if (isset($core[$type])) {
                $merge = array_merge($merge, (array) $core[$type]);
            }

            if (empty($_this->{$type}) || empty($paths)) {
                $_this->{$type} = $default;
            }

            if (!empty($paths[$type])) {
                $path = array_flip(array_flip(array_merge(
                    (array) $paths[$type], $_this->{$type}, $merge
                )));
                $_this->{$type} = array_values($path);
            } else {
                $path = array_flip(array_flip(array_merge($_this->{$type}, $merge)));
                $_this->{$type} = array_values($path);
            }
        }
    }

    /**
     * Get the path that a plugin is on.  Searches through the defined plugin paths.
     *
     * @param string $plugin camelCased/lower_cased plugin name to find the path of
     *
     * @return string full path to the plugin
     */
    public function pluginPath($plugin)
    {
        $_this = &self::getInstance();
        $pluginDir = Inflector::underscore($plugin);
        for ($i = 0, $length = count($_this->plugins); $i < $length; ++$i) {
            if (is_dir($_this->plugins[$i].$pluginDir)) {
                return $_this->plugins[$i].$pluginDir.DS;
            }
        }

        return $_this->plugins[0].$pluginDir.DS;
    }

    /**
     * Find the path that a theme is on.  Search through the defined theme paths.
     *
     * @param string $theme lower_cased theme name to find the path of
     *
     * @return string full path to the theme
     */
    public function themePath($theme)
    {
        $_this = &self::getInstance();
        $themeDir = 'themed'.DS.Inflector::underscore($theme);
        for ($i = 0, $length = count($_this->views); $i < $length; ++$i) {
            if (is_dir($_this->views[$i].$themeDir)) {
                return $_this->views[$i].$themeDir.DS;
            }
        }

        return $_this->views[0].$themeDir.DS;
    }

    /**
     * Returns a key/value list of all paths where core libs are found.
     * Passing $type only returns the values for a given value of $key.
     *
     * @param string $type valid values are: 'model', 'behavior', 'controller', 'component',
     *                     'view', 'helper', 'datasource', 'libs', and 'cake'
     *
     * @return array numeric keyed array of core lib paths
     */
    public function core($type = null)
    {
        static $paths = false;
        if (false === $paths) {
            $paths = Cache::read('core_paths', '_cake_core_');
        }
        if (!$paths) {
            $paths = [];
            $libs = dirname(__FILE__).DS;
            $cake = dirname($libs).DS;
            $path = dirname($cake).DS;

            $paths['cake'][] = $cake;
            $paths['libs'][] = $libs;
            $paths['models'][] = $libs.'model'.DS;
            $paths['datasources'][] = $libs.'model'.DS.'datasources'.DS;
            $paths['behaviors'][] = $libs.'model'.DS.'behaviors'.DS;
            $paths['controllers'][] = $libs.'controller'.DS;
            $paths['components'][] = $libs.'controller'.DS.'components'.DS;
            $paths['views'][] = $libs.'view'.DS;
            $paths['helpers'][] = $libs.'view'.DS.'helpers'.DS;
            $paths['plugins'][] = $path.'plugins'.DS;
            $paths['vendors'][] = $path.'vendors'.DS;
            $paths['shells'][] = $cake.'console'.DS.'libs'.DS;

            Cache::write('core_paths', array_filter($paths), '_cake_core_');
        }
        if ($type && isset($paths[$type])) {
            return $paths[$type];
        }

        return $paths;
    }

    /**
     * Returns an array of objects of the given type.
     *
     * Example usage:
     *
     * `App::objects('plugin');` returns `array('DebugKit', 'Blog', 'User');`
     *
     * @param string $type  Type of object, i.e. 'model', 'controller', 'helper', or 'plugin'
     * @param mixed  $path  Optional Scan only the path given. If null, paths for the chosen
     *                      type will be used.
     * @param bool   $cache Set to false to rescan objects of the chosen type. Defaults to true.
     *
     * @return mixed Either false on incorrect / miss.  Or an array of found objects.
     */
    public function objects($type, $path = null, $cache = true)
    {
        $objects = [];
        $extension = false;
        $name = $type;

        if ('file' === $type && !$path) {
            return false;
        } elseif ('file' === $type) {
            $extension = true;
            $name = $type.str_replace(DS, '', $path);
        }
        $_this = &self::getInstance();

        if (empty($_this->__objects) && true === $cache) {
            $_this->__objects = Cache::read('object_map', '_cake_core_');
        }

        if (!isset($_this->__objects[$name]) || true !== $cache) {
            $types = $_this->types;

            if (!isset($types[$type])) {
                return false;
            }
            $objects = [];

            if (empty($path)) {
                $path = $_this->{"{$type}s"};
                if (isset($types[$type]['core']) && $types[$type]['core'] === false) {
                    array_pop($path);
                }
            }
            $items = [];

            foreach ((array) $path as $dir) {
                if (APP != $dir) {
                    $items = $_this->__list($dir, $types[$type]['suffix'], $extension);
                    $objects = array_merge($items, array_diff($objects, $items));
                }
            }

            if ('file' !== $type) {
                foreach ($objects as $key => $value) {
                    $objects[$key] = Inflector::camelize($value);
                }
            }

            if (true === $cache) {
                $_this->__resetCache(true);
            }
            $_this->__objects[$name] = $objects;
        }

        return $_this->__objects[$name];
    }

    /**
     * Finds classes based on $name or specific file(s) to search.  Calling App::import() will
     * not construct any classes contained in the files. It will only find and require() the file.
     *
     * @see http://book.cakephp.org/1.3/en/The-Manual/Developing-with-CakePHP/Configuration.html#using-app-import
     *
     * @param mixed  $type    The type of Class if passed as a string, or all params can be passed as
     *                        an single array to $type,
     * @param string $name    Name of the Class or a unique name for the file
     * @param mixed  $parent  boolean true if Class Parent should be searched, accepts key => value
     *                        array('parent' => $parent ,'file' => $file, 'search' => $search, 'ext' => '$ext');
     *                        $ext allows setting the extension of the file name
     *                        based on Inflector::underscore($name) . ".$ext";
     * @param array  $search  paths to search for files, array('path 1', 'path 2', 'path 3');
     * @param string $file    full name of the file to search for including extension
     * @param bool   $return, return the loaded file, the file must have a return
     *                        statement in it to work: return $variable;
     *
     * @return bool true if Class is already in memory or if file is found and loaded, false if not
     */
    public function import($type = null, $name = null, $parent = true, $search = [], $file = null, $return = false)
    {
        $plugin = $directory = null;

        if (is_array($type)) {
            extract($type, EXTR_OVERWRITE);
        }

        if (is_array($parent)) {
            extract($parent, EXTR_OVERWRITE);
        }

        if (null === $name && null === $file) {
            $name = $type;
            $type = 'Core';
        } elseif (null === $name) {
            $type = 'File';
        }

        if (is_array($name)) {
            foreach ($name as $class) {
                $tempType = $type;
                $plugin = null;

                if (false !== strpos($class, '.')) {
                    $value = explode('.', $class);
                    $count = count($value);

                    if ($count > 2) {
                        $tempType = $value[0];
                        $plugin = $value[1].'.';
                        $class = $value[2];
                    } elseif (2 === $count && ('Core' === $type || 'File' === $type)) {
                        $tempType = $value[0];
                        $class = $value[1];
                    } else {
                        $plugin = $value[0].'.';
                        $class = $value[1];
                    }
                }

                if (!self::import($tempType, $plugin.$class, $parent)) {
                    return false;
                }
            }

            return true;
        }

        if (null != $name && false !== strpos($name, '.')) {
            list($plugin, $name) = explode('.', $name);
            $plugin = Inflector::camelize($plugin);
        }
        $_this = &self::getInstance();
        $_this->return = $return;

        if (isset($ext)) {
            $file = Inflector::underscore($name).".{$ext}";
        }
        $ext = $_this->__settings($type, $plugin, $parent);
        if (null != $name && !class_exists($name.$ext['class'])) {
            if ($load = $_this->__mapped($name.$ext['class'], $type, $plugin)) {
                if ($_this->__load($load)) {
                    $_this->__overload($type, $name.$ext['class'], $parent);

                    if ($_this->return) {
                        return include $load;
                    }

                    return true;
                } else {
                    $_this->__remove($name.$ext['class'], $type, $plugin);
                    $_this->__resetCache(true);
                }
            }
            if (!empty($search)) {
                $_this->search = $search;
            } elseif ($plugin) {
                $_this->search = $_this->__paths('plugin');
            } else {
                $_this->search = $_this->__paths($type);
            }
            $find = $file;

            if (null === $find) {
                $find = Inflector::underscore($name.$ext['suffix']).'.php';

                if ($plugin) {
                    $paths = $_this->search;
                    foreach ($paths as $key => $value) {
                        $_this->search[$key] = $value.$ext['path'];
                    }
                }
            }

            if ('vendor' !== strtolower($type) && empty($search) && $_this->__load($file)) {
                $directory = false;
            } else {
                $file = $find;
                $directory = $_this->__find($find, true);
            }

            if (null !== $directory) {
                $_this->__resetCache(true);
                $_this->__map($directory.$file, $name.$ext['class'], $type, $plugin);
                $_this->__overload($type, $name.$ext['class'], $parent);

                if ($_this->return) {
                    return include $directory.$file;
                }

                return true;
            }

            return false;
        }

        return true;
    }

    /**
     * Returns a single instance of App.
     *
     * @return object
     */
    public function &getInstance()
    {
        static $instance = [];
        if (!$instance) {
            $instance[0] = new self();
            $instance[0]->__map = (array) Cache::read('file_map', '_cake_core_');
        }

        return $instance[0];
    }

    /**
     * Locates the $file in $__paths, searches recursively.
     *
     * @param string $file      full file name
     * @param bool   $recursive search $__paths recursively
     *
     * @return mixed boolean on fail, $file directory path on success
     */
    public function __find($file, $recursive = true)
    {
        static $appPath = false;

        if (empty($this->search)) {
            return null;
        } elseif (is_string($this->search)) {
            $this->search = [$this->search];
        }

        if (empty($this->__paths)) {
            $this->__paths = Cache::read('dir_map', '_cake_core_');
        }

        foreach ($this->search as $path) {
            if (false === $appPath) {
                $appPath = rtrim(APP, DS);
            }
            $path = rtrim($path, DS);

            if ($path === $appPath) {
                $recursive = false;
            }
            if (false === $recursive) {
                if ($this->__load($path.DS.$file)) {
                    return $path.DS;
                }
                continue;
            }

            if (!isset($this->__paths[$path])) {
                if (!class_exists('Folder')) {
                    require LIBS.'folder.php';
                }
                $Folder = new Folder();
                $ignorePaths = ['.svn', '.git', 'CVS', 'tests', 'templates', 'node_modules'];
                $directories = $Folder->tree($path, $ignorePaths, 'dir');
                sort($directories);
                $this->__paths[$path] = $directories;
            }

            foreach ($this->__paths[$path] as $directory) {
                if ($this->__load($directory.DS.$file)) {
                    return $directory.DS;
                }
            }
        }

        return null;
    }

    /**
     * Attempts to load $file.
     *
     * @param string $file full path to file including file name
     *
     * @return bool
     */
    public function __load($file)
    {
        if (empty($file)) {
            return false;
        }
        if (!$this->return && isset($this->__loaded[$file])) {
            return true;
        }
        if (file_exists($file)) {
            if (!$this->return) {
                $this->__loaded[$file];
                require($file);
            }

            return true;
        }

        return false;
    }

    /**
     * Maps the $name to the $file.
     *
     * @param string $file   full path to file
     * @param string $name   unique name for this map
     * @param string $type   type object being mapped
     * @param string $plugin camelized if object is from a plugin, the name of the plugin
     */
    public function __map($file, $name, $type, $plugin)
    {
        if ($plugin) {
            $this->__map['Plugin'][$plugin][$type][$name] = $file;
        } else {
            $this->__map[$type][$name] = $file;
        }
    }

    /**
     * Returns a file's complete path.
     *
     * @param string $name   unique name
     * @param string $type   type object
     * @param string $plugin camelized if object is from a plugin, the name of the plugin
     *
     * @return mixed, file path if found, false otherwise
     */
    public function __mapped($name, $type, $plugin)
    {
        if ($plugin) {
            if (isset($this->__map['Plugin'][$plugin][$type]) && isset($this->__map['Plugin'][$plugin][$type][$name])) {
                return $this->__map['Plugin'][$plugin][$type][$name];
            }

            return false;
        }

        if (isset($this->__map[$type]) && isset($this->__map[$type][$name])) {
            return $this->__map[$type][$name];
        }

        return false;
    }

    /**
     * Used to overload objects as needed.
     *
     * @param string $type Model or Helper
     * @param string $name Class name to overload
     */
    public function __overload($type, $name, $parent)
    {
        if (('Model' === $type || 'Helper' === $type) && false !== $parent) {
            Overloadable::overload($name);
        }
    }

    /**
     * Loads parent classes based on $type.
     * Returns a prefix or suffix needed for loading files.
     *
     * @param string $type   type of object
     * @param string $plugin camelized name of plugin
     * @param bool   $parent false will not attempt to load parent
     *
     * @return array
     */
    public function __settings($type, $plugin, $parent)
    {
        if (!$parent) {
            return ['class' => null, 'suffix' => null, 'path' => null];
        }

        if ($plugin) {
            $pluginPath = Inflector::underscore($plugin);
        }
        $path = null;
        $load = strtolower($type);

        switch ($load) {
            case 'model':
                if (!class_exists('Model')) {
                    require LIBS.'model'.DS.'model.php';
                }
                if (!class_exists('AppModel')) {
                    self::import($type, 'AppModel', false);
                }
                if ($plugin) {
                    if (!class_exists($plugin.'AppModel')) {
                        self::import($type, $plugin.'.'.$plugin.'AppModel', false, [], $pluginPath.DS.$pluginPath.'_app_model.php');
                    }
                    $path = $pluginPath.DS.'models'.DS;
                }

                return ['class' => null, 'suffix' => null, 'path' => $path];
            break;
            case 'behavior':
                if ($plugin) {
                    $path = $pluginPath.DS.'models'.DS.'behaviors'.DS;
                }

                return ['class' => $type, 'suffix' => null, 'path' => $path];
            break;
            case 'datasource':
                if ($plugin) {
                    $path = $pluginPath.DS.'models'.DS.'datasources'.DS;
                }

                return ['class' => $type, 'suffix' => null, 'path' => $path];
            case 'controller':
                self::import($type, 'AppController', false);
                if ($plugin) {
                    self::import($type, $plugin.'.'.$plugin.'AppController', false, [], $pluginPath.DS.$pluginPath.'_app_controller.php');
                    $path = $pluginPath.DS.'controllers'.DS;
                }

                return ['class' => $type, 'suffix' => $type, 'path' => $path];
            break;
            case 'component':
                if ($plugin) {
                    $path = $pluginPath.DS.'controllers'.DS.'components'.DS;
                }

                return ['class' => $type, 'suffix' => null, 'path' => $path];
            break;
            case 'lib':
                if ($plugin) {
                    $path = $pluginPath.DS.'libs'.DS;
                }

                return ['class' => null, 'suffix' => null, 'path' => $path];
            break;
            case 'view':
                if ($plugin) {
                    $path = $pluginPath.DS.'views'.DS;
                }

                return ['class' => $type, 'suffix' => null, 'path' => $path];
            break;
            case 'helper':
                if (!class_exists('AppHelper')) {
                    self::import($type, 'AppHelper', false);
                }
                if ($plugin) {
                    $path = $pluginPath.DS.'views'.DS.'helpers'.DS;
                }

                return ['class' => $type, 'suffix' => null, 'path' => $path];
            break;
            case 'vendor':
                if ($plugin) {
                    $path = $pluginPath.DS.'vendors'.DS;
                }

                return ['class' => null, 'suffix' => null, 'path' => $path];
            break;
            default:
                $type = $suffix = $path = null;
            break;
        }

        return ['class' => null, 'suffix' => null, 'path' => null];
    }

    /**
     * Returns default search paths.
     *
     * @param string $type type of object to be searched
     *
     * @return array list of paths
     */
    public function __paths($type)
    {
        $type = strtolower($type);
        $paths = [];

        if ('core' === $type) {
            return self::core('libs');
        }
        if (isset($this->{$type.'s'})) {
            return $this->{$type.'s'};
        }

        return $paths;
    }

    /**
     * Removes file location from map if the file has been deleted.
     *
     * @param string $name   name of object
     * @param string $type   type of object
     * @param string $plugin camelized name of plugin
     */
    public function __remove($name, $type, $plugin)
    {
        if ($plugin) {
            unset($this->__map['Plugin'][$plugin][$type][$name]);
        } else {
            unset($this->__map[$type][$name]);
        }
    }

    /**
     * Returns an array of filenames of PHP files in the given directory.
     *
     * @param string $path   Path to scan for files
     * @param string $suffix if false, return only directories. if string, match and return files
     *
     * @return array List of directories or files in directory
     */
    public function __list($path, $suffix = false, $extension = false)
    {
        if (!class_exists('Folder')) {
            require LIBS.'folder.php';
        }
        $items = [];
        $Folder = new Folder($path);
        $contents = $Folder->read(false, true);

        if (is_array($contents)) {
            if (!$suffix) {
                return $contents[0];
            } else {
                foreach ($contents[1] as $item) {
                    if (substr($item, -strlen($suffix)) === $suffix) {
                        if ($extension) {
                            $items[] = $item;
                        } else {
                            $items[] = substr($item, 0, strlen($item) - strlen($suffix));
                        }
                    }
                }
            }
        }

        return $items;
    }

    /**
     * Determines if $__maps, $__objects and $__paths cache should be reset.
     *
     * @param bool $reset
     *
     * @return bool
     */
    public function __resetCache($reset = null)
    {
        static $cache = [];
        if (!$cache && true === $reset) {
            $cache = true;
        }

        return $cache;
    }

    /**
     * Object destructor.
     *
     * Writes cache file if changes have been made to the $__map or $__paths
     */
    public function __destruct()
    {
        if (true === $this->__resetCache()) {
            $core = self::core('cake');
            unset($this->__paths[rtrim($core[0], DS)]);
            Cache::write('dir_map', array_filter($this->__paths), '_cake_core_');
            Cache::write('file_map', array_filter($this->__map), '_cake_core_');
            Cache::write('object_map', $this->__objects, '_cake_core_');
        }
    }
}
