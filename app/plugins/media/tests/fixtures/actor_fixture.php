<?php

class ActorFixture extends CakeTestFixture
{
    public $name = 'Actor';

    public $fields = [
            'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary', 'extra' => 'auto_increment', 'length' => 10],
            'movie_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 10],
            'name' => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 255],
            'indexes' => ['PRIMARY' => ['column' => 'id', 'unique' => 1]],
            ];

    public $records = [
                        [
                            'id' => 1,
                            'movie_id' => 1,
                            'name' => 'Michael Sheen',
                        ],
                        [
                            'id' => 2,
                            'movie_id' => 1,
                            'name' => 'Frank Langella',
                        ],
                        [
                            'id' => 3,
                            'movie_id' => 2,
                            'name' => 'Nassim Amrabt',
                        ],
                    ];
}
