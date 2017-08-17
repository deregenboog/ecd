<?php

class Inkomen extends AppModel
{
    public $name = 'Inkomen';
    public $displayField = 'naam';
    //The Associations below have been created with all possible keys, those that are not needed can be removed

    public $hasAndBelongsToMany = [
        'Intake' => [
            'className' => 'Intake',
            'joinTable' => 'inkomens_intakes',
            'foreignKey' => 'inkomen_id',
            'associationForeignKey' => 'intake_id',
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
        'AwbzIntake' => [
            'className' => 'AwbzIntake',
            'joinTable' => 'inkomens_awbz_intakes',
            'foreignKey' => 'inkomen_id',
            'associationForeignKey' => 'awbz_intake_id',
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
