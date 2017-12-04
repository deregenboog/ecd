<?php
/**
 * Translate behavior.
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
 * @since         CakePHP(tm) v 1.2.0.4525
 *
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * Translate behavior.
 *
 * @see http://book.cakephp.org/1.3/en/The-Manual/Core-Behaviors/Translate.html#Translate
 */
class TranslateBehavior extends ModelBehavior
{
    /**
     * Used for runtime configuration of model.
     *
     * @var array
     */
    public $runtime = [];

    /**
     * Stores the joinTable object for generating joins.
     *
     * @var object
     */
    public $_joinTable;

    /**
     * Stores the runtime model for generating joins.
     *
     * @var Model
     */
    public $_runtimeModel;

    /**
     * Callback.
     *
     * $config for TranslateBehavior should be
     * array( 'fields' => array('field_one',
     * 'field_two' => 'FieldAssoc', 'field_three'))
     *
     * With above example only one permanent hasMany will be joined (for field_two
     * as FieldAssoc)
     *
     * $config could be empty - and translations configured dynamically by
     * bindTranslation() method
     *
     * @param Model $model  model the behavior is being attached to
     * @param array $config array of configuration information
     *
     * @return mixed
     */
    public function setup(&$model, $config = [])
    {
        $db = &ConnectionManager::getDataSource($model->useDbConfig);
        if (!$db->connected) {
            trigger_error(
                sprintf(__('Datasource %s for TranslateBehavior of model %s is not connected', true), $model->useDbConfig, $model->alias),
                E_USER_ERROR
            );

            return false;
        }

        $this->settings[$model->alias] = [];
        $this->runtime[$model->alias] = ['fields' => []];
        $this->translateModel($model);

        return $this->bindTranslation($model, $config, false);
    }

    /**
     * Cleanup Callback unbinds bound translations and deletes setting information.
     *
     * @param Model $model model being detached
     */
    public function cleanup(&$model)
    {
        $this->unbindTranslation($model);
        unset($this->settings[$model->alias]);
        unset($this->runtime[$model->alias]);
    }

    /**
     * beforeFind Callback.
     *
     * @param Model $model model find is being run on
     * @param array $query array of Query parameters
     *
     * @return array Modified query
     */
    public function beforeFind(&$model, $query)
    {
        $locale = $this->_getLocale($model);
        if (empty($locale)) {
            return $query;
        }
        $db = &ConnectionManager::getDataSource($model->useDbConfig);
        $RuntimeModel = &$this->translateModel($model);

        if (!empty($RuntimeModel->tablePrefix)) {
            $tablePrefix = $RuntimeModel->tablePrefix;
        } else {
            $tablePrefix = $db->config['prefix'];
        }
        $joinTable = new StdClass();
        $joinTable->tablePrefix = $tablePrefix;
        $joinTable->table = $RuntimeModel->table;

        $this->_joinTable = $joinTable;
        $this->_runtimeModel = $RuntimeModel;

        if (is_string($query['fields']) && 'COUNT(*) AS '.$db->name('count') == $query['fields']) {
            $query['fields'] = 'COUNT(DISTINCT('.$db->name($model->alias.'.'.$model->primaryKey).')) '.$db->alias.'count';

            $query['joins'][] = [
                'type' => 'INNER',
                'alias' => $RuntimeModel->alias,
                'table' => $joinTable,
                'conditions' => [
                    $model->alias.'.'.$model->primaryKey => $db->identifier($RuntimeModel->alias.'.foreign_key'),
                    $RuntimeModel->alias.'.model' => $model->name,
                    $RuntimeModel->alias.'.locale' => $locale,
                ],
            ];
            $conditionFields = $this->_checkConditions($model, $query);
            foreach ($conditionFields as $field) {
                $query = $this->_addJoin($model, $query, $field, $locale, false);
            }
            unset($this->_joinTable, $this->_runtimeModel);

            return $query;
        }
        $autoFields = false;

        if (empty($query['fields'])) {
            $query['fields'] = [$model->alias.'.*'];

            $recursive = $model->recursive;
            if (isset($query['recursive'])) {
                $recursive = $query['recursive'];
            }

            if ($recursive >= 0) {
                foreach (['hasOne', 'belongsTo'] as $type) {
                    foreach ($model->{$type} as $key => $value) {
                        if (empty($value['fields'])) {
                            $query['fields'][] = $key.'.*';
                        } else {
                            foreach ($value['fields'] as $field) {
                                $query['fields'][] = $key.'.'.$field;
                            }
                        }
                    }
                }
            }
            $autoFields = true;
        }
        $fields = array_merge($this->settings[$model->alias], $this->runtime[$model->alias]['fields']);
        $addFields = [];
        if (is_array($query['fields'])) {
            foreach ($fields as $key => $value) {
                $field = (is_numeric($key)) ? $value : $key;
                if (in_array($model->alias.'.*', $query['fields']) || $autoFields || in_array($model->alias.'.'.$field, $query['fields']) || in_array($field, $query['fields'])) {
                    $addFields[] = $field;
                }
            }
        }

        if ($addFields) {
            foreach ($addFields as $field) {
                foreach ([$field, $model->alias.'.'.$field] as $_field) {
                    $key = array_search($_field, $query['fields']);

                    if (false !== $key) {
                        unset($query['fields'][$key]);
                    }
                }
                $query = $this->_addJoin($model, $query, $field, $locale, true);
            }
        }
        $this->runtime[$model->alias]['beforeFind'] = $addFields;
        unset($this->_joinTable, $this->_runtimeModel);

        return $query;
    }

