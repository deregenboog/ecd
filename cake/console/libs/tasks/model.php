<?php
/**
 * The ModelTask handles creating and updating models files.
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
 * @since         CakePHP(tm) v 1.2
 *
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
include_once dirname(__FILE__).DS.'bake.php';

/**
 * Task class for creating and updating model files.
 */
class ModelTask extends BakeTask
{
    /**
     * path to MODELS directory.
     *
     * @var string
     */
    public $path = MODELS;

    /**
     * tasks.
     *
     * @var array
     */
    public $tasks = ['DbConfig', 'Fixture', 'Test', 'Template'];

    /**
     * Tables to skip when running all().
     *
     * @var array
     */
    public $skipTables = ['i18n'];

    /**
     * Holds tables found on connection.
     *
     * @var array
     */
    public $_tables = [];

    /**
     * Holds validation method map.
     *
     * @var array
     */
    public $_validations = [];

    /**
     * Execution method always used for tasks.
     */
    public function execute()
    {
        App::import('Model', 'Model', false);

        if (empty($this->args)) {
            $this->__interactive();
        }

        if (!empty($this->args[0])) {
            $this->interactive = false;
            if (!isset($this->connection)) {
                $this->connection = 'default';
            }
            if ('all' == strtolower($this->args[0])) {
                return $this->all();
            }
            $model = $this->_modelName($this->args[0]);
            $object = $this->_getModelObject($model);
            if ($this->bake($object, false)) {
                if ($this->_checkUnitTest()) {
                    $this->bakeFixture($model);
                    $this->bakeTest($model);
                }
            }
        }
    }

    /**
     * Bake all models at once.
     */
    public function all()
    {
        $this->listAll($this->connection, false);
        $unitTestExists = $this->_checkUnitTest();
        foreach ($this->_tables as $table) {
            if (in_array($table, $this->skipTables)) {
                continue;
            }
            $modelClass = Inflector::classify($table);
            $this->out(sprintf(__('Baking %s', true), $modelClass));
            $object = $this->_getModelObject($modelClass);
            if ($this->bake($object, false) && $unitTestExists) {
                $this->bakeFixture($modelClass);
                $this->bakeTest($modelClass);
            }
        }
    }

    /**
     * Get a model object for a class name.
     *
     * @param string $className name of class you want model to be
     *
     * @return object Model instance
     */
    public function &_getModelObject($className, $table = null)
    {
        if (!$table) {
            $table = Inflector::tableize($className);
        }
        $object = new Model(['name' => $className, 'table' => $table, 'ds' => $this->connection]);

        return $object;
    }

    /**
     * Generate a key value list of options and a prompt.
     *
     * @param array  $options Array of options to use for the selections. indexes must start at 0
     * @param string $prompt  prompt to use for options list
     * @param int    $default the default option for the given prompt
     *
     * @return result of user choice
     */
    public function inOptions($options, $prompt = null, $default = null)
    {
        $valid = false;
        $max = count($options);
        while (!$valid) {
            foreach ($options as $i => $option) {
                $this->out($i + 1 .'. '.$option);
            }
            if (empty($prompt)) {
                $prompt = __('Make a selection from the choices above', true);
            }
            $choice = $this->in($prompt, null, $default);
            if (intval($choice) > 0 && intval($choice) <= $max) {
                $valid = true;
            }
        }

        return $choice - 1;
    }

