<?php

/* Groepsactiviteit Fixture generated on: 2014-05-03 15:05:30 : 1399122870 */
class GroepsactiviteitFixture extends CakeTestFixture
{
    public $name = 'Groepsactiviteit';

    public $fields = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'groepsactiviteiten_groep_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'naam' => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 100],
        'datum' => ['type' => 'date', 'null' => true, 'default' => null],
        'time' => ['type' => 'time', 'null' => true, 'default' => null],
        'afgesloten' => ['type' => 'boolean', 'null' => true, 'default' => null],
        'created' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'modified' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'indexes' => ['PRIMARY' => ['column' => 'id', 'unique' => 1]],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'],
    ];

    public $records = [
        [
            'id' => 1,
            'groepsactiviteiten_groep_id' => 1,
            'naam' => 'Lorem ipsum dolor sit amet',
            'datum' => '2014-05-03',
            'time' => '15:14:30',
            'afgesloten' => 1,
            'created' => '2014-05-03 15:14:30',
            'modified' => '2014-05-03 15:14:30',
        ],
    ];
}
