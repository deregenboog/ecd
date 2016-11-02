<?php

class instantie extends AppModel
{
	public $name = 'Instantie';
	public $displayField = 'naam';

	public $hasAndBelongsToMany = array(
		'Intake' => array(
			'className' => 'Intake',
			'joinTable' => 'instanties_intakes',
			'foreignKey' => 'instantie_id',
			'associationForeignKey' => 'intake_id',
			'unique' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
			'deleteQuery' => '',
			'insertQuery' => '',
		),
		'AwbzIntake' => array(
			'className' => 'AwbzIntake',
			'joinTable' => 'instanties_awbz_intakes',
			'foreignKey' => 'instantie_id',
			'associationForeignKey' => 'awbz_intake_id',
			'unique' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
			'deleteQuery' => '',
			'insertQuery' => '',
		),
	);
}
