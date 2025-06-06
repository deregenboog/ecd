<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250502100542 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Flierbos locatie';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("INSERT INTO `locaties` (`id`, `naam`, `nachtopvang`, `gebruikersruimte`, `datum_van`, `datum_tot`, `created`, `modified`, `maatschappelijkwerk`, `tbc_check`, `wachtlijst`) VALUES (NULL, 'Flierbos', '0', '0', '2025-05-01', NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, '0', '0', '0'), (NULL, '', '0', '0', '2025-05-01', NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, '0', '0', '0')");
        $this->addSql("INSERT INTO `inloop_locatie_locatietype` (locatie_id,locatietype_id)
                    SELECT l.id, lt.id
                    FROM
                    locaties l, locatie_type lt
                    WHERE l.naam = 'Flierbos'
                    AND lt.naam = 'Inloop'"
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
                (SELECT id FROM `locaties` WHERE naam = 'Flierbos'), 
                dagen.dag_nummer, 
                '18:00', 
                '11:00', 
                CURRENT_TIMESTAMP, 
                CURRENT_TIMESTAMP
            FROM (
                SELECT 0 AS dag_nummer UNION ALL
                SELECT 1 UNION ALL
                SELECT 2 UNION ALL
                SELECT 3 UNION ALL
                SELECT 4 UNION ALL
                SELECT 5 UNION ALL
                SELECT 6
            ) AS dagen;
        ");


    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
