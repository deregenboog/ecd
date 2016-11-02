<?php
/* IntakeVerslaving Fixture generated on: 2011-04-22 15:04:45 : 1303479885 */
class IntakeVerslavingFixture extends CakeTestFixture
{
    public $name = 'IntakeVerslaving';
    public $table = 'intakes_verslavingen';
    public $import = array( 'table' => 'intakes_verslavingen' );
    public $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
        'intake_id' => array('type' => 'integer', 'null' => false, 'default' => null),
        'verslaving_id' => array('type' => 'integer', 'null' => false, 'default' => null),
        'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
        'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
        'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
        'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
    );

    public $records = array(
        array(
            'id' => 1,
            'intake_id' => 1,
            'verslaving_id' => 1,
            'created' => '2011-04-22 15:44:45',
            'modified' => '2011-04-22 15:44:45'
        ),
    );
}
