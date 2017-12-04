<?php
/**
 * This is core configuration file.
 *
 * Use it to configure core behaviour ofCake.
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
 * @since         CakePHP(tm) v 0.2.9
 *
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * Load Model and AppModel.
 */
App::import('Model', 'App');

/**
 * ACL Node.
 */
class AclNode extends AppModel
{
    /**
     * Explicitly disable in-memory query caching for ACL models.
     *
     * @var bool
     */
    public $cacheQueries = false;

    /**
     * ACL models use the Tree behavior.
     *
     * @var array
     */
    public $actsAs = ['Tree' => 'nested'];

    /**
     * Constructor.
     */
    public function __construct()
    {
        $config = Configure::read('Acl.database');
        if (isset($config)) {
            $this->useDbConfig = $config;
        }
        parent::__construct();
    }

    /**
     * Retrieves the Aro/Aco node for this model.
     *
     * @param mixed $ref Array with 'model' and 'foreign_key', model object, or string value
     *
     * @return array Node found in database
     */
    public function node($ref = null)
    {
        $db = &ConnectionManager::getDataSource($this->useDbConfig);
        $type = $this->alias;
        $result = null;

        if (!empty($this->useTable)) {
            $table = $this->useTable;
        } else {
            $table = Inflector::pluralize(Inflector::underscore($type));
        }

        if (empty($ref)) {
            return null;
        } elseif (is_string($ref)) {
            $path = explode('/', $ref);
            $start = $path[0];
            unset($path[0]);

            $queryData = [
                'conditions' => [
                    $db->name("{$type}.lft").' <= '.$db->name("{$type}0.lft"),
                    $db->name("{$type}.rght").' >= '.$db->name("{$type}0.rght"), ],
                'fields' => ['id', 'parent_id', 'model', 'foreign_key', 'alias'],
                'joins' => [[
                    'table' => $table,
                    'alias' => "{$type}0",
                    'type' => 'LEFT',
                    'conditions' => ["{$type}0.alias" => $start],
                ]],
                'order' => $db->name("{$type}.lft").' DESC',
            ];

            foreach ($path as $i => $alias) {
                $j = $i - 1;

                $queryData['joins'][] = [
                    'table' => $table,
                    'alias' => "{$type}{$i}",
                    'type' => 'LEFT',
                    'conditions' => [
                        $db->name("{$type}{$i}.lft").' > '.$db->name("{$type}{$j}.lft"),
                        $db->name("{$type}{$i}.rght").' < '.$db->name("{$type}{$j}.rght"),
                        $db->name("{$type}{$i}.alias").' = '.$db->value($alias, 'string'),
                        $db->name("{$type}{$j}.id").' = '.$db->name("{$type}{$i}.parent_id"),
                    ],
                ];

                $queryData['conditions'] = ['or' => [
                    $db->name("{$type}.lft").' <= '.$db->name("{$type}0.lft").' AND '.$db->name("{$type}.rght").' >= '.$db->name("{$type}0.rght"),
                    $db->name("{$type}.lft").' <= '.$db->name("{$type}{$i}.lft").' AND '.$db->name("{$type}.rght").' >= '.$db->name("{$type}{$i}.rght"), ],
                ];
            }
            $result = $db->read($this, $queryData, -1);
            $path = array_values($path);

            if (
                !isset($result[0][$type]) ||
                (!empty($path) && $result[0][$type]['alias'] != $path[count($path) - 1]) ||
                (empty($path) && $result[0][$type]['alias'] != $start)
            ) {
                return false;
            }
        } elseif (is_object($ref) && is_a($ref, 'Model')) {
            $ref = ['model' => $ref->alias, 'foreign_key' => $ref->id];
        } elseif (is_array($ref) && !(isset($ref['model']) && isset($ref['foreign_key']))) {
            $name = key($ref);

            if (PHP5) {
                $model = ClassRegistry::init(['class' => $name, 'alias' => $name]);
            } else {
                $model = &ClassRegistry::init(['class' => $name, 'alias' => $name]);
            }

            if (empty($model)) {
                trigger_error(sprintf(__("Model class '%s' not found in AclNode::node() when trying to bind %s object", true), $type, $this->alias), E_USER_WARNING);

                return null;
            }

            $tmpRef = null;
            if (method_exists($model, 'bindNode')) {
                $tmpRef = $model->bindNode($ref);
            }
            if (empty($tmpRef)) {
                $ref = ['model' => $name, 'foreign_key' => $ref[$name][$model->primaryKey]];
            } else {
                if (is_string($tmpRef)) {
                    return $this->node($tmpRef);
                }
                $ref = $tmpRef;
            }
        }
        if (is_array($ref)) {
            if (is_array(current($ref)) && is_string(key($ref))) {
                $name = key($ref);
                $ref = current($ref);
            }
            foreach ($ref as $key => $val) {
                if (0 !== strpos($key, $type) && false === strpos($key, '.')) {
                    unset($ref[$key]);
                    $ref["{$type}0.{$key}"] = $val;
                }
            }
            $queryData = [
                'conditions' => $ref,
                'fields' => ['id', 'parent_id', 'model', 'foreign_key', 'alias'],
                'joins' => [[
                    'table' => $table,
                    'alias' => "{$type}0",
                    'type' => 'LEFT',
                    'conditions' => [
                        $db->name("{$type}.lft").' <= '.$db->name("{$type}0.lft"),
                        $db->name("{$type}.rght").' >= '.$db->name("{$type}0.rght"),
                    ],
                ]],
                'order' => $db->name("{$type}.lft").' DESC',
            ];
            $result = $db->read($this, $queryData, -1);

            if (!$result) {
                trigger_error(sprintf(__("AclNode::node() - Couldn't find %s node identified by \"%s\"", true), $type, print_r($ref, true)), E_USER_WARNING);
            }
        }

        return $result;
    }
}

