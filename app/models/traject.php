<?php

class Traject extends AppModel
{
    public $name = 'Traject';
    public $actsAs = ['Containable'];
    //The Associations below have been created with all possible keys, those that are not needed can be removed

    public $belongsTo = [
            'Klant' => [
                    'className' => 'Klant',
                    'foreignKey' => 'klant_id',
                    'conditions' => '',
                    'fields' => '',
                    'order' => '',
            ],
            'Trajectbegeleider' => [
                    'className' => 'Medewerker',
                    'foreignKey' => 'trajectbegeleider_id',
                    'conditions' => '',
                    'fields' => '',
                    'order' => '',
            ],
            'Werkbegeleider' => [
                    'className' => 'Medewerker',
                    'foreignKey' => 'werkbegeleider_id',
                    'conditions' => '',
                    'fields' => '',
                    'order' => '',
            ],
    ];
}
