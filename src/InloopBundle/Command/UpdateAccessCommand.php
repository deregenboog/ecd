<?php

namespace InloopBundle\Command;

use InloopBundle\Service\AccessUpdater;
use InloopBundle\Service\KlantDaoInterface;
use InloopBundle\Service\LocatieDaoInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateAccessCommand extends \Symfony\Component\Console\Command\Command
{
    /**
     * @var AccessUpdater
     */
    private $accessUpdater;

    /**
     * @var LocatieDaoInterface
     */
    private $locatieDao;

    /**
     * @var KlantDaoInterface
     */
    private $klantDao;
    public function __construct(\InloopBundle\Service\AccessUpdater $accessUpdater, \InloopBundle\Service\LocatieDao $locatieDao, \InloopBundle\Service\KlantDao $klantDao)
    {
        $this->accessUpdater = $accessUpdater;
        parent::__construct();
        $this->locatieDao = $locatieDao;
        $this->klantDao = $klantDao;
    }

    protected function configure()
    {
        $this
            ->setName('inloop:access:update')
            ->addOption('locatie', 'l', InputOption::VALUE_OPTIONAL)
            ->addOption('klant', 'k', InputOption::VALUE_OPTIONAL)
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->accessUpdater = $this->accessUpdater;
        $this->locatieDao = $this->locatieDao;
        $this->klantDao = $this->klantDao;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if ($input->getOption('locatie') && $input->getOption('klant')) {
            $output->writeln('Do not provide both "locatie" and "klant"');

            return 0;
        }

        if ($input->getOption('locatie')) {
            $locatie = $this->locatieDao->find($input->getOption('locatie'));
            $output->writeln('Updating access rules for location '.$locatie.'...');
            $this->accessUpdater->updateForLocation($locatie);
            $output->writeln('Finished!');

            return 0;
        }

        if ($input->getOption('klant')) {
            $klant = $this->klantDao->find($input->getOption('klant'));
            $output->writeln('Updating access rules for client '.$klant.'...');
            $this->accessUpdater->updateForClient($klant);
            $output->writeln('Finished!');

            return 0;
        }

        $output->writeln('Updating access rules for all active locations...');
        $this->accessUpdater->updateAll();
        $output->writeln('Finished!');
        return 0;
    }
}
