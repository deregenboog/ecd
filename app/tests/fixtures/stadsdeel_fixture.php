<?php

/* Stadsdeel Fixture generated on: 2014-05-05 16:05:28 : 1399300888 */
class StadsdeelFixture extends CakeTestFixture
{
    public $name = 'Stadsdeel';

    public $fields = [
        'postcode' => ['type' => 'string', 'null' => false, 'default' => null, 'key' => 'primary'],
        'stadsdeel' => ['type' => 'string', 'null' => false, 'default' => null],
        'indexes' => ['PRIMARY' => ['column' => 'postcode', 'unique' => 1]],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'],
    ];

    public $records = [
        [
            'postcode' => 'Lorem ipsum dolor sit amet',
            'stadsdeel' => 'Lorem ipsum dolor sit amet',
        ],
    ];
}
