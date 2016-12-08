<?php

class AppModel extends Model
{
    public $actsAs = array(
            'Logable' => array(
                'change' => 'full',
                'description_ids' => 'false',
                'displayField' => 'username',
                'foreignKey' => 'foreign_key',
                'userModel' => 'Medewerker',
                'userKey' => 'medewerker_id',
                ),
            );

    public $debug_caching = false;
    public $beforeSaveData = array();
    public $ignore_caching_for = array('Log');

    /*
     * this function sets the field gezien to !gezien (the oposite boolean val)
     * it can only be used if there's a gezien field in the model table
     * @id - id of the row to be modified
     */

    public function gezien($id)
    {
        if (!array_key_exists('gezien', $this->_schema)) {
            return;
        }
        $this->recursive = -1;
        $row = &$this->read(null, $id);
        if (!empty($row)) {
            $this->set('gezien', !$row[$this->alias]['gezien'] ? 1 : 0);
            if ($this->save(null, true, array('gezien'))) {
                return $row[$this->alias]['gezien'];
            }
        }

        return null;
    }

    public function check_diff_multi($array1, $array2)
    {
        $result = array();
        foreach ($array1 as $key => $val) {
            if (isset($array2[$key])) {
                if (is_array($val) && is_array($array2[$key])) {
                    $result[$key] = check_diff_multi($val, $array2[$key]);
                }
            } else {
                $result[$key] = $val;
            }
        }

        return $result;
    }
    public function has_this_data_changed($current, $original)
    {
        return $this->check_diff_multi($current[$this->alias], $original[$this->alias]);
    }

    public function getCacheKey($id)
    {
        if ($this->useTable) {
            return $this->useTable.'.'.$id;
        } else {
            return $this->name.'.'.$id;
        }
    }

    public function getCacheKeyRelations($id)
    {
        return $this->name.'.'.$id;
    }

    /**
     * getDummy Get an empty data array for this model, reflecting the schema
     * and the virtual fields. Cached.
     */
    public function getDummy()
    {
        $result = registry_get('schema', $this->name, true);
        if (!$result) {
            $result = array();
            $schema = $this->schema();
            foreach ($schema as $field => $info) {
                $result[$field] = null;
            }
            foreach ($this->virtualFields as $field => $info) {
                $result[$field] = null;
            }
            registry_set('schema', $this->name, $result, true);
        }

        return $result;
    }

    /**
     * getById Retrieve a model data, normally from registry or cache, where it's kept.
     *
     * @param mixed $id
     * @param bool  $fromCache
     *
     * @return array with CakePHP model data
     */
    public function getById($id, $fromCache = true)
    {
        if ($this->debug_caching) {
            $this->log('getById '.$id, 'as_'.$this->name);
        }
        if (empty($id)) {
            // Early return.
            return false;
        }
        if (in_array($this->name, $this->ignore_caching_for)) {
            $fromCache = false;
        }
        $key = $this->getCacheKey($id);
        $result = registry_get('properties', $key, $fromCache);
        if ($result) {
            if ($this->debug_caching) {
                $this->log('cached:  '.json_encode($result), 'as_'.$this->name);
            }

            return $result;
        }
        $result = $this->find('first', array(
            'conditions' => array(
                $this->primaryKey => $id,
            ),
            'recursive' => -1,
          )
        );
        if ($result) {
            $result = $result[$this->alias];
            if ($this->debug_caching) {
                $this->log('read:  '.json_encode($result), 'as_'.$this->name);
            }
            registry_set('properties', $key, $result, $fromCache);
        } else {
        }

        return $result;
    }

