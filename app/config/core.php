<?php


/**
 * This is core configuration file.
 *
 * Use it to configure core behavior of Cake.
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * @see          http://cakephp.org CakePHP(tm) Project
 * @since         CakePHP(tm) v 0.2.9
 *
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
$params = parse_ini_file('config.ini', true);

\Configure::write('LDAP.configuration', $params['ldap']);

/*
 * CakePHP Log Level:
 *
 * In case of Production Mode CakePHP gives you the possibility to continue logging errors.
 *
 * The following parameters can be used:
 *  Boolean: Set true/false to activate/deactivate logging
 *    Configure::write('log', true);
 *
 *  Integer: Use built-in PHP constants to set the error level (see error_reporting)
 *    Configure::write('log', E_ERROR | E_WARNING);
 *    Configure::write('log', E_ALL ^ E_NOTICE);
 */
    Configure::write('log', true);

/*
 * Application wide charset encoding
 */
    Configure::write('App.encoding', 'UTF-8');

/*
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

/*
 * Uncomment the define below to use CakePHP prefix routes.
 *
 * The value of the define determines the names of the routes
 * and their associated controller actions:
 *
 * Set to an array of prefixes you want to use in your application. Use for
 * admin or other prefixed routes.
 *
 * 	Routing.prefixes = array('admin', 'manager');
 *
 * Enables:
 *	`admin_index()` and `/admin/controller/index`
 *	`manager_index()` and `/manager/controller/index`
 *
 * [Note Routing.admin is deprecated in 1.3.  Use Routing.prefixes instead]
 */
    Configure::write('Routing.prefixes', ['admin']);

/*
 * Enable cache checking.
 *
 * If set to true, for view caching you must still use the controller
 * var $cacheAction inside your controllers to define caching settings.
 * You can either set it controller-wide by setting var $cacheAction = true,
 * or in each action using $this->cacheAction = true.
 *
 */
    //Configure::write('Cache.check', true);

/*
 * Defines the default error type when using the log() function. Used for
 * differentiating error logging and debugging. Currently PHP supports LOG_DEBUG.
 */
    define('LOG_ERROR', 2);

/*
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
 * the cake shell command: cake schema create Sessions
 *
 */
    Configure::write('Session.save', 'php');

/*
 * The model name to be used for the session model.
 *
 * 'Session.save' must be set to 'database' in order to utilize this constant.
 *
 * The model name set here should *not* be used elsewhere in your application.
 */
    //Configure::write('Session.model', 'Session');

/*
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

/*
 * The DATABASE_CONFIG::$var to use for database session handling.
 *
 * 'Session.save' must be set to 'database' in order to utilize this constant.
 */
    //Configure::write('Session.database', 'default');

/*
 * The name of CakePHP's session cookie.
 *
 * Note the guidelines for Session names states: "The session name references
 * the session id in cookies and URLs. It should contain only alphanumeric
 * characters."
 * @link http://php.net/session_name
 */
    Configure::write('Session.cookie', $params['session']['cookie']);

/*
 * Session time out time (in seconds).
 * Actual value depends on 'Security.level' setting.
 */
    Configure::write('Session.timeout', $params['session']['timeout']);

/*
 * If set to false, sessions are not automatically started.
 */
    Configure::write('Session.start', true);

/*
 * When set to false, HTTP_USER_AGENT will not be checked
 * in the session. You might want to set the value to false, when dealing with
 * older versions of IE, Chrome Frame or certain web-browsing devices and AJAX
 */
    Configure::write('Session.checkAgent', true);

/*
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
    Configure::write('Security.level', 'medium');

/*
 * A random string used in security hashing methods.
 */
    \Configure::write('Security.salt', $params['security']['salt']);
/*
 * A random numeric string (digits only) used to encrypt/decrypt strings.
 */
    \Configure::write('Security.cipherSeed', $params['security']['cipherSeed']);

