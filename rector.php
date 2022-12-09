<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Rector\Php74\Rector\Property\TypedPropertyRector;
use Rector\Set\ValueObject\LevelSetList;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    // get parameters
    $containerConfigurator->import(\Rector\Symfony\);


    // Define what rule sets will be applied
    $containerConfigurator->import(LevelSetList::UP_TO_PHP_72);

    $parameters = $containerConfigurator->parameters();


    $parameters->set(Option::PATHS, [
        __DIR__ . '/src',
        __DIR__ . '/app',
        __DIR__ . '/tests',

    ]);
//    $parameters->set(
//        Option::SYMFONY_CONTAINER_XML_PATH_PARAMETER,
//        __DIR__ . '/var/cache/dev/App_KernelDevDebugContainer.xml'
//    );
    $parameters->set(Option::PHP_VERSION_FEATURES,\Rector\Core\ValueObject\PhpVersion::PHP_72);



//     get services (needed for register a single rule)
//     $services = $containerConfigurator->services();
//
//    // register a single rule
//     $services->set(TypedPropertyRector::class);
};
