<?php
/* BotVerslag Fixture generated on: 2013-10-07 17:10:10 : 1381161130 */
class BotVerslagFixture extends CakeTestFixture {
    var $name = 'BotVerslag';
    $table = 'bot_verslagen';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'contact_type' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50),
		'verslag' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'medewerker_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'contact_type' => 'Lorem ipsum dolor sit amet',
			'verslag' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'medewerker_id' => 1,
			'created' => '2013-10-07 17:52:10',
			'modified' => '2013-10-07 17:52:10'
		),
	);
}
?>
