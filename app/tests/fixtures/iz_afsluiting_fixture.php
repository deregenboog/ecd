<?php
/* IzAfsluiting Fixture generated on: 2014-08-13 14:08:13 : 1407934033 */
class IzAfsluitingFixture extends CakeTestFixture {
	var $name = 'IzAfsluiting';

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
			'created' => '2014-08-13 14:47:13',
			'modified' => '2014-08-13 14:47:13'
		),
	);
}
?>