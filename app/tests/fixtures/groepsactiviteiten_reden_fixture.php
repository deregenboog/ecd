<?php

/* GroepsactiviteitenReden Fixture generated on: 2014-05-03 15:05:32 : 1399124072 */
class GroepsactiviteitenRedenFixture extends CakeTestFixture
{
    public $name = 'GroepsactiviteitenReden';

    public $fields = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'naam' => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 100],
        'created' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'modified' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'indexes' => ['PRIMARY' => ['column' => 'id', 'unique' => 1]],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'],
    ];

    public $records = [
        [
            'id' => 1,
            'naam' => 'Lorem ipsum dolor sit amet',
            'created' => '2014-05-03 15:34:32',
            'modified' => '2014-05-03 15:34:32',
        ],
    ];
}
