<?php
/* Vrijwilliger Fixture generated on: 2014-05-03 15:05:15 : 1399123095 */
class VrijwilligerFixture extends CakeTestFixture {
	var $name = 'Vrijwilliger';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'voornaam' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'tussenvoegsel' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'achternaam' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'roepnaam' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'geslacht_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'geboortedatum' => array('type' => 'date', 'null' => true, 'default' => NULL, 'key' => 'index'),
		'land_id' => array('type' => 'integer', 'null' => false, 'default' => '1'),
		'nationaliteit_id' => array('type' => 'integer', 'null' => false, 'default' => '1'),
		'BSN' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'medewerker_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'adres' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'postcode' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 6),
		'werkgebied' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 20),
		'plaats' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'email' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'mobiel' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'telefoon' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'opmerking' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'geen_post' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 4),
		'disabled' => array('type' => 'boolean', 'null' => true, 'default' => '0'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'idx_klanten_geboortedatum' => array('column' => 'geboortedatum', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'voornaam' => 'Lorem ipsum dolor sit amet',
			'tussenvoegsel' => 'Lorem ipsum dolor sit amet',
			'achternaam' => 'Lorem ipsum dolor sit amet',
			'roepnaam' => 'Lorem ipsum dolor sit amet',
			'geslacht_id' => 1,
			'geboortedatum' => '2014-05-03',
			'land_id' => 1,
			'nationaliteit_id' => 1,
			'BSN' => 'Lorem ipsum dolor sit amet',
			'medewerker_id' => 1,
			'adres' => 'Lorem ipsum dolor sit amet',
			'postcode' => 'Lore',
			'werkgebied' => 'Lorem ipsum dolor ',
			'plaats' => 'Lorem ipsum dolor sit amet',
			'email' => 'Lorem ipsum dolor sit amet',
			'mobiel' => 'Lorem ipsum dolor sit amet',
			'telefoon' => 'Lorem ipsum dolor sit amet',
			'opmerking' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'geen_post' => 1,
			'disabled' => 1,
			'created' => '2014-05-03 15:18:15',
			'modified' => '2014-05-03 15:18:15'
		),
	);
}
?>