<?php

namespace Application\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171106165428 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE postcodes CHANGE postcodegebied postcodegebied VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE hs_klanten ADD postcodegebied VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE hs_klanten ADD CONSTRAINT FK_CC6284A46708ED5 FOREIGN KEY (werkgebied) REFERENCES werkgebieden (naam)');
        $this->addSql('ALTER TABLE hs_klanten ADD CONSTRAINT FK_CC6284AFB02B9C2 FOREIGN KEY (postcodegebied) REFERENCES ggw_gebieden (naam)');
        $this->addSql('CREATE INDEX IDX_CC6284A46708ED5 ON hs_klanten (werkgebied)');
        $this->addSql('CREATE INDEX IDX_CC6284AFB02B9C2 ON hs_klanten (postcodegebied)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->throwIrreversibleMigrationException();
    }
}
