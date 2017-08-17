<?php

/* ZrmSetting Fixture generated on: 2013-11-26 17:11:39 : 1385484879 */
class ZrmSettingFixture extends CakeTestFixture
{
    public $name = 'ZrmSetting';

    public $fields = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'request_module' => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 50],
        'inkomen' => ['type' => 'boolean', 'null' => true, 'default' => null],
        'dagbesteding' => ['type' => 'boolean', 'null' => true, 'default' => null],
        'huisvesting' => ['type' => 'boolean', 'null' => true, 'default' => null],
        'gezinsrelaties' => ['type' => 'boolean', 'null' => true, 'default' => null],
        'fysieke_gezondheid' => ['type' => 'boolean', 'null' => true, 'default' => null],
        'verslaving' => ['type' => 'boolean', 'null' => true, 'default' => null],
        'adl_vaardigheden' => ['type' => 'boolean', 'null' => true, 'default' => null],
        'sociaal_netwerk' => ['type' => 'boolean', 'null' => true, 'default' => null],
        'maatschappelijke_participatie' => ['type' => 'boolean', 'null' => true, 'default' => null],
        'justitie' => ['type' => 'boolean', 'null' => true, 'default' => null],
        'created' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'modified' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'indexes' => ['PRIMARY' => ['column' => 'id', 'unique' => 1]],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'],
    ];

    public $records = [
        [
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
            'modified' => '2013-11-26 17:54:39',
        ],
    ];
}
