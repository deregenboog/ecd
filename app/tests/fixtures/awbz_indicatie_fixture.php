<?php

/* AwbzIndicatie Fixture generated on: 2011-04-19 15:04:50 : 1303221050 */
class AwbzIndicatieFixture extends CakeTestFixture
{
    public $name = 'AwbzIndicatie';
    public $import = array('model' => 'AwbzIndicatie');

    public $records = array(
        array(
            'id' => 1,
            'klant_id' => 1,
            'begindatum' => '2011-04-05',
            'einddatum' => '2011-06-23',
            'begeleiding_per_week' => 4,
            'activering_per_week' => 10,
            'created' => '2011-04-14 10:41:32',
            'modified' => '2011-04-14 11:09:06',
            'aangevraagd_id' => null,
            'hoofdaannemer_id' => 7,
        ),
        array(
            'id' => 2,
            'klant_id' => 2,
            'begindatum' => '2011-04-05',
            'einddatum' => '2011-04-29',
            'begeleiding_per_week' => 4,
            'activering_per_week' => 2,
            'created' => '2011-04-14 10:45:32',
            'modified' => '2011-04-14 10:45:32',
            'aangevraagd_id' => null,
            'hoofdaannemer_id' => 4,
        ),
    );
}
