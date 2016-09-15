<?php
/* ZrmSetting Fixture generated on: 2013-11-26 17:11:39 : 1385484879 */
class ZrmSettingFixture extends CakeTestFixture {
	var $name = 'ZrmSetting';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'request_module' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50),
		'inkomen' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'dagbesteding' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'huisvesting' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'gezinsrelaties' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'fysieke_gezondheid' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'verslaving' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'adl_vaardigheden' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'sociaal_netwerk' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'maatschappelijke_participatie' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'justitie' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'request_module' => 'Lorem ipsum dolor sit amet',
			'inkomen' => 1,
			'dagbesteding' => 1,
			'huisvesting' => 1,
			'gezinsrelaties' => 1,
			'fysieke_gezondheid' => 1,
			'verslaving' => 1,
			'adl_vaardigheden' => 1,
			'sociaal_netwerk' => 1,
			'maatschappelijke_participatie' => 1,
			'justitie' => 1,
			'created' => '2013-11-26 17:54:39',
			'modified' => '2013-11-26 17:54:39'
		),
	);
}
?>