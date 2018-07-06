<?php
/**
 * LdapSource.
 *
 * @author euphrate_ylb
 * @date 07/2007
 *
 * @license http://blog.fbollon.net DWYWWI (Do whatever you want with it)
 */

// We don't use it at all in this project, it is commited as a reference in
// case we need it in the future.

class LdapSource extends DataSource
{
    public $description = 'Ldap Data Source';

    public $_baseConfig = [
        'host' => 'localhost',
        'port' => 389,
        'version' => 3,
    ];

    // Lifecycle --------------------------------------------------------------

    /**
     * Constructor.
     */
    public function __construct($config = null)
    {
        $this->debug = Configure :: read() > 0;
        $this->fullDebug = Configure :: read() > 1;
        parent :: __construct($config);

        return $this->connect();
    }

    /**
     * Destructor. Closes connection to the database.
     */
    public function __destruct()
    {
        $this->close();
        parent :: __destruct();
    }

    // Connection --------------------------------------------------------------
    public function connect()
    {
        $config = $this->config;
        $this->connected = false;

        $this->connection = ldap_connect($config['host'], $config['port']);
        ldap_set_option($this->connection, LDAP_OPT_PROTOCOL_VERSION, $config['version']);
        if (ldap_bind($this->connection, $config['login'], $config['password'])) {
            $this->connected = true;
        }

        return $this->connected;
    }

    /**
     * Disconnects database, kills the connection and says the connection is closed,
     * and if DEBUG is turned on, the log for this object is shown.
     */
    public function close()
    {
        if ($this->fullDebug && Configure :: read() > 1) {
            $this->showLog();
        }
        $this->disconnect();
    }

    public function disconnect()
    {
        @ldap_free_result($this->results);
        $this->connected = !@ldap_unbind($this->connection);

        return !$this->connected;
    }

    /**
     * Checks if it's connected to the database.
     *
     * @return bool True if the database is connected, else false
     */
    public function isConnected()
    {
        return $this->connected;
    }

    /**
     * Reconnects to database server with optional new settings.
     *
     * @param array $config An array defining the new configuration settings
     *
     * @return bool True on success, false on failure
     */
    public function reconnect($config = null)
    {
        $this->disconnect();
        if (null != $config) {
            $this->config = am($this->_baseConfig, $this->config, $config);
        }

        return $this->connect();
    }

    // CRUD --------------------------------------------------------------

    /**
     * The "R" in CRUD.
     *
     * @param Model $model
     * @param array $queryData
     * @param int   $recursive Number of levels of association
     *
     * @return unknown
     */
    public function read(&$model, $queryData = [], $recursive = null)
    {
        $this->__scrubQueryData($queryData);

        if (!is_null($recursive)) {
            $_recursive = $model->recursive;
            $model->recursive = $recursive;
        }

        // Prepare query data ------------------------
        $queryData['conditions'] = $this->_conditions($queryData['conditions'], $model);
        $queryData['targetDn'] = $model->useTable;
        $queryData['type'] = 'search';
        if (empty($queryData['order'])) {
            $queryData['order'] = [$model->primaryKey];
        }

        // Associations links --------------------------
        foreach ($model->__associations as $type) {
            foreach ($model->{$type} as $assoc => $assocData) {
                if ($model->recursive > -1) {
                    $linkModel = &$model->{$assoc};
                    $linkedModels[] = $type.'/'.$assoc;
                }
            }
        }

        // Execute search query ------------------------
        $res = $this->_executeQuery($queryData);
        if (0 == $this->lastNumRows()) {
            return false;
        }

        // Format results  -----------------------------
        ldap_sort($this->connection, $res, $queryData['order'][0]);
        $resultSet = ldap_get_entries($this->connection, $res);
        $resultSet = $this->_ldapFormat($model, $resultSet);

        // Query on linked models  ----------------------
        if ($model->recursive > 0) {
            foreach ($model->__associations as $type) {
                foreach ($model->{$type} as $assoc => $assocData) {
                    $db = null;
                    $linkModel = &$model->{$assoc};

                    if ($model->useDbConfig == $linkModel->useDbConfig) {
                        $db = &$this;
                    } else {
                        $db = &ConnectionManager :: getDataSource($linkModel->useDbConfig);
                    }

                    if (isset($db) && null != $db) {
                        $stack = [$assoc];
                        $array = [];
                        $db->queryAssociation($model, $linkModel, $type, $assoc, $assocData, $array, true, $resultSet, $model->recursive - 1, $stack);
                        unset($db);
                    }
                }
            }
        }

        if (!is_null($recursive)) {
            $model->recursive = $_recursive;
        }

        return $resultSet;
    }

