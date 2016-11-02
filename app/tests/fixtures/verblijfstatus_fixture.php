<?php
/* Verblijfstatus Fixture generated on: 2010-08-17 15:08:47 : 1282050347 */
class VerblijfstatusFixture extends CakeTestFixture
{
    public $name = 'Verblijfstatus';

    public $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
        'naam' => array('type' => 'string', 'null' => false, 'default' => null),
        'datum_van' => array('type' => 'date', 'null' => false, 'default' => null),
        'datum_tot' => array('type' => 'date', 'null' => true, 'default' => null),
        'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
        'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
        'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
        'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
    );

    public $records = array(
        array(
            'id' => 1,
            'naam' => 'Lorem ipsum dolor sit amet',
            'datum_van' => '2010-08-17',
            'datum_tot' => '2010-08-17',
            'created' => '2010-08-17 15:05:47',
            'modified' => '2010-08-17 15:05:47'
        ),
    );
}
