<?php

/* Verslaginfo Fixture generated on: 2011-04-22 09:04:14 : 1303459034 */
class VerslaginfoFixture extends CakeTestFixture
{
    public $name = 'Verslaginfo';

    public $fields = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'klant_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'advocaat' => ['type' => 'string', 'null' => false, 'default' => null],
        'contact' => ['type' => 'text', 'null' => false, 'default' => null],
        'indexes' => ['PRIMARY' => ['column' => 'id', 'unique' => 1]],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'],
    ];

    public $records = [
        [
            'id' => 1,
            'klant_id' => 1,
            'advocaat' => 'Lorem ipsum dolor sit amet',
            'contact' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
        ],
    ];
}
