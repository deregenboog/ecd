<?php
/**
 * DataSource base class.
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
 * @since         CakePHP(tm) v 0.10.5.1790
 *
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * DataSource base class.
 */
class DataSource extends Object
{
    /**
     * Are we connected to the DataSource?
     *
     * @var bool
     */
    public $connected = false;

    /**
     * Print full query debug info?
     *
     * @var bool
     */
    public $fullDebug = false;

    /**
     * Error description of last query.
     *
     * @var unknown_type
     */
    public $error = null;

    /**
     * String to hold how many rows were affected by the last SQL operation.
     *
     * @var string
     */
    public $affected = null;

    /**
     * Number of rows in current resultset.
     *
     * @var int
     */
    public $numRows = null;

    /**
     * Time the last query took.
     *
     * @var int
     */
    public $took = null;

    /**
     * The starting character that this DataSource uses for quoted identifiers.
     *
     * @var string
     */
    public $startQuote = null;

    /**
     * The ending character that this DataSource uses for quoted identifiers.
     *
     * @var string
     */
    public $endQuote = null;

    /**
     * Result.
     *
     * @var array
     */
    public $_result = null;

    /**
     * Queries count.
     *
     * @var int
     */
    public $_queriesCnt = 0;

    /**
     * Total duration of all queries.
     *
     * @var unknown_type
     */
    public $_queriesTime = null;

    /**
     * Log of queries executed by this DataSource.
     *
     * @var unknown_type
     */
    public $_queriesLog = [];

    /**
     * Maximum number of items in query log.
     *
     * This is to prevent query log taking over too much memory.
     *
     * @var int maximum number of queries in the queries log
     */
    public $_queriesLogMax = 200;

    /**
     * Caches serialzed results of executed queries.
     *
     * @var array maximum number of queries in the queries log
     */
    public $_queryCache = [];

    /**
     * The default configuration of a specific DataSource.
     *
     * @var array
     */
    public $_baseConfig = [];

    /**
     * Holds references to descriptions loaded by the DataSource.
     *
     * @var array
     */
    public $__descriptions = [];

    /**
     * Holds a list of sources (tables) contained in the DataSource.
     *
     * @var array
     */
    public $_sources = null;

    /**
     * A reference to the physical connection of this DataSource.
     *
     * @var array
     */
    public $connection = null;

    /**
     * The DataSource configuration.
     *
     * @var array
     */
    public $config = [];

    /**
     * The DataSource configuration key name.
     *
     * @var string
     */
    public $configKeyName = null;

    /**
     * Whether or not this DataSource is in the middle of a transaction.
     *
     * @var bool
     */
    public $_transactionStarted = false;

    /**
     * Whether or not source data like available tables and schema descriptions
     * should be cached.
     *
     * @var bool
     */
    public $cacheSources = true;

    /**
     * Constructor.
     *
     * @param array $config array of configuration information for the datasource
     */
    public function __construct($config = [])
    {
        parent::__construct();
        $this->setConfig($config);
    }

    /**
     * Caches/returns cached results for child instances.
     *
     * @param mixed $data
     *
     * @return array array of sources available in this datasource
     */
    public function listSources($data = null)
    {
        if (false === $this->cacheSources) {
            return null;
        }

        if (null !== $this->_sources) {
            return $this->_sources;
        }

        $key = ConnectionManager::getSourceName($this).'_'.$this->config['database'].'_list';
        $key = preg_replace('/[^A-Za-z0-9_\-.+]/', '_', $key);
        $sources = Cache::read($key, '_cake_model_');

        if (empty($sources)) {
            $sources = $data;
            Cache::write($key, $data, '_cake_model_');
        }

        $this->_sources = $sources;

        return $sources;
    }

    /**
     * Convenience method for DboSource::listSources().  Returns source names in lowercase.
     *
     * @param bool $reset whether or not the source list should be reset
     *
     * @return array Array of sources available in this datasource
     */
    public function sources($reset = false)
    {
        if (true === $reset) {
            $this->_sources = null;
        }

        return array_map('strtolower', $this->listSources());
    }

