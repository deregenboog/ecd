<?php

class IzVerslag extends AppModel
{
    public $name = 'IzVerslag';
    public $displayField = 'medewerker_id';

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
}
