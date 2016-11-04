<?php
/* Contactsoort Fixture generated on: 2011-09-20 11:09:47 : 1316510087 */
class ContactsoortFixture extends CakeTestFixture
{
    public $name = 'Contactsoort';

    public $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
        'text' => array('type' => 'string', 'null' => false, 'default' => null),
        'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
        'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
    );

    public $records = array(
        array(
            'id' => 1,
            'text' => 'Lorem ipsum dolor sit amet'
        ),
    );
}
