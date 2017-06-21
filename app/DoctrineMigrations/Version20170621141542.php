<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170621141542 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE inloop_dossier_statussen ADD land_id INT DEFAULT NULL, CHANGE reden_id reden_id INT NOT NULL');
        $this->addSql('ALTER TABLE inloop_dossier_statussen ADD CONSTRAINT FK_12D2B5701994904A FOREIGN KEY (land_id) REFERENCES landen (id)');
        $this->addSql('CREATE INDEX IDX_12D2B5701994904A ON inloop_dossier_statussen (land_id)');
        $this->addSql('ALTER TABLE inloop_afsluiting_redenen ADD land TINYINT(1) NOT NULL, CHANGE gewicht gewicht INT NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->throwIrreversibleMigrationException();
    }
}
