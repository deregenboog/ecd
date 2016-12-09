<?php

/** IMPORTANT:
 * Use deleteAll() with a third argument 'true' in order to execute
 * the beforeDelete and afterDelete callbacks for each record. This
 * is necessary if we use the Logable behavior, otherwise deletions are not
 * logged.
 */
/**
 * Logs saves and deletes of any model.
 *
 * Requires the following to work as intended :
 *
 * - "Log" model ( empty but for a order variable [created DESC]
 * - "logs" table with these fields required :
 *     - id			[int]			:
 *     - title 		[string] 		: automagically filled with the display field of the model that was modified.
 * 	   - created	[date/datetime] : filled by cake in normal way
 *
 * - actsAs = array("Logable"); on models that should be logged
 *
 * Optional extra table fields for the "logs" table :
 *
 * - "description" 	[string] : Fill with a descriptive text of what, who and to which model/row :
 * 								"Contact "John Smith"(34) added by User "Administrator"(1).
 *
 * or if u want more detail, add any combination of the following :
 *
 * - "model"    	[string] : automagically filled with the class name of the model that generated the activity.
 * - "model_id" 	[int]	 : automagically filled with the primary key of the model that was modified.
 * - "action"   	[string] : automagically filled with what action is made (add/edit/delete)
 * - "user_id"  	[int]    : populated with the supplied user info. (May be renamed. See bellow.)
 * - "change"   	[string] : depending on setting either :
 * 							[name (alek) => (Alek), age (28) => (29)] or [name, age]
 *
 * - "version_id"	[int]	 : cooperates with RevisionBehavior to link the the shadow table (thus linking to old data)
 *
 * To parse the change field, we can use
 *
 foreach (explode(',', $data['Log']['change']) as $change) {
 preg_match('/(\w+?) \((\w*?)\) => \((\w+?)\)/', $change, $matches);
 list($search, $field, $from, $to) = $matches;
 print "change \"$field\" from \"$from\" to \"$to\"\n";
 }
 *
 * (from http://bakery.cakephp.org/articles/view/logablebehavior)
 *
 * Azavista doesn't need the Revision behavior to revert changes, we don't
 * intend to use this as a revision control. Still, see
 * http://bakery.cakephp.org/articles/view/revision-behavior-revision-control-made-easy
 *
 * Remember that Logable behavior needs to be added after RevisionBehavior. In fact, just put it last to be safe.
 *
 * Optionally register what user was responisble for the activity :
 *
 * - Supply configuration only if defaults are wrong. Example given with defaults :
 *
 * class Apple extends AppModel {
 * 		var $name = 'Apple';
 * 		var $actsAs = array('Logable' => array('userModel' => 'User', 'userKey' => 'user_id'));
 *  [..]
 *
 * - In AppController (or single controller if only needed once) add these lines to beforeFilter :
 *
 *   	if (sizeof($this->uses) && $this->{$this->modelClass}->Behaviors->attached('Logable')) {
 *			$this->{$this->modelClass}->setUserData($this->activeUser);
 *		}
 *
 *   Where "$activeUser" should be an array in the standard format for the User model used :
 *
 *   $activeUser = array( $UserModel->alias => array( $UserModel->primaryKey => 123, $UserModel->displayField => 'Alexander'));
 *   // any other key is just ignored by this behaviour.
 *
 * @author Alexander Morland (alexander#maritimecolours.no)
 * @co-author Eskil Mjelva Saatvedt
 * @co-author Ronny Vindenes
 * @co-author Carl Erik Fyllingen
 * @contributor Miha
 *
 * @category Behavior
 *
 * @version 2.2
 * @modified 3.june 2009 by Miha
 */
