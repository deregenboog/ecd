<?php

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170522185619 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE verslagen CHANGE klant_id klant_id INT DEFAULT NULL, CHANGE medewerker_id medewerker_id INT DEFAULT NULL');
        $this->addSql('UPDATE verslagen SET medewerker_id = NULL WHERE medewerker_id = 0');

        $this->addSql('CREATE INDEX IDX_2BBABA713C427B2F ON verslagen (klant_id)');
        $this->addSql('CREATE INDEX IDX_2BBABA713D707F64 ON verslagen (medewerker_id)');

        $this->addSql('ALTER TABLE verslagen ADD CONSTRAINT FK_2BBABA713C427B2F FOREIGN KEY (klant_id) REFERENCES klanten (id)');
        $this->addSql('ALTER TABLE verslagen ADD CONSTRAINT FK_2BBABA713D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');

        $this->addSql('DROP INDEX idx_klant ON verslagen');
    }

    public function down(Schema $schema): void
    {
        $this->throwIrreversibleMigrationException();
    }
}
