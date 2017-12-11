<?php

/* IntakeVerslaving Fixture generated on: 2011-04-22 15:04:45 : 1303479885 */
class IntakeVerslavingFixture extends CakeTestFixture
{
    public $name = 'IntakeVerslaving';
    public $table = 'intakes_verslavingen';
    public $import = ['table' => 'intakes_verslavingen'];
    public $fields = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'intake_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'verslaving_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'created' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'modified' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'indexes' => ['PRIMARY' => ['column' => 'id', 'unique' => 1]],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'],
    ];

    public $records = [
        [
            'id' => 1,
            'intake_id' => 1,
            'verslaving_id' => 1,
            'created' => '2011-04-22 15:44:45',
            'modified' => '2011-04-22 15:44:45',
        ],
    ];
}
