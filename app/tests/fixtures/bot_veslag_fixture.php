<?php

/* BotVerslag Fixture generated on: 2013-10-07 17:10:10 : 1381161130 */
class BotVerslagFixture extends CakeTestFixture
{
    public $name = 'BotVerslag';
    public $table = 'bot_verslagen';

    public $fields = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'contact_type' => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 50],
        'verslag' => ['type' => 'text', 'null' => true, 'default' => null],
        'medewerker_id' => ['type' => 'integer', 'null' => true, 'default' => null],
        'created' => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified' => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes' => ['PRIMARY' => ['column' => 'id', 'unique' => 1]],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'],
    ];

    public $records = [
        [
            'id' => 1,
            'contact_type' => 'Lorem ipsum dolor sit amet',
            'verslag' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'medewerker_id' => 1,
            'created' => '2013-10-07 17:52:10',
            'modified' => '2013-10-07 17:52:10',
        ],
    ];
}
