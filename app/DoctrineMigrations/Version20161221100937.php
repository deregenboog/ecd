<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161221100937 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' != $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE groepsactiviteiten_groepen ADD type VARCHAR(255) DEFAULT NULL');
        $this->addSql("UPDATE groepsactiviteiten_groepen
            SET type = 'Organisatie'
            WHERE naam LIKE 'Kantoor%'
        ");
        $this->addSql("UPDATE groepsactiviteiten_groepen
            SET type = 'Buurtmaatjes'
            WHERE naam LIKE 'Buurtmaatjes%'
        ");
        $this->addSql("UPDATE groepsactiviteiten_groepen
            SET type = 'Kwartiermaken'
            WHERE naam LIKE 'Eetclub%'
            OR naam LIKE 'Filmclub EYE Noord'
            OR naam LIKE '%Psy café Noord%'
        ");
        $this->addSql("UPDATE groepsactiviteiten_groepen
            SET type = 'OpenHuis'
            WHERE naam LIKE 'Kookgroep%'
            OR naam LIKE 'Kookploeg%'
            OR naam LIKE 'Koks%'
            OR naam LIKE 'Buurtboerderij%'
            OR naam LIKE 'Psy café Centrum bezoekers'
            OR naam LIKE 'Psy café Zuidoost bezoekers'
        ");
        $this->addSql("UPDATE groepsactiviteiten_groepen
            SET type = 'ErOpUit'
            WHERE naam LIKE 'Café Ponteneur bezoekers'
            OR naam LIKE 'EropUit Begeleiders'
            OR naam LIKE 'Extra EropUit Activiteiten'
            OR (naam LIKE 'Filmclub%' AND naam NOT LIKE 'Filmclub EYE Noord')
            OR naam LIKE 'Museumclub%'
            OR naam LIKE 'Overige uitjes%'
            OR naam LIKE 'Sportclub Marnix'
            OR naam LIKE 'Zwemclub Marnix'
            OR naam LIKE 'Toeleiding%'
            OR naam LIKE 'Zomeruitjes%'
        ");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->throwIrreversibleMigrationException();
    }
}
