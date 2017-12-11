<?php

App::import('Model', 'ZrmReport');

class ZrmV2Report extends ZrmReport
{
    public $name = 'ZrmV2Report';

    public $zrm_items = [
        'financien' => 'Financiën',
        'werk_opleiding' => 'Werk en Opleiding',
        'tijdsbesteding' => 'Tijdsbesteding',
        'huisvesting' => 'Huisvesting',
        'huiselijke_relaties' => 'Huiselijke relaties',
        'geestelijke_gezondheid' => 'Geestelijke gezondheid',
        'lichamelijke_gezondheid' => 'Lichamelijke gezondheid',
        'middelengebruik' => 'Middelengebruik',
        'basale_adl' => 'Basale ADL',
        'instrumentele_adl' => 'Instrumentele ADL',
        'sociaal_netwerk' => 'Sociaal netwerk',
        'maatschappelijke_participatie' => 'Maatschappelijke participatie',
        'justitie' => 'Justitie',
    ];

    public $validate = [
        'request_module' => [
            'notempty' => [
                'rule' => ['notEmpty'],
                'message' => 'Voer een module in',
                'required' => true,
            ],
         ],
        'financien' => [
            'allowEmpty' => true,
            'checkRequired' => [
                'rule' => ['checkRequired'],
                'message' => 'Verplicht veld: Financiën',
            ],
        ],
        'werk_opleiding' => [
            'allowEmpty' => true,
            'checkRequired' => [
                'rule' => ['checkRequired'],
                'message' => 'Verplicht veld: Werk en Opleiding',
            ],
        ],
        'tijdsbesteding' => [
            'allowEmpty' => true,
            'checkRequired' => [
                'rule' => ['checkRequired'],
                'message' => 'Verplicht veld: Tijdsbesteding',
            ],
        ],
        'huisvesting' => [
            'allowEmpty' => true,
            'checkRequired' => [
                'rule' => ['checkRequired'],
                'message' => 'Verplicht veld: Huisvesting',
            ],
        ],
        'huiselijke_relaties' => [
            'allowEmpty' => true,
            'checkRequired' => [
                'rule' => ['checkRequired'],
                'message' => 'Verplicht veld: Huiselijke relaties',
            ],
        ],
        'geestelijke_gezondheid' => [
            'allowEmpty' => true,
            'checkRequired' => [
                'rule' => ['checkRequired'],
                'message' => 'Verplicht veld: Geestelijke gezondheid',
            ],
        ],
        'lichamelijke_gezondheid' => [
            'allowEmpty' => true,
            'checkRequired' => [
                'rule' => ['checkRequired'],
                'message' => 'Verplicht veld: Lichamelijke gezondheid',
            ],
        ],
        'middelengebruik' => [
            'allowEmpty' => true,
            'checkRequired' => [
                'rule' => ['checkRequired'],
                'message' => 'Verplicht veld: Middelengebruik',
            ],
        ],
        'basale_adl' => [
            'allowEmpty' => true,
            'checkRequired' => [
                'rule' => ['checkRequired'],
                'message' => 'Verplicht veld: Basale ADL',
            ],
        ],
        'instrumentele_adl' => [
            'allowEmpty' => true,
            'checkRequired' => [
                'rule' => ['checkRequired'],
                'message' => 'Verplicht veld: Instrumentele ADL',
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

    public function afterSave($created)
    {
        if (!empty($this->data['ZrmV2Report']['klant_id'])) {
            $klant_id = $this->data['ZrmV2Report']['klant_id'];
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

        if (!isset($this->data['ZrmV2Report']['request_module'])) {
            return true;
        }

        if (empty($this->data['ZrmV2Report']['request_module'])) {
            return true;
        }

        if (!isset($this->zrm_required_fields[$this->data['ZrmV2Report']['request_module']])) {
            return true;
        }

        $r = $this->zrm_required_fields[$this->data['ZrmV2Report']['request_module']];
        if (empty($r)) {
            return true;
        }

        foreach ($field as $k => $f) {
            if (in_array($k, $r) && empty($this->data['ZrmV2Report'][$k])) {
                return false;
            }
        }

        return true;
    }

    public function zrm_data()
    {
        if (!isset($this->zrm_required_fields)) {
            App::import('Model', 'ZrmV2Setting');
            $setting = new ZrmV2Setting();
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
        if (empty($zrm['ZrmV2Report']['model'])) {
            $zrm['ZrmV2Report']['model'] = $model;
        }

        if (empty($zrm['ZrmV2Report']['foreign_key'])) {
            $zrm['ZrmV2Report']['foreign_key'] = $foreign_key;
        }

        if (empty($zrm['ZrmV2Report']['klant_id'])) {
            $zrm['ZrmV2Report']['klant_id'] = $klant_id;
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
            'order' => 'ZrmV2Report.created DESC',
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
            'order' => 'ZrmV2Report.created DESC',
        ]);

        return $zrm;
    }
}
