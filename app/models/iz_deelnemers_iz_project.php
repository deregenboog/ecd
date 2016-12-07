<?php

class IzDeelnemersIzProject extends AppModel
{
    public $name = 'IzDeelnemersIzProject';

    public $belongsTo = array(
        'IzDeelnemer' => array(
            'className' => 'IzDeelnemer',
            'foreignKey' => 'iz_deelnemer_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ),
        'IzProject' => array(
            'className' => 'IzProject',
            'foreignKey' => 'iz_project_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ),
    );
}
