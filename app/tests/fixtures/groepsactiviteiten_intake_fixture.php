<?php
/* GroepsactiviteitenIntake Fixture generated on: 2014-05-07 13:05:57 : 1399461657 */
class GroepsactiviteitenIntakeFixture extends CakeTestFixture {
	var $name = 'GroepsactiviteitenIntake';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'model' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'foreign_key' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'gespreksverslag' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'informele_zorg' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 4),
		'dagbesteding' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 4),
		'inloophuis' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 4),
		'hulpverlening' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 4),
		'intakedatum' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'groepsactiviteiten_intakes_foreign_key_model_idx' => array('column' => array('foreign_key', 'model'), 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'model' => 'Lorem ipsum dolor sit amet',
			'foreign_key' => 1,
			'gespreksverslag' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'informele_zorg' => 1,
			'dagbesteding' => 1,
			'inloophuis' => 1,
			'hulpverlening' => 1,
			'intakedatum' => '2014-05-07',
			'created' => '2014-05-07 13:20:57',
			'modified' => '2014-05-07 13:20:57'
		),
	);
}
?>