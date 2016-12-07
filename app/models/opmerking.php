<?php

class Opmerking extends AppModel
{
    public $name = 'Opmerking';

    public $belongsTo = array(
        'Klant' => array(
            'className' => 'Klant',
            'foreignKey' => 'klant_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ),
        'Categorie' => array(
            'className' => 'Categorie',
            'foreignKey' => 'categorie_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ),
    );

    public $validate = array(
        'categorie_id' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Selecteer een categorie',
                'allowEmpty' => false,
                'required' => true,
            ),
        ),
    );

    public $actsAs = array('Containable');

    public $contain = array(
        'Categorie' => array('fields' => array('naam')),
        'Klant' => array('fields' => array('name', 'id')),
    );

    public function countUnSeenOpmerkingen($klant_id)
    {
        $opts = array('conditions' => array('Opmerking.klant_id' => $klant_id));
        $all = $this->find('count', $opts);
        if (!$all) {
            return null;
        }
        $opts['conditions']['Opmerking.gezien'] = '0';
        $unseen = $this->find('count', $opts);

        return $unseen;
    }
}
