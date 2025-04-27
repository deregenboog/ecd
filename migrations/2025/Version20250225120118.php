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

        // Create new table
        $this->addSql('CREATE TABLE inloop_access_fields (
            id INT AUTO_INCREMENT NOT NULL,
            klant_id INT NOT NULL,
            intake_datum DATE DEFAULT NULL,
            verblijfstatus_id INT DEFAULT NULL,
            intakelocatie_id INT DEFAULT NULL,
            toegang_inloophuis TINYINT(1) DEFAULT NULL,
            overigen_toegang_van DATE DEFAULT NULL,
            gebruikersruimte_id INT DEFAULT NULL,
            created DATETIME NOT NULL,
            modified DATETIME NOT NULL,
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');

        // Create junction table for specifiekeLocaties
        $this->addSql('CREATE TABLE inloop_access_fields_locaties (
            access_fields_id INT NOT NULL,
            locatie_id INT NOT NULL,
            PRIMARY KEY(access_fields_id, locatie_id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');

        // Add foreign key constraints
        $this->addSql('ALTER TABLE inloop_access_fields ADD CONSTRAINT FK_INLOOP_ACCESS_FIELDS_KLANT FOREIGN KEY (klant_id) REFERENCES klanten (id)');
        $this->addSql('ALTER TABLE inloop_access_fields ADD CONSTRAINT FK_INLOOP_ACCESS_FIELDS_VERBLIJFSTATUS FOREIGN KEY (verblijfstatus_id) REFERENCES verblijfstatussen (id)');
        $this->addSql('ALTER TABLE inloop_access_fields ADD CONSTRAINT FK_INLOOP_ACCESS_FIELDS_INTAKELOCATIE FOREIGN KEY (intakelocatie_id) REFERENCES locaties (id)');
        $this->addSql('ALTER TABLE inloop_access_fields ADD CONSTRAINT FK_INLOOP_ACCESS_FIELDS_GEBRUIKERSRUIMTE FOREIGN KEY (gebruikersruimte_id) REFERENCES locaties (id)');

        $this->addSql('ALTER TABLE inloop_access_fields_locaties ADD CONSTRAINT FK_INLOOP_ACCESS_FIELDS_LOCATIES_ACCESS FOREIGN KEY (access_fields_id) REFERENCES inloop_access_fields (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE inloop_access_fields_locaties ADD CONSTRAINT FK_INLOOP_ACCESS_FIELDS_LOCATIES_LOCATIE FOREIGN KEY (locatie_id) REFERENCES locaties (id) ON DELETE CASCADE');

        // Create unique index for one-to-one relation with klant
        $this->addSql('CREATE UNIQUE INDEX UNIQ_INLOOP_ACCESS_FIELDS_KLANT ON inloop_access_fields (klant_id)');

        // Migrate data from the earliest intake for each klant
        $this->addSql('INSERT INTO inloop_access_fields (
            klant_id,
            intake_datum,
            verblijfstatus_id,
            intakelocatie_id,
            toegang_inloophuis,
            overigen_toegang_van,
            gebruikersruimte_id,
            created,
            modified
        )
        SELECT
            i.klant_id,
            i.datum_intake,
            i.verblijfstatus_id,
            i.locatie2_id,
            i.toegang_inloophuis,
            i.overigen_toegang_van,
            i.locatie1_id,
            i.created,
            i.modified
        FROM intakes i
        INNER JOIN (
            SELECT klant_id, MIN(datum_intake) as first_intake_date
            FROM intakes
            GROUP BY klant_id
        ) first_intakes ON i.klant_id = first_intakes.klant_id
            AND i.datum_intake = first_intakes.first_intake_date');

        // Migrate specifieke_locaties
        $this->addSql('INSERT INTO inloop_access_fields_locaties (access_fields_id, locatie_id)
            SELECT af.id, l.locatie_id
            FROM inloop_access_fields af
            JOIN locaties_accessintakes l ON l.intake_id = (
                SELECT id FROM intakes i2
                WHERE i2.klant_id = af.klant_id
                ORDER BY i2.datum_intake ASC
                LIMIT 1
            )');
    }

    public function down(Schema $schema): void
    {
        // Drop junction table first due to foreign key constraints
        $this->addSql('DROP TABLE inloop_access_fields_locaties');

        // Drop main table
        $this->addSql('DROP TABLE inloop_access_fields');
    }
}
