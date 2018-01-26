<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180830124159 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('CREATE VIEW eropuit_klanten (id, klant_id, inschrijfdatum, uitschrijfdatum, uitschrijfreden_id, communicatie_email, communicatie_post, communicatie_telefoon, created, modified) AS
            SELECT lidmaatschap.id, lidmaatschap.klant_id, lidmaatschap.startdatum, lidmaatschap.einddatum, lidmaatschap.groepsactiviteiten_reden_id, lidmaatschap.communicatie_email, lidmaatschap.communicatie_post, lidmaatschap.communicatie_telefoon, lidmaatschap.created, lidmaatschap.modified
            FROM groepsactiviteiten_groepen_klanten lidmaatschap
            INNER JOIN klanten ON klanten.id = lidmaatschap.klant_id AND klanten.disabled = 0
            WHERE groepsactiviteiten_groep_id = 19
            AND lidmaatschap.id IN (
                SELECT MAX(id)
                FROM groepsactiviteiten_groepen_klanten
                WHERE groepsactiviteiten_groep_id = 19
                GROUP BY klant_id
            )
        ');

        $this->addSql('CREATE VIEW eropuit_vrijwilligers (id, vrijwilliger_id, inschrijfdatum, uitschrijfdatum, uitschrijfreden_id, communicatie_email, communicatie_post, communicatie_telefoon, created, modified) AS
            SELECT lidmaatschap.id, lidmaatschap.vrijwilliger_id, lidmaatschap.startdatum, lidmaatschap.einddatum, lidmaatschap.groepsactiviteiten_reden_id, lidmaatschap.communicatie_email, lidmaatschap.communicatie_post, lidmaatschap.communicatie_telefoon, lidmaatschap.created, lidmaatschap.modified
            FROM groepsactiviteiten_groepen_vrijwilligers lidmaatschap
            INNER JOIN vrijwilligers ON vrijwilligers.id = lidmaatschap.vrijwilliger_id AND vrijwilligers.disabled = 0
            WHERE groepsactiviteiten_groep_id = 19 /* Er Op Uit Kalender */
            AND lidmaatschap.id IN (
                SELECT MAX(id)
                FROM groepsactiviteiten_groepen_vrijwilligers
                WHERE groepsactiviteiten_groep_id = 19
                GROUP BY vrijwilliger_id
            )
        ');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->throwIrreversibleMigrationException();
    }
}
