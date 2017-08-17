<?php

/* IzDeelnemersIzIntervisiegroep Fixture generated on: 2014-08-20 15:08:16 : 1408540336 */
class IzDeelnemersIzIntervisiegroepFixture extends CakeTestFixture
{
    public $name = 'IzDeelnemersIzIntervisiegroep';

    public $fields = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'iz_deelnemer_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'iz_intervisiegroep_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'created' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'modified' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'indexes' => ['PRIMARY' => ['column' => 'id', 'unique' => 1]],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'],
    ];

    public $records = [
        [
            'id' => 1,
            'iz_deelnemer_id' => 1,
            'iz_intervisiegroep_id' => 1,
            'created' => '2014-08-20 15:12:16',
            'modified' => '2014-08-20 15:12:16',
        ],
    ];
}
