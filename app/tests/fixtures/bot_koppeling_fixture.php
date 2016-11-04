<?php
/* BotKoppeling Fixture generated on: 2014-03-13 17:03:41 : 1394728001 */
class BotKoppelingFixture extends CakeTestFixture
{
    public $name = 'BotKoppeling';

    public $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
        'medewerker_id' => array('type' => 'integer', 'null' => false, 'default' => null),
        'start_datum' => array('type' => 'date', 'null' => true, 'default' => null),
        'eind_datum' => array('type' => 'date', 'null' => true, 'default' => null),
        'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
        'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
        'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
        'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
    );

    public $records = array(
        array(
            'id' => 1,
            'medewerker_id' => 1,
            'start_datum' => '2014-03-13',
            'eind_datum' => '2014-03-13',
            'created' => '2014-03-13 17:26:41',
            'modified' => '2014-03-13 17:26:41'
        ),
    );
}
