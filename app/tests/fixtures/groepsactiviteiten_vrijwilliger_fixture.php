<?php

/* GroepsactiviteitenVrijwilliger Fixture generated on: 2014-05-03 15:05:52 : 1399123972 */
class GroepsactiviteitenVrijwilligerFixture extends CakeTestFixture
{
    public $name = 'GroepsactiviteitenVrijwilliger';

    public $fields = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'groepsactiviteit_id' => ['type' => 'integer', 'null' => true, 'default' => null],
        'vrijwilliger_id' => ['type' => 'integer', 'null' => true, 'default' => null],
        'startdatum' => ['type' => 'date', 'null' => true, 'default' => null],
        'einddatum' => ['type' => 'date', 'null' => true, 'default' => null],
        'communicatie_methode' => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 50],
        'created' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'modified' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'indexes' => ['PRIMARY' => ['column' => 'id', 'unique' => 1]],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'],
    ];

    public $records = [
        [
            'id' => 1,
            'groepsactiviteit_id' => 1,
            'vrijwilliger_id' => 1,
            'startdatum' => '2014-05-03',
            'einddatum' => '2014-05-03',
            'communicatie_methode' => 'Lorem ipsum dolor sit amet',
            'created' => '2014-05-03 15:32:52',
            'modified' => '2014-05-03 15:32:52',
        ],
    ];
}