    /**
     * getAllById Retrieve all related objects recursively. A parameter $contain can be passed to determine until what depth (and for what children models) this recursion will happen. Otherwise, the first level of relatives is retrieved.
     *
     * @param UUID  $id
     * @param array $contain Model relationship tree, to specify the recursiveness depth
     * @param bool $emptyIfNotFound When used recursively, we make this true so that if some object is not found, a empty array is returned instead of false, emulating Cake's behavior when building up complex objects
     *
     * @return array with all composite objects, as if it where returned by a CakePHP's find with contain
     */
    public function getAllById($id, $contain = null, $emptyIfNotFound = false, $fromCache = true)
    {
        $result = array();

        $hit = $this->getById($id, $fromCache);

        if ($hit) {
            $result[$this->alias] = $hit;
        } else {
            if ($emptyIfNotFound) {
                return array($this->alias => $this->getDummy());
            } else {
                return false;
            }
        }
        if (!empty($contain) || is_null($contain)) {
            $relations = $this->getRelatedToId($id, $fromCache);
            if (is_null($contain)) {
                $contain = array_keys($relations);
            }
        }

        foreach ($contain as $m => $branch) {
            if (!is_numeric($m) && $m == 'fields') {
                continue;
            }
            if (!is_array($branch) && $branch != '*') {
                $m = $branch;
                $branch = array();
            }
            if (!empty($relations[$m])) {
                if (!isset($this->{$m})) {
                    continue;
                }
                $pk = $this->{$m}->primaryKey;
                if (isset($relations[$m][$pk])) {
                    $c_id = $relations[$m][$pk];
                    $child = $this->{$m}->getAllById($c_id, $branch, true, $fromCache);
                    $result[$m] = $child[$m];
                    unset($child[$m]);
                    if (!empty($child)) {
                        $result[$m] = Set::merge($result[$m], $child);
                    }
                } else {
                    foreach ($relations[$m] as $k => $c_id) {
                        $child = $this->{$m}->getAllById($c_id, $branch, true, $fromCache);
                        if (isset($this->hasOne[$m])) {
                            $result[$m] = $child[$m];
                        } else {
                            $result[$m][$k] = $child[$m];
                        }
                    }
                }
            } else {
                if ($m != $this->name && $m != $this->alias) {
                    if (isset($this->belongsTo[$m])) {
                        $result[$m] = $this->{$m}->getDummy();
                    } else {
                        $result[$m] = array();
                    }
                }
            }
        }

        $result = current($this->afterFind(array($result), true));

        return $result;
    }

    /**
     * completeAllPaginated Complete paginated results retrieved with recursive = -1 with data from the cache.
     *
     * @param mixed $paginated
     * @param mixed $contain
     */
    public function completeAllPaginated($paginated, $contain = null)
    {
        $result = array();
        foreach ($paginated as $hit) {
            if (isset($hit[$this->alias]['id'])) {
                $id = $hit[$this->alias]['id'];
                $current = $hit[$this->alias];
            } else {
                $id = $hit['id'];
                $hit = array($this->alias => $hit);
            }
            $result[] = Set::merge($hit, $this->getAllById($id, $contain));
        }

        return $result;
    }

    /**
     * refreshCachedBelongsToRelations Updated the cached relations by
     * refreshing the belongsTo, the only ones that can change when saving THIS
     * object. Also clean the relations cache of the parents that have changed. and only those. We use deleteRelationsCacheAndParentsRelationsCache() to delete all parents without discrimination.
     *
     * @param mixed $id
     */
    public function refreshCachedBelongsToRelations($id)
    {
        $key = $this->getCacheKeyRelations($id);
        $allRelations = registry_get('relations', $key, true);
        if ($this->debug_caching) {
            $this->log(array('refreshCachedBelongsToRelations allRelations' => $allRelations), 'as_'.$this->name);
        }
        if (!$allRelations) {
            $allRelations = $this->getRelatedToId($id, false);
            $parent_ids = $this->findParentsOfId($id);
        } else {
            $parent_ids = $this->findParentsOfId($id);
            $related_ids = array_merge($allRelations, $parent_ids);
            registry_set('relations', $key, $related_ids, true);
        }

        $parents_to_clean = false;
        if (empty($this->beforeSaveData[$id][$this->alias])) {
            $changed = true;
            $parents_to_clean = $parent_ids;
        } else {
            $changes = null;
            if (isset($this->data[$this->alias])) {
                $ref[$this->alias] = $this->data[$this->alias];
                $changes = $this->has_this_data_changed($ref, $this->beforeSaveData[$id]);
            }
            $parents_to_clean = array();
            if (!empty($changes)) {
                foreach ($this->belongsTo as $model => $info) {
                    $fk = $info['foreignKey'];
                    $pk = $this->{$model}->primaryKey;
                    if (array_key_exists($fk, $changes)) {
                        if (!empty($this->beforeSaveData[$id][$this->alias][$fk])) {
                            $parents_to_clean[$model][] = $this->beforeSaveData[$id][$this->alias][$fk];
                        }
                        if (!empty($this->data[$this->alias][$fk])) {
                            $parents_to_clean[$model][] = $this->data[$this->alias][$fk];
                        }
                    }
                }
            }
        }

        if (!empty($parents_to_clean)) {
            $this->clearGivenRelations($parents_to_clean);
        }
    }

