<?php
/**
 * Base class for Shells.
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
 * @since         CakePHP(tm) v 1.2.0.5012
 *
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * Base class for command-line utilities for automating programmer chores.
 */
class Shell extends Object
{
    /**
     * An instance of the ShellDispatcher object that loaded this script.
     *
     * @var ShellDispatcher
     */
    public $Dispatch = null;

    /**
     * If true, the script will ask for permission to perform actions.
     *
     * @var bool
     */
    public $interactive = true;

    /**
     * Holds the DATABASE_CONFIG object for the app. Null if database.php could not be found,
     * or the app does not exist.
     *
     * @var DATABASE_CONFIG
     */
    public $DbConfig = null;

    /**
     * Contains command switches parsed from the command line.
     *
     * @var array
     */
    public $params = [];

    /**
     * Contains arguments parsed from the command line.
     *
     * @var array
     */
    public $args = [];

    /**
     * The file name of the shell that was invoked.
     *
     * @var string
     */
    public $shell = null;

    /**
     * The class name of the shell that was invoked.
     *
     * @var string
     */
    public $className = null;

    /**
     * The command called if public methods are available.
     *
     * @var string
     */
    public $command = null;

    /**
     * The name of the shell in camelized.
     *
     * @var string
     */
    public $name = null;

    /**
     * An alias for the shell.
     *
     * @var string
     */
    public $alias = null;

    /**
     * Contains tasks to load and instantiate.
     *
     * @var array
     */
    public $tasks = [];

    /**
     * Contains the loaded tasks.
     *
     * @var array
     */
    public $taskNames = [];

    /**
     * Contains models to load and instantiate.
     *
     * @var array
     */
    public $uses = [];

    /**
     *  Constructs this Shell instance.
     */
    public function __construct(&$dispatch)
    {
        $vars = ['params', 'args', 'shell', 'shellCommand' => 'command'];

        foreach ($vars as $key => $var) {
            if (is_string($key)) {
                $this->{$var} = &$dispatch->{$key};
            } else {
                $this->{$var} = &$dispatch->{$var};
            }
        }

        if (null == $this->name) {
            $this->name = get_class($this);
        }

        if (null == $this->alias) {
            $this->alias = $this->name;
        }

        ClassRegistry::addObject($this->name, $this);
        ClassRegistry::map($this->name, $this->alias);

        if (!PHP5 && isset($this->args[0])) {
            if (false !== strpos($this->name, strtolower(Inflector::camelize($this->args[0])))) {
                $dispatch->shiftArgs();
            }
            if (strtolower($this->command) == strtolower(Inflector::variable($this->args[0])) && method_exists($this, $this->command)) {
                $dispatch->shiftArgs();
            }
        }

        $this->Dispatch = &$dispatch;
    }

    /**
     * Initializes the Shell
     * acts as constructor for subclasses
     * allows configuration of tasks prior to shell execution.
     */
    public function initialize()
    {
        $this->_loadModels();
    }

    /**
     * Starts up the Shell
     * allows for checking and configuring prior to command or main execution
     * can be overriden in subclasses.
     */
    public function startup()
    {
        $this->_welcome();
    }

    /**
     * Displays a header for the shell.
     */
    public function _welcome()
    {
        $this->Dispatch->clear();
        $this->out();
        $this->out('Welcome to CakePHP v'.Configure::version().' Console');
        $this->hr();
        $this->out('App : '.$this->params['app']);
        $this->out('Path: '.$this->params['working']);
        $this->hr();
    }

    /**
     * Loads database file and constructs DATABASE_CONFIG class
     * makes $this->DbConfig available to subclasses.
     *
     * @return bool
     */
    public function _loadDbConfig()
    {
        if (config('database') && class_exists('DATABASE_CONFIG')) {
            $this->DbConfig = new DATABASE_CONFIG();

            return true;
        }
        $this->err('Database config could not be loaded.');
        $this->out('Run `bake` to create the database configuration.');

        return false;
    }

    /**
     * if var $uses = true
     * Loads AppModel file and constructs AppModel class
     * makes $this->AppModel available to subclasses
     * if var $uses is an array of models will load those models.
     *
     * @return bool
     */
    public function _loadModels()
    {
        if (null === $this->uses || false === $this->uses) {
            return;
        }

        if (true === $this->uses && App::import('Model', 'AppModel')) {
            $this->AppModel = new AppModel(false, false, false);

            return true;
        }

        if (true !== $this->uses && !empty($this->uses)) {
            $uses = is_array($this->uses) ? $this->uses : [$this->uses];

            $modelClassName = $uses[0];
            if (false !== strpos($uses[0], '.')) {
                list($plugin, $modelClassName) = explode('.', $uses[0]);
            }
            $this->modelClass = $modelClassName;

            foreach ($uses as $modelClass) {
                list($plugin, $modelClass) = pluginSplit($modelClass, true);
                if (PHP5) {
                    $this->{$modelClass} = ClassRegistry::init($plugin.$modelClass);
                } else {
                    $this->{$modelClass} = &ClassRegistry::init($plugin.$modelClass);
                }
            }

            return true;
        }

        return false;
    }

