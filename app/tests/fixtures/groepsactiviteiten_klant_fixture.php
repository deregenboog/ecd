<?php

/* GroepsactiviteitenKlant Fixture generated on: 2014-05-04 13:05:53 : 1399203713 */
class GroepsactiviteitenKlantFixture extends CakeTestFixture
{
    public $name = 'GroepsactiviteitenKlant';

    public $fields = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'groepsactiviteit_id' => ['type' => 'integer', 'null' => true, 'default' => null],
        'klant_id' => ['type' => 'integer', 'null' => true, 'default' => null],
        'created' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'modified' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'indexes' => ['PRIMARY' => ['column' => 'id', 'unique' => 1]],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'],
    ];

    public $records = [
        [
            'id' => 1,
            'groepsactiviteit_id' => 1,
            'klant_id' => 1,
            'created' => '2014-05-04 13:41:53',
            'modified' => '2014-05-04 13:41:53',
        ],
    ];
}