    /**
     * Check a query's conditions for translated fields.
     * Return an array of translated fields found in the conditions.
     *
     * @param Model $model the model being read
     * @param array $query the query array
     *
     * @return array the list of translated fields that are in the conditions
     */
    public function _checkConditions(&$model, $query)
    {
        $conditionFields = [];
        if (empty($query['conditions']) || (!empty($query['conditions']) && !is_array($query['conditions']))) {
            return $conditionFields;
        }
        foreach ($query['conditions'] as $col => $val) {
            foreach ($this->settings[$model->alias] as $field => $assoc) {
                if (is_numeric($field)) {
                    $field = $assoc;
                }
                if (false !== strpos($col, $field)) {
                    $conditionFields[] = $field;
                }
            }
        }

        return $conditionFields;
    }

    /**
     * Appends a join for translated fields and possibly a field.
     *
     * @param Model  $model     the model being worked on
     * @param object $joinTable the jointable object
     * @param array  $query     the query array to append a join to
     * @param string $field     the field name being joined
     * @param mixed  $locale    the locale(s) having joins added
     * @param bool   $addField  whether or not to add a field
     *
     * @return array The modfied query
     */
    public function _addJoin(&$model, $query, $field, $locale, $addField = false)
    {
        $db = &ConnectionManager::getDataSource($model->useDbConfig);

        $RuntimeModel = $this->_runtimeModel;
        $joinTable = $this->_joinTable;

        if (is_array($locale)) {
            foreach ($locale as $_locale) {
                if ($addField) {
                    $query['fields'][] = 'I18n__'.$field.'__'.$_locale.'.content';
                }
                $query['joins'][] = [
                    'type' => 'LEFT',
                    'alias' => 'I18n__'.$field.'__'.$_locale,
                    'table' => $joinTable,
                    'conditions' => [
                        $model->alias.'.'.$model->primaryKey => $db->identifier("I18n__{$field}__{$_locale}.foreign_key"),
                        'I18n__'.$field.'__'.$_locale.'.model' => $model->name,
                        'I18n__'.$field.'__'.$_locale.'.'.$RuntimeModel->displayField => $field,
                        'I18n__'.$field.'__'.$_locale.'.locale' => $_locale,
                    ],
                ];
            }
        } else {
            if ($addField) {
                $query['fields'][] = 'I18n__'.$field.'.content';
            }
            $query['joins'][] = [
                'type' => 'INNER',
                'alias' => 'I18n__'.$field,
                'table' => $joinTable,
                'conditions' => [
                    $model->alias.'.'.$model->primaryKey => $db->identifier("I18n__{$field}.foreign_key"),
                    'I18n__'.$field.'.model' => $model->name,
                    'I18n__'.$field.'.'.$RuntimeModel->displayField => $field,
                    'I18n__'.$field.'.locale' => $locale,
                ],
            ];
        }

        return $query;
    }

