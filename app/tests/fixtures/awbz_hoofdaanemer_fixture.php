<?php
/* AwbzHoofdaanemer Fixture generated on: 2011-03-25 11:03:25 : 1301050645 */
class AwbzHoofdaanemerFixture extends CakeTestFixture
{
    public $name = 'AwbzHoofdaanemer';

    public $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
        'klant_id' => array('type' => 'integer', 'null' => true, 'default' => null),
        'begindatum' => array('type' => 'date', 'null' => false, 'default' => null),
        'einddatum' => array('type' => 'date', 'null' => true, 'default' => null),
        'hoofdaannamer_id' => array('type' => 'integer', 'null' => true, 'default' => null),
        'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
        'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
        'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
        'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
    );

    public $records = array(
        array(
            'id' => 1,
            'klant_id' => 1,
            'begindatum' => '2011-03-25',
            'einddatum' => '2011-03-25',
            'hoofdaannamer_id' => 1,
            'created' => '2011-03-25 11:57:25',
            'modified' => '2011-03-25 11:57:25'
        ),
    );
}
