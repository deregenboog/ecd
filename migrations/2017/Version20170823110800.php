<?php

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170823110800 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE odp_deelnemers ADD automatischeIncasso TINYINT(1) DEFAULT NULL');
        $this->addSql('UPDATE odp_deelnemers SET automatischeIncasso = 1 WHERE startdatumAutomatischeIncasso IS NOT NULL');
        $this->addSql('ALTER TABLE odp_deelnemers DROP startdatumAutomatischeIncasso');
    }

    public function down(Schema $schema): void
    {
        $this->throwIrreversibleMigrationException();
    }
}