    /**
     * Handles interactive baking.
     */
    public function __interactive()
    {
        $this->hr();
        $this->out(sprintf("Bake Model\nPath: %s", $this->path));
        $this->hr();
        $this->interactive = true;

        $primaryKey = 'id';
        $validate = $associations = [];

        if (empty($this->connection)) {
            $this->connection = $this->DbConfig->getConfig();
        }
        $currentModelName = $this->getName();
        $useTable = $this->getTable($currentModelName);
        $db = &ConnectionManager::getDataSource($this->connection);
        $fullTableName = $db->fullTableName($useTable);

        if (in_array($useTable, $this->_tables)) {
            $tempModel = new Model(['name' => $currentModelName, 'table' => $useTable, 'ds' => $this->connection]);
            $fields = $tempModel->schema(true);
            if (!array_key_exists('id', $fields)) {
                $primaryKey = $this->findPrimaryKey($fields);
            }
        } else {
            $this->err(sprintf(__('Table %s does not exist, cannot bake a model without a table.', true), $useTable));
            $this->_stop();

            return false;
        }
        $displayField = $tempModel->hasField(['name', 'title']);
        if (!$displayField) {
            $displayField = $this->findDisplayField($tempModel->schema());
        }

        $prompt = __("Would you like to supply validation criteria \nfor the fields in your model?", true);
        $wannaDoValidation = $this->in($prompt, ['y', 'n'], 'y');
        if (false !== array_search($useTable, $this->_tables) && 'y' == strtolower($wannaDoValidation)) {
            $validate = $this->doValidation($tempModel);
        }

        $prompt = __("Would you like to define model associations\n(hasMany, hasOne, belongsTo, etc.)?", true);
        $wannaDoAssoc = $this->in($prompt, ['y', 'n'], 'y');
        if ('y' == strtolower($wannaDoAssoc)) {
            $associations = $this->doAssociations($tempModel);
        }

        $this->out();
        $this->hr();
        $this->out(__('The following Model will be created:', true));
        $this->hr();
        $this->out('Name:       '.$currentModelName);

        if ('default' !== $this->connection) {
            $this->out(sprintf(__('DB Config:  %s', true), $this->connection));
        }
        if ($fullTableName !== Inflector::tableize($currentModelName)) {
            $this->out(sprintf(__('DB Table:   %s', true), $fullTableName));
        }
        if ('id' != $primaryKey) {
            $this->out(sprintf(__('Primary Key: %s', true), $primaryKey));
        }
        if (!empty($validate)) {
            $this->out(sprintf(__('Validation: %s', true), print_r($validate, true)));
        }
        if (!empty($associations)) {
            $this->out(__('Associations:', true));
            $assocKeys = ['belongsTo', 'hasOne', 'hasMany', 'hasAndBelongsToMany'];
            foreach ($assocKeys as $assocKey) {
                $this->_printAssociation($currentModelName, $assocKey, $associations);
            }
        }

        $this->hr();
        $looksGood = $this->in(__('Look okay?', true), ['y', 'n'], 'y');

        if ('y' == strtolower($looksGood)) {
            $vars = compact('associations', 'validate', 'primaryKey', 'useTable', 'displayField');
            $vars['useDbConfig'] = $this->connection;
            if ($this->bake($currentModelName, $vars)) {
                if ($this->_checkUnitTest()) {
                    $this->bakeFixture($currentModelName, $useTable);
                    $this->bakeTest($currentModelName, $useTable, $associations);
                }
            }
        } else {
            return false;
        }
    }

    /**
     * Print out all the associations of a particular type.
     *
     * @param string $modelName    name of the model relations belong to
     * @param string $type         Name of association you want to see. i.e. 'belongsTo'
     * @param string $associations collection of associations
     */
    public function _printAssociation($modelName, $type, $associations)
    {
        if (!empty($associations[$type])) {
            for ($i = 0; $i < count($associations[$type]); ++$i) {
                $out = "\t".$modelName.' '.$type.' '.$associations[$type][$i]['alias'];
                $this->out($out);
            }
        }
    }

    /**
     * Finds a primary Key in a list of fields.
     *
     * @param array $fields array of fields that might have a primary key
     *
     * @return string name of field that is a primary key
     */
    public function findPrimaryKey($fields)
    {
        foreach ($fields as $name => $field) {
            if (isset($field['key']) && 'primary' == $field['key']) {
                break;
            }
        }

        return $this->in(__('What is the primaryKey?', true), null, $name);
    }

    /**
     * interact with the user to find the displayField value for a model.
     *
     * @param array $fields Array of fields to look for and choose as a displayField
     *
     * @return mixed Name of field to use for displayField or false if the user declines to choose
     */
    public function findDisplayField($fields)
    {
        $fieldNames = array_keys($fields);
        $prompt = __("A displayField could not be automatically detected\nwould you like to choose one?", true);
        $continue = $this->in($prompt, ['y', 'n']);
        if ('n' == strtolower($continue)) {
            return false;
        }
        $prompt = __('Choose a field from the options above:', true);
        $choice = $this->inOptions($fieldNames, $prompt);

        return $fieldNames[$choice];
    }

