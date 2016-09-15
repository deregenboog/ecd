<?php
/* IzDeelnemersIzIntervisiegroep Fixture generated on: 2014-08-20 15:08:16 : 1408540336 */
class IzDeelnemersIzIntervisiegroepFixture extends CakeTestFixture {
	var $name = 'IzDeelnemersIzIntervisiegroep';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'iz_deelnemer_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'iz_intervisiegroep_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'iz_deelnemer_id' => 1,
			'iz_intervisiegroep_id' => 1,
			'created' => '2014-08-20 15:12:16',
			'modified' => '2014-08-20 15:12:16'
		),
	);
}
?>