<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20161201111838 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->abortIf('mysql' != $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE iz_koppelingen ADD discr VARCHAR(255) NOT NULL');
        $this->addSql("UPDATE iz_koppelingen SET discr = 'hulpvraag' WHERE iz_deelnemer_id IN (SELECT id FROM iz_deelnemers WHERE model = 'Klant')");
        $this->addSql("UPDATE iz_koppelingen SET discr = 'hulpaanbod' WHERE iz_deelnemer_id IN (SELECT id FROM iz_deelnemers WHERE model = 'Vrijwilliger')");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->throwIrreversibleMigrationException();
    }
}
