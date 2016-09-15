<?php

class AwbzHoofdaannemer extends AppModel
{
	public $name = 'AwbzHoofdaannemer';
	public $actsAs = array('FixDates');

	public $validate = array(
		'begindatum' => array(
			'date' => array(
				'rule' => array('date'),
				'message' => 'Datum',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Dit veld is verplicht',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'hoofdaannemer_id' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Dit veld is verplicht',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	public $belongsTo = array(
		'Klant' => array(
			'className' => 'Klant',
			'foreignKey' => 'klant_id',
			'conditions' => '',
			'fields' => '',
			'order' => '',
		),
		'Hoofdaannemer' => array(
			'className' => 'Hoofdaannemer',
			'foreignKey' => 'hoofdaannemer_id',
			'conditions' => '',
			'fields' => '',
			'order' => '',
		),
	);
}
