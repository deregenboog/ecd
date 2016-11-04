<?php

/* IzIntake Fixture generated on: 2014-08-12 13:08:16 : 1407844036 */
class IzIntakeFixture extends CakeTestFixture
{
    public $name = 'IzIntake';

    public $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
        'iz_deelnemer_id' => array('type' => 'integer', 'null' => false, 'default' => null),
        'medewerker_id' => array('type' => 'integer', 'null' => true, 'default' => null),
        'intake_datum' => array('type' => 'date', 'null' => true, 'default' => null),
        'gesprek_verslag' => array('type' => 'text', 'null' => true, 'default' => null),
        'ondernemen' => array('type' => 'boolean', 'null' => true, 'default' => null),
        'overdag' => array('type' => 'boolean', 'null' => true, 'default' => null),
        'ontmoeten' => array('type' => 'boolean', 'null' => true, 'default' => null),
        'regelzaken' => array('type' => 'boolean', 'null' => true, 'default' => null),
        'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
        'modifed' => array('type' => 'datetime', 'null' => true, 'default' => null),
        'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
        'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
    );

    public $records = array(
        array(
            'id' => 1,
            'iz_deelnemer_id' => 1,
            'medewerker_id' => 1,
            'intake_datum' => '2014-08-12',
            'gesprek_verslag' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'ondernemen' => 1,
            'overdag' => 1,
            'ontmoeten' => 1,
            'regelzaken' => 1,
            'created' => '2014-08-12 13:47:16',
            'modifed' => '2014-08-12 13:47:16',
        ),
    );
}
