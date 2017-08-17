<?php

/* Klant Fixture generated on: 2013-10-16 12:10:55 : 1381920055 */
class KlantFixture extends CakeTestFixture
{
    public $name = 'Klant';

    public $fields = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'MezzoID' => ['type' => 'integer', 'null' => false, 'default' => null],
        'voornaam' => ['type' => 'string', 'null' => true, 'default' => null],
        'tussenvoegsel' => ['type' => 'string', 'null' => true, 'default' => null],
        'achternaam' => ['type' => 'string', 'null' => true, 'default' => null],
        'roepnaam' => ['type' => 'string', 'null' => true, 'default' => null],
        'geslacht_id' => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'geboortedatum' => ['type' => 'date', 'null' => true, 'default' => null, 'key' => 'index'],
        'land_id' => ['type' => 'integer', 'null' => false, 'default' => '1'],
        'nationaliteit_id' => ['type' => 'integer', 'null' => false, 'default' => '1'],
        'BSN' => ['type' => 'string', 'null' => false, 'default' => null],
        'medewerker_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'laatste_TBC_controle' => ['type' => 'date', 'null' => true, 'default' => null],
        'created' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'modified' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'laste_intake_id' => ['type' => 'integer', 'null' => true, 'default' => null],
        'disabled' => ['type' => 'boolean', 'null' => true, 'default' => '0'],
        'laatste_registratie_id' => ['type' => 'integer', 'null' => true, 'default' => null],
        'doorverwijzen_naar_amoc' => ['type' => 'boolean', 'null' => true, 'default' => '0'],
        'merged_id' => ['type' => 'integer', 'null' => true, 'default' => null],
        'indexes' => ['PRIMARY' => ['column' => 'id', 'unique' => 1], 'idx_klanten_geboortedatum' => ['column' => 'geboortedatum', 'unique' => 0]],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'],
    ];

    public $records = [
        [
            'id' => 1,
            'MezzoID' => 1,
            'voornaam' => 'My',
            'tussenvoegsel' => 'R.',
            'achternaam' => 'Visitor',
            'roepnaam' => 'DaReal',
            'geslacht_id' => 1,
            'geboortedatum' => '2013-10-16',
            'land_id' => 5001,
            'nationaliteit_id' => 1,
            'BSN' => '123456789ABC',
            'medewerker_id' => 1,
            'laatste_TBC_controle' => '2013-09-16',
            'created' => '2013-10-16 12:40:55',
            'modified' => '2013-10-16 12:40:55',
            'laste_intake_id' => 2,
            'disabled' => 0,
            'laatste_registratie_id' => 3,
            'doorverwijzen_naar_amoc' => 1,
            'merged_id' => 1,
        ],
    ];
}
