<?php
/* PfoClientenVerslag Fixture generated on: 2013-06-08 11:06:54 : 1370683914 */
class PfoClientenVerslagFixture extends CakeTestFixture {
	var $name = 'PfoClientenVerslag';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'pfo_client_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'pfo_verslag_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'pfo_client_id' => 1,
			'pfo_verslag_id' => 1,
			'created' => '2013-06-08 11:31:54',
			'modified' => '2013-06-08 11:31:54'
		),
	);
}
?>