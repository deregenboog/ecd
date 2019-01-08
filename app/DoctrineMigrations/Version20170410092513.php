<?php

namespace Application\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170410092513 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql("ALTER TABLE vrijwilligers ADD vog_aangevraagd TINYINT(1) NOT NULL DEFAULT '0'");
        $this->addSql("ALTER TABLE vrijwilligers ADD vog_aanwezig TINYINT(1) NOT NULL DEFAULT '0'");
        $this->addSql("ALTER TABLE vrijwilligers ADD overeenkomst_aanwezig TINYINT(1) NOT NULL DEFAULT '0'");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->throwIrreversibleMigrationException();
    }
}
