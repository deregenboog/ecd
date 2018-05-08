<?php

class Vrijwilliger extends AppModel
{
    public $name = 'Vrijwilliger';
    public $displayField = 'achternaam';

    public $virtualFields = [
        'name' => "CONCAT_WS(' ', Vrijwilliger.voornaam, Vrijwilliger.tussenvoegsel, Vrijwilliger.achternaam)",
        'name1st_part' => "CONCAT_WS(' ', Vrijwilliger.voornaam, Vrijwilliger.roepnaam)",
        'name2nd_part' => "CONCAT_WS(' ', Vrijwilliger.tussenvoegsel, Vrijwilliger.achternaam)",
        'klant_nummer' => "CONCAT('V', Vrijwilliger.id)",
    ];

    public $watchfields = [
        'voornaam', 'tussenvoegsel', 'achternaam', 'roepnaam',
        'geslacht_id', 'geboortedatum', 'land_id', 'nationaliteit_id',
        'BSN', 'adres', 'postcode', 'plaats', 'email', 'mobiel',
        'telefoon', 'geen_post', 'geen_email', 'overleden',
        'vog_aangevraagd', 'vog_aanwezig', 'overeenkomst_aanwezig',
    ];

    public $actsAs = [
        'Containable',
    ];

    public $paginate = [
        'contain' => ['Geslacht'],
// 		'limit' => 2,
    ];
    public $validate = [
            'achternaam' => [
                    'notempty' => [
                            'rule' => [
                                    'notEmpty',
                            ],
                            'message' => 'Voer een achternaam in',
                            //'allowEmpty' => false,
                            'required' => true,
                            //'last' => false, // Stop validation after this rule
                    ],
            ],
            'land_id' => [
                    'notempty' => [
                            'rule' => [
                                    'notEmpty',
                            ],
                            'message' => 'Kies een land',
                            //'allowEmpty' => false,
                            'required' => true,
                            //'last' => false, // Stop validation after this rule
                    ],
            ],
            'medewerker_id' => [
                    'notempty' => [
                            'rule' => [
                                    'notEmpty',
                            ],
                            'message' => 'Kies een medewerker',
                            //'allowEmpty' => false,
                            'required' => true,
                            //'last' => false, // Stop validation after this rule
                    ],
            ],
            'nationaliteit_id' => [
                    'notempty' => [
                            'rule' => [
                                    'notEmpty',
                            ],
                            'message' => 'Kies een nationaliteit',
                            //'allowEmpty' => false,
                            'required' => true,
                            //'last' => false, // Stop validation after this rule
                    ],
            ],
            'email' => [
                    'email' => [
                            'rule' => [
                                    'email',
                            ],
                            'message' => 'Een geldig E-Mail adres invoeren',
                            'allowEmpty' => true,
                            'required' => false,
                            //'last' => false, // Stop validation after this rule
                    ],
            ],
    ];

