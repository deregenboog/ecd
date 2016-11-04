<?php

/* GroepsactiviteitenGroep Fixture generated on: 2014-05-03 15:05:16 : 1399123516 */
class GroepsactiviteitenGroepFixture extends CakeTestFixture
{
    public $name = 'GroepsactiviteitenGroep';

    public $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
        'naam' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100),
        'startdatum' => array('type' => 'date', 'null' => true, 'default' => null),
        'einddatum' => array('type' => 'date', 'null' => true, 'default' => null),
        'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
        'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
        'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
        'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
    );

    public $records = array(
        array(
            'id' => 1,
            'naam' => 'Lorem ipsum dolor sit amet',
            'startdatum' => '2014-05-03',
            'einddatum' => '2014-05-03',
            'created' => '2014-05-03 15:25:16',
            'modified' => '2014-05-03 15:25:16',
        ),
    );
}
