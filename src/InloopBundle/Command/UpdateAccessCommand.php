<?php

namespace InloopBundle\Command;

use InloopBundle\Service\AccessUpdater;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateAccessCommand extends ContainerAwareCommand
{
    /**
     * @var AccessUpdater
     */
    private $accessUpdater;

    protected function configure()
    {
        $this->setName('inloop:access:update');
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->accessUpdater = $this->getContainer()->get('InloopBundle\Service\AccessUpdater');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Updating access rules for all active locations...');
        $this->accessUpdater->updateAll();
        $output->writeln('Finished!');
    }
}
