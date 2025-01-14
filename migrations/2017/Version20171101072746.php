<?php

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171101072746 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX unique_project_jaar_idx ON iz_doelstellingen');
        $this->addSql('ALTER TABLE iz_doelstellingen ADD CONSTRAINT FK_D76C6C73A13D3FD8 FOREIGN KEY (stadsdeel) REFERENCES werkgebieden (naam)');
        $this->addSql('CREATE INDEX IDX_D76C6C73A13D3FD8 ON iz_doelstellingen (stadsdeel)');
        $this->addSql('CREATE UNIQUE INDEX unique_project_jaar_stadsdeel_idx ON iz_doelstellingen (project_id, jaar, stadsdeel)');
    }

    public function down(Schema $schema): void
    {
        $this->throwIrreversibleMigrationException();
    }
}
