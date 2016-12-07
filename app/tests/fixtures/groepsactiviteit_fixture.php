<?php

/* Groepsactiviteit Fixture generated on: 2014-05-03 15:05:30 : 1399122870 */
class GroepsactiviteitFixture extends CakeTestFixture
{
    public $name = 'Groepsactiviteit';

    public $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
        'groepsactiviteiten_groep_id' => array('type' => 'integer', 'null' => false, 'default' => null),
        'naam' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100),
        'datum' => array('type' => 'date', 'null' => true, 'default' => null),
        'time' => array('type' => 'time', 'null' => true, 'default' => null),
        'afgesloten' => array('type' => 'boolean', 'null' => true, 'default' => null),
        'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
        'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
        'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
        'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
    );

    public $records = array(
        array(
            'id' => 1,
            'groepsactiviteiten_groep_id' => 1,
            'naam' => 'Lorem ipsum dolor sit amet',
            'datum' => '2014-05-03',
            'time' => '15:14:30',
            'afgesloten' => 1,
            'created' => '2014-05-03 15:14:30',
            'modified' => '2014-05-03 15:14:30',
        ),
    );
}
