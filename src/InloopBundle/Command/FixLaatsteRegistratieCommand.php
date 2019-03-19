<?php

namespace InloopBundle\Command;

use Doctrine\DBAL\Driver\Connection;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FixLaatsteRegistratieCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('inloop:fix:laatste_registratie');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $sql = 'UPDATE klanten AS klant
            LEFT JOIN (
                SELECT klant_id, MAX(buiten) AS buiten
                FROM registraties
                GROUP BY klant_id
            ) AS laatste_regisratie ON laatste_regisratie.klant_id = klant.id
            LEFT JOIN registraties AS registratie ON registratie.klant_id = klant.id AND registratie.buiten = laatste_regisratie.buiten
            SET klant.laatste_registratie_id = registratie.id';

        /* @var Connection $conn */
        $conn = $this->getContainer()->get('database_connection');
        $n = $conn->exec($sql);

        $output->writeln(sprintf('%d rows affected', $n));
    }
}
