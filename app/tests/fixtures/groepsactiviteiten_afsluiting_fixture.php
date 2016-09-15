<?php
/* GroepsactiviteitenAfsluiting Fixture generated on: 2015-11-22 08:11:01 : 1448175901 */
class GroepsactiviteitenAfsluitingFixture extends CakeTestFixture {
	var $name = 'GroepsactiviteitenAfsluiting';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'naam' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'naam' => 'Lorem ipsum dolor sit amet',
			'created' => '2015-11-22 08:05:01',
			'modified' => '2015-11-22 08:05:01'
		),
	);
}
?>