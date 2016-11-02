<?php

class IzDeelnemersIzIntervisiegroep extends AppModel
{
    public $name = 'IzDeelnemersIzIntervisiegroep';

    public $belongsTo = array(
        'IzDeelnemer' => array(
            'className' => 'IzDeelnemer',
            'foreignKey' => 'iz_deelnemer_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ),
        'IzIntervisiegroep' => array(
            'className' => 'IzIntervisiegroep',
            'foreignKey' => 'iz_intervisiegroep_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ),
    );
}
