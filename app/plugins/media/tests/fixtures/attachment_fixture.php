<?php

class AttachmentFixture extends CakeTestFixture
{
    public $name = 'Attachment';

    public $fields = [
            'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary', 'extra' => 'auto_increment', 'length' => 10],
            'model' => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 255],
            'foreign_key' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 10],
            'dirname' => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 255],
            'basename' => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 255],
            'checksum' => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 255],
            'group' => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 255],
            'alternative' => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 50],
            'indexes' => ['PRIMARY' => ['column' => 'id', 'unique' => 1]],
            ];

    public $records = [];
}