class LogableBehavior extends ModelBehavior
{
    public $user = null;
    public $UserModel = false;
    public $settings = [];
    public $defaults = array(
            'enabled' => true,
            'userModel' => 'User',
            'userKey' => 'user_id',
            'change' => 'list',
            'description_ids' => true,
            'skip' => [],
            'ignore' => [],
            'classField' => 'model',
            'foreignKey' => 'model_id',
        );
    /**
     * Cake called intializer
     * Config options are :
     *    userModel 		: 'User'. Class name of the user model you want to use (User by default), if you want to save User in log
     *    userKey   		: 'user_id'. The field for saving the user to (user_id by default).
     * 	  change    		: 'list' > [name, age]. Set to 'full' for [name (alek) => (Alek), age (28) => (29)]
     * 	  description_ids 	: TRUE. Set to FALSE to not include model id and user id in the title field
     *    skip  			: []. String array of actions to not log.
     *
     * @param object $Model
     * @param array  $config
     */
    public function setup(&$Model, $config = [])
    {
        if (!is_array($config)) {
            $config = [];
        }
        $this->settings[$Model->alias] = array_merge($this->defaults, $config);
        $this->settings[$Model->alias]['ignore'][] = $Model->primaryKey;

        $this->Log = &ClassRegistry::init('Log');
        if ($this->settings[$Model->alias]['userModel'] != $Model->alias) {
            $this->UserModel = &ClassRegistry::init($this->settings[$Model->alias]['userModel']);
        } else {
            $this->UserModel = $Model;
        }
    }

    public function settings(&$Model)
    {
        return $this->settings[$Model->alias];
    }

    public function enableLog(&$Model, $enable = null)
    {
        if ($enable !== null) {
            $this->settings[$Model->alias]['enabled'] = $enable;
        }

        return $this->settings[$Model->alias]['enabled'];
    }

    /**
     * Useful for getting logs for a model, takes params to narrow find.
     * This method can actually also be used to find logs for all models or
     * even another model. Using no params will return all activities for
     * the models it is called from.
     *
     * Possible params :
     * 'model' 		: mixed  (NULL) String with className, NULL to get current or FALSE to get everything
     * 'action' 	: string (NULL) String with action (add/edit/delete), NULL gets all
     * 'order' 		: string ('created DESC') String with custom order
     * 'conditions  : array  ([]) Add custom conditions
     * 'model_id'	: int	 (NULL) Add a int
     *
     * (remember to use your own user key if you're not using 'user_id')
     * 'user_id' 	: int 	 (NULL) Defaults to all users, supply id if you want for only one User
     *
     * @param object $Model
     * @param array  $params
     *
     * @return array
     */
    public function findLog(&$Model, $params = [])
    {
        $defaults = array(
             $this->settings[$Model->alias]['classField'] => null,
             'action' => null,
             'order' => 'Log.created DESC',
             $this->settings[$Model->alias]['userKey'] => null,
             'conditions' => [],
             $this->settings[$Model->alias]['foreignKey'] => null,
             'fields' => [],
             'limit' => 50,
        );
        $params = array_merge($defaults, $params);
        $options = array('order' => $params['order'], 'conditions' => $params['conditions'], 'fields' => $params['fields'], 'limit' => $params['limit']);
        if ($params[$this->settings[$Model->alias]['classField']] === null) {
            $params[$this->settings[$Model->alias]['classField']] = $Model->alias;
        }
        if ($params[$this->settings[$Model->alias]['classField']]) {
            if (isset($this->Log->_schema[$this->settings[$Model->alias]['classField']])) {
                $options['conditions']['Log.'.$this->settings[$Model->alias]['classField']] = $params[$this->settings[$Model->alias]['classField']];
            } elseif (isset($this->Log->_schema['description'])) {
                $options['conditions']['Log.description LIKE '] = $params[$this->settings[$Model->alias]['classField']].'%';
            } else {
                return false;
            }
        }
        if ($params['action'] && isset($this->Log->_schema['action'])) {
            $options['conditions']['Log.action'] = $params['action'];
        }
        if ($params[ $this->settings[$Model->alias]['userKey'] ] && $this->UserModel && (is_numeric($params[ $this->settings[$Model->alias]['userKey'] ])
            || Validation::uuid($params[ $this->settings[$Model->alias]['userKey'] ]))) {
            $options['conditions'][$this->settings[$Model->alias]['userKey']] = $params[ $this->settings[$Model->alias]['userKey'] ];
        }
        if ($params[$this->settings[$Model->alias]['foreignKey']] &&
            (is_numeric($params[$this->settings[$Model->alias]['foreignKey']])
    || Validation::uuid($params[$this->settings[$Model->alias]['foreignKey']]))
                ) {
            $options['conditions']['Log.'.$this->settings[$Model->alias]['foreignKey']] = $params[$this->settings[$Model->alias]['foreignKey']];
        }
        $res = $this->Log->find('all', $options);

        return $res;
    }

