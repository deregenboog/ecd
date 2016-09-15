<?php
/* GroepsactiviteitenGroep Fixture generated on: 2014-05-03 15:05:16 : 1399123516 */
class GroepsactiviteitenGroepFixture extends CakeTestFixture {
	var $name = 'GroepsactiviteitenGroep';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'naam' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
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
			'startdatum' => '2014-05-03',
			'einddatum' => '2014-05-03',
			'created' => '2014-05-03 15:25:16',
			'modified' => '2014-05-03 15:25:16'
		),
	);
}
?>