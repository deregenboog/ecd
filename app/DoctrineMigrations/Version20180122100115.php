<?php

namespace Application\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180122100115 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE inloop_vrijwilliger_locatie (vrijwilliger_id INT NOT NULL, locatie_id INT NOT NULL, INDEX IDX_A1776D9F76B43BDC (vrijwilliger_id), INDEX IDX_A1776D9F4947630C (locatie_id), PRIMARY KEY(vrijwilliger_id, locatie_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE inloop_binnen_via (id INT AUTO_INCREMENT NOT NULL, naam VARCHAR(255) NOT NULL, active TINYINT(1) NOT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE inloop_vrijwilliger_locatie ADD CONSTRAINT FK_A1776D9F76B43BDC FOREIGN KEY (vrijwilliger_id) REFERENCES inloop_vrijwilligers (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE inloop_vrijwilliger_locatie ADD CONSTRAINT FK_A1776D9F4947630C FOREIGN KEY (locatie_id) REFERENCES locaties (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE inloop_vrijwilligers ADD aanmelddatum DATE NOT NULL, ADD binnen_via_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE inloop_vrijwilligers ADD CONSTRAINT FK_56110480D8471945 FOREIGN KEY (binnen_via_id) REFERENCES inloop_binnen_via (id)');
        $this->addSql('CREATE INDEX IDX_56110480D8471945 ON inloop_vrijwilligers (binnen_via_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->throwIrreversibleMigrationException();
    }
}
