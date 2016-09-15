<?php
/* IntakeVerslaving Fixture generated on: 2011-04-22 15:04:45 : 1303479885 */
class IntakeVerslavingFixture extends CakeTestFixture {
	var $name = 'IntakeVerslaving';
    var $table = 'intakes_verslavingen';
    var $import = array ( 'table' => 'intakes_verslavingen' );
	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'intake_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'verslaving_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'intake_id' => 1,
			'verslaving_id' => 1,
			'created' => '2011-04-22 15:44:45',
			'modified' => '2011-04-22 15:44:45'
		),
	);
}
?>