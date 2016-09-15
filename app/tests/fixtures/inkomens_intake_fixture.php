<?php
/* InkomensIntake Fixture generated on: 2010-08-17 15:08:45 : 1282050345 */
class InkomensIntakeFixture extends CakeTestFixture {
    var $name = 'InkomensIntake';

    var $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
        'intake_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
        'inkomen_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
        'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
        'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
        'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
        'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
    );

    var $records = array(
        array(
            'id' => 1,
            'intake_id' => 1,
            'inkomen_id' => 1,
            'created' => '2010-08-17 15:05:45',
            'modified' => '2010-08-17 15:05:45'
        ),
    );
}
?>