<?php
/* GroepsactiviteitenGroepenKlant Fixture generated on: 2014-05-04 13:05:16 : 1399203556 */
class GroepsactiviteitenGroepenKlantFixture extends CakeTestFixture {
	var $name = 'GroepsactiviteitenGroepenKlant';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'groepsactiviteiten_groep_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'klant_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'groepsactiviteiten_reden_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'startdatum' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'einddatum' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'communicatie_email' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'communicatie_telefoon' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'communicatie_post' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'groepsactiviteiten_groep_id' => 1,
			'klant_id' => 1,
			'groepsactiviteiten_reden_id' => 1,
			'startdatum' => '2014-05-04',
			'einddatum' => '2014-05-04',
			'communicatie_email' => 1,
			'communicatie_telefoon' => 1,
			'communicatie_post' => 1,
			'created' => '2014-05-04 13:39:16',
			'modified' => '2014-05-04 13:39:16'
		),
	);
}
?>