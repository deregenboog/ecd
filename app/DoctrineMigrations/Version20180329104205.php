<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180329104205 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE iz_koppelingen ADD expat TINYINT(1) DEFAULT NULL');

        // fill hulpvraag.geschiktVoorExpat
        $this->addSql("UPDATE iz_koppelingen SET expat = 1 WHERE discr = 'hulpvraag' AND spreekt_nederlands = 0");

        // fill hulpaanbod.expat
        $this->addSql("UPDATE iz_koppelingen SET expat = 1 WHERE discr = 'hulpaanbod' AND voorkeur_voor_nederlands = 0");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->throwIrreversibleMigrationException();
    }
}
