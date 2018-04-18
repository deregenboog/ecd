<?php

$finder = PhpCsFixer\Finder::create()
    ->in('app')
    ->in('src')
    ->in('tests')
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
