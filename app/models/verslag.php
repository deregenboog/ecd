<?php

class Verslag extends AppModel
{
    public $name = 'Verslag';

    public $belongsTo = array(
        'Klant' => array(
            'className' => 'Klant',
            'foreignKey' => 'klant_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ),
        'Medewerker' => array(
            'className' => 'Medewerker',
            'foreignKey' => 'medewerker_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ),
        'Locatie' => array(
            'className' => 'Locatie',
            'foreignKey' => 'locatie_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ),
        'Contactsoort' => array(
            'className' => 'Contactsoort',
            'foreignKey' => 'contactsoort_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ),
    );

    public $hasMany = array(
        'InventarisatiesVerslagen' => array(
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
        ),
    );

    public $actsAs = array('Containable');

    public $contain = array(
        'InventarisatiesVerslagen' => array(
            'fields' => array(),
            'Inventarisatie' => array('fields' => array('titel', 'id')),
            'Doorverwijzer' => array('fields' => array('naam')),
        ),
        'Medewerker' => array('fields' => 'name'),
        'Locatie' => array('fields' => 'naam'),
        'Contactsoort' => array('fields' => array('id', 'text')),
    );

    public $validate = array(
        'contactsoort_id' => array(
            'notempty' => array(
                'rule' => array('minLength', 1),
                'allowEmpty' => false,
                'required' => true,
            ),
        ),
    );

    public function beforeSave($options = array())
    {
        if (!empty($this->data[$this->alias]['aanpassing_verslag'])) {
            if (empty($this->data[$this->alias]['contactsoort_id']) || $this->data[$this->alias]['contactsoort_id'] != 3) {
                unset($this->data[$this->alias]['aanpassing_verslag']);
            }
        }

        return parent::beforeSave($options);
    }
}
