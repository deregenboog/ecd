<?php

/* IzVraagaanbod Fixture generated on: 2014-08-15 15:08:28 : 1408111048 */
class IzVraagaanbodFixture extends CakeTestFixture
{
    public $name = 'IzVraagaanbod';

    public $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
        'naam' => array('type' => 'string', 'null' => true, 'default' => null),
        'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
        'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
        'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
        'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
    );

    public $records = array(
        array(
            'id' => 1,
            'naam' => 'Lorem ipsum dolor sit amet',
            'created' => '2014-08-15 15:57:28',
            'modified' => '2014-08-15 15:57:28',
        ),
    );
}
