<?php

/* Klantinventarisatie Fixture generated on: 2014-05-08 17:05:34 : 1399563274 */
class KlantinventarisatieFixture extends CakeTestFixture
{
    public $name = 'Klantinventarisatie';

    public $fields = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'klant_id' => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'inventarisatie_id' => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'doorverwijzer_id' => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'datum' => ['type' => 'date', 'null' => false, 'default' => null],
        'created' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'modified' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'indexes' => ['PRIMARY' => ['column' => 'id', 'unique' => 1]],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'],
    ];

    public $records = [
        [
            'id' => 1,
            'klant_id' => 1,
            'inventarisatie_id' => 1,
            'doorverwijzer_id' => 1,
            'datum' => '2014-05-08',
            'created' => '2014-05-08 17:34:34',
            'modified' => '2014-05-08 17:34:34',
        ],
    ];
}
