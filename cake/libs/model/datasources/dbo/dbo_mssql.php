<?php
/**
 * MS SQL layer for DBO.
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
 * MS SQL layer for DBO.
 *
 * Long description for class
 */
class DboMssql extends DboSource
{
    /**
     * Driver description.
     *
     * @var string
     */
    public $description = 'MS SQL DBO Driver';

    /**
     * Starting quote character for quoted identifiers.
     *
     * @var string
     */
    public $startQuote = '[';

    /**
     * Ending quote character for quoted identifiers.
     *
     * @var string
     */
    public $endQuote = ']';

    /**
     * Creates a map between field aliases and numeric indexes.  Workaround for the
     * SQL Server driver's 30-character column name limitation.
     *
     * @var array
     */
    public $__fieldMappings = [];

    /**
     * Base configuration settings for MS SQL driver.
     *
     * @var array
     */
    public $_baseConfig = [
        'persistent' => true,
        'host' => 'localhost',
        'login' => 'root',
        'password' => '',
        'database' => 'cake',
        'port' => '1433',
    ];

    /**
     * MS SQL column definition.
     *
     * @var array
     */
    public $columns = [
        'primary_key' => ['name' => 'IDENTITY (1, 1) NOT NULL'],
        'string' => ['name' => 'varchar', 'limit' => '255'],
        'text' => ['name' => 'text'],
        'integer' => ['name' => 'int', 'formatter' => 'intval'],
        'float' => ['name' => 'numeric', 'formatter' => 'floatval'],
        'datetime' => ['name' => 'datetime', 'format' => 'Y-m-d H:i:s', 'formatter' => 'date'],
        'timestamp' => ['name' => 'timestamp', 'format' => 'Y-m-d H:i:s', 'formatter' => 'date'],
        'time' => ['name' => 'datetime', 'format' => 'H:i:s', 'formatter' => 'date'],
        'date' => ['name' => 'datetime', 'format' => 'Y-m-d', 'formatter' => 'date'],
        'binary' => ['name' => 'image'],
        'boolean' => ['name' => 'bit'],
    ];

    /**
     * Index of basic SQL commands.
     *
     * @var array
     */
    public $_commands = [
        'begin' => 'BEGIN TRANSACTION',
        'commit' => 'COMMIT',
        'rollback' => 'ROLLBACK',
    ];

    /**
     * Define if the last query had error.
     *
     * @var string
     */
    public $__lastQueryHadError = false;

    /**
     * MS SQL DBO driver constructor; sets SQL Server error reporting defaults.
     *
     * @param array $config Configuration data from app/config/databases.php
     *
     * @return bool True if connected successfully, false on error
     */
    public function __construct($config, $autoConnect = true)
    {
        if ($autoConnect) {
            if (!function_exists('mssql_min_message_severity')) {
                trigger_error(__('PHP SQL Server interface is not installed, cannot continue. For troubleshooting information, see http://php.net/mssql/', true), E_USER_WARNING);
            }
            mssql_min_message_severity(15);
            mssql_min_error_severity(2);
        }

        return parent::__construct($config, $autoConnect);
    }

    /**
     * Connects to the database using options in the given configuration array.
     *
     * @return bool True if the database could be connected, else false
     */
    public function connect()
    {
        $config = $this->config;

        $os = env('OS');
        if (!empty($os) && false !== strpos($os, 'Windows')) {
            $sep = ',';
        } else {
            $sep = ':';
        }
        $this->connected = false;

        if (is_numeric($config['port'])) {
            $port = $sep.$config['port'];	// Port number
        } elseif (null === $config['port']) {
            $port = '';						// No port - SQL Server 2005
        } else {
            $port = '\\'.$config['port'];	// Named pipe
        }

        if (!$config['persistent']) {
            $this->connection = mssql_connect($config['host'].$port, $config['login'], $config['password'], true);
        } else {
            $this->connection = mssql_pconnect($config['host'].$port, $config['login'], $config['password']);
        }

        if (mssql_select_db($config['database'], $this->connection)) {
            $this->_execute('SET DATEFORMAT ymd');
            $this->connected = true;
        }

        return $this->connected;
    }

