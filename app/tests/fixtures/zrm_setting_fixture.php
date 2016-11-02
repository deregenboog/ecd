<?php
/* ZrmSetting Fixture generated on: 2013-11-26 17:11:39 : 1385484879 */
class ZrmSettingFixture extends CakeTestFixture
{
    public $name = 'ZrmSetting';

    public $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
        'request_module' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 50),
        'inkomen' => array('type' => 'boolean', 'null' => true, 'default' => null),
        'dagbesteding' => array('type' => 'boolean', 'null' => true, 'default' => null),
        'huisvesting' => array('type' => 'boolean', 'null' => true, 'default' => null),
        'gezinsrelaties' => array('type' => 'boolean', 'null' => true, 'default' => null),
        'fysieke_gezondheid' => array('type' => 'boolean', 'null' => true, 'default' => null),
        'verslaving' => array('type' => 'boolean', 'null' => true, 'default' => null),
        'adl_vaardigheden' => array('type' => 'boolean', 'null' => true, 'default' => null),
        'sociaal_netwerk' => array('type' => 'boolean', 'null' => true, 'default' => null),
        'maatschappelijke_participatie' => array('type' => 'boolean', 'null' => true, 'default' => null),
        'justitie' => array('type' => 'boolean', 'null' => true, 'default' => null),
        'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
        'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
        'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
        'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
    );

    public $records = array(
        array(
            'id' => 1,
            'request_module' => 'Lorem ipsum dolor sit amet',
            'inkomen' => 1,
            'dagbesteding' => 1,
            'huisvesting' => 1,
            'gezinsrelaties' => 1,
            'fysieke_gezondheid' => 1,
            'verslaving' => 1,
            'adl_vaardigheden' => 1,
            'sociaal_netwerk' => 1,
            'maatschappelijke_participatie' => 1,
            'justitie' => 1,
            'created' => '2013-11-26 17:54:39',
            'modified' => '2013-11-26 17:54:39'
        ),
    );
}
