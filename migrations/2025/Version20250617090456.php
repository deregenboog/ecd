<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250617090456 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'WLB locaties toevoegen';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("INSERT INTO `locaties` (`id`, `naam`, `nachtopvang`, `gebruikersruimte`, `datum_van`, `datum_tot`, `created`, `modified`, `maatschappelijkwerk`, `tbc_check`, `wachtlijst`) VALUES (NULL, 'WLB Zeeburg', '0', '0', '2025-07-01', NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, '0', '0', '0'), (NULL, '', '0', '0', '2025-05-01', NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, '0', '0', '0')");
        $this->addSql("INSERT INTO `locaties` (`id`, `naam`, `nachtopvang`, `gebruikersruimte`, `datum_van`, `datum_tot`, `created`, `modified`, `maatschappelijkwerk`, `tbc_check`, `wachtlijst`) VALUES (NULL, 'WLB Oud West', '0', '0', '2025-07-01', NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, '0', '0', '0'), (NULL, '', '0', '0', '2025-05-01', NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, '0', '0', '0')");
        $this->addSql("INSERT INTO `inloop_locatie_locatietype` (locatie_id,locatietype_id)
                    SELECT l.id, lt.id
                    FROM
                    locaties l, locatie_type lt
                    WHERE l.naam = 'WLB Zeeburg'
                    AND lt.naam = 'Inloop'"
        );
        $this->addSql("INSERT INTO `inloop_locatie_locatietype` (locatie_id,locatietype_id)
                    SELECT l.id, lt.id
                    FROM
                    locaties l, locatie_type lt
                    WHERE l.naam = 'WLB Oud West'
                    AND lt.naam = 'Inloop'"
        );
        $this->addSql("INSERT INTO `inloop_locatie_locatietype` (locatie_id,locatietype_id)
                    SELECT l.id, lt.id
                    FROM
                    locaties l, locatie_type lt
                    WHERE l.naam = 'WLB Zeeburg'
                    AND lt.naam = 'Maatschappelijk werk'"
        );
        $this->addSql("INSERT INTO `inloop_locatie_locatietype` (locatie_id,locatietype_id)
                    SELECT l.id, lt.id
                    FROM
                    locaties l, locatie_type lt
                    WHERE l.naam = 'WLB Oud West'
                    AND lt.naam = 'Maatschappelijk werk'"
        );

        $this->addSql("
                    INSERT INTO `locatie_tijden` (
                `id`, 
                `locatie_id`, 
                `dag_van_de_week`, 
                `sluitingstijd`, 
                `openingstijd`, 
                `created`, 
                `modified`
            ) 
            SELECT 
                NULL, 
                (SELECT id FROM `locaties` WHERE naam = 'WLB Zeeburg'), 
                dagen.dag_nummer, 
                '16:30', 
                '09:30', 
                CURRENT_TIMESTAMP, 
                CURRENT_TIMESTAMP
            FROM (
                SELECT 3 AS dag_nummer
            ) AS dagen;
        ");

        $this->addSql("
                    INSERT INTO `locatie_tijden` (
                `id`, 
                `locatie_id`, 
                `dag_van_de_week`, 
                `sluitingstijd`, 
                `openingstijd`, 
                `created`, 
                `modified`
            ) 
            SELECT 
                NULL, 
                (SELECT id FROM `locaties` WHERE naam = 'WLB Oud West'), 
                dagen.dag_nummer, 
                '18:00', 
                '11:00', 
                CURRENT_TIMESTAMP, 
                CURRENT_TIMESTAMP
            FROM (
                SELECT 2 AS dag_nummer
            ) AS dagen;
        ");

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
