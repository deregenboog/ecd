<?php

/* PfoClient Fixture generated on: 2013-06-04 22:06:26 : 1370377766 */
class PfoClientFixture extends CakeTestFixture
{
    public $name = 'PfoClient';

    public $fields = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'primary'],
        'roepnaam' => ['type' => 'string', 'null' => true, 'default' => null],
        'tussenvoegsel' => ['type' => 'string', 'null' => true, 'default' => null],
        'achternaam' => ['type' => 'string', 'null' => true, 'default' => null],
        'geslacht_id' => ['type' => 'integer', 'null' => true, 'default' => null],
        'geboortedatum' => ['type' => 'date', 'null' => true, 'default' => null],
        'adres' => ['type' => 'string', 'null' => true, 'default' => null],
        'postcode' => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 50],
        'woonplaats' => ['type' => 'string', 'null' => true, 'default' => null],
        'telefoon' => ['type' => 'string', 'null' => true, 'default' => null],
        'telefoon_mobiel' => ['type' => 'string', 'null' => true, 'default' => null],
        'email' => ['type' => 'string', 'null' => true, 'default' => null],
        'notitie' => ['type' => 'text', 'null' => true, 'default' => null],
        'medewerker_id' => ['type' => 'integer', 'null' => true, 'default' => null],
        'groep' => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 50],
        'pfo_clientencol' => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 50],
        'dubbele_diagnose' => ['type' => 'boolean', 'null' => true, 'default' => null],
        'eerdere_hulpverlening' => ['type' => 'boolean', 'null' => true, 'default' => null],
        'via' => ['type' => 'text', 'null' => true, 'default' => null],
        'hulpverleners' => ['type' => 'text', 'null' => true, 'default' => null],
        'contacten' => ['type' => 'text', 'null' => true, 'default' => null],
        'begeleidings_formulier' => ['type' => 'date', 'null' => true, 'default' => null],
        'brief_huisarts' => ['type' => 'date', 'null' => true, 'default' => null],
        'evaluatie_formulier' => ['type' => 'date', 'null' => true, 'default' => null],
        'datum_afgesloten' => ['type' => 'date', 'null' => true, 'default' => null],
        'indexes' => ['PRIMARY' => ['column' => 'id', 'unique' => 1]],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'],
    ];

    public $records = [
        [
            'id' => 1,
            'roepnaam' => 'Lorem ipsum dolor sit amet',
            'tussenvoegsel' => 'Lorem ipsum dolor sit amet',
            'achternaam' => 'Lorem ipsum dolor sit amet',
            'geslacht_id' => 1,
            'geboortedatum' => '2013-06-04',
            'adres' => 'Lorem ipsum dolor sit amet',
            'postcode' => 'Lorem ipsum dolor sit amet',
            'woonplaats' => 'Lorem ipsum dolor sit amet',
            'telefoon' => 'Lorem ipsum dolor sit amet',
            'telefoon_mobiel' => 'Lorem ipsum dolor sit amet',
            'email' => 'Lorem ipsum dolor sit amet',
            'notitie' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'medewerker_id' => 1,
            'groep' => 'Lorem ipsum dolor sit amet',
            'pfo_clientencol' => 'Lorem ipsum dolor sit amet',
            'dubbele_diagnose' => 1,
            'eerdere_hulpverlening' => 1,
            'via' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'hulpverleners' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'contacten' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'begeleidings_formulier' => '2013-06-04',
            'brief_huisarts' => '2013-06-04',
            'evaluatie_formulier' => '2013-06-04',
            'datum_afgesloten' => '2013-06-04',
        ],
    ];
}
