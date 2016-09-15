<?php
/* BackOnTrack Fixture generated on: 2013-09-25 12:09:54 : 1380106554 */
class BackOnTrackFixture extends CakeTestFixture {
	var $name = 'BackOnTrack';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'klant_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'startdatum' => array('type' => 'date', 'null' => true, 'default' => NULL, 'key' => 'index'),
		'einddatum' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'idx_back_on_tracks_dates' => array('column' => array('startdatum', 'einddatum'), 'unique' => 0)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'klant_id' => 1,
			'startdatum' => '2013-09-25',
			'einddatum' => '2013-09-25',
			'created' => '2013-09-25 12:55:54',
			'modified' => '2013-09-25 12:55:54'
		),
	);
}
?>