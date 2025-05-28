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
        // #FARHAD TA INJA OMADAM
        $sql = 'UPDATE
                    klanten AS k 
                    LEFT JOIN intakes fi ON fi.id = k.first_intake_id
                    LEFT JOIN intakes li ON li.id = k.laste_intake_id
                    LEFT JOIN inloop_access_fields AS faf ON faf.id = fi.id
                    LEFT JOIN inloop_access_fields AS laf ON laf.id = li.id
                    SET
                    faf.verblijfstatus_id = laf.verblijfstatus_id,
                    faf.toegang_inloophuis = laf.toegang_inloophuis, 
                    faf.gebruikersruimte_id=  laf.gebruikersruimte_id, 
                    -- #FARHAD ? @JAN IN DIGE CHIYE????
                    fi.locatie3_id=  li.locatie3_id, 
                    -- #FARHAD ? @JAN UNKNOWN
                    fi.amoc_toegang_tot = li.amoc_toegang_tot,
                    fi.ondro_bong_toegang_van = li.ondro_bong_toegang_van, 
                    faf.overigen_toegang_van = laf.overigen_toegang_van 
                    WHERE k.first_intake_id IS NOT NULL 
                    AND k.laste_intake_id IS NOT NULL
                    AND k.first_intake_id != k.laste_intake_id';

        $n = $this->conn->exec($sql);

        $output->writeln(sprintf('%d rows affected', $n));

        return 0;
    }
}
