<?php

$finder = PhpCsFixer\Finder::create()
    ->exclude('config')
    ->exclude('config_sql')
    ->exclude('config_template')
    ->exclude('libs')
    ->exclude('locale')
    ->exclude('plugins/debug_kit')
    ->exclude('plugins/media')
    ->exclude('plugins/twig')
    ->exclude('tmp')
    ->exclude('tmp_template')
    ->exclude('vendors')
    ->in('app')
    ->in('src')
;

return PhpCsFixer\Config::create()
    ->setRules([
        'psr0' => false,
        '@Symfony' => true,
        'array_syntax' => ['syntax' => 'short'],
    ])
    ->setFinder($finder)
;
