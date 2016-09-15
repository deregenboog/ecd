<?php

class IzVerslag extends AppModel
{
	public $name = 'IzVerslag';
	public $displayField = 'medewerker_id';

	public $actsAs = array('Containable');

	public $belongsTo = array(
		'IzDeelnemer' => array(
			'className' => 'IzDeelnemer',
			'foreignKey' => 'iz_deelnemer_id',
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
}