    /**
     * Loads tasks defined in var $tasks.
     *
     * @return bool
     */
    public function loadTasks()
    {
        if (null === $this->tasks || false === $this->tasks || true === $this->tasks || empty($this->tasks)) {
            return true;
        }

        $tasks = $this->tasks;
        if (!is_array($tasks)) {
            $tasks = [$tasks];
        }

        foreach ($tasks as $taskName) {
            $task = Inflector::underscore($taskName);
            $taskClass = Inflector::camelize($taskName.'Task');

            if (!class_exists($taskClass)) {
                foreach ($this->Dispatch->shellPaths as $path) {
                    $taskPath = $path.'tasks'.DS.$task.'.php';
                    if (file_exists($taskPath)) {
                        require_once $taskPath;
                        break;
                    }
                }
            }
            $taskClassCheck = $taskClass;
            if (!PHP5) {
                $taskClassCheck = strtolower($taskClass);
            }
            if (ClassRegistry::isKeySet($taskClassCheck)) {
                $this->taskNames[] = $taskName;
                if (!PHP5) {
                    $this->{$taskName} = &ClassRegistry::getObject($taskClassCheck);
                } else {
                    $this->{$taskName} = ClassRegistry::getObject($taskClassCheck);
                }
            } else {
                $this->taskNames[] = $taskName;
                if (!PHP5) {
                    $this->{$taskName} = new $taskClass($this->Dispatch);
                } else {
                    $this->{$taskName} = new $taskClass($this->Dispatch);
                }
            }

            if (!isset($this->{$taskName})) {
                $this->err("Task `{$taskName}` could not be loaded");
                $this->_stop();
            }
        }

        return true;
    }

    /**
     * Prompts the user for input, and returns it.
     *
     * @param string $prompt  prompt text
     * @param mixed  $options array or string of options
     * @param string $default default input value
     *
     * @return Either the default value, or the user-provided input
     */
    public function in($prompt, $options = null, $default = null)
    {
        if (!$this->interactive) {
            return $default;
        }
        $in = $this->Dispatch->getInput($prompt, $options, $default);

        if ($options && is_string($options)) {
            if (strpos($options, ',')) {
                $options = explode(',', $options);
            } elseif (strpos($options, '/')) {
                $options = explode('/', $options);
            } else {
                $options = [$options];
            }
        }
        if (is_array($options)) {
            while ('' === $in || ('' !== $in && (!in_array(strtolower($in), $options) && !in_array(strtoupper($in), $options)) && !in_array($in, $options))) {
                $in = $this->Dispatch->getInput($prompt, $options, $default);
            }
        }

        return $in;
    }

    /**
     * Outputs a single or multiple messages to stdout. If no parameters
     * are passed outputs just a newline.
     *
     * @param mixed $message  A string or a an array of strings to output
     * @param int   $newlines Number of newlines to append
     *
     * @return int returns the number of bytes returned from writing to stdout
     */
    public function out($message = null, $newlines = 1)
    {
        if (is_array($message)) {
            $message = implode($this->nl(), $message);
        }

        return $this->Dispatch->stdout($message.$this->nl($newlines), false);
    }

    /**
     * Outputs a single or multiple error messages to stderr. If no parameters
     * are passed outputs just a newline.
     *
     * @param mixed $message  A string or a an array of strings to output
     * @param int   $newlines Number of newlines to append
     */
    public function err($message = null, $newlines = 1)
    {
        if (is_array($message)) {
            $message = implode($this->nl(), $message);
        }
        $this->Dispatch->stderr($message.$this->nl($newlines));
    }

    /**
     * Returns a single or multiple linefeeds sequences.
     *
     * @param int $multiplier Number of times the linefeed sequence should be repeated
     *
     * @return string
     */
    public function nl($multiplier = 1)
    {
        return str_repeat("\n", $multiplier);
    }

    /**
     * Outputs a series of minus characters to the standard output, acts as a visual separator.
     *
     * @param int $newlines Number of newlines to pre- and append
     */
    public function hr($newlines = 0)
    {
        $this->out(null, $newlines);
        $this->out('---------------------------------------------------------------');
        $this->out(null, $newlines);
    }

    /**
     * Displays a formatted error message
     * and exits the application with status code 1.
     *
     * @param string $title   Title of the error
     * @param string $message An optional error message
     */
    public function error($title, $message = null)
    {
        $this->err(sprintf(__('Error: %s', true), $title));

        if (!empty($message)) {
            $this->err($message);
        }
        $this->_stop(1);
    }

    /**
     * Will check the number args matches otherwise throw an error.
     *
     * @param int    $expectedNum Expected number of paramters
     * @param string $command     Command
     */
    public function _checkArgs($expectedNum, $command = null)
    {
        if (!$command) {
            $command = $this->command;
        }
        if (count($this->args) < $expectedNum) {
            $message[] = 'Got: '.count($this->args);
            $message[] = "Expected: {$expectedNum}";
            $message[] = "Please type `cake {$this->shell} help` for help";
            $message[] = "on usage of the {$this->name} {$command}.";
            $this->error('Wrong number of parameters', $message);
        }
    }

