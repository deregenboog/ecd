<?php
/* Klant Fixture generated on: 2013-10-16 12:10:55 : 1381920055 */
class KlantFixture extends CakeTestFixture {
    var $name = 'Klant';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'MezzoID' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'voornaam' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'tussenvoegsel' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'achternaam' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'roepnaam' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'geslacht_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'geboortedatum' => array('type' => 'date', 'null' => true, 'default' => NULL, 'key' => 'index'),
		'land_id' => array('type' => 'integer', 'null' => false, 'default' => '1'),
		'nationaliteit_id' => array('type' => 'integer', 'null' => false, 'default' => '1'),
		'BSN' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'medewerker_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'laatste_TBC_controle' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'laste_intake_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'disabled' => array('type' => 'boolean', 'null' => true, 'default' => '0'),
		'laatste_registratie_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'doorverwijzen_naar_amoc' => array('type' => 'boolean', 'null' => true, 'default' => '0'),
		'merged_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'idx_klanten_geboortedatum' => array('column' => 'geboortedatum', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'MezzoID' => 1,
			'voornaam' => 'My',
			'tussenvoegsel' => 'R.',
			'achternaam' => 'Visitor',
			'roepnaam' => 'DaReal',
			'geslacht_id' => 1,
			'geboortedatum' => '2013-10-16',
			'land_id' => 5001,
			'nationaliteit_id' => 1,
			'BSN' => '123456789ABC',
			'medewerker_id' => 1,
			'laatste_TBC_controle' => '2013-09-16',
			'created' => '2013-10-16 12:40:55',
			'modified' => '2013-10-16 12:40:55',
			'laste_intake_id' => 2,
			'disabled' => 0,
			'laatste_registratie_id' => 3,
			'doorverwijzen_naar_amoc' => 1,
			'merged_id' => 1
		),
	);
}
?>
