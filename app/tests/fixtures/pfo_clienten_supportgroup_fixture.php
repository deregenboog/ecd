<?php

/* PfoClientenSupportgroup Fixture generated on: 2013-06-06 21:06:20 : 1370545220 */
class PfoClientenSupportgroupFixture extends CakeTestFixture
{
    public $name = 'PfoClientenSupportgroup';

    public $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
        'pfo_client_id' => array('type' => 'integer', 'null' => false, 'default' => null),
        'pfo_supportgroup_client_id' => array('type' => 'integer', 'null' => false, 'default' => null),
        'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
        'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
    );

    public $records = array(
        array(
            'id' => 1,
            'pfo_client_id' => 1,
            'pfo_supportgroup_client_id' => 1,
        ),
    );
}
