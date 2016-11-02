<?php

class notitie extends AppModel
{
	public $name = 'Notitie';
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	public $belongsTo = array(
		'Klant' => array(
			'className' => 'Klant',
			'foreignKey' => 'klant_id',
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
