<?php

namespace InloopBundle\Command;

use AppBundle\Entity\Klant;
use InloopBundle\Entity\Registratie;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Util\DateTimeUtil;
use InloopBundle\Service\RegistratieDaoInterface;
use Doctrine\ORM\EntityManager;

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
                SELECT id, klant_id, locatie_id, MAX(buiten) AS max_buiten
                FROM registraties
                WHERE closed = 1
                AND binnen > (NOW() + INTERVAL -15 day)
                GROUP BY klant_id, locatie_id;

            DELETE FROM registraties_recent WHERE max_buiten < (NOW() + INTERVAL -15 day);
        ';
        $this->em->getConnection()->query($sql);
    }
}
