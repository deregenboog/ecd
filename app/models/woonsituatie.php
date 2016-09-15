<?php

class Woonsituatie extends AppModel
{
	public $name = 'Woonsituatie';
	public $displayField = 'naam';

	public $hasMany = array(
		'Intake' => array(
			'className' => 'Intake',
			'foreignKey' => 'woonsituatie_id',
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