    /**
     * findParentsOfId Retrieves an array of parent objects to which the specified object belongsTo.
     *
     * @param mixed $id
     */
    public function findParentsOfId($id)
    {
        if (empty($id)) {
            debug('empty ID');

            return false;
        }

        if ($this->debug_caching) {
            $this->log('findParentsOfId '.$id, 'as_'.$this->name);
        }

        $this->recursive = -1;
        $conditions = array($this->alias.'.'.$this->primaryKey => $id);
        $result = $this->find('first', array(
                    'conditions' => $conditions,
                ));
        $related_ids = array();
        if ($this->debug_caching) {
            $this->log(array('conditions' => $conditions, 'result' => $result), 'as_'.$this->name);
        }

        if (is_array($result)) {
            foreach ($this->belongsTo as $model => $info) {
                $related_ids[$model] = array();
                $fk = $info['foreignKey'];
                $pk = $this->{$model}->primaryKey;
                if ($this->debug_caching) {
                    $this->log('my parent '.$model.' is in '.$fk,   'as_'.$this->name);
                }
                if (!empty($result[$this->alias][$fk])) {
                    $related_ids[$model] = array(
                            $pk => $result[$this->alias][$fk],
                            );
                    if ($this->debug_caching) {
                        $this->log('that is '.$pk.' => '.$result[$this->alias][$fk],   'as_'.$this->name);
                    }
                } else {
                    if ($this->debug_caching) {
                        $this->log('that is empty!',   'as_'.$this->name);
                    }
                }
            }
        }
        if ($this->debug_caching) {
            $this->log(array('conditions' => $conditions, 'result' => $result, 'belongsTo' => $this->belongsTo), 'as_'.$this->name);
        }

        if (!empty($result[$this->alias]['model'])
                    && !empty($result[$this->alias]['foreign_key'])) {
            $model = $result[$this->alias]['model'];
            $fk = $result[$this->alias]['foreign_key'];
            $related_ids[$model]['id'] = $fk;
        }
        unset($info);
        unset($result);

        return $related_ids;
    }

    public function getRelatedToId($id, $fromCache = true)
    {
        if (empty($id)) {
            debug('Empty ID!');
            debug(Debugger::trace());

            return array();
        }
        $key = $this->getCacheKeyRelations($id);
        $result = registry_get('relations', $key, $fromCache);
        if ($this->debug_caching) {
            $this->log('READ relations '.$fromCache.' '.$key, 'as_'.$this->name);
            $this->log($result, 'as_'.$this->name);
        }

        if ($result) {
            return $result;
        }

        $related_ids = $this->findParentsOfId($id);

        $relations = array();

        $relationModels = array_merge($this->hasMany, $this->hasOne);
        foreach ($relationModels as $model => $info) {
            if (empty($this->{$model}) || !is_subclass_of($this->{$model}, 'AppModel')) {
                continue;
            }
            $this->{$model}->recursive = -1;
            $table = $this->{$model}->useTable;
            $conditions = array($info['foreignKey'] => $id);
            if (is_array($info['conditions'])) {
                $conditions += $info['conditions'];
            }
            $order = $info['order'];
            $options = array(
                'conditions' => $conditions,
                'order' => $order,
                'fields' => $this->{$model}->primaryKey,
            );
            if (isset($this->hasOne[$model])) {
                $options['limit'] = 1;
            }
            $ids = $this->{$model}->find('list', $options);

            if (!empty($ids)) {
                $ids = array($model => array_keys($ids));
            } else {
                $ids = array($model => array());
            }
            $relations = array_merge($relations, $ids);
        }

        if (is_array($related_ids)) {
            $related_ids = array_merge($related_ids, $relations);
        } else {
            $related_ids = $relations;
        }

        if ($related_ids) {
            registry_set('relations', $key, $related_ids, true);
            if ($this->debug_caching) {
                $this->log('CACHING relations '.$key, 'as_'.$this->name);
                $this->log($related_ids, 'as_'.$this->name);
            }
        }

        return $related_ids;
    }

    /**
     * clearCache A function to clear the cache of one object, on demand.
     *
     * @param mixed $created
     */
    public function clearCache($created)
    {
        if (Configure::read('Cache.disable')) {
            return;
        }
        if (in_array($this->name, $this->ignore_caching_for)) {
            return;
        }

        if ($this->debug_caching) {
            $this->log('>>>> clearCache '.$this->id.' created: '.$created, 'as_'.$this->name);
        }
        if (!$created) {
            $this->deletePropertyCache($this->id);
        }

        $this->refreshCachedBelongsToRelations($this->id);
    }

    public function beforeSave($options = array())
    {
        $this->debug_caching = Configure::read('debug');
        if (!in_array($this->name, $this->ignore_caching_for)) {
            if ($this->debug_caching) {
                // $this->log('>>>> beforeSave '.$this->id, 'as_'.$this->name);
            }
            if ($this->id) {
                $this->beforeSaveData[$this->id][$this->alias] = $this->getById($this->id);
            } else {
            }
        }

        return parent::beforeSave($options);
    }

