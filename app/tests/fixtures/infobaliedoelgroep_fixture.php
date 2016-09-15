<?php
/* Infobaliedoelgroep Fixture generated on: 2011-12-30 10:12:09 : 1325236149 */
class InfobaliedoelgroepFixture extends CakeTestFixture {
	var $name = 'Infobaliedoelgroep';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'naam' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'naam' => 'Lorem ipsum dolor sit amet'
		),
	);
}
?>