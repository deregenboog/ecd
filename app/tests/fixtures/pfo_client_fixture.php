<?php

/* PfoClient Fixture generated on: 2013-06-04 22:06:26 : 1370377766 */
class PfoClientFixture extends CakeTestFixture
{
    public $name = 'PfoClient';

    public $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'primary'),
        'roepnaam' => array('type' => 'string', 'null' => true, 'default' => null),
        'tussenvoegsel' => array('type' => 'string', 'null' => true, 'default' => null),
        'achternaam' => array('type' => 'string', 'null' => true, 'default' => null),
        'geslacht_id' => array('type' => 'integer', 'null' => true, 'default' => null),
        'geboortedatum' => array('type' => 'date', 'null' => true, 'default' => null),
        'adres' => array('type' => 'string', 'null' => true, 'default' => null),
        'postcode' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 50),
        'woonplaats' => array('type' => 'string', 'null' => true, 'default' => null),
        'telefoon' => array('type' => 'string', 'null' => true, 'default' => null),
        'telefoon_mobiel' => array('type' => 'string', 'null' => true, 'default' => null),
        'email' => array('type' => 'string', 'null' => true, 'default' => null),
        'notitie' => array('type' => 'text', 'null' => true, 'default' => null),
        'medewerker_id' => array('type' => 'integer', 'null' => true, 'default' => null),
        'groep' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 50),
        'pfo_clientencol' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 50),
        'dubbele_diagnose' => array('type' => 'boolean', 'null' => true, 'default' => null),
        'eerdere_hulpverlening' => array('type' => 'boolean', 'null' => true, 'default' => null),
        'via' => array('type' => 'text', 'null' => true, 'default' => null),
        'hulpverleners' => array('type' => 'text', 'null' => true, 'default' => null),
        'contacten' => array('type' => 'text', 'null' => true, 'default' => null),
        'begeleidings_formulier' => array('type' => 'date', 'null' => true, 'default' => null),
        'brief_huisarts' => array('type' => 'date', 'null' => true, 'default' => null),
        'evaluatie_formulier' => array('type' => 'date', 'null' => true, 'default' => null),
        'datum_afgesloten' => array('type' => 'date', 'null' => true, 'default' => null),
        'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
        'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
    );

    public $records = array(
        array(
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
        ),
    );
}
