<?php

class IzKoppeling extends AppModel
{
    public $name = 'IzKoppeling';

    public $displayField = 'iz_deelnemer_id';

    public $actsAs = ['Containable'];

    public $validate = [
        'medewerker_id' => [
            'notempty' => [
                'rule' => [
                    'notEmpty',
                ],
                'message' => 'Voer een medewerker in',
                'allowEmpty' => false,
                'required' => false,
            ],
        ],
        'iz_vraagaanbod_id' => [
            'notempty' => [
                'rule' => [
                    'notEmpty',
                ],
                'message' => 'Voer een reden in',
                'allowEmpty' => false,
                'required' => false,
            ],
        ],
        'iz_eindekoppeling_id' => [
            'notempty' => [
                'rule' => [
                    'notEmpty',
                ],
                'message' => 'Voer een reden in',
                'allowEmpty' => false,
                'required' => false,
            ],
        ],
        'startdatum' => [
            'notempty' => [
                'rule' => [
                    'notEmpty',
                ],
                'message' => 'Voer een startdatum in',
                'allowEmpty' => false,
                'required' => false,
            ],
        ],
        'iz_koppeling_id' => [
            'notempty' => [
                'rule' => [
                    'notEmpty',
                ],
                'message' => 'Selecteer een koppeling',
                'allowEmpty' => false,
                'required' => false,
            ],
        ],
        'koppleling_startdatum' => [
            'notempty' => [
                'rule' => [
                    'notEmpty',
                ],
                'message' => 'Voer een startdatum in',
                'allowEmpty' => false,
                'required' => false,
            ],
        ],
        'project_id' => [
            'notempty' => [
                'rule' => [
                    'notEmpty',
                ],
                'message' => 'Voer een project in',
                'allowEmpty' => false,
                'required' => false,
            ],
        ],
    ];

    public $belongsTo = [
        'IzDeelnemer' => [
            'className' => 'IzDeelnemer',
            'foreignKey' => 'iz_deelnemer_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ],
        'Medewerker' => [
            'className' => 'Medewerker',
            'foreignKey' => 'medewerker_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ],
        'IzEindekoppeling' => [
            'className' => 'IzEindekoppeling',
            'foreignKey' => 'iz_eindekoppeling_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ],
        'IzVraagaanbod' => [
            'className' => 'IzVraagaanbod',
            'foreignKey' => 'iz_vraagaanbod_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ],
    ];

    public function beforeSave($options = [])
    {
        $izDeelnemerId = $this->data['IzDeelnemer']['id'];
        $izDeelnemer = $this->IzDeelnemer->findById($izDeelnemerId);

        if ($izDeelnemer) {
            if ($izDeelnemer['IzDeelnemer']['model'] == 'Klant') {
                $this->data['IzKoppeling']['discr'] = 'hulpvraag';
            }
            if ($izDeelnemer['IzDeelnemer']['model'] == 'Vrijwilliger') {
                $this->data['IzKoppeling']['discr'] = 'hulpaanbod';
            }
        }

        return parent::beforeSave($options);
    }

    public function getCandidatesForProjects($persoon_model, $project_ids)
    {
        if ($persoon_model == 'Klant') {
            $model = 'Vrijwilliger';
        } else {
            $model = 'Klant';
        }

        $contain = [
            'IzDeelnemer',
        ];

        $today = date('Y-m-d');

        $conditions = [
            'IzDeelnemer.model' => $model,
            'IzKoppeling.iz_koppeling_id' => null,
            'IzKoppeling.project_id' => $project_ids,
            [
               'OR' => [
                    'IzKoppeling.einddatum' => null,
                    'IzKoppeling.einddatum >=' => $today,
                ],
            ],
            [
                'OR' => [
                    'IzKoppeling.startdatum' => null,
                    'IzKoppeling.startdatum <=' => $today,
                ],
            ],
        ];

        $all = $this->find('all', [
            'conditions' => $conditions,
            'contain' => $contain,
        ]);

        foreach ($all as $key => $a) {
            $all[$key][$model] = $this->IzDeelnemer->{$model}->getById($a['IzDeelnemer']['foreign_key']);
        }

        $projects = [];

        foreach ($project_ids as $p_id) {
            $projects[$p_id] = [];
        }

        foreach ($all as $a) {
            $projects[$a['IzKoppeling']['project_id']][$a['IzKoppeling']['id']] = $a[$model]['name'];
        }

        return $projects;
    }
}
