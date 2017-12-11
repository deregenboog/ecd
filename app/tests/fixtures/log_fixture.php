<?php

/* Log Fixture generated on: 2011-12-30 10:12:59 : 1325236019 */
class LogFixture extends CakeTestFixture
{
    public $name = 'Log';

    public $fields = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'created' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'model' => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 50, 'key' => 'index'],
        'foreign_key' => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 36],
        'medewerker_id' => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 36, 'key' => 'index'],
        'ip' => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 15],
        'action' => ['type' => 'string', 'null' => true, 'default' => null],
        'change' => ['type' => 'text', 'null' => true, 'default' => null],
        'indexes' => ['PRIMARY' => ['column' => 'id', 'unique' => 1], 'idx_logs_model_foreign_key_created' => ['column' => ['model', 'foreign_key', 'created'], 'unique' => 0], 'idx_logs_medewerker_id' => ['column' => 'medewerker_id', 'unique' => 0], 'idx_logs_model_foreign_key' => ['column' => ['model', 'foreign_key'], 'unique' => 0]],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'],
    ];

    public $records = [
        [
            'id' => 1,
            'created' => '2011-12-30 10:06:59',
            'model' => 'Lorem ipsum dolor sit amet',
            'foreign_key' => 'Lorem ipsum dolor sit amet',
            'medewerker_id' => 'Lorem ipsum dolor sit amet',
            'ip' => 'Lorem ipsum d',
            'action' => 'Lorem ipsum dolor sit amet',
            'change' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
        ],
    ];
}