    /** A wrapper for findLog */
    public function quickFindLog(&$Model, $alias, $id, $conditions = null, $order = null)
    {
        $f_cond = array(
            $this->settings[$Model->alias]['classField'] => $alias,
            $this->settings[$Model->alias]['foreignKey'] => $id,
            'conditions' => $conditions,
            'order' => $order,
        );
        $res = $this->findLog($Model, $f_cond);

        return $res;
    }

     /** Gets the last logged modification for a particular object, children are
      * ignored. Extra search conditions can be passed. */
     public function findLastModification(&$Model, $id = null, $conditions = [])
     {
         if (!$id) {
             $id = $Model->id;
         }
         if (!$id) {
             return null;
         }

         $log = $Model->findLog(array(
                     $this->settings[$Model->alias]['classField'] => $Model->alias,
                     $this->settings[$Model->alias]['foreignKey'] => $id,
                     'conditions' => $conditions,
                     'order' => 'Log.created DESC',
                     'limit' => 1,
                     ));
         if (isset($log[0])) {
             return $log[0];
         } else {
             return null;
         }
     }

     /** Find all modifications since a given date. In the simple mode, only
      * changes for the current model and given $id are retrieved, but you can
      * alternatively retrieve all related models data if you provide a data
      * structure as $useThisStructure. For example, if you pass the whole
      * array of a request (with its children, including their IDs in the 'id'
      * field)), changes for all those objects will be returned.
      *
      * @param $id ID of the main object
      * @param $date Date since which all changes will be reported
      * @param $useThisStructure Optional array, that includes this and related
      * models (with their IDs) so that changes for all them are reported.
      * $param $conditions Extra find conditions, applied to all searches
      */
     public function findModificationsSince(&$Model, $id = null, $date = '1970-01-01',
             &$useThisStructure = null, $conditions = [],
             $order = 'Log.created ASC')
     {
         $alias = $Model->alias;

         if (!$useThisStructure) {
             if (!$id) {
                 $id = $Model->id;
             }
             if (!$id) {
                 return null;
             }
             $useThisStructure[$alias]['id'] = $id;
         }

         $modelID = $useThisStructure[$alias]['id'];

         $date_cond = array('Log.created >' => $date);
         $cond = array_merge($date_cond, $conditions);
         $related = [];

/** Parse the structure, to keep related models => IDs.
 * Construct an array with all model => id of objects related to a.
 * given one. */

         $model_log = [];

         foreach ($useThisStructure as $r_model => $data) {
             // Assume that all primaryKeys are always 'id'. Easier.
             if (isset($data['id'])) {
                 if ($r_model != 'Attachment') {
                     $id = $data['id'];
                     $related[$r_model] = $data['id'];
                     if ($r_model == $Model->alias) {
                         $s_model = $r_model;
                     } else {
                         // Secondary models are stored in the log file with
                         // their real names, not their aliases.
                         $s_model = $Model->{$r_model}->name;
                     }
                     $res = $Model->quickFindLog($s_model, $id, $cond, $order);
                 } else {
                     // Attachments are not logged as changes in the same
                     // object, but as successive new objects. We have to find
                     // them in another way, and reformat the result.
                     $attachments = $Model->Attachment->find('all', $modelID,
                             array('conditions' => array('created' > $date,
                                     'foreign_key' => $modelID, )));
                     $res = [];
                     foreach ($attachments as &$file) {
                         $attach = &$file['Attachment'];
                         $res[] = array('Log' => array(
                                     'created' => $attach['created'],
                                     'model' => 'Attachment',
                                     'foreign_key' => $attach['id'],
                                     'action' => 'add',
                                     'user_id' => null,
                                     'change' => 'id () => ('.
                                     $attach['id'].'), basename () => ('
                                         .$attach['basename'].'), size () => ('.
                                             $attach['size'].')',
                                             ),
                                     );
                     }
                 }

                 if (!empty($res)) {
                     $clean = [];
                     foreach ($res as $l) {
                         $clean[] = $l['Log'];
                     }
                     $model_log[$r_model]['id'] = $id;
                     $model_log[$r_model]['Log'] = $clean;
                 }
             } else {
                 if ($r_model == 'Attachment') {
                     $ids = $Model->Attachment->findByForeignKey($Model->id);
                 }

                 // hasMany-like relationships
                 foreach ($data as $key => $child) {
                     if (is_array($child) && isset($child['id'])) {
                         $id = $child['id'];
                         $related[$r_model][$key] = $id;
                         $res = $Model->
                             quickFindLog($r_model, $id, $cond, $order);

                         if (!empty($res)) {
                             $clean = [];
                             foreach ($res as $l) {
                                 $clean[] = $l['Log'];
                             }
                             $model_log[$r_model][$key]['id'] = $id;
                             $model_log[$r_model][$key]['Log'] = $clean;
                         }
                     }
                 }
             }
         }

         return $model_log;
     }