    /**
     * Handles Generation and user interaction for creating validation.
     *
     * @param object $model model to have validations generated for
     *
     * @return array $validate array of user selected validations
     */
    public function doValidation(&$model)
    {
        if (!is_object($model)) {
            return false;
        }
        $fields = $model->schema();

        if (empty($fields)) {
            return false;
        }
        $validate = [];
        $this->initValidations();
        foreach ($fields as $fieldName => $field) {
            $validation = $this->fieldValidation($fieldName, $field, $model->primaryKey);
            if (!empty($validation)) {
                $validate[$fieldName] = $validation;
            }
        }

        return $validate;
    }

    /**
     * Populate the _validations array.
     */
    public function initValidations()
    {
        $options = $choices = [];
        if (class_exists('Validation')) {
            $parent = get_class_methods(get_parent_class('Validation'));
            $options = get_class_methods('Validation');
            $options = array_diff($options, $parent);
        }
        sort($options);
        $default = 1;
        foreach ($options as $key => $option) {
            if ('_' != $option[0] && 'getinstance' != strtolower($option)) {
                $choices[$default] = strtolower($option);
                ++$default;
            }
        }
        $choices[$default] = 'none'; // Needed since index starts at 1
        $this->_validations = $choices;

        return $choices;
    }

    /**
     * Does individual field validation handling.
     *
     * @param string $fieldName name of field to be validated
     * @param array  $metaData  metadata for field
     *
     * @return array array of validation for the field
     */
    public function fieldValidation($fieldName, $metaData, $primaryKey = 'id')
    {
        $defaultChoice = count($this->_validations);
        $validate = $alreadyChosen = [];

        $anotherValidator = 'y';
        while ('y' == $anotherValidator) {
            if ($this->interactive) {
                $this->out();
                $this->out(sprintf(__('Field: %s', true), $fieldName));
                $this->out(sprintf(__('Type: %s', true), $metaData['type']));
                $this->hr();
                $this->out(__('Please select one of the following validation options:', true));
                $this->hr();
            }

            $prompt = '';
            for ($i = 1; $i < $defaultChoice; ++$i) {
                $prompt .= $i.' - '.$this->_validations[$i]."\n";
            }
            $prompt .= sprintf(__("%s - Do not do any validation on this field.\n", true), $defaultChoice);
            $prompt .= __("... or enter in a valid regex validation string.\n", true);

            $methods = array_flip($this->_validations);
            $guess = $defaultChoice;
            if (1 != $metaData['null'] && !in_array($fieldName, [$primaryKey, 'created', 'modified', 'updated'])) {
                if ('email' == $fieldName) {
                    $guess = $methods['email'];
                } elseif ('string' == $metaData['type'] && 36 == $metaData['length']) {
                    $guess = $methods['uuid'];
                } elseif ('string' == $metaData['type']) {
                    $guess = $methods['notempty'];
                } elseif ('integer' == $metaData['type']) {
                    $guess = $methods['numeric'];
                } elseif ('boolean' == $metaData['type']) {
                    $guess = $methods['boolean'];
                } elseif ('date' == $metaData['type']) {
                    $guess = $methods['date'];
                } elseif ('time' == $metaData['type']) {
                    $guess = $methods['time'];
                }
            }

            if (true === $this->interactive) {
                $choice = $this->in($prompt, null, $guess);
                if (in_array($choice, $alreadyChosen)) {
                    $this->out(__("You have already chosen that validation rule,\nplease choose again", true));
                    continue;
                }
                if (!isset($this->_validations[$choice]) && is_numeric($choice)) {
                    $this->out(__('Please make a valid selection.', true));
                    continue;
                }
                $alreadyChosen[] = $choice;
            } else {
                $choice = $guess;
            }

            if (isset($this->_validations[$choice])) {
                $validatorName = $this->_validations[$choice];
            } else {
                $validatorName = Inflector::slug($choice);
            }

            if ($choice != $defaultChoice) {
                if (is_numeric($choice) && isset($this->_validations[$choice])) {
                    $validate[$validatorName] = $this->_validations[$choice];
                } else {
                    $validate[$validatorName] = $choice;
                }
            }
            if (true == $this->interactive && $choice != $defaultChoice) {
                $anotherValidator = $this->in(__('Would you like to add another validation rule?', true), ['y', 'n'], 'n');
            } else {
                $anotherValidator = 'n';
            }
        }

        return $validate;
    }

