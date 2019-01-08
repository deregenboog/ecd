<?php

namespace ErOpUitBundle\Command;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SyncCommand extends ContainerAwareCommand
{
    /**
     * @var EntityManager
     */
    private $em;

    protected function configure()
    {
        $this
            ->setName('eropuit:sync')
            ->setDescription('Sync data between old GA-module and new ErOpUit-module')
            ->addOption('dry-run')
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->em = $this->getContainer()->get('doctrine.orm.entity_manager');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->syncAfsluitingen($input, $output);
        $this->syncKlantdossiers($input, $output);
        $this->syncVrijwilligerdossiers($input, $output);
    }

    private function syncAfsluitingen(InputInterface $input, OutputInterface $output)
    {
        $sql = <<<EOT
INSERT IGNORE INTO eropuit_uitschrijfredenen (id, naam, created, modified)
    SELECT id, naam, created, modified FROM groepsactiviteiten_redenen;
EOT;

        $output->writeln($sql."\n");
        if (!$input->getOption('dry-run')) {
            $this->em->getConnection()->query($sql);
        }
    }

    private function syncKlantdossiers(InputInterface $input, OutputInterface $output)
    {
        $sql = <<<EOT
ALTER TABLE eropuit_klanten ADD UNIQUE tmp_index (klant_id);
INSERT IGNORE INTO eropuit_klanten(klant_id, inschrijfdatum, uitschrijfdatum, uitschrijfreden_id, communicatieEmail, communicatieTelefoon, communicatiePost, created, modified) (
    SELECT gk.klant_id, gk.startdatum, gk.einddatum, gk.groepsactiviteiten_reden_id, gk.communicatie_email, gk.communicatie_telefoon, gk.communicatie_post, created, modified
    FROM groepsactiviteiten_groepen_klanten gk
    INNER JOIN (
        SELECT MAX(id) AS id
        FROM groepsactiviteiten_groepen_klanten
        WHERE groepsactiviteiten_groep_id = 19
        GROUP BY klant_id
    ) AS laatste ON gk.id = laatste.id
);
ALTER TABLE eropuit_klanten DROP INDEX tmp_index;
EOT;

        $output->writeln($sql."\n");
        if (!$input->getOption('dry-run')) {
            $this->em->getConnection()->query($sql);
        }
    }

    private function syncVrijwilligerdossiers(InputInterface $input, OutputInterface $output)
    {
        $sql = <<<EOT
ALTER TABLE eropuit_vrijwilligers ADD UNIQUE tmp_index (vrijwilliger_id);
INSERT IGNORE INTO eropuit_vrijwilligers(vrijwilliger_id, inschrijfdatum, uitschrijfdatum, uitschrijfreden_id, communicatieEmail, communicatieTelefoon, communicatiePost, created, modified) (
    SELECT gv.vrijwilliger_id, gv.startdatum, gv.einddatum, gv.groepsactiviteiten_reden_id, gv.communicatie_email, gv.communicatie_telefoon, gv.communicatie_post, created, modified
    FROM groepsactiviteiten_groepen_vrijwilligers gv
    INNER JOIN (
        SELECT MAX(id) AS id
        FROM groepsactiviteiten_groepen_vrijwilligers
        WHERE groepsactiviteiten_groep_id = 19
        GROUP BY vrijwilliger_id
    ) AS laatste ON gv.id = laatste.id
);
ALTER TABLE eropuit_vrijwilligers DROP INDEX tmp_index;
EOT;

        $output->writeln($sql."\n");
        if (!$input->getOption('dry-run')) {
            $this->em->getConnection()->query($sql);
        }
    }
}
