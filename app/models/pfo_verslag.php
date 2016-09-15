<?php

class PfoVerslag extends AppModel
{
	public $name = 'PfoVerslag';
	public $actsAs = array('Containable');
	//The Associations below have been created with all possible keys, those that are not needed can be removed
	public $belongsTo = array(
			'Medewerker' => array(
					'className' => 'Medewerker',
					'foreignKey' => 'medewerker_id',
					'conditions' => '',
					'fields' => '',
					'order' => '',
			),
	);
	public $hasMany = array(
		'PfoClientenVerslag' => array(
			'className' => 'PfoClientenVerslag',
			'foreignKey' => 'pfo_verslag_id',
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
	);
	public $contact_type = array(
		'In persoon' => 'In persoon',
		'Telefonisch' => 'Telefonisch',
		'E-Mail' => 'E-Mail',
		'Extern' => 'Extern',
	);
}
