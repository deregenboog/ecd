<?php

$finder = Symfony\CS\Finder::create()
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

return Symfony\CS\Config::create()
    ->fixers(['-psr0'])
    ->finder($finder)
;
