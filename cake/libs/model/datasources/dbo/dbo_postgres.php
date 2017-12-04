<?php
/**
 * PostgreSQL layer for DBO.
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
 * @since         CakePHP(tm) v 0.9.1.114
 *
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * PostgreSQL layer for DBO.
 *
 * Long description for class
 */
class DboPostgres extends DboSource
{
    /**
     * Driver description.
     *
     * @var string
     */
    public $description = 'PostgreSQL DBO Driver';

    /**
     * Index of basic SQL commands.
     *
     * @var array
     */
    public $_commands = [
        'begin' => 'BEGIN',
        'commit' => 'COMMIT',
        'rollback' => 'ROLLBACK',
    ];

    /**
     * Base driver configuration settings.  Merged with user settings.
     *
     * @var array
     */
    public $_baseConfig = [
        'persistent' => true,
        'host' => 'localhost',
        'login' => 'root',
        'password' => '',
        'database' => 'cake',
        'schema' => 'public',
        'port' => 5432,
        'encoding' => '',
    ];

    public $columns = [
        'primary_key' => ['name' => 'serial NOT NULL'],
        'string' => ['name' => 'varchar', 'limit' => '255'],
        'text' => ['name' => 'text'],
        'integer' => ['name' => 'integer', 'formatter' => 'intval'],
        'float' => ['name' => 'float', 'formatter' => 'floatval'],
        'datetime' => ['name' => 'timestamp', 'format' => 'Y-m-d H:i:s', 'formatter' => 'date'],
        'timestamp' => ['name' => 'timestamp', 'format' => 'Y-m-d H:i:s', 'formatter' => 'date'],
        'time' => ['name' => 'time', 'format' => 'H:i:s', 'formatter' => 'date'],
        'date' => ['name' => 'date', 'format' => 'Y-m-d', 'formatter' => 'date'],
        'binary' => ['name' => 'bytea'],
        'boolean' => ['name' => 'boolean'],
        'number' => ['name' => 'numeric'],
        'inet' => ['name' => 'inet'],
    ];

    /**
     * Starting Quote.
     *
     * @var string
     */
    public $startQuote = '"';

    /**
     * Ending Quote.
     *
     * @var string
     */
    public $endQuote = '"';

    /**
     * Contains mappings of custom auto-increment sequences, if a table uses a sequence name
     * other than what is dictated by convention.
     *
     * @var array
     */
    public $_sequenceMap = [];

    /**
     * Connects to the database using options in the given configuration array.
     *
     * @return true if successfully connected
     */
    public function connect()
    {
        $config = $this->config;
        $conn = "host='{$config['host']}' port='{$config['port']}' dbname='{$config['database']}' ";
        $conn .= "user='{$config['login']}' password='{$config['password']}'";

        if (!$config['persistent']) {
            $this->connection = pg_connect($conn, PGSQL_CONNECT_FORCE_NEW);
        } else {
            $this->connection = pg_pconnect($conn);
        }
        $this->connected = false;

        if ($this->connection) {
            $this->connected = true;
            $this->_execute('SET search_path TO '.$config['schema']);
        }
        if (!empty($config['encoding'])) {
            $this->setEncoding($config['encoding']);
        }

        return $this->connected;
    }

    /**
     * Check if PostgreSQL is enabled/loaded.
     *
     * @return bool
     */
    public function enabled()
    {
        return extension_loaded('pgsql');
    }

    /**
     * Disconnects from database.
     *
     * @return bool True if the database could be disconnected, else false
     */
    public function disconnect()
    {
        if ($this->hasResult()) {
            pg_free_result($this->_result);
        }
        if (is_resource($this->connection)) {
            $this->connected = !pg_close($this->connection);
        } else {
            $this->connected = false;
        }

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
        return pg_query($this->connection, $sql);
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

        $schema = $this->config['schema'];
        $sql = "SELECT table_name as name FROM INFORMATION_SCHEMA.tables WHERE table_schema = '{$schema}';";
        $result = $this->fetchAll($sql, false);

        if (!$result) {
            return [];
        } else {
            $tables = [];

            foreach ($result as $item) {
                $tables[] = $item[0]['name'];
            }

            parent::listSources($tables);

            return $tables;
        }
    }

