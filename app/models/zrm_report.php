<?php

class ZrmReport extends AppModel
{
    public $name = 'ZrmReport';

    public $zrm_items = array(
            'inkomen' => 'Inkomen',
            'dagbesteding' => 'Dagbesteding',
            'huisvesting' => 'Huisvesting',
            'gezinsrelaties' => 'Gezinsrelaties',
            'geestelijke_gezondheid' => 'Geestelijke gezondheid',
            'fysieke_gezondheid' => 'Fysieke gezondheid',
            'verslaving' => 'Verslaving',
            'adl_vaardigheden' => 'ADL-vaardigheden',
            'sociaal_netwerk' => 'Sociaal netwerk',
            'maatschappelijke_participatie' => 'Maatschappelijke participatie',
            'justitie' => 'Justitie',
    );

    public $zrm_models = array(
            'Intake' => array(),
            'MaatschappelijkWerk' => array(),
            'Awbz' => array(),
            'Hi5' => array(),
    );

    public $belongsTo = array(
        'Klant' => array(
            'className' => 'Klant',
            'foreignKey' => 'klant_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ),
    );

    public $zrm_names = array(
        'Intake' => 'Registratie',
        'MaatschappelijkWerk' => 'MaatschappelijkWerk',
        'Awbz' => 'Awbz',
        'Hi5' => 'Hi5',
        'GroepsactiviteitenIntake' => 'Groepsactiviteiten',
        'IzIntake' => 'IZ Intake',
    );

    public $validate = array(
            'request_module' => array(
                'notempty' => array(
                    'rule' => array(
                        'notEmpty',
                    ),
                    'message' => 'Voer een module in',
                    'required' => true,
                ),
             ),
            'inkomen' => array(
                'allowEmpty' => true,
                'checkRequired' => array(

                    'rule' => array('checkRequired'),
                    'message' => 'verplicht veld Inkomen',
                ),
            ),
            'dagbesteding' => array(
                    'allowEmpty' => true,
                    'checkRequired' => array(
                            'rule' => array('checkRequired'),
                            'message' => 'verplicht veld Dagbesteding',
                    ),
            ),
            'huisvesting' => array(
                    'allowEmpty' => true,
                    'checkRequired' => array(
                            'rule' => array('checkRequired'),
                            'message' => 'verplicht veld Huisvesting',
                    ),
            ),
            'gezinsrelaties' => array(
                    'allowEmpty' => true,
                    'checkRequired' => array(
                            'rule' => array('checkRequired'),
                            'message' => 'verplicht veld Gezinsrelaties',
                    ),
            ),
            'geestelijke_gezondheid' => array(
                    'allowEmpty' => true,
                    'checkRequired' => array(
                            'rule' => array('checkRequired'),
                            'message' => 'verplicht veld Geestelijke gezondheid',
                    ),
            ),
            'fysieke_gezondheid' => array(
                    'allowEmpty' => true,
                    'checkRequired' => array(
                            'rule' => array('checkRequired'),
                            'message' => 'verplicht veld Fysieke gezondheid',
                    ),
            ),
            'verslaving' => array(
                    'allowEmpty' => true,
                    'checkRequired' => array(
                            'rule' => array('checkRequired'),
                            'message' => 'verplicht veld Verslaving',
                    ),
            ),
            'adl_vaardigheden' => array(
                    'allowEmpty' => true,
                    'checkRequired' => array(
                            'rule' => array('checkRequired'),
                            'message' => 'verplicht veld ADL-vaardigheden',
                    ),
            ),
            'sociaal_netwerk' => array(
                    'allowEmpty' => true,
                    'checkRequired' => array(
                            'rule' => array('checkRequired'),
                            'message' => 'verplicht veld Sociaal netwerk',
                    ),
            ),
            'maatschappelijke_participatie' => array(
                    'allowEmpty' => true,
                    'checkRequired' => array(
                            'rule' => array('checkRequired'),
                            'message' => 'verplicht veld Maatschappelijke participatie',
                    ),
            ),
            'justitie' => array(
                    'allowEmpty' => true,
                    'checkRequired' => array(
                            'rule' => array('checkRequired'),
                            'message' => 'verplicht veld Justitie',
                    ),
            ),

    );

    public function afterSave($created)
    {
        if (!empty($this->data['ZrmReport']['klant_id'])) {
            $klant_id = $this->data['ZrmReport']['klant_id'];
            $this->Klant->recursive = -1;
            $klant = $this->Klant->read(null, $klant_id);

            if (!empty($klant)) {
                $this->Klant->saveField('last_zrm', date('Y-m-d'));
            }
        }

        parent::afterSave($created);
    }

    public function checkRequired($field = array())
    {
        if (empty($this->zrm_required_fields)) {
            $this->zrm_data();
        }
        if (!isset($this->data['ZrmReport']['request_module'])) {
            return true;
        }
        if (empty($this->data['ZrmReport']['request_module'])) {
            return true;
        }

        if (!isset($this->zrm_required_fields[$this->data['ZrmReport']['request_module']])) {
            return true;
        }

        $r = $this->zrm_required_fields[$this->data['ZrmReport']['request_module']];
        if (empty($r)) {
            return true;
        }
        foreach ($field as $k => $f) {
            if (in_array($k, $r) && empty($this->data['ZrmReport'][$k])) {
                return false;
            }
        }

        return true;
    }

    public function zrm_data()
    {
        if (!isset($this->zrm_required_fields)) {
            App::import('Model', 'ZrmSetting');
            $setting = new ZrmSetting();
            $s = $setting->required_fields();
            $this->zrm_required_fields = $s['required_fields'];
            $this->zrm_models = $s['zrm_models'];
        }

        $permissions = Configure::read('ACL.permissions');
        foreach ($permissions as $k => $models) {
            foreach ($models as $key => $model) {
                $permissions[$k][$key] = Inflector::singularize($model);
            }
        }

        foreach ($this->zrm_models as $model => $v) {
            $groups = array();

            foreach ($permissions as $k => $models) {
                if (in_array($model, $models)) {
                    $groups[] = $k;
                }
            }
            $this->zrm_models[$model] = $groups;
        }

        return array(
            'zrm_items' => $this->zrm_items,
            'zrm_required_fields' => $this->zrm_required_fields,
            'zrm_models' => $this->zrm_models,
            'zrm_names' => $this->zrm_names,
        );
    }

    public function update_zrm_data_for_edit(&$zrm, $model, $foreign_key, $klant_id)
    {
        if (empty($zrm['ZrmReport']['model'])) {
            $zrm['ZrmReport']['model'] = $model;
        }

        if (empty($zrm['ZrmReport']['foreign_key'])) {
            $zrm['ZrmReport']['foreign_key'] = $foreign_key;
        }

        if (empty($zrm['ZrmReport']['klant_id'])) {
            $zrm['ZrmReport']['klant_id'] = $klant_id;
        }

        return $zrm;
    }

    public function get_last_zrm_report($klant_id)
    {
        $conditions = array(
            'klant_id' => $klant_id,
        );

        $this->recursive = -1;

        $zrm = $this->find('first', array(
                'conditions' => $conditions,
                'order' => 'ZrmReport.created DESC',
        ));

        return $zrm;
    }

    public function get_zrm_report($model, $foreign_key)
    {
        $conditions = array(
            'model' => $model,
            'foreign_key' => $foreign_key,
        );

        $this->recursive = -1;

        $zrm = $this->find('first', array(
            'conditions' => $conditions,
            'order' => 'ZrmReport.created DESC',
        ));

        return $zrm;
    }
}
