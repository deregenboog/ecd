<?php
/* AwbzIndicatie Fixture generated on: 2011-04-22 14:04:56 : 1303474856 */
class AwbzReportAwbzIndicatieFixture extends CakeTestFixture {
	var $name = 'AwbzIndicatie';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'klant_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'begindatum' => array('type' => 'date', 'null' => false, 'default' => NULL),
		'einddatum' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'begeleiding_per_week' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 5),
		'activering_per_week' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 5),
		'hoofdaannemer_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'aangevraagd_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 1),
		'aangevraagd_datum' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'aangevraagd_niet' => array('type' => 'boolean', 'null' => true, 'default' => '0'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 15,
			'klant_id' => 1,
			'begindatum' => '2011-01-01',
			'einddatum' => '2011-01-21',
			'begeleiding_per_week' => 7,
			'activering_per_week' => 7,
			'hoofdaannemer_id' => 4,
			'created' => '2011-04-22 10:23:56',
			'modified' => '2011-04-22 10:23:56',
			'aangevraagd_id' => NULL,
			'aangevraagd_datum' => NULL,
			'aangevraagd_niet' => 0
		),
		array(
			'id' => 16,
			'klant_id' => 1,
			'begindatum' => '2011-03-31',
			'einddatum' => '2011-04-04',
			'begeleiding_per_week' => 20,
			'activering_per_week' => 5,
			'hoofdaannemer_id' => 4,
			'created' => '2011-04-22 10:23:56',
			'modified' => '2011-04-22 10:23:56',
			'aangevraagd_id' => NULL,
			'aangevraagd_datum' => NULL,
			'aangevraagd_niet' => 0
		),
		array(
			'id' => 17,
			'klant_id' => 1,
			'begindatum' => '2011-04-05',
			'einddatum' => '2011-04-07',
			'begeleiding_per_week' => 10,
			'activering_per_week' => 2,
			'hoofdaannemer_id' => 4,
			'created' => '2011-04-22 10:24:16',
			'modified' => '2011-04-22 10:24:16',
			'aangevraagd_id' => NULL,
			'aangevraagd_datum' => NULL,
			'aangevraagd_niet' => 0
		),
		array(
			'id' => 18,
			'klant_id' => 1,
			'begindatum' => '2011-03-28',
			'einddatum' => '2011-03-30',
			'begeleiding_per_week' => 4,
			'activering_per_week' => 4,
			'hoofdaannemer_id' => 4,
			'created' => '2011-04-22 11:31:01',
			'modified' => '2011-04-22 11:31:01',
			'aangevraagd_id' => NULL,
			'aangevraagd_datum' => NULL,
			'aangevraagd_niet' => 0
		),
		array(
			'id' => 19,
			'klant_id' => 2,
			'begindatum' => '2011-04-02',
			'einddatum' => '2011-04-07',
			'begeleiding_per_week' => 40,
			'activering_per_week' => 0,
			'hoofdaannemer_id' => 4,
			'created' => '2011-04-22 12:44:16',
			'modified' => '2011-04-22 12:44:16',
			'aangevraagd_id' => NULL,
			'aangevraagd_datum' => NULL,
			'aangevraagd_niet' => 0
		),
		array(
			'id' => 20,
			'klant_id' => 2,
			'begindatum' => '2011-03-29',
			'einddatum' => '2011-04-01',
			'begeleiding_per_week' => 10,
			'activering_per_week' => 2,
			'hoofdaannemer_id' => 7,
			'created' => '2011-04-22 12:57:37',
			'modified' => '2011-04-22 12:57:37',
			'aangevraagd_id' => NULL,
			'aangevraagd_datum' => NULL,
			'aangevraagd_niet' => 0
		),
		array(
			'id' => 21,
			'klant_id' => 2,
			'begindatum' => '2011-05-01',
			'einddatum' => '2011-04-30',
			'begeleiding_per_week' => 9,
			'activering_per_week' => 9,
			'hoofdaannemer_id' => 4,
			'created' => '2011-04-22 13:42:56',
			'modified' => '2011-04-22 13:42:56',
			'aangevraagd_id' => NULL,
			'aangevraagd_datum' => NULL,
			'aangevraagd_niet' => 0
		),
		array(
			'id' => 22,
			'klant_id' => 288,
			'begindatum' => '2011-05-01',
			'einddatum' => '2011-05-31',
			'begeleiding_per_week' => 1,
			'activering_per_week' => 1,
			'hoofdaannemer_id' => 4,
			'created' => '2011-04-22 13:44:20',
			'modified' => '2011-04-22 13:44:20',
			'aangevraagd_id' => NULL,
			'aangevraagd_datum' => NULL,
			'aangevraagd_niet' => 0
		),
	);
}
?>