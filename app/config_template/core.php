<?php
/**
 * This is core configuration file.
 *
 * Use it to configure core behavior of Cake.
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
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * CakePHP Debug Level:
 *
 * Production Mode:
 * 0: No error messages, errors, or warnings shown. Flash messages redirect.
 *
 * Development Mode:
 * 1: Errors and warnings shown, model caches refreshed, flash messages halted.
 * 2: As in 1, but also with full debug messages and SQL output.
 *
 * In production mode, flash messages redirect after a time interval.
 * In development mode, you need to click the flash message to continue.
 */

Configure::write('debug', 0);
require_once('config.php');

if( array_key_exists( 'SERVER_NAME', $_SERVER ) && //there's no 'SERVER_NAME' at CLI
$_SERVER['SERVER_NAME'] == 'localhost' && $_SERVER['REMOTE_PORT'] == 80 ) {
    $in_production = false;
} else {
    $in_production = true;
}

if ( true &&  (
            env('REMOTE_ADDR') == '95.97.75.122' ||
            env('REMOTE_ADDR') == '24.132.236.64'  ||
            env('REMOTE_ADDR') == '84.82.36.221'  ||
            env('REMOTE_ADDR') == '82.169.179.97'  ||
            env('REMOTE_ADDR') == '83.160.103.186' ||
            env('REMOTE_ADDR') == '82.170.209.32'
            )) {

    Configure::write('debug', 1);
}

$volonteers = array (
        'vrijwilliger_oud_west' => '14', 
        'vrijwilliger_eik' => '9', 
        'vrijwilliger_de_kloof' => '10', 
        'vrijwilliger_macom' => '11', 
        'vrijwilliger_macom_nacht' => '12',
        'vwspreekbuis' => '15',
);

Configure::write( 'ACL.volonteers', $volonteers );

Configure::write( 'LDAP.configuration', $ldap );

$enable = array (
        GROUP_REPORT => array (
                "Rapportages", 
        ), 
        GROUP_PORTIER => array (
                "Registraties", 
                "Opmerkingen" 
        ), 
        GROUP_MLO => array (
                "Intakes", 
                "Klanten", 
                "Registraties", 
                "Schorsingen", 
                "Opmerkingen" 
        ), 
        GROUP_MAATSCHAPPELIJK => array (
                "Awbz",
                "AwbzHoofdaannemers",
                "AwbzIndicaties",
                "AwbzIntakes",
                "Intakes", 
                "Klanten", 
                "Registraties", 
                "Opmerkingen", 
                "MaatschappelijkWerk", 
                'Verslagen', 
                "Rapportages",
                "Attachments"
        ), 
        GROUP_HI5 => array (
                "Intakes", 
                "Klanten", 
                "Attachments"
        ), 
        GROUP_ADMIN => array (
                "Admin",
                "Awbz",
                "AwbzHoofdaannemers",
                "AwbzIndicaties",
                "AwbzIntakes",
                "Intakes", 
                "Klanten", 
                "Hi5", 
                "MaatschappelijkWerk", 
                'Verslagen', 
                "Registraties", 
                "Schorsingen", 
                "Opmerkingen", 
                "Rapportages", 
                "MwInventarisaties",
                "Attachments",
                'BackOnTrack',
                'BotVerslagen',
                'Vrijwilligers',
                'Groepsactiviteiten',
        ), 
        GROUP_BACK_ON_TRACK_COORDINATOR => array(
            'BackOnTrack',
            'BotVerslagen',
            'BotKoppelingen',
            "Intakes",
        ),
        GROUP_BACK_ON_TRACK_COACH => array(
            'BackOnTrack',
            'BotVerslagen',
            'BotKoppelingen',
        ),
        GROUP_VOLONTEERS => array (
                "Registraties" 
        ), 
        GROUP_STAGE => array (  
            "Intakes", 
            "Klanten", 
            "Registraties" 
        ), 
        GROUP_PFO =>array(
            "PfoClienten",
            "PfoRapporten",
            'PfoAardRelaties',
            'PfoGroepen',
            'PfoVerslagen',
        ),
        GROUP_TRAJECTBEGELEIDER => array (
                "Awbz",
                "AwbzHoofdaannemers",
                "AwbzIndicaties",
                "AwbzIntakes",
                "Hi5",
                "Attachments"
        ),
	    GROUP_IZ => array(
		        "Klanten",
		        "Vrijwilligers",
		        "IzDeelnemers",
		        "IzVerslagen",
                "Attachments"
	    ),
	    GROUP_IZ_BEHEER => array(
            "IzAfsluitingen",
            "IzDeelnemers",
            "IzEindekoppelingen",
            "IzIntervisiegroepen",
            "IzKoppelingen",
            "IzOntstaan",
            "IzProjecten",
            "IzVerslagen",
            "IzViaPersonen",
            "IzVraagaanboden",
            "IzOntstaanContacten",
	    ),
        GROUP_GROEPSACTIVITEIT => array(
            'Vrijwilligers',
            'Groepsactiviteiten',
            'Klanten',
            'GroepsactiviteitenIntakes',
            'GroepsactiviteitenVerslagen',
            "Attachments"
        ),
	    GROUP_BEHEER_GROEPSACTIVITEIT => array(
            "GroepsactiviteitenGroepen",
            "GroepsactiviteitenRedenen",
	    ),
        GROUP_WERKBEGELEIDER => array (
        ),
        GROUP_DEVELOP => array (
                "*" ,
		"Klanten",
		"Vrijwilligers",
		"IzDeelnemers",
		"IzVerslagen",
        "Groepsactiviteiten",
        ) 
);

