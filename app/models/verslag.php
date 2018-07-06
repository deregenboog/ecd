<?php

class Verslag extends AppModel
{
    public $name = 'Verslag';

    public $belongsTo = [
        'Klant' => [
            'className' => 'Klant',
            'foreignKey' => 'klant_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ],
        'Medewerker' => [
            'className' => 'Medewerker',
            'foreignKey' => 'medewerker_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ],
        'Locatie' => [
            'className' => 'Locatie',
            'foreignKey' => 'locatie_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ],
        'Contactsoort' => [
            'className' => 'Contactsoort',
            'foreignKey' => 'contactsoort_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ],
    ];

    public $hasMany = [
        'InventarisatiesVerslagen' => [
            'className' => 'InventarisatiesVerslagen',
            'foreignKey' => 'verslag_id',
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

    public $actsAs = ['Containable'];

    public $contain = [
        'InventarisatiesVerslagen' => [
            'fields' => [],
            'Inventarisatie' => ['fields' => ['titel', 'id']],
            'Doorverwijzer' => ['fields' => ['naam']],
        ],
        'Medewerker' => ['fields' => 'name'],
        'Locatie' => ['fields' => 'naam'],
        'Contactsoort' => ['fields' => ['id', 'text']],
    ];

    public $validate = [
        'contactsoort_id' => [
            'notempty' => [
                'rule' => ['minLength', 1],
                'allowEmpty' => false,
                'required' => true,
            ],
        ],
    ];

    public function beforeSave($options = [])
    {
        if (!empty($this->data[$this->alias]['aanpassing_verslag'])) {
            if (empty($this->data[$this->alias]['contactsoort_id']) || 3 != $this->data[$this->alias]['contactsoort_id']) {
                unset($this->data[$this->alias]['aanpassing_verslag']);
            }
        }

        return parent::beforeSave($options);
    }
}
