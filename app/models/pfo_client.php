<?php

class PfoClient extends AppModel
{
	public $name = 'PfoClient';
	public $displayField = 'achternaam';

	public $actsAs = array('Containable');

	public $belongsTo = array(
		'Geslacht' => array(
			'className' => 'Geslacht',
			'foreignKey' => 'geslacht_id',
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
	);

	public $hasOne = array(
			'SupportClient' => array(
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
			),
	);

	public $hasMany = array(
		'PfoClientenSupportgroup' => array(
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
		),
		'PfoClientenVerslag' => array(
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
		),
		'Document' => array(
					'className' => 'Attachment',
					'foreignKey' => 'foreign_key',
					'conditions' => array(
						'Document.model' => 'PfoClient',
						'is_active' => 1,
					),
					'dependent' => true,
					'order' => 'created desc',
		),
	);

	public $validate = array(
			'achternaam' => array(
					'notempty' => array(
							'rule' => array(
									'notEmpty',
							),
							'message' => 'Voer een achternaam in',
							//'allowEmpty' => false,
							'required' => true,
							//'last' => false, // Stop validation after this rule
							//'on' => 'create'	// Limit validation to 'create' or 'update' operations
					),
			),
			'telefoon' => array(
					'telefoon' => array(
							//'rule' => array ('custom','/^0[1-9][0-9]+\-[0-9]+$/'),
							'rule' => array('custom', '/(0)[1-9][0-9]{1,5}[-]?[0-9]{6,7}/'),
							'message' => 'Voer geldig telefoonnummer in (vb. 020-1111111)',
							'allowEmpty' => true,
							'required' => false,
							//'last' => false, // Stop validation after this rule
							//'on' => 'create'	// Limit validation to 'create' or 'update' operations
					),
			),
			'telefoon_mobiel' => array(
					'telefoon_mobiel' => array(
							//'rule' => array ('custom','/^06\-[0-9]+$/'),
							'rule' => array('custom', '/(06)[-]?[0-9]{8}/'),
							'message' => 'Voer geldig mobiel nummer in (vb. 06-11111111)',
							'allowEmpty' => true,
							'required' => false,
							//'last' => false, // Stop validation after this rule
							//'on' => 'create'	// Limit validation to 'create' or 'update' operations
					),
			),
			'postcode' => array(
					'postcode' => array(
							'rule' => array('custom', '/^[0-9][0-9][0-9][0-9][A-Z][A-Z]$/'),
							'message' => 'Voer geldige postcode in (vb. 1000AA)',
							'allowEmpty' => true,
							'required' => false,
							//'last' => false, // Stop validation after this rule
							//'on' => 'create'	// Limit validation to 'create' or 'update' operations
					),
			),
			'medewerker_id' => array(
					'notempty' => array(
							'rule' => array(
									'notEmpty',
							),
							'message' => 'Voer een medewerker in',
							//'allowEmpty' => false,
							'required' => true,
							//'last' => false, // Stop validation after this rule
							//'on' => 'create'	// Limit validation to 'create' or 'update' operations
					),
			),
			'groep' => array(
					'notempty' => array(
							'rule' => array(
									'notEmpty',
							),
							'message' => 'Voer een groep in',
							//'allowEmpty' => false,
							'required' => true,
							//'last' => false, // Stop validation after this rule
							//'on' => 'create'	// Limit validation to 'create' or 'update' operations
					),
			),
			'aard_relatie' => array(
					'notempty' => array(
							'rule' => array(
									'notEmpty',
							),
							'message' => 'Voer een aard relatie in',
							//'allowEmpty' => false,
							'required' => true,
							//'last' => false, // Stop validation after this rule
							//'on' => 'create'	// Limit validation to 'create' or 'update' operations
					),
			),
			'email' => array(
					'rule' => 'email',
					'allowEmpty' => true,
					'message' => 'Vooer een bestaand email adres in',
			),

	);
	
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
		$pfoClient = $this->find('first', array(
			'conditions' => array('PfoClient.id' => $id),
			'contain' => array(
						'PfoClientenVerslag',
						'Geslacht',
						'SupportClient',
						'PfoClientenSupportgroup',
						'Document',

			),
		));

		$pfoClient['PfoVerslag'] = array();
		$ids=array();
		if (isset($pfoClient['PfoClientenVerslag'])) {
			foreach ($pfoClient['PfoClientenVerslag'] as $pf) {
				$ids[]=$pf['pfo_verslag_id'];
			}
		}

		$pfoClient['PfoVerslag']= $this->PfoClientenVerslag->PfoVerslag->find('all', array(
				'conditions' => array('PfoVerslag.id' => $ids),
				'contain' => array('PfoClientenVerslag'),
				'order' => array('created DESC'),
		));

		$pfoClient['AlsoSupporting'] = array();
		$complete_group = array($id);

		$hoofd_client_id = null;

		if (isset($pfoClient['SupportClient']) && !empty($pfoClient['SupportClient']['pfo_client_id'])) {
			
			$hoofd_client_id =$pfoClient['SupportClient']['pfo_client_id'];
			$complete_group[]=$hoofd_client_id;
			
			$conditions = array(
				'pfo_client_id' => $hoofd_client_id,
				'pfo_supportgroup_client_id NOT' => $id,
			);
			
			$this->PfoClientenSupportgroup->recurcive = -1;
			$support_groep = $this->PfoClientenSupportgroup->find('all', array(
				'conditions' => $conditions,
			));

			$pfoClient['AlsoSupporting'] = $support_groep;
		}
		if (isset($pfoClient['PfoClientenSupportgroup'])) {
			
			if (count($pfoClient['PfoClientenSupportgroup']) > 0) {
				$hoofd_client_id = $id;
			}
			
			foreach ($pfoClient['PfoClientenSupportgroup'] as $pf) {
				$complete_group[]=$pf['pfo_supportgroup_client_id'];
			}
			
		}
		
		foreach ($pfoClient['AlsoSupporting'] as $as) {
			
			$complete_group[]=$as['PfoClientenSupportgroup']['pfo_supportgroup_client_id'];
			
		}
		
		sort($complete_group);
		$pfoClient['hoofd_client_id'] = $hoofd_client_id;
		$pfoClient['CompleteGroup'] = array_unique($complete_group);
		
		if (empty($pfoClient['SupportClient'])) {
			$pfoClient['SupportClient'] = array();
		}
		
		return $pfoClient;
	}
	
