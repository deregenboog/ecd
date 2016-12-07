<?php

class Verslavingsperiode extends AppModel
{
    public $name = 'Verslavingsperiode';
    public $displayField = 'naam';

    public $hasMany = array(
        'Intake' => array(
            'className' => 'Intake',
            'foreignKey' => 'verslavingsperiode_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => '',
        ),
    );
}
