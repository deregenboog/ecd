<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250519142500 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        // Insert data into inloop_access_fields from intakes
        $this->addSql('INSERT INTO inloop_access_fields (
            klant_id,
            intake_datum,
            verblijfstatus_id,
            intakelocatie_id,
            toegang_inloophuis,
            overigen_toegang_van,
            gebruikersruimte_id
            )
            SELECT
            i.klant_id,
            i.datum_intake,
            i.verblijfstatus_id,
            i.locatie2_id,
            i.toegang_inloophuis,
            i.overigen_toegang_van,
            i.locatie1_id
            FROM intakes i
        ');

        // Update intakes.accessFields_id with the corresponding inloop_access_fields.id
        $this->addSql('UPDATE intakes i
            JOIN inloop_access_fields iaf
                ON i.klant_id = iaf.klant_id
                AND i.datum_intake = iaf.intake_datum
            SET i.accessFields_id = iaf.id
        ');

        // Migrate specifieke_locaties
        $this->addSql('INSERT INTO inloop_access_fields_locaties (accessfields_id, locatie_id) 
            (
                SELECT
                    la.intake_id,
                    la.locatie_id 
                FROM locaties_accessintakes la
                JOIN intakes i 
                    ON la.intake_id = i.id
                JOIN inloop_access_fields iaf 
                    ON i.accessFields_id = iaf.id
            )');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
    }
}
