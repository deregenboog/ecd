<?php
/* IzProject Fixture generated on: 2014-08-11 16:08:37 : 1407767257 */
class IzProjectFixture extends CakeTestFixture
{
    public $name = 'IzProject';

    public $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
        'naam' => array('type' => 'string', 'null' => true, 'default' => null),
        'startdatum' => array('type' => 'date', 'null' => true, 'default' => null),
        'einddatum' => array('type' => 'date', 'null' => true, 'default' => null),
        'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
        'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
        'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
        'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
    );

    public $records = array(
        array(
            'id' => 1,
            'naam' => 'Lorem ipsum dolor sit amet',
            'startdatum' => '2014-08-11',
            'einddatum' => '2014-08-11',
            'created' => '2014-08-11 16:27:37',
            'modified' => '2014-08-11 16:27:37'
        ),
    );
}
