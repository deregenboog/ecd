<?php
/* PfoClientenSupportgroup Fixture generated on: 2013-06-06 21:06:20 : 1370545220 */
class PfoClientenSupportgroupFixture extends CakeTestFixture {
	var $name = 'PfoClientenSupportgroup';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'pfo_client_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'pfo_supportgroup_client_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'pfo_client_id' => 1,
			'pfo_supportgroup_client_id' => 1
		),
	);
}
?>