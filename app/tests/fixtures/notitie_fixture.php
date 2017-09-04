<?php

/* Notitie Fixture generated on: 2010-08-17 15:08:47 : 1282050347 */
class NotitieFixture extends CakeTestFixture
{
    public $name = 'Notitie';

    public $fields = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'klant_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'medewerker_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'datum' => ['type' => 'timestamp', 'null' => false, 'default' => 'CURRENT_TIMESTAMP'],
        'opmerking' => ['type' => 'text', 'null' => false, 'default' => null],
        'created' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'modified' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'indexes' => ['PRIMARY' => ['column' => 'id', 'unique' => 1]],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'],
    ];

    public $records = [
        [
            'id' => 1,
            'klant_id' => 1,
            'medewerker_id' => 1,
            'datum' => '1282050347',
            'opmerking' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'created' => '2010-08-17 15:05:47',
            'modified' => '2010-08-17 15:05:47',
        ],
    ];
}
