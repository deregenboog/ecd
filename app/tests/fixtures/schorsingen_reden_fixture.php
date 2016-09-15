<?php
/* SchorsingenReden Fixture generated on: 2011-03-18 11:03:45 : 1300444905 */
class SchorsingenRedenFixture extends CakeTestFixture {
    var $name = 'SchorsingenReden';
    var $table = 'schorsingen_redenen';

    var $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
        'schorsing_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
        'reden_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
        'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
        'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
        'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
        'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
    );

    var $records = array(
        array(
            'id' => 1,
            'schorsing_id' => 1,
            'reden_id' => 1,
            'created' => '2011-03-18 11:41:45',
            'modified' => '2011-03-18 11:41:45'
        ),
    );
}
