<?php

/* ZrmReport Fixture generated on: 2013-11-26 11:11:23 : 1385462603 */
class ZrmReportFixture extends CakeTestFixture
{
    public $name = 'ZrmReport';

    public $fields = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'model' => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 50],
        'foreign_key' => ['type' => 'integer', 'null' => false, 'default' => null],
        'request_module' => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 50],
        'inkomen' => ['type' => 'integer', 'null' => true, 'default' => null],
        'dagbesteding' => ['type' => 'integer', 'null' => true, 'default' => null],
        'huisvesting' => ['type' => 'integer', 'null' => true, 'default' => null],
        'gezinsrelaties' => ['type' => 'integer', 'null' => true, 'default' => null],
        'geestelijke_gezondheid' => ['type' => 'integer', 'null' => true, 'default' => null],
        'fysieke_gezondheid' => ['type' => 'integer', 'null' => true, 'default' => null],
        'verslaving' => ['type' => 'integer', 'null' => true, 'default' => null],
        'adl_vaardigheden' => ['type' => 'integer', 'null' => true, 'default' => null],
        'sociaal_netwerk' => ['type' => 'integer', 'null' => true, 'default' => null],
        'maatschappelijke_participatie' => ['type' => 'integer', 'null' => true, 'default' => null],
        'justitie' => ['type' => 'integer', 'null' => true, 'default' => null],
        'indexes' => ['PRIMARY' => ['column' => 'id', 'unique' => 1]],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'],
    ];

    public $records = [
        [
            'id' => 1,
            'model' => 'Lorem ipsum dolor sit amet',
            'foreign_key' => 1,
            'module' => 'Lorem ipsum dolor sit amet',
            'inkomen' => 1,
            'dagbesteding' => 1,
            'huisvesting' => 1,
            'gezinsrelaties' => 1,
            'geestelijke_gezondheid' => 1,
            'fysieke_gezondheid' => 1,
            'verslaving' => 1,
            'adl_vaardigheden' => 1,
            'sociaal_netwerk' => 1,
            'maatschappelijke_participatie' => 1,
            'justitie' => 1,
        ],
    ];
}
