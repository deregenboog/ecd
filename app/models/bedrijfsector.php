<?php

class Bedrijfsector extends AppModel
{
    public $name = 'Bedrijfsector';
    public $displayField = 'name';

    public $hasMany = array(
            'Bedrijfitem' => array(
                    'className' => 'Bedrijfitem',
                    'foreignKey' => 'bedrijfsector_id',
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

    public function getNestedSectors()
    {
        $list = $this->find('list');
        $nestedList = [];

        foreach ($list as $key => $value) {
            $nestedList[$key] = $this->Bedrijfitem->find('list', array(
                'conditions' => array(
                    'bedrijfsector_id' => $key,
                ),
            ));
        }

        return $nestedList;
    }
}
