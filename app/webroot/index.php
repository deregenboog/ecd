<?php

define('WEBROOT_DIR', basename(dirname(__FILE__)));
define('WWW_ROOT', dirname(__FILE__).'/');

use Symfony\Component\HttpFoundation\Request;

// https://symfony.com/doc/3.4/setup/file_permissions.html
umask(0002);

/** @var \Composer\Autoload\ClassLoader $loader */
$loader = require __DIR__.'/../autoload.php';
include_once __DIR__.'/../bootstrap.php.cache';

$kernel = new AppKernel('prod', false);
if (PHP_VERSION_ID < 70000) {
    $kernel->loadClassCache();
}
//$kernel = new AppCache($kernel);

// When using the HttpCache, you need to call the method in your front controller instead of relying on the configuration parameter
//Request::enableHttpMethodParameterOverride();
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
