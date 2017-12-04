<?php
/**
 * Schema database management for CakePHP.
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
 * @since         CakePHP(tm) v 1.2.0.5550
 *
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::import('Core', ['Model', 'ConnectionManager']);

/**
 * Base Class for Schema management.
 */
class CakeSchema extends Object
{
    /**
     * Name of the App Schema.
     *
     * @var string
     */
    public $name = null;

    /**
     * Path to write location.
     *
     * @var string
     */
    public $path = null;

    /**
     * File to write.
     *
     * @var string
     */
    public $file = 'schema.php';

    /**
     * Connection used for read.
     *
     * @var string
     */
    public $connection = 'default';

    /**
     * plugin name.
     *
     * @var string
     */
    public $plugin = null;

    /**
     * Set of tables.
     *
     * @var array
     */
    public $tables = [];

    /**
     * Constructor.
     *
     * @param array $options optional load object properties
     */
    public function __construct($options = [])
    {
        parent::__construct();

        if (empty($options['name'])) {
            $this->name = preg_replace('/schema$/i', '', get_class($this));
        }
        if (!empty($options['plugin'])) {
            $this->plugin = $options['plugin'];
        }

        if ('cake' === strtolower($this->name)) {
            $this->name = Inflector::camelize(Inflector::slug(Configure::read('App.dir')));
        }

        if (empty($options['path'])) {
            if (is_dir(CONFIGS.'schema')) {
                $this->path = CONFIGS.'schema';
            } else {
                $this->path = CONFIGS.'sql';
            }
        }

        $options = array_merge(get_object_vars($this), $options);
        $this->_build($options);
    }

    /**
     * Builds schema object properties.
     *
     * @param array $data loaded object properties
     */
    public function _build($data)
    {
        $file = null;
        foreach ($data as $key => $val) {
            if (!empty($val)) {
                if (!in_array($key, ['plugin', 'name', 'path', 'file', 'connection', 'tables', '_log'])) {
                    $this->tables[$key] = $val;
                    unset($this->{$key});
                } elseif ('tables' !== $key) {
                    if ('name' === $key && $val !== $this->name && !isset($data['file'])) {
                        $file = Inflector::underscore($val).'.php';
                    }
                    $this->{$key} = $val;
                }
            }
        }
        if (file_exists($this->path.DS.$file) && is_file($this->path.DS.$file)) {
            $this->file = $file;
        } elseif (!empty($this->plugin)) {
            $this->path = App::pluginPath($this->plugin).'config'.DS.'schema';
        }
    }

    /**
     * Before callback to be implemented in subclasses.
     *
     * @param array $events schema object properties
     *
     * @return bool Should process continue
     */
    public function before($event = [])
    {
        return true;
    }

    /**
     * After callback to be implemented in subclasses.
     *
     * @param array $events schema object properties
     */
    public function after($event = [])
    {
    }

    /**
     * Reads database and creates schema tables.
     *
     * @param array $options schema object properties
     *
     * @return array Set of name and tables
     */
    public function &load($options = [])
    {
        if (is_string($options)) {
            $options = ['path' => $options];
        }

        $this->_build($options);
        extract(get_object_vars($this));

        $class = $name.'Schema';

        if (!class_exists($class)) {
            if (file_exists($path.DS.$file) && is_file($path.DS.$file)) {
                require_once $path.DS.$file;
            } elseif (file_exists($path.DS.'schema.php') && is_file($path.DS.'schema.php')) {
                require_once $path.DS.'schema.php';
            }
        }

        if (class_exists($class)) {
            $Schema = new $class($options);

            return $Schema;
        }
        $false = false;

        return $false;
    }

