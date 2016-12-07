<?php

class GroepsactiviteitenVerslag extends AppModel
{
    public $name = 'GroepsactiviteitenVerslag';
    public $displayField = 'foreign_key';
    //The Associations below have been created with all possible keys, those that are not needed can be removed

    public $belongsTo = array(
        'Medewerker' => array(
            'className' => 'Medewerker',
            'foreignKey' => 'medewerker_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ),
    );
}
