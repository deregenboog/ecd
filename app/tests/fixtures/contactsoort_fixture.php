<?php
/* Contactsoort Fixture generated on: 2011-09-20 11:09:47 : 1316510087 */
class ContactsoortFixture extends CakeTestFixture {
	var $name = 'Contactsoort';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'text' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'text' => 'Lorem ipsum dolor sit amet'
		),
	);
}
?>