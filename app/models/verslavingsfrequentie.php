<?php

class verslavingsfrequentie extends AppModel
{
	public $name = 'Verslavingsfrequentie';
	public $displayField = 'naam';
	public $validate = array(
		'datum_van' => array(
			'date' => array(
				'rule' => array('date'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	public $hasMany = array(
		'Intake' => array(
			'className' => 'Intake',
			'foreignKey' => 'verslavingsfrequentie_id',
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
}
