<?php
/* Klantinventarisatie Fixture generated on: 2014-05-08 17:05:34 : 1399563274 */
class KlantinventarisatieFixture extends CakeTestFixture {
	var $name = 'Klantinventarisatie';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'klant_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'inventarisatie_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'doorverwijzer_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'datum' => array('type' => 'date', 'null' => false, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'klant_id' => 1,
			'inventarisatie_id' => 1,
			'doorverwijzer_id' => 1,
			'datum' => '2014-05-08',
			'created' => '2014-05-08 17:34:34',
			'modified' => '2014-05-08 17:34:34'
		),
	);
}
?>