<?php

namespace OekraineBundle\Command;

use OekraineBundle\Service\AccessUpdater;
use OekraineBundle\Service\KlantDaoInterface;
use OekraineBundle\Service\LocatieDaoInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateAccessCommand extends ContainerAwareCommand
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
        $this->accessUpdater = $this->getContainer()->get('OekraineBundle\Service\AccessUpdater');
        $this->locatieDao = $this->getContainer()->get('OekraineBundle\Service\LocatieDao');
        $this->klantDao = $this->getContainer()->get('OekraineBundle\Service\BezoekerDao');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($input->getOption('locatie') && $input->getOption('klant')) {
            $output->writeln('Do not provide both "locatie" and "klant"');

            return;
        }

        if ($input->getOption('locatie')) {
            $locatie = $this->locatieDao->find($input->getOption('locatie'));
            $output->writeln('Updating access rules for location '.$locatie.'...');
            $this->accessUpdater->updateForLocation($locatie);
            $output->writeln('Finished!');

            return;
        }

        if ($input->getOption('klant')) {
            $klant = $this->klantDao->find($input->getOption('klant'));
            $output->writeln('Updating access rules for client '.$klant.'...');
            $this->accessUpdater->updateForClient($klant);
            $output->writeln('Finished!');

            return;
        }

        $output->writeln('Updating access rules for all active locations...');
        $this->accessUpdater->updateAll();
        $output->writeln('Finished!');
    }
}
