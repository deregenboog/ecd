<?php

/* IzProject Fixture generated on: 2014-08-11 16:08:37 : 1407767257 */
class IzProjectFixture extends CakeTestFixture
{
    public $name = 'IzProject';

    public $fields = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'naam' => ['type' => 'string', 'null' => true, 'default' => null],
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
            'startdatum' => '2014-08-11',
            'einddatum' => '2014-08-11',
            'created' => '2014-08-11 16:27:37',
            'modified' => '2014-08-11 16:27:37',
        ],
    ];
}
