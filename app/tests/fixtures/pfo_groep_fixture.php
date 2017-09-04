<?php

/* PfoGroep Fixture generated on: 2013-06-09 19:06:10 : 1370799490 */
class PfoGroepFixture extends CakeTestFixture
{
    public $name = 'PfoGroep';

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
            'created' => '2013-06-09 19:38:10',
            'modified' => '2013-06-09 19:38:10',
        ],
    ];
}
