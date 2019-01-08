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
        $sql = '
            REPLACE INTO registraties_recent (registratie_id, klant_id, locatie_id, max_buiten)
                SELECT registraties.id, registraties.klant_id, registraties.locatie_id, registraties.buiten AS max_buiten
                FROM registraties
                INNER JOIN (
                    SELECT klant_id, locatie_id, MAX(buiten) AS max_buiten FROM registraties WHERE closed = 1
                    AND binnen > (NOW() + INTERVAL -15 day)
                    GROUP BY klant_id, locatie_id
                ) AS recent ON registraties.klant_id = recent.klant_id AND registraties.locatie_id = recent.locatie_id AND registraties.buiten = recent.max_buiten
                WHERE closed = 1
                AND binnen > (NOW() + INTERVAL -15 day)
                GROUP BY klant_id, locatie_id;

            DELETE FROM registraties_recent WHERE max_buiten < (NOW() + INTERVAL -15 day);
        ';
        $this->em->getConnection()->query($sql);
    }
}