    /**
     * Check that MsSQL is installed/loaded.
     *
     * @return bool
     */
    public function enabled()
    {
        return extension_loaded('mssql');
    }

    /**
     * Disconnects from database.
     *
     * @return bool True if the database could be disconnected, else false
     */
    public function disconnect()
    {
        @mssql_free_result($this->results);
        $this->connected = !@mssql_close($this->connection);

        return !$this->connected;
    }

    /**
     * Executes given SQL statement.
     *
     * @param string $sql SQL statement
     *
     * @return resource Result resource identifier
     */
    public function _execute($sql)
    {
        $result = @mssql_query($sql, $this->connection);
        $this->__lastQueryHadError = (false === $result);

        return $result;
    }

    /**
     * Returns an array of sources (tables) in the database.
     *
     * @return array Array of tablenames in the database
     */
    public function listSources()
    {
        $cache = parent::listSources();

        if (null != $cache) {
            return $cache;
        }
        $result = $this->fetchAll('SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES', false);

        if (!$result || empty($result)) {
            return [];
        } else {
            $tables = [];

            foreach ($result as $table) {
                $tables[] = $table[0]['TABLE_NAME'];
            }

            parent::listSources($tables);

            return $tables;
        }
    }

    /**
     * Returns an array of the fields in given table name.
     *
     * @param Model $model Model object to describe
     *
     * @return array Fields in table. Keys are name and type
     */
    public function describe(&$model)
    {
        $cache = parent::describe($model);

        if (null != $cache) {
            return $cache;
        }

        $table = $this->fullTableName($model, false);
        $cols = $this->fetchAll("SELECT COLUMN_NAME as Field, DATA_TYPE as Type, COL_LENGTH('".$table."', COLUMN_NAME) as Length, IS_NULLABLE As [Null], COLUMN_DEFAULT as [Default], COLUMNPROPERTY(OBJECT_ID('".$table."'), COLUMN_NAME, 'IsIdentity') as [Key], NUMERIC_SCALE as Size FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '".$table."'", false);

        $fields = false;
        foreach ($cols as $column) {
            $field = $column[0]['Field'];
            $fields[$field] = [
                'type' => $this->column($column[0]['Type']),
                'null' => ('YES' == strtoupper($column[0]['Null'])),
                'default' => preg_replace("/^[(]{1,2}'?([^')]*)?'?[)]{1,2}$/", '$1', $column[0]['Default']),
                'length' => intval($column[0]['Length']),
                'key' => ($column[0]['Key'] == '1') ? 'primary' : false,
            ];
            if ($fields[$field]['default'] === 'null') {
                $fields[$field]['default'] = null;
            } else {
                $this->value($fields[$field]['default'], $fields[$field]['type']);
            }

            if ($fields[$field]['key'] && $fields[$field]['type'] == 'integer') {
                $fields[$field]['length'] = 11;
            } elseif (!$fields[$field]['key']) {
                unset($fields[$field]['key']);
            }
            if (in_array($fields[$field]['type'], ['date', 'time', 'datetime', 'timestamp'])) {
                $fields[$field]['length'] = null;
            }
        }
        $this->__cacheDescription($this->fullTableName($model, false), $fields);

        return $fields;
    }