    // Public --------------------------------------------------------------
    public function generateAssociationQuery(&$model, &$linkModel, $type, $association, $assocData, &$queryData, $external, &$resultSet)
    {
        $this->__scrubQueryData($queryData);

        switch ($type) {
            case 'hasOne':
                $id = $resultSet[$model->name][$model->primaryKey];
                $queryData['conditions'] = trim($assocData['foreignKey']).'='.trim($id);
                $queryData['targetDn'] = $linkModel->useTable;
                $queryData['type'] = 'search';
                $queryData['limit'] = 1;

                return $queryData;

            case 'belongsTo':
                $id = $resultSet[$model->name][$assocData['foreignKey']];
                $queryData['conditions'] = trim($linkModel->primaryKey).'='.trim($id);
                $queryData['targetDn'] = $linkModel->useTable;
                $queryData['type'] = 'search';
                $queryData['limit'] = 1;

                return $queryData;

            case 'hasMany':
                $id = $resultSet[$model->name][$model->primaryKey];
                $queryData['conditions'] = trim($assocData['foreignKey']).'='.trim($id);
                $queryData['targetDn'] = $linkModel->useTable;
                $queryData['type'] = 'search';
                $queryData['limit'] = $assocData['limit'];

                return $queryData;

            case 'hasAndBelongsToMany':
                return null;
        }

        return null;
    }

    public function queryAssociation(&$model, &$linkModel, $type, $association, $assocData, &$queryData, $external, &$resultSet, $recursive, $stack)
    {
        if (!isset($resultSet) || !is_array($resultSet)) {
            if (Configure :: read() > 0) {
                e('<div style = "font: Verdana bold 12px; color: #FF0000">SQL Error in model '.$model->name.': ');
                if (isset($this->error) && null != $this->error) {
                    e($this->error);
                }
                e('</div>');
            }

            return null;
        }

        $count = count($resultSet);
        for ($i = 0; $i < $count; ++$i) {
            $row = &$resultSet[$i];
            $queryData = $this->generateAssociationQuery($model, $linkModel, $type, $association, $assocData, $queryData, $external, $row);
            $fetch = $this->_executeQuery($queryData);
            $fetch = ldap_get_entries($this->connection, $fetch);
            $fetch = $this->_ldapFormat($linkModel, $fetch);

            if (!empty($fetch) && is_array($fetch)) {
                if ($recursive > 0) {
                    foreach ($linkModel->__associations as $type1) {
                        foreach ($linkModel->{$type1 } as $assoc1 => $assocData1) {
                            $deepModel = &$linkModel->{$assocData1['className']};
                            if ($deepModel->alias != $model->name) {
                                $tmpStack = $stack;
                                $tmpStack[] = $assoc1;
                                if ($linkModel->useDbConfig == $deepModel->useDbConfig) {
                                    $db = &$this;
                                } else {
                                    $db = &ConnectionManager :: getDataSource($deepModel->useDbConfig);
                                }
                                $queryData = [];
                                $db->queryAssociation($linkModel, $deepModel, $type1, $assoc1, $assocData1, $queryData, true, $fetch, $recursive - 1, $tmpStack);
                            }
                        }
                    }
                }
                $this->__mergeAssociation($resultSet[$i], $fetch, $association, $type);
            } else {
                $tempArray[0][$association] = false;
                $this->__mergeAssociation($resultSet[$i], $tempArray, $association, $type);
            }
        }
    }

