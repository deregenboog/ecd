<?php

/* GroepsactiviteitenKlant Fixture generated on: 2014-05-04 13:05:53 : 1399203713 */
class GroepsactiviteitenKlantFixture extends CakeTestFixture
{
    public $name = 'GroepsactiviteitenKlant';

    public $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
        'groepsactiviteit_id' => array('type' => 'integer', 'null' => true, 'default' => null),
        'klant_id' => array('type' => 'integer', 'null' => true, 'default' => null),
        'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
        'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
        'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
        'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
    );

    public $records = array(
        array(
            'id' => 1,
            'groepsactiviteit_id' => 1,
            'klant_id' => 1,
            'created' => '2014-05-04 13:41:53',
            'modified' => '2014-05-04 13:41:53',
        ),
    );
}