    /**
     * Returns an array of the fields in given table name.
     *
     * @param string $tableName Name of database table to inspect
     *
     * @return array Fields in table. Keys are name and type
     */
    public function &describe(&$model)
    {
        $fields = parent::describe($model);
        $table = $this->fullTableName($model, false);
        $this->_sequenceMap[$table] = [];

        if (null === $fields) {
            $cols = $this->fetchAll(
                'SELECT DISTINCT column_name AS name, data_type AS type, is_nullable AS null,
					column_default AS default, ordinal_position AS position, character_maximum_length AS char_length,
					character_octet_length AS oct_length FROM information_schema.columns
				WHERE table_name = '.$this->value($table).' AND table_schema = '.
                $this->value($this->config['schema']).'  ORDER BY position',
                false
            );

            foreach ($cols as $column) {
                $colKey = array_keys($column);

                if (isset($column[$colKey[0]]) && !isset($column[0])) {
                    $column[0] = $column[$colKey[0]];
                }

                if (isset($column[0])) {
                    $c = $column[0];

                    if (!empty($c['char_length'])) {
                        $length = intval($c['char_length']);
                    } elseif (!empty($c['oct_length'])) {
                        if ('character varying' == $c['type']) {
                            $length = null;
                            $c['type'] = 'text';
                        } else {
                            $length = intval($c['oct_length']);
                        }
                    } else {
                        $length = $this->length($c['type']);
                    }
                    $fields[$c['name']] = [
                        'type' => $this->column($c['type']),
                        'null' => ('NO' == $c['null'] ? false : true),
                        'default' => preg_replace(
                            "/^'(.*)'$/",
                            '$1',
                            preg_replace('/::.*/', '', $c['default'])
                        ),
                        'length' => $length,
                    ];
                    if ($c['name'] == $model->primaryKey) {
                        $fields[$c['name']]['key'] = 'primary';
                        if ($fields[$c['name']]['type'] !== 'string') {
                            $fields[$c['name']]['length'] = 11;
                        }
                    }
                    if (
                        $fields[$c['name']]['default'] == 'NULL' ||
                        preg_match('/nextval\([\'"]?([\w.]+)/', $c['default'], $seq)
                    ) {
                        $fields[$c['name']]['default'] = null;
                        if (!empty($seq) && isset($seq[1])) {
                            $this->_sequenceMap[$table][$c['name']] = $seq[1];
                        }
                    }
                    if ($fields[$c['name']]['type'] == 'boolean' && !empty($fields[$c['name']]['default'])) {
                        $fields[$c['name']]['default'] = constant($fields[$c['name']]['default']);
                    }
                }
            }
            $this->__cacheDescription($table, $fields);
        }
        if (isset($model->sequence)) {
            $this->_sequenceMap[$table][$model->primaryKey] = $model->sequence;
        }

        return $fields;
    }

    /**
     * Returns a quoted and escaped string of $data for use in an SQL statement.
     *
     * @param string $data   String to be prepared for use in an SQL statement
     * @param string $column The column into which this data will be inserted
     * @param bool   $read   Value to be used in READ or WRITE context
     *
     * @return string Quoted and escaped
     *
     * @todo Add logic that formats/escapes data based on column type
     */
    public function value($data, $column = null, $read = true)
    {
        $parent = parent::value($data, $column);
        if (null != $parent) {
            return $parent;
        }

        if (null === $data || (is_array($data) && empty($data))) {
            return 'NULL';
        }
        if (empty($column)) {
            $column = $this->introspectType($data);
        }

        switch ($column) {
            case 'binary':
                $data = pg_escape_bytea($data);
            break;
            case 'boolean':
                if (true === $data || 't' === $data || 'true' === $data) {
                    return 'TRUE';
                } elseif (false === $data || 'f' === $data || 'false' === $data) {
                    return 'FALSE';
                }

                return !empty($data) ? 'TRUE' : 'FALSE';
            break;
            case 'float':
                if (is_float($data)) {
                    $data = sprintf('%F', $data);
                }
                // no break
            case 'inet':
            case 'integer':
            case 'date':
            case 'datetime':
            case 'timestamp':
            case 'time':
                if ('' === $data) {
                    return $read ? 'NULL' : 'DEFAULT';
                }
                // no break
            default:
                $data = pg_escape_string($data);
            break;
        }

        return "'".$data."'";
    }

