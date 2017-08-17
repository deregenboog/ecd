<?php

class Doorverwijzer extends AppModel
{
    public $name = 'Doorverwijzer';
    public $displayField = 'naam';
    //The Associations below have been created with all possible keys, those that are not needed can be removed

    public $hasMany = [
        'InventarisatiesVerslagen' => [
            'className' => 'InventarisatiesVerslagen',
            'foreignKey' => 'doorverwijzer_id',
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

    public function &getLists()
    {
        $this->recursive = -1;
        $all = &$this->find('all', [
            'order' => 'type, naam ASC',
            'fields' => ['type', 'id', 'naam'],
        ]);

        $result = [];

        if (empty($all)) {
            return $result;
        }

        foreach ($all as &$item) {
            $type = &$item['Doorverwijzer']['type'];
            $id = &$item['Doorverwijzer']['id'];
            $result[$type][$id] = $item['Doorverwijzer']['naam'];
        }

        return $result;
    }
}