    /**
     * Returns a quoted and escaped string of $data for use in an SQL statement.
     *
     * @param string $data   String to be prepared for use in an SQL statement
     * @param string $column The column into which this data will be inserted
     * @param bool   $safe   Whether or not numeric data should be handled automagically if no column data is provided
     *
     * @return string Quoted and escaped data
     */
    public function value($data, $column = null, $safe = false)
    {
        $parent = parent::value($data, $column, $safe);

        if (null != $parent) {
            return $parent;
        }
        if (null === $data) {
            return 'NULL';
        }
        if (in_array($column, ['integer', 'float', 'binary']) && '' === $data) {
            return 'NULL';
        }
        if ('' === $data) {
            return "''";
        }

        switch ($column) {
            case 'boolean':
                $data = $this->boolean((bool) $data);
            break;
            default:
                if (get_magic_quotes_gpc()) {
                    $data = stripslashes(str_replace("'", "''", $data));
                } else {
                    $data = str_replace("'", "''", $data);
                }
            break;
        }

        if (in_array($column, ['integer', 'float', 'binary']) && is_numeric($data)) {
            return $data;
        }

        return "'".$data."'";
    }

    /**
     * Generates the fields list of an SQL query.
     *
     * @param Model  $model
     * @param string $alias  Alias tablename
     * @param mixed  $fields
     *
     * @return array
     */
    public function fields(&$model, $alias = null, $fields = [], $quote = true)
    {
        if (empty($alias)) {
            $alias = $model->alias;
        }
        $fields = parent::fields($model, $alias, $fields, false);
        $count = count($fields);

        if (
            $count >= 1 &&
            false === strpos($fields[0], 'COUNT(*)') &&
            false === strpos($fields[0], 'COUNT(DISTINCT')
        ) {
            $result = [];
            for ($i = 0; $i < $count; ++$i) {
                $prepend = '';

                if (false !== strpos($fields[$i], 'DISTINCT')) {
                    $prepend = 'DISTINCT ';
                    $fields[$i] = trim(str_replace('DISTINCT', '', $fields[$i]));
                }
                $fieldAlias = count($this->__fieldMappings);

                if (!preg_match('/\s+AS\s+/i', $fields[$i])) {
                    if ('*' == substr($fields[$i], -1)) {
                        if (false !== strpos($fields[$i], '.') && $fields[$i] != $alias.'.*') {
                            $build = explode('.', $fields[$i]);
                            $AssociatedModel = $model->{$build[0]};
                        } else {
                            $AssociatedModel = $model;
                        }

                        $_fields = $this->fields($AssociatedModel, $AssociatedModel->alias, array_keys($AssociatedModel->schema()));
                        $result = array_merge($result, $_fields);
                        continue;
                    }

                    if (false === strpos($fields[$i], '.')) {
                        $this->__fieldMappings[$alias.'__'.$fieldAlias] = $alias.'.'.$fields[$i];
                        $fieldName = $this->name($alias.'.'.$fields[$i]);
                        $fieldAlias = $this->name($alias.'__'.$fieldAlias);
                    } else {
                        $build = explode('.', $fields[$i]);
                        $this->__fieldMappings[$build[0].'__'.$fieldAlias] = $fields[$i];
                        $fieldName = $this->name($build[0].'.'.$build[1]);
                        $fieldAlias = $this->name(preg_replace("/^\[(.+)\]$/", '$1', $build[0]).'__'.$fieldAlias);
                    }
                    if ('datetime' == $model->getColumnType($fields[$i])) {
                        $fieldName = "CONVERT(VARCHAR(20), {$fieldName}, 20)";
                    }
                    $fields[$i] = "{$fieldName} AS {$fieldAlias}";
                }
                $result[] = $prepend.$fields[$i];
            }

            return $result;
        } else {
            return $fields;
        }
    }

    /**
     * Generates and executes an SQL INSERT statement for given model, fields, and values.
     * Removes Identity (primary key) column from update data before returning to parent, if
     * value is empty.
     *
     * @param Model $model
     * @param array $fields
     * @param array $values
     * @param mixed $conditions
     *
     * @return array
     */
    public function create(&$model, $fields = null, $values = null)
    {
        if (!empty($values)) {
            $fields = array_combine($fields, $values);
        }
        $primaryKey = $this->_getPrimaryKey($model);

        if (array_key_exists($primaryKey, $fields)) {
            if (empty($fields[$primaryKey])) {
                unset($fields[$primaryKey]);
            } else {
                $this->_execute('SET IDENTITY_INSERT '.$this->fullTableName($model).' ON');
            }
        }
        $result = parent::create($model, array_keys($fields), array_values($fields));
        if (array_key_exists($primaryKey, $fields) && !empty($fields[$primaryKey])) {
            $this->_execute('SET IDENTITY_INSERT '.$this->fullTableName($model).' OFF');
        }

        return $result;
    }

