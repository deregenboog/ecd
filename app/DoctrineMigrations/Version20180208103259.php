<?php

namespace Application\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180208103259 extends AbstractMigration
{
    /**
     * Backup values of non-existing "klanten" and "locaties" and add fk-constraints.
     *
     * Non-existing "klanten" and "locaties":
     * - SELECT r.klant_id, k.id FROM registraties r LEFT JOIN klanten k ON r.klant_id = k.id WHERE k.id IS NULL
     * - SELECT r.locatie_id, l.id FROM registraties r LEFT JOIN locaties l ON r.locatie_id = l.id WHERE l.id IS NULL
     *
     * @param Schema $schema
     */
      public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE registraties
            ADD klant_id_before_constraint INT DEFAULT NULL,
            ADD locatie_id_before_constraint INT DEFAULT NULL');

        $this->addSql('UPDATE registraties r
            LEFT JOIN klanten k ON r.klant_id = k.id
            SET r.klant_id_before_constraint = r.klant_id
            WHERE k.id IS NULL');

        $this->addSql('UPDATE registraties r
            LEFT JOIN locaties l ON r.locatie_id = l.id
            SET r.locatie_id_before_constraint = r.locatie_id
            WHERE l.id IS NULL');

        $this->addSql('ALTER TABLE registraties
            CHANGE klant_id klant_id INT DEFAULT NULL,
            CHANGE locatie_id locatie_id INT DEFAULT NULL');

        $this->addSql('UPDATE registraties r
            LEFT JOIN klanten k ON r.klant_id = k.id
            SET r.klant_id = NULL
            WHERE k.id IS NULL');

        $this->addSql('UPDATE registraties r
            LEFT JOIN locaties l ON r.locatie_id = l.id
            SET r.locatie_id = NULL
            WHERE l.id IS NULL');

        $this->addSql('ALTER TABLE registraties ADD CONSTRAINT FK_FB4123F44947630C FOREIGN KEY (locatie_id) REFERENCES locaties (id)');
        $this->addSql('ALTER TABLE registraties ADD CONSTRAINT FK_FB4123F43C427B2F FOREIGN KEY (klant_id) REFERENCES klanten (id)');
        $this->addSql('CREATE INDEX IDX_FB4123F44947630C ON registraties (locatie_id)');
    }

    /**
     * @param Schema $schema
     */
     public function down(Schema $schema): void
    {
        $this->throwIrreversibleMigrationException();
    }
}