    /**
     * Handles associations.
     *
     * @param object $model
     *
     * @return array $assocaitons
     */
    public function doAssociations(&$model)
    {
        if (!is_object($model)) {
            return false;
        }
        if (true === $this->interactive) {
            $this->out(__('One moment while the associations are detected.', true));
        }

        $fields = $model->schema(true);
        if (empty($fields)) {
            return false;
        }

        if (empty($this->_tables)) {
            $this->_tables = $this->getAllTables();
        }

        $associations = [
            'belongsTo' => [], 'hasMany' => [], 'hasOne' => [], 'hasAndBelongsToMany' => [],
        ];
        $possibleKeys = [];

        $associations = $this->findBelongsTo($model, $associations);
        $associations = $this->findHasOneAndMany($model, $associations);
        $associations = $this->findHasAndBelongsToMany($model, $associations);

        if (true !== $this->interactive) {
            unset($associations['hasOne']);
        }

        if (true === $this->interactive) {
            $this->hr();
            if (empty($associations)) {
                $this->out(__('None found.', true));
            } else {
                $this->out(__('Please confirm the following associations:', true));
                $this->hr();
                $associations = $this->confirmAssociations($model, $associations);
            }
            $associations = $this->doMoreAssociations($model, $associations);
        }

        return $associations;
    }

    /**
     * Find belongsTo relations and add them to the associations list.
     *
     * @param object $model        model instance of model being generated
     * @param array  $associations Array of inprogress associations
     *
     * @return array $associations with belongsTo added in
     */
    public function findBelongsTo(&$model, $associations)
    {
        $fields = $model->schema(true);
        foreach ($fields as $fieldName => $field) {
            $offset = strpos($fieldName, '_id');
            if ($fieldName != $model->primaryKey && 'parent_id' != $fieldName && false !== $offset) {
                $tmpModelName = $this->_modelNameFromKey($fieldName);
                $associations['belongsTo'][] = [
                    'alias' => $tmpModelName,
                    'className' => $tmpModelName,
                    'foreignKey' => $fieldName,
                ];
            } elseif ('parent_id' == $fieldName) {
                $associations['belongsTo'][] = [
                    'alias' => 'Parent'.$model->name,
                    'className' => $model->name,
                    'foreignKey' => $fieldName,
                ];
            }
        }

        return $associations;
    }

    /**
     * Find the hasOne and HasMany relations and add them to associations list.
     *
     * @param object $model        Model instance being generated
     * @param array  $associations Array of inprogress associations
     *
     * @return array $associations with hasOne and hasMany added in
     */
    public function findHasOneAndMany(&$model, $associations)
    {
        $foreignKey = $this->_modelKey($model->name);
        foreach ($this->_tables as $otherTable) {
            $tempOtherModel = $this->_getModelObject($this->_modelName($otherTable), $otherTable);
            $modelFieldsTemp = $tempOtherModel->schema(true);

            $pattern = '/_'.preg_quote($model->table, '/').'|'.preg_quote($model->table, '/').'_/';
            $possibleJoinTable = preg_match($pattern, $otherTable);
            if (true == $possibleJoinTable) {
                continue;
            }
            foreach ($modelFieldsTemp as $fieldName => $field) {
                $assoc = false;
                if ($fieldName != $model->primaryKey && $fieldName == $foreignKey) {
                    $assoc = [
                        'alias' => $tempOtherModel->name,
                        'className' => $tempOtherModel->name,
                        'foreignKey' => $fieldName,
                    ];
                } elseif ($otherTable == $model->table && 'parent_id' == $fieldName) {
                    $assoc = [
                        'alias' => 'Child'.$model->name,
                        'className' => $model->name,
                        'foreignKey' => $fieldName,
                    ];
                }
                if ($assoc) {
                    $associations['hasOne'][] = $assoc;
                    $associations['hasMany'][] = $assoc;
                }
            }
        }

        return $associations;
    }

