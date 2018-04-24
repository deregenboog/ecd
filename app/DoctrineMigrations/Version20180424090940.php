<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180424090940 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE zrm_reports ADD discr VARCHAR(5) NOT NULL, ADD financien INT DEFAULT NULL, ADD werk_opleiding INT DEFAULT NULL, ADD tijdsbesteding INT DEFAULT NULL, ADD huiselijke_relaties INT DEFAULT NULL, ADD lichamelijke_gezondheid INT DEFAULT NULL, ADD middelengebruik INT DEFAULT NULL, ADD basale_adl INT DEFAULT NULL, ADD instrumentele_adl INT DEFAULT NULL');
        $this->addSql("UPDATE zrm_reports SET discr = 'zrmv1' WHERE discr = ''");
        $this->addSql("INSERT INTO zrm_reports (discr, klant_id, model, foreign_key, request_module, financien, werk_opleiding, tijdsbesteding, huisvesting, huiselijke_relaties, geestelijke_gezondheid, lichamelijke_gezondheid, middelengebruik, basale_adl, instrumentele_adl, sociaal_netwerk, maatschappelijke_participatie, justitie, created, modified)
            SELECT 'zrmv2', klant_id, model, foreign_key, request_module, financien, werk_opleiding, tijdsbesteding, huisvesting, huiselijke_relaties, geestelijke_gezondheid, lichamelijke_gezondheid, middelengebruik, basale_adl, instrumentele_adl, sociaal_netwerk, maatschappelijke_participatie, justitie, created, modified
            FROM zrm_v2_reports");
        $this->addSql('UPDATE klanten
            INNER JOIN (
                SELECT klant_id, DATE(MAX(zrm_reports.created)) AS max_date
                FROM zrm_reports
                GROUP BY klant_id
            ) zrm ON zrm.klant_id = klanten.id
            SET klanten.last_zrm = zrm.max_date');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->throwIrreversibleMigrationException();
    }
}
