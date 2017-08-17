<?php

/* Contactsoort Fixture generated on: 2011-09-20 11:09:47 : 1316510087 */
class ContactsoortFixture extends CakeTestFixture
{
    public $name = 'Contactsoort';

    public $fields = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'text' => ['type' => 'string', 'null' => false, 'default' => null],
        'indexes' => ['PRIMARY' => ['column' => 'id', 'unique' => 1]],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'],
    ];

    public $records = [
        [
            'id' => 1,
            'text' => 'Lorem ipsum dolor sit amet',
        ],
    ];
}