/**
 * Access Control Object.
 */
class Aco extends AclNode
{
    /**
     * Model name.
     *
     * @var string
     */
    public $name = 'Aco';

    /**
     * Binds to ARO nodes through permissions settings.
     *
     * @var array
     */
    public $hasAndBelongsToMany = ['Aro' => ['with' => 'Permission']];
}

/**
 * Action for Access Control Object.
 */
class AcoAction extends AppModel
{
    /**
     * Model name.
     *
     * @var string
     */
    public $name = 'AcoAction';

    /**
     * ACO Actions belong to ACOs.
     *
     * @var array
     */
    public $belongsTo = ['Aco'];
}

/**
 * Access Request Object.
 */
class Aro extends AclNode
{
    /**
     * Model name.
     *
     * @var string
     */
    public $name = 'Aro';

    /**
     * AROs are linked to ACOs by means of Permission.
     *
     * @var array
     */
    public $hasAndBelongsToMany = ['Aco' => ['with' => 'Permission']];
}

/**
 * Permissions linking AROs with ACOs.
 */
class Permission extends AppModel
{
    /**
     * Model name.
     *
     * @var string
     */
    public $name = 'Permission';

    /**
     * Explicitly disable in-memory query caching.
     *
     * @var bool
     */
    public $cacheQueries = false;

    /**
     * Override default table name.
     *
     * @var string
     */
    public $useTable = 'aros_acos';

    /**
     * Permissions link AROs with ACOs.
     *
     * @var array
     */
    public $belongsTo = ['Aro', 'Aco'];

    /**
     * No behaviors for this model.
     *
     * @var array
     */
    public $actsAs = null;

    /**
     * Constructor, used to tell this model to use the
     * database configured for ACL.
     */
    public function __construct()
    {
        $config = Configure::read('Acl.database');
        if (!empty($config)) {
            $this->useDbConfig = $config;
        }
        parent::__construct();
    }
}
