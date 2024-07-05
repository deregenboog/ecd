<?php

namespace InloopBundle\Command;

use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FixEersteIntakeKoppelingCommand extends \Symfony\Component\Console\Command\Command
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
        $this->setName('inloop:fix:eerste_intake_koppeling');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $sql = 'UPDATE klanten AS klant
            LEFT JOIN (
                SELECT id, klant_id, MIN(datum_intake) AS datum_intake
                FROM intakes
                GROUP BY klant_id
            ) AS eerste_intake ON eerste_intake.klant_id = klant.id
            SET klant.first_intake_id = eerste_intake.id';

        $n = $this->conn->exec($sql);

        $output->writeln(sprintf('%d rows affected', $n));

        return 0;
    }
}
