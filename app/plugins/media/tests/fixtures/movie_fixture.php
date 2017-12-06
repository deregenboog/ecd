<?php

class MovieFixture extends CakeTestFixture
{
    public $name = 'Movie';

    public $fields = [
            'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary', 'extra' => 'auto_increment', 'length' => 10],
            'title' => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 255],
            'director' => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 255],
            'indexes' => ['PRIMARY' => ['column' => 'id', 'unique' => 1]],
            ];

    public $records = [
                        [
                            'id' => 1,
                            'title' => 'Frost/Nixon',
                            'director' => 'Ron Howard',
                        ],
                        [
                            'id' => 2,
                            'title' => 'Entre les murs',
                            'director' => 'Laurent Cantet',
                        ],
                        [
                            'id' => 3,
                            'title' => 'Revanche',
                            'director' => 'Goetz Spielmann',
                        ],
                    ];
}
