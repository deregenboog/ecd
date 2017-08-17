<?php

/* PfoVerslag Fixture generated on: 2013-06-07 14:06:29 : 1370607449 */
class PfoVerslagFixture extends CakeTestFixture
{
    public $name = 'PfoVerslag';

    public $fields = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'contact_type' => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 50],
        'verslag' => ['type' => 'text', 'null' => true, 'default' => null],
        'indexes' => ['PRIMARY' => ['column' => 'id', 'unique' => 1]],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'],
    ];

    public $records = [
        [
            'id' => 1,
            'contact_type' => 'Lorem ipsum dolor sit amet',
            'verslag' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
        ],
    ];
}
