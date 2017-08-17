<?php

/* PfoAardRelatie Fixture generated on: 2013-06-09 19:06:59 : 1370799659 */
class PfoAardRelatieFixture extends CakeTestFixture
{
    public $name = 'PfoAardRelatie';

    public $fields = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'name' => ['type' => 'string', 'null' => true, 'default' => null],
        'created' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'modified' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'indexes' => ['PRIMARY' => ['column' => 'id', 'unique' => 1]],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'],
    ];

    public $records = [
        [
            'id' => 1,
            'name' => 'Lorem ipsum dolor sit amet',
            'created' => '2013-06-09 19:40:59',
            'modified' => '2013-06-09 19:40:59',
        ],
    ];
}
