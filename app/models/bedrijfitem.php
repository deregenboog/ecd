<?php

class Bedrijfitem extends AppModel
{
    public $name = 'Bedrijfitem';
    public $displayField = 'name';

    public $belongsTo = array(
        'Bedrijfsector' => array(
            'className' => 'Bedrijfsector',
            'foreignKey' => 'bedrijfsector_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ),
    );
}
