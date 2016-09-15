<?php
/**
 * This file is loaded automatically by the app/webroot/index.php file after the core bootstrap.php
 *
 * This is an application wide file to load any function that is not used within a class
 * define. You can also use this to include or require any files in your application.
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.app.config
 * @since         CakePHP(tm) v 0.10.8.2117
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * The settings below can be used to set additional paths to models, views and controllers.
 * This is related to Ticket #470 (https://trac.cakephp.org/ticket/470)
 *
 * App::build(array(
 *     'plugins' => array('/full/path/to/plugins/', '/next/full/path/to/plugins/'),
 *     'models' =>  array('/full/path/to/models/', '/next/full/path/to/models/'),
 *     'views' => array('/full/path/to/views/', '/next/full/path/to/views/'),
 *     'controllers' => array('/full/path/to/controllers/', '/next/full/path/to/controllers/'),
 *     'datasources' => array('/full/path/to/datasources/', '/next/full/path/to/datasources/'),
 *     'behaviors' => array('/full/path/to/behaviors/', '/next/full/path/to/behaviors/'),
 *     'components' => array('/full/path/to/components/', '/next/full/path/to/components/'),
 *     'helpers' => array('/full/path/to/helpers/', '/next/full/path/to/helpers/'),
 *     'vendors' => array('/full/path/to/vendors/', '/next/full/path/to/vendors/'),
 *     'shells' => array('/full/path/to/shells/', '/next/full/path/to/shells/'),
 *     'locales' => array('/full/path/to/locale/', '/next/full/path/to/locale/')
 * ));
 *
 */

/**
 * As of 1.3, additional rules for the inflector are added below
 *
 */


/**
 * Setting language to Dutch for month selection fields
 */

Configure::write('Config.language', 'dut');
Configure::write('Calendar.dateDisplayFormat', 'd-M-y');
//e-mail addresses for the intake notifications:

if(isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] == 'ecd.deregenboog.org' ) {
    Configure::write('informele_zorg_mail', 'jschmidt@deregenboog.org');
    Configure::write('dagbesteding_mail', 'bnieuwburg@deregenboog.org');
    Configure::write('inloophuis_mail', 'adbruijn@deregenboog.org');
    Configure::write('hulpverlening_mail', 'jvloo@deregenboog.org');
    Configure::write('agressie_mail', 'tvhamond@deregenboog.org');
    Configure::write('administratiebedrijf', 'administratiebedrijf@deregenboog.org ');
} else if (isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] == 'dev-ecd.deregenboog.org' ) {
    Configure::write('informele_zorg_mail', 'robert@accelcloud.com');
    Configure::write('dagbesteding_mail', 'robert@accelcloud.com');
    Configure::write('inloophuis_mail', 'robert@accelcloud.com');
    Configure::write('hulpverlening_mail', 'robert@accelcloud.com');
    Configure::write('agressie_mail', 'robert@accelcloud.com');
    Configure::write('administratiebedrijf', 'robert@accelcloud.com');
}else {
    //Configure::write('informele_zorg_mail', 'bhuttinga@deregenboog.org');
    //Configure::write('dagbesteding_mail', 'bhuttinga@deregenboog.org');
    //Configure::write('inloophuis_mail', 'bhuttinga@deregenboog.org');
    //Configure::write('hulpverlening_mail', 'bhuttinga@deregenboog.org');
    //Configure::write('agressie_mail', 'bhuttinga@deregenboog.org');
    //Configure::write('administratiebedrijf', 'bhuttinga@deregenboog.org');
    Configure::write('informele_zorg_mail', 'robert@accelcloud.com');
    Configure::write('dagbesteding_mail', 'robert@accelcloud.com');
    Configure::write('inloophuis_mail', 'robert@accelcloud.com');
    Configure::write('hulpverlening_mail', 'robert@accelcloud.com');
    Configure::write('agressie_mail', 'robert@accelcloud.com');
    Configure::write('administratiebedrijf', 'robert@accelcloud.com');
}
#Configure::write('agressie_mail', 'robert@accelcloud.com');

/*
 Inflector entries for Dutch model names:
*/