	public function clienten()
	{
		$conditions = array();

		$clienten_all = $this->find('all', array(
			'contain' => array(),
			'conditions' => $conditions,
			'fields' => array('id', 'roepnaam', 'tussenvoegsel', 'achternaam'),
		));

		$clienten = array();
		foreach ($clienten_all as $client) {
			$clienten[$client['PfoClient']['id']] =  $client['PfoClient']['roepnaam']." ".$client['PfoClient']['tussenvoegsel']." ".$client['PfoClient']['achternaam'];
			$clienten[$client['PfoClient']['id']] = trim($clienten[$client['PfoClient']['id']]);
		}

		return $clienten;
	}

	public function vrije_clienten($all = null)
	{
		if (! $all) {
			$all = $this->clienten();
		}
		
		$query = "select PfoClient.id  as id , s.id as sid from pfo_clienten PfoClient 
				left join pfo_clienten_supportgroups s on PfoClient.id = pfo_client_id or PfoClient.id = pfo_supportgroup_client_id having isnull(s.id)";
		
		$data = $this->query($query);
		$clienten = array();
		
		foreach ($data as $client) {
			$clienten[$client['PfoClient']['id']] =  $all[$client['PfoClient']['id']];
			$clienten[$client['PfoClient']['id']] = trim($clienten[$client['PfoClient']['id']]);
		}
		
		return $clienten;
		
	}

	public function hoofd_clienten($all = null)
	{
		if (! $all) {
			$all = $this->clienten();
		}
		
		$contain = array(
			'PfoClientenSupportgroup' => array(
				'fields' => array('id', 'pfo_client_id'),
			),
		);
		
		$data = $this->find('all', array(
			'contain' => $contain,
			'fields' => array('id'),
		));
		
		$clienten = array();
		
		foreach ($data as $client) {
			if (count($client['PfoClientenSupportgroup']) == 0) {
				continue;
			}
			$clienten[$client['PfoClient']['id']] =  $all[$client['PfoClient']['id']];
			$clienten[$client['PfoClient']['id']] = trim($clienten[$client['PfoClient']['id']]);
		}
		
		return $clienten;
	}
}
