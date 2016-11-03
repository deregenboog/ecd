<?php

class AwbzIntake extends AppModel
{
    public $name = 'AwbzIntake';
    public $order = 'AwbzIntake.datum_intake DESC';
    public $validate = array(
        'locatie1_id' => array(
            'notempty' => array(
                'rule' => 'notempty',
                'message' => 'Dit veld is verplicht',
                'allowEmpty' => false,
                'required' => true,
            ),
        ),
        'locatie2_id' => array(
            'not_equal_to_locatie1' => array(
                'rule' => 'checkLocation',
                'message' => 'De twee gekozen opvanghuizen moeten van elkaar verschillen',
                'allowEmpty' => true,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
    );

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
        'Verblijfstatus' => array(
            'className' => 'Verblijfstatus',
            'foreignKey' => 'verblijfstatus_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ),
        'Legitimatie' => array(
            'className' => 'Legitimatie',
            'foreignKey' => 'legitimatie_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ),
        'PrimaireProblematiek' => array(
            'className' => 'Verslaving',
            'foreignKey' => 'primaireproblematiek_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ),
        'PrimaireProblematieksfrequentie' => array(
            'className' => 'Verslavingsfrequentie',
            'foreignKey' => 'primaireproblematieksfrequentie_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ),
        'PrimaireProblematieksperiode' => array(
            'className' => 'Verslavingsperiode',
            'foreignKey' => 'primaireproblematieksperiode_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ),
        'Verslavingsfrequentie' => array(
            'className' => 'Verslavingsfrequentie',
            'foreignKey' => 'verslavingsfrequentie_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ),
        'Verslavingsperiode' => array(
            'className' => 'Verslavingsperiode',
            'foreignKey' => 'verslavingsperiode_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ),
        'Woonsituatie' => array(
            'className' => 'Woonsituatie',
            'foreignKey' => 'woonsituatie_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ),
        'Locatie1' => array(
            'className' => 'Locatie',
            'foreignKey' => 'locatie1_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ),
        'Locatie2' => array(
            'className' => 'Locatie',
            'foreignKey' => 'locatie2_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ),
        'Infobaliedoelgroep' => array(
            'className' => 'Infobaliedoelgroep',
            'foreignKey' => 'infobaliedoelgroep_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ),

    );

    public $hasAndBelongsToMany = array(
        'Inkomen' => array(
            'className' => 'Inkomen',
            'joinTable' => 'inkomens_awbz_intakes',
            'foreignKey' => 'awbz_intake_id',
            'associationForeignKey' => 'inkomen_id',
            'unique' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'finderQuery' => '',
            'deleteQuery' => '',
            'insertQuery' => '',
        ),
        'Instantie' => array(
            'className' => 'Instantie',
            'joinTable' => 'instanties_awbz_intakes',
            'foreignKey' => 'awbz_intake_id',
            'associationForeignKey' => 'instantie_id',
            'unique' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'finderQuery' => '',
            'deleteQuery' => '',
            'insertQuery' => '',
        ),
        'Verslavingsgebruikswijze' => array(
            'className' => 'Verslavingsgebruikswijze',
            'joinTable' => 'awbz_intakes_verslavingsgebruikswijzen',
            'foreignKey' => 'awbz_intake_id',
            'associationForeignKey' => 'verslavingsgebruikswijze_id',
            'unique' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'finderQuery' => '',
            'deleteQuery' => '',
            'insertQuery' => '',
        ),
        'Primaireproblematieksgebruikswijze' => array(
            'className' => 'Primaireproblematieksgebruikswijze',
            'joinTable' => 'awbz_intakes_primaireproblematieksgebruikswijzen',
            'foreignKey' => 'awbz_intake_id',
            'associationForeignKey' => 'primaireproblematieksgebruikswijze_id',
            'unique' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'finderQuery' => '',
            'deleteQuery' => '',
            'insertQuery' => '',
        ),
        'Verslaving' => array(
            'className' => 'Verslaving',
            'joinTable' => 'awbz_intakes_verslavingen',
            'foreignKey' => 'awbz_intake_id',
            'associationForeignKey' => 'verslaving_id',
            'unique' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'finderQuery' => '',
            'deleteQuery' => '',
            'insertQuery' => '',
        ),
    );

    public $actsAs = array('Containable');

    public $contain = array('Klant' => array(
            'Medewerker' => array(
                'fields' => array('name', 'email'),
            ),
            'Geboorteland' => array('fields' => array('land')),
            'Nationaliteit' => array('fields' => array('naam')),
            'Geslacht' => array('fields' => array('volledig', 'afkorting')),
            'fields' => array('voornaam', 'tussenvoegsel', 'achternaam',
                'roepnaam', 'geboortedatum', 'BSN', 'laatste_TBC_controle',
            ),
        ),
        'Medewerker' => array(
            'fields' => array('name', 'email'),
        ),
        'Verblijfstatus' => array(
            'fields' => array('naam', 'datum_van', 'datum_tot'),
        ),
        'Legitimatie' => array(
            'fields' => array('naam', 'datum_van', 'datum_tot'),
        ),
        'Verslaving' => array(
            'fields' => array('naam'),
        ),
        'Verslavingsfrequentie' => array(
            'fields' => array('naam', 'datum_van', 'datum_tot'),
        ),
        'Verslavingsperiode' => array(
            'fields' => array('naam', 'datum_van', 'datum_tot'),
        ),
        'Woonsituatie' => array(
            'fields' => array('naam', 'datum_van', 'datum_tot'),
        ),
        'Locatie1' => array(
            'fields' => array('naam', 'datum_van', 'datum_tot'),
        ),
        'Locatie2' => array(
            'fields' => array('naam', 'datum_van', 'datum_tot'),
        ),
        'Inkomen' => array('fields' => array('naam')),
        'Instantie' => array('fields' => array('naam')),
        'PrimaireProblematiek' => array(
            'fields' => array('naam'),
        ),
    );

    public function checkLocation()
    {
        if ($this->data['AwbzIntake']['locatie1_id'] != $this->data['AwbzIntake']['locatie2_id']) {
            return true;
        } else {
            return false;
        }
    }

    public function dateNotInFuture($date)
    {
        $datevalue = array_values($date);
        if (strtotime($datevalue[0]) < strtotime('now')) {
            return true;
        } else {
            return false;
        }
    }

    public function getIntakeCountForClant($id)
    {
        return $this->find('count', array(
            'conditions' => array('klant_id' => $id), ));
    }

    public function set_last_intake($klant_id)
    {
        if (empty($klant_id)) {
            return false;
        }

        $this->Klant->recursive = -1;
        if ($this->Klant->read('laste_intake_id', $klant_id)) {
            return false;
        }
        $this->Klant->set('laste_intake_id', $this->id);
        if ($this->Klant->save()) {
            return false;
        }

        return true;
    }

    public function uniqueForUser($data)
    {
        $conditions = array(
            'AwbzIntake.klant_id' => $this->data['AwbzIntake']['klant_id'],
            'AwbzIntake.datum_intake' => $data['datum_intake'],
        );
        if (array_key_exists('id', $this->data['Intake'])) {
            $conditions['AwbzIntake.id !='] = $this->data['AwbzIntake']['id'];
        }
        $check = $this->find('count', array('conditions' => $conditions));
        if ($check > 0) {
            return false;
        } else {
            return true;
        }
    }
}
