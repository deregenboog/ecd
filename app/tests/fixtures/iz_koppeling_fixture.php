<?php

/* IzKoppeling Fixture generated on: 2014-08-13 12:08:03 : 1407927183 */
class IzKoppelingFixture extends CakeTestFixture
{
    public $name = 'IzKoppeling';

    public $fields = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'iz_deelnemer_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'medewerker_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'start_datum' => ['type' => 'date', 'null' => true, 'default' => null],
        'afsluit_datum' => ['type' => 'date', 'null' => true, 'default' => null],
        'iz_vraagaanbod_id' => ['type' => 'integer', 'null' => true, 'default' => null],
        'iz_koppeling_id' => ['type' => 'integer', 'null' => true, 'default' => null],
        'koppeling_start_datum' => ['type' => 'date', 'null' => true, 'default' => null],
        'koppeling_eind_datum' => ['type' => 'date', 'null' => true, 'default' => null],
        'iz_eindekoppeling_id' => ['type' => 'integer', 'null' => true, 'default' => null],
        'koppeling_succesvol' => ['type' => 'boolean', 'null' => true, 'default' => null],
        'created' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'modified' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'indexes' => ['PRIMARY' => ['column' => 'id', 'unique' => 1]],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'],
    ];

    public $records = [
        [
            'id' => 1,
            'iz_deelnemer_id' => 1,
            'medewerker_id' => 1,
            'start_datum' => '2014-08-13',
            'afsluit_datum' => '2014-08-13',
            'iz_vraagaanbod_id' => 1,
            'iz_koppeling_id' => 1,
            'koppeling_start_datum' => '2014-08-13',
            'koppeling_eind_datum' => '2014-08-13',
            'iz_eindekoppeling_id' => 1,
            'koppeling_succesvol' => 1,
            'created' => '2014-08-13 12:53:03',
            'modified' => '2014-08-13 12:53:03',
        ],
    ];
}