    /**
     * Returns a formatted error message from previous database operation.
     *
     * @return string Error message
     */
    public function lastError()
    {
        $error = pg_last_error($this->connection);

        return ($error) ? $error : null;
    }

    /**
     * Returns number of affected rows in previous database operation. If no previous operation exists, this returns false.
     *
     * @return int Number of affected rows
     */
    public function lastAffected()
    {
        return ($this->_result) ? pg_affected_rows($this->_result) : false;
    }

    /**
     * Returns number of rows in previous resultset. If no previous resultset exists,
     * this returns false.
     *
     * @return int Number of rows in resultset
     */
    public function lastNumRows()
    {
        return ($this->_result) ? pg_num_rows($this->_result) : false;
    }

    /**
     * Returns the ID generated from the previous INSERT operation.
     *
     * @param string $source Name of the database table
     * @param string $field  Name of the ID database field. Defaults to "id"
     *
     * @return int
     */
    public function lastInsertId($source, $field = 'id')
    {
        $seq = $this->getSequence($source, $field);
        $data = $this->fetchRow("SELECT currval('{$seq}') as max");

        return $data[0]['max'];
    }

    /**
     * Gets the associated sequence for the given table/field.
     *
     * @param mixed  $table Either a full table name (with prefix) as a string, or a model object
     * @param string $field Name of the ID database field. Defaults to "id"
     *
     * @return string The associated sequence name from the sequence map, defaults to "{$table}_{$field}_seq"
     */
    public function getSequence($table, $field = 'id')
    {
        if (is_object($table)) {
            $table = $this->fullTableName($table, false);
        }
        if (isset($this->_sequenceMap[$table]) && isset($this->_sequenceMap[$table][$field])) {
            return $this->_sequenceMap[$table][$field];
        } else {
            return "{$table}_{$field}_seq";
        }
    }

    /**
     * Deletes all the records in a table and drops all associated auto-increment sequences.
     *
     * @param mixed $table A string or model class representing the table to be truncated
     * @param int   $reset If -1, sequences are dropped, if 0 (default), sequences are reset,
     *                     and if 1, sequences are not modified
     *
     * @return bool SQL TRUNCATE TABLE statement, false if not applicable
     */
    public function truncate($table, $reset = 0)
    {
        if ($this->execute('DELETE FROM '.$this->fullTableName($table))) {
            $table = $this->fullTableName($table, false);
            if (isset($this->_sequenceMap[$table]) && 1 !== $reset) {
                foreach ($this->_sequenceMap[$table] as $field => $sequence) {
                    if (0 === $reset) {
                        $this->execute("ALTER SEQUENCE \"{$sequence}\" RESTART WITH 1");
                    } elseif ($reset === -1) {
                        $this->execute("DROP SEQUENCE IF EXISTS \"{$sequence}\"");
                    }
                }
            }

            return true;
        }

        return false;
    }

    /**
     * Prepares field names to be quoted by parent.
     *
     * @param string $data
     *
     * @return string SQL field
     */
    public function name($data)
    {
        if (is_string($data)) {
            $data = str_replace('"__"', '__', $data);
        }

        return parent::name($data);
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

        if (!$quote) {
            return $fields;
        }
        $count = count($fields);

        if ($count >= 1 && false === strpos($fields[0], 'COUNT(*)')) {
            $result = [];
            for ($i = 0; $i < $count; ++$i) {
                if (!preg_match('/^.+\\(.*\\)/', $fields[$i]) && !preg_match('/\s+AS\s+/', $fields[$i])) {
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

                    $prepend = '';
                    if (false !== strpos($fields[$i], 'DISTINCT')) {
                        $prepend = 'DISTINCT ';
                        $fields[$i] = trim(str_replace('DISTINCT', '', $fields[$i]));
                    }

                    if (false === strrpos($fields[$i], '.')) {
                        $fields[$i] = $prepend.$this->name($alias).'.'.$this->name($fields[$i]).' AS '.$this->name($alias.'__'.$fields[$i]);
                    } else {
                        $build = explode('.', $fields[$i]);
                        $fields[$i] = $prepend.$this->name($build[0]).'.'.$this->name($build[1]).' AS '.$this->name($build[0].'__'.$build[1]);
                    }
                } else {
                    $fields[$i] = preg_replace_callback('/\(([\s\.\w]+)\)/', [&$this, '__quoteFunctionField'], $fields[$i]);
                }
                $result[] = $fields[$i];
            }

            return $result;
        }

        return $fields;
    }

