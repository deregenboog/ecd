<?php

/* Verblijfstatus Fixture generated on: 2010-08-17 15:08:47 : 1282050347 */
class VerblijfstatusFixture extends CakeTestFixture
{
    public $name = 'Verblijfstatus';

    public $fields = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'naam' => ['type' => 'string', 'null' => false, 'default' => null],
        'datum_van' => ['type' => 'date', 'null' => false, 'default' => null],
        'datum_tot' => ['type' => 'date', 'null' => true, 'default' => null],
        'created' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'modified' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'indexes' => ['PRIMARY' => ['column' => 'id', 'unique' => 1]],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'],
    ];

    public $records = [
        [
            'id' => 1,
            'naam' => 'Lorem ipsum dolor sit amet',
            'datum_van' => '2010-08-17',
            'datum_tot' => '2010-08-17',
            'created' => '2010-08-17 15:05:47',
            'modified' => '2010-08-17 15:05:47',
        ],
    ];
}