$pluralizations = array(
'iz_deelnemers_iz_intervisiegroep' => 'iz_deelnemers_iz_intervisiegroepen',
'iz_via_persoon' => 'iz_via_personen',
'izviapersoon' => 'IzViaPersonen',
'iz_ontstaan_contact' => 'iz_ontstaan_contacten',
'izontstaancontact' => 'IzOntstaanContacten',
'iz_vraagaanbod' => 'iz_vraagaanboden',
'izvraagaanbod' => 'IzVraagaanboden',
'iz_koppeling' => 'iz_koppelingen',
'izkoppeling' => 'IzKoppelingen',
'iz_verslag' => 'iz_verslagen',
'izverslag' => 'IzVerslagen',
'iz_deelnemers_iz_project' => 'iz_deelnemers_iz_projecten',
'iz_afsluiting' => 'iz_afsluitingen',
'izafsluiting' => 'IzAfsluitingen',
'iz_eindekoppeling' => 'iz_eindekoppelingen',
'izeindekoppeling' => 'IzEindekoppelingen',
'iz_project' => 'iz_projecten',
'izproject' => 'IzProjecten',
'iz_intervisiegroep' => 'iz_intervisiegroepen',
'izintervisiegroep' => 'IzIntervisiegroepen',
'awbz_hoofdaannemers' => 'awbz_hoofdaannemer',
'awbz_indicatie' => 'awbz_indicaties',
'awbz_intake' => 'awbz_intakes',
'awbz_intake_verslaving' => 'awbz_intakes_verslavingen',
'awbz_intakes_primaireproblematieksgebruikswijze' => 'awbz_intakes_primaireproblematieksgebruikswijzen',
'awbz_intakes_verslavingsgebruikswijze' => 'awbz_intakes_verslavingsgebruikswijzen',
'bot_verslag' => 'bot_verslagen',
'botverslag' => 'BotVerslagen',
'bot_koppeling' => 'bot_koppelingen',
'botkoppeling' => 'BotKoppelingen',
'categorie' => 'categorieen',
'doelgroep' => 'doelgroepen',
'doorverwijzer' => 'doorverwijzers',
'geslacht' => 'geslachten',
'hoofdaannemer' => 'hoofdaannemers',
'infobaliedoelgroep' => 'infobaliedoelgroepen',
'inkomen' => 'inkomens',
'instantie' => 'instanties',
'intake' => 'intakes',
'intake_verslaving' => 'intakes_verslavingen',
'intakes_primaireproblematieksgebruikswijze' => 'intakes_primaireproblematieksgebruikswijzen',
'intakes_verslavingsgebruikswijze' => 'intakes_verslavingsgebruikswijzen',
'inventarisatie' => 'inventarisaties',
'inventarisaties_verslag' => 'inventarisaties_verslagen',
'klant' => 'klanten',
'klantinventarisatie' => 'klantinventarisaties',
'land' => 'landen',
'legitimatie' => 'legitimaties',
'locatie' => 'locaties',
'medewerker' => 'medewerkers',
'nationaliteit' => 'nationaliteiten',
'notitie' => 'notities',
'opmerking' => 'opmerkingen',
'pfo_aard_relatie' => 'pfo_aard_relaties',
'pfoaardrelatie' => 'PfoAardRelaties',
'pfo_client' => 'pfo_clienten',
'pfogroep' => 'PfoGroepen',
'pfo_groep' => 'pfo_groepen',
'pfoclient' => 'PfoClienten',
'pfo_clienten_verslag' => 'pfo_clienten_verslagen',
'pfoclientenverslag' => 'PfoClientenVerslagen',
'pfo_verslag' => 'pfo_verslagen',
'pfoverslag' => 'PfoVerslagen',
'persoon' => 'personen',
'groepsactiviteiten_groep' => 'groepsactiviteiten_groepen',
'groepsactiviteitengroep' => 'GroepsactiviteitenGroepen',
'groepsactiviteiten_afsluiting' => 'groepsactiviteiten_afsluitingen',
'groepsactiviteitenafsluiting' => 'GroepsactiviteitenAfsluitingen',
'groepsactiviteiten_reden' => 'groepsactiviteiten_redenen',
'groepsactiviteitenreden' => 'GroepsactiviteitenRedenen',
'groepsactiviteiten_groepen_klant' => 'groepsactiviteiten_groepen_klanten',
'groepsactiviteitengroepenklant' => 'GroepsactiviteitenGroepenKlanten',
        
        'groepsactiviteiten_klant' => 'groepsactiviteiten_klanten',
        'groepsactiviteitenklant' => 'GroepsactiviteitenKlanten',
        
        
'groepsactiviteit' => 'groepsactiviteiten',
'groepsactiviteiten_verslag' => 'groepsactiviteiten_verslagen',
'groepsactiviteitenverslag' => 'GroepsactiviteitenVerslagen',
'primaireproblematieksgebruikswijze' => 'primaireproblematieksgebruikswijzen',
'reden' => 'redenen',
'registratie' => 'registraties',
'schorsing' => 'schorsingen',
'schorsingen_reden' => 'schorsingen_redenen',
'test_naam' => 'test_namen',
'traject' => 'trajecten',
'verblijfstatus' => 'verblijfstatussen',
'verslag' => 'verslagen',
'verslaving' => 'verslavingen',
'verslavingsfrequentie' => 'verslavingsfrequenties',
'verslavingsgebruikswijze' => 'verslavingsgebruikswijzen',
'verslavingsperiode' => 'verslavingsperiodes',
'woonsituatie' => 'woonsituaties',
'bedrijfsector' => 'bedrijfsectoren',
'hi5_evaluatie' => 'hi5_evaluaties',
'locatie_tijd' => 'locatie_tijden',
'stadsdeel' => 'stadsdelen',
'postcodegebied' => 'postcodegebieden',
);

