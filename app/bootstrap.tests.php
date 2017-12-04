<?php

error_reporting(E_ALL & ~E_DEPRECATED);

require __DIR__.'/autoload.php';

$kernel = new AppKernel('test', true);
$kernel->loadClassCache();
$kernel->boot();
