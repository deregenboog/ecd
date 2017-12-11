<?php

class IzDeelnemersIzProject extends AppModel
{
    public $name = 'IzDeelnemersIzProject';

    public $belongsTo = [
        'IzDeelnemer' => [
            'className' => 'IzDeelnemer',
            'foreignKey' => 'iz_deelnemer_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ],
        'IzProject' => [
            'className' => 'IzProject',
            'foreignKey' => 'iz_project_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ],
    ];
}