    public $belongsTo = [
        'Geslacht' => [
            'className' => 'Geslacht',
            'foreignKey' => 'geslacht_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ],
        'Geboorteland' => [
            'className' => 'Land',
            'foreignKey' => 'land_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ],
        'Nationaliteit' => [
            'className' => 'Nationaliteit',
            'foreignKey' => 'nationaliteit_id',
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
    ];

    public $hasMany = [
        'GroepsactiviteitenVrijwilliger' => [
            'className' => 'GroepsactiviteitenVrijwilliger',
            'foreignKey' => 'vrijwilliger_id',
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
        'GroepsactiviteitenGroepenVrijwilliger' => [
            'className' => 'GroepsactiviteitenGroepenVrijwilliger',
            'foreignKey' => 'vrijwilliger_id',
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
        'GroepsactiviteitenVerslag' => [
            'className' => 'GroepsactiviteitenVerslag',
            'foreignKey' => 'foreign_key',
            'conditions' => [
                'GroepsactiviteitenVerslag.model' => 'Vrijwilliger',
            ],
            'order' => 'GroepsactiviteitenVerslag.created DESC',
            'dependent' => true,
        ],
        'GroepsactiviteitenDocument' => [
            'className' => 'Attachment',
            'foreignKey' => 'foreign_key',
            'conditions' => [
                'GroepsactiviteitenDocument.model' => 'Vrijwilliger',
                'is_active' => 1,
            ],
            'dependent' => true,
            'order' => 'created desc',
        ],
    ];

    public $hasOne = [
        'IzDeelnemer' => [
            'className' => 'IzDeelnemer',
            'foreignKey' => 'foreign_key',
            'conditions' => [
                'IzDeelnemer.model' => 'Vrijwilliger',
            ],
            'dependent' => true,
        ],
        'GroepsactiviteitenIntake' => [
            'className' => 'GroepsactiviteitenIntake',
            'foreignKey' => 'foreign_key',
            'conditions' => [
                'GroepsactiviteitenIntake.model' => 'Vrijwilliger',
            ],
            'dependent' => true,
        ],
    ];

    public function beforeSave($options = [])
    {
        // convert empty strings to null
        if ('' === $this->data['Vrijwilliger']['werkgebied']) {
            $this->data['Vrijwilliger']['werkgebied'] = null;
        }
        if ('' === $this->data['Vrijwilliger']['postcodegebied']) {
            $this->data['Vrijwilliger']['postcodegebied'] = null;
        }

        if (empty($this->id) && empty($this->data['Vrijwilliger']['id'])) {
            $this->send_admin_email = true;
            $this->changes = $this->data;
            if (isset($this->data['Vrijwilliger'])) {
                $this->changes = [];
                foreach ($this->watchfields as $watch) {
                    if (isset($this->data['Vrijwilliger'][$watch])) {
                        $this->changes[$watch] = $this->data['Vrijwilliger'][$watch];
                    }
                }
            }
        } else {
            $current = $this->getById($this->data['Vrijwilliger']['id']);
            $compare = $this->data;
            if (isset($this->data['Vrijwilliger'])) {
                $compare = $this->data['Vrijwilliger'];
            }
            $this->send_admin_email = false;
            $this->changes = [];
            foreach ($this->watchfields as $watch) {
                if (!isset($current[$watch]) || !isset($compare[$watch])) {
                    continue;
                }
                if ($current[$watch] != $compare[$watch]) {
                    $this->send_admin_email = true;
                    $this->changes[$watch] = $compare[$watch];
                }
            }
        }

        return parent::beforeSave($options);
    }

    public function get_selectie($data, $only_email = false)
    {
        $conditions = [];

        if (!empty($data['Groepsactiviteit']['werkgebieden'])) {
            $conditions['Vrijwilliger.werkgebied'] = $data['Groepsactiviteit']['werkgebieden'];
        }

        if (!empty($only_email)) {
            $conditions['email NOT'] = null;
            $conditions['email NOT'] = '';
        }

        $join_conditions = [
                'Vrijwilliger.id = GroepsactiviteitenGroepenVrijwilliger.vrijwilliger_id',
        ];

        $join_table = Inflector::pluralize(Inflector::underscore('GroepsactiviteitenGroepenVrijwilliger'));

        if (!empty($data['Groepsactiviteit']['activiteitengroepen'])) {
            $join_conditions['GroepsactiviteitenGroepenVrijwilliger.groepsactiviteiten_groep_id'] = $data['Groepsactiviteit']['activiteitengroepen'];
        }

        if (!empty($data['Groepsactiviteit']['communicatie_type'])) {
            $or = [];
            if (in_array('communicatie_email', $data['Groepsactiviteit']['communicatie_type'])) {
                $or['GroepsactiviteitenGroepenVrijwilliger.communicatie_email'] = 1;
            }

            if (in_array('communicatie_post', $data['Groepsactiviteit']['communicatie_type'])) {
                $or['GroepsactiviteitenGroepenVrijwilliger.communicatie_post'] = 1;
            }

            if (in_array('communicatie_telefoon', $data['Groepsactiviteit']['communicatie_type'])) {
                $or['GroepsactiviteitenGroepenVrijwilliger.communicatie_telefoon'] = 1;
            }

            $join_conditions['OR'] = $or;
        }

        $join_conditions['OR'] = [
                'GroepsactiviteitenGroepenVrijwilliger.einddatum' => null,
                'GroepsactiviteitenGroepenVrijwilliger.einddatum >=' => date('Y-m-d'),
        ];

        $contain = ['GroepsactiviteitenIntake'];

        $joins = [];

        $joins[] = [
            'table' => $join_table,
            'alias' => 'GroepsactiviteitenGroepenVrijwilliger',
            'type' => 'inner',
            'conditions' => $join_conditions,
        ];

        $options = [
            'conditions' => $conditions,
            'joins' => $joins,
            'contain' => $contain,
            'group' => ['Vrijwilliger.id'],
            'fields' => ['id', 'voornaam', 'tussenvoegsel', 'achternaam', 'roepnaam', 'geslacht_id',
                'geboortedatum', 'land_id', 'nationaliteit_id', 'BSN', 'medewerker_id', 'adres',
                'postcode', 'werkgebied', 'postcodegebied', 'plaats', 'email', 'mobiel', 'telefoon',
                'opmerking', 'geen_post', 'geen_email', 'disabled', 'created', 'modified', 'geen_email', 'name',
                'name1st_part', 'name2nd_part', 'klant_nummer',
                'GroepsactiviteitenIntake.id',
                'GroepsactiviteitenIntake.model',
                'GroepsactiviteitenIntake.foreign_key',
                'GroepsactiviteitenIntake.groepsactiviteiten_afsluiting_id',
                'GroepsactiviteitenIntake.medewerker_id',
                'GroepsactiviteitenIntake.gespreksverslag',
                'GroepsactiviteitenIntake.ondernemen',
                'GroepsactiviteitenIntake.overdag',
                'GroepsactiviteitenIntake.ontmoeten',
                'GroepsactiviteitenIntake.regelzaken',
                'GroepsactiviteitenIntake.informele_zorg',
                'GroepsactiviteitenIntake.dagbesteding',
                'GroepsactiviteitenIntake.inloophuis',
                'GroepsactiviteitenIntake.hulpverlening',
                'GroepsactiviteitenIntake.gezin_met_kinderen',
                'GroepsactiviteitenIntake.intakedatum',
                'GroepsactiviteitenIntake.afsluitdatum',
                'count(*) as count',
                'min(GroepsactiviteitenGroepenVrijwilliger.startdatum) as startdatum',
            ],
        ];

        $personen = $this->find('all', $options);

        return $personen;
    }
}
