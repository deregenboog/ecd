<?php

namespace InloopBundle\Command;

use Doctrine\DBAL\Driver\Connection;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FixEersteIntakeKoppelingCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('inloop:fix:eerste_intake_koppeling');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $sql = 'UPDATE klanten AS klant
            LEFT JOIN (
                SELECT id, klant_id, MIN(datum_intake) AS datum_intake
                FROM intakes
                GROUP BY klant_id
            ) AS eerste_intake ON eerste_intake.klant_id = klant.id
            SET klant.first_intake_id = eerste_intake.id';

        /* @var Connection $conn */
        $conn = $this->getContainer()->get('database_connection');
        $n = $conn->exec($sql);

        $output->writeln(sprintf('%d rows affected', $n));
    }
}