    /**
     * Find the hasAndBelongsToMany relations and add them to associations list.
     *
     * @param object $model        Model instance being generated
     * @param array  $associations Array of inprogress associations
     *
     * @return array $associations with hasAndBelongsToMany added in
     */
    public function findHasAndBelongsToMany(&$model, $associations)
    {
        $foreignKey = $this->_modelKey($model->name);
        foreach ($this->_tables as $otherTable) {
            $tempOtherModel = $this->_getModelObject($this->_modelName($otherTable), $otherTable);
            $modelFieldsTemp = $tempOtherModel->schema(true);

            $offset = strpos($otherTable, $model->table.'_');
            $otherOffset = strpos($otherTable, '_'.$model->table);

            if (false !== $offset) {
                $offset = strlen($model->table.'_');
                $habtmName = $this->_modelName(substr($otherTable, $offset));
                $associations['hasAndBelongsToMany'][] = [
                    'alias' => $habtmName,
                    'className' => $habtmName,
                    'foreignKey' => $foreignKey,
                    'associationForeignKey' => $this->_modelKey($habtmName),
                    'joinTable' => $otherTable,
                ];
            } elseif (false !== $otherOffset) {
                $habtmName = $this->_modelName(substr($otherTable, 0, $otherOffset));
                $associations['hasAndBelongsToMany'][] = [
                    'alias' => $habtmName,
                    'className' => $habtmName,
                    'foreignKey' => $foreignKey,
                    'associationForeignKey' => $this->_modelKey($habtmName),
                    'joinTable' => $otherTable,
                ];
            }
        }

        return $associations;
    }

    /**
     * Interact with the user and confirm associations.
     *
     * @param array $model        temporary Model instance
     * @param array $associations array of associations to be confirmed
     *
     * @return array Array of confirmed associations
     */
    public function confirmAssociations(&$model, $associations)
    {
        foreach ($associations as $type => $settings) {
            if (!empty($associations[$type])) {
                $count = count($associations[$type]);
                $response = 'y';
                foreach ($associations[$type] as $i => $assoc) {
                    $prompt = "{$model->name} {$type} {$assoc['alias']}?";
                    $response = $this->in($prompt, ['y', 'n'], 'y');

                    if ('n' == strtolower($response)) {
                        unset($associations[$type][$i]);
                    } elseif ('hasMany' == $type) {
                        unset($associations['hasOne'][$i]);
                    }
                }
                $associations[$type] = array_merge($associations[$type]);
            }
        }

        return $associations;
    }

    /**
     * Interact with the user and generate additional non-conventional associations.
     *
     * @param object $model        Temporary model instance
     * @param array  $associations array of associations
     *
     * @return array array of associations
     */
    public function doMoreAssociations($model, $associations)
    {
        $prompt = __('Would you like to define some additional model associations?', true);
        $wannaDoMoreAssoc = $this->in($prompt, ['y', 'n'], 'n');
        $possibleKeys = $this->_generatePossibleKeys();
        while ('y' == strtolower($wannaDoMoreAssoc)) {
            $assocs = ['belongsTo', 'hasOne', 'hasMany', 'hasAndBelongsToMany'];
            $this->out(__('What is the association type?', true));
            $assocType = intval($this->inOptions($assocs, __('Enter a number', true)));

            $this->out(__("For the following options be very careful to match your setup exactly.\nAny spelling mistakes will cause errors.", true));
            $this->hr();

            $alias = $this->in(__('What is the alias for this association?', true));
            $className = $this->in(sprintf(__('What className will %s use?', true), $alias), null, $alias);
            $suggestedForeignKey = null;

            if (0 == $assocType) {
                $showKeys = $possibleKeys[$model->table];
                $suggestedForeignKey = $this->_modelKey($alias);
            } else {
                $otherTable = Inflector::tableize($className);
                if (in_array($otherTable, $this->_tables)) {
                    if ($assocType < 3) {
                        $showKeys = $possibleKeys[$otherTable];
                    } else {
                        $showKeys = null;
                    }
                } else {
                    $otherTable = $this->in(__('What is the table for this model?', true));
                    $showKeys = $possibleKeys[$otherTable];
                }
                $suggestedForeignKey = $this->_modelKey($model->name);
            }
            if (!empty($showKeys)) {
                $this->out(__('A helpful List of possible keys', true));
                $foreignKey = $this->inOptions($showKeys, __('What is the foreignKey?', true));
                $foreignKey = $showKeys[intval($foreignKey)];
            }
            if (!isset($foreignKey)) {
                $foreignKey = $this->in(__('What is the foreignKey? Specify your own.', true), null, $suggestedForeignKey);
            }
            if (3 == $assocType) {
                $associationForeignKey = $this->in(__('What is the associationForeignKey?', true), null, $this->_modelKey($model->name));
                $joinTable = $this->in(__('What is the joinTable?', true));
            }
            $associations[$assocs[$assocType]] = array_values((array) $associations[$assocs[$assocType]]);
            $count = count($associations[$assocs[$assocType]]);
            $i = ($count > 0) ? $count : 0;
            $associations[$assocs[$assocType]][$i]['alias'] = $alias;
            $associations[$assocs[$assocType]][$i]['className'] = $className;
            $associations[$assocs[$assocType]][$i]['foreignKey'] = $foreignKey;
            if (3 == $assocType) {
                $associations[$assocs[$assocType]][$i]['associationForeignKey'] = $associationForeignKey;
                $associations[$assocs[$assocType]][$i]['joinTable'] = $joinTable;
            }
            $wannaDoMoreAssoc = $this->in(__('Define another association?', true), ['y', 'n'], 'y');
        }

        return $associations;
    }

