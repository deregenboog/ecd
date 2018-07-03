<?php

class IzEindekoppeling extends AppModel
{
    public $name = 'IzEindekoppeling';
    public $displayField = 'naam';

    public $hasMany = [
        'IzKoppeling' => [
            'className' => 'IzKoppeling',
            'foreignKey' => 'iz_eindekoppeling_id',
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

    public $cachekey = 'IzEindKoppelingList';
    public $active_key = 'IzEindKoppelingListactive';

    public function beforeSave($options = [])
    {
        Cache::delete($this->cachekey);
        Cache::delete($this->active_key);

        return true;
    }

    public function eindekoppelingList($all = true)
    {
        $key = $this->cachekey;

        $conditions = [];

        if (empty($all)) {
            $conditions = [
                'active' => true,
            ];
            $key = $this->active_key;
        }

        if (!empty($iz_eindekoppeling_list)) {
            return $iz_eindekoppeling_list;
        }

        $iz_eindekoppeling_list = $this->find('list', [
            'conditions' => $conditions,
            'contain' => [],
        ]);

        $iz_eindekoppeling_list = ['' => ''] + $iz_eindekoppeling_list;

        Cache::write($key, $iz_eindekoppeling_list);

        return $iz_eindekoppeling_list;
    }
}
