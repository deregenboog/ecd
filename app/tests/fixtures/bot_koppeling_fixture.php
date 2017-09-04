<?php

/* BotKoppeling Fixture generated on: 2014-03-13 17:03:41 : 1394728001 */
class BotKoppelingFixture extends CakeTestFixture
{
    public $name = 'BotKoppeling';

    public $fields = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'medewerker_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'start_datum' => ['type' => 'date', 'null' => true, 'default' => null],
        'eind_datum' => ['type' => 'date', 'null' => true, 'default' => null],
        'created' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'modified' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'indexes' => ['PRIMARY' => ['column' => 'id', 'unique' => 1]],
        'tableParameters' => ['charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $records = [
        [
            'id' => 1,
            'medewerker_id' => 1,
            'start_datum' => '2014-03-13',
            'eind_datum' => '2014-03-13',
            'created' => '2014-03-13 17:26:41',
            'modified' => '2014-03-13 17:26:41',
        ],
    ];
}
