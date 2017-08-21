<?php

/* AwbzHoofdaanemer Fixture generated on: 2011-03-25 11:03:25 : 1301050645 */
class AwbzHoofdaanemerFixture extends CakeTestFixture
{
    public $name = 'AwbzHoofdaanemer';

    public $fields = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'klant_id' => ['type' => 'integer', 'null' => true, 'default' => null],
        'begindatum' => ['type' => 'date', 'null' => false, 'default' => null],
        'einddatum' => ['type' => 'date', 'null' => true, 'default' => null],
        'hoofdaannamer_id' => ['type' => 'integer', 'null' => true, 'default' => null],
        'created' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'modified' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'indexes' => ['PRIMARY' => ['column' => 'id', 'unique' => 1]],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'],
    ];

    public $records = [
        [
            'id' => 1,
            'klant_id' => 1,
            'begindatum' => '2011-03-25',
            'einddatum' => '2011-03-25',
            'hoofdaannamer_id' => 1,
            'created' => '2011-03-25 11:57:25',
            'modified' => '2011-03-25 11:57:25',
        ],
    ];
}