    /**
     * Returns a Model description (metadata) or null if none found.
     *
     * @param Model $model
     *
     * @return array Array of Metadata for the $model
     */
    public function describe(&$model)
    {
        if (false === $this->cacheSources) {
            return null;
        }
        $table = $model->tablePrefix.$model->table;

        if (isset($this->__descriptions[$table])) {
            return $this->__descriptions[$table];
        }
        $cache = $this->__cacheDescription($table);

        if (null !== $cache) {
            $this->__descriptions[$table] = &$cache;

            return $cache;
        }

        return null;
    }

    /**
     * Begin a transaction.
     *
     * @return bool Returns true if a transaction is not in progress
     */
    public function begin(&$model)
    {
        return !$this->_transactionStarted;
    }

    /**
     * Commit a transaction.
     *
     * @return bool Returns true if a transaction is in progress
     */
    public function commit(&$model)
    {
        return $this->_transactionStarted;
    }

    /**
     * Rollback a transaction.
     *
     * @return bool Returns true if a transaction is in progress
     */
    public function rollback(&$model)
    {
        return $this->_transactionStarted;
    }

    /**
     * Converts column types to basic types.
     *
     * @param string $real Real  column type (i.e. "varchar(255)")
     *
     * @return string Abstract column type (i.e. "string")
     */
    public function column($real)
    {
        return false;
    }

    /**
     * Used to create new records. The "C" CRUD.
     *
     * To-be-overridden in subclasses.
     *
     * @param Model $model  the Model to be created
     * @param array $fields an Array of fields to be saved
     * @param array $values an Array of values to save
     *
     * @return bool success
     */
    public function create(&$model, $fields = null, $values = null)
    {
        return false;
    }

    /**
     * Used to read records from the Datasource. The "R" in CRUD.
     *
     * To-be-overridden in subclasses.
     *
     * @param Model $model     the model being read
     * @param array $queryData An array of query data used to find the data you want
     *
     * @return mixed
     */
    public function read(&$model, $queryData = [])
    {
        return false;
    }

    /**
     * Update a record(s) in the datasource.
     *
     * To-be-overridden in subclasses.
     *
     * @param Model $model  Instance of the model class being updated
     * @param array $fields Array of fields to be updated
     * @param array $values array of values to be update $fields to
     *
     * @return bool Success
     */
    public function update(&$model, $fields = null, $values = null)
    {
        return false;
    }

    /**
     * Delete a record(s) in the datasource.
     *
     * To-be-overridden in subclasses.
     *
     * @param Model $model      The model class having record(s) deleted
     * @param mixed $conditions the conditions to use for deleting
     */
    public function delete(&$model, $conditions = null)
    {
        return false;
    }

    /**
     * Returns the ID generated from the previous INSERT operation.
     *
     * @param unknown_type $source
     *
     * @return mixed Last ID key generated in previous INSERT
     */
    public function lastInsertId($source = null)
    {
        return false;
    }

    /**
     * Returns the number of rows returned by last operation.
     *
     * @param unknown_type $source
     *
     * @return int Number of rows returned by last operation
     */
    public function lastNumRows($source = null)
    {
        return false;
    }

    /**
     * Returns the number of rows affected by last query.
     *
     * @param unknown_type $source
     *
     * @return int number of rows affected by last query
     */
    public function lastAffected($source = null)
    {
        return false;
    }

    /**
     * Check whether the conditions for the Datasource being available
     * are satisfied.  Often used from connect() to check for support
     * before establishing a connection.
     *
     * @return bool whether or not the Datasources conditions for use are met
     */
    public function enabled()
    {
        return true;
    }

    /**
     * Returns true if the DataSource supports the given interface (method).
     *
     * @param string $interface The name of the interface (method)
     *
     * @return bool True on success
     */
    public function isInterfaceSupported($interface)
    {
        static $methods = false;
        if (false === $methods) {
            $methods = array_map('strtolower', get_class_methods($this));
        }

        return in_array(strtolower($interface), $methods);
    }

    /**
     * Sets the configuration for the DataSource.
     * Merges the $config information with the _baseConfig and the existing $config property.
     *
     * @param array $config The configuration array
     */
    public function setConfig($config = [])
    {
        $this->config = array_merge($this->_baseConfig, $this->config, $config);
    }

