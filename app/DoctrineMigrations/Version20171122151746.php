<?php

namespace Application\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171122151746 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE hs_klanten DROP onHold, DROP roepnaam, CHANGE achternaam achternaam VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE hs_arbeiders ADD actief TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE hs_facturen ADD locked TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE hs_memos ADD onderwerp VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE hs_klussen ADD status VARCHAR(255) NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->throwIrreversibleMigrationException();
    }
}
