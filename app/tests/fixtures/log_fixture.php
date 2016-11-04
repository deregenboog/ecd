<?php

/* Log Fixture generated on: 2011-12-30 10:12:59 : 1325236019 */
class LogFixture extends CakeTestFixture
{
    public $name = 'Log';

    public $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
        'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
        'model' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 50, 'key' => 'index'),
        'foreign_key' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 36),
        'medewerker_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 36, 'key' => 'index'),
        'ip' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 15),
        'action' => array('type' => 'string', 'null' => true, 'default' => null),
        'change' => array('type' => 'text', 'null' => true, 'default' => null),
        'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'idx_logs_model_foreign_key_created' => array('column' => array('model', 'foreign_key', 'created'), 'unique' => 0), 'idx_logs_medewerker_id' => array('column' => 'medewerker_id', 'unique' => 0), 'idx_logs_model_foreign_key' => array('column' => array('model', 'foreign_key'), 'unique' => 0)),
        'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
    );

    public $records = array(
        array(
            'id' => 1,
            'created' => '2011-12-30 10:06:59',
            'model' => 'Lorem ipsum dolor sit amet',
            'foreign_key' => 'Lorem ipsum dolor sit amet',
            'medewerker_id' => 'Lorem ipsum dolor sit amet',
            'ip' => 'Lorem ipsum d',
            'action' => 'Lorem ipsum dolor sit amet',
            'change' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
        ),
    );
}
