<?php

use Doctrine\Common\Annotations\AnnotationRegistry;
use Composer\Autoload\ClassLoader;

if (!defined('ROOT')) {
    define('ROOT', dirname(dirname(__FILE__)));
}

if (!defined('APP_DIR')) {
    define('APP_DIR', basename(dirname(__FILE__)));
}

if (!defined('CAKE_CORE_INCLUDE_PATH')) {
    define('CAKE_CORE_INCLUDE_PATH', ROOT);
}

if (!defined('CORE_PATH')) {
    if (function_exists('ini_set') && ini_set('include_path', CAKE_CORE_INCLUDE_PATH.PATH_SEPARATOR.ROOT.DS.APP_DIR.DS.PATH_SEPARATOR.ini_get('include_path'))) {
        define('APP_PATH', null);
        define('CORE_PATH', null);
    } else {
        define('APP_PATH', ROOT.DS.APP_DIR.DS);
        define('CORE_PATH', CAKE_CORE_INCLUDE_PATH.DS);
    }
}
if (php_sapi_name() == 'cli-server') {
    var_dump(''); die;
    $_SERVER['PHP_SELF'] = '/'.basename(__FILE__);
}

// CakePHP autoloading
spl_autoload_register(function($class) {
    switch($class) {
        case ErrorHandler::class:
            require_once __DIR__.'/../cake/libs/error.php';
            break;
        case Controller::class:
            require_once __DIR__.'/../cake/libs/controller/controller.php';
            break;
        case AppController::class:
            require_once __DIR__.'/app_controller.php';
            break;
    }
});

/**
 * @var ClassLoader
 */
$loader = require __DIR__.'/../vendor/autoload.php';

AnnotationRegistry::registerLoader([$loader, 'loadClass']);

return $loader;
