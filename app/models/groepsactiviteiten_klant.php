<?php

class GroepsactiviteitenKlant extends AppModel
{
	public $name = 'GroepsactiviteitenKlant';
	public $displayField = 'groepsactiviteit_id';
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	public $actsAs = array('Containable');

	public $belongsTo = array(
		'Groepsactiviteit' => array(
			'className' => 'Groepsactiviteit',
			'foreignKey' => 'groepsactiviteit_id',
			'conditions' => '',
			'fields' => '',
			'order' => '',
		),
		'Klant' => array(
			'className' => 'Klant',
			'foreignKey' => 'klant_id',
			'conditions' => '',
			'fields' => '',
			'order' => '',
		),
	);

	public function get_count($groepsactiviteit_id)
	{
		$return = $this->find('list', array(
			'conditions' => array('groepsactiviteit_id' => $groepsactiviteit_id    ),
			'contain' => array(),
		));

		$count = count($return);
		return $count;
	}
}
