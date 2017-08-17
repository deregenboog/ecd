<?php

class Verslaginfo extends AppModel
{
    public $name = 'Verslaginfo';

    public $displayField = 'klant_id';

    public $validate = [
        'klant_id' => [
            'numeric' => [
                'rule' => ['numeric'],
            ],
        ],
        'casemanager_email' => [
            'email' => [
                'rule' => ['email'],
                'message' => 'Niet een geldig e-mailadres.',
                'allowEmpty' => true,
                'required' => false,
            ],
        ],
        'trajectbegeleider_email' => [
            'email' => [
                'rule' => ['email'],
                'message' => 'Niet een geldig e-mailadres.',
                'allowEmpty' => true,
                'required' => false,
            ],
        ],
        'trajecthouder_extern_email' => [
            'email' => [
                'rule' => ['email'],
                'message' => 'Niet een geldig e-mailadres.',
                'allowEmpty' => true,
                'required' => false,
            ],
        ],
        'klantmanager_email' => [
            'email' => [
                'rule' => ['email'],
                'message' => 'Niet een geldig e-mailadres.',
                'allowEmpty' => true,
                'required' => false,
            ],
        ],
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
}
