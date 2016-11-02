<?php

class verslavingsgebruikswijze extends AppModel
{
    public $name = 'Verslavingsgebruikswijze';
    public $displayField = 'naam';

    public $hasAndBelongsToMany = array(
        'Intake' => array(
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
        ),
        'AwbzIntake' => array(
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
        ),
    );
}
