<?php

namespace Application\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170713114136 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE zrm_reports ADD CONSTRAINT FK_C8EF119C3C427B2F FOREIGN KEY (klant_id) REFERENCES klanten (id)');
        $this->addSql('CREATE INDEX IDX_C8EF119C3C427B2F ON zrm_reports (klant_id)');

        $this->addSql('CREATE TABLE zrm_v2_reports (
                id INT AUTO_INCREMENT NOT NULL,
                klant_id int(11) NOT NULL,
                model VARCHAR(255) NOT NULL,
                foreign_key INT NOT NULL,
                request_module VARCHAR(255) NOT NULL,
                financien INT DEFAULT NULL,
                werk_opleiding INT DEFAULT NULL,
                tijdsbesteding INT DEFAULT NULL,
                huisvesting INT DEFAULT NULL,
                huiselijke_relaties INT DEFAULT NULL,
                geestelijke_gezondheid INT DEFAULT NULL,
                lichamelijke_gezondheid INT DEFAULT NULL,
                middelengebruik INT DEFAULT NULL,
                basale_adl INT DEFAULT NULL,
                instrumentele_adl INT DEFAULT NULL,
                sociaal_netwerk INT DEFAULT NULL,
                maatschappelijke_participatie INT DEFAULT NULL,
                justitie INT DEFAULT NULL,
                created DATETIME NOT NULL,
                modified DATETIME NOT NULL,
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB'
        );
        $this->addSql('CREATE TABLE zrm_v2_settings (
                id INT AUTO_INCREMENT NOT NULL,
                request_module VARCHAR(50) NOT NULL,
                financien TINYINT(1) DEFAULT NULL,
                werk_opleiding TINYINT(1) DEFAULT NULL,
                tijdsbesteding TINYINT(1) DEFAULT NULL,
                huisvesting TINYINT(1) DEFAULT NULL,
                huiselijke_relaties TINYINT(1) DEFAULT NULL,
                geestelijke_gezondheid TINYINT(1) DEFAULT NULL,
                lichamelijke_gezondheid TINYINT(1) DEFAULT NULL,
                middelengebruik TINYINT(1) DEFAULT NULL,
                basale_adl TINYINT(1) DEFAULT NULL,
                instrumentele_adl TINYINT(1) DEFAULT NULL,
                sociaal_netwerk TINYINT(1) DEFAULT NULL,
                maatschappelijke_participatie TINYINT(1) DEFAULT NULL,
                justitie TINYINT(1) DEFAULT NULL,
                created DATETIME NOT NULL,
                modified DATETIME NOT NULL, PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB'
        );
        $this->addSql('ALTER TABLE zrm_v2_reports ADD CONSTRAINT FK_751519083C427B2F FOREIGN KEY (klant_id) REFERENCES klanten (id)');
        $this->addSql('CREATE INDEX IDX_751519083C427B2F ON zrm_v2_reports (klant_id)');

        $this->addSql("INSERT INTO zrm_v2_settings (request_module, financien, werk_opleiding, tijdsbesteding, huisvesting, huiselijke_relaties, geestelijke_gezondheid, lichamelijke_gezondheid, middelengebruik, basale_adl, instrumentele_adl, sociaal_netwerk, maatschappelijke_participatie, justitie, created, modified) VALUES ('Intake', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', NOW(), NOW())");
        $this->addSql("INSERT INTO zrm_v2_settings (request_module, financien, werk_opleiding, tijdsbesteding, huisvesting, huiselijke_relaties, geestelijke_gezondheid, lichamelijke_gezondheid, middelengebruik, basale_adl, instrumentele_adl, sociaal_netwerk, maatschappelijke_participatie, justitie, created, modified) VALUES ('MaatschappelijkWerk', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', NOW(), NOW())");
        $this->addSql("INSERT INTO zrm_v2_settings (request_module, financien, werk_opleiding, tijdsbesteding, huisvesting, huiselijke_relaties, geestelijke_gezondheid, lichamelijke_gezondheid, middelengebruik, basale_adl, instrumentele_adl, sociaal_netwerk, maatschappelijke_participatie, justitie, created, modified) VALUES ('Awbz', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', NOW(), NOW())");
        $this->addSql("INSERT INTO zrm_v2_settings (request_module, financien, werk_opleiding, tijdsbesteding, huisvesting, huiselijke_relaties, geestelijke_gezondheid, lichamelijke_gezondheid, middelengebruik, basale_adl, instrumentele_adl, sociaal_netwerk, maatschappelijke_participatie, justitie, created, modified) VALUES ('Hi5', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', NOW(), NOW())");
        $this->addSql("INSERT INTO zrm_v2_settings (request_module, financien, werk_opleiding, tijdsbesteding, huisvesting, huiselijke_relaties, geestelijke_gezondheid, lichamelijke_gezondheid, middelengebruik, basale_adl, instrumentele_adl, sociaal_netwerk, maatschappelijke_participatie, justitie, created, modified) VALUES ('GroepsactiviteitenIntake', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', NOW(), NOW())");
        $this->addSql("INSERT INTO zrm_v2_settings (request_module, financien, werk_opleiding, tijdsbesteding, huisvesting, huiselijke_relaties, geestelijke_gezondheid, lichamelijke_gezondheid, middelengebruik, basale_adl, instrumentele_adl, sociaal_netwerk, maatschappelijke_participatie, justitie, created, modified) VALUES ('IzIntake', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', NOW(), NOW())");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->throwIrreversibleMigrationException();
    }
}
