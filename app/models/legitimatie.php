<?php

class legitimatie extends AppModel
{
	public $name = 'Legitimatie';
	public $displayField = 'naam';

	public $hasMany = array(
		'Intake' => array(
			'className' => 'Intake',
			'foreignKey' => 'legitimatie_id',
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