    /**
     * Cake model afterSave callback.
     *
     * @param bool $created Is it a new record
     */
    public function afterSave($created)
    {
        if ($this->debug_caching) {
            $this->log('>>>> afterSave '.$this->id, 'as_'.$this->name);
        }
        parent::afterSave($created);
        $this->clearCache($created);
        unset($this->beforeSaveData[$this->id][$this->alias]);
    }

    public function deletePropertyCache($id)
    {
        $key = $this->getCacheKey($id);
        if ($this->debug_caching) {
            $this->log('deletePropertyCache '.$key, 'as_'.$this->name);
        }
        registry_delete('properties', $key, true);
        registry_delete('computed_properties', $key, true);
        if ($this->debug_caching) {
            $this->log('after deletePropertyCache: '.registry_get('properties', $key, true), 'as_'.$this->name);
        }
    }

    /**
     * getComputedPropertyById Parallel to the propeties registry for data in the database, we have another one for computed properties. These are the getter and setter:.
     *
     * @param UUID   $id
     * @param string $property
     * @param bool   $fromCache
     */
    public function getComputedPropertyById($id, $property, $fromCache = true)
    {
        $key = $this->getCacheKey($id);

        $prop = registry_get('computed_properties', $key, $fromCache);

        if (isset($prop[$property])) {
            return $prop[$property];
        } else {
            return false;
        }
    }

    /**
     * setComputedPropertyById Store a value in an array of properties for a particular object. See getComputedPropertyById().
     *
     * @param UUID   $id
     * @param string $property
     * @param mixed  $value
     * @param bool   $fromCache
     */
    public function setComputedPropertyById($id, $property, $value, $fromCache = true)
    {
        $key = $this->getCacheKey($id);

        $prop = registry_get('computed_properties', $key, $fromCache);
        if (!$prop) {
            $prop = array();
        }
        $prop[$property] = $value;
        $result = registry_set('computed_properties', $key, $prop, $fromCache);

        return $result;
    }

    private function clearGivenRelations($relations)
    {
        if ($this->debug_caching) {
            $this->log($this->alias.' '.$this->id.' is cleaning his parents relations', 'debug_cache');
            $this->log($relations, 'debug_cache');
        }
        foreach ($relations as $model => $ids) {
            if ($model != $this->alias) {
                foreach ($ids as $relationId) {
                    if (!empty($this->{$model})) {
                        $key = $this->{$model}->getCacheKeyRelations($relationId);
                        if ($this->debug_caching) {
                            $this->log('Clean relations of '.$model.': '.$key, 'as_'.$this->name);
                            $this->log('Clean relations of '.$model.': '.$key, 'debug_cache');
                        }
                        if ($model == 'Event') {
                            // $this->log(Debugger::trace(), 'debug_cache');
                        }
                    } else {
                        $m = ClassRegistry::init($model);
                        $key = $m->getCacheKeyRelations($relationId);
                        if ($this->debug_caching) {
                            $this->log('Clean relations of '.$model.': special '.$key, 'as_'.$this->name);
                            $this->log('Clean relations of '.$model.': special '.$key, 'debug_cache');
                        }
                    }
                    registry_delete('relations', $key, true);
                }
            }
        }
    }

    /**
     * deleteRelationsCacheAndParentsRelationsCache This deletes the relations
     * of this object, and also the cached relations of all parents related
     * to this.
     *
     * @param mixed $id
     */
    public function deleteRelationsCacheAndParentsRelationsCache($id)
    {
        $key = $this->getCacheKeyRelations($this->id);
        registry_delete('relations', $key, true);
        $relations = $this->findParentsOfId($id);
        if ($this->debug_caching) {
            $this->log('DELETE relations and parents relations '.$key, 'as_'.$this->name);
            $this->log($relations, 'as_'.$this->name);
        }
        $this->clearGivenRelations($relations);
    }

    public function beforeDelete($cascade)
    {
        $result = parent::beforeDelete($cascade);

        if (!in_array($this->name, $this->ignore_caching_for)) {
            $this->deleteRelationsCacheAndParentsRelationsCache($this->id);
            $this->deletePropertyCache($this->id);
        }

        return $result;
    }

    /**
     * deleteAll Rewrite the parent's to run the callbacks by default.
     */
    public function deleteAll($conditions, $cascade = true, $callbacks = true)
    {
        return parent::deleteAll($conditions, $cascade, $callbacks);
    }
}
