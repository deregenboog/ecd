<?php

class IzIntake extends AppModel
{
    public $name = 'IzIntake';
    public $displayField = 'iz_deelnemer_id';

    const DECISION_VALUE_NO = 0;
    const DECISION_VALUE_YES = 1;

    public $actsAs = ['Containable'];

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
    ];

    public $validate = [
        'medewerker_id' => [
            'notempty' => [
                'rule' => [
                    'notEmpty',
                ],
                'message' => 'Een medewerker selecteren',
                'allowEmpty' => false,
                'required' => false,
            ],
        ],
    ];
}