/*
 * Apply timestamps with the last modified time to static assets (js, css, images).
 * Will append a querystring parameter containing the time the file was modified. This is
 * useful for invalidating browser caches.
 *
 * Set to `true` to apply timestamps when debug > 0. Set to 'force' to always enable
 * timestamping regardless of debug value.
 */
    //Configure::write('Asset.timestamp', true);
/*
 * Compress CSS output by removing comments, whitespace, repeating tags, etc.
 * This requires a/var/cache directory to be writable by the web server for caching.
 * and /vendors/csspp/csspp.php
 *
 * To use, prefix the CSS link URL with '/ccss/' instead of '/css/' or use HtmlHelper::css().
 */
    //Configure::write('Asset.filter.css', 'css.php');

/*
 * Plug in your own custom JavaScript compressor by dropping a script in your webroot to handle the
 * output, and setting the config below to the name of the script.
 *
 * To use, prefix your JavaScript link URLs with '/cjs/' instead of '/js/' or use JavaScriptHelper::link().
 */
    //Configure::write('Asset.filter.js', 'custom_javascript_output_filter.php');

/*
 * The classname and database used in CakePHP's
 * access control lists.
 */
    Configure::write('Acl.classname', 'DbAcl');
    Configure::write('Acl.database', 'default');

/*
 * If you are on PHP 5.3 uncomment this line and correct your server timezone
 * to fix the date & time related errors.
 */
    date_default_timezone_set($params['timezone']);

/*
 *
 * Cache Engine Configuration
 * Default settings provided below
 *
 * File storage engine.
 *
 * 	 Cache::config('default', array(
 *		'engine' => 'File', //[required]
 *		'duration'=> 3600, //[optional]
 *		'probability'=> 100, //[optional]
 * 		'path' => CACHE, //[optional] use system tmp directory - remember to use absolute path
 * 		'prefix' => 'cake_', //[optional]  prefix every cache file with this string
 * 		'lock' => false, //[optional]  use file locking
 * 		'serialize' => true, [optional]
 *	));
 *
 *
 * APC (http://pecl.php.net/package/APC)
 *
 * 	 Cache::config('default', array(
 *		'engine' => 'Apc', //[required]
 *		'duration'=> 3600, //[optional]
 *		'probability'=> 100, //[optional]
 * 		'prefix' => Inflector::slug(APP_DIR) . '_', //[optional]  prefix every cache file with this string
 *	));
 *
 * Xcache (http://xcache.lighttpd.net/)
 *
 * 	 Cache::config('default', array(
 *		'engine' => 'Xcache', //[required]
 *		'duration'=> 3600, //[optional]
 *		'probability'=> 100, //[optional]
 * 		'prefix' => Inflector::slug(APP_DIR) . '_', //[optional] prefix every cache file with this string
 *		'user' => 'user', //user from xcache.admin.user settings
 *      'password' => 'password', //plaintext password (xcache.admin.pass)
 *	));
 *
 *
 * Memcache (http://www.danga.com/memcached/)
 *
 * 	 Cache::config('default', array(
 *		'engine' => 'Memcache', //[required]
 *		'duration'=> 3600, //[optional]
 *		'probability'=> 100, //[optional]
 * 		'prefix' => Inflector::slug(APP_DIR) . '_', //[optional]  prefix every cache file with this string
 * 		'servers' => array(
 * 			'127.0.0.1:11211' // localhost, default port 11211
 * 		), //[optional]
 * 		'compress' => false, // [optional] compress data in Memcache (slower, but uses less memory)
 * 		'persistent' => true, // [optional] set this to false for non-persistent connections
 *	));
 *
 */

$prefix = md5(realpath('.'));

Cache::config('default', [
    'engine' => 'File',
    'duration' => DAY,
    'prefix' => $prefix,
]);

Cache::config('ephemeral', [
    'engine' => 'File',
    'duration' => HOUR,
    'prefix' => $prefix,
]);

// cache configuration for ldap queries - @see Medewerker::listByLdapGroup()
Cache::config('ldap', [
    'engine' => 'File',
    'duration' => 4 * HOUR,
    'prefix' => $prefix,
]);