     /** Take the output of findModificationsSince() parse it and format as an
      * array, reporting per model the initial and final values for
      * each modified field.
      */
     public function formatChangesPerField(&$Model, $report, $showAllChanges = false)
     {
         $changes = [];

         foreach ($report as $model => &$data) {
             if (Set::numeric(array_keys($data))) {
                 // all keys are numbers? Then is "many" relationships
                 $data_array = &$data;
                 $many = true;
             } else {
                 $data_array = array($data);
                 $many = false;
             }
             foreach ($data_array as $key => &$object) {
                 $id = $object['id'];
                 $history = $object['Log'];
                 if ($many) {
                     $changes[$model][$key] = [];
                     $set = &$changes[$model][$key];
                 } else {
                     $changes[$model] = [];
                     $set = &$changes[$model];
                 }
                 foreach ($history as $changeset) {
                     $date = $changeset['created'];
                     $author = $changeset['user_id'];
                     $action = $changeset['action'];
                     $change = $changeset['change'];
                     preg_match_all("/([^ ]*) \((.*)\) => \((.*)\)/U", $change,
                             $matches);
                     foreach ($matches[0] as $index => $fullMatch) {
                         $field = $matches[1][$index];
                         $old = $matches[2][$index];
                         $new = $matches[3][$index];
                         // Store initial value (only once, in case there are
                         // multiple editions).
                         if (!isset($set[$field]['old'])) {
                             $set[$field]['old'] = $old;
                         }
                         // Store last value as 'new'. We are assuming that the
                         // report comes sorted by date.
                         $set[$field]['new'] = $new;
                         $set[$field]['date'] = $date;
                         $set[$field]['author'] = $author;
                         $set[$field]['action'] = $action;

                         if ($showAllChanges) {
                             // Store not only initial and final values, but
                             // also all possible intermediate modifications.
                             $set[$field]['changes']
                                 [$date]['value'] = $new;
                             $set[$field]['changes']
                                 [$date]['author'] = $author;
                             $set[$field]['changes']
                                 [$date]['action'] = $action;
                         }
                     }
                 }
                 if (!$showAllChanges) {
                     // When not showing all intermediate states, we have to
                     // consider that there is not real change if the final
                     // value is equal to the initial one: the user reverted
                     // the edition.
                     foreach ($set as $field => $fdata) {
                         if ($fdata['new'] == $fdata['old']) {
                             unset($set[$field]);
                         }
                     }
                 }
             }
         }

         return $changes;
     }

