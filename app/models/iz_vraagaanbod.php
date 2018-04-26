<?php

class IzVraagaanbod extends AppModel
{
    public $name = 'IzVraagaanbod';
    public $displayField = 'naam';

    public $hasMany = [
        'IzKoppeling' => [
            'className' => 'IzKoppeling',
            'foreignKey' => 'iz_vraagaanbod_id',
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
    public $cachekey = 'IzVraagaanbodList';

    public function beforeSave($options = [])
    {
        Cache::delete($this->cachekey);

        return true;
    }

    public function vraagaanbodList()
    {
        $iz_eindekoppeling_list = Cache::read($this->cachekey);

        if (!empty($iz_vraag_aanbod_list)) {
            return $iz_vraag_aanbod_list;
        }
        $iz_vraag_aanbod_list = $this->find('list');
        $iz_vraag_aanbod_list = ['' => ''] + $iz_vraag_aanbod_list;
        Cache::write($this->cachekey, $iz_vraag_aanbod_list);

        return $iz_vraag_aanbod_list;
    }
}