    /**
     * afterFind Callback.
     *
     * @param Model $model   Model find was run on
     * @param array $results array of model results
     * @param bool  $primary did the find originate on $model
     *
     * @return array Modified results
     */
    public function afterFind(&$model, $results, $primary)
    {
        $this->runtime[$model->alias]['fields'] = [];
        $locale = $this->_getLocale($model);

        if (empty($locale) || empty($results) || empty($this->runtime[$model->alias]['beforeFind'])) {
            return $results;
        }
        $beforeFind = $this->runtime[$model->alias]['beforeFind'];

        foreach ($results as $key => $row) {
            $results[$key][$model->alias]['locale'] = (is_array($locale)) ? @$locale[0] : $locale;

            foreach ($beforeFind as $field) {
                if (is_array($locale)) {
                    foreach ($locale as $_locale) {
                        if (!isset($results[$key][$model->alias][$field]) && !empty($results[$key]['I18n__'.$field.'__'.$_locale]['content'])) {
                            $results[$key][$model->alias][$field] = $results[$key]['I18n__'.$field.'__'.$_locale]['content'];
                        }
                        unset($results[$key]['I18n__'.$field.'__'.$_locale]);
                    }

                    if (!isset($results[$key][$model->alias][$field])) {
                        $results[$key][$model->alias][$field] = '';
                    }
                } else {
                    $value = '';
                    if (!empty($results[$key]['I18n__'.$field]['content'])) {
                        $value = $results[$key]['I18n__'.$field]['content'];
                    }
                    $results[$key][$model->alias][$field] = $value;
                    unset($results[$key]['I18n__'.$field]);
                }
            }
        }

        return $results;
    }

    /**
     * beforeValidate Callback.
     *
     * @param Model $model model invalidFields was called on
     *
     * @return bool
     */
    public function beforeValidate(&$model)
    {
        unset($this->runtime[$model->alias]['beforeSave']);
        $this->_setRuntimeData($model);

        return true;
    }

    /**
     * beforeSave callback.
     *
     * Copies data into the runtime property when `$options['validate']` is
     * disabled.  Or the runtime data hasn't been set yet.
     *
     * @param Model $model model save was called on
     *
     * @return bool true
     */
    public function beforeSave($model, $options = [])
    {
        if (isset($options['validate']) && false == $options['validate']) {
            unset($this->runtime[$model->alias]['beforeSave']);
        }
        if (isset($this->runtime[$model->alias]['beforeSave'])) {
            return true;
        }
        $this->_setRuntimeData($model);

        return true;
    }

    /**
     * Sets the runtime data.
     *
     * Used from beforeValidate() and beforeSave() for compatibility issues,
     * and to allow translations to be persisted even when validation
     * is disabled.
     *
     * @param Model $model
     */
    public function _setRuntimeData(Model $model)
    {
        $locale = $this->_getLocale($model);
        if (empty($locale)) {
            return true;
        }
        $fields = array_merge($this->settings[$model->alias], $this->runtime[$model->alias]['fields']);
        $tempData = [];

        foreach ($fields as $key => $value) {
            $field = (is_numeric($key)) ? $value : $key;

            if (isset($model->data[$model->alias][$field])) {
                $tempData[$field] = $model->data[$model->alias][$field];
                if (is_array($model->data[$model->alias][$field])) {
                    if (is_string($locale) && !empty($model->data[$model->alias][$field][$locale])) {
                        $model->data[$model->alias][$field] = $model->data[$model->alias][$field][$locale];
                    } else {
                        $values = array_values($model->data[$model->alias][$field]);
                        $model->data[$model->alias][$field] = $values[0];
                    }
                }
            }
        }
        $this->runtime[$model->alias]['beforeSave'] = $tempData;
    }

