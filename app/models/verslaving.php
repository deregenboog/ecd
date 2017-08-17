<?php

class Verslaving extends AppModel
{
    public $name = 'Verslaving';
    public $displayField = 'naam';

    public $hasAndBelongsToMany = [
        'Intake' => [
            'className' => 'Intake',
            'joinTable' => 'intakes_verslavingen',
            'foreignKey' => 'verslaving_id',
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
            'joinTable' => 'awbz_intakes_verslavingen',
            'foreignKey' => 'verslaving_id',
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