$singularizations = array(
'iz_deelnemers_iz_intervisiegroepen' => 'iz_deelnemers_iz_intervisiegroep',
'iz_via_personen' => 'iz_via_persoon',
'izviapersonen' => 'IzViaPersoon',
'iz_ontstaan_contacten' => 'iz_ontstaan_contact',
'izontstaancontacten' => 'IzOntstaanContact',
'iz_vraagaanboden' => 'iz_vraagaanbod',
'izvraagaanboden' => 'iz_vraagaanbod',
'iz_koppelingen' => 'iz_koppeling',
'izkoppelingen' => 'IzKoppeling',
'iz_verslagen' => 'iz_verslag',
'izverslagen' => 'IzVerslag',        
'iz_deelnemers_iz_projecten' => 'iz_deelnemers_iz_project',
'iz_afsluitingen' => 'iz_afsluiting',
'izafsluitingen' => 'IzAfsluiting',
'iz_eindekoppelingen' => 'iz_eindekoppeling',
'izeindekoppelingen' => 'IzEindekoppeling',
'iz_projecten' => 'iz_project',
'izprojecten' => 'IzProject',
'iz_intervisiegroepen' => 'iz_intervisiegroep',
'izintervisiegroepen' => 'IzIntervisiegroep',
'awbz_hoofdaannemer' => 'awbz_hoofdaannemers',
'awbz_indicaties' => 'awbz_indicatie',
'awbz_intakes' => 'awbz_intake',
'awbz_intakes_primaireproblematieksgebruikswijzen' => 'awbz_intakes_primaireproblematieksgebruikswijze',
'awbz_intakes_verslavingsgebruikswijzen' => 'awbz_intakes_verslavingsgebruikswijze',
'bot_koppelingen' => 'bot_koppeling',
'botkoppelingen' => 'BotKoppeling',
'bot_verslagen' => 'bot_verslag',
'botverslagen' => 'BotVerslag',
'categorieen' => 'categorie',
'doelgroepen' => 'doelgroep',
'doorverwijzers' => 'doorverwijzer',
'geslachten' => 'geslacht',
'hoofdaannemers' => 'hoofdaannemer',
'infobaliedoelgroepen' => 'infobaliedoelgroep',
'inkomens' => 'inkomen',
'instanties' => 'instantie',
'intakes' => 'intake',
'intakes_primaireproblematieksgebruikswijzen' => 'intakes_primaireproblematieksgebruikswijze',
'intakes_verslavingsgebruikswijzen' => 'intakes_verslavingsgebruikswijze',
'inventarisaties' => 'inventarisatie',
'inventarisaties_verslagen' => 'inventarisaties_verslag',
'klanten' => 'klant',
'klantinventarisaties' => 'klantinventarisatie',
'landen' => 'land',
'legitimaties' => 'legitimatie',//'iz_deelnemers' => 'iz_deelnemer',
//'izdeelnemers' => 'IzDeelnemer',
        
'locaties' => 'locatie',
'medewerkers' => 'medewerker',
'nationaliteiten' => 'nationaliteit',
'notities' => 'notitie',
'opmerkingen' => 'opmerking',
'pfo_aard_relaties' => 'pfo_aard_relatie',
'pfoaardrelaties' => 'PfoAardRelatie',
'pfo_groepen' => 'pfo_groep',
'pfogroepen' => 'PfoGroep',
'pfo_clienten' => 'pfo_client',
'pfoclienten' => 'PfoClient',
'pfo_verslagen' => 'pfo_verslag',
'pfoverslagen' => 'PfoVerslag',
'personen' => 'persoon',
'groepsactiviteiten_groepen' => 'groepsactiviteiten_groep',
'groepsactiviteitengroepen' => 'GroepsactiviteitenGroep',
'groepsactiviteiten_afsluitingen' => 'groepsactiviteiten_afsluiting',
'groepsactiviteitenafsluitingen' => 'GroepsactiviteitenAfsluiting',
'groepsactiviteiten_redenen' => 'groepsactiviteiten_reden',
'groepsactiviteitenredenen' => 'GroepsactiviteitenReden',
'groepsactiviteiten_redenen' => 'groepsactiviteiten_reden',
'groepsactiviteitenredenen' => 'GroepsactiviteitenReden',
'groepsactiviteiten_groepen_klanten' => 'groepsactiviteiten_groepen_klant',
'groepsactiviteitengroepenklanten' => 'GroepsactiviteitenGroepenKlant',

        'groepsactiviteiten_klanten' => 'groepsactiviteiten_klant',
        'groepsactiviteitenklanten' => 'GroepsactiviteitenKlant',
        
        
'groepsactiviteiten' => 'Groepsactiviteit',
'groepsactiviteiten_verslagen' => 'groepsactiviteiten_verslag',
'groepsactiviteitenverslagen' => 'GroepsactiviteitenVerslag',
'pfo_clienten_verslagen' => 'pfo_clienten_verslag',
'pfoclientenverslagen' => 'PfoClientenVerslag',
'PfoClientenVerslagen' => 'PfoClientenVerslag',
'primaireproblematieksgebruikswijzen' => 'primaireproblematieksgebruikswijze',
'redenen' => 'reden',
'registraties' => 'registratie',
'schorsingen' => 'schorsing',
'schorsingen_redenen' => 'schorsingen_reden',
'test_namen' => 'test_naam',
'trajecten' => 'traject',
'verblijfstatussen' => 'verblijfstatus',
'verslagen' => 'verslag',
'verslavingen' => 'verslaving',
'verslavingsfrequenties' => 'verslavingsfrequentie',
'verslavingsgebruikswijzen' => 'verslavingsgebruikswijze',
'verslavingsperiodes' => 'verslavingsperiode',
'woonsituaties' => 'woonsituatie',
'bedrijfsectoren' => 'bedrijfsector',
'hi5_evaluaties' => 'hi5_evaluatie',
'locatie_tijden' => 'locatie_tijd',
'postcodegebieden' => 'postcodegebied',
);

