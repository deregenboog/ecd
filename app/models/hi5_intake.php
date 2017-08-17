<?php

class Hi5Intake extends AppModel
{
    public $name = 'Hi5Intake';
    public $useTable = 'hi5_intakes';

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
            'Verblijfstatus' => [
                    'className' => 'Verblijfstatus',
                    'foreignKey' => 'verblijfstatus_id',
                    'conditions' => '',
                    'fields' => '',
                    'order' => '',
            ],
            'Legitimatie' => [
                    'className' => 'Legitimatie',
                    'foreignKey' => 'legitimatie_id',
                    'conditions' => '',
                    'fields' => '',
                    'order' => '',
            ],
            'PrimaireProblematiek' => [
                    'className' => 'Verslaving',
                    'foreignKey' => 'primaireproblematiek_id',
                    'conditions' => '',
                    'fields' => '',
                    'order' => '',
            ],
            'PrimaireProblematieksfrequentie' => [
                    'className' => 'Verslavingsfrequentie',
                    'foreignKey' => 'primaireproblematieksfrequentie_id',
                    'conditions' => '',
                    'fields' => '',
                    'order' => '',
            ],
            'PrimaireProblematieksperiode' => [
                    'className' => 'Verslavingsperiode',
                    'foreignKey' => 'primaireproblematieksperiode_id',
                    'conditions' => '',
                    'fields' => '',
                    'order' => '',
            ],
            'Verslavingsfrequentie' => [
                    'className' => 'Verslavingsfrequentie',
                    'foreignKey' => 'verslavingsfrequentie_id',
                    'conditions' => '',
                    'fields' => '',
                    'order' => '',
            ],
            'Verslavingsperiode' => [
                    'className' => 'Verslavingsperiode',
                    'foreignKey' => 'verslavingsperiode_id',
                    'conditions' => '',
                    'fields' => '',
                    'order' => '',
            ],
            'Woonsituatie' => [
                    'className' => 'Woonsituatie',
                    'foreignKey' => 'woonsituatie_id',
                    'conditions' => '',
                    'fields' => '',
                    'order' => '',
            ],
            'Locatie1' => [
                    'className' => 'Locatie',
                    'foreignKey' => 'locatie1_id',
                    'conditions' => '',
                    'fields' => '',
                    'order' => '',
            ],
            'Locatie2' => [
                    'className' => 'Locatie',
                    'foreignKey' => 'locatie2_id',
                    'conditions' => '',
                    'fields' => '',
                    'order' => '',
            ],
            'Locatie3' => [
                    'className' => 'Locatie',
                    'foreignKey' => 'locatie3_id',
                    'conditions' => '',
                    'fields' => '',
                    'order' => '',
            ],
            'Werklocatie' => [
                    'className' => 'Locatie',
                    'foreignKey' => 'werklocatie_id',
                    'conditions' => '',
                    'fields' => '',
                    'order' => '',
            ],
            'Bedrijfitem1' => [
                    'className' => 'Bedrijfitem',
                    'foreignKey' => 'bedrijfitem_1_id',
                    'conditions' => '',
                    'fields' => '',
                    'order' => '',
            ],
            'Bedrijfitem2' => [
                    'className' => 'Bedrijfitem',
                    'foreignKey' => 'bedrijfitem_2_id',
                    'conditions' => '',
                    'fields' => '',
                    'order' => '',
            ],
    ];

    public $hasAndBelongsToMany = [
            'Verslavingsgebruikswijze' => [
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
            ],
            'Primaireproblematieksgebruikswijze' => [
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
            ],
            'Verslaving' => [
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
            ],
            'Inkomen' => [
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
            ],
            'Instantie' => [
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
            ],
            'Hi5Answer' => [
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
            ],
    ];

    public $actsAs = [
            'Containable',
    ];

    public $validate = [
        'locatie1_id' => [
            'notempty' => [
                'rule' => 'notempty',
                'message' => 'Dit veld is verplicht',
                'allowEmpty' => false,
                'required' => true,
            ],
        ],
        'locatie2_id' => [
                'not_equal_to_locatie1' => [
                    'rule' => 'checkLocation',
                    'message' => 'De drie gekozen opvanghuizen moeten van elkaar verschillen',
                    'allowEmpty' => true,
                    ],
                ],
        'locatie3_id' => [
                'not_equal_to_locatie1' => [
                    'rule' => 'checkLocation',
                    'message' => 'De drie gekozen opvanghuizen moeten van elkaar verschillen',
                    'allowEmpty' => true,
                    ],
                ],
    ];

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
