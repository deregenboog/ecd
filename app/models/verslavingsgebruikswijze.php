<?php

class Verslavingsgebruikswijze extends AppModel
{
    public $name = 'Verslavingsgebruikswijze';
    public $displayField = 'naam';

    public $hasAndBelongsToMany = [
        'Intake' => [
            'className' => 'Intake',
            'joinTable' => 'intakes_verslavingsgebruikswijzen',
            'foreignKey' => 'verslavingsgebruikswijze_id',
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
            'joinTable' => 'awbz_intakes_verslavingsgebruikswijzen',
            'foreignKey' => 'verslavingsgebruikswijze_id',
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
