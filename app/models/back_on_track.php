<?php

class BackOnTrack extends AppModel
{
    public $name = 'BackOnTrack';

    public $actsAs = array(
            'Containable',
    );

    public $belongsTo = array(
        'Klant' => array(
            'className' => 'Klant',
            'foreignKey' => 'klant_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ),
    );

    public $hasMany = array(
        'BotKoppeling' => array(
            'className' => 'BotKoppeling',
            'foreignKey' => 'back_on_track_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => 'startdatum ASC, einddatum ASC',
        ),
    );

    public $validate = array(
            'startdatum' => array(
                    'notempty' => array(
                            'rule' => array(
                                    'notEmpty',
                            ),
                            'message' => 'Voer een startdatum in',
                            //'allowEmpty' => false,
                            'required' => true,
                    ),
            ),
            'intakedatum' => array(
                    'rule' => array('validate_intakedatum'),
                    'message' => 'Datum intake groter dan datum aanmelding',
            ),
            'einddatum' => array(
                    'rule' => array('validate_einddatum'),
                    'message' => 'Datum intake groter dan datum aanmelding',
            ),
    );

    public function validate_intakedatum($check)
    {
        if (empty($this->data['BackOnTrack']['startdatum'])) {
            return true;
        }

        if (empty($this->data['BackOnTrack']['intakedatum'])) {
            return true;
        }

        if (strtotime($this->data['BackOnTrack']['intakedatum']) < strtotime($this->data['BackOnTrack']['startdatum'])) {
            return false;
        }

        return true;
    }

    public function validate_einddatum($check, $compare)
    {
        if (empty($this->data['BackOnTrack']['intakedatum'])) {
            return true;
        }

        if (empty($this->data['BackOnTrack']['einddatum'])) {
            return true;
        }

        if (strtotime($this->data['BackOnTrack']['einddatum']) < strtotime($this->data['BackOnTrack']['intakedatum'])) {
            return false;
        }

        return true;
    }
}
