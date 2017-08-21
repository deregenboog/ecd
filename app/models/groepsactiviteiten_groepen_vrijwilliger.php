<?php

class GroepsactiviteitenGroepenVrijwilliger extends AppModel
{
    public $name = 'GroepsactiviteitenGroepenVrijwilliger';
    public $displayField = 'groepsactiviteiten_groep_id';

    public $actsAs = ['Containable'];

    public $validate = [
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
            'einddatum' => [
                    'notempty' => [
                            'rule' => [
                                    'notEmpty',
                            ],
                            'message' => 'Voer een einddatum in',
                            'allowEmpty' => false,
                            'required' => false,
                    ],
                    'datecompare' => [
                            'rule' => [
                                    'compareDates',
                            ],
                            'message' => 'Einddatum moet later dan startdatum zijn',
                    ],
            ],
            'groepsactiviteiten_reden_id' => [
                    'notempty' => [
                            'rule' => [
                                    'notEmpty',
                            ],
                            'message' => 'Voer een groepsactiviteiten_reden_id in',
                            'allowEmpty' => false,
                            'required' => false,
                    ],
            ],
    ];

    public function compareDates()
    {
        if (empty($this->data['GroepsactiviteitenGroepenVrijwilliger']['einddatum'])) {
            return true;
        }
        if (empty($this->data['GroepsactiviteitenGroepenVrijwilliger']['startdatum'])) {
            return true;
        }
        $s = strtotime($this->data['GroepsactiviteitenGroepenVrijwilliger']['startdatum']);
        $e = strtotime($this->data['GroepsactiviteitenGroepenVrijwilliger']['einddatum']);

        if ($e < $s) {
            return false;
        }

        return true;
    }

    public $belongsTo = [
        'GroepsactiviteitenGroep' => [
            'className' => 'GroepsactiviteitenGroep',
            'foreignKey' => 'groepsactiviteiten_groep_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ],
        'Vrijwilliger' => [
            'className' => 'Vrijwilliger',
            'foreignKey' => 'vrijwilliger_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ],
        'GroepsactiviteitenReden' => [
            'className' => 'GroepsactiviteitenReden',
            'foreignKey' => 'groepsactiviteiten_reden_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ],
    ];
}
