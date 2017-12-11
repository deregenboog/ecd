<?php

/* PfoClientenVerslag Fixture generated on: 2013-06-08 11:06:54 : 1370683914 */
class PfoClientenVerslagFixture extends CakeTestFixture
{
    public $name = 'PfoClientenVerslag';

    public $fields = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'pfo_client_id' => ['type' => 'integer', 'null' => true, 'default' => null],
        'pfo_verslag_id' => ['type' => 'integer', 'null' => true, 'default' => null],
        'created' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'modified' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'indexes' => ['PRIMARY' => ['column' => 'id', 'unique' => 1]],
        'tableParameters' => ['charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $records = [
        [
            'id' => 1,
            'pfo_client_id' => 1,
            'pfo_verslag_id' => 1,
            'created' => '2013-06-08 11:31:54',
            'modified' => '2013-06-08 11:31:54',
        ],
    ];
}
