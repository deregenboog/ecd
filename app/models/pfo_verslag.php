<?php

class PfoVerslag extends AppModel
{
    public $name = 'PfoVerslag';
    public $actsAs = ['Containable'];
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    public $belongsTo = [
            'Medewerker' => [
                    'className' => 'Medewerker',
                    'foreignKey' => 'medewerker_id',
                    'conditions' => '',
                    'fields' => '',
                    'order' => '',
            ],
    ];
    public $hasMany = [
        'PfoClientenVerslag' => [
            'className' => 'PfoClientenVerslag',
            'foreignKey' => 'pfo_verslag_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => '',
        ],
    ];
    public $contact_type = [
        'In persoon' => 'In persoon',
        'Telefonisch' => 'Telefonisch',
        'E-Mail' => 'E-Mail',
        'Extern' => 'Extern',
    ];
}
