<?php

use Doctrine\Common\Annotations\AnnotationRegistry;
use Composer\Autoload\ClassLoader;

/** @var $loader ClassLoader */
$loader = require __DIR__.'/../vendor/autoload.php';

// CakePHP autoloading
require_once 'autoload_cake.php';

AnnotationRegistry::registerLoader([$loader, 'loadClass']);

return $loader;