    /**
     * Auxiliary function to quote matched `(Model.fields)` from a preg_replace_callback call
     * Quotes the fields in a function call.
     *
     * @param string matched string
     *
     * @return string quoted strig
     */
    public function __quoteFunctionField($match)
    {
        $prepend = '';
        if (false !== strpos($match[1], 'DISTINCT')) {
            $prepend = 'DISTINCT ';
            $match[1] = trim(str_replace('DISTINCT', '', $match[1]));
        }
        $constant = preg_match('/^\d+|NULL|FALSE|TRUE$/i', $match[1]);

        if (!$constant && false === strpos($match[1], '.')) {
            $match[1] = $this->name($match[1]);
        } elseif (!$constant) {
            $parts = explode('.', $match[1]);
            if (!Set::numeric($parts)) {
                $match[1] = $this->name($match[1]);
            }
        }

        return '('.$prepend.$match[1].')';
    }

    /**
     * Returns an array of the indexes in given datasource name.
     *
     * @param string $model Name of model to inspect
     *
     * @return array Fields in table. Keys are column and unique
     */
    public function index($model)
    {
        $index = [];
        $table = $this->fullTableName($model, false);
        if ($table) {
            $indexes = $this->query("SELECT c2.relname, i.indisprimary, i.indisunique, i.indisclustered, i.indisvalid, pg_catalog.pg_get_indexdef(i.indexrelid, 0, true) as statement, c2.reltablespace
			FROM pg_catalog.pg_class c, pg_catalog.pg_class c2, pg_catalog.pg_index i
			WHERE c.oid  = (
				SELECT c.oid
				FROM pg_catalog.pg_class c LEFT JOIN pg_catalog.pg_namespace n ON n.oid = c.relnamespace
				WHERE c.relname ~ '^(".$table.")$'
					AND pg_catalog.pg_table_is_visible(c.oid)
					AND n.nspname ~ '^(".$this->config['schema'].")$'
			)
			AND c.oid = i.indrelid AND i.indexrelid = c2.oid
			ORDER BY i.indisprimary DESC, i.indisunique DESC, c2.relname", false);
            foreach ($indexes as $i => $info) {
                $key = array_pop($info);
                if ($key['indisprimary']) {
                    $key['relname'] = 'PRIMARY';
                }
                $col = [];
                preg_match('/\(([^\)]+)\)/', $key['statement'], $indexColumns);
                $parsedColumn = $indexColumns[1];
                if (false !== strpos($indexColumns[1], ',')) {
                    $parsedColumn = explode(', ', $indexColumns[1]);
                }
                $index[$key['relname']]['unique'] = $key['indisunique'];
                $index[$key['relname']]['column'] = $parsedColumn;
            }
        }

        return $index;
    }

    /**
     * Alter the Schema of a table.
     *
     * @param array  $compare Results of CakeSchema::compare()
     * @param string $table   name of the table
     *
     * @return array
     */
    public function alterSchema($compare, $table = null)
    {
        if (!is_array($compare)) {
            return false;
        }
        $out = '';
        $colList = [];
        foreach ($compare as $curTable => $types) {
            $indexes = $colList = [];
            if (!$table || $table == $curTable) {
                $out .= 'ALTER TABLE '.$this->fullTableName($curTable)." \n";
                foreach ($types as $type => $column) {
                    if (isset($column['indexes'])) {
                        $indexes[$type] = $column['indexes'];
                        unset($column['indexes']);
                    }
                    switch ($type) {
                        case 'add':
                            foreach ($column as $field => $col) {
                                $col['name'] = $field;
                                $colList[] = 'ADD COLUMN '.$this->buildColumn($col);
                            }
                        break;
                        case 'drop':
                            foreach ($column as $field => $col) {
                                $col['name'] = $field;
                                $colList[] = 'DROP COLUMN '.$this->name($field);
                            }
                        break;
                        case 'change':
                            foreach ($column as $field => $col) {
                                if (!isset($col['name'])) {
                                    $col['name'] = $field;
                                }
                                $fieldName = $this->name($field);

                                $default = isset($col['default']) ? $col['default'] : null;
                                $nullable = isset($col['null']) ? $col['null'] : null;
                                unset($col['default'], $col['null']);
                                $colList[] = 'ALTER COLUMN '.$fieldName.' TYPE '.str_replace([$fieldName, 'NOT NULL'], '', $this->buildColumn($col));
                                if (isset($nullable)) {
                                    $nullable = ($nullable) ? 'DROP NOT NULL' : 'SET NOT NULL';
                                    $colList[] = 'ALTER COLUMN '.$fieldName.'  '.$nullable;
                                }

                                if (isset($default)) {
                                    $colList[] = 'ALTER COLUMN '.$fieldName.'  SET DEFAULT '.$this->value($default, $col['type']);
                                } else {
                                    $colList[] = 'ALTER COLUMN '.$fieldName.'  DROP DEFAULT';
                                }
                            }
                        break;
                    }
                }
                if (isset($indexes['drop']['PRIMARY'])) {
                    $colList[] = 'DROP CONSTRAINT '.$curTable.'_pkey';
                }
                if (isset($indexes['add']['PRIMARY'])) {
                    $cols = $indexes['add']['PRIMARY']['column'];
                    if (is_array($cols)) {
                        $cols = implode(', ', $cols);
                    }
                    $colList[] = 'ADD PRIMARY KEY ('.$cols.')';
                }

                if (!empty($colList)) {
                    $out .= "\t".implode(",\n\t", $colList).";\n\n";
                } else {
                    $out = '';
                }
                $out .= implode(";\n\t", $this->_alterIndexes($curTable, $indexes));
            }
        }

        return $out;
    }

    /**
     * Generate PostgreSQL index alteration statements for a table.
     *
     * @param string $table Table to alter indexes for
     * @param array  $new   Indexes to add and drop
     *
     * @return array Index alteration statements
     */
    public function _alterIndexes($table, $indexes)
    {
        $alter = [];
        if (isset($indexes['drop'])) {
            foreach ($indexes['drop'] as $name => $value) {
                $out = 'DROP ';
                if ('PRIMARY' == $name) {
                    continue;
                } else {
                    $out .= 'INDEX '.$name;
                }
                $alter[] = $out;
            }
        }
        if (isset($indexes['add'])) {
            foreach ($indexes['add'] as $name => $value) {
                $out = 'CREATE ';
                if ('PRIMARY' == $name) {
                    continue;
                } else {
                    if (!empty($value['unique'])) {
                        $out .= 'UNIQUE ';
                    }
                    $out .= 'INDEX ';
                }
                if (is_array($value['column'])) {
                    $out .= $name.' ON '.$table.' ('.implode(', ', array_map([&$this, 'name'], $value['column'])).')';
                } else {
                    $out .= $name.' ON '.$table.' ('.$this->name($value['column']).')';
                }
                $alter[] = $out;
            }
        }

        return $alter;
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

        $floats = [
            'float', 'float4', 'float8', 'double', 'double precision', 'decimal', 'real', 'numeric',
        ];

        switch (true) {
            case in_array($col, ['date', 'time', 'inet', 'boolean']):
                return $col;
            case false !== strpos($col, 'timestamp'):
                return 'datetime';
            case 0 === strpos($col, 'time'):
                return 'time';
            case false !== strpos($col, 'int') && 'interval' != $col:
                return 'integer';
            case false !== strpos($col, 'char') || 'uuid' == $col:
                return 'string';
            case false !== strpos($col, 'text'):
                return 'text';
            case false !== strpos($col, 'bytea'):
                return 'binary';
            case in_array($col, $floats):
                return 'float';
            default:
                return 'text';
            break;
        }
    }

    /**
     * Gets the length of a database-native column description, or null if no length.
     *
     * @param string $real Real database-layer column type (i.e. "varchar(255)")
     *
     * @return int An integer representing the length of the column
     */
    public function length($real)
    {
        $col = str_replace([')', 'unsigned'], '', $real);
        $limit = null;

        if (false !== strpos($col, '(')) {
            list($col, $limit) = explode('(', $col);
        }
        if ('uuid' == $col) {
            return 36;
        }
        if (null != $limit) {
            return intval($limit);
        }

        return null;
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
        $num_fields = pg_num_fields($results);
        $index = 0;
        $j = 0;

        while ($j < $num_fields) {
            $columnName = pg_field_name($results, $j);

            if (strpos($columnName, '__')) {
                $parts = explode('__', $columnName);
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
        if ($row = pg_fetch_row($this->results)) {
            $resultRow = [];

            foreach ($row as $index => $field) {
                list($table, $column) = $this->map[$index];
                $type = pg_field_type($this->results, $index);

                switch ($type) {
                    case 'bool':
                        $resultRow[$table][$column] = $this->boolean($row[$index], false);
                    break;
                    case 'binary':
                    case 'bytea':
                        $resultRow[$table][$column] = pg_unescape_bytea($row[$index]);
                    break;
                    default:
                        $resultRow[$table][$column] = $row[$index];
                    break;
                }
            }

            return $resultRow;
        } else {
            return false;
        }
    }

    /**
     * Translates between PHP boolean values and PostgreSQL boolean values.
     *
     * @param mixed $data  Value to be translated
     * @param bool  $quote True to quote value, false otherwise
     *
     * @return mixed Converted boolean value
     */
    public function boolean($data, $quote = true)
    {
        switch (true) {
            case true === $data || false === $data:
                return $data;
            case 't' === $data || 'f' === $data:
                return 't' === $data;
            case 'true' === $data || 'false' === $data:
                return 'true' === $data;
            case 'TRUE' === $data || 'FALSE' === $data:
                return 'TRUE' === $data;
            default:
                return (bool) $data;
            break;
        }
    }

    /**
     * Sets the database encoding.
     *
     * @param mixed $enc Database encoding
     *
     * @return bool True on success, false on failure
     */
    public function setEncoding($enc)
    {
        return 0 == pg_set_client_encoding($this->connection, $enc);
    }

    /**
     * Gets the database encoding.
     *
     * @return string The database encoding
     */
    public function getEncoding()
    {
        return pg_client_encoding($this->connection);
    }

    /**
     * Generate a Postgres-native column schema string.
     *
     * @param array $column an array structured like the following:
     *                      array('name'=>'value', 'type'=>'value'[, options]),
     *                      where options can be 'default', 'length', or 'key'
     *
     * @return string
     */
    public function buildColumn($column)
    {
        $col = $this->columns[$column['type']];
        if (!isset($col['length']) && !isset($col['limit'])) {
            unset($column['length']);
        }
        $out = preg_replace('/integer\([0-9]+\)/', 'integer', parent::buildColumn($column));
        $out = str_replace('integer serial', 'serial', $out);
        if (strpos($out, 'timestamp DEFAULT')) {
            if (isset($column['null']) && $column['null']) {
                $out = str_replace('DEFAULT NULL', '', $out);
            } else {
                $out = str_replace('DEFAULT NOT NULL', '', $out);
            }
        }
        if (strpos($out, 'DEFAULT DEFAULT')) {
            if (isset($column['null']) && $column['null']) {
                $out = str_replace('DEFAULT DEFAULT', 'DEFAULT NULL', $out);
            } elseif (in_array($column['type'], ['integer', 'float'])) {
                $out = str_replace('DEFAULT DEFAULT', 'DEFAULT 0', $out);
            } elseif ('boolean' == $column['type']) {
                $out = str_replace('DEFAULT DEFAULT', 'DEFAULT FALSE', $out);
            }
        }

        return $out;
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
        if (!is_array($indexes)) {
            return [];
        }
        foreach ($indexes as $name => $value) {
            if ('PRIMARY' == $name) {
                $out = 'PRIMARY KEY  ('.$this->name($value['column']).')';
            } else {
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
            }
            $join[] = $out;
        }

        return $join;
    }

    /**
     * Overrides DboSource::renderStatement to handle schema generation with Postgres-style indexes.
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

                foreach ($indexes as $i => $index) {
                    if (preg_match('/PRIMARY KEY/', $index)) {
                        unset($indexes[$i]);
                        $columns[] = $index;
                        break;
                    }
                }
                $join = ['columns' => ",\n\t", 'indexes' => "\n"];

                foreach (['columns', 'indexes'] as $var) {
                    if (is_array(${$var})) {
                        ${$var} = implode($join[$var], array_filter(${$var}));
                    }
                }

                return "CREATE TABLE {$table} (\n\t{$columns}\n);\n{$indexes}";
            break;
            default:
                return parent::renderStatement($type, $data);
            break;
        }
    }
}