    /**
     * Get list of actions for one user.
     * Params for getting (one line) activity descriptions
     * and/or for just one model.
     *
     * @example $this->Model->findUserActions(301,array('model' => 'BookTest'));
     * @example $this->Model->findUserActions(301,array('events' => true));
     * @example $this->Model->findUserActions(301,array('fields' => array('id','model'),'model' => 'BookTest');
     *
     * @param object $Model
     * @param int    $user_id
     * @param array  $params
     *
     * @return array
     */
    public function findUserActions(&$Model, $user_id, $params = [])
    {
        if (!$this->UserModel) {
            return null;
        }
        // if logged in user is asking for her own log, use the data we allready have
        if (isset($this->user)
             && isset($this->user[$this->UserModel->alias][$this->UserModel->primaryKey])
             && $user_id == $this->user[$this->UserModel->alias][$this->UserModel->primaryKey]
             && isset($this->user[$this->UserModel->alias][$this->UserModel->displayField])) {
            $username = $this->user[$this->UserModel->alias][$this->UserModel->displayField];
        } else {
            $this->UserModel->recursive = -1;
            $user = $this->UserModel->find(array($this->UserModel->primaryKey => $user_id));
            $username = $user[$this->UserModel->alias][$this->UserModel->displayField];
        }
        $fields = [];
        if (isset($params['fields'])) {
            if (is_array($params['fields'])) {
                $fields = $params['fields'];
            } else {
                $fields = array($params['fields']);
            }
        }
        $conditions = array($this->settings[$Model->alias]['userKey'] => $user_id);
        if (isset($params[$this->settings[$Model->alias]['classField']])) {
            $conditions['Log.'.$this->settings[$Model->alias]['classField']] = $params[$this->settings[$Model->alias]['classField']];
        }
        $data = $this->Log->find('all', array(
            'conditions' => $conditions,
            'recursive' => -1,
            'fields' => $fields,
        ));
        if (!isset($params['events']) || (isset($params['events']) && $params['events'] == false)) {
            return $data;
        }
        $result = [];
        foreach ($data as $key => $row) {
            $one = $row['Log'];
            $result[$key]['Log']['id'] = $one['id'];
            $result[$key]['Log']['event'] = $username;
            // have all the detail models and change as list :
            if (isset($one[$this->settings[$Model->alias]['classField']]) && isset($one['action']) && isset($one['change']) && isset($one[$this->settings[$Model->alias]['foreignKey']])) {
                if ($one['action'] == 'edit') {
                    $result[$key]['Log']['event'] .= ' edited '.$one['change'].' of '.low($one[$this->settings[$Model->alias]['classField']]).'(id '.$one[$this->settings[$Model->alias]['foreignKey']].')';
                    //	' at '.$one['created'];
                } elseif ($one['action'] == 'add') {
                    $result[$key]['Log']['event'] .= ' added a '.low($one[$this->settings[$Model->alias]['classField']]).'(id '.$one[$this->settings[$Model->alias]['foreignKey']].')';
                } elseif ($one['action'] == 'delete') {
                    $result[$key]['Log']['event'] .= ' deleted the '.low($one[$this->settings[$Model->alias]['classField']]).'(id '.$one[$this->settings[$Model->alias]['foreignKey']].')';
                }
            } elseif (isset($one[$this->settings[$Model->alias]['classField']]) && isset($one['action']) && isset($one[$this->settings[$Model->alias]['foreignKey']])) { // have model,model_id and action
                 if ($one['action'] == 'edit') {
                     $result[$key]['Log']['event'] .= ' edited '.low($one[$this->settings[$Model->alias]['classField']]).'(id '.$one[$this->settings[$Model->alias]['foreignKey']].')';
                    //	' at '.$one['created'];
                 } elseif ($one['action'] == 'add') {
                     $result[$key]['Log']['event'] .= ' added a '.low($one[$this->settings[$Model->alias]['classField']]).'(id '.$one[$this->settings[$Model->alias]['foreignKey']].')';
                 } elseif ($one['action'] == 'delete') {
                     $result[$key]['Log']['event'] .= ' deleted the '.low($one[$this->settings[$Model->alias]['classField']]).'(id '.$one[$this->settings[$Model->alias]['foreignKey']].')';
                 }
            } else { // only description field exist
                $result[$key]['Log']['event'] = $one['description'];
            }
        }

        return $result;
    }
    /**
     * Use this to supply a model with the data of the logged in User.
     * Intended to be called in AppController::beforeFilter like this :.
     *
     *   	if ($this->{$this->modelClass}->Behaviors->attached('Logable')) {
     *			$this->{$this->modelClass}->setUserData($activeUser);/
     *		}
     *
     * The $userData array is expected to look like the result of a
     * User::find(array('id'=>123));
     *
     * @param object $Model
     * @param array  $userData
     */
    public function setUserData(&$Model, $userData = null)
    {
        if ($userData) {
            $this->user = $userData;
        }
    }

    /**
     * Used for logging custom actions that arent crud, like login or download.
     *
     * @example $this->Boat->customLog('ship', 66, array('title' => 'Titanic heads out'));
     *
     * @param object $Model
     * @param string $action name of action that is taking place (dont use the crud ones)
     * @param int    $id     id of the logged item (ie model_id in logs table)
     * @param array  $values optional other values for your logs table
     */
    public function customLog(&$Model, $action, $id, $values = [])
    {
        $logData['Log'] = $values;
        /* @todo clean up $logData */
        if (isset($this->Log->_schema[$this->settings[$Model->alias]['foreignKey']]) && is_numeric($id)) {
            $logData['Log'][$this->settings[$Model->alias]['foreignKey']] = $id;
        }
        $title = null;
        if (isset($values['title'])) {
            $title = $values['title'];
            unset($logData['Log']['title']);
        }
        $logData['Log']['action'] = $action;
        $this->_saveLog($Model, $logData, $title);
    }

    public function clearUserData(&$Model)
    {
        $this->user = null;
    }

