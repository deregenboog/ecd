<?php

class IzIntervisiegroep extends AppModel
{
    public $name = 'IzIntervisiegroep';
    public $displayField = 'naam';

    public $belongsTo = [
        'Medewerker' => [
            'className' => 'Medewerker',
            'foreignKey' => 'medewerker_id',
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
                            'allowEmpty' => false,
                            'required' => true,
                    ],
        ],
        'medewerker_id' => [
            'notempty' => [
                'rule' => [
                    'notEmpty',
                ],
                'message' => 'Voer een medewerker in',
                'allowEmpty' => false,
                'required' => true,
            ],
        ],
    ];

    public function beforeSave(&$model)
    {
        Cache::delete($this->getcachekey(false));
        Cache::delete($this->getcachekey(true));

        return true;
    }

    public function getcachekey($all = true)
    {
        $cachekey = 'IzIntervisiegroepenList';

        if ($all) {
            return $cachekey;
        }

        $cachekey .= date('Y-m-d');

        return $cachekey;
    }

    public function intervisiegroepenLists($all = false)
    {
        $cachekey = $this->getcachekey($all);
        $intervisigroepenlists = Cache::read($cachekey);

        if (!empty($intervisigroepenlists)) {
            return $intervisigroepenlists;
        }

        if ($all) {
            $conditions = [];
        } else {
            $conditions = [
                'OR' => [
                    [
                        'startdatum' => null,
                        'einddatum' => null,
                    ],
                    [
                        'startdatum <= now()',
                        'einddatum >= now()',
                    ],
                    [
                        'startdatum <= now()',
                        'einddatum' => null,
                    ],
                ],
            ];
        }

        $medewerkers = $this->Medewerker->getMedewerkers([], [], true);

        $intervisigroepenlists = $this->find('all', [
                'conditions' => $conditions,
                'fields' => ['id', 'naam', 'medewerker_id'],
                'order' => 'naam',
        ]);

        $ig = [];

        foreach ($intervisigroepenlists as $intervisigroepenlist) {
            $n = $intervisigroepenlist['IzIntervisiegroep']['naam'];

            if (!empty($medewerkers[$intervisigroepenlist['IzIntervisiegroep']['medewerker_id']])) {
                $n .= ' ('.$medewerkers[$intervisigroepenlist['IzIntervisiegroep']['medewerker_id']].') ';
            }

            $ig[$intervisigroepenlist['IzIntervisiegroep']['id']] = $n;
        }

        Cache::write($cachekey, $ig);

        return $ig;
    }
}
