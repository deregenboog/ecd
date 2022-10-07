<?php

namespace OekraineBundle\Command;

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
        $this->setName('oekraine:recent-registrations:update');
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->em = $this->getContainer()->get('doctrine.orm.entity_manager');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /*
        $sql = 'INSERT IGNORE INTO registraties_recent (registratie_id, locatie_id, klant_id, max_buiten)
            SELECT id, locatie_id, klant_id, MAX(buiten) AS max_buiten
            FROM registraties
            WHERE buiten >= (DATE(\'NOW\') + INTERVAL -3 month) AND closed = 1
            GROUP BY klant_id, locatie_id, DATE(buiten)';
        $this->em->getConnection()->query($sql);
        */

        $sql = 'DELETE FROM registraties_recent WHERE max_buiten < (DATE(\'NOW\') + INTERVAL -3 month);';
        $this->em->getConnection()->query($sql);
    }
}
