<?php

class Opmerking extends AppModel
{
    public $name = 'Opmerking';

    public $belongsTo = [
        'Klant' => [
            'className' => 'Klant',
            'foreignKey' => 'klant_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ],
        'Categorie' => [
            'className' => 'Categorie',
            'foreignKey' => 'categorie_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ],
    ];

    public $validate = [
        'categorie_id' => [
            'notempty' => [
                'rule' => 'notEmpty',
                'message' => 'Selecteer een categorie',
                'allowEmpty' => false,
                'required' => true,
            ],
        ],
    ];

    public $actsAs = ['Containable'];

    public $contain = [
        'Categorie' => ['fields' => ['naam']],
        'Klant' => ['fields' => ['name', 'id']],
    ];

    public function countUnSeenOpmerkingen($klant_id)
    {
        $opts = ['conditions' => ['Opmerking.klant_id' => $klant_id]];
        $all = $this->find('count', $opts);
        if (!$all) {
            return null;
        }
        $opts['conditions']['Opmerking.gezien'] = '0';
        $unseen = $this->find('count', $opts);

        return $unseen;
    }
}
