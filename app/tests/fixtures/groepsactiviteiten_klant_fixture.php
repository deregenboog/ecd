<?php
/* GroepsactiviteitenKlant Fixture generated on: 2014-05-04 13:05:53 : 1399203713 */
class GroepsactiviteitenKlantFixture extends CakeTestFixture {
	var $name = 'GroepsactiviteitenKlant';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'groepsactiviteit_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'klant_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'groepsactiviteit_id' => 1,
			'klant_id' => 1,
			'created' => '2014-05-04 13:41:53',
			'modified' => '2014-05-04 13:41:53'
		),
	);
}
?>