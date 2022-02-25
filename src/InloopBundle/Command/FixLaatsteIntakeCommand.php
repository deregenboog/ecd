<?php

namespace InloopBundle\Command;

use Doctrine\DBAL\Driver\Connection;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FixLaatsteIntakeCommand extends \Symfony\Component\Console\Command\Command
{
    protected function configure()
    {
        $this->setName('inloop:fix:laatste_intake');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $sql = 'UPDATE klanten AS klant
            LEFT JOIN (
                SELECT klant_id, MAX(datum_intake) AS datum_intake
                FROM intakes
                GROUP BY klant_id
            ) AS laatste_intake ON laatste_intake.klant_id = klant.id
            LEFT JOIN intakes AS intake ON intake.klant_id = klant.id AND intake.datum_intake = laatste_intake.datum_intake
            SET klant.laste_intake_id = intake.id';

        /* @var Connection $conn */
        $conn = $this->getContainer()->get('database_connection');
        $n = $conn->exec($sql);

        $output->writeln(sprintf('%d rows affected', $n));
        return 0;
    }
}