Configure::write( 'ACL.permissions', $enable );

/** Disable ACL with a flag. This only works in debug mode. */
Configure::write( 'ACL.disabled', false );

$menu_elements = array (
	'Hi5' => 'Hi5', 
	'Klanten' => 'Klantenlijst', 
	'Vrijwilligers' => 'Vrijwilligers',
	'Registraties' => 'Registratie', 
	'MaatschappelijkWerk' => 'Maatschappelijk werk', 
	'Rapportages' => 'Rapportages', 
	'Awbz' => 'AWBZ',
	'PfoClienten' => 'PFO', 
	'BackOnTrack' => 'BOT',
	'IzDeelnemers' => 'IZ',
	'Groepsactiviteiten' => 'Groepsactiviteiten',
	'Admin' => 'Admin'
);



Configure::write( 'all_menu_items', $menu_elements );

Configure::write( 'TBC_months_period', 6 );

/**
 * CakePHP Log Level:
 *
 * In case of Production Mode CakePHP gives you the possibility to continue logging errors.
 *
 * The following parameters can be used:
 * Boolean: Set true/false to activate/deactivate logging
 * Configure::write('log', true);
 *
 * Integer: Use built-in PHP constants to set the error level (see error_reporting)
 * Configure::write('log', E_ERROR | E_WARNING);
 * Configure::write('log', E_ALL ^ E_NOTICE);
 */
Configure::write( 'log', true );

/**
 * Application wide charset encoding
 */
Configure::write( 'App.encoding', 'UTF-8' );

/**
 * To configure CakePHP *not* to use mod_rewrite and to
 * use CakePHP pretty URLs, remove these .htaccess
 * files:
 *
 * /.htaccess
 * /app/.htaccess
 * /app/webroot/.htaccess
 *
 * And uncomment the App.baseUrl below:
 */
//Configure::write('App.baseUrl', env('SCRIPT_NAME'));


/**
 * Uncomment the define below to use CakePHP prefix routes.
 *
 * The value of the define determines the names of the routes
 * and their associated controller actions:
 *
 * Set to an array of prefixes you want to use in your application. Use for
 * admin or other prefixed routes.
 *
 * Routing.prefixes = array('admin', 'manager');
 *
 * Enables:
 * `admin_index()` and `/admin/controller/index`
 * `manager_index()` and `/manager/controller/index`
 *
 * [Note Routing.admin is deprecated in 1.3.  Use Routing.prefixes instead]
 */
Configure::write( 'Routing.prefixes', array (
        'admin' 
) );

/**
 * Turn off all caching application-wide.
 *
 */
Configure::write('Cache.disable', false);


/**
 * Enable cache checking.
 *
 * If set to true, for view caching you must still use the controller
 * var $cacheAction inside your controllers to define caching settings.
 * You can either set it controller-wide by setting var $cacheAction = true,
 * or in each action using $this->cacheAction = true.
 *
 */
//Configure::write('Cache.check', true);

/**
 * Defines the default error type when using the log() function. Used for
 * differentiating error logging and debugging. Currently PHP supports LOG_DEBUG.
 */
define( 'LOG_ERROR', 2 );

