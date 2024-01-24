<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191125080129 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE uhk_deelnemers (id INT AUTO_INCREMENT NOT NULL, klant_id INT DEFAULT NULL, medewerker_id INT DEFAULT NULL, contactpersoonNazorg VARCHAR(255) DEFAULT NULL, aanmeldNaam VARCHAR(255) NOT NULL, aanmelddatum DATE NOT NULL, active TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_739D2F673C427B2F (klant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE uhk_verslagen (id INT AUTO_INCREMENT NOT NULL, deelnemer_id INT DEFAULT NULL, medewerker_id INT DEFAULT NULL, tekst LONGTEXT DEFAULT NULL, datum DATE NOT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, INDEX IDX_70A8F1855DFA57A1 (deelnemer_id), INDEX IDX_70A8F1853D707F64 (medewerker_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');

        $this->addSql('ALTER TABLE uhk_deelnemers ADD CONSTRAINT FK_739D2F673C427B2F FOREIGN KEY (klant_id) REFERENCES klanten (id)');
        $this->addSql('ALTER TABLE uhk_deelnemers ADD CONSTRAINT FK_739D2F673D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
        $this->addSql('ALTER TABLE uhk_verslagen ADD CONSTRAINT FK_70A8F1855DFA57A1 FOREIGN KEY (deelnemer_id) REFERENCES uhk_deelnemers (id)');
        $this->addSql('ALTER TABLE uhk_verslagen ADD CONSTRAINT FK_70A8F1853D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE uhk_verslagen DROP FOREIGN KEY FK_70A8F1855DFA57A1');

        $this->addSql('DROP TABLE uhk_deelnemers');
        $this->addSql('DROP TABLE uhk_verslagen');
    }
}
