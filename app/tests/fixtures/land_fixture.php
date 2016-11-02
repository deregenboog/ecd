<?php
/* Land Fixture generated on: 2013-10-16 15:10:30 : 1381930350 */
class LandFixture extends CakeTestFixture
{
    public $name = 'Land';

    public $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
        'land' => array('type' => 'string', 'null' => false, 'default' => null),
        'AFK2' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 5),
        'AFK3' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 5),
        'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
        'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
        'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
        'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
    );

    public $records = array(
        array(
            'id' => 0,
            'land' => 'Onbekend',
            'AFK2' => '',
            'AFK3' => '',
            'created' => null,
            'modified' => null
        ),
        array(
            'id' => 5001,
            'land' => 'Canada',
            'AFK2' => '',
            'AFK3' => '',
            'created' => null,
            'modified' => null
        ),
        array(
            'id' => 5002,
            'land' => 'Frankrijk',
            'AFK2' => '',
            'AFK3' => '',
            'created' => null,
            'modified' => null
        ),
        array(
            'id' => 5003,
            'land' => 'Zwitserland',
            'AFK2' => '',
            'AFK3' => '',
            'created' => null,
            'modified' => null
        ),
        array(
            'id' => 5004,
            'land' => 'Rhodesië',
            'AFK2' => '',
            'AFK3' => '',
            'created' => null,
            'modified' => null
        ),
        array(
            'id' => 5005,
            'land' => 'Malawi',
            'AFK2' => '',
            'AFK3' => '',
            'created' => null,
            'modified' => null
        ),
        array(
            'id' => 5006,
            'land' => 'Cuba',
            'AFK2' => '',
            'AFK3' => '',
            'created' => null,
            'modified' => null
        ),
        array(
            'id' => 5007,
            'land' => 'Suriname',
            'AFK2' => '',
            'AFK3' => '',
            'created' => null,
            'modified' => null
        ),
        array(
            'id' => 5008,
            'land' => 'Tunesië',
            'AFK2' => '',
            'AFK3' => '',
            'created' => null,
            'modified' => null
        ),
        array(
            'id' => 5009,
            'land' => 'Oostenrijk',
            'AFK2' => '',
            'AFK3' => '',
            'created' => null,
            'modified' => null
        ),
    );
}
