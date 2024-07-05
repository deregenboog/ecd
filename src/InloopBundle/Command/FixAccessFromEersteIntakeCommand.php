<?php

namespace InloopBundle\Command;

use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FixAccessFromEersteIntakeCommand extends \Symfony\Component\Console\Command\Command
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
        $this->setName('inloop:fix:access_eerste_intake');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $sql = 'UPDATE
                    klanten AS k 
                    LEFT JOIN intakes fi ON fi.id = k.first_intake_id
                    LEFT JOIN intakes li ON li.id = k.laste_intake_id
                    SET
                    fi.verblijfstatus_id = li.verblijfstatus_id,
                    fi.toegang_inloophuis = li.toegang_inloophuis, 
                    fi.locatie1_id=  li.locatie1_id, 
                    fi.locatie3_id=  li.locatie3_id, 
                    fi.amoc_toegang_tot = li.amoc_toegang_tot,
                    fi.ondro_bong_toegang_van = li.ondro_bong_toegang_van, 
                    fi.overigen_toegang_van = li.overigen_toegang_van 
                    WHERE k.first_intake_id IS NOT NULL 
                    AND k.laste_intake_id IS NOT NULL
                    AND k.first_intake_id != k.laste_intake_id';

        $n = $this->conn->exec($sql);

        $output->writeln(sprintf('%d rows affected', $n));

        return 0;
    }
}
