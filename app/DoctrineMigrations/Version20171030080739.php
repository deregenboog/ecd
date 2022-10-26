<?php

namespace Application\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171030080739 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
      public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE werkgebieden (naam VARCHAR(255) NOT NULL, PRIMARY KEY(naam)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ggw_gebieden (naam VARCHAR(255) NOT NULL, PRIMARY KEY(naam)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE iz_doelstellingen ADD stadsdeel VARCHAR(255) DEFAULT NULL');

        $this->addSql('INSERT INTO werkgebieden (SELECT DISTINCT stadsdeel FROM postcodes)');
        $this->addSql('INSERT INTO ggw_gebieden (SELECT DISTINCT postcodegebied FROM postcodes)');

        $this->addSql('ALTER TABLE postcodes ADD CONSTRAINT FK_71DDD65DA13D3FD8 FOREIGN KEY (stadsdeel) REFERENCES werkgebieden (naam)');
        $this->addSql('ALTER TABLE postcodes ADD CONSTRAINT FK_71DDD65DFB02B9C2 FOREIGN KEY (postcodegebied) REFERENCES ggw_gebieden (naam)');
        $this->addSql('CREATE INDEX IDX_71DDD65DA13D3FD8 ON postcodes (stadsdeel)');
        $this->addSql('CREATE INDEX IDX_71DDD65DFB02B9C2 ON postcodes (postcodegebied)');
    }

    /**
     * @param Schema $schema
     */
     public function down(Schema $schema): void
    {
        $this->throwIrreversibleMigrationException();
    }
}
