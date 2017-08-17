<?php

/* IzAfsluiting Fixture generated on: 2014-08-13 14:08:13 : 1407934033 */
class IzAfsluitingFixture extends CakeTestFixture
{
    public $name = 'IzAfsluiting';

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
            'created' => '2014-08-13 14:47:13',
            'modified' => '2014-08-13 14:47:13',
        ],
    ];
}