    public function setUserIp(&$Model, $userIP = null)
    {
        $this->userIP = $userIP;
    }

    public function beforeDelete(&$Model)
    {
        if (!$this->settings[$Model->alias]['enabled']) {
            return true;
        }
        if (isset($this->settings[$Model->alias]['skip']['delete']) && $this->settings[$Model->alias]['skip']['delete']) {
            return true;
        }
        $Model->recursive = -1;
        $Model->read();

        return true;
    }

    public function afterDelete(&$Model)
    {
        if (!$this->settings[$Model->alias]['enabled']) {
            return true;
        }
        if (isset($this->settings[$Model->alias]['skip']['delete']) && $this->settings[$Model->alias]['skip']['delete']) {
            return true;
        }
        $logData = [];
        if (isset($this->Log->_schema['description'])) {
            $logData['Log']['description'] = $Model->alias;
            if (isset($Model->data[$Model->alias][$Model->displayField]) && $Model->displayField != $Model->primaryKey) {
                $logData['Log']['description'] .= ' "'.$Model->data[$Model->alias][$Model->displayField].'"';
            }
            if ($this->settings[$Model->alias]['description_ids']) {
                $logData['Log']['description'] .= ' ('.$Model->id.') ';
            }
            $logData['Log']['description'] .= __t('deleted');
        }
        $logData['Log']['action'] = 'delete';
        $this->_saveLog($Model, $logData);
    }

    public function beforeSave(&$Model)
    {
        if (isset($this->Log->_schema['change']) && $Model->id) {
            $this->old = $Model->find('first', array('conditions' => array($Model->primaryKey => $Model->id), 'recursive' => -1));
        }

        return true;
    }

    public function afterSave(&$Model, $created)
    {
        if (!$this->settings[$Model->alias]['enabled']) {
            return true;
        }
        if (isset($this->settings[$Model->alias]['skip']['add']) && $this->settings[$Model->alias]['skip']['add'] && $created) {
            return true;
        } elseif (isset($this->settings[$Model->alias]['skip']['edit']) && $this->settings[$Model->alias]['skip']['edit'] && !$created) {
            return true;
        }
        if (empty($Model->data)) {
            // Nothing saved, like when updating an unexisting ID. I wonder why
            // do we get here in that case, but it happens. (We do that crazy
            // thing in some unit tests).
            return true;
        }
        $keys = array_keys($Model->data[$Model->alias]);
        $diff = array_diff($keys, $this->settings[$Model->alias]['ignore']);
        if (sizeof($diff) == 0 && empty($Model->logableAction)) {
            return false;
        }
        if ($Model->id) {
            $id = $Model->id;
        } elseif ($Model->insertId) {
            $id = $Model->insertId;
        }
        if (isset($this->Log->_schema[$this->settings[$Model->alias]['foreignKey']])) {
            $logData['Log'][$this->settings[$Model->alias]['foreignKey']] = $id;
        }
        if (isset($this->Log->_schema['description'])) {
            $logData['Log']['description'] = $Model->alias.' ';
            if (isset($Model->data[$Model->alias][$Model->displayField]) && $Model->displayField != $Model->primaryKey) {
                $logData['Log']['description'] .= '"'.$Model->data[$Model->alias][$Model->displayField].'" ';
            }

            if ($this->settings[$Model->alias]['description_ids']) {
                $logData['Log']['description'] .= '('.$id.') ';
            }

            if ($created) {
                $logData['Log']['description'] .= __t('added');
            } else {
                $logData['Log']['description'] .= __t('updated');
            }
        }
        if (isset($this->Log->_schema['action'])) {
            if ($created) {
                $logData['Log']['action'] = 'add';
            } else {
                $logData['Log']['action'] = 'edit';
            }
        }
        if (isset($this->Log->_schema['change'])) {
            $logData['Log']['change'] = '';
            $db_fields = array_keys($Model->_schema);
            $changed_fields = [];
            foreach ($Model->data[$Model->alias] as $key => $value) {
                if (isset($Model->data[$Model->alias][$Model->primaryKey]) && !empty($this->old) && isset($this->old[$Model->alias][$key])) {
                    $old = $this->old[$Model->alias][$key];
                } else {
                    $old = '';
                }
                if ($key != 'modified'
                    && !in_array($key, $this->settings[$Model->alias]['ignore'])
                    && $value != $old && in_array($key, $db_fields)) {
                    if ($this->settings[$Model->alias]['change'] == 'full') {
                        $changed_fields[] = $key.' ('.$old.') => ('.$value.')';
                    } elseif ($this->settings[$Model->alias]['change'] == 'serialize') {
                        $changed_fields[$key] = array('old' => $old, 'value' => $value);
                    } else {
                        $changed_fields[] = $key;
                    }
                }
            }
            $changes = sizeof($changed_fields);
            // Store the number of changes in the model, like that we can reuse
            // this information in other places.
            $Model->logable_changes = $changes;
            if ($changes == 0) {
                return true;
            }
            if ($this->settings[$Model->alias]['change'] == 'serialize') {
                $logData['Log']['change'] = serialize($changed_fields);
            } else {
                $logData['Log']['change'] = implode(', ', $changed_fields);
            }
            $logData['Log']['changes'] = $changes;
        }
        $this->_saveLog($Model, $logData);
    }

