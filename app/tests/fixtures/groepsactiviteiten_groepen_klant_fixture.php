<?php

/* GroepsactiviteitenGroepenKlant Fixture generated on: 2014-05-04 13:05:16 : 1399203556 */
class GroepsactiviteitenGroepenKlantFixture extends CakeTestFixture
{
    public $name = 'GroepsactiviteitenGroepenKlant';

    public $fields = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'groepsactiviteiten_groep_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'klant_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'groepsactiviteiten_reden_id' => ['type' => 'integer', 'null' => true, 'default' => null],
        'startdatum' => ['type' => 'date', 'null' => true, 'default' => null],
        'einddatum' => ['type' => 'date', 'null' => true, 'default' => null],
        'communicatie_email' => ['type' => 'boolean', 'null' => true, 'default' => null],
        'communicatie_telefoon' => ['type' => 'boolean', 'null' => true, 'default' => null],
        'communicatie_post' => ['type' => 'boolean', 'null' => true, 'default' => null],
        'created' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'modified' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'indexes' => ['PRIMARY' => ['column' => 'id', 'unique' => 1]],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'],
    ];

    public $records = [
        [
            'id' => 1,
            'groepsactiviteiten_groep_id' => 1,
            'klant_id' => 1,
            'groepsactiviteiten_reden_id' => 1,
            'startdatum' => '2014-05-04',
            'einddatum' => '2014-05-04',
            'communicatie_email' => 1,
            'communicatie_telefoon' => 1,
            'communicatie_post' => 1,
            'created' => '2014-05-04 13:39:16',
            'modified' => '2014-05-04 13:39:16',
        ],
    ];
}
