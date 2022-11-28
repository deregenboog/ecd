<?php

namespace AppBundle\Command;

use Fidry\AliceDataFixtures\LoaderInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command used to load the fixtures.
 */
class FixturesLoaderCommand extends Command
{
    protected static $defaultName = 'app:fixtures:load';

    /**
     * @var LoaderInterface
     */
    protected $loader;

    public function __construct(LoaderInterface $loader) {
        $this->loader = $loader;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Load data fixtures to your database.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $files = [
            'src/AppBundle/DataFixtures/ORM/fixtures.yml',
            'src/ClipBundle/DataFixtures/ORM/fixtures.yml',
            'src/DagbestedingBundle/DataFixtures/ORM/fixtures.yml',
            'src/GaBundle/DataFixtures/ORM/fixtures.yml',
            'src/ErOpUitBundle/DataFixtures/ORM/fixtures.yml',
            'src/HsBundle/DataFixtures/ORM/fixtures.yml',
            'src/InloopBundle/DataFixtures/ORM/fixtures.yml',
            'src/IzBundle/DataFixtures/ORM/fixtures.yml',
            'src/TwBundle/DataFixtures/ORM/fixtures.yml',
            'src/OekBundle/DataFixtures/ORM/fixtures.yml',
            // 'src/PfoBundle/DataFixtures/ORM/fixtures.yml',
        ];

        $this->loader->load($files);

        return 0;
    }
}