    /**
     * afterSave Callback.
     *
     * @param Model $model   Model the callback is called on
     * @param bool  $created whether or not the save created a record
     */
    public function afterSave(&$model, $created)
    {
        if (!isset($this->runtime[$model->alias]['beforeValidate']) && !isset($this->runtime[$model->alias]['beforeSave'])) {
            return true;
        }
        $locale = $this->_getLocale($model);
        if (isset($this->runtime[$model->alias]['beforeValidate'])) {
            $tempData = $this->runtime[$model->alias]['beforeValidate'];
        } else {
            $tempData = $this->runtime[$model->alias]['beforeSave'];
        }

        unset($this->runtime[$model->alias]['beforeValidate'], $this->runtime[$model->alias]['beforeSave']);
        $conditions = ['model' => $model->alias, 'foreign_key' => $model->id];
        $RuntimeModel = &$this->translateModel($model);

        foreach ($tempData as $field => $value) {
            unset($conditions['content']);
            $conditions['field'] = $field;
            if (is_array($value)) {
                $conditions['locale'] = array_keys($value);
            } else {
                $conditions['locale'] = $locale;
                if (is_array($locale)) {
                    $value = [$locale[0] => $value];
                } else {
                    $value = [$locale => $value];
                }
            }
            $translations = $RuntimeModel->find('list', ['conditions' => $conditions, 'fields' => [$RuntimeModel->alias.'.locale', $RuntimeModel->alias.'.id']]);
            foreach ($value as $_locale => $_value) {
                $RuntimeModel->create();
                $conditions['locale'] = $_locale;
                $conditions['content'] = $_value;
                if (array_key_exists($_locale, $translations)) {
                    $RuntimeModel->save([$RuntimeModel->alias => array_merge($conditions, ['id' => $translations[$_locale]])]);
                } else {
                    $RuntimeModel->save([$RuntimeModel->alias => $conditions]);
                }
            }
        }
    }

    /**
     * afterDelete Callback.
     *
     * @param Model $model model the callback was run on
     */
    public function afterDelete(&$model)
    {
        $RuntimeModel = &$this->translateModel($model);
        $conditions = ['model' => $model->alias, 'foreign_key' => $model->id];
        $RuntimeModel->deleteAll($conditions);
    }

    /**
     * Get selected locale for model.
     *
     * @param Model $model model the locale needs to be set/get on
     *
     * @return mixed string or false
     */
    public function _getLocale(&$model)
    {
        if (!isset($model->locale) || is_null($model->locale)) {
            if (!class_exists('I18n')) {
                App::import('Core', 'i18n');
            }
            $I18n = &I18n::getInstance();
            $I18n->l10n->get(Configure::read('Config.language'));
            $model->locale = $I18n->l10n->locale;
        }

        return $model->locale;
    }

    /**
     * Get instance of model for translations.
     *
     * If the model has a translateModel property set, this will be used as the class
     * name to find/use.  If no translateModel property is found 'I18nModel' will be used.
     *
     * @param Model $model model to get a translatemodel for
     *
     * @return object
     */
    public function &translateModel(&$model)
    {
        if (!isset($this->runtime[$model->alias]['model'])) {
            if (!isset($model->translateModel) || empty($model->translateModel)) {
                $className = 'I18nModel';
            } else {
                $className = $model->translateModel;
            }

            if (PHP5) {
                $this->runtime[$model->alias]['model'] = ClassRegistry::init($className, 'Model');
            } else {
                $this->runtime[$model->alias]['model'] = &ClassRegistry::init($className, 'Model');
            }
        }
        if (!empty($model->translateTable) && $this->runtime[$model->alias]['model']->useTable !== $model->translateTable) {
            $this->runtime[$model->alias]['model']->setSource($model->translateTable);
        } elseif (empty($model->translateTable) && empty($model->translateModel)) {
            $this->runtime[$model->alias]['model']->setSource('i18n');
        }
        $model = &$this->runtime[$model->alias]['model'];

        return $model;
    }

