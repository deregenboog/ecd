<?php
/* Land Fixture generated on: 2013-10-16 15:10:30 : 1381930350 */
class LandFixture extends CakeTestFixture {
	var $name = 'Land';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'land' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'AFK2' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 5),
		'AFK3' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 5),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 0,
			'land' => 'Onbekend',
			'AFK2' => '',
			'AFK3' => '',
			'created' => NULL,
			'modified' => NULL
		),
		array(
			'id' => 5001,
			'land' => 'Canada',
			'AFK2' => '',
			'AFK3' => '',
			'created' => NULL,
			'modified' => NULL
		),
		array(
			'id' => 5002,
			'land' => 'Frankrijk',
			'AFK2' => '',
			'AFK3' => '',
			'created' => NULL,
			'modified' => NULL
		),
		array(
			'id' => 5003,
			'land' => 'Zwitserland',
			'AFK2' => '',
			'AFK3' => '',
			'created' => NULL,
			'modified' => NULL
		),
		array(
			'id' => 5004,
			'land' => 'Rhodesië',
			'AFK2' => '',
			'AFK3' => '',
			'created' => NULL,
			'modified' => NULL
		),
		array(
			'id' => 5005,
			'land' => 'Malawi',
			'AFK2' => '',
			'AFK3' => '',
			'created' => NULL,
			'modified' => NULL
		),
		array(
			'id' => 5006,
			'land' => 'Cuba',
			'AFK2' => '',
			'AFK3' => '',
			'created' => NULL,
			'modified' => NULL
		),
		array(
			'id' => 5007,
			'land' => 'Suriname',
			'AFK2' => '',
			'AFK3' => '',
			'created' => NULL,
			'modified' => NULL
		),
		array(
			'id' => 5008,
			'land' => 'Tunesië',
			'AFK2' => '',
			'AFK3' => '',
			'created' => NULL,
			'modified' => NULL
		),
		array(
			'id' => 5009,
			'land' => 'Oostenrijk',
			'AFK2' => '',
			'AFK3' => '',
			'created' => NULL,
			'modified' => NULL
		),
	);
}
?>