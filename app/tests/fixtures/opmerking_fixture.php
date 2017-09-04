<?php

/* Opmerking Fixture generated on: 2010-10-05 14:10:23 : 1286283203 */
class OpmerkingFixture extends CakeTestFixture
{
    public $name = 'Opmerking';
    public $import = ['model' => 'Opmerking'];

    public $fields = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'categorie_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'klant_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'beschrijving' => ['type' => 'string', 'null' => false, 'default' => null],
        'gezien' => ['type' => 'boolean', 'null' => false, 'default' => 0],
        'created' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'modified' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'indexes' => ['PRIMARY' => ['column' => 'id', 'unique' => 1]],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'],
    ];

    public $records = [
        [
            'id' => 1,
            'categorie_id' => 1,
            'klant_id' => 1,
            'beschrijving' => 'Lorem ipsum dolor',
            'gezien' => 1,
            'created' => '2010-04-12 12:23:46',
            'modified' => '2010-04-12 12:23:46',
        ],
        [
            'id' => 2,
            'categorie_id' => 1,
            'klant_id' => 1,
            'beschrijving' => 'Lorem ipsum dolor',
            'gezien' => 0,
            'created' => '2010-04-12 12:23:46',
            'modified' => '2010-04-12 12:23:46',
        ],
        [
            'id' => 3,
            'categorie_id' => 1,
            'klant_id' => 3,
            'beschrijving' => 'Lorem ipsum dolor',
            'gezien' => 1,
            'created' => '2010-04-12 12:23:46',
            'modified' => '2010-04-12 12:23:46',
        ],
        [
            'id' => 4,
            'categorie_id' => 1,
            'klant_id' => 3,
            'beschrijving' => 'Lorem ipsum dolor',
            'gezien' => 1,
            'created' => '2010-04-12 12:23:46',
            'modified' => '2010-04-12 12:23:46',
        ],
    ];
}
