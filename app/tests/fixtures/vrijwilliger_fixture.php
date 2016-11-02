<?php
/* Vrijwilliger Fixture generated on: 2014-05-03 15:05:15 : 1399123095 */
class VrijwilligerFixture extends CakeTestFixture
{
    public $name = 'Vrijwilliger';

    public $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
        'voornaam' => array('type' => 'string', 'null' => true, 'default' => null),
        'tussenvoegsel' => array('type' => 'string', 'null' => true, 'default' => null),
        'achternaam' => array('type' => 'string', 'null' => true, 'default' => null),
        'roepnaam' => array('type' => 'string', 'null' => true, 'default' => null),
        'geslacht_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
        'geboortedatum' => array('type' => 'date', 'null' => true, 'default' => null, 'key' => 'index'),
        'land_id' => array('type' => 'integer', 'null' => false, 'default' => '1'),
        'nationaliteit_id' => array('type' => 'integer', 'null' => false, 'default' => '1'),
        'BSN' => array('type' => 'string', 'null' => false, 'default' => null),
        'medewerker_id' => array('type' => 'integer', 'null' => false, 'default' => null),
        'adres' => array('type' => 'string', 'null' => true, 'default' => null),
        'postcode' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 6),
        'werkgebied' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 20),
        'plaats' => array('type' => 'string', 'null' => true, 'default' => null),
        'email' => array('type' => 'string', 'null' => true, 'default' => null),
        'mobiel' => array('type' => 'string', 'null' => true, 'default' => null),
        'telefoon' => array('type' => 'string', 'null' => true, 'default' => null),
        'opmerking' => array('type' => 'text', 'null' => true, 'default' => null),
        'geen_post' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 4),
        'disabled' => array('type' => 'boolean', 'null' => true, 'default' => '0'),
        'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
        'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
        'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'idx_klanten_geboortedatum' => array('column' => 'geboortedatum', 'unique' => 0)),
        'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
    );

    public $records = array(
        array(
            'id' => 1,
            'voornaam' => 'Lorem ipsum dolor sit amet',
            'tussenvoegsel' => 'Lorem ipsum dolor sit amet',
            'achternaam' => 'Lorem ipsum dolor sit amet',
            'roepnaam' => 'Lorem ipsum dolor sit amet',
            'geslacht_id' => 1,
            'geboortedatum' => '2014-05-03',
            'land_id' => 1,
            'nationaliteit_id' => 1,
            'BSN' => 'Lorem ipsum dolor sit amet',
            'medewerker_id' => 1,
            'adres' => 'Lorem ipsum dolor sit amet',
            'postcode' => 'Lore',
            'werkgebied' => 'Lorem ipsum dolor ',
            'plaats' => 'Lorem ipsum dolor sit amet',
            'email' => 'Lorem ipsum dolor sit amet',
            'mobiel' => 'Lorem ipsum dolor sit amet',
            'telefoon' => 'Lorem ipsum dolor sit amet',
            'opmerking' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'geen_post' => 1,
            'disabled' => 1,
            'created' => '2014-05-03 15:18:15',
            'modified' => '2014-05-03 15:18:15'
        ),
    );
}