    /**
     * Creates a file at given path.
     *
     * @param string $path     where to put the file
     * @param string $contents content to put in the file
     *
     * @return bool Success
     */
    public function createFile($path, $contents)
    {
        $path = str_replace(DS.DS, DS, $path);

        $this->out();
        $this->out(sprintf(__('Creating file %s', true), $path));

        if (is_file($path) && true === $this->interactive) {
            $prompt = sprintf(__('File `%s` exists, overwrite?', true), $path);
            $key = $this->in($prompt, ['y', 'n', 'q'], 'n');

            if ('q' == strtolower($key)) {
                $this->out(__('Quitting.', true), 2);
                $this->_stop();
            } elseif ('y' != strtolower($key)) {
                $this->out(sprintf(__('Skip `%s`', true), $path), 2);

                return false;
            }
        }
        if (!class_exists('File')) {
            require LIBS.'file.php';
        }

        if ($File = new File($path, true)) {
            $data = $File->prepare($contents);
            $File->write($data);
            $this->out(sprintf(__('Wrote `%s`', true), $path));

            return true;
        } else {
            $this->err(sprintf(__('Could not write to `%s`.', true), $path), 2);

            return false;
        }
    }

    /**
     * Outputs usage text on the standard output. Implement it in subclasses.
     */
    public function help()
    {
        if (null != $this->command) {
            $this->err("Unknown {$this->name} command `{$this->command}`.");
            $this->err("For usage, try `cake {$this->shell} help`.", 2);
        } else {
            $this->Dispatch->help();
        }
    }

    /**
     * Action to create a Unit Test.
     *
     * @return bool Success
     */
    public function _checkUnitTest()
    {
        if (App::import('vendor', 'simpletest'.DS.'simpletest')) {
            return true;
        }
        $prompt = 'SimpleTest is not installed. Do you want to bake unit test files anyway?';
        $unitTest = $this->in($prompt, ['y', 'n'], 'y');
        $result = 'y' == strtolower($unitTest) || 'yes' == strtolower($unitTest);

        if ($result) {
            $this->out();
            $this->out('You can download SimpleTest from http://simpletest.org');
        }

        return $result;
    }

    /**
     * Makes absolute file path easier to read.
     *
     * @param string $file Absolute file path
     *
     * @return sting short path
     */
    public function shortPath($file)
    {
        $shortPath = str_replace(ROOT, null, $file);
        $shortPath = str_replace('..'.DS, '', $shortPath);

        return str_replace(DS.DS, DS, $shortPath);
    }

    /**
     * Creates the proper controller path for the specified controller class name.
     *
     * @param string $name Controller class name
     *
     * @return string Path to controller
     */
    public function _controllerPath($name)
    {
        return strtolower(Inflector::underscore($name));
    }

    /**
     * Creates the proper controller plural name for the specified controller class name.
     *
     * @param string $name Controller class name
     *
     * @return string Controller plural name
     */
    public function _controllerName($name)
    {
        return Inflector::pluralize(Inflector::camelize($name));
    }

    /**
     * Creates the proper controller camelized name (singularized) for the specified name.
     *
     * @param string $name Name
     *
     * @return string Camelized and singularized controller name
     */
    public function _modelName($name)
    {
        return Inflector::camelize(Inflector::singularize($name));
    }

    /**
     * Creates the proper underscored model key for associations.
     *
     * @param string $name Model class name
     *
     * @return string Singular model key
     */
    public function _modelKey($name)
    {
        return Inflector::underscore($name).'_id';
    }

    /**
     * Creates the proper model name from a foreign key.
     *
     * @param string $key Foreign key
     *
     * @return string Model name
     */
    public function _modelNameFromKey($key)
    {
        return Inflector::camelize(str_replace('_id', '', $key));
    }

    /**
     * creates the singular name for use in views.
     *
     * @param string $name
     *
     * @return string $name
     */
    public function _singularName($name)
    {
        return Inflector::variable(Inflector::singularize($name));
    }

    /**
     * Creates the plural name for views.
     *
     * @param string $name Name to use
     *
     * @return string Plural name for views
     */
    public function _pluralName($name)
    {
        return Inflector::variable(Inflector::pluralize($name));
    }

    /**
     * Creates the singular human name used in views.
     *
     * @param string $name Controller name
     *
     * @return string Singular human name
     */
    public function _singularHumanName($name)
    {
        return Inflector::humanize(Inflector::underscore(Inflector::singularize($name)));
    }

    /**
     * Creates the plural human name used in views.
     *
     * @param string $name Controller name
     *
     * @return string Plural human name
     */
    public function _pluralHumanName($name)
    {
        return Inflector::humanize(Inflector::underscore($name));
    }

    /**
     * Find the correct path for a plugin. Scans $pluginPaths for the plugin you want.
     *
     * @param string $pluginName Name of the plugin you want ie. DebugKit
     *
     * @return string $path path to the correct plugin
     */
    public function _pluginPath($pluginName)
    {
        return App::pluginPath($pluginName);
    }
}
