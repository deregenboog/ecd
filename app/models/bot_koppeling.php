<?php

class BotKoppeling extends AppModel
{
    public $name = 'BotKoppeling';

    public $actsAs = [
            'Containable',
    ];

    public $belongsTo = [
        'Medewerker' => [
            'className' => 'Medewerker',
            'foreignKey' => 'medewerker_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ],
        'BackOnTrack' => [
            'className' => 'BackOnTrack',
            'foreignKey' => 'back_on_track_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ],
    ];

    public $validate = [
        'startdatum' => [
            'notempty' => [
                'rule' => [
                    'notEmpty',
                ],
                'message' => 'Voer een startdatum in',
                //'allowEmpty' => false,
                'required' => true,
            ],
        ],
        'medewerker_id' => [
            'notempty' => [
                'rule' => [
                    'notEmpty',
                ],
                'message' => 'Voer een coach in',
                //'allowEmpty' => false,
                'required' => true,
            ],
        ],
        'einddatum' => [
            'rule' => ['validate_einddatum'],
            'message' => 'Datum intake groter dan datum aanmelding',
        ],
    ];

    public function validate_einddatum($check)
    {
        if (empty($this->data['BotKoppeling']['startdatum'])) {
            return true;
        }

        if (empty($this->data['BotKoppeling']['einddatum'])) {
            return true;
        }

        if (strtotime($this->data['BotKoppeling']['einddatum']) < strtotime($this->data['BotKoppeling']['startdatum'])) {
            return false;
        }

        return true;
    }
}
