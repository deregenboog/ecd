<?php

class Categorie extends AppModel
{
    public $name = 'Categorie';
    public $displayField = 'naam';

    public $hasMany = [
        'Opmerking' => [
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
        ],
    ];
}
