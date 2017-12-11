<?php

/* Hi5EvaluatiesQuestion Fixture generated on: 2011-04-14 15:04:15 : 1302788835 */
class Hi5EvaluatiesQuestionFixture extends CakeTestFixture
{
    public $name = 'Hi5EvaluatiesQuestion';

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
