<?php

class IzDeelnemersIzIntervisiegroep extends AppModel
{
    public $name = 'IzDeelnemersIzIntervisiegroep';

    public $belongsTo = [
        'IzDeelnemer' => [
            'className' => 'IzDeelnemer',
            'foreignKey' => 'iz_deelnemer_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ],
        'IzIntervisiegroep' => [
            'className' => 'IzIntervisiegroep',
            'foreignKey' => 'iz_intervisiegroep_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ],
    ];
}
