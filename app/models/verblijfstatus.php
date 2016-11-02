<?php

class verblijfstatus extends AppModel
{
	public $name = 'Verblijfstatus';
	public $displayField = 'naam';
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	public $hasMany = array(
		'Intake' => array(
			'className' => 'Intake',
			'foreignKey' => 'verblijfstatus_id',
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
