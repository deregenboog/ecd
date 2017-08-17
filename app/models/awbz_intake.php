<?php

class AwbzIntake extends AppModel
{
    public $name = 'AwbzIntake';
    public $order = 'AwbzIntake.datum_intake DESC';
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
                'message' => 'De twee gekozen opvanghuizen moeten van elkaar verschillen',
                'allowEmpty' => true,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ],
        ],
        'doelgroep' => [
            'notempty' => [
                'rule' => 'notEmpty',
                'message' => 'Dit veld is verplicht',
                'allowEmpty' => false,
                'required' => true,
            ],
        ],
    ];

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
        'Infobaliedoelgroep' => [
            'className' => 'Infobaliedoelgroep',
            'foreignKey' => 'infobaliedoelgroep_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ],
    ];

    public $hasAndBelongsToMany = [
        'Inkomen' => [
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
        ],
        'Instantie' => [
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
        ],
        'Verslavingsgebruikswijze' => [
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
        ],
        'Primaireproblematieksgebruikswijze' => [
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
        ],
        'Verslaving' => [
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
        ],
    ];

    public $actsAs = ['Containable'];

    public $contain = ['Klant' => [
            'Medewerker' => [
                'fields' => ['name', 'email'],
            ],
            'Geboorteland' => ['fields' => ['land']],
            'Nationaliteit' => ['fields' => ['naam']],
            'Geslacht' => ['fields' => ['volledig', 'afkorting']],
            'fields' => ['voornaam', 'tussenvoegsel', 'achternaam',
                'roepnaam', 'geboortedatum', 'BSN', 'laatste_TBC_controle',
            ],
        ],
        'Medewerker' => [
            'fields' => ['name', 'email'],
        ],
        'Verblijfstatus' => [
            'fields' => ['naam', 'datum_van', 'datum_tot'],
        ],
        'Legitimatie' => [
            'fields' => ['naam', 'datum_van', 'datum_tot'],
        ],
        'Verslaving' => [
            'fields' => ['naam'],
        ],
        'Verslavingsfrequentie' => [
            'fields' => ['naam', 'datum_van', 'datum_tot'],
        ],
        'Verslavingsperiode' => [
            'fields' => ['naam', 'datum_van', 'datum_tot'],
        ],
        'Woonsituatie' => [
            'fields' => ['naam', 'datum_van', 'datum_tot'],
        ],
        'Locatie1' => [
            'fields' => ['naam', 'datum_van', 'datum_tot'],
        ],
        'Locatie2' => [
            'fields' => ['naam', 'datum_van', 'datum_tot'],
        ],
        'Inkomen' => ['fields' => ['naam']],
        'Instantie' => ['fields' => ['naam']],
        'PrimaireProblematiek' => [
            'fields' => ['naam'],
        ],
    ];

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
        return $this->find('count', [
            'conditions' => ['klant_id' => $id], ]);
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
        $conditions = [
            'AwbzIntake.klant_id' => $this->data['AwbzIntake']['klant_id'],
            'AwbzIntake.datum_intake' => $data['datum_intake'],
        ];
        if (array_key_exists('id', $this->data['Intake'])) {
            $conditions['AwbzIntake.id !='] = $this->data['AwbzIntake']['id'];
        }
        $check = $this->find('count', ['conditions' => $conditions]);
        if ($check > 0) {
            return false;
        } else {
            return true;
        }
    }
}
