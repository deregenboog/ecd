<?php
/* IzOntstaanContact Fixture generated on: 2014-12-12 15:12:17 : 1418392817 */
class IzOntstaanContactFixture extends CakeTestFixture
{
    public $name = 'IzOntstaanContact';

    public $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
        'naam' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 50),
        'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
        'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
        'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
        'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
    );

    public $records = array(
        array(
            'id' => 1,
            'naam' => 'Lorem ipsum dolor sit amet',
            'created' => '2014-12-12 15:00:17',
            'modified' => '2014-12-12 15:00:17'
        ),
    );
}
