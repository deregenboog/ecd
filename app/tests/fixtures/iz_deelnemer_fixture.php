<?php

/* IzDeelnemer Fixture generated on: 2014-08-04 10:08:17 : 1407139877 */
class IzDeelnemerFixture extends CakeTestFixture
{
    public $name = 'IzDeelnemer';

    public $fields = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'model' => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 50],
        'foreign_key' => ['type' => 'integer', 'null' => false, 'default' => null],
        'datum_aanmelding' => ['type' => 'date', 'null' => true, 'default' => null],
        'binnengekomen_via' => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 50],
        'organisatie' => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 100],
        'naam_aanmelder' => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 100],
        'email_aanmelder' => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 100],
        'telefoon_aanmelder' => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 100],
        'notitie' => ['type' => 'text', 'null' => true, 'default' => null],
        'created' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'modified' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'indexes' => ['PRIMARY' => ['column' => 'id', 'unique' => 1]],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'],
    ];

    public $records = [
        [
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
            'modified' => '2014-08-04 10:11:17',
        ],
    ];
}
