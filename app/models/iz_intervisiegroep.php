<?php

class IzIntervisiegroep extends AppModel
{
    public $name = 'IzIntervisiegroep';
    public $displayField = 'naam';

    public $belongsTo = array(
        'Medewerker' => array(
            'className' => 'Medewerker',
            'foreignKey' => 'medewerker_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ),
    );

    public $validate = array(
        'startdatum' => array(
                    'notempty' => array(
                            'rule' => array(
                                    'notEmpty',
                            ),
                            'message' => 'Voer een startdatum in',
                            'allowEmpty' => false,
                            'required' => true,
                    ),
        ),
        'medewerker_id' => array(
            'notempty' => array(
                'rule' => array(
                    'notEmpty',
                ),
                'message' => 'Voer een medewerker in',
                'allowEmpty' => false,
                'required' => true,
            ),
        ),
    );

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
            $conditions = array(
                'OR' => array(
                    array(
                        'startdatum' => null,
                        'einddatum' => null,
                    ),
                    array(
                        'startdatum <= now()',
                        'einddatum >= now()',
                    ),
                    array(
                        'startdatum <= now()',
                        'einddatum' => null,
                    ),
                ),
            );
        }

        $medewerkers = $this->Medewerker->getMedewerkers(null, null, true);

        $intervisigroepenlists = $this->find('all', array(
                'conditions' => $conditions,
                'fields' => array('id', 'naam', 'medewerker_id'),
                'order' => 'naam',
        ));

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
