<?php

@define('DS', DIRECTORY_SEPARATOR);
@define('ROOT', dirname(dirname(__FILE__)));
@define('APP_DIR', basename(dirname(__FILE__)));
@define('CAKE_CORE_INCLUDE_PATH', ROOT);

if (!defined('CORE_PATH')) {
    if (function_exists('ini_set') && ini_set('include_path', CAKE_CORE_INCLUDE_PATH.PATH_SEPARATOR.ROOT.DS.APP_DIR.DS.PATH_SEPARATOR.ini_get('include_path'))) {
        define('APP_PATH', null);
        define('CORE_PATH', null);
    } else {
        define('APP_PATH', ROOT.DS.APP_DIR.DS);
        define('CORE_PATH', CAKE_CORE_INCLUDE_PATH.DS);
    }
}

require_once __DIR__.'/../cake/bootstrap.php';