    /**
     * Does the actual saving of the Log model. Also adds the special field if possible.
     *
     * If model field in table, add the Model->alias
     * If action field is NOT in table, remove it from dataset
     * If the userKey field in table, add it to dataset
     * If userData is supplied to model, add it to the title
     *
     * @param object $Model
     * @param array  $logData
     */
    public function _saveLog(&$Model, $logData, $title = null)
    {
        if ($title !== null) {
            $logData['Log']['title'] = $title;
        } elseif ($Model->displayField == $Model->primaryKey) {
            $logData['Log']['title'] = $Model->alias.' ('.$Model->id.')';
        } elseif (isset($Model->data[$Model->alias][$Model->displayField])) {
            $logData['Log']['title'] = $Model->data[$Model->alias][$Model->displayField];
        } else {
            $logData['Log']['title'] = $Model->field($Model->displayField);
        }

        if (isset($this->Log->_schema[$this->settings[$Model->alias]['classField']])) {
            // by miha nahtigal
            $logData['Log'][$this->settings[$Model->alias]['classField']] = $Model->name;
        }

        if (isset($this->Log->_schema[$this->settings[$Model->alias]['foreignKey']]) && !isset($logData['Log'][$this->settings[$Model->alias]['foreignKey']])) {
            if ($Model->id) {
                $logData['Log'][$this->settings[$Model->alias]['foreignKey']] = $Model->id;
            } elseif ($Model->insertId) {
                $logData['Log'][$this->settings[$Model->alias]['foreignKey']] = $Model->insertId;
            }
        }

        if (!isset($this->Log->_schema[ 'action' ])) {
            unset($logData['Log']['action']);
        } elseif (isset($Model->logableAction) && !empty($Model->logableAction)) {
            $logData['Log']['action'] = implode(',', $Model->logableAction); // . ' ' . $logData['Log']['action'];
            unset($Model->logableAction);
        }

        if (isset($this->Log->_schema[ 'version_id' ]) && isset($Model->version_id)) {
            $logData['Log']['version_id'] = $Model->version_id;
            unset($Model->version_id);
        }

        if (isset($this->Log->_schema[ 'ip' ]) && isset($this->userIP)) {
            $logData['Log']['ip'] = $this->userIP;
        }

        if (isset($this->Log->_schema[ $this->settings[$Model->alias]['userKey'] ]) && $this->user) {
            $logData['Log'][$this->settings[$Model->alias]['userKey']] = $this->user[$this->UserModel->alias][$this->UserModel->primaryKey];
        }

        if (isset($this->Log->_schema['description'])) {
            if ($this->user && $this->UserModel) {
                $logData['Log']['description'] .= ' by '.$this->settings[$Model->alias]['userModel'].' "'.
                        $this->user[$this->UserModel->alias][$this->UserModel->displayField].'"';
                if ($this->settings[$Model->alias]['description_ids']) {
                    $logData['Log']['description'] .= ' ('.$this->user[$this->UserModel->alias][$this->UserModel->primaryKey].')';
                }
            } else {
                // UserModel is active, but the data hasnt been set. Assume system action.
                $logData['Log']['description'] .= ' by System';
            }
            $logData['Log']['description'] .= '.';
        }
        if (Configure::read('Application.activityID')) {
            $logData['Log']['activity_id'] =
                Configure::read('Application.activityID');
        }
        $this->Log->create($logData);
        $this->Log->save(null, array('validate' => false, 'callbacks' => false));
    }
}
