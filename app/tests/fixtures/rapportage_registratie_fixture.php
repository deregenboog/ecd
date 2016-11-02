<?php
/* Registratie Fixture generated on: 2010-08-17 15:08:47 : 1282050347 */
class RapportageRegistratieFixture extends CakeTestFixture
{
    public $name = 'RapportageRegistratie';
    public $table = 'registraties';

    public $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
        'locatie_id' => array('type' => 'integer', 'null' => false, 'default' => null),
        'klant_id' => array('type' => 'integer', 'null' => false, 'default' => null),
        'binnen' => array('type' => 'timestamp', 'null' => false, 'default' => 'CURRENT_TIMESTAMP'),
        'buiten' => array('type' => 'timestamp', 'null' => true, 'default' => null),
        'douche' => array('type' => 'integer', 'null' => false, 'default' => null),
        'kleding' => array('type' => 'integer', 'null' => false, 'default' => null),
        'maaltijd' => array('type' => 'integer', 'null' => false, 'default' => null),
        'activering' => array('type' => 'integer', 'null' => false, 'default' => null),
        'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
        'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
        'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
        'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
    );
    
    /*
     * two checked out registraties and two "active"
    */
    public $records = array(

    //registered on 2010-08-17:
    //4 unique clients
    //3 showers, 2 meals
    //times in: 4', 10', 60', 15'
        array(
            'id' => 1,
            'locatie_id' => 1,
            'klant_id' => 3,
            'binnen' => '2010-08-17 12:30:00',
            'buiten' => '2010-08-17 12:34:00',
            'douche' => -1,
            'kleding' => 1,
            'maaltijd' => 0,
            'activering' => 1,
            'created' => '2010-08-17 15:05:47',
            'modified' => '2010-08-17 15:05:47'
        ),
        array(
            'id' => 2,
            'locatie_id' => 1,
            'klant_id' => 4,
            'binnen' => '2010-08-17 12:30:00',
            'buiten' => '2010-08-17 12:40:00',
            'douche' => -1,
            'kleding' => 1,
            'maaltijd' => 1,
            'activering' => 1,
            'created' => '2010-08-17 15:05:47',
            'modified' => '2010-08-17 15:05:47'
        ),
        array(
            'id' => 3,
            'locatie_id' => 1,
            'klant_id' => 12,
            'binnen' => '2010-08-17 12:30:00',
            'buiten' => '2010-08-17 13:30:00',
            'douche' => 0,
            'kleding' => 1,
            'maaltijd' => 1,
            'activering' => 1,
            'created' => '2010-08-17 15:05:47',
            'modified' => '2010-08-17 15:05:47'
        ),
        array(
            'id' => 4,
            'locatie_id' => 1,
            'klant_id' => 11,
            'binnen' => '2010-08-17 12:30:00',
            'buiten' => '2010-08-17 12:45:00',
            'douche' => -1,
            'kleding' => 1,
            'maaltijd' => 0,
            'activering' => 1,
            'created' => '2010-08-17 15:05:47',
            'modified' => '2010-08-17 15:05:47'
        ),
    //registered on 2010-08-18:
    //4 unique clients (the same clients as on 2010-08-18
    //1 shower, 3 meals
    //times in: 24', 20', 60', 15'
        array(
            'id' => 5,
            'locatie_id' => 1,
            'klant_id' => 3,
            'binnen' => '2010-08-18 12:30:00',
            'buiten' => '2010-08-18 12:54:00',
            'douche' => 0,
            'kleding' => 1,
            'maaltijd' => 1,
            'activering' => 1,
            'created' => '2010-08-18 15:05:47',
            'modified' => '2010-08-18 15:05:47'
        ),
        array(
            'id' => 6,
            'locatie_id' => 1,
            'klant_id' => 4,
            'binnen' => '2010-08-18 12:30:00',
            'buiten' => '2010-08-18 12:50:00',
            'douche' => 0,
            'kleding' => 1,
            'maaltijd' => 1,
            'activering' => 1,
            'created' => '2010-08-18 15:05:47',
            'modified' => '2010-08-18 15:05:47'
        ),
        array(
            'id' => 7,
            'locatie_id' => 1,
            'klant_id' => 12,
            'binnen' => '2010-08-18 12:30:00',
            'buiten' => '2010-08-18 13:30:00',
            'douche' => 0,
            'kleding' => 1,
            'maaltijd' => 1,
            'activering' => 1,
            'created' => '2010-08-18 15:05:47',
            'modified' => '2010-08-18 15:05:47'
        ),
        array(
            'id' => 8,
            'locatie_id' => 1,
            'klant_id' => 11,
            'binnen' => '2010-08-18 12:30:00',
            'buiten' => '2010-08-18 12:45:00',
            'douche' => -1,
            'kleding' => 1,
            'maaltijd' => 0,
            'activering' => 1,
            'created' => '2010-08-18 15:05:47',
            'modified' => '2010-08-18 15:05:47'
        ),
    //registered on 2010-08-19:
    //3 unique clients (client 4 registered for two periods)
    //2 showers, 4 meals
    //times in: 48', 30' + 5h 7', 25'
        array(
            'id' => 9,
            'locatie_id' => 1,
            'klant_id' => 3,
            'binnen' => '2010-08-19 12:10:00',
            'buiten' => '2010-08-19 12:58:00',
            'douche' => 0,
            'kleding' => 1,
            'maaltijd' => 1,
            'activering' => 1,
            'created' => '2010-08-19 15:05:47',
            'modified' => '2010-08-19 15:05:47'
        ),
        array(
            'id' => 10,
            'locatie_id' => 1,
            'klant_id' => 4,
            'binnen' => '2010-08-19 12:20:00',
            'buiten' => '2010-08-19 12:50:00',
            'douche' => 0,
            'kleding' => 1,
            'maaltijd' => 1,
            'activering' => 1,
            'created' => '2010-08-19 15:05:47',
            'modified' => '2010-08-19 15:05:47'
        ),
        array(
            'id' => 11,
            'locatie_id' => 1,
            'klant_id' => 4,
            'binnen' => '2010-08-19 14:00:00',
            'buiten' => '2010-08-19 14:07:00',
            'douche' => -1,
            'kleding' => 1,
            'maaltijd' => 1,
            'activering' => 1,
            'created' => '2010-08-19 15:05:47',
            'modified' => '2010-08-19 15:05:47'
        ),
        array(
            'id' => 12,
            'locatie_id' => 1,
            'klant_id' => 11,
            'binnen' => '2010-08-19 12:30:00',
            'buiten' => '2010-08-19 12:55:00',
            'douche' => -1,
            'kleding' => 1,
            'maaltijd' => 1,
            'activering' => 1,
            'created' => '2010-08-19 15:05:47',
            'modified' => '2010-08-19 15:05:47'
        ),
    );
}