    /**
     * Bind translation for fields, optionally with hasMany association for
     * fake field.
     *
     * @param object instance of model
     * @param mixed string with field or array(field1, field2=>AssocName, field3)
     * @param bool $reset
     *
     * @return bool
     */
    public function bindTranslation(&$model, $fields, $reset = true)
    {
        if (is_string($fields)) {
            $fields = [$fields];
        }
        $associations = [];
        $RuntimeModel = &$this->translateModel($model);
        $default = ['className' => $RuntimeModel->alias, 'foreignKey' => 'foreign_key'];

        foreach ($fields as $key => $value) {
            if (is_numeric($key)) {
                $field = $value;
                $association = null;
            } else {
                $field = $key;
                $association = $value;
            }

            if (array_key_exists($field, $this->settings[$model->alias])) {
                unset($this->settings[$model->alias][$field]);
            } elseif (in_array($field, $this->settings[$model->alias])) {
                $this->settings[$model->alias] = array_merge(array_diff_assoc($this->settings[$model->alias], [$field]));
            }

            if (array_key_exists($field, $this->runtime[$model->alias]['fields'])) {
                unset($this->runtime[$model->alias]['fields'][$field]);
            } elseif (in_array($field, $this->runtime[$model->alias]['fields'])) {
                $this->runtime[$model->alias]['fields'] = array_merge(array_diff_assoc($this->runtime[$model->alias]['fields'], [$field]));
            }

            if (is_null($association)) {
                if ($reset) {
                    $this->runtime[$model->alias]['fields'][] = $field;
                } else {
                    $this->settings[$model->alias][] = $field;
                }
            } else {
                if ($reset) {
                    $this->runtime[$model->alias]['fields'][$field] = $association;
                } else {
                    $this->settings[$model->alias][$field] = $association;
                }

                foreach (['hasOne', 'hasMany', 'belongsTo', 'hasAndBelongsToMany'] as $type) {
                    if (isset($model->{$type}[$association]) || isset($model->__backAssociation[$type][$association])) {
                        trigger_error(
                            sprintf(__('Association %s is already binded to model %s', true), $association, $model->alias),
                            E_USER_ERROR
                        );

                        return false;
                    }
                }
                $associations[$association] = array_merge($default, ['conditions' => [
                    'model' => $model->alias,
                    $RuntimeModel->displayField => $field,
                ]]);
            }
        }

        if (!empty($associations)) {
            $model->bindModel(['hasMany' => $associations], $reset);
        }

        return true;
    }

    /**
     * Unbind translation for fields, optionally unbinds hasMany association for
     * fake field.
     *
     * @param object $model  instance of model
     * @param mixed  $fields string with field, or array(field1, field2=>AssocName, field3), or null for
     *                       unbind all original translations
     *
     * @return bool
     */
    public function unbindTranslation(&$model, $fields = null)
    {
        if (empty($fields) && empty($this->settings[$model->alias])) {
            return false;
        }
        if (empty($fields)) {
            return $this->unbindTranslation($model, $this->settings[$model->alias]);
        }

        if (is_string($fields)) {
            $fields = [$fields];
        }
        $RuntimeModel = &$this->translateModel($model);
        $associations = [];

        foreach ($fields as $key => $value) {
            if (is_numeric($key)) {
                $field = $value;
                $association = null;
            } else {
                $field = $key;
                $association = $value;
            }

            if (array_key_exists($field, $this->settings[$model->alias])) {
                unset($this->settings[$model->alias][$field]);
            } elseif (in_array($field, $this->settings[$model->alias])) {
                $this->settings[$model->alias] = array_merge(array_diff_assoc($this->settings[$model->alias], [$field]));
            }

            if (array_key_exists($field, $this->runtime[$model->alias]['fields'])) {
                unset($this->runtime[$model->alias]['fields'][$field]);
            } elseif (in_array($field, $this->runtime[$model->alias]['fields'])) {
                $this->runtime[$model->alias]['fields'] = array_merge(array_diff_assoc($this->runtime[$model->alias]['fields'], [$field]));
            }

            if (!is_null($association) && (isset($model->hasMany[$association]) || isset($model->__backAssociation['hasMany'][$association]))) {
                $associations[] = $association;
            }
        }

        if (!empty($associations)) {
            $model->unbindModel(['hasMany' => $associations], false);
        }

        return true;
    }
}
if (!defined('CAKEPHP_UNIT_TEST_EXECUTION')) {
    class I18nModel extends AppModel
    {
        public $name = 'I18nModel';
        public $useTable = 'i18n';
        public $displayField = 'field';
    }
}
