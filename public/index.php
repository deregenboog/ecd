<?php
umask(0002); //https://symfony.com/doc/5.4/setup/file_permissions.html#3-without-using-acl
use App\Kernel;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

return function (array $context) {
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
