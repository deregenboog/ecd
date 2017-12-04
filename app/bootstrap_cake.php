<?php

if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

if (!defined('WEBROOT_DIR')) {
    define('WEBROOT_DIR', basename(dirname(__FILE__)));
}

if (!defined('WWW_ROOT')) {
    define('WWW_ROOT', dirname(__FILE__).DS);
}

if ('cli-server' == php_sapi_name()) {
    $_SERVER['PHP_SELF'] = '/'.basename(__FILE__);
}

if (!defined('DISABLE_DEFAULT_ERROR_HANDLING')) {
    define('DISABLE_DEFAULT_ERROR_HANDLING', 1);
}

$handler = function ($code, $message, $file) {
    // ignore warnings from legacy Cake lib
    if (preg_match('#/cake/#', $file) || preg_match('#/app/plugins/#', $file)) {
        if (preg_match('/non-static method .* should not be called statically/', $message)
            || preg_match('/^Declaration of .* should be compatible with .*/', $message)
            ) {
            return true;
        }
    }

    // ignore known warnings due to using legacy Cake lib
    switch ($message) {
        case 'Declaration of AppBundle\Controller\SymfonyController::render($view, array $parameters = Array, ?Symfony\Component\HttpFoundation\Response $response = NULL) should be compatible with Controller::render($action = NULL, $layout = NULL, $file = NULL)':
        case 'Runtime Notice: Declaration of AppBundle\Controller\SymfonyController::redirect() should be compatible with Controller::redirect($url, $status = NULL, $exit = true)':
            var_dump($message); die;

            return true;
        default:
//             var_dump($message); die;
            break;
    }

    return false;
};

// set_error_handler($handler, E_WARNING);
// set_error_handler($handler);

require_once __DIR__.'/../cake/bootstrap.php';
