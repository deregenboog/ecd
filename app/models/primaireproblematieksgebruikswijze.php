<?php

class Primaireproblematieksgebruikswijze extends AppModel
{
    public $name = 'Primaireproblematieksgebruikswijze';
    public $useTable = 'verslavingsgebruikswijzen';
    public $displayField = 'naam';

    public $hasAndBelongsToMany = [
        'Intake' => [
            'className' => 'Intake',
            'joinTable' => 'intakes_primaireproblematieksgebruikswijzen',
            'foreignKey' => 'primaireproblematieksgebruikswijze_id',
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
            'joinTable' => 'awbz_intakes_primaireproblematieksgebruikswijzen',
            'foreignKey' => 'primaireproblematieksgebruikswijze_id',
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
