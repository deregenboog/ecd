<?php

class Verslavingsfrequentie extends AppModel
{
    public $name = 'Verslavingsfrequentie';
    public $displayField = 'naam';
    public $validate = [
        'datum_van' => [
            'date' => [
                'rule' => ['date'],
                //'message' => 'Your custom message here',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ],
        ],
    ];
    //The Associations below have been created with all possible keys, those that are not needed can be removed

    public $hasMany = [
        'Intake' => [
            'className' => 'Intake',
            'foreignKey' => 'verslavingsfrequentie_id',
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
