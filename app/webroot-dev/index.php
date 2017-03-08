<?php

use Symfony\Component\HttpFoundation\Request;

umask(0000);

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

$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
