<?php

$finder = Symfony\CS\Finder\DefaultFinder::create()
    ->exclude('config')
    ->exclude('config_sql')
    ->exclude('config_template')
    ->exclude('libs')
    ->exclude('locale')
    ->exclude('plugins/debug_kit')
    ->exclude('plugins/media')
    ->exclude('plugins/media')
    ->exclude('tmp')
    ->exclude('tmp_template')
    ->exclude('vendors')
    ->in('app');

return Symfony\CS\Config::create()
    ->finder($finder);
