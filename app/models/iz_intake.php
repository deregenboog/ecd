<?php

class IzIntake extends AppModel
{
    public $name = 'IzIntake';
    public $displayField = 'iz_deelnemer_id';

    const DECISION_VALUE_NO = 0;
    const DECISION_VALUE_YES = 1;

    public $actsAs = array('Containable');

    public $belongsTo = array(
        'IzDeelnemer' => array(
            'className' => 'IzDeelnemer',
            'foreignKey' => 'iz_deelnemer_id',
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
    
    public $validate = array(
        'medewerker_id' => array(
            'notempty' => array(
                'rule' => array(
                    'notEmpty',
                ),
                'message' => 'Een medewerker selecteren',
                'allowEmpty' => false,
                'required' => false,
            ),
        ),
    );
}
