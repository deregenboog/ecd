<?php
/* QueueTask Fixture generated on: 2014-05-11 20:05:57 : 1399833897 */
class QueueTaskFixture extends CakeTestFixture {
	var $name = 'QueueTask';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'model' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50),
		'foreign_key' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36),
		'action' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50),
		'data' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL, 'key' => 'index'),
		'run_after' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'batch' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50),
		'output' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'executed' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'status' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'idx_queue_tasks_status_modified' => array('column' => array('modified', 'status'), 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'model' => 'Lorem ipsum dolor sit amet',
			'foreign_key' => 'Lorem ipsum dolor sit amet',
			'action' => 'Lorem ipsum dolor sit amet',
			'data' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'created' => '2014-05-11 20:44:57',
			'modified' => '2014-05-11 20:44:57',
			'run_after' => '2014-05-11 20:44:57',
			'batch' => 'Lorem ipsum dolor sit amet',
			'output' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'executed' => '2014-05-11 20:44:57',
			'status' => 'Lorem ipsum dolor sit amet'
		),
	);
}
?>