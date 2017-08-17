<?php

/* Infobaliedoelgroep Fixture generated on: 2011-12-30 10:12:09 : 1325236149 */
class InfobaliedoelgroepFixture extends CakeTestFixture
{
    public $name = 'Infobaliedoelgroep';

    public $fields = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'naam' => ['type' => 'string', 'null' => false, 'default' => null],
        'indexes' => ['PRIMARY' => ['column' => 'id', 'unique' => 1]],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'],
    ];

    public $records = [
        [
            'id' => 1,
            'naam' => 'Lorem ipsum dolor sit amet',
        ],
    ];
}
