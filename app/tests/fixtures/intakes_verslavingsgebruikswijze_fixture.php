<?php

/* IntakesVerslavingsgebruikswijze Fixture generated on: 2011-03-18 11:03:30 : 1300443870 */
class IntakesVerslavingsgebruikswijzeFixture extends CakeTestFixture
{
    public $name = 'IntakesVerslavingsgebruikswijze';
    public $table = 'intakes_verslavingsgebruikswijzen';

    public $fields = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'intake_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'verslavingsgebruikswijze_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'created' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'modified' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'indexes' => ['PRIMARY' => ['column' => 'id', 'unique' => 1]],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'],
    ];

    public $records = [
        [
            'id' => 6,
            'intake_id' => 361,
            'verslavingsgebruikswijze_id' => 3,
            'created' => null,
            'modified' => null,
        ],
        [
            'id' => 7,
            'intake_id' => 4701,
            'verslavingsgebruikswijze_id' => 3,
            'created' => null,
            'modified' => null,
        ],
        [
            'id' => 8,
            'intake_id' => 1012,
            'verslavingsgebruikswijze_id' => 4,
            'created' => null,
            'modified' => null,
        ],
        [
            'id' => 9,
            'intake_id' => 1012,
            'verslavingsgebruikswijze_id' => 7,
            'created' => null,
            'modified' => null,
        ],
        [
            'id' => 16,
            'intake_id' => 4705,
            'verslavingsgebruikswijze_id' => 4,
            'created' => null,
            'modified' => null,
        ],
        [
            'id' => 17,
            'intake_id' => 4705,
            'verslavingsgebruikswijze_id' => 5,
            'created' => null,
            'modified' => null,
        ],
        [
            'id' => 18,
            'intake_id' => 4705,
            'verslavingsgebruikswijze_id' => 6,
            'created' => null,
            'modified' => null,
        ],
        [
            'id' => 19,
            'intake_id' => 1394,
            'verslavingsgebruikswijze_id' => 3,
            'created' => null,
            'modified' => null,
        ],
        [
            'id' => 22,
            'intake_id' => 4709,
            'verslavingsgebruikswijze_id' => 4,
            'created' => null,
            'modified' => null,
        ],
        [
            'id' => 23,
            'intake_id' => 4709,
            'verslavingsgebruikswijze_id' => 6,
            'created' => null,
            'modified' => null,
        ],
    ];
}
