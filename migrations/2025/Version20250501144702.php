<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250501144702 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE hs_incidenten (id INT AUTO_INCREMENT NOT NULL, klant_id INT NOT NULL, datum DATE NOT NULL, remark LONGTEXT DEFAULT NULL, politie TINYINT(1) DEFAULT 0 NOT NULL, ambulance TINYINT(1) DEFAULT 0 NOT NULL, crisisdienst TINYINT(1) DEFAULT 0 NOT NULL, created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, modified DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX IDX_E239C8C53C427B2F (klant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE incidenten (id INT AUTO_INCREMENT NOT NULL, klant_id INT NOT NULL, datum DATE NOT NULL, remark LONGTEXT DEFAULT NULL, politie TINYINT(1) DEFAULT 0 NOT NULL, ambulance TINYINT(1) DEFAULT 0 NOT NULL, crisisdienst TINYINT(1) DEFAULT 0 NOT NULL, created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, modified DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, discr VARCHAR(255) NOT NULL, INDEX IDX_E2A595E43C427B2F (klant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE inloop_incident_info (id INT AUTO_INCREMENT NOT NULL, incident_id INT NOT NULL, locatie_id INT NOT NULL, UNIQUE INDEX UNIQ_8750905F59E53FB9 (incident_id), UNIQUE INDEX UNIQ_8750905F4947630C (locatie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mw_incident_info (id INT AUTO_INCREMENT NOT NULL, incident_id INT NOT NULL, locatie_id INT NOT NULL, UNIQUE INDEX UNIQ_1E832E3C59E53FB9 (incident_id), UNIQUE INDEX UNIQ_1E832E3C4947630C (locatie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pfo_incidenten (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, datum DATE NOT NULL, remark LONGTEXT DEFAULT NULL, politie TINYINT(1) DEFAULT 0 NOT NULL, ambulance TINYINT(1) DEFAULT 0 NOT NULL, crisisdienst TINYINT(1) DEFAULT 0 NOT NULL, created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, modified DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX IDX_256DE80819EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE hs_incidenten ADD CONSTRAINT FK_E239C8C53C427B2F FOREIGN KEY (klant_id) REFERENCES hs_klanten (id)');
        $this->addSql('ALTER TABLE incidenten ADD CONSTRAINT FK_E2A595E43C427B2F FOREIGN KEY (klant_id) REFERENCES klanten (id)');
        $this->addSql('ALTER TABLE inloop_incident_info ADD CONSTRAINT FK_8750905F59E53FB9 FOREIGN KEY (incident_id) REFERENCES incidenten (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE inloop_incident_info ADD CONSTRAINT FK_8750905F4947630C FOREIGN KEY (locatie_id) REFERENCES locaties (id)');
        $this->addSql('ALTER TABLE mw_incident_info ADD CONSTRAINT FK_1E832E3C59E53FB9 FOREIGN KEY (incident_id) REFERENCES incidenten (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mw_incident_info ADD CONSTRAINT FK_1E832E3C4947630C FOREIGN KEY (locatie_id) REFERENCES locaties (id)');
        $this->addSql('ALTER TABLE pfo_incidenten ADD CONSTRAINT FK_256DE80819EB6921 FOREIGN KEY (client_id) REFERENCES pfo_clienten (id)');
        $this->addSql('ALTER TABLE inloop_incidenten ADD discr VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE hs_incidenten DROP FOREIGN KEY FK_E239C8C53C427B2F');
        $this->addSql('ALTER TABLE incidenten DROP FOREIGN KEY FK_E2A595E43C427B2F');
        $this->addSql('ALTER TABLE inloop_incident_info DROP FOREIGN KEY FK_8750905F59E53FB9');
        $this->addSql('ALTER TABLE inloop_incident_info DROP FOREIGN KEY FK_8750905F4947630C');
        $this->addSql('ALTER TABLE mw_incident_info DROP FOREIGN KEY FK_1E832E3C59E53FB9');
        $this->addSql('ALTER TABLE mw_incident_info DROP FOREIGN KEY FK_1E832E3C4947630C');
        $this->addSql('ALTER TABLE pfo_incidenten DROP FOREIGN KEY FK_256DE80819EB6921');
        $this->addSql('DROP TABLE hs_incidenten');
        $this->addSql('DROP TABLE incidenten');
        $this->addSql('DROP TABLE inloop_incident_info');
        $this->addSql('DROP TABLE mw_incident_info');
        $this->addSql('DROP TABLE pfo_incidenten');
    }
}
