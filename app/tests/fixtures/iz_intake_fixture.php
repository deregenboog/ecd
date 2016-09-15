<?php
/* IzIntake Fixture generated on: 2014-08-12 13:08:16 : 1407844036 */
class IzIntakeFixture extends CakeTestFixture {
	var $name = 'IzIntake';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'iz_deelnemer_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'medewerker_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'intake_datum' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'gesprek_verslag' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'ondernemen' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'overdag' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'ontmoeten' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'regelzaken' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modifed' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'iz_deelnemer_id' => 1,
			'medewerker_id' => 1,
			'intake_datum' => '2014-08-12',
			'gesprek_verslag' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'ondernemen' => 1,
			'overdag' => 1,
			'ontmoeten' => 1,
			'regelzaken' => 1,
			'created' => '2014-08-12 13:47:16',
			'modifed' => '2014-08-12 13:47:16'
		),
	);
}
?>