    /**
     * Finds all possible keys to use on custom associations.
     *
     * @return array array of tables and possible keys
     */
    public function _generatePossibleKeys()
    {
        $possible = [];
        foreach ($this->_tables as $otherTable) {
            $tempOtherModel = new Model(['table' => $otherTable, 'ds' => $this->connection]);
            $modelFieldsTemp = $tempOtherModel->schema(true);
            foreach ($modelFieldsTemp as $fieldName => $field) {
                if ('integer' == $field['type'] || 'string' == $field['type']) {
                    $possible[$otherTable][] = $fieldName;
                }
            }
        }

        return $possible;
    }

    /**
     * Assembles and writes a Model file.
     *
     * @param mixed $name Model name or object
     * @param mixed $data if array and $name is not an object assume bake data, otherwise boolean
     */
    public function bake($name, $data = [])
    {
        if (is_object($name)) {
            if (false == $data) {
                $data = $associations = [];
                $data['associations'] = $this->doAssociations($name, $associations);
                $data['validate'] = $this->doValidation($name);
            }
            $data['primaryKey'] = $name->primaryKey;
            $data['useTable'] = $name->table;
            $data['useDbConfig'] = $name->useDbConfig;
            $data['name'] = $name = $name->name;
        } else {
            $data['name'] = $name;
        }
        $defaults = ['associations' => [], 'validate' => [], 'primaryKey' => 'id',
            'useTable' => null, 'useDbConfig' => 'default', 'displayField' => null, ];
        $data = array_merge($defaults, $data);

        $this->Template->set($data);
        $this->Template->set('plugin', Inflector::camelize($this->plugin));
        $out = $this->Template->generate('classes', 'model');

        $path = $this->getPath();
        $filename = $path.Inflector::underscore($name).'.php';
        $this->out("\nBaking model class for $name...");
        $this->createFile($filename, $out);
        ClassRegistry::flush();

        return $out;
    }

    /**
     * Assembles and writes a unit test file.
     *
     * @param string $className Model class name
     */
    public function bakeTest($className)
    {
        $this->Test->interactive = $this->interactive;
        $this->Test->plugin = $this->plugin;
        $this->Test->connection = $this->connection;

        return $this->Test->bake('Model', $className);
    }

    /**
     * outputs the a list of possible models or controllers from database.
     *
     * @param string $useDbConfig Database configuration name
     */
    public function listAll($useDbConfig = null)
    {
        $this->_tables = $this->getAllTables($useDbConfig);

        if (true === $this->interactive) {
            $this->out(__('Possible Models based on your current database:', true));
            $this->_modelNames = [];
            $count = count($this->_tables);
            for ($i = 0; $i < $count; ++$i) {
                $this->_modelNames[] = $this->_modelName($this->_tables[$i]);
                $this->out($i + 1 .'. '.$this->_modelNames[$i]);
            }
        }

        return $this->_tables;
    }

