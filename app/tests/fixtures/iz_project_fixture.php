<?php
/* IzProject Fixture generated on: 2014-08-11 16:08:37 : 1407767257 */
class IzProjectFixture extends CakeTestFixture {
	var $name = 'IzProject';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'naam' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'startdatum' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'einddatum' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'naam' => 'Lorem ipsum dolor sit amet',
			'startdatum' => '2014-08-11',
			'einddatum' => '2014-08-11',
			'created' => '2014-08-11 16:27:37',
			'modified' => '2014-08-11 16:27:37'
		),
	);
}
?>