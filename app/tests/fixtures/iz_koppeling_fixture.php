<?php
/* IzKoppeling Fixture generated on: 2014-08-13 12:08:03 : 1407927183 */
class IzKoppelingFixture extends CakeTestFixture {
	var $name = 'IzKoppeling';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'iz_deelnemer_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'medewerker_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'start_datum' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'afsluit_datum' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'iz_vraagaanbod_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'iz_koppeling_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'koppeling_start_datum' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'koppeling_eind_datum' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'iz_eindekoppeling_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'koppeling_succesvol' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'iz_deelnemer_id' => 1,
			'medewerker_id' => 1,
			'start_datum' => '2014-08-13',
			'afsluit_datum' => '2014-08-13',
			'iz_vraagaanbod_id' => 1,
			'iz_koppeling_id' => 1,
			'koppeling_start_datum' => '2014-08-13',
			'koppeling_eind_datum' => '2014-08-13',
			'iz_eindekoppeling_id' => 1,
			'koppeling_succesvol' => 1,
			'created' => '2014-08-13 12:53:03',
			'modified' => '2014-08-13 12:53:03'
		),
	);
}
?>