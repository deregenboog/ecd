<?php

/* Contactjournal Fixture generated on: 2011-04-19 17:04:39 : 1303225539 */
class ContactjournalFixture extends CakeTestFixture
{
    public $name = 'Contactjournal';

    public $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
        'klant_id' => array('type' => 'integer', 'null' => false, 'default' => null),
        'medewerker_id' => array('type' => 'integer', 'null' => false, 'default' => null),
        'datum' => array('type' => 'date', 'null' => false, 'default' => null),
        'text' => array('type' => 'text', 'null' => false, 'default' => null),
        'is_tb' => array('type' => 'boolean', 'null' => false, 'default' => null),
        'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
        'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
        'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
        'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
    );

    public $records = array(
        array(
            'id' => 1,
            'klant_id' => 1,
            'medewerker_id' => 1,
            'datum' => '2011-04-19',
            'text' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'is_tb' => 1,
            'created' => '2011-04-19 17:05:39',
            'modified' => '2011-04-19 17:05:39',
        ),
    );
}
