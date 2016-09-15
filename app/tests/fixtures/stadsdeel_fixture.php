<?php
/* Stadsdeel Fixture generated on: 2014-05-05 16:05:28 : 1399300888 */
class StadsdeelFixture extends CakeTestFixture {
	var $name = 'Stadsdeel';

	var $fields = array(
		'postcode' => array('type' => 'string', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'stadsdeel' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'postcode', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'postcode' => 'Lorem ipsum dolor sit amet',
			'stadsdeel' => 'Lorem ipsum dolor sit amet'
		),
	);
}
?>