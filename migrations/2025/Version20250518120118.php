<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250225120118 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create inloop_access_fields table and migrate data from intakes';
    }

    public function up(Schema $schema): void
    {

        // // Create new table
        
        // // Migrate data from the earliest intake for each klant
        // $this->addSql('INSERT INTO inloop_access_fields (
        //     klant_id,
        //     intake_datum,
        //     verblijfstatus_id,
        //     intakelocatie_id,
        //     toegang_inloophuis,
        //     overigen_toegang_van,
        //     gebruikersruimte_id,
        //     created,
        //     modified
        // )
        // SELECT
        //     i.klant_id,
        //     i.datum_intake,
        //     i.verblijfstatus_id,
        //     i.locatie2_id,
        //     i.toegang_inloophuis,
        //     i.overigen_toegang_van,
        //     i.locatie1_id,
        //     i.created,
        //     i.modified
        // FROM intakes i
        // INNER JOIN (
        //     SELECT klant_id, MIN(datum_intake) as first_intake_date
        //     FROM intakes
        //     GROUP BY klant_id
        // ) first_intakes ON i.klant_id = first_intakes.klant_id
        //     AND i.datum_intake = first_intakes.first_intake_date');

        // // Migrate specifieke_locaties
        // $this->addSql('INSERT INTO inloop_access_fields_locaties (accessfields_id, locatie_id)
        //     SELECT af.id, l.locatie_id
        //     FROM inloop_access_fields af
        //     JOIN locaties_accessintakes l ON l.intake_id = (
        //         SELECT id FROM intakes i2
        //         WHERE i2.klant_id = af.klant_id
        //         ORDER BY i2.datum_intake ASC
        //         LIMIT 1
        //     )');
    }

    public function down(Schema $schema): void
    {
        // // Drop junction table first due to foreign key constraints
        // $this->addSql('DROP TABLE inloop_access_fields_locaties');

        // // Drop main table
        // $this->addSql('DROP TABLE inloop_access_fields');
    }
}
