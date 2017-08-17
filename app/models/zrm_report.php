<?php

class ZrmReport extends AppModel
{
    public static $zrmReportModels = [
        'ZrmV2Report' => '2017-08-01',
        'ZrmReport' => '2010-01-01',
    ];

    public $name = 'ZrmReport';

    public $zrm_items = [
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
    ];

    public $zrm_models = [
        'Intake' => [],
        'MaatschappelijkWerk' => [],
        'Awbz' => [],
        'Hi5' => [],
    ];

    public $belongsTo = [
        'Klant' => [
            'className' => 'Klant',
            'foreignKey' => 'klant_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ],
    ];

    public $zrm_names = [
        'Intake' => 'Registratie',
        'MaatschappelijkWerk' => 'MaatschappelijkWerk',
        'Awbz' => 'Awbz',
        'Hi5' => 'Hi5',
        'GroepsactiviteitenIntake' => 'Groepsactiviteiten',
        'IzIntake' => 'IZ Intake',
    ];

    public $validate = [
        'request_module' => [
            'notempty' => [
                'rule' => ['notEmpty'],
                'message' => 'Voer een module in',
                'required' => true,
            ],
         ],
        'inkomen' => [
            'allowEmpty' => true,
            'checkRequired' => [
                'rule' => ['checkRequired'],
                'message' => 'Verplicht veld: Inkomen',
            ],
        ],
        'dagbesteding' => [
            'allowEmpty' => true,
            'checkRequired' => [
                'rule' => ['checkRequired'],
                'message' => 'Verplicht veld: Dagbesteding',
            ],
        ],
        'huisvesting' => [
            'allowEmpty' => true,
            'checkRequired' => [
                'rule' => ['checkRequired'],
                'message' => 'Verplicht veld: Huisvesting',
            ],
        ],
        'gezinsrelaties' => [
            'allowEmpty' => true,
            'checkRequired' => [
                'rule' => ['checkRequired'],
                'message' => 'Verplicht veld: Gezinsrelaties',
            ],
        ],
        'geestelijke_gezondheid' => [
            'allowEmpty' => true,
            'checkRequired' => [
                'rule' => ['checkRequired'],
                'message' => 'Verplicht veld: Geestelijke gezondheid',
            ],
        ],
        'fysieke_gezondheid' => [
            'allowEmpty' => true,
            'checkRequired' => [
                'rule' => ['checkRequired'],
                'message' => 'Verplicht veld: Fysieke gezondheid',
            ],
        ],
        'verslaving' => [
            'allowEmpty' => true,
            'checkRequired' => [
                'rule' => ['checkRequired'],
                'message' => 'Verplicht veld: Verslaving',
            ],
        ],
        'adl_vaardigheden' => [
            'allowEmpty' => true,
            'checkRequired' => [
                'rule' => ['checkRequired'],
                'message' => 'Verplicht veld: ADL-vaardigheden',
            ],
        ],
        'sociaal_netwerk' => [
            'allowEmpty' => true,
            'checkRequired' => [
                'rule' => ['checkRequired'],
                'message' => 'Verplicht veld: Sociaal netwerk',
            ],
        ],
        'maatschappelijke_participatie' => [
            'allowEmpty' => true,
            'checkRequired' => [
                'rule' => ['checkRequired'],
                'message' => 'Verplicht veld: Maatschappelijke participatie',
            ],
        ],
        'justitie' => [
            'allowEmpty' => true,
            'checkRequired' => [
                'rule' => ['checkRequired'],
                'message' => 'Verplicht veld: Justitie',
            ],
        ],
    ];

    public static function getZrmReportModels()
    {
        return array_keys(self::$zrmReportModels);
    }

    public static function getZrmReportModel()
    {
        $today = new \DateTime('today');
        foreach (self::$zrmReportModels as $zrmReportModel => $date) {
            if ($today >= new \DateTime($date)) {
                return $zrmReportModel;
            }
        }

        return $zrmReportModel;
    }

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

    public function checkRequired($field = [])
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
            $groups = [];
            foreach ($permissions as $k => $models) {
                if (in_array($model, $models)) {
                    $groups[] = $k;
                }
            }
            $this->zrm_models[$model] = $groups;
        }

        return [
            'zrm_items' => $this->zrm_items,
            'zrm_required_fields' => $this->zrm_required_fields,
            'zrm_models' => $this->zrm_models,
            'zrm_names' => $this->zrm_names,
        ];
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
        $this->recursive = -1;

        $zrm = $this->find('first', [
            'conditions' => [
                'klant_id' => $klant_id,
            ],
            'order' => 'ZrmReport.created DESC',
        ]);

        return $zrm;
    }

    public function get_zrm_report($model, $foreign_key)
    {
        $this->recursive = -1;

        $zrm = $this->find('first', [
            'conditions' => [
                'model' => $model,
                'foreign_key' => $foreign_key,
            ],
            'order' => 'ZrmReport.created DESC',
        ]);

        return $zrm;
    }
}
