<?php
/* Stadsdeel Fixture generated on: 2014-05-05 16:05:28 : 1399300888 */
class StadsdeelFixture extends CakeTestFixture
{
    public $name = 'Stadsdeel';

    public $fields = array(
        'postcode' => array('type' => 'string', 'null' => false, 'default' => null, 'key' => 'primary'),
        'stadsdeel' => array('type' => 'string', 'null' => false, 'default' => null),
        'indexes' => array('PRIMARY' => array('column' => 'postcode', 'unique' => 1)),
        'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
    );

    public $records = array(
        array(
            'postcode' => 'Lorem ipsum dolor sit amet',
            'stadsdeel' => 'Lorem ipsum dolor sit amet'
        ),
    );
}
