<?php

class categorie extends AppModel
{
    public $name = 'Categorie';
    public $displayField = 'naam';

    public $hasMany = array(
        'Opmerking' => array(
            'className' => 'Opmerking',
            'foreignKey' => 'categorie_id',
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
