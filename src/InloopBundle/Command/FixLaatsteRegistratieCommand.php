<?php

namespace InloopBundle\Command;

use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FixLaatsteRegistratieCommand extends \Symfony\Component\Console\Command\Command
{
    /**
     * @var Connection
     */
    private $conn;

    public function __construct(Connection $conn)
    {
        $this->conn = $conn;

        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('inloop:fix:laatste_registratie');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $sql = 'UPDATE klanten AS klant
            LEFT JOIN (
                SELECT klant_id, MAX(buiten) AS buiten
                FROM registraties
                GROUP BY klant_id
            ) AS laatste_regisratie ON laatste_regisratie.klant_id = klant.id
            LEFT JOIN registraties AS registratie ON registratie.klant_id = klant.id AND registratie.buiten = laatste_regisratie.buiten
            SET klant.laatste_registratie_id = registratie.id';

        $n = $this->conn->exec($sql);

        $output->writeln(sprintf('%d rows affected', $n));

        return 0;
    }
}
