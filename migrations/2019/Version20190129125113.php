<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190129125113 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE intakes ADD medewerker_id_before_constraint INT DEFAULT NULL');
        $this->addSql('UPDATE intakes
            SET medewerker_id_before_constraint = medewerker_id, medewerker_id = NULL
            WHERE medewerker_id NOT IN (SELECT id FROM medewerkers)');
        $this->addSql('ALTER TABLE intakes ADD CONSTRAINT FK_AB70F5AE3D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');

        $this->addSql('ALTER TABLE intakes ADD klant_id_before_constraint INT DEFAULT NULL');
        $this->addSql('UPDATE intakes
            SET klant_id_before_constraint = klant_id, klant_id = NULL
            WHERE klant_id NOT IN (SELECT id FROM klanten)');
        $this->addSql('ALTER TABLE intakes ADD CONSTRAINT FK_AB70F5AE3C427B2F FOREIGN KEY (klant_id) REFERENCES klanten (id)');

        $this->addSql('ALTER TABLE intakes ADD woonsituatie_id_before_constraint INT DEFAULT NULL');
        $this->addSql('UPDATE intakes
            SET woonsituatie_id_before_constraint = woonsituatie_id, woonsituatie_id = NULL
            WHERE woonsituatie_id NOT IN (SELECT id FROM woonsituaties)');
        $this->addSql('ALTER TABLE intakes ADD CONSTRAINT FK_AB70F5AEC7424D3F FOREIGN KEY (woonsituatie_id) REFERENCES woonsituaties (id)');

        $this->addSql('UPDATE klanten
            SET laatste_registratie_id = NULL
            WHERE laatste_registratie_id NOT IN (SELECT id FROM registraties)');
        $this->addSql('ALTER TABLE klanten ADD CONSTRAINT FK_F538C5BC815E1ED FOREIGN KEY (laatste_registratie_id) REFERENCES registraties (id)');

        $this->addSql('DELETE FROM inkomens_intakes WHERE intake_id NOT IN (SELECT id FROM intakes)');
        $this->addSql('ALTER TABLE inkomens_intakes ADD CONSTRAINT FK_66CE79C0733DE450 FOREIGN KEY (intake_id) REFERENCES intakes (id) ON DELETE CASCADE');

        $this->addSql('DELETE FROM instanties_intakes WHERE intake_id NOT IN (SELECT id FROM intakes)');
        $this->addSql('ALTER TABLE instanties_intakes ADD CONSTRAINT FK_9D745955733DE450 FOREIGN KEY (intake_id) REFERENCES intakes (id) ON DELETE CASCADE');

        $this->addSql('UPDATE iz_koppelingen SET iz_vraagaanbod_id = NULL WHERE iz_vraagaanbod_id NOT IN (SELECT id FROM iz_vraagaanboden)');
        $this->addSql('ALTER TABLE iz_koppelingen ADD CONSTRAINT FK_24E5FDC2C217B9F FOREIGN KEY (iz_vraagaanbod_id) REFERENCES iz_vraagaanboden (id)');

        $this->addSql('UPDATE iz_koppelingen AS hulpvraag
            LEFT JOIN iz_koppelingen AS hulpaanbod ON hulpvraag.iz_koppeling_id = hulpaanbod.id
            SET hulpvraag.iz_koppeling_id = NULL
            WHERE hulpaanbod.id IS NULL');
        $this->addSql('ALTER TABLE iz_koppelingen ADD CONSTRAINT FK_24E5FDC28B2EFA2C FOREIGN KEY (iz_koppeling_id) REFERENCES iz_koppelingen (id)');
    }

    public function down(Schema $schema): void
    {
        $this->throwIrreversibleMigrationException();
    }
}
