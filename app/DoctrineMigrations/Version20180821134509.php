<?php

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180821134509 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('DROP INDEX unique_naam_idx ON iz_hulpvraagsoorten');
        $this->addSql('DROP INDEX unique_naam_idx ON iz_doelgroepen');
    }

    public function down(Schema $schema): void
    {
        $this->throwIrreversibleMigrationException();
    }
}
