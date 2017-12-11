<?php

/* GroepsactiviteitenGroep Fixture generated on: 2014-05-03 15:05:16 : 1399123516 */
class GroepsactiviteitenGroepFixture extends CakeTestFixture
{
    public $name = 'GroepsactiviteitenGroep';

    public $fields = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'naam' => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 100],
        'startdatum' => ['type' => 'date', 'null' => true, 'default' => null],
        'einddatum' => ['type' => 'date', 'null' => true, 'default' => null],
        'created' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'modified' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'indexes' => ['PRIMARY' => ['column' => 'id', 'unique' => 1]],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'],
    ];

    public $records = [
        [
            'id' => 1,
            'naam' => 'Lorem ipsum dolor sit amet',
            'startdatum' => '2014-05-03',
            'einddatum' => '2014-05-03',
            'created' => '2014-05-03 15:25:16',
            'modified' => '2014-05-03 15:25:16',
        ],
    ];
}
