<?php

/* BackOnTrack Fixture generated on: 2013-09-25 12:09:54 : 1380106554 */
class BackOnTrackFixture extends CakeTestFixture
{
    public $name = 'BackOnTrack';

    public $fields = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'klant_id' => ['type' => 'integer', 'null' => true, 'default' => null],
        'startdatum' => ['type' => 'date', 'null' => true, 'default' => null, 'key' => 'index'],
        'einddatum' => ['type' => 'date', 'null' => true, 'default' => null],
        'created' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'modified' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'indexes' => ['PRIMARY' => ['column' => 'id', 'unique' => 1], 'idx_back_on_tracks_dates' => ['column' => ['startdatum', 'einddatum'], 'unique' => 0]],
        'tableParameters' => ['charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $records = [
        [
            'id' => 1,
            'klant_id' => 1,
            'startdatum' => '2013-09-25',
            'einddatum' => '2013-09-25',
            'created' => '2013-09-25 12:55:54',
            'modified' => '2013-09-25 12:55:54',
        ],
    ];
}
