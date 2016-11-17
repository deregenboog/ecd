<?php

class Traject extends AppModel
{
    public $name = 'Traject';
    public $actsAs = array('Containable');
    //The Associations below have been created with all possible keys, those that are not needed can be removed

    public $belongsTo = array(
            'Klant' => array(
                    'className' => 'Klant',
                    'foreignKey' => 'klant_id',
                    'conditions' => '',
                    'fields' => '',
                    'order' => '',
            ),
            'Trajectbegeleider' => array(
                    'className' => 'Medewerker',
                    'foreignKey' => 'trajectbegeleider_id',
                    'conditions' => '',
                    'fields' => '',
                    'order' => '',
            ),
            'Werkbegeleider' => array(
                    'className' => 'Medewerker',
                    'foreignKey' => 'werkbegeleider_id',
                    'conditions' => '',
                    'fields' => '',
                    'order' => '',
            ),
    );
}
