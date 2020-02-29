<?php

namespace Application\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170426144554 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql("UPDATE iz_deelnemers SET contact_ontstaan = NULL WHERE contact_ontstaan = ''");
        $this->addSql("UPDATE iz_deelnemers SET binnengekomen_via = NULL WHERE binnengekomen_via = ''");

        $this->addSql('ALTER TABLE iz_deelnemers CHANGE binnengekomen_via binnengekomen_via INT DEFAULT NULL');
        $this->addSql('ALTER TABLE iz_deelnemers CHANGE contact_ontstaan contact_ontstaan INT DEFAULT NULL');

        $this->addSql('ALTER TABLE iz_deelnemers ADD CONSTRAINT FK_89B5B51C782093FC FOREIGN KEY (contact_ontstaan) REFERENCES iz_ontstaan_contacten (id)');
        $this->addSql('ALTER TABLE iz_deelnemers ADD CONSTRAINT FK_89B5B51CF0A6F57E FOREIGN KEY (binnengekomen_via) REFERENCES iz_via_personen (id)');

        $this->addSql('CREATE INDEX IDX_89B5B51C782093FC ON iz_deelnemers (contact_ontstaan)');
        $this->addSql('CREATE INDEX IDX_89B5B51CF0A6F57E ON iz_deelnemers (binnengekomen_via)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->throwIrreversibleMigrationException();
    }
}
