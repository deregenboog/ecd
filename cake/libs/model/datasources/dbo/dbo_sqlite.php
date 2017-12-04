<?php
/**
 * SQLite layer for DBO.
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
 * @since         CakePHP(tm) v 0.9.0
 *
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * DBO implementation for the SQLite DBMS.
 *
 * Long description for class
 */
class DboSqlite extends DboSource
{
    /**
     * Datasource Description.
     *
     * @var string
     */
    public $description = 'SQLite DBO Driver';

    /**
     * Opening quote for quoted identifiers.
     *
     * @var string
     */
    public $startQuote = '"';

    /**
     * Closing quote for quoted identifiers.
     *
     * @var string
     */
    public $endQuote = '"';

    /**
     * Keeps the transaction statistics of CREATE/UPDATE/DELETE queries.
     *
     * @var array
     */
    public $_queryStats = [];

    /**
     * Base configuration settings for SQLite driver.
     *
     * @var array
     */
    public $_baseConfig = [
        'persistent' => true,
        'database' => null,
    ];

    /**
     * Index of basic SQL commands.
     *
     * @var array
     */
    public $_commands = [
        'begin' => 'BEGIN TRANSACTION',
        'commit' => 'COMMIT TRANSACTION',
        'rollback' => 'ROLLBACK TRANSACTION',
    ];

    /**
     * SQLite column definition.
     *
     * @var array
     */
    public $columns = [
        'primary_key' => ['name' => 'integer primary key'],
        'string' => ['name' => 'varchar', 'limit' => '255'],
        'text' => ['name' => 'text'],
        'integer' => ['name' => 'integer', 'limit' => 11, 'formatter' => 'intval'],
        'float' => ['name' => 'float', 'formatter' => 'floatval'],
        'datetime' => ['name' => 'datetime', 'format' => 'Y-m-d H:i:s', 'formatter' => 'date'],
        'timestamp' => ['name' => 'timestamp', 'format' => 'Y-m-d H:i:s', 'formatter' => 'date'],
        'time' => ['name' => 'time', 'format' => 'H:i:s', 'formatter' => 'date'],
        'date' => ['name' => 'date', 'format' => 'Y-m-d', 'formatter' => 'date'],
        'binary' => ['name' => 'blob'],
        'boolean' => ['name' => 'boolean'],
    ];

    /**
     * List of engine specific additional field parameters used on table creating.
     *
     * @var array
     */
    public $fieldParameters = [
        'collate' => [
            'value' => 'COLLATE',
            'quote' => false,
            'join' => ' ',
            'column' => 'Collate',
            'position' => 'afterDefault',
            'options' => [
                'BINARY', 'NOCASE', 'RTRIM',
            ],
        ],
    ];

    /**
     * Connects to the database using config['database'] as a filename.
     *
     * @param array $config Configuration array for connecting
     *
     * @return mixed
     */
    public function connect()
    {
        $config = $this->config;

        if (!$config['persistent']) {
            $this->connection = sqlite_open($config['database']);
        } else {
            $this->connection = sqlite_popen($config['database']);
        }
        $this->connected = is_resource($this->connection);

        if ($this->connected) {
            $this->_execute('PRAGMA count_changes = 1;');
        }

        return $this->connected;
    }

    /**
     * Check that SQLite is enabled/installed.
     *
     * @return bool
     */
    public function enabled()
    {
        return extension_loaded('sqlite');
    }

    /**
     * Disconnects from database.
     *
     * @return bool True if the database could be disconnected, else false
     */
    public function disconnect()
    {
        @sqlite_close($this->connection);
        $this->connected = false;

        return $this->connected;
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
        $result = sqlite_query($this->connection, $sql);

        if (preg_match('/^(INSERT|UPDATE|DELETE)/', $sql)) {
            $this->resultSet($result);
            list($this->_queryStats) = $this->fetchResult();
        }

        return $result;
    }

    /**
     * Overrides DboSource::execute() to correctly handle query statistics.
     *
     * @param string $sql
     *
     * @return unknown
     */
    public function execute($sql)
    {
        $result = parent::execute($sql);
        $this->_queryStats = [];

        return $result;
    }

    /**
     * Returns an array of tables in the database. If there are no tables, an error is raised and the application exits.
     *
     * @return array Array of tablenames in the database
     */
    public function listSources()
    {
        $cache = parent::listSources();

        if (null != $cache) {
            return $cache;
        }
        $result = $this->fetchAll("SELECT name FROM sqlite_master WHERE type='table' ORDER BY name;", false);

        if (empty($result)) {
            return [];
        } else {
            $tables = [];
            foreach ($result as $table) {
                $tables[] = $table[0]['name'];
            }
            parent::listSources($tables);

            return $tables;
        }

        return [];
    }