Cache::config('_cake_model_', [
    'engine' => 'File', //[required]
    'duration' => WEEK,
    'probability' => 100, //[optional]
    'prefix' => $prefix,
]);

Cache::config('_cake_core_', [
    'engine' => 'File', //[required]
    'duration' => WEEK,
    'probability' => 100, //[optional]
    'prefix' => $prefix,
]);

Cache::config('association_query', [
    'engine' => 'File', //[required]
    'duration' => WEEK, //[optional]
    'probability' => 100, //[optional]
    'prefix' => $prefix,
]);

define('PFO_CLIENTEN_ALL', null);
define('PFO_CLIENTEN_HOOFDCLIENT', 1);
define('PFO_CLIENTEN_SUPPORTCLIENT', 2);

define('STATUS_DONE', 'STATUS_DONE');
define('STATUS_SKIPPED', 'STATUS_SKIPPED');
define('STATUS_INVALID', 'STATUS_INVALID');
define('STATUS_PROCESSING', 'STATUS_PROCESSING');
define('STATUS_PENDING', 'STATUS_PENDING');

/** Include the attachments plugin. */
include APP.'plugins/media/config/core.php';

Configure::write('openingTimeCorrectionSec', 30 * MINUTE);
Configure::write('attachment.max_size', '10M');

/**
 * Translate a string, and replace variables in it as given by the $params
 * array.
 * Variables in the string are given by :keywords, and these should
 * not be translated, of course.
 * Example __tr( "Expire in :d days", array ('d' => $days) );
 * See http://book.cakephp.org/view/1483/insert.
 */
function __tr($string, $params = [])
{
    if (!class_exists('String')) {
        App::import('Core', 'String');
    }

    // First translate.
    $message = __($string, true);
    // Then replace parameter keywords
    $message = String::insert($message, $params);

    return $message;
}

/**
 * registry_isset See if a $type and $key are set in the registry.
 *
 * @param mixed $type Mandatory object type
 * @param mixed $key  Optional object ID
 *
 * @return bool
 */
function registry_isset($type, $key)
{
    if (Configure::read('Cache.disable')) {
        return false;
    }

    return isset($GLOBALS['AplicationRegistry']["$type.$key"]);
}

/**
 * registry_delete Delete all or a chunk of the registry, depending of the specfied parameters.
 *
 * @param mixed $type Object type
 * @param mixed $key  Object ID
 */
function registry_delete($type, $key, $mem_cache = false, $config = 'default')
{
    if (Configure::read('Cache.disable')) {
        return false;
    }
    unset($GLOBALS['AplicationRegistry']["$type.$key"]);
    if ($mem_cache) {
        Cache::delete($type.'.'.$key, $config);
    }
}

function registry_reset($mem_cache = false, $config = 'default')
{
    if (Configure::read('Cache.disable')) {
        return false;
    }
    unset($GLOBALS['AplicationRegistry']);
    if ($mem_cache) {
        Cache::clear(false, $config);
    }
}

/**
 * registry_set Store a value in the registry, for the given object type and ID.
 *
 * @param mixed $type      Object type
 * @param mixed $key       Object ID
 * @param mixed $value     The value to store, can be anything
 * @param bool  $mem_cache Store the value also in the long term memory cache
 */
function registry_set($type, $key, $value, $mem_cache = false, $config = 'default')
{
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
 * @param mixed $type      Object type
 * @param mixed $key       Object ID
 * @param bool  $mem_cache If value is not in the registry, try to read it from the long term memory cache
 *
 * @return mixed The stored value, or NULL if not set
 */
function registry_get($type, $key = null, $mem_cache = false, $config = 'default')
{
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
function is_saved($haystack)
{
    if (!is_array($haystack)) {
        if (empty($haystack)) {
            return false;
        }

        return true;
    }
    if (in_array(0, $haystack)) {
        return false;
    }
    foreach ($haystack as $element) {
        if (is_array($element) && !is_saved($element)) {
            return false;
        }
    }

    return true;
}
