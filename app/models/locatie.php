<?php

class Locatie extends AppModel
{
    public $name = 'Locatie';
    public $displayField = 'naam';
    public $order = 'naam ASC';

    public $hasMany = [
        'Registratie' => [
            'className' => 'Registratie',
            'foreignKey' => 'locatie_id',
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
        'Schorsing' => [
            'className' => 'Schorsing',
            'foreignKey' => 'locatie_id',
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
        'Intake1' => [
            'className' => 'Intake',
            'foreignKey' => 'locatie1_id',
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
        'Intake2' => [
            'className' => 'Intake',
            'foreignKey' => 'locatie2_id',
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

    public function isDayFacility($locationId)
    {
        $this->id = $locationId;

        return !$this->field('nachtopvang');
    }

    public function getDayStart($locationId, $currentTime = null)
    {
        if ($this->isDayFacility($locationId)) {
            $unregisterHour = 2;
        } else {
            $unregisterHour = 14;
        }

        $now = new DateTime($currentTime);

        $unregister = new DateTime($currentTime);
        $unregister->setTime($unregisterHour, 10, 0);

        if ($now < $unregister) {
            $unregister->sub(new DateInterval('P0000-00-01T00:10:00'));
        } else {
            $unregister->sub(new DateInterval('P0000-00-00T00:10:00'));
        }

        return $unregister;
    }

    public function locaties($conditions = [])
    {
        $cachekey = 'Locatie.all';

        foreach ($conditions as $key => $condition) {
            $cachekey .= ".{$key}.{$condition}";
        }

        $locaties = Cache::read($cachekey);
        if (!empty($locaties)) {
            return $locaties;
        }

        $conditions[] = ['OR' => [
            ['datum_tot' => '0000-00-00'],
            ['datum_tot >' => date('Y-m-d')],
        ]];
        $locaties = $this->find('list', ['conditions' => $conditions]);
        Cache::write($cachekey, $locaties);

        return $locaties;
    }
}