    /**
     * Returns an array of the fields in given table name.
     *
     * @param string $tableName Name of database table to inspect
     *
     * @return array Fields in table. Keys are name and type
     */
    public function describe(&$model)
    {
        $cache = parent::describe($model);
        if (null != $cache) {
            return $cache;
        }
        $fields = [];
        $result = $this->fetchAll('PRAGMA table_info('.$this->fullTableName($model).')');

        foreach ($result as $column) {
            $fields[$column[0]['name']] = [
                'type' => $this->column($column[0]['type']),
                'null' => !$column[0]['notnull'],
                'default' => $column[0]['dflt_value'],
                'length' => $this->length($column[0]['type']),
            ];
            if ($column[0]['pk'] == 1) {
                $colLength = $this->length($column[0]['type']);
                $fields[$column[0]['name']] = [
                    'type' => $fields[$column[0]['name']]['type'],
                    'null' => false,
                    'default' => $column[0]['dflt_value'],
                    'key' => $this->index['PRI'],
                    'length' => (null != $colLength) ? $colLength : 11,
                ];
            }
        }

        $this->__cacheDescription($model->tablePrefix.$model->table, $fields);

        return $fields;
    }

    /**
     * Returns a quoted and escaped string of $data for use in an SQL statement.
     *
     * @param string $data String to be prepared for use in an SQL statement
     *
     * @return string Quoted and escaped
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
        if ('' === $data && 'integer' !== $column && 'float' !== $column && 'boolean' !== $column) {
            return  "''";
        }
        switch ($column) {
            case 'boolean':
                $data = $this->boolean((bool) $data);
            break;
            case 'integer':
            case 'float':
                if ('' === $data) {
                    return 'NULL';
                }
                // no break
            default:
                $data = sqlite_escape_string($data);
            break;
        }

        return "'".$data."'";
    }

    /**
     * Generates and executes an SQL UPDATE statement for given model, fields, and values.
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
        if (empty($values) && !empty($fields)) {
            foreach ($fields as $field => $value) {
                if (false !== strpos($field, $model->alias.'.')) {
                    unset($fields[$field]);
                    $field = str_replace($model->alias.'.', '', $field);
                    $field = str_replace($model->alias.'.', '', $field);
                    $fields[$field] = $value;
                }
            }
        }
        $result = parent::update($model, $fields, $values, $conditions);

        return $result;
    }

    /**
     * Deletes all the records in a table and resets the count of the auto-incrementing
     * primary key, where applicable.
     *
     * @param mixed $table A string or model class representing the table to be truncated
     *
     * @return bool SQL TRUNCATE TABLE statement, false if not applicable
     */
    public function truncate($table)
    {
        return $this->execute('DELETE From '.$this->fullTableName($table));
    }

    /**
     * Returns a formatted error message from previous database operation.
     *
     * @return string Error message
     */
    public function lastError()
    {
        $error = sqlite_last_error($this->connection);
        if ($error) {
            return $error.': '.sqlite_error_string($error);
        }

        return null;
    }

    /**
     * Returns number of affected rows in previous database operation. If no previous operation exists, this returns false.
     *
     * @return int Number of affected rows
     */
    public function lastAffected()
    {
        if (!empty($this->_queryStats)) {
            foreach (['rows inserted', 'rows updated', 'rows deleted'] as $key) {
                if (array_key_exists($key, $this->_queryStats)) {
                    return $this->_queryStats[$key];
                }
            }
        }

        return false;
    }

    /**
     * Returns number of rows in previous resultset. If no previous resultset exists,
     * this returns false.
     *
     * @return int Number of rows in resultset
     */
    public function lastNumRows()
    {
        if ($this->hasResult()) {
            sqlite_num_rows($this->_result);
        }

        return false;
    }