/**
 * The preferred session handling method. Valid values:
 *
 * 'php'	 		Uses settings defined in your php.ini.
 * 'cake'		Saves session files in CakePHP's /tmp directory.
 * 'database'	Uses CakePHP's database sessions.
 *
 * To define a custom session handler, save it at /app/config/<name>.php.
 * Set the value of 'Session.save' to <name> to utilize it in CakePHP.
 *
 * To use database sessions, run the app/config/schema/sessions.php schema using
 * the cake shell command: cake schema run create Sessions
 *
 */
Configure::write( 'Session.save', 'php' );

/**
 * The model name to be used for the session model.
 *
 * 'Session.save' must be set to 'database' in order to utilize this constant.
 *
 * The model name set here should *not* be used elsewhere in your application.
 */
//Configure::write('Session.model', 'Session');


/**
 * The name of the table used to store CakePHP database sessions.
 *
 * 'Session.save' must be set to 'database' in order to utilize this constant.
 *
 * The table name set here should *not* include any table prefix defined elsewhere.
 *
 * Please note that if you set a value for Session.model (above), any value set for
 * Session.table will be ignored.
 *
 * [Note: Session.table is deprecated as of CakePHP 1.3]
 */
//Configure::write('Session.table', 'cake_sessions');


/**
 * The DATABASE_CONFIG::$var to use for database session handling.
 *
 * 'Session.save' must be set to 'database' in order to utilize this constant.
 */
//Configure::write('Session.database', 'default');


/**
 * The name of CakePHP's session cookie.
 *
 * Note the guidelines for Session names states: "The session name references
 * the session id in cookies and URLs. It should contain only alphanumeric
 * characters."
 * @link http://php.net/session_name
 */
Configure::write( 'Session.cookie', 'ECD' );

/**
 * Session time out time (in minutes). (but the documentation says 'in seconds')
 * Actual value depends on 'Security.level' setting.
 */
Configure::write( 'Session.timeout', '8000' );

/**
 * If set to false, sessions are not automatically started.
 */
Configure::write( 'Session.start', true );

/**
 * When set to false, HTTP_USER_AGENT will not be checked
 * in the session
 */
Configure::write( 'Session.checkAgent', true );

/**
 * The level of CakePHP security. The session timeout time defined
 * in 'Session.timeout' is multiplied according to the settings here.
 * Valid values:
 *
 * 'high'   Session timeout in 'Session.timeout' x 10
 * 'medium' Session timeout in 'Session.timeout' x 100
 * 'low'    Session timeout in 'Session.timeout' x 300
 *
 * CakePHP session IDs are also regenerated between requests if
 * 'Security.level' is set to 'high'.
 */
Configure::write( 'Security.level', 'medium' );

/**
 * A random string used in security hashing methods.
 */
Configure::write( 'Security.salt', 'efc488c8f80972aa21584df9060cfcb30dbef3db' );

/**
 * A random numeric string (digits only) used to encrypt/decrypt strings.
 */
Configure::write( 'Security.cipherSeed', '98271797238915345465612303645' );

/**
  Apply timestamps with the last modified time to static assets (js, css, images).
 * Will append a querystring parameter containing the time the file was modified. This is
 * useful for invalidating browser caches.
 *
 * Set to `true` to apply timestamps, when debug = 0, or set to 'force' to always enable
 * timestamping.
 */
//Configure::write('Asset.timestamp', true);
/**
 * Compress CSS output by removing comments, whitespace, repeating tags, etc.
 * This requires a/var/cache directory to be writable by the web server for caching.
 * and /vendors/csspp/csspp.php
 *
 * To use, prefix the CSS link URL with '/ccss/' instead of '/css/' or use HtmlHelper::css().
 */
//Configure::write('Asset.filter.css', 'css.php');


/**
 * Plug in your own custom JavaScript compressor by dropping a script in your webroot to handle the
 * output, and setting the config below to the name of the script.
 *
 * To use, prefix your JavaScript link URLs with '/cjs/' instead of '/js/' or use JavaScriptHelper::link().
 */
//Configure::write('Asset.filter.js', 'custom_javascript_output_filter.php');


/**
 * The classname and database used in CakePHP's
 * access control lists.
 */
Configure::write( 'Acl.classname', 'DbAcl' );
Configure::write( 'Acl.database', 'default' );

/**
 * If you are on PHP 5.3 uncomment this line and correct your server timezone
 * to fix the date & time related errors.
 */
date_default_timezone_set( 'Europe/Amsterdam' );

