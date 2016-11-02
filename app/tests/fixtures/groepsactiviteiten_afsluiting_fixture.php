<?php
/* GroepsactiviteitenAfsluiting Fixture generated on: 2015-11-22 08:11:01 : 1448175901 */
class GroepsactiviteitenAfsluitingFixture extends CakeTestFixture
{
    public $name = 'GroepsactiviteitenAfsluiting';

    public $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
        'naam' => array('type' => 'string', 'null' => true, 'default' => null),
        'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
        'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
        'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
        'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
    );

    public $records = array(
        array(
            'id' => 1,
            'naam' => 'Lorem ipsum dolor sit amet',
            'created' => '2015-11-22 08:05:01',
            'modified' => '2015-11-22 08:05:01'
        ),
    );
}
