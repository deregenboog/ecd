<?php

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170410092513 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql("ALTER TABLE vrijwilligers ADD vog_aangevraagd TINYINT(1) NOT NULL DEFAULT '0'");
        $this->addSql("ALTER TABLE vrijwilligers ADD vog_aanwezig TINYINT(1) NOT NULL DEFAULT '0'");
        $this->addSql("ALTER TABLE vrijwilligers ADD overeenkomst_aanwezig TINYINT(1) NOT NULL DEFAULT '0'");
    }

    public function down(Schema $schema): void
    {
        $this->throwIrreversibleMigrationException();
    }
}
