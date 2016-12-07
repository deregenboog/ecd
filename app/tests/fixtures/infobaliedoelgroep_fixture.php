<?php

/* Infobaliedoelgroep Fixture generated on: 2011-12-30 10:12:09 : 1325236149 */
class InfobaliedoelgroepFixture extends CakeTestFixture
{
    public $name = 'Infobaliedoelgroep';

    public $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
        'naam' => array('type' => 'string', 'null' => false, 'default' => null),
        'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
        'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
    );

    public $records = array(
        array(
            'id' => 1,
            'naam' => 'Lorem ipsum dolor sit amet',
        ),
    );
}
