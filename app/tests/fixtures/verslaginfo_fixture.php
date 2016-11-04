<?php
/* Verslaginfo Fixture generated on: 2011-04-22 09:04:14 : 1303459034 */
class VerslaginfoFixture extends CakeTestFixture
{
    public $name = 'Verslaginfo';

    public $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
        'klant_id' => array('type' => 'integer', 'null' => false, 'default' => null),
        'advocaat' => array('type' => 'string', 'null' => false, 'default' => null),
        'contact' => array('type' => 'text', 'null' => false, 'default' => null),
        'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
        'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
    );

    public $records = array(
        array(
            'id' => 1,
            'klant_id' => 1,
            'advocaat' => 'Lorem ipsum dolor sit amet',
            'contact' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.'
        ),
    );
}
