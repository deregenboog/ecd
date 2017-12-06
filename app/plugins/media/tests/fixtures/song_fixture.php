<?php

class SongFixture extends CakeTestFixture
{
    public $name = 'Song';

    public $fields = [
            'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary', 'extra' => 'auto_increment', 'length' => 10],
            'dirname' => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 255],
            'basename' => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 255],
            'checksum' => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 255],
            'indexes' => ['PRIMARY' => ['column' => 'id', 'unique' => 1]],
            ];

    public $records = [
                        [
                        'id' => 1,
                        'dirname' => 'static/img',
                        'basename' => 'image-png.png',
                        'checksum' => '7f9af648b511f2c83b1744f42254983f',
                        ],
                        [
                        'id' => 2,
                        'dirname' => 'static/img',
                        'basename' => 'image-jpg.jpg',
                        'checksum' => '1920c29e7fbe4d1ad2f9173ef4591133',
                        ],
                        [
                        'id' => 3,
                        'dirname' => 'static/txt',
                        'basename' => 'text-plain.txt',
                        'checksum' => '3f3f58abd4209b4c87be51018fe5a0c6',
                        ],
                        [
                        'id' => 4,
                        'dirname' => 'static/txt',
                        'basename' => 'not-existent.txt',
                        'checksum' => null,
                        ],
                    ];
}