/**
 *
 * Cache Engine Configuration
 * Default settings provided below
 *
 * File storage engine.
 *
 * Cache::config('default', array(
 * 'engine' => 'File', //[required]
 * 'duration'=> 3600, //[optional]
 * 'probability'=> 100, //[optional]
 * 'path' => CACHE, //[optional] use system tmp directory - remember to use absolute path
 * 'prefix' => 'cake_', //[optional]  prefix every cache file with this string
 * 'lock' => false, //[optional]  use file locking
 * 'serialize' => true, [optional]
 * ));
 *
 *
 * APC (http://pecl.php.net/package/APC)
 *
 * Cache::config('default', array(
 * 'engine' => 'Apc', //[required]
 * 'duration'=> 3600, //[optional]
 * 'probability'=> 100, //[optional]
 * 'prefix' => Inflector::slug(APP_DIR) . '_', //[optional]  prefix every cache file with this string
 * ));
 *
 * Xcache (http://xcache.lighttpd.net/)
 *
 * Cache::config('default', array(
 * 'engine' => 'Xcache', //[required]
 * 'duration'=> 3600, //[optional]
 * 'probability'=> 100, //[optional]
 * 'prefix' => Inflector::slug(APP_DIR) . '_', //[optional] prefix every cache file with this string
 * 'user' => 'user', //user from xcache.admin.user settings
 * 'password' => 'password', //plaintext password (xcache.admin.pass)
 * ));
 *
 *
 * Memcache (http://www.danga.com/memcached/)
 *
 * Cache::config('default', array(
 * 'engine' => 'Memcache', //[required]
 * 'duration'=> 3600, //[optional]
 * 'probability'=> 100, //[optional]
 * 'prefix' => Inflector::slug(APP_DIR) . '_', //[optional]  prefix every cache file with this string
 * 'servers' => array(
 * '127.0.0.1:11211' // localhost, default port 11211
 * ), //[optional]
 * 'compress' => false, // [optional] compress data in Memcache (slower, but uses less memory)
 * ));
 *
 */

$prefix = md5(realpath('.'));

Cache::config( 'default', array (
    'engine' => 'Apc' ,
    'duration' => DAY,
    'prefix' => $prefix,
) );

Cache::config( 'ephemeral', array (
    'engine' => 'Apc' ,
    'duration' => HOUR,
    'prefix' => $prefix,
) );

// cache configuration for ldap queries - @see Medewerker::listByLdapGroup()
Cache::config('ldap', array(
    'engine' => 'Apc',
    'duration' => 4 * HOUR,
    'prefix' => $prefix,
));

Cache::config('_cake_model_', array(
    'engine' => 'Apc', //[required]
    'duration'=> WEEK,
    'probability'=> 100, //[optional]
    'prefix' => $prefix,
));

Cache::config('_cake_core_', array(
    'engine' => 'Apc', //[required]
    'duration'=> WEEK,
    'probability'=> 100, //[optional]
    'prefix' => $prefix,
));

Cache::config('association_query', array(
    'engine' => 'Apc', //[required]
    'duration'=> WEEK, //[optional]
    'probability'=> 100, //[optional]
    'prefix' => $prefix
));
//uitgenodigd, aangemeld, aanwezig, afwezig
Configure::write( 'Afmeldstatus', array(
    '' => '',
    'Aanwezig' => 'Aanwezig',
    'Afwezig' => 'Afwezig',
));

Configure::write( 'Postcodegebieden', array(
    '' => '',
    'Noord-oost' => 'Noord-oost',
    'Noord-West' => 'Noord-West',
    'Oud Noord' => 'Oud Noord',
    'Westerpark' => 'Westerpark',
    'Centrum West' => 'Centrum West',
    'Centrum Oost' => 'Centrum Oost',
    'Oostelijke Havengebied - Indische Buurt' => 'Oostelijke Havengebied - Indische Buurt',
    'Oud Oost' => 'Oud Oost',
    'Ijburg - Zeeburgereiland' => 'Ijburg - Zeeburgereiland',
    'Watergraafsmeer' => 'Watergraafsmeer',
    'De Pijp - Rivierenbuurt' => 'De Pijp - Rivierenbuurt',
    'Buitenveldert - Zuidas' => 'Buitenveldert - Zuidas',
    'Zuid' => 'Zuid',
    'De Baarsjes - Oud West' => 'De Baarsjes - Oud West',
    'Bos en Lommer' => 'Bos en Lommer',
    'Geuzenveld - Slotermeer' => 'Geuzenveld - Slotermeer',
    'Slotervaart' => 'Slotervaart',
    'Osdorp' => 'Osdorp',
    'De Aker - Nieuw Sloten' => 'De Aker - Nieuw Sloten',
    'Bijlmer Centrum' => 'Bijlmer Centrum',
    'Bijlmer Oost' => 'Bijlmer Oost',
    'Gaaperdam - Driemond' => 'Gaaperdam - Driemond',
    'Overig' => 'Overig',
));

