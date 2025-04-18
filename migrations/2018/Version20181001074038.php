<?php

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20181001074038 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql("ALTER TABLE medewerkers
            ADD ldap_groups LONGTEXT DEFAULT NULL COMMENT '(DC2Type:json)',
            ADD roles LONGTEXT NOT NULL COMMENT '(DC2Type:json)',
            CHANGE uidnumber uidnumber VARCHAR(255) NOT NULL");
    }

    public function down(Schema $schema): void
    {
        $this->throwIrreversibleMigrationException();
    }
}
