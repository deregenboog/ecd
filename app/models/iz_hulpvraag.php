<?php

App::import('Model', 'IzKoppeling');

class IzHulpvraag extends IzKoppeling
{
	public $name = 'IzHulpvraag';

	public $hasOne = array(
		'IzHulpaanbod' => array(
			'className' => 'IzHulpaanbod',
			'foreignKey' => 'iz_koppeling_id',
		),
	);

	public $belongsTo = array(
		'IzProject' => array(
			'className' => 'IzProject',
			'foreignKey' => 'project_id',
			'type' => 'INNER',
		),
		'IzKlant' => array(
			'className' => 'IzKlant',
			'foreignKey' => 'iz_deelnemer_id',
			'type' => 'INNER',
			'conditions' => array('model' => 'Klant'),
		),
		'Medewerker' => array(
			'className' => 'Medewerker',
			'foreignKey' => 'medewerker_id',
			'type' => 'inner',
		),
		'IzEindekoppeling' => array(
			'className' => 'IzEindekoppeling',
			'foreignKey' => 'iz_eindekoppeling_id',
		),
		'IzVraagaanbod' => array(
			'className' => 'IzVraagaanbod',
			'foreignKey' => 'iz_vraagaanbod_id',
		),
	);
}
