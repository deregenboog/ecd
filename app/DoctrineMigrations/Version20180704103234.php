<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180704103234 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE iz_deelnemers_iz_intervisiegroepen DROP FOREIGN KEY FK_3A903EEF495B2A54');
        $this->addSql('DROP TABLE iz_deelnemers_iz_intervisiegroepen');
        $this->addSql('DROP TABLE iz_intervisiegroepen');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE iz_deelnemers_iz_intervisiegroepen (id INT AUTO_INCREMENT NOT NULL, iz_intervisiegroep_id INT DEFAULT NULL, iz_deelnemer_id INT DEFAULT NULL, INDEX IDX_3A903EEF495B2A54 (iz_intervisiegroep_id), INDEX IDX_3A903EEFD3124B3F (iz_deelnemer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE iz_intervisiegroepen (id INT AUTO_INCREMENT NOT NULL, medewerker_id INT DEFAULT NULL, startdatum DATE NOT NULL, einddatum DATE NOT NULL, naam VARCHAR(255) NOT NULL COLLATE utf8_general_ci, created DATETIME NOT NULL, modified DATETIME NOT NULL, INDEX IDX_86CA227E3D707F64 (medewerker_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE iz_deelnemers_iz_intervisiegroepen ADD CONSTRAINT FK_3A903EEF495B2A54 FOREIGN KEY (iz_intervisiegroep_id) REFERENCES iz_intervisiegroepen (id)');
        $this->addSql('ALTER TABLE iz_deelnemers_iz_intervisiegroepen ADD CONSTRAINT FK_3A903EEFD3124B3F FOREIGN KEY (iz_deelnemer_id) REFERENCES iz_deelnemers (id)');
        $this->addSql('ALTER TABLE iz_intervisiegroepen ADD CONSTRAINT FK_86CA227E3D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
    }
}
