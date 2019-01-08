<?php

namespace GaBundle\Command;

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
            ->setName('ga:sync')
            ->setDescription('Sync data between old and new GA-module')
            ->addOption('dry-run')
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->em = $this->getContainer()->get('doctrine.orm.entity_manager');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->syncGroepen($input, $output);
        $this->syncActiviteiten($input, $output);
        $this->syncKlantdossiers($input, $output);
        $this->syncVrijwilligerdossiers($input, $output);
        $this->syncKlantlidmaatschappen($input, $output);
        $this->syncVrijwilligerlidmaatschappen($input, $output);
        $this->syncKlantdeelnames($input, $output);
        $this->syncVrijwilligerdeelnames($input, $output);
        $this->syncKlantverslagen($input, $output);
        $this->syncVrijwilligerverslagen($input, $output);
        $this->syncKlantintakes($input, $output);
        $this->syncAfsluitingen($input, $output);
        $this->syncKlantafsluitingen($input, $output);
        $this->syncVrijwilligerafsluitingen($input, $output);
    }

    private function syncGroepen(InputInterface $input, OutputInterface $output)
    {
        $sql = <<<EOT
ALTER TABLE ga_groepen ADD UNIQUE tmp_index (naam, werkgebied);
INSERT IGNORE INTO ga_groepen(id, naam, startdatum, einddatum, created, modified, werkgebied, activiteitenRegistreren, discr)
    SELECT id, naam, startdatum, einddatum, created, modified, werkgebied, activiteiten_registreren, IF(type IS NOT NULL, type, 'ErOpUit')
    FROM groepsactiviteiten_groepen
    WHERE id <> 19;
ALTER TABLE ga_groepen DROP INDEX tmp_index;
EOT;

        $output->writeln($sql."\n");
        if (!$input->getOption('dry-run')) {
            $this->em->getConnection()->query($sql);
        }
    }

    private function syncActiviteiten(InputInterface $input, OutputInterface $output)
    {
        $sql = <<<EOT
ALTER TABLE ga_activiteiten ADD UNIQUE tmp_index (naam, groep_id, datum);
INSERT IGNORE INTO ga_activiteiten(id, groep_id, naam, datum, afgesloten, created, modified)
    SELECT id, groepsactiviteiten_groep_id, naam, CONCAT(datum, ' ', time) AS dt, afgesloten, created, modified
    FROM groepsactiviteiten;
ALTER TABLE ga_activiteiten DROP INDEX tmp_index;
EOT;

        $output->writeln($sql."\n");
        if (!$input->getOption('dry-run')) {
            $this->em->getConnection()->query($sql);
        }
    }

    private function syncKlantdossiers(InputInterface $input, OutputInterface $output)
    {
        $sql = <<<EOT
ALTER TABLE ga_dossiers ADD UNIQUE tmp_index (klant_id);
INSERT IGNORE INTO ga_dossiers(klant_id, discr, aanmelddatum, created, modified) (
    SELECT klant_id, 'Klantdossier', IFNULL(MIN(dossiers.created), NOW()), IFNULL(MIN(dossiers.created), NOW()), IFNULL(MAX(dossiers.modified), NOW()) FROM (
        SELECT foreign_key AS klant_id, created, modified
            FROM groepsactiviteiten_intakes
            WHERE model = 'Klant'
        UNION
        SELECT foreign_key AS klant_id, intakedatum AS created, modified
            FROM groepsactiviteiten_intakes
            WHERE model = 'Klant'
        UNION
        SELECT foreign_key AS klant_id, created, modified
            FROM groepsactiviteiten_verslagen
            WHERE model = 'Klant'
        UNION
        SELECT k.klant_id, k.created, k.modified
            FROM groepsactiviteiten_klanten k
            INNER JOIN groepsactiviteiten g ON k.groepsactiviteit_id = g.id
            WHERE g.groepsactiviteiten_groep_id <> 19
        UNION
        SELECT klant_id, created, modified
            FROM groepsactiviteiten_groepen_klanten
            WHERE  groepsactiviteiten_groep_id <> 19
    ) dossiers
    GROUP BY dossiers.klant_id
);
ALTER TABLE ga_dossiers DROP INDEX tmp_index;
EOT;

        $output->writeln($sql."\n");
        if (!$input->getOption('dry-run')) {
            $this->em->getConnection()->query($sql);
        }
    }

    private function syncVrijwilligerdossiers(InputInterface $input, OutputInterface $output)
    {
        $sql = <<<EOT
ALTER TABLE ga_dossiers ADD UNIQUE tmp_index (vrijwilliger_id);
INSERT IGNORE INTO ga_dossiers(vrijwilliger_id, discr, aanmelddatum, created, modified) (
    SELECT vrijwilliger_id, 'Vrijwilligerdossier', IFNULL(MIN(dossiers.created), NOW()), IFNULL(MIN(dossiers.created), NOW()), IFNULL(MAX(dossiers.modified), NOW()) FROM (
        SELECT foreign_key AS vrijwilliger_id, created, modified
            FROM groepsactiviteiten_intakes
            WHERE model = 'Vrijwilliger'
        UNION
        SELECT foreign_key AS vrijwilliger_id, intakedatum AS created, modified
            FROM groepsactiviteiten_intakes
            WHERE model = 'Vrijwilliger'
        UNION
        SELECT foreign_key AS vrijwilliger_id, created, modified
            FROM groepsactiviteiten_verslagen
            WHERE model = 'Vrijwilliger'
        UNION
        SELECT v.vrijwilliger_id, v.created, v.modified
            FROM groepsactiviteiten_vrijwilligers v
            INNER JOIN groepsactiviteiten g ON v.groepsactiviteit_id = g.id
            WHERE g.groepsactiviteiten_groep_id <> 19
        UNION
        SELECT vrijwilliger_id, created, modified
            FROM groepsactiviteiten_groepen_vrijwilligers
            WHERE groepsactiviteiten_groep_id <> 19
    ) dossiers
    GROUP BY dossiers.vrijwilliger_id
);
ALTER TABLE ga_dossiers DROP INDEX tmp_index;
EOT;

        $output->writeln($sql."\n");
        if (!$input->getOption('dry-run')) {
            $this->em->getConnection()->query($sql);
        }
    }

    private function syncKlantlidmaatschappen(InputInterface $input, OutputInterface $output)
    {
        $sql = <<<EOT
ALTER TABLE ga_lidmaatschappen ADD UNIQUE tmp_index (groep_id, dossier_id);
INSERT IGNORE INTO ga_lidmaatschappen(dossier_id, groep_id, startdatum, einddatum, communicatieEmail, communicatieTelefoon, communicatiePost, created, modified) (
    SELECT d.id AS dossier_id, gk.groepsactiviteiten_groep_id, gk.startdatum, gk.einddatum, gk.communicatie_email, gk.communicatie_telefoon, gk.communicatie_post, gk.created, gk.modified
    FROM groepsactiviteiten_groepen_klanten gk
    INNER JOIN ga_dossiers d ON d.klant_id = gk.klant_id
    INNER JOIN (
        SELECT MAX(id) AS id
        FROM groepsactiviteiten_groepen_klanten
        WHERE groepsactiviteiten_groep_id <> 19
        GROUP BY klant_id, groepsactiviteiten_groep_id
    ) AS laatste ON gk.id = laatste.id
);
ALTER TABLE ga_lidmaatschappen DROP INDEX tmp_index;
EOT;

        $output->writeln($sql."\n");
        if (!$input->getOption('dry-run')) {
            $this->em->getConnection()->query($sql);
        }
    }

    private function syncVrijwilligerlidmaatschappen(InputInterface $input, OutputInterface $output)
    {
        $sql = <<<EOT
ALTER TABLE ga_lidmaatschappen ADD UNIQUE tmp_index (groep_id, dossier_id);
INSERT IGNORE INTO ga_lidmaatschappen(dossier_id, groep_id, startdatum, einddatum, communicatieEmail, communicatieTelefoon, communicatiePost, created, modified) (
    SELECT d.id AS dossier_id, gv.groepsactiviteiten_groep_id, gv.startdatum, gv.einddatum, gv.communicatie_email, gv.communicatie_telefoon, gv.communicatie_post, gv.created, gv.modified
    FROM groepsactiviteiten_groepen_vrijwilligers gv
    INNER JOIN ga_dossiers d ON d.vrijwilliger_id = gv.vrijwilliger_id
    INNER JOIN (
        SELECT MAX(id) AS id
        FROM groepsactiviteiten_groepen_vrijwilligers
        WHERE groepsactiviteiten_groep_id <> 19
        GROUP BY vrijwilliger_id, groepsactiviteiten_groep_id
    ) AS laatste ON gv.id = laatste.id
);
ALTER TABLE ga_lidmaatschappen DROP INDEX tmp_index;
EOT;

        $output->writeln($sql."\n");
        if (!$input->getOption('dry-run')) {
            $this->em->getConnection()->query($sql);
        }
    }

    private function syncKlantdeelnames(InputInterface $input, OutputInterface $output)
    {
        $sql = <<<EOT
ALTER TABLE ga_deelnames ADD UNIQUE tmp_index (activiteit_id, dossier_id);
INSERT IGNORE INTO ga_deelnames(dossier_id, activiteit_id, status, created, modified) (
    SELECT d.id AS dossier_id, gk.groepsactiviteit_id, LOWER(gk.afmeld_status), gk.created, gk.modified
    FROM groepsactiviteiten_klanten gk
    INNER JOIN ga_dossiers d ON d.klant_id = gk.klant_id
);
ALTER TABLE ga_deelnames DROP INDEX tmp_index;
EOT;

        $output->writeln($sql."\n");
        if (!$input->getOption('dry-run')) {
            $this->em->getConnection()->query($sql);
        }
    }

    private function syncVrijwilligerdeelnames(InputInterface $input, OutputInterface $output)
    {
        $sql = <<<EOT
ALTER TABLE ga_deelnames ADD UNIQUE tmp_index (activiteit_id, dossier_id);
INSERT IGNORE INTO ga_deelnames(dossier_id, activiteit_id, status, created, modified) (
    SELECT d.id AS dossier_id, gv.groepsactiviteit_id, LOWER(gv.afmeld_status), gv.created, gv.modified
    FROM groepsactiviteiten_vrijwilligers gv
    INNER JOIN ga_dossiers d ON d.vrijwilliger_id = gv.vrijwilliger_id
);
ALTER TABLE ga_deelnames DROP INDEX tmp_index;
EOT;

        $output->writeln($sql."\n");
        if (!$input->getOption('dry-run')) {
            $this->em->getConnection()->query($sql);
        }
    }

    private function syncKlantverslagen(InputInterface $input, OutputInterface $output)
    {
        $sql = <<<EOT
ALTER TABLE ga_verslagen ADD UNIQUE tmp_index (dossier_id, medewerker_id, opmerking(255), created, modified);
INSERT IGNORE INTO ga_verslagen(dossier_id, medewerker_id, opmerking, created, modified) (
    SELECT d.id, v.medewerker_id, v.opmerking, v.created, v.modified
    FROM groepsactiviteiten_verslagen v
    INNER JOIN ga_dossiers d ON d.klant_id = v.foreign_key
    WHERE v.model = 'Klant'
);
ALTER TABLE ga_verslagen DROP INDEX tmp_index;
EOT;

        $output->writeln($sql."\n");
        if (!$input->getOption('dry-run')) {
            $this->em->getConnection()->query($sql);
        }
    }

    private function syncVrijwilligerverslagen(InputInterface $input, OutputInterface $output)
    {
        $sql = <<<EOT
ALTER TABLE ga_verslagen ADD UNIQUE tmp_index (dossier_id, medewerker_id, opmerking(255), created, modified);
INSERT IGNORE INTO ga_verslagen(dossier_id, medewerker_id, opmerking, created, modified) (
    SELECT d.id, v.medewerker_id, v.opmerking, v.created, v.modified
    FROM groepsactiviteiten_verslagen v
    INNER JOIN ga_dossiers d ON d.vrijwilliger_id = v.foreign_key
    WHERE v.model = 'Vrijwilliger'
);
ALTER TABLE ga_verslagen DROP INDEX tmp_index;
EOT;

        $output->writeln($sql."\n");
        if (!$input->getOption('dry-run')) {
            $this->em->getConnection()->query($sql);
        }
    }

    private function syncKlantintakes(InputInterface $input, OutputInterface $output)
    {
        $sql = <<<EOT
ALTER TABLE ga_intakes ADD UNIQUE tmp_index (dossier_id, medewerker_id, gespreksverslag(255), intakedatum, created, modified);
INSERT IGNORE INTO ga_intakes(dossier_id, medewerker_id, gespreksverslag, intakedatum, informele_zorg, dagbesteding, inloophuis, hulpverlening, ondernemen, overdag, ontmoeten, regelzaken, gezin_met_kinderen, created, modified) (
    SELECT d.id, i.medewerker_id, i.gespreksverslag, i.intakedatum, i.informele_zorg, i.dagbesteding, i.inloophuis, i.hulpverlening, i.ondernemen, i.overdag, i.ontmoeten, i.regelzaken, i.gezin_met_kinderen, i.created, i.modified
    FROM groepsactiviteiten_intakes i
    INNER JOIN ga_dossiers d ON d.klant_id = i.foreign_key
    WHERE i.model = 'Klant'
);
ALTER TABLE ga_intakes DROP INDEX tmp_index;
EOT;

        $output->writeln($sql."\n");
        if (!$input->getOption('dry-run')) {
            $this->em->getConnection()->query($sql);
        }
    }

    private function syncAfsluitingen(InputInterface $input, OutputInterface $output)
    {
        $sql = <<<EOT
INSERT IGNORE INTO ga_dossierafsluitredenen (id, naam, created, modified)
    SELECT id, naam, created, modified FROM groepsactiviteiten_afsluitingen;
EOT;

        $output->writeln($sql."\n");
        if (!$input->getOption('dry-run')) {
            $this->em->getConnection()->query($sql);
        }
    }

    private function syncKlantafsluitingen(InputInterface $input, OutputInterface $output)
    {
        $sql = <<<EOT
UPDATE ga_dossiers d
INNER JOIN groepsactiviteiten_intakes i ON d.klant_id = i.foreign_key AND i.model = 'Klant'
SET d.afsluitdatum = i.afsluitdatum
WHERE d.afsluitdatum IS NULL AND i.afsluitdatum IS NOT NULL;

UPDATE ga_dossiers d
INNER JOIN groepsactiviteiten_intakes i ON d.klant_id = i.foreign_key AND i.model = 'Klant'
SET d.afsluitreden_id = i.groepsactiviteiten_afsluiting_id
WHERE d.afsluitreden_id IS NULL AND i.groepsactiviteiten_afsluiting_id IS NOT NULL;
EOT;

        $output->writeln($sql."\n");
        if (!$input->getOption('dry-run')) {
            $this->em->getConnection()->query($sql);
        }
    }

    private function syncVrijwilligerafsluitingen(InputInterface $input, OutputInterface $output)
    {
        $sql = <<<EOT
UPDATE ga_dossiers d
INNER JOIN groepsactiviteiten_intakes i ON d.vrijwilliger_id = i.foreign_key AND i.model = 'Vrijwilliger'
SET d.afsluitdatum = i.afsluitdatum
WHERE d.afsluitdatum IS NULL AND i.afsluitdatum IS NOT NULL;

UPDATE ga_dossiers d
INNER JOIN groepsactiviteiten_intakes i ON d.vrijwilliger_id = i.foreign_key AND i.model = 'Vrijwilliger'
SET d.afsluitreden_id = i.groepsactiviteiten_afsluiting_id
WHERE d.afsluitreden_id IS NULL AND i.groepsactiviteiten_afsluiting_id IS NOT NULL;
EOT;

        $output->writeln($sql."\n");
        if (!$input->getOption('dry-run')) {
            $this->em->getConnection()->query($sql);
        }
    }
}
