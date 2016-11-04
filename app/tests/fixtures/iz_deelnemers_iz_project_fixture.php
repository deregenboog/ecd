<?php

/* IzDeelnemersIzProject Fixture generated on: 2014-08-11 16:08:17 : 1407767057 */
class IzDeelnemersIzProjectFixture extends CakeTestFixture
{
    public $name = 'IzDeelnemersIzProject';

    public $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
        'iz_deelnemer_id' => array('type' => 'integer', 'null' => false, 'default' => null),
        'iz_project_id' => array('type' => 'integer', 'null' => false, 'default' => null),
        'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
        'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
        'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
        'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
    );

    public $records = array(
        array(
            'id' => 1,
            'iz_deelnemer_id' => 1,
            'iz_project_id' => 1,
            'created' => '2014-08-11 16:24:17',
            'modified' => '2014-08-11 16:24:17',
        ),
    );
}
