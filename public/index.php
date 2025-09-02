<?php

umask(0002); //https://symfony.com/doc/5.4/setup/file_permissions.html#3-without-using-acl
use App\Kernel;
// Check libxml error state
error_log("libxml_use_internal_errors: " . (libxml_use_internal_errors() ? 'TRUE' : 'FALSE'));

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';
return function (array $context) {
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
