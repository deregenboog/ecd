<?php

class Intake extends AppModel
{
    public $name = 'Intake';

    public $order = 'datum_intake DESC';

    public $validate = [
       'klant_id' => [
            'notempty' => [
                'rule' => 'notEmpty',
                'message' => 'Dit veld is verplicht',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ],
        ],
        'medewerker_id' => [
            'notempty' => [
                'rule' => 'notEmpty',
                'message' => 'Dit veld is verplicht',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ],
        ],
        'datum_intake' => [
            'date' => [
                'rule' => 'notEmpty',
                'message' => 'Dit veld is verplicht',
                //'allowEmpty' => false,
                //'required' => true,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ],
            'date' => [
                'rule' => ['date'],
                'message' => 'Dit is geen geldige datum',
                'allowEmpty' => false,
                'required' => true,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ],
            'date_not_in_future' => [
                'rule' => 'dateNotInFuture',
                'message' => 'De datum van dit veld mag niet in de toekomst liggen',
            ],
            'date_unique' => [
                'rule' => ['uniqueForUser'],
                'message' => 'Klant heeft al een intake op deze datum.',
            ],
        ],
        'verblijfstatus_id' => [
            'notempty' => [
                'rule' => 'notEmpty',
                'message' => 'Dit veld is verplicht',
                //'allowEmpty' => false,
                'required' => true,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ],
        ],
        'Inkomen' => [
            'multiple' => [
                'message' => 'Kies minimaal een optie',
                 'rule' => ['multiple', ['min' => 1]],
                //'allowEmpty' => false,
                'required' => true,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ],
        ],
        'verblijf_in_amsterdam_sinds' => [
            'date' => [
                'rule' => ['date'],
                'message' => 'Dit is geen geldige datum',
                // 'allowEmpty' => true,
                'required' => true,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
                ],
            'date_not_in_future' => [
                'rule' => 'dateNotInFuture',
                'message' => 'De datum van dit veld mag niet in de toekomst liggen',
            ],
        ],
        'woonsituatie_id' => [
            'notempty' => [
                'rule' => 'notEmpty',
                'message' => 'Dit veld is verplicht',
                //'allowEmpty' => false,
                'required' => true,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ],
        ],
        'verwachting_dienstaanbod' => [
            'notempty' => [
                'rule' => 'notEmpty',
                'message' => 'Dit veld is verplicht',
                'allowEmpty' => false,
                'required' => true,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ],
        ],
        'toekomstplannen' => [
            'notempty' => [
                'rule' => 'notEmpty',
                'message' => 'Dit veld is verplicht',
                'allowEmpty' => false,
                'required' => true,
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
        'Locatie3' => [
            'className' => 'Locatie',
            'foreignKey' => 'locatie3_id',
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
            'joinTable' => 'inkomens_intakes',
            'foreignKey' => 'intake_id',
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
            'joinTable' => 'instanties_intakes',
            'foreignKey' => 'intake_id',
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
            'joinTable' => 'intakes_verslavingsgebruikswijzen',
            'foreignKey' => 'intake_id',
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
            'joinTable' => 'intakes_primaireproblematieksgebruikswijzen',
            'foreignKey' => 'intake_id',
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
            'joinTable' => 'intakes_verslavingen',
            'foreignKey' => 'intake_id',
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

    public function beforeValidate()
    {
        foreach ($this->hasAndBelongsToMany as $k => $v) {
            if (isset($this->data[$k][$k])) {
                $this->data[$this->alias][$k] = $this->data[$k][$k];
            }
        }

        return true;
    }

    public function validate()
    {
        debug($this->validationErrors);
        die;
    }

    public function checkLocation()
    {
        $l1 = $this->data['Intake']['locatie1_id'];
        $l2 = $this->data['Intake']['locatie2_id'];
        $l3 = $this->data['Intake']['locatie3_id'];
        if ($l1 != $l2 && $l2 != $l3 && $l3 != $l1) {
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

    public function afterSave($created)
    {
        parent::afterSave($created);

        $k_id = $this->data['Intake']['klant_id'];
        $klant = $this->Klant->read(null, $k_id);

        $last_intake_id = $klant['Intake'][0]['id'];
        $this->Klant->saveField('laste_intake_id', $last_intake_id);
        if (isset($this->data['Intake']['datum_intake']) && !empty($this->data['Intake']['datum_intake'])) {
            if (empty($klant['Klant']['first_intake_date'])) {
                $this->Klant->saveField('first_intake_date', $this->data['Intake']['datum_intake']);
            } else {
                $f_date = strtotime($klant['Klant']['first_intake_date']);
                $n_date = strtotime($this->data['Intake']['datum_intake']);
                if ($n_date < $f_date) {
                    $this->Klant->saveField('first_intake_date', $this->data['Intake']['datum_intake']);
                }
            }
        }
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
            'Intake.klant_id' => $this->data['Intake']['klant_id'],
            'Intake.datum_intake' => $data['datum_intake'],
        ];
        if (array_key_exists('id', $this->data['Intake'])) {
            $conditions['Intake.id !='] = $this->data['Intake']['id'];
        }
        $check = $this->find('count', ['conditions' => $conditions]);
        if ($check > 0) {
            return false;
        } else {
            return true;
        }
    }

    public function completeKlantenIntakesWithLocationNames($klanten)
    {
        foreach ($klanten as $key => $k) {
            if (!isset($k[$this->alias])) {
                continue;
            }

            for ($li = 1; $li < 4; ++$li) {
                $relation = 'Locatie'.$li;
                $relInfo = $this->belongsTo[$relation];
                $field = $relInfo['foreignKey'];
                if (empty($k[$this->alias][$field])) {
                    continue;
                }
                $relId = $k[$this->alias][$field];
                $rel = $this->$relation->getById($relId);
                $klanten[$key][$this->alias][$relation] = $rel;
            }
        }

        return $klanten;
    }
}