    /**
     * Generates and executes an SQL UPDATE statement for given model, fields, and values.
     * Removes Identity (primary key) column from update data before returning to parent.
     *
     * @param Model $model
     * @param array $fields
     * @param array $values
     * @param mixed $conditions
     *
     * @return array
     */
    public function update(&$model, $fields = [], $values = null, $conditions = null)
    {
        if (!empty($values)) {
            $fields = array_combine($fields, $values);
        }
        if (isset($fields[$model->primaryKey])) {
            unset($fields[$model->primaryKey]);
        }
        if (empty($fields)) {
            return true;
        }

        return parent::update($model, array_keys($fields), array_values($fields), $conditions);
    }

    /**
     * Returns a formatted error message from previous database operation.
     *
     * @return string Error message with error number
     */
    public function lastError()
    {
        if ($this->__lastQueryHadError) {
            $error = mssql_get_last_message();
            if ($error && !preg_match('/contexto de la base de datos a|contesto di database|changed database|contexte de la base de don|datenbankkontext/i', $error)) {
                return $error;
            }
        }

        return null;
    }

    /**
     * Returns number of affected rows in previous database operation. If no previous operation exists,
     * this returns false.
     *
     * @return int Number of affected rows
     */
    public function lastAffected()
    {
        if ($this->_result) {
            return mssql_rows_affected($this->connection);
        }

        return null;
    }

    /**
     * Returns number of rows in previous resultset. If no previous resultset exists,
     * this returns false.
     *
     * @return int Number of rows in resultset
     */
    public function lastNumRows()
    {
        if ($this->_result) {
            return @mssql_num_rows($this->_result);
        }

        return null;
    }

    /**
     * Returns the ID generated from the previous INSERT operation.
     *
     * @param unknown_type $source
     *
     * @return in
     */
    public function lastInsertId($source = null)
    {
        $id = $this->fetchRow('SELECT SCOPE_IDENTITY() AS insertID', false);

        return $id[0]['insertID'];
    }

    /**
     * Returns a limit statement in the correct format for the particular database.
     *
     * @param int $limit  Limit of results returned
     * @param int $offset Offset from which to start results
     *
     * @return string SQL limit/offset statement
     */
    public function limit($limit, $offset = null)
    {
        if ($limit) {
            $rt = '';
            if (!strpos(strtolower($limit), 'top') || 0 === strpos(strtolower($limit), 'top')) {
                $rt = ' TOP';
            }
            $rt .= ' '.$limit;
            if (is_int($offset) && $offset > 0) {
                $rt .= ' OFFSET '.$offset;
            }

            return $rt;
        }

        return null;
    }

