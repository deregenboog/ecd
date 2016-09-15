<?php
/* Registratie Fixture generated on: 2013-10-16 12:10:22 : 1381920082 */
class RegistratieFixture extends CakeTestFixture {
	var $name = 'Registratie';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'locatie_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'klant_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'binnen' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'buiten' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'douche' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'mw' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'kleding' => array('type' => 'boolean', 'null' => false, 'default' => NULL),
		'maaltijd' => array('type' => 'boolean', 'null' => false, 'default' => NULL),
		'activering' => array('type' => 'boolean', 'null' => false, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'gbrv' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'idx_registraties_klant_id_locatie_id' => array('column' => array('klant_id', 'locatie_id'), 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'locatie_id' => 1,
			'klant_id' => 1,
			'binnen' => '2011-10-16 12:41:22',
			'buiten' => '2011-10-16 14:41:22',
			'douche' => 1,
			'mw' => 1,
			'kleding' => 1,
			'maaltijd' => 1,
			'activering' => 1,
			'created' => '2011-10-16 12:41:22',
			'modified' => '2011-10-16 14:41:22',
			'gbrv' => 0
		),
		array(
			'id' => 2,
			'locatie_id' => 6,
			'klant_id' => 1,
			'binnen' => '2013-09-16 12:41:22',
			'buiten' => '2013-09-16 13:41:22',
			'douche' => 1,
			'mw' => 1,
			'kleding' => 1,
			'maaltijd' => 1,
			'activering' => 1,
			'created' => '2013-09-16 12:41:22',
			'modified' => '2013-09-16 13:41:22',
			'gbrv' => 0
		),
		array(
			'id' => 3,
			'locatie_id' => 3,
			'klant_id' => 1,
			'binnen' => '2013-10-15 12:21:22',
			'buiten' => '2013-10-15 13:51:22',
			'douche' => 1,
			'mw' => 1,
			'kleding' => 1,
			'maaltijd' => 1,
			'activering' => 1,
			'created' => '2013-10-15 12:21:22',
			'modified' => '2013-10-15 13:51:22',
			'gbrv' => 0
		),

	);
}
?>
