<?php

namespace AppBundle\Maker;

use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Exception\RuntimeCommandException;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Bundle\MakerBundle\Str;
use Symfony\Bundle\MakerBundle\Util\UseStatementGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\String\UnicodeString;

final class DaoMaker extends AbstractMaker
{
    public static function getCommandName(): string
    {
        return 'make:dao';
    }

    public static function getCommandDescription(): string
    {
        return 'Create a new data access service for an entity';
    }

    public function configureCommand(Command $command, InputConfiguration $inputConfig): void
    {
        $command
            ->addArgument('module', InputArgument::OPTIONAL, sprintf('Choose the module namespace for your data access service class (e.g. <fg=yellow>%sBundle</>)', Str::asClassName(Str::getRandomTerm())))
            ->addArgument('entity', InputArgument::OPTIONAL, sprintf('Choose the entity for which you want to create the data access service (e.g. <fg=yellow>%s</>)', Str::asClassName(Str::getRandomTerm())))
        ;
    }

    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator): void
    {
        $module = $input->getArgument('module');
        $entity = $input->getArgument('entity');
        $alias = (new UnicodeString($entity))->snake()->toString();

        $entityClassName = sprintf('\\%s\\Entity\\%s', $module, $entity);
        $entityClassNameDetails = $generator->createClassNameDetails($entityClassName, '', '');
        if (!class_exists($entityClassNameDetails->getFullName())) {
            throw new RuntimeCommandException(sprintf('The entity %s does not exist.', $entityClassNameDetails->getFullName()));
        }

        $daoInterfaceName = sprintf('\\%s\\Service\\%sDaoInterface', $module, $entity);
        $daoInterfaceNameDetails = $generator->createClassNameDetails($daoInterfaceName, '', '');

        $daoClassName = sprintf('\\%s\\Service\\%sDao', $module, $entity);
        $daoClassNameDetails = $generator->createClassNameDetails($daoClassName, '', '');

        $generator->generateClass(
            $daoInterfaceNameDetails->getFullName(),
            'skeleton/dao/DaoInterface.tpl.php',
            [
                'use_statements' => new UseStatementGenerator([
                    $entityClassNameDetails->getFullName(),
                ]),
                'entity_class' => $entityClassNameDetails->getShortName(),
                'alias' => $alias,
            ],
        );
        $generator->generateClass(
            $daoClassNameDetails->getFullName(),
            'skeleton/dao/Dao.tpl.php',
            [
                'use_statements' => new UseStatementGenerator([
                    AbstractDao::class,
                    $entityClassNameDetails->getFullName(),
                    FilterInterface::class,
                    ArrayCollection::class,
                ]),
                'interface_name' => $daoInterfaceNameDetails->getShortName(),
                'entity_class' => $entityClassNameDetails->getShortName(),
                'alias' => $alias,
            ],
        );
        $generator->writeChanges();

        $this->writeSuccessMessage($io);
    }

    public function configureDependencies(DependencyBuilder $dependencies): void
    {
    }
}
