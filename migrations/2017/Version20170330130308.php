<?php

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170330130308 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE odp_huurder_afsluitingen');
        $this->addSql('DROP TABLE odp_huurovereenkomst_afsluitingen');
        $this->addSql('DROP TABLE odp_verhuurder_afsluiting');
        $this->addSql('ALTER TABLE odp_huurovereenkomsten CHANGE afsluitdatum einddatum DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE odp_huurovereenkomsten ADD afsluitdatum DATE DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->throwIrreversibleMigrationException();
    }
}