    /**
     * Returns a formatted error message from previous database operation.
     *
     * @return string Error message with error number
     */
    public function lastError()
    {
        if (ldap_errno($this->connection)) {
            return ldap_errno($this->connection).': '.ldap_error($this->connection);
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
        if ($this->_result and is_resource($this->_result)) {
            return @ldap_count_entries($this->connection, $this->_result);
        }

        return null;
    }

    // Usefull public (static) functions--------------------------------------------

    /**
     * Convert Active Directory timestamps to unix ones.
     *
     * @param int $ad_timestamp Active directory timestamp
     *
     * @return int Unix timestamp
     */
    public function convertTimestamp_ADToUnix($ad_timestamp)
    {
        $epoch_diff = 11644473600; // difference 1601<>1970 in seconds. see reference URL
        $date_timestamp = $ad_timestamp * 0.0000001;
        $unix_timestamp = $date_timestamp - $epoch_diff;

        return $unix_timestamp;
    }

    // convertTimestamp_ADToUnix

    // Wont be implemeneted -----------------------------------------------------

    /**
     * Function required but not really implemented.
     */
    public function describe(&$model)
    {
        $fields[] = ['name' => '--NotYetImplemented--',
                        'type' => '--NotYetImplemented--',
                        'null' => '--NotYetImplemented--', ];

        return $fields;
    }

    /**
     * Function not supported.
     */
    public function execute($query)
    {
        return null;
    }

    /**
     * Function not supported.
     */
    public function fetchAll($query, $cache = true)
    {
        return [];
    }

    // Logs --------------------------------------------------------------

    /**
     * Log given LDAP query.
     *
     * @param string $query LDAP statement
     * @todo: Add hook to log errors instead of returning false
     */
    public function logQuery($query)
    {
        ++$this->_queriesCnt;
        $this->_queriesTime += $this->took;
        $this->_queriesLog[] = [
            'query' => $query,
            'error' => $this->error,
            'affected' => $this->affected,
            'numRows' => $this->numRows,
            'took' => $this->took,
        ];
        if (count($this->_queriesLog) > $this->_queriesLogMax) {
            array_pop($this->_queriesLog);
        }
        if ($this->error) {
            return false;
        }
    }

    /**
     * Outputs the contents of the queries log.
     *
     * @param bool $sorted
     */
    public function showLog($sorted = false)
    {
        if ($sorted) {
            $log = sortByKey($this->_queriesLog, 'took', 'desc', SORT_NUMERIC);
        } else {
            $log = $this->_queriesLog;
        }

        if ($this->_queriesCnt > 1) {
            $text = 'queries';
        } else {
            $text = 'query';
        }

        if ('cli' != php_sapi_name()) {
            echo "<table id=\"cakeSqlLog\" cellspacing=\"0\" border = \"0\">\n<caption>{$this->_queriesCnt} {$text} took {$this->_queriesTime} ms</caption>\n";
            echo "<thead>\n<tr><th>Nr</th><th>Query</th><th>Error</th><th>Affected</th><th>Num. rows</th><th>Took (ms)</th></tr>\n</thead>\n<tbody>\n";

            foreach ($log as $k => $i) {
                echo '<tr><td>'.($k + 1)."</td><td>{$i['query']}</td><td>{$i['error']}</td><td style = \"text-align: right\">{$i['affected']}</td><td style = \"text-align: right\">{$i['numRows']}</td><td style = \"text-align: right\">{$i['took']}</td></tr>\n";
            }
            echo "</table>\n";
        } else {
            foreach ($log as $k => $i) {
                echo($k + 1).". {$i['query']} {$i['error']}\n";
            }
        }
    }

    /**
     * Output information about a LDAP query. The query, number of rows in resultset,
     * and execution time in microseconds. If the query fails, an error is output instead.
     *
     * @param string $query Query to show information on
     */
    public function showQuery($query)
    {
        $error = $this->error;
        if (strlen($query) > 200 && !$this->fullDebug) {
            $query = substr($query, 0, 200).'[...]';
        }

        if ($this->debug || $error) {
            echo "<p style = \"text-align:left\"><b>Query:</b> {$query} <small>[Aff:{$this->affected} Num:{$this->numRows} Took:{$this->took}ms]</small>";
            if ($error) {
                echo "<br /><span style = \"color:Red;text-align:left\"><b>ERROR:</b> {$this->error}</span>";
            }
            echo '</p>';
        }
    }

    // _ private --------------------------------------------------------------
    public function _conditions($conditions, $model)
    {
        $res = '';
        $key = $model->primaryKey;
        $name = $model->name;
        if (is_array($conditions)) {
            // Conditions expressed as an array
            if (empty($conditions)) {
                $conditions = ['equals' => [$key => null]];
            }

            $res = $this->__conditionsArrayToString($conditions);
        } else {
            // "valid" ldap search expression
            if (!strpos($conditions, '=')) {
                $conditions = $key.'='.trim($conditions);
            }

            $res = str_replace(["$name.$key", ' = '], [$key, '='], $conditions);
        }

        return $res;
    }

    /**
     * Convert an array into a ldap condition string.
     *
     * @param array $conditions condition
     *
     * @return string
     */
    public function __conditionsArrayToString($conditions)
    {
        $ops_rec = ['and' => ['prefix' => '&'], 'or' => ['prefix' => '|']];
        $ops_neg = ['and not' => [], 'or not' => [], 'not equals' => []];
        $ops_ter = ['equals' => ['null' => '*']];

        $ops = array_merge($ops_rec, $ops_neg, $ops_ter);

        if (is_array($conditions)) {
            $operand = array_keys($conditions);
            $operand = $operand[0];

            if (!in_array($operand, array_keys($ops))) {
                return null;
            }

            $children = $conditions[$operand];

            if (in_array($operand, array_keys($ops_rec))) {
                if (!is_array($children)) {
                    return null;
                }

                $tmp = '('.$ops_rec[$operand]['prefix'];
                foreach ($children as $key => $value) {
                    $child = [$key => $value];
                    $tmp .= $this->__conditionsArrayToString($child);
                }

                return $tmp.')';
            } elseif (in_array($operand, array_keys($ops_neg))) {
                if (!is_array($children)) {
                    return null;
                }

                $next_operand = trim(str_replace('not', '', $operand));

                return '(!'.$this->__conditionsArrayToString([$next_operand => $children]).')';
            } elseif (in_array($operand, array_keys($ops_ter))) {
                $tmp = '';
                foreach ($children as $key => $value) {
                    if (!is_array($value)) {
                        $tmp .= '('.$key.'='.((is_null($value)) ? $ops_ter['equals']['null'] : $value).')';
                    } else {
                        foreach ($value as $subvalue) {
                            $tmp .= $this->__conditionsArrayToString(['equals' => [$key => $subvalue]]);
                        }
                    }
                }

                return $tmp;
            }
        }
    }

    public function _executeQuery($queryData = [], $cache = true)
    {
        $t = getMicrotime();
        $query = $this->_queryToString($queryData);
        if ($cache && isset($this->_queryCache[$query])) {
            if (false !== strpos(trim(strtolower($query)), $queryData['type'])) {
                $res = $this->_queryCache[$query];
            }
        } else {
            switch ($queryData['type']) {
                case 'search':
                    // TODO pb ldap_search & $queryData['limit']
                    if ($res = @ldap_search($this->connection, $queryData['targetDn'].','.$this->config['basedn'],
                            $queryData['conditions'], $queryData['fields'], 0, $queryData['limit'])) {
                        if ($cache) {
                            if (false !== strpos(trim(strtolower($query)), $queryData['type'])) {
                                $this->_queryCache[$query] = $res;
                            }
                        }
                    } else {
                        $res = false;
                    }
                    break;
                case 'delete':
                    $res = @ldap_delete($this->connection, $queryData['targetDn'].','.$this->config['basedn']);
                    break;
                default:
                    $res = false;
                    break;
            }
        }

        $this->_result = $res;
        $this->took = round((getMicrotime() - $t) * 1000, 0);
        $this->error = $this->lastError();
        $this->numRows = $this->lastNumRows();

        if ($this->fullDebug) {
            $this->logQuery($query);
        }

        return $this->_result;
    }

    public function _queryToString($queryData)
    {
        $tmp = '';
        if (!empty($queryData['conditions'])) {
            $tmp .= ' | cond: '.$queryData['conditions'].' ';
        }

        if (!empty($queryData['targetDn'])) {
            $tmp .= ' | targetDn: '.$queryData['targetDn'].','.$this->config['basedn'].' ';
        }

        $fields = '';
        if (!empty($queryData['fields'])) {
            $fields .= ' | fields: ';
            foreach ($queryData['fields'] as $field) {
                $fields .= ' '.$field;
            }
            $tmp .= $queryData['fields'].' ';
        }

        if (!empty($queryData['order'])) {
            $tmp .= ' | order: '.$queryData['order'][0].' ';
        }

        if (!empty($queryData['limit'])) {
            $tmp .= ' | limit: '.$queryData['limit'];
        }

        return $queryData['type'].$tmp;
    }

    public function _ldapFormat(&$model, $data)
    {
        $res = [];
        foreach ($data as $key => $row) {
            if ('count' === $key) {
                continue;
            }

            foreach ($row as $key1 => $param) {
                if (!is_numeric($key1)) {
                    continue;
                }
                if (1 === $row[$param]['count']) {
                    $res[$key][$model->name][$param] = $row[$param][0];
                } else {
                    foreach ($row[$param] as $key2 => $item) {
                        if ('count' === $key2) {
                            continue;
                        }
                        $res[$key][$model->name][$param][] = $item;
                    }
                }
            }
        }

        return $res;
    }

    public function _ldapQuote($str)
    {
        return str_replace(
                ['\\', ' ', '*', '(', ')'],
                ['\\5c', '\\20', '\\2a', '\\28', '\\29'],
                $str
        );
    }

    // __ -----------------------------------------------------
    public function __mergeAssociation(&$data, $merge, $association, $type)
    {
        if (isset($merge[0]) && !isset($merge[0][$association])) {
            $association = Inflector :: pluralize($association);
        }

        if ('belongsTo' == $type || 'hasOne' == $type) {
            if (isset($merge[$association])) {
                $data[$association] = $merge[$association][0];
            } else {
                if (count($merge[0][$association]) > 1) {
                    foreach ($merge[0] as $assoc => $data2) {
                        if ($assoc != $association) {
                            $merge[0][$association][$assoc] = $data2;
                        }
                    }
                }
                if (!isset($data[$association])) {
                    $data[$association] = $merge[0][$association];
                } else {
                    if (is_array($merge[0][$association])) {
                        $data[$association] = array_merge($merge[0][$association], $data[$association]);
                    }
                }
            }
        } else {
            if (false === $merge[0][$association]) {
                if (!isset($data[$association])) {
                    $data[$association] = [];
                }
            } else {
                foreach ($merge as $i => $row) {
                    if (1 == count($row)) {
                        $data[$association][] = $row[$association];
                    } else {
                        $tmp = array_merge($row[$association], $row);
                        unset($tmp[$association]);
                        $data[$association][] = $tmp;
                    }
                }
            }
        }
    }

    /**
     * Private helper method to remove query metadata in given data array.
     *
     * @param array $data
     */
    public function __scrubQueryData(&$data)
    {
        if (!isset($data['type'])) {
            $data['type'] = 'default';
        }

        if (!isset($data['conditions'])) {
            $data['conditions'] = [];
        }

        if (!isset($data['targetDn'])) {
            $data['targetDn'] = null;
        }

        if (!isset($data['fields']) && empty($data['fields'])) {
            $data['fields'] = [];
        }

        if (!isset($data['order']) && empty($data['order'])) {
            $data['order'] = [];
        }

        if (!isset($data['limit'])) {
            $data['limit'] = null;
        }
    }
} // LdapSource
