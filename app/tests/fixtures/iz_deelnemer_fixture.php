<?php
/* IzDeelnemer Fixture generated on: 2014-08-04 10:08:17 : 1407139877 */
class IzDeelnemerFixture extends CakeTestFixture
{
    public $name = 'IzDeelnemer';

    public $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
        'model' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 50),
        'foreign_key' => array('type' => 'integer', 'null' => false, 'default' => null),
        'datum_aanmelding' => array('type' => 'date', 'null' => true, 'default' => null),
        'binnengekomen_via' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 50),
        'organisatie' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100),
        'naam_aanmelder' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100),
        'email_aanmelder' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100),
        'telefoon_aanmelder' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100),
        'notitie' => array('type' => 'text', 'null' => true, 'default' => null),
        'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
        'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
        'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
        'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
    );

    public $records = array(
        array(
            'id' => 1,
            'model' => 'Lorem ipsum dolor sit amet',
            'foreign_key' => 1,
            'datum_aanmelding' => '2014-08-04',
            'binnengekomen_via' => 'Lorem ipsum dolor sit amet',
            'organisatie' => 'Lorem ipsum dolor sit amet',
            'naam_aanmelder' => 'Lorem ipsum dolor sit amet',
            'email_aanmelder' => 'Lorem ipsum dolor sit amet',
            'telefoon_aanmelder' => 'Lorem ipsum dolor sit amet',
            'notitie' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'created' => '2014-08-04 10:11:17',
            'modified' => '2014-08-04 10:11:17'
        ),
    );
}
