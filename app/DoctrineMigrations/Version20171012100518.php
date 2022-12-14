<?php

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171012100518 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE inloop_dossier_statussen (
            id INT AUTO_INCREMENT NOT NULL,
            klant_id INT NOT NULL,
            medewerker_id INT NULL,
            reden_id INT NULL,
            land_id INT NULL,
            datum DATE NOT NULL,
            toelichting LONGTEXT DEFAULT NULL,
            created DATETIME NOT NULL,
            modified DATETIME NOT NULL,
            class VARCHAR(255) NOT NULL,
            INDEX IDX_12D2B5703C427B2F (klant_id),
            INDEX IDX_12D2B5703D707F64 (medewerker_id),
            INDEX IDX_12D2B570D29703A5 (reden_id),
            INDEX IDX_12D2B5701994904A (land_id),
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE inloop_afsluiting_redenen (id INT AUTO_INCREMENT NOT NULL, naam VARCHAR(255) NOT NULL, actief TINYINT(1) NOT NULL, land TINYINT(1) NOT NULL, gewicht INT NOT NULL DEFAULT 0, created DATETIME NOT NULL, modified DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE inloop_dossier_statussen ADD CONSTRAINT FK_12D2B5703C427B2F FOREIGN KEY (klant_id) REFERENCES klanten (id)');
        $this->addSql('ALTER TABLE inloop_dossier_statussen ADD CONSTRAINT FK_12D2B5703D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
        $this->addSql('ALTER TABLE inloop_dossier_statussen ADD CONSTRAINT FK_12D2B570D29703A5 FOREIGN KEY (reden_id) REFERENCES inloop_afsluiting_redenen (id)');
        $this->addSql('ALTER TABLE inloop_dossier_statussen ADD CONSTRAINT FK_12D2B5701994904A FOREIGN KEY (land_id) REFERENCES landen (id)');
        $this->addSql('ALTER TABLE klanten ADD huidigeStatus_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE klanten ADD CONSTRAINT FK_F538C5BC8B2671BD FOREIGN KEY (huidigeStatus_id) REFERENCES inloop_dossier_statussen (id)');

        // add "Aanmelding" for the first "Intake" of each "Klant"
        $this->addSql("INSERT INTO inloop_dossier_statussen (klant_id, medewerker_id, datum, created, modified, class)
            SELECT klanten.id, medewerkers.id, MIN(datum_intake), NOW(), NOW(), 'Aanmelding'
            FROM klanten
            INNER JOIN intakes ON intakes.klant_id = klanten.id
            LEFT JOIN medewerkers ON intakes.medewerker_id = medewerkers.id
            LEFT JOIN inloop_dossier_statussen ON inloop_dossier_statussen.klant_id = klanten.id
            WHERE klanten.disabled = 0
            AND inloop_dossier_statussen.id IS NULL
            GROUP BY klanten.id
            ORDER BY klanten.id ASC
        ");

        // set current status
        $this->addSql('UPDATE klanten
            INNER JOIN inloop_dossier_statussen ON inloop_dossier_statussen.klant_id = klanten.id
            SET klanten.huidigeStatus_id = inloop_dossier_statussen.id
            WHERE klanten.huidigeStatus_id IS NULL
        ');

        // add options for "RedenAfsluiting"
        $this->addSql("INSERT INTO inloop_afsluiting_redenen (naam, actief, land, gewicht, created, modified) VALUES ('>3 jaar geen inloophuis bezocht', 1, 0, 0, NOW(), NOW())");
        $this->addSql("INSERT INTO inloop_afsluiting_redenen (naam, actief, land, gewicht, created, modified) VALUES ('Gerepatrieerd', 1, 1, 0, NOW(), NOW())");
        $this->addSql("INSERT INTO inloop_afsluiting_redenen (naam, actief, land, gewicht, created, modified) VALUES ('Overleden', 1, 0, 0, NOW(), NOW())");
        $this->addSql("INSERT INTO inloop_afsluiting_redenen (naam, actief, land, gewicht, created, modified) VALUES ('Overig', 1, 0, 100, NOW(), NOW())");
    }

    public function down(Schema $schema): void
    {
        $this->throwIrreversibleMigrationException();
    }
}
