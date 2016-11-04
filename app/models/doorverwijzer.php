<?php

class doorverwijzer extends AppModel
{
    public $name = 'Doorverwijzer';
    public $displayField = 'naam';
    //The Associations below have been created with all possible keys, those that are not needed can be removed

    public $hasMany = array(
        'InventarisatiesVerslagen' => array(
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
        ),
    );

    public function &getLists()
    {
        $this->recursive = -1;
        $all = &$this->find('all', array(
            'order' => 'type, naam ASC',
            'fields' => array('type', 'id', 'naam'),
        ));

        $result = array();

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
