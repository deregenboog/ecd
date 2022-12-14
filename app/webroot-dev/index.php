<?php

use Symfony\Component\ErrorHandler\Debug;
use Symfony\Component\ErrorHandler\DebugClassLoader;
use Symfony\Component\HttpFoundation\Request;


// If you don't want to setup permissions the proper way, just uncomment the following PHP line
// read https://symfony.com/doc/current/setup.html#checking-symfony-application-configuration-and-setup

// for more information
//umask(0000);
umask(0002);

//force https
$forceHttps = false;
if ($forceHttps === true && !(isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' ||
        $_SERVER['HTTPS'] == 1) ||
    isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
    $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https'))
{
    $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $redirect);
    exit();
}

// This check prevents access to debug front controllers that are deployed by accident to production servers.
// Feel free to remove this, extend it, or make something more sophisticated.
if (isset($_SERVER['HTTP_CLIENT_IP'])
    || isset($_SERVER['HTTP_X_FORWARDED_FOR'])
    || !(
        '192.168.' === substr(@$_SERVER['REMOTE_ADDR'], 0, 8)
        || preg_match('/172\.1[6-9]\./', substr(@$_SERVER['REMOTE_ADDR'], 0, 7))
        || preg_match('/172\.[2-3]\d\./', substr(@$_SERVER['REMOTE_ADDR'], 0, 7))
        || 'cli-server' === PHP_SAPI
        || '127.0.0.1' === $_SERVER['REMOTE_ADDR'])
) {
    header('HTTP/1.0 403 Forbidden');
    var_dump($_SERVER['REMOTE_ADDR']);

    exit('You are not allowed to access this file. Check '.basename(__FILE__).' for more information.');
}

require __DIR__.'/../../vendor/autoload.php';

//DebugClassLoader::enable();
Debug::enable();
//
$kernel = new \App\Kernel('dev', true);

$request = Request::createFromGlobals();
$response = $kernel->handle($request);

$response->send();
$kernel->terminate($request, $response);
