<?php
/* GroepsactiviteitenVerslag Fixture generated on: 2014-05-06 18:05:29 : 1399392869 */
class GroepsactiviteitenVerslagFixture extends CakeTestFixture
{
    public $name = 'GroepsactiviteitenVerslag';

    public $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
        'model' => array('type' => 'string', 'null' => false, 'default' => null),
        'foreign_key' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'),
        'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
        'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
        'medewerker_id' => array('type' => 'integer', 'null' => true, 'default' => null),
        'opmerking' => array('type' => 'text', 'null' => true, 'default' => null),
        'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'foreign_key_model_idx' => array('column' => array('foreign_key', 'model'), 'unique' => 0)),
        'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
    );

    public $records = array(
        array(
            'id' => 1,
            'model' => 'Lorem ipsum dolor sit amet',
            'foreign_key' => 1,
            'created' => '2014-05-06 18:14:29',
            'modified' => '2014-05-06 18:14:29',
            'medewerker_id' => 1,
            'opmerking' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.'
        ),
    );
}