    /**
     * Cache the DataSource description.
     *
     * @param string $object The name of the object (model) to cache
     * @param mixed  $data   The description of the model, usually a string or array
     *
     * @return mixed
     */
    public function __cacheDescription($object, $data = null)
    {
        if (false === $this->cacheSources) {
            return null;
        }

        if (null !== $data) {
            $this->__descriptions[$object] = &$data;
        }

        $key = ConnectionManager::getSourceName($this).'_'.$object;
        $cache = Cache::read($key, '_cake_model_');

        if (empty($cache)) {
            $cache = $data;
            Cache::write($key, $cache, '_cake_model_');
        }

        return $cache;
    }

    /**
     * Replaces `{$__cakeID__$}` and `{$__cakeForeignKey__$}` placeholders in query data.
     *
     * @param string       $query       query string needing replacements done
     * @param array        $data        array of data with values that will be inserted in placeholders
     * @param string       $association Name of association model being replaced
     * @param unknown_type $assocData
     * @param Model        $model       Instance of the model to replace $__cakeID__$
     * @param Model        $linkModel   Instance of model to replace $__cakeForeignKey__$
     * @param array        $stack
     *
     * @return string string of query data with placeholders replaced
     *
     * @todo Remove and refactor $assocData, ensure uses of the method have the param removed too.
     */
    public function insertQueryData($query, $data, $association, $assocData, &$model, &$linkModel, $stack)
    {
        $keys = ['{$__cakeID__$}', '{$__cakeForeignKey__$}'];

        foreach ($keys as $key) {
            $val = null;
            $type = null;

            if (false !== strpos($query, $key)) {
                switch ($key) {
                    case '{$__cakeID__$}':
                        if (isset($data[$model->alias]) || isset($data[$association])) {
                            if (isset($data[$model->alias][$model->primaryKey])) {
                                $val = $data[$model->alias][$model->primaryKey];
                            } elseif (isset($data[$association][$model->primaryKey])) {
                                $val = $data[$association][$model->primaryKey];
                            }
                        } else {
                            $found = false;
                            foreach (array_reverse($stack) as $assoc) {
                                if (isset($data[$assoc]) && isset($data[$assoc][$model->primaryKey])) {
                                    $val = $data[$assoc][$model->primaryKey];
                                    $found = true;
                                    break;
                                }
                            }
                            if (!$found) {
                                $val = '';
                            }
                        }
                        $type = $model->getColumnType($model->primaryKey);
                    break;
                    case '{$__cakeForeignKey__$}':
                        foreach ($model->__associations as $id => $name) {
                            foreach ($model->$name as $assocName => $assoc) {
                                if ($assocName === $association) {
                                    if (isset($assoc['foreignKey'])) {
                                        $foreignKey = $assoc['foreignKey'];
                                        $assocModel = $model->$assocName;
                                        $type = $assocModel->getColumnType($assocModel->primaryKey);

                                        if (isset($data[$model->alias][$foreignKey])) {
                                            $val = $data[$model->alias][$foreignKey];
                                        } elseif (isset($data[$association][$foreignKey])) {
                                            $val = $data[$association][$foreignKey];
                                        } else {
                                            $found = false;
                                            foreach (array_reverse($stack) as $assoc) {
                                                if (isset($data[$assoc]) && isset($data[$assoc][$foreignKey])) {
                                                    $val = $data[$assoc][$foreignKey];
                                                    $found = true;
                                                    break;
                                                }
                                            }
                                            if (!$found) {
                                                $val = '';
                                            }
                                        }
                                    }
                                    break 3;
                                }
                            }
                        }
                    break;
                }
                if (empty($val) && '0' !== $val) {
                    return false;
                }
                $query = str_replace($key, $this->value($val, $type), $query);
            }
        }

        return $query;
    }

    /**
     * To-be-overridden in subclasses.
     *
     * @param Model  $model Model instance
     * @param string $key   Key name to make
     *
     * @return string key name for model
     */
    public function resolveKey(&$model, $key)
    {
        return $model->alias.$key;
    }

    /**
     * Closes the current datasource.
     */
    public function __destruct()
    {
        if ($this->_transactionStarted) {
            $null = null;
            $this->rollback($null);
        }
        if ($this->connected) {
            $this->close();
        }
    }
}
