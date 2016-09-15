<?php 
/* SVN FILE: $Id$ */
/* App schema generated on: 2016-04-02 00:04:39 : 1459549899*/
class AppSchema extends CakeSchema {
	var $name = 'App';

	function before($event = array()) {
		return true;
	}

	function after($event = array()) {
	}

	var $attachments = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'primary'),
		'model' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'foreign_key' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36),
		'dirname' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'basename' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'checksum' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'group' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'alternative' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50),
		'title' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'user_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'is_active' => array('type' => 'boolean', 'null' => true, 'default' => '1'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $awbz_hoofdaannemers = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'klant_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'key' => 'index'),
		'begindatum' => array('type' => 'date', 'null' => false, 'default' => NULL),
		'einddatum' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'hoofdaannemer_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'key' => 'index'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'idx_awbz_hoofdaannemers_klant_id' => array('column' => 'klant_id', 'unique' => 0), 'idx_awbz_hoofdaannemers_hoofdaannemer_id' => array('column' => 'hoofdaannemer_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $awbz_indicaties = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'klant_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'key' => 'index'),
		'begindatum' => array('type' => 'date', 'null' => false, 'default' => NULL),
		'einddatum' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'begeleiding_per_week' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 5),
		'activering_per_week' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 5),
		'hoofdaannemer_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'aangevraagd_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 1),
		'aangevraagd_datum' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'aangevraagd_niet' => array('type' => 'boolean', 'null' => true, 'default' => '0'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'idx_awbz_indicaties_klant_id' => array('column' => 'klant_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $awbz_intakes = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'klant_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'key' => 'index'),
		'medewerker_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'datum_intake' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'verblijfstatus_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'postadres' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'postcode' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 6),
		'woonplaats' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'verblijf_in_NL_sinds' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'verblijf_in_amsterdam_sinds' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'legitimatie_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'legitimatie_nummer' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'legitimatie_geldig_tot' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'verslavingsfrequentie_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'verslavingsperiode_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'woonsituatie_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'verwachting_dienstaanbod' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'toekomstplannen' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'opmerking_andere_instanties' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'medische_achtergrond' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'locatie1_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'locatie2_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'indruk' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'doelgroep' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'verslaving_overig' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'inkomen_overig' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'informele_zorg' => array('type' => 'boolean', 'null' => true, 'default' => '0'),
		'dagbesteding' => array('type' => 'boolean', 'null' => true, 'default' => '0'),
		'inloophuis' => array('type' => 'boolean', 'null' => true, 'default' => '0'),
		'hulpverlening' => array('type' => 'boolean', 'null' => true, 'default' => '0'),
		'mag_gebruiken' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'primaireproblematiek_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'primaireproblematieksfrequentie_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'primaireproblematieksperiode_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'eerste_gebruik' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'locatie3_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'infobaliedoelgroep_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'idx_awbz_intakes_klant_id' => array('column' => 'klant_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $awbz_intakes_primaireproblematieksgebruikswijzen = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'awbz_intake_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'primaireproblematieksgebruikswijze_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $awbz_intakes_verslavingen = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'awbz_intake_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'verslaving_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $awbz_intakes_verslavingsgebruikswijzen = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'awbz_intake_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'verslavingsgebruikswijze_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $back_on_tracks = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'klant_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'startdatum' => array('type' => 'date', 'null' => true, 'default' => NULL, 'key' => 'index'),
		'einddatum' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'intakedatum' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'idx_back_on_tracks_dates' => array('column' => array('startdatum', 'einddatum'), 'unique' => 0)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);
	var $bedrijfitems = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'bedrijfsector_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $bedrijfsectoren = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $bot_koppelingen = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'medewerker_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'back_on_track_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'startdatum' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'einddatum' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);
	var $bot_verslagen = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'contact_type' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50),
		'verslag' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'medewerker_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'klant_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'idx_bto_verslagen_klant_id' => array('column' => 'klant_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $categorieen = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'naam' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $contactjournals = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'klant_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'medewerker_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'datum' => array('type' => 'date', 'null' => false, 'default' => NULL),
		'text' => array('type' => 'text', 'null' => false, 'default' => NULL),
		'is_tb' => array('type' => 'boolean', 'null' => false, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'idx_contactjournals_klant_id' => array('column' => 'klant_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $contactsoorts = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'text' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $doorverwijzers = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'naam' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'startdatum' => array('type' => 'date', 'null' => false, 'default' => NULL),
		'einddatum' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'type' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $gd27 = array(
		'naam' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50),
		'voornaam' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50),
		'achternaam' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50),
		'geboortedatum' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'klant_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'db_voornaam' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50),
		'db_achternaam' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50),
		'roepnaam' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50),
		'land' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'nationaliteit' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'woonsituatie' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'inschrijving' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'key' => 'primary'),
		'idd' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'indexes' => array('PRIMARY' => array('column' => 'idd', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
	);
	var $geslachten = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'afkorting' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'volledig' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $groepsactiviteiten = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'groepsactiviteiten_groep_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'naam' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100),
		'datum' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'time' => array('type' => 'time', 'null' => true, 'default' => NULL),
		'afgesloten' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $groepsactiviteiten_afsluitingen = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'naam' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $groepsactiviteiten_groepen = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'naam' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
		'startdatum' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'einddatum' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'activiteiten_registreren' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'werkgebied' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 20),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $groepsactiviteiten_groepen_klanten = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'groepsactiviteiten_groep_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'klant_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'groepsactiviteiten_reden_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'startdatum' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'einddatum' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'communicatie_email' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'communicatie_telefoon' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'communicatie_post' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $groepsactiviteiten_groepen_vrijwilligers = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'groepsactiviteiten_groep_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'vrijwilliger_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'groepsactiviteiten_reden_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'startdatum' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'einddatum' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'communicatie_email' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'communicatie_telefoon' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'communicatie_post' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $groepsactiviteiten_intakes = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'model' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'foreign_key' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'groepsactiviteiten_afsluiting_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'medewerker_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'gespreksverslag' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'ondernemen' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'overdag' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'ontmoeten' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'regelzaken' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'informele_zorg' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 4),
		'dagbesteding' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 4),
		'inloophuis' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 4),
		'hulpverlening' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 4),
		'gezin_met_kinderen' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'intakedatum' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'afsluitdatum' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'groepsactiviteiten_intakes_foreign_key_model_idx' => array('column' => array('foreign_key', 'model'), 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $groepsactiviteiten_klanten = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'groepsactiviteit_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'klant_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'afmeld_status' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $groepsactiviteiten_redenen = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'naam' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $groepsactiviteiten_verslagen = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'model' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'foreign_key' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'medewerker_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'opmerking' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'foreign_key_model_idx' => array('column' => array('foreign_key', 'model'), 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $groepsactiviteiten_vrijwilligers = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'groepsactiviteit_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'vrijwilliger_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'afmeld_status' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $hi5_answer_types = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'answer_type' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $hi5_answers = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'answer' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'hi5_question_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'hi5_answer_type_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $hi5_evaluatie_paragraphs = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'text' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $hi5_evaluatie_questions = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'hi5_evaluatie_paragraph_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'text' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $hi5_evaluaties = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'klant_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'medewerker_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'datumevaluatie' => array('type' => 'date', 'null' => false, 'default' => NULL),
		'werkproject' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'aantal_dagdelen' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'startdatumtraject' => array('type' => 'date', 'null' => false, 'default' => NULL),
		'datum_intake' => array('type' => 'date', 'null' => false, 'default' => NULL),
		'verslagvan' => array('type' => 'date', 'null' => false, 'default' => NULL),
		'verslagtm' => array('type' => 'date', 'null' => false, 'default' => NULL),
		'extraanwezigen' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'opmerkingen_overige' => array('type' => 'text', 'null' => false, 'default' => NULL),
		'afspraken_afgelopen' => array('type' => 'text', 'null' => false, 'default' => NULL),
		'watdoejij' => array('type' => 'text', 'null' => false, 'default' => NULL),
		'watdoetb' => array('type' => 'text', 'null' => false, 'default' => NULL),
		'watdoewb' => array('type' => 'text', 'null' => false, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'idx_hi5_evaluaties_klant_id' => array('column' => 'klant_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $hi5_evaluaties_hi5_evaluatie_questions = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'hi5_evaluatie_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'hi5_evaluatie_question_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'hi5er_radio' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'hi5er_details' => array('type' => 'text', 'null' => false, 'default' => NULL),
		'wb_radio' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'wb_details' => array('type' => 'text', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $hi5_intakes = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'klant_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'key' => 'index'),
		'medewerker_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'datum_intake' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'verblijfstatus_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'postadres' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'postcode' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 6),
		'woonplaats' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'verblijf_in_NL_sinds' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'verblijf_in_amsterdam_sinds' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'verslaving_overig' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'inkomen_overig' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'locatie1_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'locatie2_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'woonsituatie_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'werklocatie_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'mag_gebruiken' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'legitimatie_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'legitimatie_nummer' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'legitimatie_geldig_tot' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'primaireproblematiek_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'primaireproblematieksfrequentie_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'primaireproblematieksperiode_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'eerste_gebruik' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'verslavingsfrequentie_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'verslavingsperiode_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'bedrijfitem_1_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'bedrijfitem_2_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'locatie3_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'idx_hi5_intakes_klant_id' => array('column' => 'klant_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $hi5_intakes_answers = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'hi5_intake_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'hi5_answer_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'hi5_answer_text' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $hi5_intakes_inkomens = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'hi5_intake_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'inkomen_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $hi5_intakes_instanties = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'hi5_intake_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'instantie_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $hi5_intakes_primaireproblematieksgebruikswijzen = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'hi5_intake_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'primaireproblematieksgebruikswijze_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $hi5_intakes_verslavingen = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'hi5_intake_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'verslaving_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $hi5_intakes_verslavingsgebruikswijzen = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'hi5_intake_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'verslavingsgebruikswijze_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $hi5_questions = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'question' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'category' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'order' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $hoofdaannemers = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'naam' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $i18n = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'locale' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 6, 'key' => 'index'),
		'model' => array('type' => 'string', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'foreign_key' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'index'),
		'field' => array('type' => 'string', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'content' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'locale' => array('column' => 'locale', 'unique' => 0), 'model' => array('column' => 'model', 'unique' => 0), 'row_id' => array('column' => 'foreign_key', 'unique' => 0), 'field' => array('column' => 'field', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $infobaliedoelgroepen = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'naam' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $inkomens = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'naam' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'datum_van' => array('type' => 'date', 'null' => false, 'default' => NULL),
		'datum_tot' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $inkomens_awbz_intakes = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'awbz_intake_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'inkomen_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $inkomens_intakes = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'intake_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'inkomen_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $instanties = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'naam' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'datum_van' => array('type' => 'date', 'null' => false, 'default' => NULL),
		'datum_tot' => array('type' => 'date', 'null' => false, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $instanties_awbz_intakes = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'awbz_intake_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'instantie_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $instanties_intakes = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'intake_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'instantie_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $intakes = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'klant_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'key' => 'index'),
		'medewerker_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'datum_intake' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'verblijfstatus_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'key' => 'index'),
		'postadres' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'postcode' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 6),
		'woonplaats' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'verblijf_in_NL_sinds' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'verblijf_in_amsterdam_sinds' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'legitimatie_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'legitimatie_nummer' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'legitimatie_geldig_tot' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'primaireproblematiek_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'primaireproblematieksfrequentie_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'primaireproblematieksperiode_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'verslavingsfrequentie_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'verslavingsperiode_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'verslaving_overig' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'eerste_gebruik' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'inkomen_overig' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'woonsituatie_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'key' => 'index'),
		'verwachting_dienstaanbod' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'toekomstplannen' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'opmerking_andere_instanties' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'medische_achtergrond' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'locatie1_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'locatie2_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'mag_gebruiken' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'indruk' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'doelgroep' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'informele_zorg' => array('type' => 'boolean', 'null' => true, 'default' => '0'),
		'dagbesteding' => array('type' => 'boolean', 'null' => true, 'default' => '0'),
		'inloophuis' => array('type' => 'boolean', 'null' => true, 'default' => '0'),
		'hulpverlening' => array('type' => 'boolean', 'null' => true, 'default' => '0'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'locatie3_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'infobaliedoelgroep_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'toegang_vrouwen_nacht_opvang' => array('type' => 'boolean', 'null' => true, 'default' => '0'),
		'telefoonnummer' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'toegang_inloophuis' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'idx_intakes_woonsituatie_id' => array('column' => 'woonsituatie_id', 'unique' => 0), 'idx_intakes_klant_id' => array('column' => 'klant_id', 'unique' => 0), 'idx_intakes_verblijfstatus_id' => array('column' => 'verblijfstatus_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $intakes_primaireproblematieksgebruikswijzen = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'intake_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'primaireproblematieksgebruikswijze_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $intakes_verslavingen = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'intake_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'verslaving_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $intakes_verslavingsgebruikswijzen = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'intake_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'verslavingsgebruikswijze_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $inventarisaties = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'order' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'parent_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'actief' => array('type' => 'boolean', 'null' => false, 'default' => '1'),
		'type' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'titel' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'actie' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'startdatum' => array('type' => 'date', 'null' => false, 'default' => NULL),
		'einddatum' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'lft' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'rght' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'depth' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 3),
		'dropdown_metadata' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $inventarisaties_verslagen = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'verslag_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'inventarisatie_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'doorverwijzer_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $iz_afsluitingen = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'naam' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'active' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $iz_deelnemers = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'model' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50, 'key' => 'index'),
		'foreign_key' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'datum_aanmelding' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'binnengekomen_via' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50),
		'organisatie' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100),
		'naam_aanmelder' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100),
		'email_aanmelder' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100),
		'telefoon_aanmelder' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100),
		'notitie' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'datumafsluiting' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'iz_afsluiting_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'contact_ontstaan' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'idx_iz_deelnemers_persoon' => array('column' => array('model', 'foreign_key'), 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $iz_deelnemers_iz_intervisiegroepen = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'iz_deelnemer_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'iz_intervisiegroep_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $iz_deelnemers_iz_projecten = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'iz_deelnemer_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'iz_project_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'iz_deelnemers_iz_projecten_id_deelnemr' => array('column' => 'iz_deelnemer_id', 'unique' => 0), 'iz_deelnemers_iz_projecten_iz_project_id' => array('column' => 'iz_project_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $iz_eindekoppelingen = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'naam' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'active' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $iz_intakes = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'iz_deelnemer_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'medewerker_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'intake_datum' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'gesprek_verslag' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'ondernemen' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'overdag' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'ontmoeten' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'regelzaken' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'stagiair' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'gezin_met_kinderen' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modifed' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'iz_deelnemer_id' => array('column' => 'iz_deelnemer_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $iz_intervisiegroepen = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'naam' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100),
		'startdatum' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'einddatum' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'medewerker_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $iz_koppelingen = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'project_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'iz_deelnemer_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'medewerker_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'startdatum' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'koppeling_startdatum' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'koppeling_einddatum' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'iz_eindekoppeling_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'koppeling_succesvol' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'einddatum' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'iz_vraagaanbod_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'iz_koppeling_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $iz_ontstaan_contacten = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'naam' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50),
		'active' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $iz_projecten = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'naam' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'startdatum' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'einddatum' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'heeft_koppelingen' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $iz_verslagen = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'iz_deelnemer_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'medewerker_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'opmerking' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'iz_koppeling_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'key' => 'index'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'idz_iz_verslag_iz_koppeling_id' => array('column' => 'iz_koppeling_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $iz_via_personen = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'naam' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50),
		'active' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $iz_vraagaanboden = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'naam' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $klanten = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'MezzoID' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'voornaam' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'tussenvoegsel' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'achternaam' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'roepnaam' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'geslacht_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'geboortedatum' => array('type' => 'date', 'null' => true, 'default' => NULL, 'key' => 'index'),
		'land_id' => array('type' => 'integer', 'null' => false, 'default' => '1'),
		'nationaliteit_id' => array('type' => 'integer', 'null' => false, 'default' => '1'),
		'BSN' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'medewerker_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'laatste_TBC_controle' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'laste_intake_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'disabled' => array('type' => 'boolean', 'null' => true, 'default' => '0'),
		'laatste_registratie_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'doorverwijzen_naar_amoc' => array('type' => 'boolean', 'null' => true, 'default' => '0'),
		'merged_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'adres' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'postcode' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 6),
		'werkgebied' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 20, 'key' => 'index'),
		'postcodegebied' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50),
		'plaats' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'email' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'mobiel' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'telefoon' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'opmerking' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'geen_post' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'first_intake_date' => array('type' => 'date', 'null' => true, 'default' => NULL, 'key' => 'index'),
		'geen_email' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'overleden' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'last_zrm' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'idx_klanten_geboortedatum' => array('column' => 'geboortedatum', 'unique' => 0), 'idx_klanten_first_intake_date' => array('column' => 'first_intake_date', 'unique' => 0), 'idx_klanten_werkgebied' => array('column' => 'werkgebied', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $klantinventarisaties = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'klant_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'inventarisatie_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'doorverwijzer_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'datum' => array('type' => 'date', 'null' => false, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $landen = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'land' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'AFK2' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 5),
		'AFK3' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 5),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $legitimaties = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'naam' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'datum_van' => array('type' => 'date', 'null' => false, 'default' => NULL),
		'datum_tot' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $locatie_tijden = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'locatie_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'dag_van_de_week' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 4),
		'sluitingstijd' => array('type' => 'time', 'null' => false, 'default' => NULL),
		'openingstijd' => array('type' => 'time', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);
	var $locaties = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'naam' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'nachtopvang' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'gebruikersruimte' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'datum_van' => array('type' => 'date', 'null' => false, 'default' => NULL),
		'datum_tot' => array('type' => 'date', 'null' => false, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'maatschappelijkwerk' => array('type' => 'boolean', 'null' => true, 'default' => '0'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $logs = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'model' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50, 'key' => 'index'),
		'foreign_key' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 36),
		'medewerker_id' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 36, 'key' => 'index'),
		'ip' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 15),
		'action' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'change' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'idx_logs_model_foreign_key_created' => array('column' => array('model', 'foreign_key', 'created'), 'unique' => 0), 'idx_logs_medewerker_id' => array('column' => 'medewerker_id', 'unique' => 0), 'idx_logs_model_foreign_key' => array('column' => array('model', 'foreign_key'), 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $medewerkers = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'username' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'voornaam' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'tussenvoegsel' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'achternaam' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'email' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'eerste_bezoek' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'laatste_bezoek' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'uidnumber' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'active' => array('type' => 'integer', 'null' => false, 'default' => '1', 'length' => 4),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $nationaliteiten = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'naam' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'afkorting' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $notities = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'klant_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'medewerker_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'datum' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'opmerking' => array('type' => 'text', 'null' => false, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'idx_notities_klant_id' => array('column' => 'klant_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $opmerkingen = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'categorie_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'beschrijving' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'klant_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'gezien' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'idx_opmerkingen_klant_id' => array('column' => 'klant_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $pfo_aard_relaties = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'naam' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'startdatum' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'einddatum' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $pfo_clienten = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'roepnaam' => array('type' => 'string', 'null' => true, 'default' => NULL, 'key' => 'index'),
		'tussenvoegsel' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'achternaam' => array('type' => 'string', 'null' => true, 'default' => NULL, 'key' => 'index'),
		'geslacht_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'geboortedatum' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'adres' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'postcode' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50),
		'woonplaats' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'telefoon' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'telefoon_mobiel' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'email' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'notitie' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'medewerker_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'key' => 'index'),
		'groep' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'key' => 'index'),
		'aard_relatie' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'dubbele_diagnose' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 4),
		'eerdere_hulpverlening' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'via' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'hulpverleners' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'contacten' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'begeleidings_formulier' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'brief_huisarts' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'evaluatie_formulier' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'datum_afgesloten' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'idx_pfo_clienten_roepnaam' => array('column' => 'roepnaam', 'unique' => 0), 'idx_pfo_clienten_achternaam' => array('column' => 'achternaam', 'unique' => 0), 'idx_pfo_clienten_groep' => array('column' => 'groep', 'unique' => 0), 'idx_pfo_clienten_medewerker_id' => array('column' => 'medewerker_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $pfo_clienten_supportgroups = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'pfo_client_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'pfo_supportgroup_client_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'pfo_cl_s_pfo_client_id' => array('column' => 'pfo_client_id', 'unique' => 0), 'pfo_cl_s_pfo_supportgroup_client_id' => array('column' => 'pfo_supportgroup_client_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $pfo_clienten_verslagen = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'pfo_client_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'pfo_verslag_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);
	var $pfo_groepen = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'naam' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'startdatum' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'einddatum' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $pfo_verslagen = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'contact_type' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50),
		'verslag' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'medewerker_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $postcodegebieden = array(
		'postcodegebied' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'van' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'tot' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $queue_tasks = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'model' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50),
		'foreign_key' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36),
		'action' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50),
		'data' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL, 'key' => 'index'),
		'run_after' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'batch' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50),
		'output' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'executed' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'status' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'idx_queue_tasks_status_modified' => array('column' => array('modified', 'status'), 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $redenen = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'naam' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $registraties = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'locatie_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'klant_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'binnen' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'buiten' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'douche' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'mw' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'kleding' => array('type' => 'boolean', 'null' => false, 'default' => NULL),
		'maaltijd' => array('type' => 'boolean', 'null' => false, 'default' => NULL),
		'activering' => array('type' => 'boolean', 'null' => false, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'gbrv' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'closed' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'idx_registraties_klant_id_locatie_id' => array('column' => array('klant_id', 'locatie_id'), 'unique' => 0), 'idx_registraties_locatie_id_closed' => array('column' => array('locatie_id', 'closed'), 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $schorsingen = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'datum_van' => array('type' => 'date', 'null' => false, 'default' => NULL),
		'datum_tot' => array('type' => 'date', 'null' => false, 'default' => NULL),
		'locatie_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'klant_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'remark' => array('type' => 'text', 'null' => false, 'default' => NULL),
		'gezien' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'overig_reden' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'aangifte' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'nazorg' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'aggressie_tegen_medewerker' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 4),
		'aggressie_doelwit' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'agressie' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 4),
		'aggressie_tegen_medewerker2' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 4),
		'aggressie_doelwit2' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'aggressie_tegen_medewerker3' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 4),
		'aggressie_doelwit3' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'aggressie_tegen_medewerker4' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 4),
		'aggressie_doelwit4' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'locatiehoofd' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
		'bijzonderheden' => array('type' => 'text', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'idx_schorsingen_klant_id' => array('column' => 'klant_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $schorsingen_redenen = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'schorsing_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'reden_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'idx_schorsingen_redenen_schorsing_id' => array('column' => 'schorsing_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $stadsdelen = array(
		'postcode' => array('type' => 'string', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'stadsdeel' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'postcode', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $tmp_avgduration = array(
		'label' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 64),
		'range_start' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'range_end' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'indexes' => array(),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);
	var $tmp_open_days = array(
		'locatie_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'open_day' => array('type' => 'date', 'null' => true, 'default' => NULL, 'key' => 'index'),
		'indexes' => array('idx_tmp_open_days_locatie_id' => array('column' => 'locatie_id', 'unique' => 0), 'idx_tmp_open_days_open_day' => array('column' => 'open_day', 'unique' => 0)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);
	var $tmp_registraties = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'primary'),
		'locatie_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'klant_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'binnen' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'buiten' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'douche' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'mw' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'kleding' => array('type' => 'boolean', 'null' => false, 'default' => NULL),
		'maaltijd' => array('type' => 'boolean', 'null' => false, 'default' => NULL),
		'activering' => array('type' => 'boolean', 'null' => false, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'gbrv' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'indexes' => array(),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
	);
	var $tmp_registrations2 = array(
		'klant_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'voornaam' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'tussenvoegsel' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'achternaam' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'douche' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'maaltijd' => array('type' => 'boolean', 'null' => false, 'default' => NULL),
		'activering' => array('type' => 'boolean', 'null' => false, 'default' => NULL),
		'locatie_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'indexes' => array(),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);
	var $tmp_visitors = array(
		'klant_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'land_id' => array('type' => 'integer', 'null' => false, 'default' => '1', 'key' => 'index'),
		'geslacht' => array('type' => 'string', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'date' => array('type' => 'date', 'null' => true, 'default' => NULL, 'key' => 'index'),
		'verslaving_id' => array('type' => 'integer', 'null' => true, 'default' => '0', 'key' => 'index'),
		'woonsituatie_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'key' => 'index'),
		'verblijfstatus_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'key' => 'index'),
		'indexes' => array('idx_tmp_visitors_land_id' => array('column' => 'land_id', 'unique' => 0), 'idx_tmp_visitors_verslaving_id' => array('column' => 'verslaving_id', 'unique' => 0), 'idx_tmp_visitors_klant_id' => array('column' => 'klant_id', 'unique' => 0), 'idx_tmp_visitors_date' => array('column' => 'date', 'unique' => 0), 'idx_tmp_visitors_woonsituatie_id' => array('column' => 'woonsituatie_id', 'unique' => 0), 'idx_tmp_visitors_verblijfstatus_id' => array('column' => 'verblijfstatus_id', 'unique' => 0), 'idx_tmp_visitors_geslacht' => array('column' => 'geslacht', 'unique' => 0)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);
	var $tmp_visits = array(
		'locatie_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'klant_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'date' => array('type' => 'date', 'null' => true, 'default' => NULL, 'key' => 'index'),
		'gender' => array('type' => 'string', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'duration' => array('type' => 'float', 'null' => true, 'default' => NULL, 'length' => 31, 'key' => 'index'),
		'indexes' => array('idx_tmp_visits_locatie_id' => array('column' => 'locatie_id', 'unique' => 0), 'idx_tmp_visits_klant_id' => array('column' => 'klant_id', 'unique' => 0), 'idx_tmp_visits_date' => array('column' => 'date', 'unique' => 0), 'idx_tmp_visits_duration' => array('column' => 'duration', 'unique' => 0), 'idx_tmp_visits_gender' => array('column' => 'gender', 'unique' => 0)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);
	var $trajecten = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'klant_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'unique'),
		'trajectbegeleider_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'werkbegeleider_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'klant_telefoonnummer' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'administratienummer' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'klantmanager' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'manager_telefoonnummer' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'manager_email' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'uq_klant_id' => array('column' => 'klant_id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $verblijfstatussen = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'naam' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'datum_van' => array('type' => 'date', 'null' => false, 'default' => NULL),
		'datum_tot' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $verslagen = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'klant_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'medewerker_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'opmerking' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'datum' => array('type' => 'date', 'null' => true, 'default' => NULL, 'key' => 'index'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'medewerker' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'locatie_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'key' => 'index'),
		'aanpassing_verslag' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 5),
		'contactsoort_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'idx_klant' => array('column' => 'klant_id', 'unique' => 0), 'idx_locatie_id' => array('column' => 'locatie_id', 'unique' => 0), 'idx_datum' => array('column' => 'datum', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $verslaginfos = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'klant_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'unique'),
		'advocaat' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'contact' => array('type' => 'text', 'null' => false, 'default' => NULL),
		'casemanager_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'casemanager_email' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'casemanager_telefoonnummer' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'trajectbegeleider_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'trajectbegeleider_email' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'trajectbegeleider_telefoonnummer' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'trajecthouder_extern_organisatie' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'trajecthouder_extern_naam' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'trajecthouder_extern_email' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'trajecthouder_extern_telefoonnummer' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'overige_contactpersonen_extern' => array('type' => 'text', 'null' => false, 'default' => NULL),
		'instantie' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'registratienummer' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'budgettering' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'contactpersoon' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'klantmanager_naam' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'klantmanager_email' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'klantmanager_telefoonnummer' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'sociaal_netwerk' => array('type' => 'text', 'null' => false, 'default' => NULL),
		'bankrekeningnummer' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'polisnummer_ziektekostenverzekering' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'inschrijfnummer' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'wachtwoord' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'telefoonnummer' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'adres' => array('type' => 'text', 'null' => false, 'default' => NULL),
		'overigen' => array('type' => 'text', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'uq_klant' => array('column' => 'klant_id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $verslavingen = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'naam' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'datum_van' => array('type' => 'date', 'null' => false, 'default' => NULL),
		'datum_tot' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $verslavingsfrequenties = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'naam' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'datum_van' => array('type' => 'date', 'null' => false, 'default' => NULL),
		'datum_tot' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $verslavingsgebruikswijzen = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'naam' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'datum_van' => array('type' => 'date', 'null' => false, 'default' => NULL),
		'datum_tot' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $verslavingsperiodes = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'naam' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'datum_van' => array('type' => 'date', 'null' => false, 'default' => NULL),
		'datum_tot' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $vrijwilligers = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'voornaam' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'tussenvoegsel' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'achternaam' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'roepnaam' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'geslacht_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'geboortedatum' => array('type' => 'date', 'null' => true, 'default' => NULL, 'key' => 'index'),
		'land_id' => array('type' => 'integer', 'null' => false, 'default' => '1'),
		'nationaliteit_id' => array('type' => 'integer', 'null' => false, 'default' => '1'),
		'BSN' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'medewerker_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'adres' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'postcode' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 6),
		'werkgebied' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 20, 'key' => 'index'),
		'postcodegebied' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50),
		'plaats' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'email' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'mobiel' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'telefoon' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'opmerking' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'geen_post' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'disabled' => array('type' => 'boolean', 'null' => true, 'default' => '0'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'geen_email' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'idx_klanten_geboortedatum' => array('column' => 'geboortedatum', 'unique' => 0), 'idx_vrijwilligers_werkgebied' => array('column' => 'werkgebied', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $woonsituaties = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'naam' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'datum_van' => array('type' => 'date', 'null' => false, 'default' => NULL),
		'datum_tot' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $zrm_reports = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'klant_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'model' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50),
		'foreign_key' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'request_module' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50),
		'inkomen' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'dagbesteding' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'huisvesting' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'gezinsrelaties' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'geestelijke_gezondheid' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'fysieke_gezondheid' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'verslaving' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'adl_vaardigheden' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'sociaal_netwerk' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'maatschappelijke_participatie' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'justitie' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	var $zrm_settings = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'request_module' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50),
		'inkomen' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'dagbesteding' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'huisvesting' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'gezinsrelaties' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'geestelijke_gezondheid' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'fysieke_gezondheid' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'verslaving' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'adl_vaardigheden' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'sociaal_netwerk' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'maatschappelijke_participatie' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'justitie' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
}
?>