<?php

class PfoClient extends AppModel
{
    public $name = 'PfoClient';
    public $displayField = 'achternaam';

    public $actsAs = ['Containable'];

    public $belongsTo = [
        'Geslacht' => [
            'className' => 'Geslacht',
            'foreignKey' => 'geslacht_id',
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

    public $hasOne = [
            'SupportClient' => [
                    'className' => 'PfoClientenSupportgroup',
                    'foreignKey' => 'pfo_supportgroup_client_id	',
                    'dependent' => true,
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

    public $hasMany = [
        'PfoClientenSupportgroup' => [
            'className' => 'PfoClientenSupportgroup',
            'foreignKey' => 'pfo_client_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => '',
        ],
        'PfoClientenVerslag' => [
            'className' => 'PfoClientenVerslag',
            'foreignKey' => 'pfo_client_id',
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
        'Document' => [
                    'className' => 'Attachment',
                    'foreignKey' => 'foreign_key',
                    'conditions' => [
                        'Document.model' => 'PfoClient',
                        'is_active' => 1,
                    ],
                    'dependent' => true,
                    'order' => 'created desc',
        ],
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
                            //'on' => 'create'	// Limit validation to 'create' or 'update' operations
                    ],
            ],
            'telefoon' => [
                    'telefoon' => [
                            //'rule' => array ('custom','/^0[1-9][0-9]+\-[0-9]+$/'),
                            'rule' => ['custom', '/(0)[1-9][0-9]{1,5}[-]?[0-9]{6,7}/'],
                            'message' => 'Voer geldig telefoonnummer in (vb. 020-1111111)',
                            'allowEmpty' => true,
                            'required' => false,
                            //'last' => false, // Stop validation after this rule
                            //'on' => 'create'	// Limit validation to 'create' or 'update' operations
                    ],
            ],
            'telefoon_mobiel' => [
                    'telefoon_mobiel' => [
                            //'rule' => array ('custom','/^06\-[0-9]+$/'),
                            'rule' => ['custom', '/(06)[-]?[0-9]{8}/'],
                            'message' => 'Voer geldig mobiel nummer in (vb. 06-11111111)',
                            'allowEmpty' => true,
                            'required' => false,
                            //'last' => false, // Stop validation after this rule
                            //'on' => 'create'	// Limit validation to 'create' or 'update' operations
                    ],
            ],
            'postcode' => [
                    'postcode' => [
                            'rule' => ['custom', '/^[0-9][0-9][0-9][0-9][A-Z][A-Z]$/'],
                            'message' => 'Voer geldige postcode in (vb. 1000AA)',
                            'allowEmpty' => true,
                            'required' => false,
                            //'last' => false, // Stop validation after this rule
                            //'on' => 'create'	// Limit validation to 'create' or 'update' operations
                    ],
            ],
            'medewerker_id' => [
                    'notempty' => [
                            'rule' => [
                                    'notEmpty',
                            ],
                            'message' => 'Voer een medewerker in',
                            //'allowEmpty' => false,
                            'required' => true,
                            //'last' => false, // Stop validation after this rule
                            //'on' => 'create'	// Limit validation to 'create' or 'update' operations
                    ],
            ],
            'groep' => [
                    'notempty' => [
                            'rule' => [
                                    'notEmpty',
                            ],
                            'message' => 'Voer een groep in',
                            //'allowEmpty' => false,
                            'required' => true,
                            //'last' => false, // Stop validation after this rule
                            //'on' => 'create'	// Limit validation to 'create' or 'update' operations
                    ],
            ],
            'aard_relatie' => [
                    'notempty' => [
                            'rule' => [
                                    'notEmpty',
                            ],
                            'message' => 'Voer een aard relatie in',
                            //'allowEmpty' => false,
                            'required' => true,
                            //'last' => false, // Stop validation after this rule
                            //'on' => 'create'	// Limit validation to 'create' or 'update' operations
                    ],
            ],
            'email' => [
                    'rule' => 'email',
                    'allowEmpty' => true,
                    'message' => 'Vooer een bestaand email adres in',
            ],
    ];

    public function validPhone($data)
    {
        foreach ($data as $key => $value) {
            if (preg_match('/^0[0-9]+\-[0-9]+$/', $value)) {
                return true;
            }
        }

        return false;
    }

    public function read_complete($id)
    {
        $pfoClient = $this->find('first', [
            'conditions' => ['PfoClient.id' => $id],
            'contain' => [
                        'PfoClientenVerslag',
                        'Geslacht',
                        'SupportClient',
                        'PfoClientenSupportgroup',
                        'Document',
            ],
        ]);

        $pfoClient['PfoVerslag'] = [];
        $ids = [];
        if (isset($pfoClient['PfoClientenVerslag'])) {
            foreach ($pfoClient['PfoClientenVerslag'] as $pf) {
                $ids[] = $pf['pfo_verslag_id'];
            }
        }

        $pfoClient['PfoVerslag'] = $this->PfoClientenVerslag->PfoVerslag->find('all', [
                'conditions' => ['PfoVerslag.id' => $ids],
                'contain' => ['PfoClientenVerslag'],
                'order' => ['created DESC'],
        ]);

        $pfoClient['AlsoSupporting'] = [];
        $complete_group = [$id];

        $hoofd_client_id = null;

        if (isset($pfoClient['SupportClient']) && !empty($pfoClient['SupportClient']['pfo_client_id'])) {
            $hoofd_client_id = $pfoClient['SupportClient']['pfo_client_id'];
            $complete_group[] = $hoofd_client_id;

            $conditions = [
                'pfo_client_id' => $hoofd_client_id,
                'pfo_supportgroup_client_id NOT' => $id,
            ];

            $this->PfoClientenSupportgroup->recurcive = -1;
            $support_groep = $this->PfoClientenSupportgroup->find('all', [
                'conditions' => $conditions,
            ]);

            $pfoClient['AlsoSupporting'] = $support_groep;
        }
        if (isset($pfoClient['PfoClientenSupportgroup'])) {
            if (count($pfoClient['PfoClientenSupportgroup']) > 0) {
                $hoofd_client_id = $id;
            }

            foreach ($pfoClient['PfoClientenSupportgroup'] as $pf) {
                $complete_group[] = $pf['pfo_supportgroup_client_id'];
            }
        }

        foreach ($pfoClient['AlsoSupporting'] as $as) {
            $complete_group[] = $as['PfoClientenSupportgroup']['pfo_supportgroup_client_id'];
        }

        sort($complete_group);
        $pfoClient['hoofd_client_id'] = $hoofd_client_id;
        $pfoClient['CompleteGroup'] = array_unique($complete_group);

        if (empty($pfoClient['SupportClient'])) {
            $pfoClient['SupportClient'] = [];
        }

        return $pfoClient;
    }

    public function clienten()
    {
        $conditions = [];

        $clienten_all = $this->find('all', [
            'contain' => [],
            'conditions' => $conditions,
            'fields' => ['id', 'roepnaam', 'tussenvoegsel', 'achternaam'],
        ]);

        $clienten = [];
        foreach ($clienten_all as $client) {
            $clienten[$client['PfoClient']['id']] = $client['PfoClient']['roepnaam'].' '.$client['PfoClient']['tussenvoegsel'].' '.$client['PfoClient']['achternaam'];
            $clienten[$client['PfoClient']['id']] = trim($clienten[$client['PfoClient']['id']]);
        }

        return $clienten;
    }

    public function vrije_clienten($all = null)
    {
        if (!$all) {
            $all = $this->clienten();
        }

        $query = 'select PfoClient.id  as id , s.id as sid from pfo_clienten PfoClient 
				left join pfo_clienten_supportgroups s on PfoClient.id = pfo_client_id or PfoClient.id = pfo_supportgroup_client_id having isnull(s.id)';

        $data = $this->query($query);
        $clienten = [];

        foreach ($data as $client) {
            $clienten[$client['PfoClient']['id']] = $all[$client['PfoClient']['id']];
            $clienten[$client['PfoClient']['id']] = trim($clienten[$client['PfoClient']['id']]);
        }

        return $clienten;
    }

    public function hoofd_clienten($all = null)
    {
        if (!$all) {
            $all = $this->clienten();
        }

        $contain = [
            'PfoClientenSupportgroup' => [
                'fields' => ['id', 'pfo_client_id'],
            ],
        ];

        $data = $this->find('all', [
            'contain' => $contain,
            'fields' => ['id'],
        ]);

        $clienten = [];

        foreach ($data as $client) {
            if (0 == count($client['PfoClientenSupportgroup'])) {
                continue;
            }
            $clienten[$client['PfoClient']['id']] = $all[$client['PfoClient']['id']];
            $clienten[$client['PfoClient']['id']] = trim($clienten[$client['PfoClient']['id']]);
        }

        return $clienten;
    }
}
