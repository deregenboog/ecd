<?php

class PfoClientenVerslag extends AppModel
{
	public $name = 'PfoClientenVerslag';
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	public $belongsTo = array(
		'PfoClient' => array(
			'className' => 'PfoClient',
			'foreignKey' => 'pfo_client_id',
			'conditions' => '',
			'fields' => '',
			'order' => '',
		),
		'PfoVerslag' => array(
			'className' => 'PfoVerslag',
			'foreignKey' => 'pfo_verslag_id',
			'conditions' => '',
			'fields' => '',
			'order' => '',
		),
	);
}
