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
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE groepsactiviteiten_groepen ADD type VARCHAR(255) DEFAULT NULL');
        $this->addSql("UPDATE groepsactiviteiten_groepen
            SET type = 'ErOpUit'
            WHERE naam NOT LIKE 'Kookgroep%'
            AND naam NOT LIKE 'Buurtmaatjes%'
            AND naam NOT LIKE 'ErOpUit Kalender'
            AND naam NOT LIKE 'Kantoorvrijwilligers'
            AND naam NOT LIKE 'Eetclub Noord%'
            AND naam NOT LIKE 'Psy café Centrum%'
            AND naam NOT LIKE 'Psy café Noord%'
        ");
        $this->addSql("UPDATE groepsactiviteiten_groepen
            SET type = 'OpenHuis'
            WHERE type IS NULL
            AND naam LIKE 'Kookgroep%'
            AND naam NOT LIKE '%Psy%'
        ");
        $this->addSql("UPDATE groepsactiviteiten_groepen
            SET type = 'Buurtmaatjes'
            WHERE type IS NULL
            AND (
                naam LIKE 'Buurtmaatjes%'
                OR naam LIKE 'Eetclub%'
                OR naam LIKE '%Psy%'
            )
        ");
        $this->addSql("UPDATE groepsactiviteiten_groepen
            SET type = 'Organisatie'
            WHERE type IS NULL
        ");
        $this->addSql('ALTER TABLE groepsactiviteiten_groepen CHANGE type type VARCHAR(255) NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE groepsactiviteiten_groepen DROP type');
    }
}
