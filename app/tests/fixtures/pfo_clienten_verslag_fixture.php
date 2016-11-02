<?php
/* PfoClientenVerslag Fixture generated on: 2013-06-08 11:06:54 : 1370683914 */
class PfoClientenVerslagFixture extends CakeTestFixture
{
    public $name = 'PfoClientenVerslag';

    public $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
        'pfo_client_id' => array('type' => 'integer', 'null' => true, 'default' => null),
        'pfo_verslag_id' => array('type' => 'integer', 'null' => true, 'default' => null),
        'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
        'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
        'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
        'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
    );

    public $records = array(
        array(
            'id' => 1,
            'pfo_client_id' => 1,
            'pfo_verslag_id' => 1,
            'created' => '2013-06-08 11:31:54',
            'modified' => '2013-06-08 11:31:54'
        ),
    );
}
