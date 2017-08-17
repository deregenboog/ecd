<?php

class Notitie extends AppModel
{
    public $name = 'Notitie';
    //The Associations below have been created with all possible keys, those that are not needed can be removed

    public $belongsTo = [
        'Klant' => [
            'className' => 'Klant',
            'foreignKey' => 'klant_id',
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
