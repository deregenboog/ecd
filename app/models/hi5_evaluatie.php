<?php

class Hi5Evaluatie extends AppModel
{
    public $name = 'Hi5Evaluatie';

    public $actsAs = array(
                'Translatable',
    );

    public $validate = array(
      'verslagvan' => array( 'rule' => 'date',
              'allowEmpty' => false,
           ),
      'verslagtm' => array( 'rule' => 'date',
              'allowEmpty' => false,
           ),
    );

    public $belongsTo = array(
            'Klant' => array(
                    'className' => 'Klant',
                    'foreignKey' => 'klant_id',
                    'conditions' => '',
                    'fields' => '',
                    'order' => '',
            ),
            'Medewerker' => array(
                    'className' => 'Medewerker',
                    'foreignKey' => 'medewerker_id',
                    'conditions' => '',
                    'fields' => '',
                    'order' => '',
            ),
    );

    public $hasAndBelongsToMany = array(
            'Hi5EvaluatieQuestion' => array(
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
            ),
    );
}
