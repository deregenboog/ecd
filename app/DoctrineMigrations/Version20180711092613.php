<?php

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180711092613 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE iz_deelnemers_documenten DROP FOREIGN KEY FK_66AE504FC33F7837');
        $this->addSql('ALTER TABLE iz_deelnemers_documenten ADD CONSTRAINT FK_66AE504FC33F7837 FOREIGN KEY (document_id) REFERENCES iz_documenten (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        $this->throwIrreversibleMigrationException();
    }
}
