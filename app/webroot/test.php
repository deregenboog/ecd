<?php

use Symfony\Component\HttpFoundation\Request;

umask(0000);
set_time_limit(0);
ini_set('display_errors', 1);

if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}
if (!defined('WEBROOT_DIR')) {
    define('WEBROOT_DIR', basename(dirname(__FILE__)));
}
if (!defined('WWW_ROOT')) {
    define('WWW_ROOT', dirname(__FILE__).DS);
}

require __DIR__.'/../autoload.php';
include_once __DIR__.'/../bootstrap.php.cache';
$kernel = new AppKernel('dev', true);
$kernel->loadClassCache();
$kernel->boot();

require_once __DIR__.'/../../cake/bootstrap.php';
$cakeConfig = $kernel->getContainer()->get('cake.configuration');
foreach ($cakeConfig->all() as $key => $value) {
    \Configure::write($key, $value);
}

$corePath = App::core('cake');
if (isset($corePath[0])) {
    define('TEST_CAKE_CORE_INCLUDE_PATH', rtrim($corePath[0], DS).DS);
} else {
    define('TEST_CAKE_CORE_INCLUDE_PATH', CAKE_CORE_INCLUDE_PATH);
}

if (Configure::read('debug') < 1) {
    die(__('Debug setting does not allow access to this url.', true));
}

require_once CAKE_TESTS_LIB.'cake_test_suite_dispatcher.php';

$Dispatcher = new CakeTestSuiteDispatcher();
$Dispatcher->dispatch();
