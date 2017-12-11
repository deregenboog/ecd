<?php

/* Schorsing Fixture generated on: 2010-08-17 15:08:47 : 1282050347 */
class SchorsingFixture extends CakeTestFixture
{
    public $name = 'Schorsing';

    public $fields = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'datum_van' => ['type' => 'date', 'null' => false, 'default' => null],
        'datum_tot' => ['type' => 'date', 'null' => false, 'default' => null],
        'locatie_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'klant_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'remark' => ['type' => 'string', 'null' => false, 'default' => null],
        'created' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'modified' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'indexes' => ['PRIMARY' => ['column' => 'id', 'unique' => 1]],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'],
    ];

    public $records = [
        [ //expired schorsing
            'id' => 1,
            'datum_van' => '2001-08-17',
            'datum_tot' => '2001-08-17',
            'locatie_id' => 1,
            'klant_id' => 1,
            'remark' => 'Lorem ipsum dolor sit amet',
            'created' => '2010-08-17 15:05:47',
            'modified' => '2010-08-17 15:05:47',
        ],
        [ //active schorsing
            'id' => 2,
            'datum_van' => '2010-08-17',
            'datum_tot' => '3000-01-01',
            'locatie_id' => 1,
            'klant_id' => 1,
            'remark' => 'Lorem ipsum dolor sit amet',
            'created' => '2010-08-17 15:05:47',
            'modified' => '2010-08-17 15:05:47',
        ],
        [ //active schorsing
            'id' => 3,
            'datum_van' => '2010-08-17',
            'datum_tot' => '2010-08-01',
            'locatie_id' => 33,
            'klant_id' => 666,
            'remark' => 'Lorem ipsum dolor sit amet',
            'created' => '2010-08-17 15:05:47',
            'modified' => '2010-08-17 15:05:47',
        ],
        [ //active schorsing
            'id' => 4,
            'datum_van' => '2010-08-17',
            'datum_tot' => '3333-11-11',
            'locatie_id' => 33,
            'klant_id' => 666,
            'remark' => 'Lorem ipsum dolor sit amet',
            'created' => '2010-08-17 15:05:47',
            'modified' => '2010-08-17 15:05:47',
        ],
    ];
}
