<?php
/* GroepsactiviteitenVrijwilliger Fixture generated on: 2014-05-03 15:05:52 : 1399123972 */
class GroepsactiviteitenVrijwilligerFixture extends CakeTestFixture {
	var $name = 'GroepsactiviteitenVrijwilliger';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'groepsactiviteit_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'vrijwilliger_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'startdatum' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'einddatum' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'communicatie_methode' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'groepsactiviteit_id' => 1,
			'vrijwilliger_id' => 1,
			'startdatum' => '2014-05-03',
			'einddatum' => '2014-05-03',
			'communicatie_methode' => 'Lorem ipsum dolor sit amet',
			'created' => '2014-05-03 15:32:52',
			'modified' => '2014-05-03 15:32:52'
		),
	);
}
?>