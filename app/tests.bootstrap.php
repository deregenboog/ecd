<?php

umask(0000);

set_time_limit(0);

if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}
if (!defined('WEBROOT_DIR')) {
    define('WEBROOT_DIR', basename(dirname(__FILE__)));
}
if (!defined('WWW_ROOT')) {
    define('WWW_ROOT', dirname(__FILE__).DS);
}
define('CAKEPHP_SHELL', true);

// passthru(sprintf('php "%s/console" cache:clear --env=test', realpath(__DIR__.'/../bin')));

require __DIR__.'/../app/autoload.php';

$kernel = new AppKernel('test', true);
$kernel->boot();

require_once CORE_PATH.'cake'.DS.'bootstrap.php';
