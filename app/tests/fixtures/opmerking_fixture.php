<?php
/* Opmerking Fixture generated on: 2010-10-05 14:10:23 : 1286283203 */
class OpmerkingFixture extends CakeTestFixture {
	var $name = 'Opmerking';
	var $import = array('model' => 'Opmerking');

    var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'categorie_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'klant_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'beschrijving' => array('type' => 'string', 'null' => false, 'default' => NULL),
        'gezien' => array('type' => 'boolean', 'null' => false, 'default' => 0),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	
	var $records = array(
	    array(
    		'id' => 1,
    	    'categorie_id' => 1,
    	    'klant_id' => 1,
    	    'beschrijving' => 'Lorem ipsum dolor',
    	    'gezien' => 1,
    		'created' => '2010-04-12 12:23:46',
    		'modified' => '2010-04-12 12:23:46'
    	),
	    array(
    		'id' => 2,
    	    'categorie_id' => 1,
    	    'klant_id' => 1,
    	    'beschrijving' => 'Lorem ipsum dolor',
    	    'gezien' => 0,
    		'created' => '2010-04-12 12:23:46',
    		'modified' => '2010-04-12 12:23:46'
    	),
    	array(
    		'id' => 3,
    	    'categorie_id' => 1,
    	    'klant_id' => 3,
    	    'beschrijving' => 'Lorem ipsum dolor',
    	    'gezien' => 1,
    		'created' => '2010-04-12 12:23:46',
    		'modified' => '2010-04-12 12:23:46'
    	),
	    array(
    		'id' => 4,
    	    'categorie_id' => 1,
    	    'klant_id' => 3,
    	    'beschrijving' => 'Lorem ipsum dolor',
    	    'gezien' => 1,
    		'created' => '2010-04-12 12:23:46',
    		'modified' => '2010-04-12 12:23:46'
    	),
	);
}
?>