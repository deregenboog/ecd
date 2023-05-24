<?php

namespace InloopBundle\Command;

use Doctrine\DBAL\Driver\Connection;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FixLaatsteZrmCommand extends \Symfony\Component\Console\Command\Command
{


    /** @var Connection */
    protected $conn;

    public function __construct()
    {
        $this->conn = $this->getContainer()->get('database_connection');
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('inloop:fix:laatste_zrm');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $sql = 'UPDATE klanten AS klant
            LEFT JOIN (
                SELECT klant_id, MAX(created) AS datum
                FROM zrm_reports
                GROUP BY klant_id
            ) AS laatste_zrm ON laatste_zrm.klant_id = klant.id
            SET klant.last_zrm = laatste_zrm.datum';


        $n = $this->conn->exec($sql);

        $output->writeln(sprintf('%d rows affected', $n));
        return 0;
    }
}
