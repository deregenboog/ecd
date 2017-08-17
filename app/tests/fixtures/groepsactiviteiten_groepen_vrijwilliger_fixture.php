<?php

/* GroepsactiviteitenGroepenVrijwilliger Fixture generated on: 2014-05-03 15:05:30 : 1399124010 */
class GroepsactiviteitenGroepenVrijwilligerFixture extends CakeTestFixture
{
    public $name = 'GroepsactiviteitenGroepenVrijwilliger';

    public $fields = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'groepsactiviteiten_groep_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'vrijwilliger_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'groepsactiviteiten_reden_id' => ['type' => 'integer', 'null' => true, 'default' => null],
        'created' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'modified' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'indexes' => ['PRIMARY' => ['column' => 'id', 'unique' => 1]],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'],
    ];

    public $records = [
        [
            'id' => 1,
            'groepsactiviteiten_groep_id' => 1,
            'vrijwilliger_id' => 1,
            'groepsactiviteiten_reden_id' => 1,
            'created' => '2014-05-03 15:33:30',
            'modified' => '2014-05-03 15:33:30',
        ],
    ];
}