Configure::write( 'Werkgebieden', array(
    '' => '',
    'Amstelveen' => 'Amstelveen',
    'Centrum' => 'Centrum',
    'Diemen' => 'Diemen',
    'Nieuw-West' => 'Nieuw-West',
    'Noord' => 'Noord',
    'Overig' => 'Overig',
    'Oost' => 'Oost',
    'West' => 'West',
    'Westpoort' => 'Westpoort',
    'Zuid' => 'Zuid',
    'Zuidoost' => 'Zuidoost',
));

Configure::write( 'Persoontypen', array(
    'Klant' => 'Klanten',
    'Vrijwilliger' => 'Vrijwilligers',
));

Configure::write( 'IzFase', array(
    //'iedereen' => 'Iedereen',
    'aanmelding' => 'Aanmelding',
    'onvolledig' => 'Onvolledige invoer',
    'wachtlijst' => 'Wachtlijst',
    'gekoppeld' => 'Gekoppeld',
    //'beeindigd' => 'Beeindigd',
    'afgesloten' => 'Afgesloten',
    'koppeling_nvt' => 'Koppeling n.v.t.',
));

Configure::write( 'Communicatietypen', array(
    'iedereen' => 'Iedereen',
    'communicatie_email' => 'E-Mail',
    'communicatie_post' => 'Post',
));

Configure::write( 'options_medewerker', array(
        '1' => 'medewerker',
        '2' => 'stagiair',
        '3' => 'vrijwilliger',
));

//list of klant countries indicating that the klant should be sent to AMOC
Configure::write('Landen.AMOC', array(
    5002, 5009, 5010, 5015, 5017, 5039, 5040, 5049, 6002, 6003, 6007, 6018,
    6029, 6037, 6039, 6066, 6067, 7003, 7024, 7028, 7044, 7047, 7050, 7064, 7065,
    7066
));

define( 'PFO_CLIENTEN_ALL', null );
define( 'PFO_CLIENTEN_HOOFDCLIENT', 1 );
define( 'PFO_CLIENTEN_SUPPORTCLIENT', 2 );

define('STATUS_DONE', 'STATUS_DONE');
define('STATUS_SKIPPED', 'STATUS_SKIPPED');
define('STATUS_INVALID', 'STATUS_INVALID');
define('STATUS_PROCESSING', 'STATUS_PROCESSING');
define('STATUS_PENDING', 'STATUS_PENDING');

/** Include the attachments plugin. */
include(APP.'plugins/media/config/core.php');

Configure::write('openingTimeCorrectionSec', 30 * MINUTE);
Configure::write('attachment.max_size', '10M');


$GLOBALS['ts_lastt']= microtime(true);
$GLOBALS['ts_start'] = $GLOBALS['ts_lastt'];
$GLOBALS['mem_usage'] = memory_get_usage();

/**
 * ts A general utility to profile the application: whenever you call it, a line with run time and memory usage is added to app/tmp/logs/ts
 * 
 * @param mixed $mark 
 * @access public
 * @return Integer the time lapse in seconds since the previous call.
 */
function ts($mark, $tag = 'tag') {
    return;

    if (!Configure::read('debug')) return;

    $now = microtime(true);
    $mem_usage = memory_get_usage();

    $delta = number_format($now - $GLOBALS['ts_lastt'], 5);
    $total = number_format($now - $GLOBALS['ts_start'], 3);

    $delta_m = number_format($mem_usage - $GLOBALS['mem_usage'], 0, '', ',');
    $total_m = number_format($mem_usage, 0, '', ',');

    CakeLog::write('ts', '; ' . $delta . '; ' . $total . '; ' . $delta_m . '; ' . $total_m . '; ' . $mark. '; '. getmypid(). '; '.$tag);
    debug('; ' . $delta . '; ' . $total . '; ' . $delta_m . '; ' . $total_m . '; ' . $mark);

    $GLOBALS['ts_lastt'] = $now;
    $GLOBALS['mem_usage'] = $mem_usage;

    return $delta;
}
