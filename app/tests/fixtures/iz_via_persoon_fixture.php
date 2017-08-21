<?php

/* IzViaPersoon Fixture generated on: 2014-12-12 15:12:22 : 1418393602 */
class IzViaPersoonFixture extends CakeTestFixture
{
    public $name = 'IzViaPersoon';

    public $fields = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'naam' => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 50],
        'created' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'modified' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'indexes' => ['PRIMARY' => ['column' => 'id', 'unique' => 1]],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'],
    ];

    public $records = [
        [
            'id' => 1,
            'naam' => 'Lorem ipsum dolor sit amet',
            'created' => '2014-12-12 15:13:22',
            'modified' => '2014-12-12 15:13:22',
        ],
    ];
}
