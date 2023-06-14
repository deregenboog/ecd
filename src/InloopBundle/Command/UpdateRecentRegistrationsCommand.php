<?php

namespace InloopBundle\Command;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateRecentRegistrationsCommand extends \Symfony\Component\Console\Command\Command
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        parent::__construct();
    }


    protected function configure()
    {
        $this->setName('inloop:recent-registrations:update');
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
//        $this->em = $entityManager;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /*
        $sql = 'INSERT IGNORE INTO registraties_recent (registratie_id, locatie_id, klant_id, max_buiten)
            SELECT id, locatie_id, klant_id, MAX(buiten) AS max_buiten
            FROM registraties
            WHERE buiten >= (NOW() + INTERVAL -3 month) AND closed = 1
            GROUP BY klant_id, locatie_id, DATE(buiten)';
        $this->em->getConnection()->query($sql);
        */

        $sql = 'DELETE FROM registraties_recent WHERE max_buiten < (NOW() + INTERVAL -3 month);';
        $this->em->getConnection()->query($sql);
        return 0;
    }
}
