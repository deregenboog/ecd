<?php

/* InkomensIntake Fixture generated on: 2010-08-17 15:08:45 : 1282050345 */
class InkomensIntakeFixture extends CakeTestFixture
{
    public $name = 'InkomensIntake';

    public $fields = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'intake_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'inkomen_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'created' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'modified' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'indexes' => ['PRIMARY' => ['column' => 'id', 'unique' => 1]],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'],
    ];

    public $records = [
        [
            'id' => 1,
            'intake_id' => 1,
            'inkomen_id' => 1,
            'created' => '2010-08-17 15:05:45',
            'modified' => '2010-08-17 15:05:45',
        ],
    ];
}