    /**
     * Returns the ID generated from the previous INSERT operation.
     *
     * @return int
     */
    public function lastInsertId()
    {
        return sqlite_last_insert_rowid($this->connection);
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

        $col = strtolower(str_replace(')', '', $real));
        $limit = null;
        if (false !== strpos($col, '(')) {
            list($col, $limit) = explode('(', $col);
        }

        if (in_array($col, ['text', 'integer', 'float', 'boolean', 'timestamp', 'date', 'datetime', 'time'])) {
            return $col;
        }
        if (false !== strpos($col, 'varchar')) {
            return 'string';
        }
        if (in_array($col, ['blob', 'clob'])) {
            return 'binary';
        }
        if (false !== strpos($col, 'numeric')) {
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
        $fieldCount = sqlite_num_fields($results);
        $index = $j = 0;

        while ($j < $fieldCount) {
            $columnName = str_replace('"', '', sqlite_field_name($results, $j));

            if (strpos($columnName, '.')) {
                $parts = explode('.', $columnName);
                $this->map[$index++] = [$parts[0], $parts[1]];
            } else {
                $this->map[$index++] = [0, $columnName];
            }
            ++$j;
        }
    }

    /**
     * Fetches the next row from the current result set.
     *
     * @return unknown
     */
    public function fetchResult()
    {
        if ($row = sqlite_fetch_array($this->results, SQLITE_ASSOC)) {
            $resultRow = [];
            $i = 0;

            foreach ($row as $index => $field) {
                if (strpos($index, '.')) {
                    list($table, $column) = explode('.', str_replace('"', '', $index));
                    $resultRow[$table][$column] = $row[$index];
                } else {
                    $resultRow[0][str_replace('"', '', $index)] = $row[$index];
                }
                ++$i;
            }

            return $resultRow;
        } else {
            return false;
        }
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
            if (!strpos(strtolower($limit), 'limit') || 0 === strpos(strtolower($limit), 'limit')) {
                $rt = ' LIMIT';
            }
            $rt .= ' '.$limit;
            if ($offset) {
                $rt .= ' OFFSET '.$offset;
            }

            return $rt;
        }

        return null;
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
        $name = $type = null;
        $column = array_merge(['null' => true], $column);
        extract($column);

        if (empty($name) || empty($type)) {
            trigger_error(__('Column name or type not defined in schema', true), E_USER_WARNING);

            return null;
        }

        if (!isset($this->columns[$type])) {
            trigger_error(sprintf(__('Column type %s does not exist', true), $type), E_USER_WARNING);

            return null;
        }

        $real = $this->columns[$type];
        $out = $this->name($name).' '.$real['name'];
        if (isset($column['key']) && 'primary' == $column['key'] && 'integer' == $type) {
            return $this->name($name).' '.$this->columns['primary_key']['name'];
        }

        return parent::buildColumn($column);
    }

    /**
     * Sets the database encoding.
     *
     * @param string $enc Database encoding
     */
    public function setEncoding($enc)
    {
        if (!in_array($enc, ['UTF-8', 'UTF-16', 'UTF-16le', 'UTF-16be'])) {
            return false;
        }

        return false !== $this->_execute("PRAGMA encoding = \"{$enc}\"");
    }

    /**
     * Gets the database encoding.
     *
     * @return string The database encoding
     */
    public function getEncoding()
    {
        return $this->fetchRow('PRAGMA encoding');
    }

    /**
     * Removes redundant primary key indexes, as they are handled in the column def of the key.
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
                continue;
            }
            $out = 'CREATE ';

            if (!empty($value['unique'])) {
                $out .= 'UNIQUE ';
            }
            if (is_array($value['column'])) {
                $value['column'] = implode(', ', array_map([&$this, 'name'], $value['column']));
            } else {
                $value['column'] = $this->name($value['column']);
            }
            $out .= "INDEX {$name} ON {$table}({$value['column']});";
            $join[] = $out;
        }

        return $join;
    }

    /**
     * Overrides DboSource::index to handle SQLite indexe introspection
     * Returns an array of the indexes in given table name.
     *
     * @param string $model Name of model to inspect
     *
     * @return array Fields in table. Keys are column and unique
     */
    public function index(&$model)
    {
        $index = [];
        $table = $this->fullTableName($model);
        if ($table) {
            $indexes = $this->query('PRAGMA index_list('.$table.')');
            $tableInfo = $this->query('PRAGMA table_info('.$table.')');
            foreach ($indexes as $i => $info) {
                $key = array_pop($info);
                $keyInfo = $this->query('PRAGMA index_info("'.$key['name'].'")');
                foreach ($keyInfo as $keyCol) {
                    if (!isset($index[$key['name']])) {
                        $col = [];
                        if (preg_match('/autoindex/', $key['name'])) {
                            $key['name'] = 'PRIMARY';
                        }
                        $index[$key['name']]['column'] = $keyCol[0]['name'];
                        $index[$key['name']]['unique'] = intval(1 == $key['unique']);
                    } else {
                        if (!is_array($index[$key['name']]['column'])) {
                            $col[] = $index[$key['name']]['column'];
                        }
                        $col[] = $keyCol[0]['name'];
                        $index[$key['name']]['column'] = $col;
                    }
                }
            }
        }

        return $index;
    }

    /**
     * Overrides DboSource::renderStatement to handle schema generation with SQLite-style indexes.
     *
     * @param string $type
     * @param array  $data
     *
     * @return string
     */
    public function renderStatement($type, $data)
    {
        switch (strtolower($type)) {
            case 'schema':
                extract($data);

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
}
