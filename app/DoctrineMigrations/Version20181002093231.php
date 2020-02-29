<?php

namespace Application\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20181002093231 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE mw_documenten (id INT AUTO_INCREMENT NOT NULL, klant_id INT DEFAULT NULL, medewerker_id INT NULL, naam VARCHAR(255) NOT NULL, filename VARCHAR(255) NOT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, INDEX IDX_99E478283C427B2F (klant_id), INDEX IDX_99E478283D707F64 (medewerker_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mw_documenten ADD CONSTRAINT FK_99E478283C427B2F FOREIGN KEY (klant_id) REFERENCES klanten (id)');
        $this->addSql('ALTER TABLE mw_documenten ADD CONSTRAINT FK_99E478283D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');

        $this->addSql("INSERT INTO mw_documenten (klant_id, medewerker_id, naam, filename, created, modified)
            SELECT attachments.foreign_key, attachments.user_id, attachments.title, attachments.basename, attachments.created, attachments.modified
            FROM attachments
            INNER JOIN klanten ON attachments.foreign_key = klanten.id
                AND attachments.model = 'Klant'
            WHERE `group` = 'mw'
                AND is_active = 1
        ");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->throwIrreversibleMigrationException();
    }
}
