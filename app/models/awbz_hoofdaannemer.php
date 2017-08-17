<?php

class AwbzHoofdaannemer extends AppModel
{
    public $name = 'AwbzHoofdaannemer';
    public $actsAs = ['FixDates'];

    public $validate = [
        'begindatum' => [
            'date' => [
                'rule' => ['date'],
                'message' => 'Datum',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ],
            'notempty' => [
                'rule' => 'notEmpty',
                'message' => 'Dit veld is verplicht',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ],
        ],
        'hoofdaannemer_id' => [
            'notempty' => [
                'rule' => 'notEmpty',
                'message' => 'Dit veld is verplicht',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ],
        ],
    ];

    public $belongsTo = [
        'Klant' => [
            'className' => 'Klant',
            'foreignKey' => 'klant_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ],
        'Hoofdaannemer' => [
            'className' => 'Hoofdaannemer',
            'foreignKey' => 'hoofdaannemer_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ],
    ];
}
