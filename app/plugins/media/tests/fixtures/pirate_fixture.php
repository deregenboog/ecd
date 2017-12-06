<?php

class PirateFixture extends CakeTestFixture
{
    public $name = 'Pirate';

    public $fields = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary', 'extra' => 'auto_increment', 'length' => 10],
        'name' => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 255],
        'group' => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 255],
        'model' => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 255],
    ];

    public $records = [
        [
            'id' => 1,
            'name' => 'George Lowther',
            'group' => 'atlantic',
            'model' => 'unknown',
    ], ];
}
