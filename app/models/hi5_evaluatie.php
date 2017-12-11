<?php

class Hi5Evaluatie extends AppModel
{
    public $name = 'Hi5Evaluatie';

    public $actsAs = [
                'Translatable',
    ];

    public $validate = [
      'verslagvan' => ['rule' => 'date',
              'allowEmpty' => false,
           ],
      'verslagtm' => ['rule' => 'date',
              'allowEmpty' => false,
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
            'Medewerker' => [
                    'className' => 'Medewerker',
                    'foreignKey' => 'medewerker_id',
                    'conditions' => '',
                    'fields' => '',
                    'order' => '',
            ],
    ];

    public $hasAndBelongsToMany = [
            'Hi5EvaluatieQuestion' => [
                    'className' => 'Hi5EvaluatieQuestion',
                    'joinTable' => 'hi5_evaluaties_hi5_evaluatie_questions',
                    'foreignKey' => 'hi5_evaluatie_id',
                    'associationForeignKey' => 'hi5_evaluatie_question_id',
                    'unique' => true,
                    'conditions' => '',
                    'fields' => '',
                    'order' => '',
                    'limit' => '',
                    'offset' => '',
                    'finderQuery' => '',
                    'deleteQuery' => '',
                    'insertQuery' => '',
            ],
    ];
}
