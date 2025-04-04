<?php

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20181106110520 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE klanten ADD deleted DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE vrijwilligers ADD deleted DATETIME DEFAULT NULL');

        $this->addSql('UPDATE klanten SET deleted = modified WHERE disabled = 1');
        $this->addSql('UPDATE vrijwilligers SET deleted = modified WHERE disabled = 1');
    }

    public function down(Schema $schema): void
    {
        $this->throwIrreversibleMigrationException();
    }
}
