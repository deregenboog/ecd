<?php

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170601130850 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE klanten ADD CONSTRAINT FK_F538C5BC1D103C3F FOREIGN KEY (laste_intake_id) REFERENCES intakes (id)');
        $this->addSql('CREATE INDEX IDX_F538C5BC3D707F64 ON klanten (medewerker_id)');
        $this->addSql('CREATE INDEX IDX_F538C5BC1994904A ON klanten (land_id)');
        $this->addSql('ALTER TABLE schorsingen ADD CONSTRAINT FK_9E658EBF3C427B2F FOREIGN KEY (klant_id) REFERENCES klanten (id)');
        $this->addSql('CREATE INDEX IDX_9E658EBF3C427B2F ON schorsingen (klant_id)');
        $this->addSql('DROP INDEX idx_schorsingen_klant_id ON schorsingen');
        $this->addSql('CREATE INDEX IDX_AB70F5AE3D707F64 ON intakes (medewerker_id)');
        $this->addSql('DROP INDEX idx_intakes_klant_id ON intakes');
        $this->addSql('CREATE INDEX IDX_AB70F5AE3C427B2F ON intakes (klant_id)');
        $this->addSql('ALTER TABLE iz_deelnemers CHANGE foreign_key foreign_key INT DEFAULT NULL');
        $this->addSql('CREATE INDEX IDX_89B5B51C7E366551 ON iz_deelnemers (foreign_key)');

        $this->addSql('UPDATE iz_deelnemers SET foreign_key = NULL WHERE foreign_key = 0');
    }

    public function down(Schema $schema): void
    {
        $this->throwIrreversibleMigrationException();
    }
}
