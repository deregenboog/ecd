<?php

class BotVerslag extends AppModel
{
	public $name = 'BotVerslag';

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
	public $actsAs = array('Containable');

	public $contact_type = array(
		'In persoon' => 'In persoon',
		'Telefonisch' => 'Telefonisch',
		'E-Mail' => 'E-Mail',
	);

	public function getContactTypes()
	{
		return $this->contact_type;
	}
}
