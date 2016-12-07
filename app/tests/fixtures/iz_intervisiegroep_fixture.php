<?php

/* IzIntervisiegroep Fixture generated on: 2014-08-05 16:08:58 : 1407248458 */
class IzIntervisiegroepFixture extends CakeTestFixture
{
    public $name = 'IzIntervisiegroep';

    public $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
        'naam' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100),
        'startdatum' => array('type' => 'integer', 'null' => true, 'default' => null),
        'einddatum' => array('type' => 'integer', 'null' => true, 'default' => null),
        'medewerker_id' => array('type' => 'integer', 'null' => true, 'default' => null),
        'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
        'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
        'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
        'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
    );

    public $records = array(
        array(
            'id' => 1,
            'naam' => 'Lorem ipsum dolor sit amet',
            'startdatum' => 1,
            'einddatum' => 1,
            'medewerker_id' => 1,
            'created' => '2014-08-05 16:20:58',
            'modified' => '2014-08-05 16:20:58',
        ),
    );
}
