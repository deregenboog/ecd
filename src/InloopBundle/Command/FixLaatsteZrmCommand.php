<?php

namespace InloopBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FixLaatsteZrmCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('inloop:fix:laatste_zrm');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $manager = $this->getContainer()->get('doctrine.orm.entity_manager');

        $n = $manager->getConnection()->exec('
            UPDATE klanten
            LEFT JOIN (
                SELECT zrm_reports.klant_id, DATE(MAX(zrm_reports.created)) AS zrm_created
                FROM zrm_reports
                GROUP BY zrm_reports.klant_id
            ) AS zrms ON zrms.klant_id = klanten.id
            SET klanten.last_zrm = zrms.zrm_created
            WHERE klanten.last_zrm <> zrms.zrm_created
            OR klanten.last_zrm IS NULL
        ');

        $output->writeln(sprintf('%d klanten bijgewerkt', $n));
    }
}
