<?php
/* ZrmReport Fixture generated on: 2013-11-26 11:11:23 : 1385462603 */
class ZrmReportFixture extends CakeTestFixture {
	var $name = 'ZrmReport';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'model' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50),
		'foreign_key' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'request_module' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50),
		'inkomen' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'dagbesteding' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'huisvesting' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'gezinsrelaties' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'geestelijke_gezondheid' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'fysieke_gezondheid' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'verslaving' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'adl_vaardigheden' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'sociaal_netwerk' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'maatschappelijke_participatie' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'justitie' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'model' => 'Lorem ipsum dolor sit amet',
			'foreign_key' => 1,
			'module' => 'Lorem ipsum dolor sit amet',
			'inkomen' => 1,
			'dagbesteding' => 1,
			'huisvesting' => 1,
			'gezinsrelaties' => 1,
			'geestelijke_gezondheid' => 1,
			'fysieke_gezondheid' => 1,
			'verslaving' => 1,
			'adl_vaardigheden' => 1,
			'sociaal_netwerk' => 1,
			'maatschappelijke_participatie' => 1,
			'justitie' => 1
		),
	);
}
?>