    /**
     * Interact with the user to determine the table name of a particular model.
     *
     * @param string $modelName   name of the model you want a table for
     * @param string $useDbConfig name of the database config you want to get tables from
     */
    public function getTable($modelName, $useDbConfig = null)
    {
        if (!isset($useDbConfig)) {
            $useDbConfig = $this->connection;
        }
        App::import('Model', 'ConnectionManager', false);

        $db = &ConnectionManager::getDataSource($useDbConfig);
        $useTable = Inflector::tableize($modelName);
        $fullTableName = $db->fullTableName($useTable, false);
        $tableIsGood = false;

        if (false === array_search($useTable, $this->_tables)) {
            $this->out();
            $this->out(sprintf(__("Given your model named '%s',\nCake would expect a database table named '%s'", true), $modelName, $fullTableName));
            $tableIsGood = $this->in(__('Do you want to use this table?', true), ['y', 'n'], 'y');
        }
        if ('n' == strtolower($tableIsGood)) {
            $useTable = $this->in(__('What is the name of the table?', true));
        }

        return $useTable;
    }

    /**
     * Get an Array of all the tables in the supplied connection
     * will halt the script if no tables are found.
     *
     * @param string $useDbConfig connection name to scan
     *
     * @return array array of tables in the database
     */
    public function getAllTables($useDbConfig = null)
    {
        if (!isset($useDbConfig)) {
            $useDbConfig = $this->connection;
        }
        App::import('Model', 'ConnectionManager', false);

        $tables = [];
        $db = &ConnectionManager::getDataSource($useDbConfig);
        $db->cacheSources = false;
        $usePrefix = empty($db->config['prefix']) ? '' : $db->config['prefix'];
        if ($usePrefix) {
            foreach ($db->listSources() as $table) {
                if (!strncmp($table, $usePrefix, strlen($usePrefix))) {
                    $tables[] = substr($table, strlen($usePrefix));
                }
            }
        } else {
            $tables = $db->listSources();
        }
        if (empty($tables)) {
            $this->err(__('Your database does not have any tables.', true));
            $this->_stop();
        }

        return $tables;
    }

    /**
     * Forces the user to specify the model he wants to bake, and returns the selected model name.
     *
     * @return string the model name
     */
    public function getName($useDbConfig = null)
    {
        $this->listAll($useDbConfig);

        $enteredModel = '';

        while ('' == $enteredModel) {
            $enteredModel = $this->in(__("Enter a number from the list above,\ntype in the name of another model, or 'q' to exit", true), null, 'q');

            if ('q' === $enteredModel) {
                $this->out(__('Exit', true));
                $this->_stop();
            }

            if ('' == $enteredModel || intval($enteredModel) > count($this->_modelNames)) {
                $this->err(__("The model name you supplied was empty,\nor the number you selected was not an option. Please try again.", true));
                $enteredModel = '';
            }
        }
        if (intval($enteredModel) > 0 && intval($enteredModel) <= count($this->_modelNames)) {
            $currentModelName = $this->_modelNames[intval($enteredModel) - 1];
        } else {
            $currentModelName = $enteredModel;
        }

        return $currentModelName;
    }

    /**
     * Displays help contents.
     */
    public function help()
    {
        $this->hr();
        $this->out('Usage: cake bake model <arg1>');
        $this->hr();
        $this->out('Arguments:');
        $this->out();
        $this->out('<name>');
        $this->out("\tName of the model to bake. Can use Plugin.name");
        $this->out("\tas a shortcut for plugin baking.");
        $this->out();
        $this->out('Params:');
        $this->out();
        $this->out('-connection <config>');
        $this->out("\tset db config <config>. uses 'default' if none is specified");
        $this->out();
        $this->out('Commands:');
        $this->out();
        $this->out('model');
        $this->out("\tbakes model in interactive mode.");
        $this->out();
        $this->out('model <name>');
        $this->out("\tbakes model file with no associations or validation");
        $this->out();
        $this->out('model all');
        $this->out("\tbakes all model files with associations and validation");
        $this->out();
        $this->_stop();
    }

    /**
     * Interact with FixtureTask to automatically bake fixtures when baking models.
     *
     * @param string $className Name of class to bake fixture for
     * @param string $useTable  optional table name for fixture to use
     *
     * @see FixtureTask::bake
     */
    public function bakeFixture($className, $useTable = null)
    {
        $this->Fixture->interactive = $this->interactive;
        $this->Fixture->connection = $this->connection;
        $this->Fixture->plugin = $this->plugin;
        $this->Fixture->bake($className, $useTable);
    }
}
