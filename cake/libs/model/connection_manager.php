<?php
/**
 * Datasource connection manager.
 *
 * Provides an interface for loading and enumerating connections defined in app/config/database.php
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
 * @since         CakePHP(tm) v 0.10.x.1402
 *
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
require LIBS.'model'.DS.'datasources'.DS.'datasource.php';
include_once CONFIGS.'database.php';

/**
 * Manages loaded instances of DataSource objects.
 */
class ConnectionManager extends Object
{
    /**
     * Holds a loaded instance of the Connections object.
     *
     * @var DATABASE_CONFIG
     */
    public $config = null;

    /**
     * Holds instances DataSource objects.
     *
     * @var array
     */
    public $_dataSources = [];

    /**
     * Contains a list of all file and class names used in Connection settings.
     *
     * @var array
     */
    public $_connectionsEnum = [];

    /**
     * Constructor.
     */
    public function __construct()
    {
        if (class_exists('DATABASE_CONFIG')) {
            $this->config = new DATABASE_CONFIG();
            $this->_getConnectionObjects();
        }
    }

    /**
     * Gets a reference to the ConnectionManger object instance.
     *
     * @return object Instance
     * @static
     */
    public function &getInstance()
    {
        static $instance = [];

        if (!$instance) {
            $instance[0] = new self();
        }

        return $instance[0];
    }

    /**
     * Gets a reference to a DataSource object.
     *
     * @param string $name The name of the DataSource, as defined in app/config/database.php
     *
     * @return object Instance
     * @static
     */
    public function &getDataSource($name)
    {
        $_this = &self::getInstance();

        if (!empty($_this->_dataSources[$name])) {
            $return = &$_this->_dataSources[$name];

            return $return;
        }

        if (empty($_this->_connectionsEnum[$name])) {
            trigger_error(sprintf(__('ConnectionManager::getDataSource - Non-existent data source %s', true), $name), E_USER_ERROR);
            $null = null;

            return $null;
        }
        $conn = $_this->_connectionsEnum[$name];
        $class = $conn['classname'];

        if (null === $_this->loadDataSource($name)) {
            trigger_error(sprintf(__('ConnectionManager::getDataSource - Could not load class %s', true), $class), E_USER_ERROR);
            $null = null;

            return $null;
        }
        $_this->_dataSources[$name] = new $class($_this->config->{$name});
        $_this->_dataSources[$name]->configKeyName = $name;

        $return = &$_this->_dataSources[$name];

        return $return;
    }

    /**
     * Gets the list of available DataSource connections.
     *
     * @return array List of available connections
     * @static
     */
    public function sourceList()
    {
        $_this = &self::getInstance();

        return array_keys($_this->_dataSources);
    }

    /**
     * Gets a DataSource name from an object reference.
     *
     * **Warning** this method may cause fatal errors in PHP4.
     *
     * @param object $source DataSource object
     *
     * @return string datasource name, or null if source is not present
     *                in the ConnectionManager
     * @static
     */
    public function getSourceName(&$source)
    {
        $_this = &self::getInstance();
        foreach ($_this->_dataSources as $name => $ds) {
            if ($ds == $source) {
                return $name;
            }
        }

        return '';
    }

    /**
     * Loads the DataSource class for the given connection name.
     *
     * @param mixed $connName A string name of the connection, as defined in app/config/database.php,
     *                        or an array containing the filename (without extension) and class name of the object,
     *                        to be found in app/models/datasources/ or cake/libs/model/datasources/.
     *
     * @return bool True on success, null on failure or false if the class is already loaded
     * @static
     */
    public function loadDataSource($connName)
    {
        $_this = &self::getInstance();

        if (is_array($connName)) {
            $conn = $connName;
        } else {
            $conn = $_this->_connectionsEnum[$connName];
        }

        if (class_exists($conn['classname'])) {
            return false;
        }

        if (!empty($conn['parent'])) {
            $_this->loadDataSource($conn['parent']);
        }

        $conn = array_merge(['plugin' => null, 'classname' => null, 'parent' => null], $conn);
        $class = "{$conn['plugin']}.{$conn['classname']}";

        if (!App::import('Datasource', $class, !is_null($conn['plugin']))) {
            trigger_error(sprintf(__('ConnectionManager::loadDataSource - Unable to import DataSource class %s', true), $class), E_USER_ERROR);

            return null;
        }

        return true;
    }

    /**
     * Return a list of connections.
     *
     * @return array an associative array of elements where the key is the connection name
     *               (as defined in Connections), and the value is an array with keys 'filename' and 'classname'
     * @static
     */
    public function enumConnectionObjects()
    {
        $_this = &self::getInstance();

        return $_this->_connectionsEnum;
    }

    /**
     * Dynamically creates a DataSource object at runtime, with the given name and settings.
     *
     * @param string $name   The DataSource name
     * @param array  $config The DataSource configuration settings
     *
     * @return object A reference to the DataSource object, or null if creation failed
     * @static
     */
    public function &create($name = '', $config = [])
    {
        $_this = &self::getInstance();

        if (empty($name) || empty($config) || array_key_exists($name, $_this->_connectionsEnum)) {
            $null = null;

            return $null;
        }
        $_this->config->{$name} = $config;
        $_this->_connectionsEnum[$name] = $_this->__connectionData($config);
        $return = &$_this->getDataSource($name);

        return $return;
    }

    /**
     * Gets a list of class and file names associated with the user-defined DataSource connections.
     *
     * @static
     */
    public function _getConnectionObjects()
    {
        $connections = get_object_vars($this->config);

        if (null != $connections) {
            foreach ($connections as $name => $config) {
                $this->_connectionsEnum[$name] = $this->__connectionData($config);
            }
        } else {
            $this->cakeError('missingConnection', [['code' => 500, 'className' => 'ConnectionManager']]);
        }
    }

    /**
     * Returns the file, class name, and parent for the given driver.
     *
     * @return array An indexed array with: filename, classname, plugin and parent
     */
    public function __connectionData($config)
    {
        if (!isset($config['datasource'])) {
            $config['datasource'] = 'dbo';
        }
        $filename = $classname = $parent = $plugin = null;

        if (!empty($config['driver'])) {
            $parent = $this->__connectionData(['datasource' => $config['datasource']]);
            $parentSource = preg_replace('/_source$/', '', $parent['filename']);

            list($plugin, $classname) = pluginSplit($config['driver']);
            if ($plugin) {
                $source = Inflector::underscore($classname);
            } else {
                $source = $parentSource.'_'.$config['driver'];
                $classname = Inflector::camelize(strtolower($source));
            }
            $filename = $parentSource.DS.$source;
        } else {
            list($plugin, $classname) = pluginSplit($config['datasource']);
            if ($plugin) {
                $filename = Inflector::underscore($classname);
            } else {
                $filename = Inflector::underscore($config['datasource']);
            }
            if ('_source' != substr($filename, -7)) {
                $filename .= '_source';
            }
            $classname = Inflector::camelize(strtolower($filename));
        }

        return compact('filename', 'classname', 'parent', 'plugin');
    }

    /**
     * Destructor.
     */
    public function __destruct()
    {
        if ('database' == Configure::read('Session.save') && function_exists('session_write_close')) {
            session_write_close();
        }
    }
}
