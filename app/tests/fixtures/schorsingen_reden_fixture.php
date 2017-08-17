<?php

/* SchorsingenReden Fixture generated on: 2011-03-18 11:03:45 : 1300444905 */
class SchorsingenRedenFixture extends CakeTestFixture
{
    public $name = 'SchorsingenReden';
    public $table = 'schorsingen_redenen';

    public $fields = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'schorsing_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'reden_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'created' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'modified' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'indexes' => ['PRIMARY' => ['column' => 'id', 'unique' => 1]],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'],
    ];

    public $records = [
        [
            'id' => 1,
            'schorsing_id' => 1,
            'reden_id' => 1,
            'created' => '2011-03-18 11:41:45',
            'modified' => '2011-03-18 11:41:45',
        ],
    ];
}
