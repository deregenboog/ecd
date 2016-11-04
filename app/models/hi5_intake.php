<?php

class Hi5Intake extends AppModel
{
    public $name = 'Hi5Intake';
    public $useTable = 'hi5_intakes';

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
            'Locatie3' => array(
                    'className' => 'Locatie',
                    'foreignKey' => 'locatie3_id',
                    'conditions' => '',
                    'fields' => '',
                    'order' => '',
            ),
            'Werklocatie' => array(
                    'className' => 'Locatie',
                    'foreignKey' => 'werklocatie_id',
                    'conditions' => '',
                    'fields' => '',
                    'order' => '',
            ),
            'Bedrijfitem1' => array(
                    'className' => 'Bedrijfitem',
                    'foreignKey' => 'bedrijfitem_1_id',
                    'conditions' => '',
                    'fields' => '',
                    'order' => '',
            ),
            'Bedrijfitem2' => array(
                    'className' => 'Bedrijfitem',
                    'foreignKey' => 'bedrijfitem_2_id',
                    'conditions' => '',
                    'fields' => '',
                    'order' => '',
            ),
    );

    public $hasAndBelongsToMany = array(
            'Verslavingsgebruikswijze' => array(
                    'className' => 'Verslavingsgebruikswijze',
                    'joinTable' => 'hi5_intakes_verslavingsgebruikswijzen',
                    'foreignKey' => 'hi5_intake_id',
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
                    'joinTable' => 'hi5_intakes_primaireproblematieksgebruikswijzen',
                    'foreignKey' => 'hi5_intake_id',
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
                    'joinTable' => 'hi5_intakes_verslavingen',
                    'foreignKey' => 'hi5_intake_id',
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
            'Inkomen' => array(
                    'className' => 'Inkomen',
                    'joinTable' => 'hi5_intakes_inkomens',
                    'foreignKey' => 'hi5_intake_id',
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
                    'joinTable' => 'hi5_intakes_instanties',
                    'foreignKey' => 'hi5_intake_id',
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
            'Hi5Answer' => array(
                    'className' => 'Hi5Answer',
                    'joinTable' => 'hi5_intakes_answers',
                    'foreignKey' => 'hi5_intake_id',
                    'associationForeignKey' => 'hi5_answer_id',
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

    public $actsAs = array(
            'Containable',
    );

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
                    'message' => 'De drie gekozen opvanghuizen moeten van elkaar verschillen',
                    'allowEmpty' => true,
                    ),
                ),
        'locatie3_id' => array(
                'not_equal_to_locatie1' => array(
                    'rule' => 'checkLocation',
                    'message' => 'De drie gekozen opvanghuizen moeten van elkaar verschillen',
                    'allowEmpty' => true,
                    ),

                ),
    );

    public function checkLocation()
    {
        $l1 = $this->data['Hi5Intake']['locatie1_id'];
        $l2 = $this->data['Hi5Intake']['locatie2_id'];
        $l3 = $this->data['Hi5Intake']['locatie3_id'];

        if ($l1 != $l2 && $l2 != $l3 && $l3 != $l1) {
            return true;
        } else {
            return false;
        }
    }
}
