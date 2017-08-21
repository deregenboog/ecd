<?php

class BotVerslag extends AppModel
{
    public $name = 'BotVerslag';

    public $belongsTo = [
        'Klant' => [
            'className' => 'Klant',
            'foreignKey' => 'klant_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ],
        'Medewerker' => [
            'className' => 'Medewerker',
            'foreignKey' => 'medewerker_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ],
    ];
    public $actsAs = ['Containable'];

    public $contact_type = [
        'In persoon' => 'In persoon',
        'Telefonisch' => 'Telefonisch',
        'E-Mail' => 'E-Mail',
    ];

    public function getContactTypes()
    {
        return $this->contact_type;
    }
}