    /**
     * Converts database-layer column types to basic types.
     *
     * @param string $real Real database-layer column type (i.e. "varchar(255)")
     *
     * @return string Abstract column type (i.e. "string")
     */
    public function column($real)
    {
        if (is_array($real)) {
            $col = $real['name'];

            if (isset($real['limit'])) {
                $col .= '('.$real['limit'].')';
            }

            return $col;
        }
        $col = str_replace(')', '', $real);
        $limit = null;
        if (false !== strpos($col, '(')) {
            list($col, $limit) = explode('(', $col);
        }

        if (in_array($col, ['date', 'time', 'datetime', 'timestamp'])) {
            return $col;
        }
        if ('bit' == $col) {
            return 'boolean';
        }
        if (false !== strpos($col, 'int')) {
            return 'integer';
        }
        if (false !== strpos($col, 'char')) {
            return 'string';
        }
        if (false !== strpos($col, 'text')) {
            return 'text';
        }
        if (false !== strpos($col, 'binary') || 'image' == $col) {
            return 'binary';
        }
        if (in_array($col, ['float', 'real', 'decimal', 'numeric'])) {
            return 'float';
        }

        return 'text';
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $results
     */
    public function resultSet(&$results)
    {
        $this->results = &$results;
        $this->map = [];
        $numFields = mssql_num_fields($results);
        $index = 0;
        $j = 0;

        while ($j < $numFields) {
            $column = mssql_field_name($results, $j);

            if (strpos($column, '__')) {
                if (isset($this->__fieldMappings[$column]) && strpos($this->__fieldMappings[$column], '.')) {
                    $map = explode('.', $this->__fieldMappings[$column]);
                } elseif (isset($this->__fieldMappings[$column])) {
                    $map = [0, $this->__fieldMappings[$column]];
                } else {
                    $map = [0, $column];
                }
                $this->map[$index++] = $map;
            } else {
                $this->map[$index++] = [0, $column];
            }
            ++$j;
        }
    }

    /**
     * Builds final SQL statement.
     *
     * @param string $type Query type
     * @param array  $data Query data
     *
     * @return string
     */
    public function renderStatement($type, $data)
    {
        switch (strtolower($type)) {
            case 'select':
                extract($data);
                $fields = trim($fields);

                if (false !== strpos($limit, 'TOP') && 0 === strpos($fields, 'DISTINCT ')) {
                    $limit = 'DISTINCT '.trim($limit);
                    $fields = substr($fields, 9);
                }

                if (preg_match('/offset\s+([0-9]+)/i', $limit, $offset)) {
                    $limit = preg_replace('/\s*offset.*$/i', '', $limit);
                    preg_match('/top\s+([0-9]+)/i', $limit, $limitVal);
                    $offset = intval($offset[1]) + intval($limitVal[1]);
                    $rOrder = $this->__switchSort($order);
                    list($order2, $rOrder) = [$this->__mapFields($order), $this->__mapFields($rOrder)];

                    return "SELECT * FROM (SELECT {$limit} * FROM (SELECT TOP {$offset} {$fields} FROM {$table} {$alias} {$joins} {$conditions} {$group} {$order}) AS Set1 {$rOrder}) AS Set2 {$order2}";
                } else {
                    return "SELECT {$limit} {$fields} FROM {$table} {$alias} {$joins} {$conditions} {$group} {$order}";
                }
            break;
            case 'schema':
                extract($data);

                foreach ($indexes as $i => $index) {
                    if (preg_match('/PRIMARY KEY/', $index)) {
                        unset($indexes[$i]);
                        break;
                    }
                }

                foreach (['columns', 'indexes'] as $var) {
                    if (is_array(${$var})) {
                        ${$var} = "\t".implode(",\n\t", array_filter(${$var}));
                    }
                }

                return "CREATE TABLE {$table} (\n{$columns});\n{$indexes}";
            break;
            default:
                return parent::renderStatement($type, $data);
            break;
        }
    }

    /**
     * Reverses the sort direction of ORDER statements to get paging offsets to work correctly.
     *
     * @param string $order
     *
     * @return string
     */
    public function __switchSort($order)
    {
        $order = preg_replace('/\s+ASC/i', '__tmp_asc__', $order);
        $order = preg_replace('/\s+DESC/i', ' ASC', $order);

        return preg_replace('/__tmp_asc__/', ' DESC', $order);
    }

    /**
     * Translates field names used for filtering and sorting to shortened names using the field map.
     *
     * @param string $sql A snippet of SQL representing an ORDER or WHERE statement
     *
     * @return string The value of $sql with field names replaced
     */
    public function __mapFields($sql)
    {
        if (empty($sql) || empty($this->__fieldMappings)) {
            return $sql;
        }
        foreach ($this->__fieldMappings as $key => $val) {
            $sql = preg_replace('/'.preg_quote($val).'/', $this->name($key), $sql);
            $sql = preg_replace('/'.preg_quote($this->name($val)).'/', $this->name($key), $sql);
        }

        return $sql;
    }

    /**
     * Returns an array of all result rows for a given SQL query.
     * Returns false if no rows matched.
     *
     * @param string $sql   SQL statement
     * @param bool   $cache Enables returning/storing cached query results
     *
     * @return array Array of resultset rows, or false if no rows matched
     */
    public function read(&$model, $queryData = [], $recursive = null)
    {
        $results = parent::read($model, $queryData, $recursive);
        $this->__fieldMappings = [];

        return $results;
    }

    /**
     * Fetches the next row from the current result set.
     *
     * @return unknown
     */
    public function fetchResult()
    {
        if ($row = mssql_fetch_row($this->results)) {
            $resultRow = [];
            $i = 0;

            foreach ($row as $index => $field) {
                list($table, $column) = $this->map[$index];
                $resultRow[$table][$column] = $row[$index];
                ++$i;
            }

            return $resultRow;
        } else {
            return false;
        }
    }

    /**
     * Inserts multiple values into a table.
     *
     * @param string $table
     * @param string $fields
     * @param array  $values
     */
    public function insertMulti($table, $fields, $values)
    {
        $primaryKey = $this->_getPrimaryKey($table);
        $hasPrimaryKey = null != $primaryKey && (
            (is_array($fields) && in_array($primaryKey, $fields)
            || (is_string($fields) && false !== strpos($fields, $this->startQuote.$primaryKey.$this->endQuote)))
        );

        if ($hasPrimaryKey) {
            $this->_execute('SET IDENTITY_INSERT '.$this->fullTableName($table).' ON');
        }
        parent::insertMulti($table, $fields, $values);
        if ($hasPrimaryKey) {
            $this->_execute('SET IDENTITY_INSERT '.$this->fullTableName($table).' OFF');
        }
    }

    /**
     * Generate a database-native column schema string.
     *
     * @param array $column an array structured like the following: array('name'=>'value', 'type'=>'value'[, options]),
     *                      where options can be 'default', 'length', or 'key'
     *
     * @return string
     */
    public function buildColumn($column)
    {
        $result = preg_replace('/(int|integer)\([0-9]+\)/i', '$1', parent::buildColumn($column));
        if (false !== strpos($result, 'DEFAULT NULL')) {
            $result = str_replace('DEFAULT NULL', 'NULL', $result);
        } elseif (array_keys($column) == ['type', 'name']) {
            $result .= ' NULL';
        }

        return $result;
    }

    /**
     * Format indexes for create table.
     *
     * @param array  $indexes
     * @param string $table
     *
     * @return string
     */
    public function buildIndex($indexes, $table = null)
    {
        $join = [];

        foreach ($indexes as $name => $value) {
            if ('PRIMARY' == $name) {
                $join[] = 'PRIMARY KEY ('.$this->name($value['column']).')';
            } elseif (isset($value['unique']) && $value['unique']) {
                $out = "ALTER TABLE {$table} ADD CONSTRAINT {$name} UNIQUE";

                if (is_array($value['column'])) {
                    $value['column'] = implode(', ', array_map([&$this, 'name'], $value['column']));
                } else {
                    $value['column'] = $this->name($value['column']);
                }
                $out .= "({$value['column']});";
                $join[] = $out;
            }
        }

        return $join;
    }

    /**
     * Makes sure it will return the primary key.
     *
     * @param mixed $model
     *
     * @return string
     */
    public function _getPrimaryKey($model)
    {
        if (is_object($model)) {
            $schema = $model->schema();
        } else {
            $schema = $this->describe($model);
        }

        foreach ($schema as $field => $props) {
            if (isset($props['key']) && 'primary' == $props['key']) {
                return $field;
            }
        }

        return null;
    }
}
