<?php
/* IzDeelnemersIzProject Fixture generated on: 2014-08-11 16:08:17 : 1407767057 */
class IzDeelnemersIzProjectFixture extends CakeTestFixture {
	var $name = 'IzDeelnemersIzProject';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'iz_deelnemer_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'iz_project_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'iz_deelnemer_id' => 1,
			'iz_project_id' => 1,
			'created' => '2014-08-11 16:24:17',
			'modified' => '2014-08-11 16:24:17'
		),
	);
}
?>