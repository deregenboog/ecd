<?php

/* IzEindekoppeling Fixture generated on: 2014-08-13 14:08:36 : 1407933996 */
class IzEindekoppelingFixture extends CakeTestFixture
{
    public $name = 'IzEindekoppeling';

    public $fields = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'naam' => ['type' => 'string', 'null' => true, 'default' => null],
        'created' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'modified' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'indexes' => ['PRIMARY' => ['column' => 'id', 'unique' => 1]],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'],
    ];

    public $records = [
        [
            'id' => 1,
            'naam' => 'Lorem ipsum dolor sit amet',
            'created' => '2014-08-13 14:46:36',
            'modified' => '2014-08-13 14:46:36',
        ],
    ];
}
