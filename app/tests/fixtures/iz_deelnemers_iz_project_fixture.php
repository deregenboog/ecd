<?php

/* IzDeelnemersIzProject Fixture generated on: 2014-08-11 16:08:17 : 1407767057 */
class IzDeelnemersIzProjectFixture extends CakeTestFixture
{
    public $name = 'IzDeelnemersIzProject';

    public $fields = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'iz_deelnemer_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'iz_project_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'created' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'modified' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'indexes' => ['PRIMARY' => ['column' => 'id', 'unique' => 1]],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'],
    ];

    public $records = [
        [
            'id' => 1,
            'iz_deelnemer_id' => 1,
            'iz_project_id' => 1,
            'created' => '2014-08-11 16:24:17',
            'modified' => '2014-08-11 16:24:17',
        ],
    ];
}
