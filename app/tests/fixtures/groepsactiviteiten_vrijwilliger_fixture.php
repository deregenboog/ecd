<?php
/* GroepsactiviteitenVrijwilliger Fixture generated on: 2014-05-03 15:05:52 : 1399123972 */
class GroepsactiviteitenVrijwilligerFixture extends CakeTestFixture
{
    public $name = 'GroepsactiviteitenVrijwilliger';

    public $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
        'groepsactiviteit_id' => array('type' => 'integer', 'null' => true, 'default' => null),
        'vrijwilliger_id' => array('type' => 'integer', 'null' => true, 'default' => null),
        'startdatum' => array('type' => 'date', 'null' => true, 'default' => null),
        'einddatum' => array('type' => 'date', 'null' => true, 'default' => null),
        'communicatie_methode' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 50),
        'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
        'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
        'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
        'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
    );

    public $records = array(
        array(
            'id' => 1,
            'groepsactiviteit_id' => 1,
            'vrijwilliger_id' => 1,
            'startdatum' => '2014-05-03',
            'einddatum' => '2014-05-03',
            'communicatie_methode' => 'Lorem ipsum dolor sit amet',
            'created' => '2014-05-03 15:32:52',
            'modified' => '2014-05-03 15:32:52'
        ),
    );
}
