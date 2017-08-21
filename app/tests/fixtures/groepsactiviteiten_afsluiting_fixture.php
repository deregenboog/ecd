<?php

/* GroepsactiviteitenAfsluiting Fixture generated on: 2015-11-22 08:11:01 : 1448175901 */
class GroepsactiviteitenAfsluitingFixture extends CakeTestFixture
{
    public $name = 'GroepsactiviteitenAfsluiting';

    public $fields = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'naam' => ['type' => 'string', 'null' => true, 'default' => null],
        'created' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'modified' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'indexes' => ['PRIMARY' => ['column' => 'id', 'unique' => 1]],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'],
    ];

    public $records = [
        [
            'id' => 1,
            'naam' => 'Lorem ipsum dolor sit amet',
            'created' => '2015-11-22 08:05:01',
            'modified' => '2015-11-22 08:05:01',
        ],
    ];
}
