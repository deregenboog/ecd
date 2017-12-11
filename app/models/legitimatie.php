<?php

class Legitimatie extends AppModel
{
    public $name = 'Legitimatie';
    public $displayField = 'naam';

    public $hasMany = [
        'Intake' => [
            'className' => 'Intake',
            'foreignKey' => 'legitimatie_id',
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
}
