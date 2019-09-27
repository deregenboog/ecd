<?php

use Symfony\Component\Debug\Debug;
use Symfony\Component\HttpFoundation\Request;


// https://symfony.com/doc/3.4/setup/file_permissions.html

// If you don't want to setup permissions the proper way, just uncomment the following PHP line
// read https://symfony.com/doc/current/setup.html#checking-symfony-application-configuration-and-setup
// for more information
//umask(0000);

umask(0002);

// This check prevents access to debug front controllers that are deployed by accident to production servers.
// Feel free to remove this, extend it, or make something more sophisticated.
if (isset($_SERVER['HTTP_CLIENT_IP'])
    || isset($_SERVER['HTTP_X_FORWARDED_FOR'])
    || !(
        '192.168.' === substr(@$_SERVER['REMOTE_ADDR'], 0, 8)
        || '172.18.' === substr(@$_SERVER['REMOTE_ADDR'], 0, 7)
        || 'cli-server' === PHP_SAPI
        || '127.0.0.1' === $_SERVER['REMOTE_ADDR'])
) {
    header('HTTP/1.0 403 Forbidden');

    exit('You are not allowed to access this file. Check '.basename(__FILE__).' for more information.');
}

require __DIR__.'/../../vendor/autoload.php';
Debug::enable();

$kernel = new AppKernel('dev', true);
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