Inflector::rules('singular', array('rules' => array(), 'irregular' => $singularizations, 'uninflected' => array()));
Inflector::rules('plural', array('rules' => array(), 'irregular' => $pluralizations, 'uninflected' => array()));

/*
debug(Inflector::pluralize('iz_deelnemers_iz_project'));
debug(Inflector::singularize('iz_deelnemers_iz_projecten'));

debug(Inflector::pluralize('izafsluiting'));
debug(Inflector::singularize('izafsluitingen'));
debug(Inflector::pluralize('IzAfsluiting'));
debug(Inflector::singularize('IzAfsluitingen'));
*/

/*
 * 
*/

function mt(){
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

/** Translate a string, and replace variables in it as given by the $params
 * array. Variables in the string are given by :keywords, and these should
 * not be translated, of course.
 * Example __tr( "Expire in :d days", array ('d' => $days) );
 * See http://book.cakephp.org/view/1483/insert
 */

function __tr  ($string, $params = array() ) {

    if (!class_exists('String')) {
        App::import('Core', 'String');
    }

    // First translate.
    $message = __($string , true);
    // Then replace parameter keywords
    $message = String::insert($message, $params);

    return $message;

}

function debugLastQuery($model) {
    $qs = $model->getDataSource()->_queriesLog;
    var_dump(array_pop($qs));
}

/**
 * registry_isset See if a $type and $key are set in the registry
 * 
 * @param mixed $type Mandatory object type
 * @param mixed $key  Optional object ID
 * @access public
 * @return Boolean
 */
function registry_isset($type, $key) {
    if (Configure::read('Cache.disable')) {
        return false;
    }
    return isset($GLOBALS['AplicationRegistry']["$type.$key"]);
}

/**
 * registry_delete Delete all or a chunk of the registry, depending of the specfied parameters
 * 
 * @param mixed $type  Object type
 * @param mixed $key  Object ID
 * @access public
 * @return void
 */
function registry_delete($type, $key, $mem_cache = false, $config = 'default') {
    if (Configure::read('Cache.disable')) {
        return false;
    }
    unset($GLOBALS['AplicationRegistry']["$type.$key"]);
    if ($mem_cache) {
        Cache::delete($type.'.'.$key, $config);
    }
}

function registry_reset($mem_cache = false, $config = 'default') {
    if (Configure::read('Cache.disable')) {
        return false;
    }
    unset($GLOBALS['AplicationRegistry']);
    if ($mem_cache) {
        Cache::clear(false, $config);
    }
}



/**
 * registry_set Store a value in the registry, for the given object type and ID
 * 
 * @param mixed $type Object type
 * @param mixed $key  Object ID
 * @param mixed $value The value to store, can be anything.
 * @param boolean $mem_cache  Store the value also in the long term memory cache.
 * @access public
 * @return void
 */
function registry_set($type, $key, $value, $mem_cache = false, $config = 'default') {
    if (Configure::read('Cache.disable')) {
        return false;
    }
    $GLOBALS['AplicationRegistry']["$type.$key"] = $value;
    if ($mem_cache) {
        Cache::write($type.'.'.$key, $value, $config);
    }
}

/**
 * registry_get Get a value from the registry, for the given object type and optionally ID.
 * 
 * @param mixed $type Object type
 * @param mixed $key  Object ID
 * @param boolean $mem_cache  If value is not in the registry, try to read it from the long term memory cache.
 * @access public
 * @return mixed The stored value, or NULL if not set.
 */
function registry_get($type, $key = null, $mem_cache = false, $config = 'default') {
    if (Configure::read('Cache.disable')) {
        return null;
    }

    // Used to exclude globals in certain installations, if there is an 
    // external queue server that only reads from cache. Not for the ECD by 
    // now:
    $from_globals = true;

    if ($from_globals && isset($GLOBALS['AplicationRegistry']["$type.$key"])) {
        return $GLOBALS['AplicationRegistry']["$type.$key"];
    } else {
        if ($mem_cache) {
            $value = Cache::read($type.'.'.$key, $config);
            if ($value) {
                $GLOBALS['AplicationRegistry']["$type.$key"] = $value;
                return $value;
            }
        }
    }
    return null;
}

/*
 * Check if a savelall return when using atomic = false is true or false
 */
function is_saved($haystack) {
    if(! is_array($haystack)) {
        if(empty($haystack)) {
            return false;
        }
        return true;
    }
    if(in_array(0, $haystack)) {
        return false;
    }
    foreach($haystack as $element) {
        if(is_array($element) && ! is_saved( $element))
            return false;
    }
    return true;
}

