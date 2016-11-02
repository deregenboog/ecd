<?php

class verslaving extends AppModel
{
	public $name = 'Verslaving';
	public $displayField = 'naam';

	public $hasAndBelongsToMany = array(
		'Intake' => array(
			'className' => 'Intake',
			'joinTable' => 'intakes_verslavingen',
			'foreignKey' => 'verslaving_id',
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
			'joinTable' => 'awbz_intakes_verslavingen',
			'foreignKey' => 'verslaving_id',
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