    /**
     * Reads database and creates schema tables.
     *
     * Options
     *
     * - 'connection' - the db connection to use
     * - 'name' - name of the schema
     * - 'models' - a list of models to use, or false to ignore models
     *
     * @param array $options schema object properties
     *
     * @return array Array indexed by name and tables
     */
    public function read($options = [])
    {
        extract(array_merge(
            [
                'connection' => $this->connection,
                'name' => $this->name,
                'models' => true,
            ],
            $options
        ));
        $db = &ConnectionManager::getDataSource($connection);

        App::import('Model', 'AppModel');
        if (isset($this->plugin)) {
            App::import('Model', Inflector::camelize($this->plugin).'AppModel');
        }

        $tables = [];
        $currentTables = $db->listSources();

        $prefix = null;
        if (isset($db->config['prefix'])) {
            $prefix = $db->config['prefix'];
        }

        if (!is_array($models) && false !== $models) {
            if (isset($this->plugin)) {
                $models = App::objects('model', App::pluginPath($this->plugin).'models'.DS, false);
            } else {
                $models = App::objects('model');
            }
        }

        if (is_array($models)) {
            foreach ($models as $model) {
                $importModel = $model;
                if (isset($this->plugin)) {
                    $importModel = $this->plugin.'.'.$model;
                }
                if (!App::import('Model', $importModel)) {
                    continue;
                }
                $vars = get_class_vars($model);
                if (empty($vars['useDbConfig']) || $vars['useDbConfig'] != $connection) {
                    continue;
                }

                if (PHP5) {
                    $Object = ClassRegistry::init(['class' => $model, 'ds' => $connection]);
                } else {
                    $Object = &ClassRegistry::init(['class' => $model, 'ds' => $connection]);
                }

                if (is_object($Object) && false !== $Object->useTable) {
                    $fulltable = $table = $db->fullTableName($Object, false);
                    if ($prefix && 0 !== strpos($table, $prefix)) {
                        continue;
                    }
                    $table = $this->_noPrefixTable($prefix, $table);

                    if (in_array($fulltable, $currentTables)) {
                        $key = array_search($fulltable, $currentTables);
                        if (empty($tables[$table])) {
                            $tables[$table] = $this->__columns($Object);
                            $tables[$table]['indexes'] = $db->index($Object);
                            $tables[$table]['tableParameters'] = $db->readTableParameters($fulltable);
                            unset($currentTables[$key]);
                        }
                        if (!empty($Object->hasAndBelongsToMany)) {
                            foreach ($Object->hasAndBelongsToMany as $Assoc => $assocData) {
                                if (isset($assocData['with'])) {
                                    $class = $assocData['with'];
                                }
                                if (is_object($Object->$class)) {
                                    $withTable = $db->fullTableName($Object->$class, false);
                                    if ($prefix && 0 !== strpos($withTable, $prefix)) {
                                        continue;
                                    }
                                    if (in_array($withTable, $currentTables)) {
                                        $key = array_search($withTable, $currentTables);
                                        $noPrefixWith = $this->_noPrefixTable($prefix, $withTable);

                                        $tables[$noPrefixWith] = $this->__columns($Object->$class);
                                        $tables[$noPrefixWith]['indexes'] = $db->index($Object->$class);
                                        $tables[$noPrefixWith]['tableParameters'] = $db->readTableParameters($withTable);
                                        unset($currentTables[$key]);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        if (!empty($currentTables)) {
            foreach ($currentTables as $table) {
                if ($prefix) {
                    if (0 !== strpos($table, $prefix)) {
                        continue;
                    }
                    $table = $this->_noPrefixTable($prefix, $table);
                }
                $Object = new AppModel([
                    'name' => Inflector::classify($table), 'table' => $table, 'ds' => $connection,
                ]);

                $systemTables = [
                    'aros', 'acos', 'aros_acos', Configure::read('Session.table'), 'i18n',
                ];

                $fulltable = $db->fullTableName($Object, false);

                if (in_array($table, $systemTables)) {
                    $tables[$Object->table] = $this->__columns($Object);
                    $tables[$Object->table]['indexes'] = $db->index($Object);
                    $tables[$Object->table]['tableParameters'] = $db->readTableParameters($fulltable);
                } elseif (false === $models) {
                    $tables[$table] = $this->__columns($Object);
                    $tables[$table]['indexes'] = $db->index($Object);
                    $tables[$table]['tableParameters'] = $db->readTableParameters($fulltable);
                } else {
                    $tables['missing'][$table] = $this->__columns($Object);
                    $tables['missing'][$table]['indexes'] = $db->index($Object);
                    $tables['missing'][$table]['tableParameters'] = $db->readTableParameters($fulltable);
                }
            }
        }

        ksort($tables);

        return compact('name', 'tables');
    }

    /**
     * Writes schema file from object or options.
     *
     * @param mixed $object  schema object or options array
     * @param array $options schema object properties to override object
     *
     * @return mixed false or string written to file
     */
    public function write($object, $options = [])
    {
        if (is_object($object)) {
            $object = get_object_vars($object);
            $this->_build($object);
        }

        if (is_array($object)) {
            $options = $object;
            unset($object);
        }

        extract(array_merge(
            get_object_vars($this), $options
        ));

        $out = "class {$name}Schema extends CakeSchema {\n";

        $out .= "\tvar \$name = '{$name}';\n\n";

        if ($path !== $this->path) {
            $out .= "\tvar \$path = '{$path}';\n\n";
        }

        if ($file !== $this->file) {
            $out .= "\tvar \$file = '{$file}';\n\n";
        }

        if ('default' !== $connection) {
            $out .= "\tvar \$connection = '{$connection}';\n\n";
        }

        $out .= "\tfunction before(\$event = array()) {\n\t\treturn true;\n\t}\n\n\tfunction after(\$event = array()) {\n\t}\n\n";

        if (empty($tables)) {
            $this->read();
        }

        foreach ($tables as $table => $fields) {
            if (!is_numeric($table) && 'missing' !== $table) {
                $out .= $this->generateTable($table, $fields);
            }
        }
        $out .= "}\n";

        $File = new File($path.DS.$file, true);
        $header = '$Id';
        $content = "<?php \n/* {$name} schema generated on: ".date('Y-m-d H:i:s').' : '.time()."*/\n{$out}?>";
        $content = $File->prepare($content);
        if ($File->write($content)) {
            return $content;
        }

        return false;
    }

    /**
     * Generate the code for a table. Takes a table name and $fields array
     * Returns a completed variable declaration to be used in schema classes.
     *
     * @param string $table  table name you want returned
     * @param array  $fields array of field information to generate the table with
     *
     * @return string Variable declaration for a schema class
     */
    public function generateTable($table, $fields)
    {
        $out = "\tvar \${$table} = array(\n";
        if (is_array($fields)) {
            $cols = [];
            foreach ($fields as $field => $value) {
                if ('indexes' != $field && 'tableParameters' != $field) {
                    if (is_string($value)) {
                        $type = $value;
                        $value = ['type' => $type];
                    }
                    $col = "\t\t'{$field}' => array('type' => '".$value['type']."', ";
                    unset($value['type']);
                    $col .= join(', ', $this->__values($value));
                } elseif ('indexes' == $field) {
                    $col = "\t\t'indexes' => array(";
                    $props = [];
                    foreach ((array) $value as $key => $index) {
                        $props[] = "'{$key}' => array(".join(', ', $this->__values($index)).')';
                    }
                    $col .= join(', ', $props);
                } elseif ('tableParameters' == $field) {
                    //@todo add charset, collate and engine here
                    $col = "\t\t'tableParameters' => array(";
                    $props = [];
                    foreach ((array) $value as $key => $param) {
                        $props[] = "'{$key}' => '$param'";
                    }
                    $col .= join(', ', $props);
                }
                $col .= ')';
                $cols[] = $col;
            }
            $out .= join(",\n", $cols);
        }
        $out .= "\n\t);\n";

        return $out;
    }

    /**
     * Compares two sets of schemas.
     *
     * @param mixed $old Schema object or array
     * @param mixed $new Schema object or array
     *
     * @return array Tables (that are added, dropped, or changed)
     */
    public function compare($old, $new = null)
    {
        if (empty($new)) {
            $new = &$this;
        }
        if (is_array($new)) {
            if (isset($new['tables'])) {
                $new = $new['tables'];
            }
        } else {
            $new = $new->tables;
        }

        if (is_array($old)) {
            if (isset($old['tables'])) {
                $old = $old['tables'];
            }
        } else {
            $old = $old->tables;
        }
        $tables = [];
        foreach ($new as $table => $fields) {
            if ('missing' == $table) {
                continue;
            }
            if (!array_key_exists($table, $old)) {
                $tables[$table]['add'] = $fields;
            } else {
                $diff = $this->_arrayDiffAssoc($fields, $old[$table]);
                if (!empty($diff)) {
                    $tables[$table]['add'] = $diff;
                }
                $diff = $this->_arrayDiffAssoc($old[$table], $fields);
                if (!empty($diff)) {
                    $tables[$table]['drop'] = $diff;
                }
            }

            foreach ($fields as $field => $value) {
                if (isset($old[$table][$field])) {
                    $diff = $this->_arrayDiffAssoc($value, $old[$table][$field]);
                    if (!empty($diff) && 'indexes' !== $field && 'tableParameters' !== $field) {
                        $tables[$table]['change'][$field] = array_merge($old[$table][$field], $diff);
                    }
                }

                if (isset($tables[$table]['add'][$field]) && 'indexes' !== $field && 'tableParameters' !== $field) {
                    $wrapper = array_keys($fields);
                    if ($column = array_search($field, $wrapper)) {
                        if (isset($wrapper[$column - 1])) {
                            $tables[$table]['add'][$field]['after'] = $wrapper[$column - 1];
                        }
                    }
                }
            }

            if (isset($old[$table]['indexes']) && isset($new[$table]['indexes'])) {
                $diff = $this->_compareIndexes($new[$table]['indexes'], $old[$table]['indexes']);
                if ($diff) {
                    if (!isset($tables[$table])) {
                        $tables[$table] = [];
                    }
                    if (isset($diff['drop'])) {
                        $tables[$table]['drop']['indexes'] = $diff['drop'];
                    }
                    if ($diff && isset($diff['add'])) {
                        $tables[$table]['add']['indexes'] = $diff['add'];
                    }
                }
            }
            if (isset($old[$table]['tableParameters']) && isset($new[$table]['tableParameters'])) {
                $diff = $this->_compareTableParameters($new[$table]['tableParameters'], $old[$table]['tableParameters']);
                if ($diff) {
                    $tables[$table]['change']['tableParameters'] = $diff;
                }
            }
        }

        return $tables;
    }

    /**
     * Extended array_diff_assoc noticing change from/to NULL values.
     *
     * It behaves almost the same way as array_diff_assoc except for NULL values: if
     * one of the values is not NULL - change is detected. It is useful in situation
     * where one value is strval('') ant other is strval(null) - in string comparing
     * methods this results as EQUAL, while it is not.
     *
     * @param array $array1 Base array
     * @param array $array2 Corresponding array checked for equality
     *
     * @return array difference as array with array(keys => values) from input array
     *               where match was not found
     */
    public function _arrayDiffAssoc($array1, $array2)
    {
        $difference = [];
        foreach ($array1 as $key => $value) {
            if (!array_key_exists($key, $array2)) {
                $difference[$key] = $value;
                continue;
            }
            $correspondingValue = $array2[$key];
            if (is_null($value) !== is_null($correspondingValue)) {
                $difference[$key] = $value;
                continue;
            }
            if (is_bool($value) !== is_bool($correspondingValue)) {
                $difference[$key] = $value;
                continue;
            }
            if (is_array($value) && is_array($correspondingValue)) {
                continue;
            }
            $compare = strval($value);
            $correspondingValue = strval($correspondingValue);
            if ($compare === $correspondingValue) {
                continue;
            }
            $difference[$key] = $value;
        }

        return $difference;
    }

    /**
     * Formats Schema columns from Model Object.
     *
     * @param array $values options keys(type, null, default, key, length, extra)
     *
     * @return array Formatted values
     */
    public function __values($values)
    {
        $vals = [];
        if (is_array($values)) {
            foreach ($values as $key => $val) {
                if (is_array($val)) {
                    $vals[] = "'{$key}' => array('".implode("', '", $val)."')";
                } elseif (!is_numeric($key)) {
                    $val = var_export($val, true);
                    $vals[] = "'{$key}' => {$val}";
                }
            }
        }

        return $vals;
    }

    /**
     * Formats Schema columns from Model Object.
     *
     * @param array $Obj model object
     *
     * @return array Formatted columns
     */
    public function __columns(&$Obj)
    {
        $db = &ConnectionManager::getDataSource($Obj->useDbConfig);
        $fields = $Obj->schema(true);
        $columns = $props = [];
        foreach ($fields as $name => $value) {
            if ($Obj->primaryKey == $name) {
                $value['key'] = 'primary';
            }
            if (!isset($db->columns[$value['type']])) {
                trigger_error(sprintf(__('Schema generation error: invalid column type %s does not exist in DBO', true), $value['type']), E_USER_NOTICE);
                continue;
            } else {
                $defaultCol = $db->columns[$value['type']];
                if (isset($defaultCol['limit']) && $defaultCol['limit'] == $value['length']) {
                    unset($value['length']);
                } elseif (isset($defaultCol['length']) && $defaultCol['length'] == $value['length']) {
                    unset($value['length']);
                }
                unset($value['limit']);
            }

            if (isset($value['default']) && ('' === $value['default'] || false === $value['default'])) {
                unset($value['default']);
            }
            if (empty($value['length'])) {
                unset($value['length']);
            }
            if (empty($value['key'])) {
                unset($value['key']);
            }
            $columns[$name] = $value;
        }

        return $columns;
    }

    /**
     * Compare two schema files table Parameters.
     *
     * @param array $new New indexes
     * @param array $old Old indexes
     *
     * @return mixed false on failure, or an array of parameters to add & drop
     */
    public function _compareTableParameters($new, $old)
    {
        if (!is_array($new) || !is_array($old)) {
            return false;
        }
        $change = $this->_arrayDiffAssoc($new, $old);

        return $change;
    }

    /**
     * Compare two schema indexes.
     *
     * @param array $new New indexes
     * @param array $old Old indexes
     *
     * @return mixed false on failure or array of indexes to add and drop
     */
    public function _compareIndexes($new, $old)
    {
        if (!is_array($new) || !is_array($old)) {
            return false;
        }

        $add = $drop = [];

        $diff = $this->_arrayDiffAssoc($new, $old);
        if (!empty($diff)) {
            $add = $diff;
        }

        $diff = $this->_arrayDiffAssoc($old, $new);
        if (!empty($diff)) {
            $drop = $diff;
        }

        foreach ($new as $name => $value) {
            if (isset($old[$name])) {
                $newUnique = isset($value['unique']) ? $value['unique'] : 0;
                $oldUnique = isset($old[$name]['unique']) ? $old[$name]['unique'] : 0;
                $newColumn = $value['column'];
                $oldColumn = $old[$name]['column'];

                $diff = false;

                if ($newUnique != $oldUnique) {
                    $diff = true;
                } elseif (is_array($newColumn) && is_array($oldColumn)) {
                    $diff = ($newColumn !== $oldColumn);
                } elseif (is_string($newColumn) && is_string($oldColumn)) {
                    $diff = ($newColumn != $oldColumn);
                } else {
                    $diff = true;
                }
                if ($diff) {
                    $drop[$name] = null;
                    $add[$name] = $value;
                }
            }
        }

        return array_filter(compact('add', 'drop'));
    }

    /**
     * Trim the table prefix from the full table name, and return the prefix-less table.
     *
     * @param string $prefix Table prefix
     * @param string $table  Full table name
     *
     * @return string Prefix-less table name
     */
    public function _noPrefixTable($prefix, $table)
    {
        return preg_replace('/^'.preg_quote($prefix).'/', '', $table);
    }
}
