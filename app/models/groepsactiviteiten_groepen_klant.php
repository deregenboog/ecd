<?php

class GroepsactiviteitenGroepenKlant extends AppModel
{
    public $name = 'GroepsactiviteitenGroepenKlant';
    public $displayField = 'groepsactiviteiten_groep_id';

    public $actsAs = array('Containable');

    public $validate = array(
            'startdatum' => array(
                    'notempty' => array(
                            'rule' => array(
                                    'notEmpty',
                            ),
                            'message' => 'Voer een startdatum in',
                            'allowEmpty' => false,
                            'required' => false,
                    ),
            ),
            'einddatum' => array(
                    'notempty' => array(
                            'rule' => array(
                                    'notEmpty',
                            ),
                            'message' => 'Voer een einddatum in',
                            'allowEmpty' => false,
                            'required' => false,
                    ),
                    'datecompare' => array(
                            'rule' => array(
                                    'compareDates',
                            ),
                            'message' => 'Einddatum moet later dan startdatum zijn',
                    ),
            ),
            'groepsactiviteiten_reden_id' => array(
                    'notempty' => array(
                            'rule' => array(
                                    'notEmpty',
                            ),
                            'message' => 'Voer een groepsactiviteiten_reden_id in',
                            'allowEmpty' => false,
                            'required' => false,
                    ),
            ),

    );

    public function compareDates()
    {
        if (empty($this->data['GroepsactiviteitenGroepenKlant']['einddatum'])) {
            return true;
        }
        if (empty($this->data['GroepsactiviteitenGroepenKlant']['startdatum'])) {
            return true;
        }
        $s = strtotime($this->data['GroepsactiviteitenGroepenKlant']['startdatum']);
        $e = strtotime($this->data['GroepsactiviteitenGroepenKlant']['einddatum']);

        if ($e < $s) {
            return false;
        }

        return true;
    }

    public $belongsTo = array(
        'GroepsactiviteitenGroep' => array(
            'className' => 'GroepsactiviteitenGroep',
            'foreignKey' => 'groepsactiviteiten_groep_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ),
        'Klant' => array(
            'className' => 'Klant',
            'foreignKey' => 'klant_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ),
        'GroepsactiviteitenReden' => array(
            'className' => 'GroepsactiviteitenReden',
            'foreignKey' => 'groepsactiviteiten_reden_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ),
    );

    public function Add2Group($klant_id, $groepsactiviteiten_groep_id)
    {
        $conditions = array(
            'klant_id' => $klant_id,
            'groepsactiviteiten_groep_id' => $groepsactiviteiten_groep_id,
        );
        $gag = $this->find('first', array(
            'conditions' => $conditions,
            'fields' => array('id'),
        ));

        if (empty($gag)) {
            $data = array(
                'groepsactiviteiten_groep_id' => $groepsactiviteiten_groep_id,
                'klant_id' => $klant_id,
                'groepsactiviteiten_reden_id' => null,
                'startdatum' => date('Y-m-d'),
                'einddatum' => null,
                'communicatie_email' => 1,
                'communicatie_telefoon' => 1,
                'communicatie_post' => 1,
            );
            unset($this->validate['einddatum']);
            unset($this->validate['groepsactiviteiten_reden_id']);
            $this->create();
            $retval = $this->save($data);

            return $retval;
        }

        return true;
    }
}
