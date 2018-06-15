<?php

$finder = PhpCsFixer\Finder::create()
    ->exclude('app/config_sql')
    ->exclude('app/config_template')
    ->exclude('app/locale')
    ->exclude('app/plugins')
    ->exclude('app/sql')
    ->exclude('app/tmp')
    ->exclude('app/vendors')
    ->exclude('archive')
    ->exclude('bin')
    ->exclude('cake')
    ->exclude('docker')
    ->exclude('var')
    ->exclude('vendors')
    ->in(__DIR__)
;

return PhpCsFixer\Config::create()
->setRules([
    'psr0' => false,
    '@Symfony' => true,
    'array_syntax' => ['syntax' => 'short'],
        'ordered_imports' => true,
])
->setFinder($finder)
;
