<?php

namespace InloopBundle\Command;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateRecentRegistrationsCommand extends ContainerAwareCommand
{
    /**
     * @var EntityManager
     */
    private $em;

    protected function configure()
    {
        $this->setName('inloop:recent-registrations:update');
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->em = $this->getContainer()->get('doctrine.orm.entity_manager');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $sql = 'DELETE FROM registraties_recent WHERE max_buiten < (NOW() + INTERVAL -15 day);';
        $this->em->getConnection()->query($sql);
    }
}
