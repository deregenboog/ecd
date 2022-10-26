<?php

namespace Application\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171115185650 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
      public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql("UPDATE postcodes SET postcodegebied = NULL WHERE postcodegebied = ''");
        $this->addSql("DELETE FROM ggw_gebieden WHERE naam = ''");
    }

    /**
     * @param Schema $schema
     */
     public function down(Schema $schema): void
    {
        $this->throwIrreversibleMigrationException();
    }
}
